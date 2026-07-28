<?php
/**
 * Base Controller
 * Provides common functionality for all controllers
 */

class Controller {
    protected $db;
    protected $auth;
    protected $session;
    protected $view;
    protected $data = [];

    public function __construct() {
        $this->db = Database::getInstance();
        $this->auth = Auth::getInstance();
        $this->session = Session::getInstance();
        $this->view = new View();
    }

    /**
     * Require authentication
     */
    protected function requireAuth() {
        if (!$this->auth->check()) {
            $this->session->flash('error', 'Please log in to continue.');
            redirect('/login');
        }
    }

    /**
     * Require specific role
     */
    protected function requireRole($role) {
        $this->requireAuth();
        if (!$this->auth->hasRole($role)) {
            $this->session->flash('error', 'You do not have permission to access this page.');
            redirect('/dashboard');
        }
    }

    /**
     * Verify CSRF token from POST
     */
    protected function verifyCsrf() {
        $token = $_POST['csrf_token'] ?? $_POST['_token'] ?? null;
        if (!$token || !verifyCsrf($token)) {
            $this->session->flash('error', 'Security token verification failed. Please try again.');
            redirect(backUrl());
        }
    }

    /**
     * Get validated POST data
     */
    protected function validatePost($rules) {
        $errors = [];
        $data = [];

        foreach ($rules as $field => $rule) {
            $value = $_POST[$field] ?? null;
            $label = $rule['label'] ?? ucfirst(str_replace('_', ' ', $field));

            // Check required
            if (!empty($rule['required']) && (is_null($value) || trim($value) === '')) {
                $errors[$field] = $label . ' is required.';
                continue;
            }

            // Skip further validation if empty and not required
            if (is_null($value) || trim($value) === '') {
                $data[$field] = $value;
                continue;
            }

            // Validate email
            if (!empty($rule['email']) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = $label . ' must be a valid email address.';
            }

            // Validate min length
            if (!empty($rule['min']) && strlen($value) < $rule['min']) {
                $errors[$field] = $label . ' must be at least ' . $rule['min'] . ' characters.';
            }

            // Validate max length
            if (!empty($rule['max']) && strlen($value) > $rule['max']) {
                $errors[$field] = $label . ' must not exceed ' . $rule['max'] . ' characters.';
            }

            // Validate numeric
            if (!empty($rule['numeric']) && !is_numeric($value)) {
                $errors[$field] = $label . ' must be a number.';
            }

            // Validate date
            if (!empty($rule['date']) && !strtotime($value)) {
                $errors[$field] = $label . ' must be a valid date.';
            }

            $data[$field] = $value;
        }

        return ['valid' => empty($errors), 'errors' => $errors, 'data' => $data];
    }

    /**
     * Get pagination parameters
     */
    protected function getPagination($total, $perPage = null) {
        $perPage = $perPage ?: ITEMS_PER_PAGE;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $perPage;
        $totalPages = ceil($total / $perPage);

        return [
            'page'       => $page,
            'per_page'   => $perPage,
            'offset'     => $offset,
            'total'      => $total,
            'total_pages'=> $totalPages,
            'has_prev'   => $page > 1,
            'has_next'   => $page < $totalPages,
            'prev_page'  => $page - 1,
            'next_page'  => $page + 1
        ];
    }

    /**
     * Render a view
     */
    protected function render($view, $data = []) {
        $this->view->render($view, array_merge($this->data, $data));
    }

    /**
     * Render JSON response
     */
    protected function json($data, $code = 200) {
        jsonResponse($data, $code);
    }

    /**
     * Redirect with success message
     */
    protected function redirectWithSuccess($url, $message) {
        $this->session->flash('success', $message);
        redirect($url);
    }

    /**
     * Redirect with error message
     */
    protected function redirectWithError($url, $message) {
        $this->session->flash('error', $message);
        redirect($url);
    }

    /**
     * Get current URL
     */
    protected function currentUrl() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') .
               '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
}
