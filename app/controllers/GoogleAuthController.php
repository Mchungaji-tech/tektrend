<?php
/**
 * Google OAuth 2.0 Controller
 * Implements the "Sign in with Google" authorization-code flow without external libraries.
 *
 * Flow:
 *   1) redirect()  -> sends the user to Google's consent screen with a CSRF-safe `state`.
 *   2) callback()  -> validates `state`, exchanges the code for tokens, reads the user's
 *                     profile, then links/creates a local `users` row and logs them in.
 *
 * The exact redirect_uri sent to Google is derived from BASE_URL so it always matches the
 * route registered below (see App.php): /auth/google/callback
 */
class GoogleAuthController extends Controller {

    /**
     * Step 1: Kick off the Google sign-in flow.
     */
    public function redirect() {
        if (!$this->isConfigured()) {
            $this->session->flash('error', 'Google Sign-In is not configured on this server yet.');
            redirect('/login');
            return;
        }

        // Ensure the users table can store a Google ID before anyone signs in.
        $this->ensureGoogleColumn();

        $state = bin2hex(random_bytes(16));
        $this->session->set('google_oauth_state', $state);

        $params = [
            'client_id'     => GOOGLE_CLIENT_ID,
            'redirect_uri'  => $this->redirectUri(),
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'state'         => $state,
            'access_type'   => 'online',
            'prompt'        => 'select_account',
        ];

        header('Location: ' . GOOGLE_OAUTH_AUTH_ENDPOINT . '?' . http_build_query($params));
        exit;
    }

    /**
     * Step 2: Handle Google's callback.
     */
    public function callback() {
        // A Google error (e.g. user cancelled) comes back in the query string.
        if (!empty($_GET['error'])) {
            $this->session->flash('error', 'Google sign-in was cancelled or returned an error: ' . sanitize($_GET['error']));
            redirect('/login');
            return;
        }

        $code  = $_GET['code'] ?? '';
        $state = $_GET['state'] ?? '';

        // Validate CSRF `state`.
        $expected = $this->session->get('google_oauth_state');
        $this->session->remove('google_oauth_state');
        if (!$expected || !$state || !hash_equals((string)$expected, (string)$state)) {
            $this->session->flash('error', 'Security check failed during Google sign-in. Please try again.');
            redirect('/login');
            return;
        }

        if (!$code || !$this->isConfigured()) {
            $this->session->flash('error', 'Invalid Google sign-in request.');
            redirect('/login');
            return;
        }

        // Exchange the authorization code for tokens.
        $tokenRes = $this->httpPost(GOOGLE_OAUTH_TOKEN_ENDPOINT, [
            'code'          => $code,
            'client_id'     => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri'  => $this->redirectUri(),
            'grant_type'    => 'authorization_code',
        ]);

        $accessToken = $tokenRes['access_token'] ?? null;
        if (!$accessToken) {
            error_log('Google OAuth token exchange failed: ' . json_encode($tokenRes));
            $this->session->flash('error', 'Could not complete Google sign-in. Please try again.');
            redirect('/login');
            return;
        }

        // Fetch the user's verified profile.
        $profile = $this->httpGetJson(GOOGLE_OAUTH_USERINFO_ENDPOINT, 'Bearer ' . $accessToken);
        $googleId = $profile['sub'] ?? null;
        $email    = $profile['email'] ?? null;

        if (!$googleId || !$email) {
            $this->session->flash('error', 'Google did not return a valid account identifier.');
            redirect('/login');
            return;
        }

        // Only trust verified emails to prevent impersonation via unverified Google addresses.
        if (!empty($profile['email_verified']) === false && isset($profile['email_verified'])) {
            $this->session->flash('error', 'Your Google email is not verified.');
            redirect('/login');
            return;
        }

        // Optional single-domain lock (recommended for internal portals).
        if (GOOGLE_ALLOWED_DOMAIN !== '') {
            $domain = strtolower(substr(strrchr((string)$email, '@'), 1));
            if ($domain !== strtolower(GOOGLE_ALLOWED_DOMAIN)) {
                $this->session->flash('error', 'Only accounts ending in @' . GOOGLE_ALLOWED_DOMAIN . ' may sign in here.');
                redirect('/login');
                return;
            }
        }

        try {
            $user = $this->findOrCreateUser($googleId, $email, $profile);
        } catch (\Throwable $e) {
            error_log('Google OAuth user provisioning error: ' . $e->getMessage());
            $this->session->flash('error', 'Something went wrong preparing your account. Please try again.');
            redirect('/login');
            return;
        }

        if (!$user) {
            $this->session->flash('error', 'Your account is not active. Please contact an administrator.');
            redirect('/login');
            return;
        }

        if (!$this->auth->loginUser($user)) {
            $this->session->flash('error', 'Your account has been suspended.');
            redirect('/login');
            return;
        }

        $this->redirectWithSuccess('/dashboard', 'Welcome, ' . $user['first_name'] . '! You are signed in with Google.');
    }

