<?php
/**
 * Portfolio & Live Demo Controller
 * Manages live demos, showcased projects, and external hosting domain linkages
 */

class PortfolioController extends Controller {

    /**
     * Public Live Demos Showcase
     */
    public function showcase() {
        $this->view->setLayout(null);
        $this->ensurePortfolioDemosTable();
        $demos = [];
        try {
            $demos = $this->db->fetchAll("SELECT * FROM portfolio_demos WHERE is_featured = 1 ORDER BY sort_order ASC, id DESC");
        } catch (\Throwable $e) {
            $demos = [];
        }
        if (empty($demos)) {
            $demos = self::getDefaultPrototypes();
        }
        $settings = $this->getSettings();
        $this->render('home/demos', ['demos' => $demos, 'settings' => $settings], null);
    }

    /**
     * Public handler for Request System & Buy Template inquiries
     */
    public function requestSystem() {
        $templateTitle = trim($_POST['template_title'] ?? 'Custom Web System');
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $actionType = trim($_POST['action_type'] ?? 'request_system'); // 'request_system' or 'buy_template'
        $license = trim($_POST['license'] ?? 'Standard Turnkey Code');
        $notes = trim($_POST['notes'] ?? '');

        if (empty($name) || empty($email)) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Please provide both your name and email address.'], 400);
            }
            $this->session->flash('error', 'Please provide both your name and email address.');
            redirect('/live-demos');
            return;
        }

        // Record inquiry in leads CRM
        try {
            $names = explode(' ', $name, 2);
            $firstName = $names[0];
            $lastName = $names[1] ?? 'Client';
            $combinedNotes = "Action: " . ($actionType === 'buy_template' ? 'Buy Template' : 'Request Custom System') . "\n" .
                             "Template: " . $templateTitle . "\n" .
                             "Package/License: " . $license . "\n" .
                             "Notes: " . $notes;

            $this->db->insert(
                "INSERT INTO leads (first_name, last_name, email, phone, company, status, priority, notes, created_at) VALUES (?, ?, ?, ?, ?, 'new', 'high', ?, NOW())",
                [$firstName, $lastName, $email, $phone, $company, $combinedNotes]
            );
            auditLog('create', 'leads', null, "Inquiry from Live Demos for '$templateTitle' by $name");
        } catch (\Throwable $e) {
            error_log('Failed to store demo inquiry lead: ' . $e->getMessage());
        }

        $message = ($actionType === 'buy_template')
            ? "Your template purchase order for '$templateTitle' has been received! Our engineering team will contact you shortly with the turnkey source code package."
            : "Your custom system request for '$templateTitle' has been submitted! Our solutions architect will contact you shortly.";

        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => $message]);
            return;
        }

        $this->session->flash('success', $message);
        redirect('/live-demos');
    }

    /**
     * Public Showcase or Admin Management
     */
    public function index() {
        if (!$this->auth->check()) {
            $this->showcase();
            return;
        }

        $this->ensurePortfolioDemosTable();
        $demos = [];
        try {
            $demos = $this->db->fetchAll("SELECT * FROM portfolio_demos ORDER BY sort_order ASC, id DESC");
        } catch (\Throwable $e) {
            if ($this->ensurePortfolioDemosTable()) {
                try {
                    $demos = $this->db->fetchAll("SELECT * FROM portfolio_demos ORDER BY sort_order ASC, id DESC");
                } catch (\Throwable $ex) {
                    $demos = [];
                }
            }
        }
        $this->render('demos/index', ['demos' => $demos]);
    }

    /**
     * Admin: Create new demo / domain project
     */
    public function create() {
        $this->requireAuth();
        $this->ensurePortfolioDemosTable();
        $this->render('demos/create');
    }

    /**
     * Admin: Store new demo
     */
    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->ensurePortfolioDemosTable();

        $rules = [
            'title' => ['required' => true, 'label' => 'Project Title'],
            'category' => ['required' => true, 'label' => 'Category'],
            'demo_url' => ['required' => true, 'label' => 'Demo / Hosting URL']
        ];

        $validation = $this->validatePost($rules);
        if (!$validation['valid']) {
            $this->session->flash('error', reset($validation['errors']));
            $this->session->flash('old', $_POST);
            redirect('/demos/create');
        }

        $data = $validation['data'];

        $demoId = $this->db->insert(
            "INSERT INTO portfolio_demos (title, category, short_description, icon, demo_type, demo_url, hosting_domain, tech_stack, preview_image, award_badge, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['title'],
                $data['category'],
                $_POST['short_description'] ?? '',
                $_POST['icon'] ?? 'fas fa-laptop-code',
                $_POST['demo_type'] ?? 'external',
                $data['demo_url'],
                $_POST['hosting_domain'] ?? parse_url($data['demo_url'], PHP_URL_HOST) ?? 'external',
                $_POST['tech_stack'] ?? 'PHP, HTML, CSS',
                $_POST['preview_image'] ?? 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80',
                $_POST['award_badge'] ?? '',
                isset($_POST['is_featured']) ? 1 : 0,
                (int)($_POST['sort_order'] ?? 0)
            ]
        );

        auditLog('create', 'portfolio_demos', $demoId, 'Created project demo: ' . $data['title']);
        $this->redirectWithSuccess('/demos', 'Live demo project linked successfully!');
    }

    /**
     * Admin: Edit project demo
     */
    public function edit($id) {
        $this->requireAuth();
        $this->ensurePortfolioDemosTable();
        $demo = $this->db->fetch("SELECT * FROM portfolio_demos WHERE id = ?", [$id]);
        if (!$demo) {
            $this->session->flash('error', 'Demo project not found.');
            redirect('/demos');
        }
        $this->render('demos/edit', ['demo' => $demo]);
    }

    /**
     * Admin: Update demo
     */
    public function update($id) {
        $this->requireAuth();
        $this->verifyCsrf();

        $demo = $this->db->fetch("SELECT * FROM portfolio_demos WHERE id = ?", [$id]);
        if (!$demo) {
            $this->session->flash('error', 'Demo project not found.');
            redirect('/demos');
        }

        $rules = [
            'title' => ['required' => true, 'label' => 'Project Title'],
            'category' => ['required' => true, 'label' => 'Category'],
            'demo_url' => ['required' => true, 'label' => 'Demo / Hosting URL']
        ];

        $validation = $this->validatePost($rules);
        if (!$validation['valid']) {
            $this->session->flash('error', reset($validation['errors']));
            redirect("/demos/$id/edit");
        }

        $data = $validation['data'];

        $this->db->execute(
            "UPDATE portfolio_demos SET title = ?, category = ?, short_description = ?, icon = ?, demo_type = ?, demo_url = ?, hosting_domain = ?, tech_stack = ?, preview_image = ?, award_badge = ?, is_featured = ?, sort_order = ? WHERE id = ?",
            [
                $data['title'],
                $data['category'],
                $_POST['short_description'] ?? '',
                $_POST['icon'] ?? 'fas fa-laptop-code',
                $_POST['demo_type'] ?? 'external',
                $data['demo_url'],
                $_POST['hosting_domain'] ?? parse_url($data['demo_url'], PHP_URL_HOST) ?? '',
                $_POST['tech_stack'] ?? 'PHP, HTML, CSS',
                $_POST['preview_image'] ?? '',
                $_POST['award_badge'] ?? '',
                isset($_POST['is_featured']) ? 1 : 0,
                (int)($_POST['sort_order'] ?? 0),
                $id
            ]
        );

        auditLog('update', 'portfolio_demos', $id, 'Updated demo: ' . $data['title']);
        $this->redirectWithSuccess('/demos', 'Demo project updated successfully!');
    }

    /**
     * Admin: Delete demo
     */
    public function destroy($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->ensurePortfolioDemosTable();

        $demo = $this->db->fetch("SELECT * FROM portfolio_demos WHERE id = ?", [$id]);
        if ($demo) {
            $this->db->execute("DELETE FROM portfolio_demos WHERE id = ?", [$id]);
            auditLog('delete', 'portfolio_demos', $id, 'Deleted demo: ' . $demo['title']);
            $this->redirectWithSuccess('/demos', 'Demo project removed.');
        } else {
            $this->session->flash('error', 'Demo project not found.');
            redirect('/demos');
        }
    }

    private function getSettings() {
        $settings = [];
        try {
            $rows = $this->db->fetchAll("SELECT `key`, `value` FROM settings");
            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $settings[$row['key']] = $row['value'];
                }
            }
        } catch (\Throwable $e) {
            $settings = [];
        }
        return $settings;
    }

    public static function getDefaultPrototypes() {
        return [
            [
                'id' => 1,
                'title' => 'Studio Maven · Creative Studio',
                'category' => 'Creative & Design',
                'short_description' => 'Minimalist luxury branding portfolio with case studies, smooth motion reels, interactive typography, and instant project booking.',
                'icon' => 'fas fa-palette',
                'demo_type' => 'local',
                'demo_url' => '/live_demo/graphic.html',
                'hosting_domain' => 'Turnkey Template',
                'tech_stack' => 'HTML5, CSS3, GSAP, Responsive',
                'preview_image' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Site of the Day',
                'price' => 49.00,
                'is_featured' => 1
            ],
            [
                'id' => 2,
                'title' => 'GrowthPulse · Marketing Agency',
                'category' => 'Marketing & SEO',
                'short_description' => 'Growth-driven marketing agency portal featuring live campaign tracking, analytics metrics, interactive ROI calculator, and lead generation.',
                'icon' => 'fas fa-chart-line',
                'demo_type' => 'local',
                'demo_url' => '/live_demo/digital_markting.html',
                'hosting_domain' => 'Turnkey Template',
                'tech_stack' => 'HTML5, Bootstrap, CSS3, Chart.js',
                'preview_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Top Conversion',
                'price' => 59.00,
                'is_featured' => 1
            ],
            [
                'id' => 3,
                'title' => 'LuxeCart · Modern E-Commerce',
                'category' => 'E-Commerce & Retail',
                'short_description' => 'High-speed storefront with dynamic category filtering, cart management, instant checkout flow, customer wishlist, and payment readiness.',
                'icon' => 'fas fa-store',
                'demo_type' => 'local',
                'demo_url' => '/live_demo/e-commerce.html',
                'hosting_domain' => 'Turnkey Template',
                'tech_stack' => 'HTML5, CSS3, JavaScript, eCommerce UI',
                'preview_image' => 'https://images.unsplash.com/photo-1556742049-0a67c5574f73?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Best Architecture',
                'price' => 79.00,
                'is_featured' => 1
            ],
            [
                'id' => 4,
                'title' => 'Vanguard & Sterling · Law Firm',
                'category' => 'Legal & Corporate',
                'short_description' => 'High-trust legal advisory platform with practice areas directory, attorney profiles, consultation booking vaults, and case study breakdowns.',
                'icon' => 'fas fa-scale-balanced',
                'demo_type' => 'local',
                'demo_url' => '/live_demo/law_firm.html',
                'hosting_domain' => 'Turnkey Template',
                'tech_stack' => 'HTML5, Modern CSS, JavaScript, Legal Portal',
                'preview_image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Corporate Elite',
                'price' => 69.00,
                'is_featured' => 1
            ],
            [
                'id' => 5,
                'title' => 'Grace Fellowship · Community Hub',
                'category' => 'Non-Profit & Community',
                'short_description' => 'Welcoming community portal with sermon live broadcast layout, event timetables, online donation workflows, and member connection registry.',
                'icon' => 'fas fa-church',
                'demo_type' => 'local',
                'demo_url' => '/live_demo/church.html',
                'hosting_domain' => 'Turnkey Template',
                'tech_stack' => 'HTML5, CSS3, Audio Stream UI, Grid',
                'preview_image' => 'https://images.unsplash.com/photo-1548625361-195b0662d083?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Community Choice',
                'price' => 39.00,
                'is_featured' => 1
            ],
            [
                'id' => 6,
                'title' => 'Titan Engineering · Industrial Systems',
                'category' => 'Engineering & Tech',
                'short_description' => 'Industrial automation showcase with technical specification viewers, interactive blueprint galleries, equipment catalogs, and RFQ request forms.',
                'icon' => 'fas fa-cogs',
                'demo_type' => 'local',
                'demo_url' => '/live_demo/engineering.html',
                'hosting_domain' => 'Turnkey Template',
                'tech_stack' => 'HTML5, Canvas, WebGL, Industrial CSS',
                'preview_image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Industry Benchmark',
                'price' => 79.00,
                'is_featured' => 1
            ],
            [
                'id' => 7,
                'title' => 'Le Jardin · Gourmet Restaurant',
                'category' => 'Hospitality & Dining',
                'short_description' => 'Atmospheric dining experience platform with seasonal menu showcases, chef stories, interactive table reservation system, and food gallery.',
                'icon' => 'fas fa-utensils',
                'demo_type' => 'local',
                'demo_url' => '/live_demo/restaurant.html',
                'hosting_domain' => 'Turnkey Template',
                'tech_stack' => 'HTML5, Playfair Display, CSS Animations',
                'preview_image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Michelin Aesthetic',
                'price' => 49.00,
                'is_featured' => 1
            ],
            [
                'id' => 8,
                'title' => 'Verve · Modern Tech Magazine',
                'category' => 'Publishing & Media',
                'short_description' => 'Ultra-sleek editorial platform designed for high readership with dark-mode aesthetic, typography hierarchy, category tabs, and newsletter forms.',
                'icon' => 'fas fa-newspaper',
                'demo_type' => 'local',
                'demo_url' => '/live_demo/magazine.html',
                'hosting_domain' => 'Turnkey Template',
                'tech_stack' => 'HTML5, Modern Typography, Responsive Grid',
                'preview_image' => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Editorial Pick',
                'price' => 39.00,
                'is_featured' => 1
            ],
            [
                'id' => 9,
                'title' => 'Nexus Commercial · Enterprise SaaS',
                'category' => 'Fintech & SaaS',
                'short_description' => 'Enterprise-grade technology portal with interactive software feature matrices, live pricing tiers, API doc viewer, and sales funnel.',
                'icon' => 'fas fa-network-wired',
                'demo_type' => 'local',
                'demo_url' => '/live_demo/nexus.html',
                'hosting_domain' => 'Turnkey Template',
                'tech_stack' => 'HTML5, CSS3, Inter Font, SaaS UI',
                'preview_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Developer Award',
                'price' => 89.00,
                'is_featured' => 1
            ]
        ];
    }
}
