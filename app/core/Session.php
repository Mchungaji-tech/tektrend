<?php
/**
 * Session Management
 * Secure session handling with regeneration
 */

class Session {
    private static $instance = null;

    private function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_name('tektrend_session');
            session_start([
                'cookie_lifetime'   => SESSION_LIFETIME,
                'cookie_httponly'   => true,
                'cookie_samesite'   => 'Lax',
                'use_strict_mode'   => true,
                'gc_maxlifetime'    => SESSION_LIFETIME
            ]);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Set session value
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value
     */
    public function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     */
    public function has($key) {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove session value
     */
    public function remove($key) {
        unset($_SESSION[$key]);
    }

    /**
     * Regenerate session ID
     */
    public function regenerate() {
        session_regenerate_id(true);
    }

    /**
     * Destroy session
     */
    public function destroy() {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
    }

    /**
     * Set flash message
     */
    public function flash($key, $message = null) {
        if ($message === null) {
            $message = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * Get all session data
     */
    public function all() {
        return $_SESSION;
    }
}
