<?php
/**
 * Authentication Controller
 * Handles login, registration, password reset
 */

class AuthController extends Controller {

    /**
     * Show login form
     */
    public function login() {
        if ($this->auth->check()) {
            redirect($this->auth->homeUrl());
        }
        $area = $_GET['area'] ?? '';
        $this->view->setLayout('layouts/auth');
        $this->render('auth/login', ['area' => $area]);
    }

    /**
     * Authenticate user
     */
    public function authenticate() {
        $this->verifyCsrf();

        $rules = [
            'email'    => ['required' => true, 'email' => true, 'label' => 'Email'],
            'password' => ['required' => true, 'min' => 1, 'label' => 'Password']
        ];

        $validation = $this->validatePost($rules);

        if (!$validation['valid']) {
            $this->session->flash('error', reset($validation['errors']));
            $this->session->flash('old', $_POST);
            redirect('/login');
        }

        $result = $this->auth->attempt(
            $validation['data']['email'],
            $validation['data']['password'],
            isset($_POST['remember'])
        );

        if ($result['success']) {
            $this->redirectWithSuccess($this->auth->homeUrl(), 'Welcome back, ' . $result['user']['first_name'] . '!');
        } else {
            $this->session->flash('error', $result['message']);
            $this->session->flash('old', $_POST);
            redirect('/login');
        }
    }

    /**
     * Show registration form
     */
    public function register() {
        $this->requireRole('admin');

        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $this->view->setLayout('layouts/auth');
        $this->render('auth/register', ['departments' => $departments]);
    }

    /**
     * Store new user
     */
    public function store() {
        $this->requireRole('admin');
        $this->verifyCsrf();

        $rules = [
            'employee_id'   => ['required' => true, 'label' => 'Employee ID'],
            'first_name'    => ['required' => true, 'label' => 'First Name'],
            'last_name'     => ['required' => true, 'label' => 'Last Name'],
            'email'         => ['required' => true, 'email' => true, 'label' => 'Email'],
            'password'      => ['required' => true, 'min' => 8, 'label' => 'Password'],
            'role'          => ['required' => true, 'label' => 'Role'],
        ];

        $validation = $this->validatePost($rules);

        if (!$validation['valid']) {
            $this->session->flash('error', reset($validation['errors']));
            $this->session->flash('old', $_POST);
            redirect('/admin/register');
        }

        $data = $validation['data'];
        $data['department_id'] = $_POST['department_id'] ?? null;
        $data['phone'] = $_POST['phone'] ?? null;
        $data['position'] = $_POST['position'] ?? null;

        $result = $this->auth->register($data);

        if ($result['success']) {
            $this->redirectWithSuccess('/users', 'User registered successfully!');
        } else {
            $this->session->flash('error', $result['message']);
            $this->session->flash('old', $_POST);
            redirect('/admin/register');
        }
    }

    /**
     * Show public client self-registration form
     */
    public function signup() {
        if ($this->auth->check()) {
            redirect($this->auth->homeUrl());
        }
        $this->view->setLayout('layouts/auth');
        $this->render('auth/signup');
    }

    /**
     * Create a self-registered client account (active) and log them in
     */
    public function createClient() {
        if ($this->auth->check()) {
            redirect($this->auth->homeUrl());
        }
        $this->verifyCsrf();

        $rules = [
            'first_name' => ['required' => true, 'label' => 'First Name'],
            'last_name'  => ['required' => true, 'label' => 'Last Name'],
            'email'      => ['required' => true, 'email' => true, 'label' => 'Email'],
            'password'   => ['required' => true, 'min' => 8, 'label' => 'Password'],
        ];
        $validation = $this->validatePost($rules);

        if (!$validation['valid']) {
            $this->session->flash('error', reset($validation['errors']));
            $this->session->flash('old', $_POST);
            redirect('/signup');
        }

        // Reject if the email is already registered
        if ($this->db->fetchColumn("SELECT id FROM users WHERE email = ?", [$validation['data']['email']])) {
            $this->session->flash('error', 'An account with that email already exists. Please sign in instead.');
            redirect('/login?area=client');
        }

        $data = $validation['data'];
        $data['phone']      = trim($_POST['phone'] ?? '') ?: null;
        $data['company']    = trim($_POST['company'] ?? '') ?: null;
        $data['role']       = 'client';
        $data['status']     = 'active';
        $data['employee_id'] = $this->generateClientEmployeeId();

        $result = $this->auth->register($data);
        if (!$result['success']) {
            $this->session->flash('error', $result['message']);
            $this->session->flash('old', $_POST);
            redirect('/signup');
        }

        // Link (or create) a customer record scoped to this user
        $this->linkClientCustomer($result['user_id'], $data);

        $user = $this->db->fetch("SELECT * FROM users WHERE id = ?", [$result['user_id']]);
        $this->auth->loginUser($user, 'Self-signup');

        $this->redirectWithSuccess('/portal', 'Your account is ready. Welcome to Tek Trend!');
    }

