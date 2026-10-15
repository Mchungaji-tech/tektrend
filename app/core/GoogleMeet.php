<?php
/**
 * Google Meet helper (dependency-free - no Composer, no SDK).
 *
 * Creates a Google Calendar event with an auto-generated Google Meet conference and
 * returns its join URL (https://meet.google.com/xxx-xxxx-xxx). Uses a Google Cloud
 * service account with the OAuth2 JWT-bearer flow, signed with RS256 via OpenSSL.
 *
 * The private key lives ONLY on the server (env var or a JSON key file kept outside the
 * public web root). It is never sent to the browser.
 *
 * Prereqs (see docs / .env.example): a Google Cloud project with the Calendar API
 * enabled, a service account, and its key. Workspace accounts with Meet enabled are the
 * most reliable for programmatic conference creation.
 */

class GoogleMeet
{
    private const SCOPE = 'https://www.googleapis.com/auth/calendar.events';
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    private static ?array $tokenCache = null;

    /** Resolve (and validate) the service-account credentials. */
    private static function credentials(): ?array
    {
        static $resolved = null;
        if ($resolved !== null) {
            return $resolved === false ? null : $resolved;
        }

        $email = '';
        $privateKey = '';

        // Preferred: point at the downloaded service-account JSON (kept outside /public).
        $file = defined('GOOGLE_MEET_KEY_FILE') ? trim((string)GOOGLE_MEET_KEY_FILE) : '';
        if ($file !== '' && is_readable($file)) {
            $json = json_decode((string)file_get_contents($file), true);
            if (is_array($json) && !empty($json['client_email']) && !empty($json['private_key'])) {
                $email = $json['client_email'];
                $privateKey = $json['private_key'];
            }
        }

        // Fallback: email + PEM stored directly in env (accept literal "\n" sequences).
        if ($email === '' && defined('GOOGLE_MEET_CLIENT_EMAIL') && defined('GOOGLE_MEET_PRIVATE_KEY')) {
            $email = trim((string)GOOGLE_MEET_CLIENT_EMAIL);
            $raw = (string)GOOGLE_MEET_PRIVATE_KEY;
            $raw = str_replace('\\n', "\n", $raw); // env single-line escapes
            if ($raw !== '' && strpos($raw, 'BEGIN') === false) {
                $raw = "-----BEGIN PRIVATE KEY-----\n" . wordwrap($raw, 64, "\n", true) . "\n-----END PRIVATE KEY-----\n";
            }
            $privateKey = $raw;
        }

        $resolved = ($email !== '' && $privateKey !== '') ? ['email' => $email, 'key' => $privateKey] : false;
        return $resolved === false ? null : $resolved;
    }

    /** True when credentials are present and the feature is enabled. */
    public static function isConfigured(): bool
    {
        $enabled = defined('GOOGLE_MEET_ENABLED')
            ? filter_var(GOOGLE_MEET_ENABLED, FILTER_VALIDATE_BOOLEAN)
            : true;
        return $enabled && self::credentials() !== null;
    }

    /**
     * Create a Meet-backed calendar event and return the meet.google.com join link.
     *
     * @param string $summary   Event title.
     * @param string $start     Local start as 'Y-m-d H:i:s'.
     * @param string $end       Local end as 'Y-m-d H:i:s'.
     * @param array  $attendees Email addresses to invite.
     * @param string $description Optional event body.
     * @return string|null      The Meet URL, or null on failure / not configured.
     */
    public static function createMeeting(string $summary, string $start, string $end, array $attendees = [], string $description = ''): ?string
    {
        $creds = self::credentials();
        if (!$creds) {
            error_log('GoogleMeet::createMeeting - service account not configured.');
            return null;
        }

        $token = self::accessToken($creds);
        if (!$token) {
            return null;
        }

        $tz = defined('GOOGLE_MEET_TIMEZONE') && GOOGLE_MEET_TIMEZONE !== '' ? GOOGLE_MEET_TIMEZONE : 'Africa/Nairobi';
        $calendarId = rawurlencode(defined('GOOGLE_MEET_CALENDAR_ID') && GOOGLE_MEET_CALENDAR_ID !== '' ? GOOGLE_MEET_CALENDAR_ID : 'primary');
        $requestId = self::uuid();

        $body = [
            'summary'     => $summary,
            'description' => $description,
            'start'       => ['dateTime' => date('c', strtotime($start)), 'timeZone' => $tz],
            'end'         => ['dateTime' => date('c', strtotime($end)), 'timeZone' => $tz],
            'attendees'   => array_map(static fn($e) => ['email' => $e], array_values(array_filter($attendees))),
            'conferenceData' => [
                'createRequest' => [
                    'requestId'           => $requestId,
                    'conferenceSolutionKey' => ['type' => 'hangoutsMeet'],
                ],
            ],
            'reminders'   => ['useDefault' => true],
        ];

        $url = "https://www.googleapis.com/calendar/v3/calendars/{$calendarId}/events?conferenceDataVersion=1&sendUpdates=all";
        [$status, $resp] = self::request('POST', $url, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ], json_encode($body));

