<?php
/**
 * LiveKit access-token helper (dependency-free).
 *
 * Mints HS256-signed JSON Web Tokens that grant a single participant access to ONE
 * backend-controlled room. The API secret is only ever read here, server-side - it is
 * never sent to the browser. Tokens are short-lived and lock the caller's identity and
 * room so a client cannot join an arbitrary room or impersonate someone else.
 *
 * Docs: https://docs.livekit.io/home/get-started/authentication/
 */

class LiveKit
{
    /** True when a usable API key pair + server URL are configured. */
    public static function isConfigured()
    {
        return defined('LIVEKIT_URL') && LIVEKIT_URL !== ''
            && defined('LIVEKIT_API_KEY') && LIVEKIT_API_KEY !== ''
            && defined('LIVEKIT_API_SECRET') && LIVEKIT_API_SECRET !== ''
            && strlen(LIVEKIT_API_SECRET) >= 16;
    }

    /** Signaling origin (wss://host or https://host) used by the browser client. */
    public static function url()
    {
        return defined('LIVEKIT_URL') ? LIVEKIT_URL : '';
    }

    /**
     * Build a room name from the tenant prefix + a server-known key. Result is limited
     * to LiveKit's allowed character set (letters, digits, "-", "_", ".") and <= 155 chars.
     */
    public static function roomName($suffix)
    {
        $prefix = self::sanitize(defined('LIVEKIT_ROOM_PREFIX') ? LIVEKIT_ROOM_PREFIX : 'tektrend', 24) ?: 'tektrend';
        $key = self::sanitize($suffix, 80);
        $name = $prefix . '-' . ($key !== '' ? $key : uniqid());
        return substr($name, 0, 155);
    }

    /** Force an identity into LiveKit's safe charset and length (<= 64 chars). */
    public static function identity($raw, $fallback = 'guest')
    {
        $id = self::sanitize($raw, 64);
        return $id !== '' ? $id : substr($fallback . '-' . substr(hash('sha256', (string)$raw), 0, 10), 0, 64);
    }

    /** Re-validate an already-built room name against LiveKit's charset/length. */
    public static function normalizeRoom($name)
    {
        $n = self::sanitize($name, 155);
        return $n !== '' ? $n : self::roomName(uniqid());
    }

    /** Keep only [A-Za-z0-9._-], trimming separators; used for room names & identities. */
    private static function sanitize($value, $max)
    {
        $v = preg_replace('/[^A-Za-z0-9._-]+/', '-', (string)$value);
        $v = trim($v, '-');
        return substr($v, 0, $max);
    }

    /**
     * Mint an access token for $identity to join $room.
     *
     * @param string $identity  Stable, server-derived participant id (locks the identity).
     * @param string $name      Display name shown to other participants.
     * @param string $room      Backend-generated room name (already sanitized).
     * @param array  $grants    Optional overrides for the video grant.
     * @return string           Signed JWT.
     * @throws RuntimeException when LiveKit is not configured.
     */
    public static function accessToken($identity, $name, $room, array $grants = [])
    {
        if (!self::isConfigured()) {
            throw new RuntimeException('LiveKit is not configured on this server.');
        }

        $now = time();
        $ttl = defined('LIVEKIT_TOKEN_TTL') ? max(60, (int)LIVEKIT_TOKEN_TTL) : 21600;

        $video = array_merge([
            'room'           => $room,
            'roomJoin'       => true,
            'canPublish'     => true,
            'canSubscribe'   => true,
            'canPublishData' => true,
            'identity'       => $identity,   // enforce identity - client cannot spoof
        ], $grants);

        $payload = [
            'iss' => LIVEKIT_API_KEY,
            'sub' => $identity,
            'nbf' => $now - 5,
            'exp' => $now + $ttl,
            'vat' => 'video',
            'video' => $video,
        ];
        if ($name !== '' && $name !== null) {
            $payload['name'] = $name;   // participant display name
        }

        $header = ['alg' => 'HS256', 'typ' => 'JWT'];

        $segments = [
            self::base64url(json_encode($header, JSON_UNESCAPED_SLASHES)),
            self::base64url(json_encode($payload, JSON_UNESCAPED_SLASHES)),
        ];
        $signingInput = implode('.', $segments);
        $signature = hash_hmac('sha256', $signingInput, LIVEKIT_API_SECRET, true);
        $segments[] = self::base64url($signature);

        return implode('.', $segments);
    }

    /** RFC 7515 base64url encoding (no padding, URL-safe alphabet). */
    private static function base64url($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
