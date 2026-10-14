<?php
/**
 * GitHub OAuth 2.0 Controller
 * Implements the "Sign in with GitHub" authorization-code flow without external libraries.
 *
 * Flow:
 *   1) redirect() -> send the user to GitHub's consent screen with a CSRF-safe `state`.
 *   2) callback() -> validate `state`, exchange the code for a token, read the verified
 *                    profile + email, then link/create a local `users` row and log them in.
 *
 * The exact redirect_uri sent to GitHub is derived from BASE_URL so it always matches the
 * route registered in App.php: /auth/github/callback
 */
class GithubAuthController extends Controller {

    /**
     * Step 1: Kick off the GitHub sign-in flow.
     */
    public function redirect() {
        if (!$this->isConfigured()) {
            $this->session->flash('error', 'GitHub Sign-In is not configured on this server yet.');
            redirect('/login');
            return;
        }

        $this->ensureGithubColumn();

        $state = bin2hex(random_bytes(16));
        $this->session->set('github_oauth_state', $state);

        $params = [
            'client_id'    => GITHUB_CLIENT_ID,
            'redirect_uri' => $this->redirectUri(),
            'scope'        => 'read:user user:email',
            'state'        => $state,
        ];

        header('Location: ' . GITHUB_OAUTH_AUTH_ENDPOINT . '?' . http_build_query($params));
        exit;
    }

    /**
     * Step 2: Handle GitHub's callback.
     */
    public function callback() {
        if (!empty($_GET['error'])) {
            $this->session->flash('error', 'GitHub sign-in was cancelled or returned an error: ' . sanitize($_GET['error']));
            redirect('/login');
            return;
        }

        $code  = $_GET['code'] ?? '';
        $state = $_GET['state'] ?? '';

        $expected = $this->session->get('github_oauth_state');
        $this->session->remove('github_oauth_state');
        if (!$expected || !$state || !hash_equals((string)$expected, (string)$state)) {
            $this->session->flash('error', 'Security check failed during GitHub sign-in. Please try again.');
            redirect('/login');
            return;
        }

        if (!$code || !$this->isConfigured()) {
            $this->session->flash('error', 'Invalid GitHub sign-in request.');
            redirect('/login');
            return;
        }

        // Exchange the authorization code for an access token (GitHub returns JSON when Accept: application/json).
        $tokenRes = $this->httpPost(GITHUB_OAUTH_TOKEN_ENDPOINT, [
            'client_id'     => GITHUB_CLIENT_ID,
            'client_secret' => GITHUB_CLIENT_SECRET,
            'code'          => $code,
            'redirect_uri'  => $this->redirectUri(),
        ]);

        $accessToken = $tokenRes['access_token'] ?? null;
        if (!$accessToken) {
            error_log('GitHub OAuth token exchange failed: ' . json_encode($tokenRes));
            $this->session->flash('error', 'Could not complete GitHub sign-in. Please try again.');
            redirect('/login');
            return;
        }

        // Fetch the profile.
        $profile = $this->httpGetJson(GITHUB_OAUTH_USER_ENDPOINT, 'Bearer ' . $accessToken);
        $githubId = $profile['id'] ?? null;
        if (!$githubId) {
            $this->session->flash('error', 'GitHub did not return a valid account identifier.');
            redirect('/login');
            return;
        }

        // Resolve a verified email (the profile email can be null or unverified).
        $email = $this->resolveVerifiedEmail($accessToken, $profile);
        if (!$email) {
            $this->session->flash('error', 'We could not get a verified email from your GitHub account. Add & verify one in GitHub, then try again.');
            redirect('/login');
            return;
        }
        $email = strtolower(trim($email));

        // Optional domain allowlist (comma-separated).
        if (GITHUB_ALLOWED_DOMAIN !== '') {
            $allowed = array_map('strtolower', array_map('trim', explode(',', GITHUB_ALLOWED_DOMAIN)));
            $domain = strtolower(substr(strrchr($email, '@'), 1));
            if (!in_array($domain, $allowed, true)) {
                $this->session->flash('error', 'Sign-in is restricted to the following domains: ' . GITHUB_ALLOWED_DOMAIN . '.');
                redirect('/login');
                return;
            }
        }

        try {
            $user = $this->findOrCreateUser((string)$githubId, $email, $profile);
        } catch (\Throwable $e) {
            error_log('GitHub OAuth user provisioning error: ' . $e->getMessage());
            $this->session->flash('error', 'Something went wrong preparing your account. Please try again.');
            redirect('/login');
            return;
        }

        if (!$user) {
            $this->session->flash('error', 'Your account is not active. Please contact an administrator.');
            redirect('/login');
            return;
        }

        if (!$this->auth->loginUser($user, 'GitHub')) {
            $this->session->flash('error', 'Your account has been suspended.');
            redirect('/login');
            return;
        }

        $this->redirectWithSuccess('/dashboard', 'Welcome, ' . $user['first_name'] . '! You are signed in with GitHub.');
    }

