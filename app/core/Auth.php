<?php
/**
 * Authentication Manager
 * Handles login, logout, password hashing, session management
 */

class Auth {
    private static $instance = null;
    private $session;
    private $db;

    private function __construct() {
        $this->session = Session::getInstance();
        $this->db = Database::getInstance();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Attempt to log in a user
     */
    public function attempt($email, $password, $remember = false) {
        // Check rate limiting
        if ($this->isRateLimited($email)) {
            return ['success' => false, 'message' => 'Too many login attempts. Please try again later.'];
        }

        $user = $this->db->fetch(
            "SELECT * FROM users WHERE email = ? AND status IN ('active', 'suspended') LIMIT 1",
            [$email]
        );

        if (!$user) {
            $this->logAttempt($email, false);
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }

        if ($user['status'] === 'suspended') {
            $this->logAttempt($email, false);
            return ['success' => false, 'message' => 'Your account has been suspended.'];
        }

        if (!password_verify($password, $user['password'])) {
            $this->logAttempt($email, false);
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }

        // Clear failed attempts
        $this->clearAttempts($email);

        // Update user status
        $this->db->execute(
            "UPDATE users SET last_login = NOW(), last_activity = NOW(), is_online = 1, work_status = 'working' WHERE id = ?",
            [$user['id']]
        );

        // Create session record
        $sessionId = session_id();
        $this->db->insert(
            "INSERT INTO user_sessions (user_id, session_id, ip_address, user_agent, expires_at) 
             VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL " . SESSION_LIFETIME . " SECOND))",
            [
                $user['id'],
                $sessionId,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null
            ]
        );

        // Set session data
        $this->session->regenerate();
        $this->session->set('user_id', $user['id']);
        $this->session->set('user_email', $user['email']);
        $this->session->set('user_role', $user['role']);
        $this->session->set('user_name', $user['first_name'] . ' ' . $user['last_name']);
        $this->session->set('user_avatar', $user['avatar']);
        $this->session->set('user_department', $user['department_id']);
        $this->session->set('login_time', time());

        // Remember me
        if ($remember) {
            $this->setRememberToken($user['id']);
        }

        auditLog('login', 'users', $user['id'], 'User logged in');

