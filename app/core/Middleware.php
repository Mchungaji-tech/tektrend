<?php
/**
 * Middleware
 * Security and request handling middleware
 */

class Middleware {
    private $auth;
    private $session;

    public function __construct() {
        $this->auth = Auth::getInstance();
        $this->session = Session::getInstance();
    }

    /**
     * Handle all security checks
     */
    public function handle() {
        $this->checkSessionTimeout();
        $this->updateOnlineStatus();
        $this->enforceHttps();
        $this->setSecurityHeaders();
    }

    /**
     * Check session timeout
     */
    private function checkSessionTimeout() {
        if (!$this->auth->check()) {
            return;
        }

        $loginTime = $this->session->get('login_time');
        $lastActivity = $this->session->get('last_activity');

        if ($loginTime && (time() - $loginTime > SESSION_LIFETIME)) {
            $this->auth->logout();
            $this->session->flash('error', 'Your session has expired. Please log in again.');
            redirect('/login');
        }

        if ($lastActivity && (time() - $lastActivity > 300)) {
            // Mark as away after 5 minutes of inactivity
            $this->auth->setWorkStatus('away');
        }
    }

    /**
     * Update online status based on activity
     */
    private function updateOnlineStatus() {
        if ($this->auth->check()) {
            $this->auth->updateActivity();
        }
    }

    /**
     * Enforce HTTPS in production
     */
    private function enforceHttps() {
        if (APP_ENV === 'production' && !isset($_SERVER['HTTPS']) && isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            if ($_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https') {
                return;
            }
        }
        if (APP_ENV === 'production' && empty($_SERVER['HTTPS']) && $_SERVER['HTTP_HOST'] !== 'localhost') {
            // In production, you might redirect to HTTPS
            // redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    /**
     * Set security headers
     */
    private function setSecurityHeaders() {
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://www.googletagmanager.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com data:; img-src 'self' data: https:; connect-src 'self' ws: wss: https:;");
    }

    /**
     * Clean expired sessions from database
     */
    public function cleanExpiredSessions() {
        try {
            $db = Database::getInstance();
            $threshold = date('Y-m-d H:i:s', strtotime('-' . SESSION_LIFETIME . ' seconds'));
            $db->execute("DELETE FROM user_sessions WHERE expires_at < ?", [$threshold]);
            $db->execute("UPDATE users SET is_online = 0, work_status = 'offline' WHERE last_activity < ?", [$threshold]);
        } catch (Exception $e) {
            // Silent fail
        }
    }

    /**
     * Log security event
     */
    public function logSecurityEvent($event, $details = null) {
        auditLog($event, 'security', null, $details);
    }
}