    /**
     * Generate a unique employee_id for self-registered clients.
     */
    private function generateClientEmployeeId() {
        do {
            $candidate = 'CL' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8)); // CL + 8 chars, <= 20
            $exists = $this->db->fetchColumn("SELECT id FROM users WHERE employee_id = ?", [$candidate]);
        } while ($exists);
        return $candidate;
    }

    /**
     * Attach the new client user to an existing customer by email, else create one.
     */
    private function linkClientCustomer($userId, array $data) {
        try {
            $existing = $this->db->fetch("SELECT id FROM customers WHERE email = ? AND user_id IS NULL LIMIT 1", [$data['email']]);
            if ($existing) {
                $this->db->execute("UPDATE customers SET user_id = ? WHERE id = ?", [$userId, $existing['id']]);
                return;
            }
            $this->db->insert(
                "INSERT INTO customers (user_id, first_name, last_name, email, phone, company, status) VALUES (?, ?, ?, ?, ?, ?, 'active')",
                [$userId, $data['first_name'], $data['last_name'], $data['email'], $data['phone'] ?? null, $data['company'] ?? null]
            );
        } catch (\Throwable $e) {
            error_log('linkClientCustomer: ' . $e->getMessage());
        }
    }

    /**
     * Show forgot password form
     */
    public function forgotPassword() {
        $this->view->setLayout('layouts/auth');
        $this->render('auth/forgot');
    }

    /**
     * Send password reset
     */
    public function sendReset() {
        $this->verifyCsrf();

        $rules = ['email' => ['required' => true, 'email' => true, 'label' => 'Email']];
        $validation = $this->validatePost($rules);

        if (!$validation['valid']) {
            $this->session->flash('error', reset($validation['errors']));
            redirect('/forgot-password');
        }

        $result = $this->auth->forgotPassword($validation['data']['email']);

        if ($result['success']) {
            $this->session->flash('success', 'If the email exists, a reset link will be sent.');
            // In development, show the token
            if (APP_DEBUG) {
                $this->session->flash('debug_token', 'Reset token: ' . $result['token']);
            }
            redirect('/forgot-password');
        } else {
            $this->session->flash('error', $result['message']);
            redirect('/forgot-password');
        }
    }

    /**
     * Show reset password form
     */
    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        if (!$token) {
            $this->session->flash('error', 'Invalid reset token.');
            redirect('/forgot-password');
        }
        $this->view->setLayout('layouts/auth');
        $this->render('auth/reset', ['token' => $token]);
    }

    /**
     * Update password
     */
    public function updatePassword() {
        $this->verifyCsrf();

        $rules = [
            'token'    => ['required' => true, 'label' => 'Token'],
            'password' => ['required' => true, 'min' => 8, 'label' => 'Password'],
            'password_confirm' => ['required' => true, 'label' => 'Confirm Password']
        ];

        $validation = $this->validatePost($rules);

        if (!$validation['valid']) {
            $this->session->flash('error', reset($validation['errors']));
            redirect(backUrl());
        }

        if ($validation['data']['password'] !== $validation['data']['password_confirm']) {
            $this->session->flash('error', 'Passwords do not match.');
            redirect(backUrl());
        }

        $result = $this->auth->resetPassword($validation['data']['token'], $validation['data']['password']);

        if ($result['success']) {
            $this->redirectWithSuccess('/login', $result['message']);
        } else {
            $this->session->flash('error', $result['message']);
            redirect(backUrl());
        }
    }

    /**
     * Logout
     */
    public function logout() {
        $this->auth->logout();
        $this->redirectWithSuccess('/login', 'You have been logged out successfully.');
    }
}