        return ['success' => true, 'user' => $user];
    }

    /**
     * Log out the current user
     */
    public function logout() {
        $userId = $this->session->get('user_id');

        if ($userId) {
            // Remove session record
            $this->db->execute(
                "DELETE FROM user_sessions WHERE user_id = ? AND session_id = ?",
                [$userId, session_id()]
            );

            // Update user status
            $this->db->execute(
                "UPDATE users SET is_online = 0, work_status = 'offline', last_activity = NOW() WHERE id = ?",
                [$userId]
            );

            auditLog('logout', 'users', $userId, 'User logged out');
        }

        // Clear remember token
        $this->clearRememberToken();

        $this->session->destroy();
    }

    /**
     * Check if user is logged in
     */
    public function check() {
        if (!$this->session->has('user_id')) {
            return false;
        }

        // Check session timeout
        $loginTime = $this->session->get('login_time');
        if ($loginTime && (time() - $loginTime > SESSION_LIFETIME)) {
            $this->logout();
            return false;
        }

        // Update last activity
        $this->updateActivity();

        return true;
    }

    /**
     * Get current user
     */
    public function user() {
        if (!$this->check()) {
            return null;
        }

        $user = $this->db->fetch(
            "SELECT u.*, d.name as department_name, d.color as department_color 
             FROM users u 
             LEFT JOIN departments d ON u.department_id = d.id 
             WHERE u.id = ? LIMIT 1",
            [$this->session->get('user_id')]
        );

        return $user;
    }

    /**
     * Get current user ID
     */
    public function id() {
        return $this->session->get('user_id');
    }

    /**
     * Get current user role
     */
    public function role() {
        return $this->session->get('user_role');
    }

    /**
     * Check if user has role
     */
    public function hasRole($role) {
        return hasRole($role, $this->role());
    }

    /**
     * Update user activity timestamp
     */
    public function updateActivity() {
        $userId = $this->session->get('user_id');
        if ($userId) {
            $this->db->execute(
                "UPDATE users SET last_activity = NOW(), is_online = 1 WHERE id = ?",
                [$userId]
            );
            $this->session->set('last_activity', time());
        }
    }

    /**
     * Set work status
     */
    public function setWorkStatus($status) {
        $userId = $this->session->get('user_id');
        if ($userId) {
            $this->db->execute(
                "UPDATE users SET work_status = ?, last_activity = NOW() WHERE id = ?",
                [$status, $userId]
            );
        }
    }

    /**
     * Get online users
     */
    public function getOnlineUsers() {
        $threshold = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        return $this->db->fetchAll(
            "SELECT id, first_name, last_name, email, avatar, role, work_status, last_activity, department_id 
             FROM users 
             WHERE is_online = 1 AND last_activity >= ? 
             ORDER BY last_activity DESC",
            [$threshold]
        );
    }

    /**
     * Get users who are at work (working status)
     */
    public function getUsersAtWork() {
        $threshold = date('Y-m-d H:i:s', strtotime('-10 minutes'));
        return $this->db->fetchAll(
            "SELECT u.id, u.first_name, u.last_name, u.email, u.avatar, u.role, u.work_status, u.last_activity, d.name as department
             FROM users u 
             LEFT JOIN departments d ON u.department_id = d.id
             WHERE u.is_online = 1 AND u.work_status IN ('working', 'meeting', 'away', 'break') 
             AND u.last_activity >= ? 
             ORDER BY u.work_status, u.last_activity DESC",
            [$threshold]
        );
    }

    /**
     * Hash password
     */
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => BCRYPT_ROUNDS]);
    }

    /**
     * Check rate limiting
     */
    private function isRateLimited($email) {
        $threshold = date('Y-m-d H:i:s', strtotime('-15 minutes'));
        $count = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM login_attempts WHERE email = ? AND success = 0 AND attempted_at >= ?",
            [$email, $threshold]
        );
        return $count >= 5;
    }

    /**
     * Log login attempt
     */
    private function logAttempt($email, $success) {
        $this->db->insert(
            "INSERT INTO login_attempts (email, ip_address, user_agent, success) VALUES (?, ?, ?, ?)",
            [$email, $_SERVER['REMOTE_ADDR'] ?? null, $_SERVER['HTTP_USER_AGENT'] ?? null, $success ? 1 : 0]
        );
    }

    /**
     * Clear failed attempts
     */
    private function clearAttempts($email) {
        $this->db->execute(
            "DELETE FROM login_attempts WHERE email = ? AND success = 0",
            [$email]
        );
    }

    /**
     * Set remember me token
     */
    private function setRememberToken($userId) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
        $this->db->insert(
            "INSERT INTO password_resets (email, token, expires_at, used) VALUES (?, ?, ?, 0)",
            [$this->session->get('user_email'), $token, $expires]
        );
        setcookie('remember_token', $token, strtotime('+30 days'), '/', '', false, true);
    }

    /**
     * Clear remember token
     */
    private function clearRememberToken() {
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }
    }

    /**
     * Register a new user
     */
    public function register($data) {
        // Check if email exists
        $exists = $this->db->fetchColumn(
            "SELECT id FROM users WHERE email = ? OR employee_id = ?",
            [$data['email'], $data['employee_id']]
        );

        if ($exists) {
            return ['success' => false, 'message' => 'Email or employee ID already exists.'];
        }

        $userId = $this->db->insert(
            "INSERT INTO users (department_id, employee_id, first_name, last_name, email, phone, password, role, position, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['department_id'] ?? null,
                $data['employee_id'],
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['phone'] ?? null,
                $this->hashPassword($data['password']),
                $data['role'] ?? 'employee',
                $data['position'] ?? null,
                $data['status'] ?? 'active'
            ]
        );

        auditLog('register', 'users', $userId, 'New user registered');

        return ['success' => true, 'user_id' => $userId];
    }

    /**
     * Generate password reset token
     */
    public function forgotPassword($email) {
        $user = $this->db->fetch("SELECT id, email FROM users WHERE email = ? AND status = 'active'", [$email]);

        if (!$user) {
            return ['success' => false, 'message' => 'If the email exists, a reset link will be sent.'];
        }

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->db->insert(
            "INSERT INTO password_resets (email, token, expires_at, used) VALUES (?, ?, ?, 0)",
            [$email, $token, $expires]
        );

        // In production, send email. For now, return token.
        return ['success' => true, 'token' => $token, 'message' => 'If the email exists, a reset link will be sent.'];
    }

    /**
     * Reset password using token
     */
    public function resetPassword($token, $password) {
        $reset = $this->db->fetch(
            "SELECT * FROM password_resets WHERE token = ? AND used = 0 AND expires_at > NOW() LIMIT 1",
            [$token]
        );

        if (!$reset) {
            return ['success' => false, 'message' => 'Invalid or expired token.'];
        }

        $this->db->execute(
            "UPDATE users SET password = ? WHERE email = ?",
            [$this->hashPassword($password), $reset['email']]
        );

        $this->db->execute(
            "UPDATE password_resets SET used = 1 WHERE id = ?",
            [$reset['id']]
        );

        auditLog('password_reset', 'users', null, 'Password reset for ' . $reset['email']);

        return ['success' => true, 'message' => 'Password has been reset successfully.'];
    }
}