        $data = json_decode($resp, true);
        if ($status === 200 || $status === 201) {
            $link = $data['hangoutLink'] ?? null;
            if ($link) {
                return $link;
            }
            error_log('GoogleMeet::createMeeting - event created but no hangoutLink returned.');
        } else {
            error_log('GoogleMeet::createMeeting - HTTP ' . $status . ': ' . substr($resp, 0, 400));
        }
        return null;
    }

    /** Fetch (or reuse) an OAuth2 access token for the service account. */
    private static function accessToken(array $creds): ?string
    {
        if (self::$tokenCache && self::$tokenCache['exp'] > time() + 30) {
            return self::$tokenCache['token'];
        }

        $now = time();
        $claims = [
            'iss'   => $creds['email'],
            'scope' => self::SCOPE,
            'aud'   => self::TOKEN_URL,
            'iat'   => $now,
            'exp'   => $now + 3600,
        ];

        $header = self::base64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT'], JSON_UNESCAPED_SLASHES));
        $payload = self::base64url(json_encode($claims, JSON_UNESCAPED_SLASHES));
        $signingInput = $header . '.' . $payload;

        $pkey = openssl_pkey_get_private($creds['key']);
        if (!$pkey) {
            error_log('GoogleMeet::accessToken - invalid private key.');
            return null;
        }
        $signature = '';
        if (!openssl_sign($signingInput, $signature, $pkey, 'sha256')) {
            return null;
        }
        $jwt = $signingInput . '.' . self::base64url($signature);

        [$status, $resp] = self::request(
            'POST',
            self::TOKEN_URL,
            ['Content-Type: application/x-www-form-urlencoded'],
            http_build_query(['grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer', 'assertion' => $jwt])
        );

        $data = json_decode($resp, true);
        if (($status === 200) && !empty($data['access_token'])) {
            self::$tokenCache = ['token' => $data['access_token'], 'exp' => $now + (int)($data['expires_in'] ?? 3600)];
            return self::$tokenCache['token'];
        }

        error_log('GoogleMeet::accessToken - token exchange failed HTTP ' . $status . ': ' . substr($resp, 0, 300));
        return null;
    }

    /** Minimal HTTP client: prefers cURL, falls back to stream context. */
    private static function request(string $method, string $url, array $headers, ?string $body = null): array
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST  => $method,
                CURLOPT_HTTPHEADER     => $headers,
                CURLOPT_TIMEOUT        => 20,
                CURLOPT_SSL_VERIFYPEER => true,
            ]);
            if ($body !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }
            $resp = curl_exec($ch);
            $status = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            if ($resp === false) {
                error_log('GoogleMeet::request cURL error: ' . curl_error($ch));
            }
            curl_close($ch);
            return [$status, (string)$resp];
        }

        $ctx = stream_context_create(['http' => [
            'method'        => $method,
            'header'        => implode("\r\n", $headers),
            'content'       => $body ?? '',
            'ignore_errors' => true,
            'timeout'       => 20,
        ]]);
        $resp = @file_get_contents($url, false, $ctx);
        $status = 0;
        foreach ($http_response_header ?? [] as $h) {
            if (preg_match('#^HTTP/\S+\s+(\d{3})#', $h, $m)) { $status = (int)$m[1]; }
        }
        return [$status, (string)$resp];
    }

    private static function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function uuid(): string
    {
        $b = random_bytes(16);
        $b[6] = chr((ord($b[6]) & 0x0f) | 0x40);
        $b[8] = chr((ord($b[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($b), 4));
    }
}
