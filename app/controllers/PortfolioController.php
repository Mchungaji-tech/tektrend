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
     * Dedicated Shop & Turnkey Software Store
     * Accessible by guests, clients, and partners.
     */
    public function shop() {
        $this->view->setLayout(null);
        $this->ensurePortfolioDemosTable();
        $templates = [];
        try {
            $templates = $this->db->fetchAll("SELECT * FROM portfolio_demos WHERE is_for_sale = 1 ORDER BY sort_order ASC, id DESC");
        } catch (\Throwable $e) {
            $templates = [];
        }
        if (empty($templates)) {
            $all = self::getDefaultPrototypes();
            $templates = array_filter($all, function($p) {
                return !empty($p['is_for_sale']);
            });
        }
        $settings = $this->getSettings();
        $user = $this->auth->check() ? $this->auth->user() : null;
        $role = $this->auth->check() ? $this->auth->role() : null;
        $partner = null;
        if ($role === 'partner' && $this->auth->check()) {
            $partner = $this->db->fetch("SELECT * FROM partners WHERE user_id = ? LIMIT 1", [$this->auth->id()]);
        }
        $this->render('home/shop', [
            'templates' => $templates,
            'settings'  => $settings,
            'user'      => $user,
            'role'      => $role,
            'partner'   => $partner
        ], null);
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

        // Handle Preview Image Upload (Uploaded file takes priority)
        $previewImage = 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80';
        if (isset($_FILES['preview_image_file']) && $_FILES['preview_image_file']['error'] === UPLOAD_ERR_OK) {
            $upload = uploadFile($_FILES['preview_image_file'], 'demos');
            if ($upload['success']) {
                $previewImage = 'uploads/' . $upload['filename'];
            } else {
                $this->session->flash('error', 'Image upload failed: ' . ($upload['error'] ?? 'Unknown error'));
                $this->session->flash('old', $_POST);
                redirect('/demos/create');
                return;
            }
        } elseif (!empty($_POST['preview_image'])) {
            $previewImage = trim($_POST['preview_image']);
        }

        $price = isset($_POST['price']) ? (float)$_POST['price'] : 49.00;
        $isForSale = isset($_POST['is_for_sale']) ? (int)$_POST['is_for_sale'] : (self::isProjectForSale(['demo_url' => $data['demo_url'], 'demo_type' => $_POST['demo_type'] ?? 'external']) ? 1 : 0);

        $demoId = $this->db->insert(
            "INSERT INTO portfolio_demos (title, category, short_description, icon, demo_type, demo_url, hosting_domain, tech_stack, preview_image, award_badge, price, is_featured, is_for_sale, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['title'],
                $data['category'],
                $_POST['short_description'] ?? '',
                $_POST['icon'] ?? 'fas fa-laptop-code',
                $_POST['demo_type'] ?? 'external',
                $data['demo_url'],
                $_POST['hosting_domain'] ?? parse_url($data['demo_url'], PHP_URL_HOST) ?? 'external',
                $_POST['tech_stack'] ?? 'PHP, HTML, CSS',
                $previewImage,
                $_POST['award_badge'] ?? '',
                $price,
                isset($_POST['is_featured']) ? 1 : 0,
                $isForSale,
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

        // Handle Preview Image (Uploaded file takes priority, then URL input, else keep existing)
        $previewImage = $demo['preview_image'] ?? '';
        if (isset($_FILES['preview_image_file']) && $_FILES['preview_image_file']['error'] === UPLOAD_ERR_OK) {
            $upload = uploadFile($_FILES['preview_image_file'], 'demos');
            if ($upload['success']) {
                $previewImage = 'uploads/' . $upload['filename'];
            } else {
                $this->session->flash('error', 'Image upload failed: ' . ($upload['error'] ?? 'Unknown error'));
                redirect("/demos/$id/edit");
                return;
            }
        } elseif (isset($_POST['preview_image']) && trim($_POST['preview_image']) !== '') {
            $previewImage = trim($_POST['preview_image']);
        }

        $price = isset($_POST['price']) ? (float)$_POST['price'] : ($demo['price'] ?? 49.00);
        $isForSale = isset($_POST['is_for_sale']) ? (int)$_POST['is_for_sale'] : (isset($_POST['is_for_sale_present']) ? 0 : ($demo['is_for_sale'] ?? 1));

        $this->db->execute(
            "UPDATE portfolio_demos SET title = ?, category = ?, short_description = ?, icon = ?, demo_type = ?, demo_url = ?, hosting_domain = ?, tech_stack = ?, preview_image = ?, award_badge = ?, price = ?, is_featured = ?, is_for_sale = ?, sort_order = ? WHERE id = ?",
            [
                $data['title'],
                $data['category'],
                $_POST['short_description'] ?? '',
                $_POST['icon'] ?? 'fas fa-laptop-code',
                $_POST['demo_type'] ?? 'external',
                $data['demo_url'],
                $_POST['hosting_domain'] ?? parse_url($data['demo_url'], PHP_URL_HOST) ?? '',
                $_POST['tech_stack'] ?? 'PHP, HTML, CSS',
                $previewImage,
                $_POST['award_badge'] ?? '',
                $price,
                isset($_POST['is_featured']) ? 1 : 0,
                $isForSale,
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
                'id' => 101,
                'title' => 'Apex Logistics & Fleet Cloud · Real-Time Freight',
                'category' => 'Logistics & Supply Chain',
                'short_description' => 'Custom engineered enterprise freight platform with live GPS telemetry, automated cargo manifest generation, customs clearance vaults, and driver dispatch mobile API.',
                'icon' => 'fas fa-truck-fast',
                'demo_type' => 'external',
                'demo_url' => 'https://apexlogistics.co.ke',
                'hosting_domain' => 'apexlogistics.co.ke',
                'tech_stack' => 'PHP 8.2, MySQL 8, Leaflet GIS, WebSockets, REST API',
                'preview_image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Client Production System',
                'price' => 0.00,
                'is_featured' => 1,
                'is_for_sale' => 0
            ],
            [
                'id' => 102,
                'title' => 'Eldoret Tech Innovation Hub · Community Portal',
                'category' => 'Technology & Incubation',
                'short_description' => 'Regional innovation ecosystem platform featuring co-working desk reservations, developer fellowship application funnel, tech mentor registry, and event streaming.',
                'icon' => 'fas fa-microchip',
                'demo_type' => 'external',
                'demo_url' => 'https://eldorettechhub.org',
                'hosting_domain' => 'eldorettechhub.org',
                'tech_stack' => 'PHP MVC, MySQL, Calendar Sync, Inter Font, Tailored CSS',
                'preview_image' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Client Production System',
                'price' => 0.00,
                'is_featured' => 1,
                'is_for_sale' => 0
            ],
            [
                'id' => 103,
                'title' => 'Rift Valley Specialty Agribusiness & Commodity Auction',
                'category' => 'AgriTech & Export',
                'short_description' => 'High-volume direct trade commodity exchange platform with digital lot bidding, moisture analytics reporting, exporter KYC verification, and M-Pesa B2B escrow.',
                'icon' => 'fas fa-seedling',
                'demo_type' => 'external',
                'demo_url' => 'https://riftvalleycoffee.co.ke',
                'hosting_domain' => 'riftvalleycoffee.co.ke',
                'tech_stack' => 'PHP 8, Real-time Bidding Engine, Chart.js, M-Pesa API',
                'preview_image' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Client Production System',
                'price' => 0.00,
                'is_featured' => 1,
                'is_for_sale' => 0
            ],
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
                'is_featured' => 1,
                'is_for_sale' => 1
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
            ],
            [
                'id' => 10,
                'title' => 'FORMA · Independent Creative Studio',
                'category' => 'Creative & Design',
                'short_description' => 'Award-site inspired studio showcase with inertia scrolling, split-text reveals, pinned horizontal work gallery, cursor-following case index and magnetic interactions.',
                'icon' => 'fas fa-feather-pointed',
                'demo_type' => 'local',
                'demo_url' => '/live_demo/agency.html',
                'hosting_domain' => 'Turnkey Template',
                'tech_stack' => 'HTML5, MM Motion Engine, Scroll Pinning, Editorial Type',
                'preview_image' => 'https://images.unsplash.com/photo-1618005182384-a81a0e104d95?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Awwwards Aesthetic',
                'price' => 69.00,
                'is_featured' => 1,
                'is_for_sale' => 1
            ],
            [
                'id' => 11,
                'title' => 'Arbor Living · Signature Residences',
                'category' => 'Real Estate & Architecture',
                'short_description' => 'Cinematic property experience with Ken Burns hero, sticky philosophy section, full-bleed parallax image breaks and horizontal typology collection.',
                'icon' => 'fas fa-building',
                'demo_type' => 'local',
                'demo_url' => '/live_demo/residence.html',
                'hosting_domain' => 'Turnkey Template',
                'tech_stack' => 'HTML5, MM Motion Engine, Parallax Layers, Scroll Counters',
                'preview_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Design Award Nominee',
                'price' => 79.00,
                'is_featured' => 1,
                'is_for_sale' => 1
            ],
            [
                'id' => 12,
                'title' => 'Casa Sala · Boutique Heritage Hotel',
                'category' => 'Hospitality & Travel',
                'short_description' => 'Editorial hotel storysite with wipe-in ritual rows, pinned suite carousel, destination walk-times, marquee bands and direct booking strip.',
                'icon' => 'fas fa-hotel',
                'demo_type' => 'local',
                'demo_url' => '/live_demo/hotel.html',
                'hosting_domain' => 'Turnkey Template',
                'tech_stack' => 'HTML5, MM Motion Engine, Scroll-Pin, Warm Editorial CSS',
                'preview_image' => 'https://images.unsplash.com/photo-1564501049405-7bbd324bf842?auto=format&fit=crop&w=800&q=80',
                'award_badge' => 'Travel Aesthetic Award',
                'price' => 59.00,
                'is_featured' => 1,
                'is_for_sale' => 1
            ]
        ];
    }
}
