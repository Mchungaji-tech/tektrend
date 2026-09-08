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

    /**
     * Ensure the portfolio_demos table exists and is populated with default showcases
     */
    protected function ensurePortfolioDemosTable() {
        try {
            $this->db->execute("CREATE TABLE IF NOT EXISTS `portfolio_demos` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(255) NOT NULL,
                `category` VARCHAR(100) NOT NULL,
                `short_description` TEXT DEFAULT NULL,
                `icon` VARCHAR(100) DEFAULT 'fas fa-laptop-code',
                `demo_type` VARCHAR(50) DEFAULT 'external',
                `demo_url` VARCHAR(500) NOT NULL,
                `hosting_domain` VARCHAR(255) DEFAULT NULL,
                `tech_stack` VARCHAR(255) DEFAULT 'PHP, HTML, CSS',
                `preview_image` VARCHAR(500) DEFAULT NULL,
                `award_badge` VARCHAR(100) DEFAULT NULL,
                `is_featured` TINYINT(1) DEFAULT 1,
                `sort_order` INT DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `category` (`category`),
                KEY `is_featured` (`is_featured`),
                KEY `sort_order` (`sort_order`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            $count = (int)$this->db->fetchColumn("SELECT COUNT(*) FROM portfolio_demos");
            if ($count === 0) {
                $seedDemos = [
                    ['Graphic Design Studio', 'Creative & Design', 'Bespoke branding showcase with smooth layouts, typography, and interactive client portfolio showcases.', 'fas fa-palette', 'local', '/live_demo/graphic.html', 'Local Showcase', 'HTML5, CSS3, GSAP, JS', 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80', 'Site of the Day', 1, 1],
                    ['Digital Marketing Agency', 'Marketing & SEO', 'High-conversion marketing agency portal featuring live campaign tracking, analytics, and instant funnel booking.', 'fas fa-chart-line', 'local', '/live_demo/digital_markting.html', 'Local Showcase', 'PHP, Bootstrap, JS', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80', 'Top Conversion', 1, 2],
                    ['E-Commerce Multi-Vendor Platform', 'E-Commerce & Retail', 'Ultra-fast scalable storefront with category filtering, cart management, instant checkout, and payment gateways.', 'fas fa-store', 'local', '/live_demo/e-commerce.html', 'Local Showcase', 'PHP, MySQL, JavaScript', 'https://images.unsplash.com/photo-1556742049-0a67c5574f73?auto=format&fit=crop&w=800&q=80', 'Best Architecture', 1, 3],
                    ['Law Firm & Legal Advisory', 'Legal & Corporate', 'Prestigious law firm web platform with confidential attorney booking, case study directories, and consultation vaults.', 'fas fa-scale-balanced', 'local', '/live_demo/law_firm (2).html', 'Local Showcase', 'HTML5, CSS3, JavaScript', 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=800&q=80', 'Corporate Elite', 1, 4],
                    ['Community & Worship Center', 'Non-Profit & Community', 'Modern community hub with live media broadcasting, event calendars, donation workflows, and member registries.', 'fas fa-church', 'local', '/live_demo/church.html', 'Local Showcase', 'HTML5, CSS3, Media Player', 'https://images.unsplash.com/photo-1548625361-195b0662d083?auto=format&fit=crop&w=800&q=80', 'Community Choice', 1, 5],
                    ['Engineering & Industrial Systems', 'Engineering & Tech', 'Industrial machinery portfolio, technical specification viewers, blueprints gallery, and RFQ submission system.', 'fas fa-cogs', 'local', '/live_demo/engineering.html', 'Local Showcase', 'HTML5, Canvas, WebGL', 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80', 'Industry Benchmark', 1, 6],
                ];

                foreach ($seedDemos as $demo) {
                    $this->db->execute(
                        "INSERT INTO portfolio_demos (title, category, short_description, icon, demo_type, demo_url, hosting_domain, tech_stack, preview_image, award_badge, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                        $demo
                    );
                }
            }
            return true;
        } catch (\Throwable $e) {
            error_log('ensurePortfolioDemosTable error: ' . $e->getMessage());
            return false;
        }
    }
}