    // ---------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------

    private function isConfigured() {
        return GOOGLE_CLIENT_ID !== '' && GOOGLE_CLIENT_SECRET !== '';
    }

    /**
     * The absolute callback URL. This value MUST be copied verbatim into
     * Google Cloud Console > Credentials > Authorized redirect URIs.
     */
    private function redirectUri() {
        return url('/auth/google/callback');
    }

    /**
     * Add the `google_id` column to `users` if it does not exist yet.
     */
    private function ensureGoogleColumn() {
        try {
            $cols = $this->db->fetchAll("SHOW COLUMNS FROM `users` LIKE 'google_id'");
            if (empty($cols)) {
                $this->db->execute("ALTER TABLE `users` ADD COLUMN `google_id` VARCHAR(64) DEFAULT NULL AFTER `email`");
                $this->db->execute("ALTER TABLE `users` ADD UNIQUE KEY `google_id` (`google_id`)");
            }
        } catch (\Throwable $e) {
            error_log('ensureGoogleColumn: ' . $e->getMessage());
        }
    }

    /**
     * Locate a local user by Google ID, otherwise by email (linking the account),
     * otherwise create a fresh employee account from the Google profile.
     */
    private function findOrCreateUser($googleId, $email, array $profile) {
        $email    = strtolower(trim($email));
        $googleId = (string)$googleId;

        // 1) Already linked.
        $user = $this->db->fetch("SELECT * FROM users WHERE google_id = ? LIMIT 1", [$googleId]);
        if ($user) {
            return $user['status'] === 'active' ? $user : null;
        }

        $first = trim($profile['given_name'] ?? '');
        $last  = trim($profile['family_name'] ?? '');
        if ($first === '' && $last === '') {
            $name = trim($profile['name'] ?? '');
            $parts = preg_split('/\s+/', $name);
            $first = array_shift($parts) ?: explode('@', $email)[0];
            $last  = $parts ? implode(' ', $parts) : 'User';
        }
        $avatar = $profile['picture'] ?? null;

        // 2) Same verified email already registered -> link Google ID and log in.
        $user = $this->db->fetch("SELECT * FROM users WHERE email = ? LIMIT 1", [$email]);
        if ($user) {
            $this->db->execute("UPDATE users SET google_id = ?, avatar = COALESCE(avatar, ?) WHERE id = ?", [$googleId, $avatar, $user['id']]);
            $user['google_id'] = $googleId;
            return $user['status'] === 'active' ? $user : null;
        }

        // 3) Brand-new account provisioned as an employee.
        $this->ensureGoogleColumn();
        $employeeId = $this->generateEmployeeId();
        $randPass   = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT, ['cost' => BCRYPT_ROUNDS]);

        $newId = $this->db->insert(
            "INSERT INTO users (employee_id, first_name, last_name, email, google_id, password, role, avatar, status)
             VALUES (?, ?, ?, ?, ?, ?, 'employee', ?, 'active')",
            [$employeeId, $first, $last, $email, $googleId, $randPass, $avatar]
        );

        return $this->db->fetch("SELECT * FROM users WHERE id = ? LIMIT 1", [$newId]);
    }

    /**
     * Generate a unique, non-colliding employee_id for auto-provisioned Google users.
     */
    private function generateEmployeeId() {
        do {
            $candidate = 'G' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 7)); // e.g. G1A2B3C4D (<= 20 chars)
            $exists = $this->db->fetchColumn("SELECT id FROM users WHERE employee_id = ? LIMIT 1", [$candidate]);
        } while ($exists);
        return $candidate;
    }

    /**
     * POST form fields via cURL and decode the JSON response.
     */
    private function httpPost($url, array $fields) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($fields),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded', 'Accept: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        $body = curl_exec($ch);
        if ($body === false) {
            error_log('Google OAuth cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
        return json_decode((string)$body, true) ?: [];
    }

    /**
     * GET a JSON endpoint with a Bearer token.
     */
    private function httpGetJson($url, $authorization) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER     => ['Authorization: ' . $authorization, 'Accept: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        $body = curl_exec($ch);
        if ($body === false) {
            error_log('Google OAuth cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
        return json_decode((string)$body, true) ?: [];
    }
}