    // ---------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------

    private function isConfigured() {
        return GITHUB_CLIENT_ID !== '' && GITHUB_CLIENT_SECRET !== '';
    }

    private function redirectUri() {
        return url('/auth/github/callback');
    }

    private function ensureGithubColumn() {
        try {
            $cols = $this->db->fetchAll("SHOW COLUMNS FROM `users` LIKE 'github_id'");
            if (empty($cols)) {
                $this->db->execute("ALTER TABLE `users` ADD COLUMN `github_id` VARCHAR(64) DEFAULT NULL AFTER `email`");
                $this->db->execute("ALTER TABLE `users` ADD UNIQUE KEY `github_id` (`github_id`)");
            }
        } catch (\Throwable $e) {
            error_log('ensureGithubColumn: ' . $e->getMessage());
        }
    }

    /**
     * Prefer a primary verified email from /user/emails; fall back to a verified profile email.
     */
    private function resolveVerifiedEmail($accessToken, array $profile) {
        $emails = $this->httpGetJson(GITHUB_OAUTH_EMAILS_ENDPOINT, 'Bearer ' . $accessToken);
        if (is_array($emails) && !empty($emails)) {
            $primary = null;
            $firstVerified = null;
            foreach ($emails as $em) {
                if (!empty($em['verified'])) {
                    if (!empty($em['primary'])) {
                        $primary = $em['email'] ?? $primary;
                    }
                    $firstVerified = $firstVerified ?: ($em['email'] ?? null);
                }
            }
            if ($primary) return $primary;
            if ($firstVerified) return $firstVerified;
        }
        // Fall back to the profile email only when GitHub marks it public.
        if (!empty($profile['email'])) {
            return $profile['email'];
        }
        return null;
    }

    /**
     * Locate a local user by GitHub ID, else by email (link), else create a fresh employee.
     */
    private function findOrCreateUser($githubId, $email, array $profile) {
        $email    = strtolower(trim($email));
        $githubId = (string)$githubId;

        $user = $this->db->fetch("SELECT * FROM users WHERE github_id = ? LIMIT 1", [$githubId]);
        if ($user) {
            return $user['status'] === 'active' ? $user : null;
        }

        $full  = trim($profile['name'] ?? '') ?: trim($profile['login'] ?? '');
        $parts = preg_split('/\s+/', $full);
        $first = $parts[0] ?? explode('@', $email)[0];
        $last  = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : 'User';
        $avatar = $profile['avatar_url'] ?? null;

        $user = $this->db->fetch("SELECT * FROM users WHERE email = ? LIMIT 1", [$email]);
        if ($user) {
            $this->db->execute("UPDATE users SET github_id = ?, avatar = COALESCE(avatar, ?) WHERE id = ?", [$githubId, $avatar, $user['id']]);
            $user['github_id'] = $githubId;
            return $user['status'] === 'active' ? $user : null;
        }

        $this->ensureGithubColumn();
        $employeeId = $this->generateEmployeeId();
        $randPass   = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT, ['cost' => BCRYPT_ROUNDS]);

        $newId = $this->db->insert(
            "INSERT INTO users (employee_id, first_name, last_name, email, github_id, password, role, avatar, status)
             VALUES (?, ?, ?, ?, ?, ?, 'employee', ?, 'active')",
            [$employeeId, $first, $last, $email, $githubId, $randPass, $avatar]
        );

        return $this->db->fetch("SELECT * FROM users WHERE id = ? LIMIT 1", [$newId]);
    }

    private function generateEmployeeId() {
        do {
            $candidate = 'GH' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6)); // e.g. GH1A2B3C (<= 20 chars)
            $exists = $this->db->fetchColumn("SELECT id FROM users WHERE employee_id = ? LIMIT 1", [$candidate]);
        } while ($exists);
        return $candidate;
    }

    private function httpPost($url, array $fields) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($fields),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded', 'Accept: application/json', 'User-Agent: TekTrend-App'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        $body = curl_exec($ch);
        if ($body === false) {
            error_log('GitHub OAuth cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
        // GitHub may return url-encoded body if Accept was ignored; normalise to array.
        $json = json_decode((string)$body, true);
        if (is_array($json)) {
            return $json;
        }
        parse_str((string)$body, $form);
        return $form ?: [];
    }

    private function httpGetJson($url, $authorization) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER     => ['Authorization: ' . $authorization, 'Accept: application/json', 'User-Agent: TekTrend-App'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        $body = curl_exec($ch);
        if ($body === false) {
            error_log('GitHub OAuth cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
        return json_decode((string)$body, true) ?: [];
    }
}
