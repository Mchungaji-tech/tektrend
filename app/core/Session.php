<?php
/**
 * Session Management
 * Secure session handling with regeneration
 */

class Session {
    private static $instance = null;

    private $retrievedFlash = [];

    private function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            $isSecure = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1))
                || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
                || (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
                || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);

            session_name('tektrend_session');
            session_start([
                'cookie_lifetime'   => defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 1440,
                'cookie_path'       => '/',
                'cookie_httponly'   => true,
                'cookie_samesite'   => 'Lax',
                'cookie_secure'     => $isSecure,
                'use_strict_mode'   => false,
                'gc_maxlifetime'    => defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 1440
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
     * Set/get flash message
     */
    public function flash($key, $message = null) {
        if ($message === null) {
            if (isset($_SESSION['flash'][$key])) {
                $this->retrievedFlash[$key] = $_SESSION['flash'][$key];
                unset($_SESSION['flash'][$key]);
                return $this->retrievedFlash[$key];
            }
            return $this->retrievedFlash[$key] ?? null;
        }
        $_SESSION['flash'][$key] = $message;
        unset($this->retrievedFlash[$key]);
    }

    /**
     * Get all session data
     */
    public function all() {
        return $_SESSION;
    }
}
