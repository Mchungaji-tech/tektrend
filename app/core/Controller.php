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
     * Render a view with optional layout override
     */
    protected function render($view, $data = [], $layout = 'default') {
        if ($layout !== 'default') {
            $this->view->setLayout($layout);
        }
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
     * Check if request is AJAX
     */
    protected function isAjax() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
               (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false);
    }

    /**
     * Ensure the portfolio_demos table exists and is populated with all live demo prototypes
     */
    protected function ensurePortfolioDemosTable() {
        try {
            $this->db->execute("CREATE TABLE IF NOT EXISTS `portfolio_demos` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(255) NOT NULL,
                `category` VARCHAR(100) NOT NULL,
                `short_description` TEXT DEFAULT NULL,
                `icon` VARCHAR(100) DEFAULT 'fas fa-laptop-code',
                `demo_type` VARCHAR(50) DEFAULT 'local',
                `demo_url` VARCHAR(500) NOT NULL,
                `hosting_domain` VARCHAR(255) DEFAULT NULL,
                `tech_stack` VARCHAR(255) DEFAULT 'PHP, HTML, CSS',
                `preview_image` VARCHAR(500) DEFAULT NULL,
                `award_badge` VARCHAR(100) DEFAULT NULL,
                `price` DECIMAL(10,2) DEFAULT 49.00,
                `is_featured` TINYINT(1) DEFAULT 1,
                `sort_order` INT DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `category` (`category`),
                KEY `is_featured` (`is_featured`),
                KEY `sort_order` (`sort_order`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            // Ensure price column exists if table was previously created without it
            try {
                $cols = $this->db->fetchAll("SHOW COLUMNS FROM `portfolio_demos` LIKE 'price'");
                if (empty($cols)) {
                    $this->db->execute("ALTER TABLE `portfolio_demos` ADD COLUMN `price` DECIMAL(10,2) DEFAULT 49.00 AFTER `award_badge`");
                }
            } catch (\Throwable $ex) {}

            $seedDemos = [
                ['Studio Maven · Creative Studio', 'Creative & Design', 'Minimalist luxury branding portfolio with case studies, smooth motion reels, interactive typography, and instant project booking.', 'fas fa-palette', 'local', '/live_demo/graphic.html', 'Turnkey Template', 'HTML5, CSS3, GSAP, Responsive', 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80', 'Site of the Day', 49.00, 1, 1],
                ['GrowthPulse · Marketing Agency', 'Marketing & SEO', 'Growth-driven marketing agency portal featuring live campaign tracking, analytics metrics, interactive ROI calculator, and lead generation.', 'fas fa-chart-line', 'local', '/live_demo/digital_markting.html', 'Turnkey Template', 'HTML5, Bootstrap, CSS3, Chart.js', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80', 'Top Conversion', 59.00, 1, 2],
                ['LuxeCart · Modern E-Commerce', 'E-Commerce & Retail', 'High-speed storefront with dynamic category filtering, cart management, instant checkout flow, customer wishlist, and payment readiness.', 'fas fa-store', 'local', '/live_demo/e-commerce.html', 'Turnkey Template', 'HTML5, CSS3, JavaScript, eCommerce UI', 'https://images.unsplash.com/photo-1556742049-0a67c5574f73?auto=format&fit=crop&w=800&q=80', 'Best Architecture', 79.00, 1, 3],
                ['Vanguard & Sterling · Law Firm', 'Legal & Corporate', 'High-trust legal advisory platform with practice areas directory, attorney profiles, consultation booking vaults, and case study breakdowns.', 'fas fa-scale-balanced', 'local', '/live_demo/law_firm (2).html', 'Turnkey Template', 'HTML5, Modern CSS, JavaScript, Legal Portal', 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=800&q=80', 'Corporate Elite', 69.00, 1, 4],
                ['Grace Fellowship · Community Hub', 'Non-Profit & Community', 'Welcoming community portal with sermon live broadcast layout, event timetables, online donation workflows, and member connection registry.', 'fas fa-church', 'local', '/live_demo/church.html', 'Turnkey Template', 'HTML5, CSS3, Audio Stream UI, Grid', 'https://images.unsplash.com/photo-1548625361-195b0662d083?auto=format&fit=crop&w=800&q=80', 'Community Choice', 39.00, 1, 5],
                ['Titan Engineering · Industrial Systems', 'Engineering & Tech', 'Industrial automation showcase with technical specification viewers, interactive blueprint galleries, equipment catalogs, and RFQ request forms.', 'fas fa-cogs', 'local', '/live_demo/engineering.html', 'Turnkey Template', 'HTML5, Canvas, WebGL, Industrial CSS', 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80', 'Industry Benchmark', 79.00, 1, 6],
                ['Le Jardin · Gourmet Restaurant', 'Hospitality & Dining', 'Atmospheric dining experience platform with seasonal menu showcases, chef stories, interactive table reservation system, and food gallery.', 'fas fa-utensils', 'local', '/live_demo/restaurant.html', 'Turnkey Template', 'HTML5, Playfair Display, CSS Animations', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80', 'Michelin Aesthetic', 49.00, 1, 7],
                ['Verve · Modern Tech Magazine', 'Publishing & Media', 'Ultra-sleek editorial platform designed for high readership with dark-mode aesthetic, typography hierarchy, category tabs, and newsletter forms.', 'fas fa-newspaper', 'local', '/live_demo/magazine.html', 'Turnkey Template', 'HTML5, Modern Typography, Responsive Grid', 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=800&q=80', 'Editorial Pick', 39.00, 1, 8],
                ['Nexus Commercial · Enterprise SaaS', 'Fintech & SaaS', 'Enterprise-grade technology portal with interactive software feature matrices, live pricing tiers, API doc viewer, and sales funnel.', 'fas fa-network-wired', 'local', '/live_demo/nexus.html', 'Turnkey Template', 'HTML5, CSS3, Inter Font, SaaS UI', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80', 'Developer Award', 89.00, 1, 9]
            ];

            try {
                $existing = $this->db->fetchAll("SELECT demo_url FROM portfolio_demos");
                $existingUrls = array_column($existing, 'demo_url');

                foreach ($seedDemos as $demo) {
                    if (!in_array($demo[5], $existingUrls)) {
                        $this->db->execute(
                            "INSERT INTO portfolio_demos (title, category, short_description, icon, demo_type, demo_url, hosting_domain, tech_stack, preview_image, award_badge, price, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                            $demo
                        );
                    }
                }
            } catch (\Throwable $ex) {}

            return true;
        } catch (\Throwable $e) {
            error_log('ensurePortfolioDemosTable error: ' . $e->getMessage());
            return false;
        }
    }
}
