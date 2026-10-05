<?php
/**
 * Content Controller
 * Comprehensive Visual CMS Studio for Front-End Content, Photos & Marketing Copy
 */

class ContentController extends Controller {

    /**
     * Default front-end content catalog with initial values
     */
    private $defaultContent = [
        // Hero Section
        'hero_eyebrow' => ['title' => 'Hero Eyebrow Tag', 'type' => 'text', 'section' => 'hero', 'content' => '[ SOFTWARE ARCHITECTURE & DIGITAL INNOVATION ]'],
        'hero_tag_live' => ['title' => 'Hero Live Status Tag', 'type' => 'text', 'section' => 'hero', 'content' => 'ACCEPTING Q4 / 2026 COMMISSIONS'],
        'hero_brand_letters' => ['title' => 'Hero Brand Mark Letters', 'type' => 'text', 'section' => 'hero', 'content' => 'TEKTREND'],
        'hero_headline' => ['title' => 'Hero Main Headline Paragraph', 'type' => 'text', 'section' => 'hero', 'content' => 'We are Tektrend Softwares. We architect world-class web systems, cloud dashboards, and turnkey production platforms based in Eldoret, Kenya.'],
        'hero_featured_btn_text' => ['title' => 'Hero Featured Project Button Text', 'type' => 'text', 'section' => 'hero', 'content' => '01 / Featured Project'],
        'hero_explore_btn_text' => ['title' => 'Hero Explore Works Button Text', 'type' => 'text', 'section' => 'hero', 'content' => 'Explore Works'],
        'hero_image' => ['title' => 'Hero Showcase Photo', 'type' => 'image', 'section' => 'hero', 'content' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=85'],
        'hero_discipline_1' => ['title' => 'Hero Discipline #1', 'type' => 'text', 'section' => 'hero', 'content' => 'PHP 8 MVC Architecture'],
        'hero_discipline_2' => ['title' => 'Hero Discipline #2', 'type' => 'text', 'section' => 'hero', 'content' => 'Google Workspace ERP'],
        'hero_discipline_3' => ['title' => 'Hero Discipline #3', 'type' => 'text', 'section' => 'hero', 'content' => 'Real-time Dashboards'],
        'hero_discipline_4' => ['title' => 'Hero Discipline #4', 'type' => 'text', 'section' => 'hero', 'content' => 'Turnkey Codebases'],

        // Capabilities & Services (4 Core Services with Photos)
        'services_badge' => ['title' => 'Services Section Badge', 'type' => 'text', 'section' => 'services', 'content' => '[ SYSTEM CAPABILITIES & EXPERTISE ]'],
        'services_title' => ['title' => 'Services Section Title', 'type' => 'text', 'section' => 'services', 'content' => 'What We Architect'],
        'services_subtitle' => ['title' => 'Services Section Subtitle', 'type' => 'text', 'section' => 'services', 'content' => 'We blend uncompromising engineering standards with French creative agency aesthetics to build systems that scale effortlessly.'],
        
        'service_1_num' => ['title' => 'Service 1 Number', 'type' => 'text', 'section' => 'services', 'content' => '00'],
        'service_1_title' => ['title' => 'Service 1 Title', 'type' => 'text', 'section' => 'services', 'content' => 'Enterprise Software Architecture'],
        'service_1_desc' => ['title' => 'Service 1 Description', 'type' => 'text', 'section' => 'services', 'content' => 'Custom PHP 8.x MVC backends, relational schema design, role hierarchies, audit logging, and resilient micro-framework foundations.'],
        'service_1_image' => ['title' => 'Service 1 Preview Photo', 'type' => 'image', 'section' => 'services', 'content' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80'],

        'service_2_num' => ['title' => 'Service 2 Number', 'type' => 'text', 'section' => 'services', 'content' => '01'],
        'service_2_title' => ['title' => 'Service 2 Title', 'type' => 'text', 'section' => 'services', 'content' => 'Google Workspace & Cloud Automation'],
        'service_2_desc' => ['title' => 'Service 2 Description', 'type' => 'text', 'section' => 'services', 'content' => 'Google Apps Script enterprise ERPs, automated Sheets-to-Docs reporting, Gmail outreach pipelines, and zero-maintenance cloud workflows.'],
        'service_2_image' => ['title' => 'Service 2 Preview Photo', 'type' => 'image', 'section' => 'services', 'content' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80'],

        'service_3_num' => ['title' => 'Service 3 Number', 'type' => 'text', 'section' => 'services', 'content' => '02'],
        'service_3_title' => ['title' => 'Service 3 Title', 'type' => 'text', 'section' => 'services', 'content' => 'Real-time Dashboards & School Systems'],
        'service_3_desc' => ['title' => 'Service 3 Description', 'type' => 'text', 'section' => 'services', 'content' => 'Interactive metric suites with Chart.js, attendance tracking, automated grading pipelines, financial ledgers, and teleconferencing.'],
        'service_3_image' => ['title' => 'Service 3 Preview Photo', 'type' => 'image', 'section' => 'services', 'content' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=600&q=80'],

        'service_4_num' => ['title' => 'Service 4 Number', 'type' => 'text', 'section' => 'services', 'content' => '03'],
        'service_4_title' => ['title' => 'Service 4 Title', 'type' => 'text', 'section' => 'services', 'content' => 'Turnkey Web Templates & Source Code'],
        'service_4_desc' => ['title' => 'Service 4 Description', 'type' => 'text', 'section' => 'services', 'content' => 'Award-caliber HTML5/CSS3 prototypes, accessible typography, fluid scroll physics, and instant deployment packs for founders and businesses.'],
        'service_4_image' => ['title' => 'Service 4 Preview Photo', 'type' => 'image', 'section' => 'services', 'content' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80'],

        // Trust Metrics
        'metric_1_val' => ['title' => 'Metric 1 Value', 'type' => 'text', 'section' => 'metrics', 'content' => '99.9%'],
        'metric_1_lbl' => ['title' => 'Metric 1 Label', 'type' => 'text', 'section' => 'metrics', 'content' => 'System Uptime Architecture'],
        'metric_2_val' => ['title' => 'Metric 2 Value', 'type' => 'text', 'section' => 'metrics', 'content' => '< 85ms'],
        'metric_2_lbl' => ['title' => 'Metric 2 Label', 'type' => 'text', 'section' => 'metrics', 'content' => 'Server Response Latency'],
        'metric_3_val' => ['title' => 'Metric 3 Value', 'type' => 'text', 'section' => 'metrics', 'content' => '15+'],
        'metric_3_lbl' => ['title' => 'Metric 3 Label', 'type' => 'text', 'section' => 'metrics', 'content' => 'Enterprise Deployments'],
        'metric_4_val' => ['title' => 'Metric 4 Value', 'type' => 'text', 'section' => 'metrics', 'content' => '100%'],
        'metric_4_lbl' => ['title' => 'Metric 4 Label', 'type' => 'text', 'section' => 'metrics', 'content' => 'Turnkey Delivery Guarantee'],

        // Consultation & Zoom Scoping
        'consult_badge' => ['title' => 'Consultation Eyebrow Badge', 'type' => 'text', 'section' => 'consultation', 'content' => '[ FREE 45-MINUTE ARCHITECTURAL SCOPING ]'],
        'consult_title' => ['title' => 'Consultation Headline', 'type' => 'text', 'section' => 'consultation', 'content' => 'Book 1-on-1 Zoom Session'],
        'consult_desc' => ['title' => 'Consultation Pitch Description', 'type' => 'text', 'section' => 'consultation', 'content' => 'Discuss your enterprise software scope, cloud automation needs, or turnkey prototype deployment directly with our Principal Solutions Architect.'],
        'consult_btn_text' => ['title' => 'Consultation Button Text', 'type' => 'text', 'section' => 'consultation', 'content' => 'Schedule Zoom Session'],
        'consult_modal_title' => ['title' => 'Consultation Modal Title', 'type' => 'text', 'section' => 'consultation', 'content' => 'Book Scoping Consultation'],
        'consult_modal_subtitle' => ['title' => 'Consultation Modal Subtitle', 'type' => 'text', 'section' => 'consultation', 'content' => 'Select your preferred slot to receive an instant Zoom meeting link & calendar invite.'],

        // Investor Relations Modal
        'invest_badge' => ['title' => 'Investor Eyebrow Badge', 'type' => 'text', 'section' => 'investor', 'content' => 'Investor Relations & Growth Capital'],
        'invest_title' => ['title' => 'Investor Modal Title', 'type' => 'text', 'section' => 'investor', 'content' => 'Invest in Tektrend Softwares'],
        'invest_subtitle' => ['title' => 'Investor Modal Subtitle', 'type' => 'text', 'section' => 'investor', 'content' => 'We are Tektrend Softwares based in Eldoret, Kenya. Partner with East Africa\'s elite software architecture and turnkey digital systems powerhouse.'],
        'invest_stat_1_val' => ['title' => 'Investor Stat 1 Value', 'type' => 'text', 'section' => 'investor', 'content' => '11+'],
        'invest_stat_1_lbl' => ['title' => 'Investor Stat 1 Label', 'type' => 'text', 'section' => 'investor', 'content' => 'Live Turnkey Platforms'],
        'invest_stat_2_val' => ['title' => 'Investor Stat 2 Value', 'type' => 'text', 'section' => 'investor', 'content' => 'Eldoret, KE'],
        'invest_stat_2_lbl' => ['title' => 'Investor Stat 2 Label', 'type' => 'text', 'section' => 'investor', 'content' => 'East Africa Tech Hub'],
        'invest_stat_3_val' => ['title' => 'Investor Stat 3 Value', 'type' => 'text', 'section' => 'investor', 'content' => '78%+'],
        'invest_stat_3_lbl' => ['title' => 'Investor Stat 3 Label', 'type' => 'text', 'section' => 'investor', 'content' => 'SaaS Software Margin'],

        // Footer & Corporate Identity
        'footer_brand_title' => ['title' => 'Footer Brand Title', 'type' => 'text', 'section' => 'footer', 'content' => 'Tektrend Softwares'],
        'footer_tagline' => ['title' => 'Footer Corporate Bio', 'type' => 'text', 'section' => 'footer', 'content' => 'We are Tektrend Softwares. Bespoke software architecture, digital systems, and cloud engineering based in Eldoret, Kenya.'],
        'footer_headquarters' => ['title' => 'Footer Office Location', 'type' => 'text', 'section' => 'footer', 'content' => "Tektrend Softwares, Eldoret, Kenya\nGlobal Engineering Retainers"],
        'footer_big_mark' => ['title' => 'Footer Stamped Big Mark', 'type' => 'text', 'section' => 'footer', 'content' => 'TEKTREND'],
        'footer_copyright' => ['title' => 'Footer Copyright Line', 'type' => 'text', 'section' => 'footer', 'content' => 'Tektrend Softwares · Eldoret, Kenya · We are Tektrend Softwares'],

        // Navigation Teaser
        'nav_teaser_img' => ['title' => 'Nav Drawer Teaser Photo', 'type' => 'image', 'section' => 'navigation', 'content' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80'],
        'nav_teaser_title' => ['title' => 'Nav Drawer Teaser Title', 'type' => 'text', 'section' => 'navigation', 'content' => 'Nexus Commercial ERP'],

        // SEO & Social
        'meta_title' => ['title' => 'Browser Title Tag', 'type' => 'text', 'section' => 'seo', 'content' => 'Tektrend Softwares · Eldoret, Kenya · Software Architecture & Creative Studio'],
        'meta_description' => ['title' => 'Meta Description Tag', 'type' => 'text', 'section' => 'seo', 'content' => 'Tektrend Softwares · Enterprise software architecture, digital innovation, cloud dashboards, and turnkey production platforms based in Eldoret, Kenya. We are Tektrend Softwares.'],
        'meta_keywords' => ['title' => 'Meta Keywords Tag', 'type' => 'text', 'section' => 'seo', 'content' => 'Tektrend Softwares, Tektrend Softwares Eldoret Kenya, We are Tektrend Softwares, software architecture, web development, custom software, PHP 8 MVC, Google Apps Script, dashboards, Eldoret, Kenya, creative agency'],
        'og_image' => ['title' => 'OpenGraph Sharing Photo', 'type' => 'image', 'section' => 'seo', 'content' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=85'],
    ];

    /**
     * Visual CMS Studio Index
     */
    public function index() {
        $this->requireAuth();
        $this->ensureContentDefaults();

        $activeTab = $_GET['tab'] ?? 'hero';

        // Load all content rows indexed by key
        $contentMap = [];
        try {
            $rows = $this->db->fetchAll("SELECT * FROM content WHERE is_active = 1");
            foreach ($rows as $r) {
                $contentMap[$r['key']] = $r;
            }
        } catch (\Throwable $e) {
            $contentMap = [];
        }

        // Load contact settings
        $settings = [];
        try {
            $sRows = $this->db->fetchAll("SELECT `key`, `value` FROM settings");
            foreach ($sRows as $sr) {
                $settings[$sr['key']] = $sr['value'];
            }
        } catch (\Throwable $e) {
            $settings = [];
        }

        // Load portfolio demos for the showcase tab
        $portfolioDemos = [];
        try {
            $portfolioDemos = $this->db->fetchAll("SELECT * FROM portfolio_demos ORDER BY sort_order ASC, id ASC");
        } catch (\Throwable $e) {
            $portfolioDemos = [];
        }

        $this->render('content/index', [
            'contentMap'     => $contentMap,
            'defaultContent' => $this->defaultContent,
            'settings'       => $settings,
            'portfolioDemos' => $portfolioDemos,
            'activeTab'      => $activeTab
        ]);
    }

    /**
     * Bulk save all CMS fields from the Visual CMS Studio
     */
    public function bulkUpdate() {
        $this->requireAuth();
        $this->verifyCsrf();

        $userId = $this->auth->id();
        $tab = $_POST['tab'] ?? 'hero';

        // 1. Process File Uploads for any image inputs
        if (!empty($_FILES)) {
            $uploadDir = UPLOAD_PATH . '/content';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }

            foreach ($_FILES as $inputName => $file) {
                if (isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
                    // Extract corresponding key (e.g., 'file_hero_image' -> 'hero_image')
                    $targetKey = preg_replace('/^file_/', '', $inputName);
                    $result = uploadFile($file, 'content');
                    if ($result['success']) {
                        $savedUrl = '/uploads/' . $result['filename'];
                        $this->saveContentKey($targetKey, $savedUrl, 'image', $userId);
                    }
                }
            }
        }

        // 2. Process Text & URL inputs from POST
        if (isset($_POST['content']) && is_array($_POST['content'])) {
            foreach ($_POST['content'] as $key => $val) {
                $val = trim($val);
                // Don't overwrite if an image file was just uploaded for this key
                $fileKey = 'file_' . $key;
                if (!empty($_FILES[$fileKey]['name']) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                    continue;
                }
                $type = isset($this->defaultContent[$key]['type']) ? $this->defaultContent[$key]['type'] : 'text';
                $this->saveContentKey($key, $val, $type, $userId);
            }
        }

        // 3. Process Settings inputs (phone, email, whatsapp, address, social)
        if (isset($_POST['settings']) && is_array($_POST['settings'])) {
            foreach ($_POST['settings'] as $sKey => $sVal) {
                $sVal = trim($sVal);
                $this->saveSettingKey($sKey, $sVal);
            }
        }

        // 4. Process Project Showcase photo/title updates
        if (isset($_POST['demos']) && is_array($_POST['demos'])) {
            foreach ($_POST['demos'] as $demoId => $demoData) {
                $demoId = (int)$demoId;
                if ($demoId <= 0) continue;

                $demoTitle = trim($demoData['title'] ?? '');
                $demoCategory = trim($demoData['category'] ?? '');
                $demoPrice = (float)($demoData['price'] ?? 49.00);
                $demoImg = trim($demoData['preview_image'] ?? '');
                $demoUrl = trim($demoData['demo_url'] ?? '');

                // Check for demo file upload
                if (!empty($_FILES['demo_file_' . $demoId]['name']) && $_FILES['demo_file_' . $demoId]['error'] === UPLOAD_ERR_OK) {
                    $res = uploadFile($_FILES['demo_file_' . $demoId], 'demos');
                    if ($res['success']) {
                        $demoImg = '/uploads/' . $res['filename'];
                    }
                }

                try {
                    $this->db->execute(
                        "UPDATE portfolio_demos SET title = ?, category = ?, price = ?, preview_image = ?, demo_url = ?, updated_at = NOW() WHERE id = ?",
                        [$demoTitle, $demoCategory, $demoPrice, $demoImg, $demoUrl, $demoId]
                    );
                } catch (\Throwable $e) {}
            }
        }

        auditLog('bulk_update', 'content', null, 'Bulk updated CMS studio fields in tab: ' . $tab);
        $this->session->flash('success', 'Front-End CMS content and photos updated successfully!');
        redirect('/content?tab=' . urlencode($tab));
    }

    /**
     * Single content edit view
     */
    public function edit($key) {
        $this->requireAuth();
        $content = $this->db->fetch("SELECT * FROM content WHERE `key` = ? OR id = ?", [$key, is_numeric($key) ? (int)$key : 0]);
        if (!$content) {
            $this->session->flash('error', 'Content item not found.');
            redirect('/content');
        }
        $this->render('content/edit', ['content' => $content]);
    }

    /**
     * Single content update
     */
    public function update($key) {
        $this->requireAuth();
        $this->verifyCsrf();

        $content = $this->db->fetch("SELECT * FROM content WHERE `key` = ? OR id = ?", [$key, is_numeric($key) ? (int)$key : 0]);
        if (!$content) {
            $this->session->flash('error', 'Content item not found.');
            redirect('/content');
        }

        $userId = $this->auth->id();
        $newContent = $_POST['content'] ?? '';

        // Check if an image file was uploaded
        if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $res = uploadFile($_FILES['image_file'], 'content');
            if ($res['success']) {
                $newContent = '/uploads/' . $res['filename'];
            }
        }

        // Save to history
        try {
            $this->db->insert(
                "INSERT INTO content_history (content_id, content, changed_by) VALUES (?, ?, ?)",
                [$content['id'], $content['content'], $userId]
            );
        } catch (\Throwable $e) {}

        $this->db->execute(
            "UPDATE content SET title = ?, content = ?, type = ?, is_active = ?, updated_at = NOW() WHERE id = ?",
            [
                $_POST['title'] ?? $content['title'],
                $newContent,
                $_POST['type'] ?? $content['type'],
                isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1,
                $content['id']
            ]
        );

        auditLog('update', 'content', $content['id'], 'Updated content key: ' . $content['key']);
        $this->redirectWithSuccess('/content', 'Content section updated successfully!');
    }

    /**
     * Save or insert single key into content table
     */
    private function saveContentKey($key, $contentValue, $type = 'text', $userId = 1) {
        try {
            $existing = $this->db->fetch("SELECT id, content FROM content WHERE `key` = ?", [$key]);
            if ($existing) {
                if ($existing['content'] !== $contentValue) {
                    // Record history
                    $this->db->insert(
                        "INSERT INTO content_history (content_id, content, changed_by) VALUES (?, ?, ?)",
                        [$existing['id'], $existing['content'], $userId]
                    );
                }
                $this->db->execute(
                    "UPDATE content SET content = ?, type = ?, is_active = 1, updated_at = NOW() WHERE id = ?",
                    [$contentValue, $type, $existing['id']]
                );
            } else {
                $meta = $this->defaultContent[$key] ?? [];
                $title = $meta['title'] ?? ucfirst(str_replace('_', ' ', $key));
                $section = $meta['section'] ?? 'main';
                $this->db->insert(
                    "INSERT INTO content (`key`, title, content, type, page, section, is_active, created_by) VALUES (?, ?, ?, ?, 'home', ?, 1, ?)",
                    [$key, $title, $contentValue, $type, $section, $userId]
                );
            }
        } catch (\Throwable $e) {
            // Log or ignore if table issue
        }
    }

    /**
     * Save setting value into settings table
     */
    private function saveSettingKey($key, $value) {
        try {
            $existing = $this->db->fetch("SELECT id FROM settings WHERE `key` = ?", [$key]);
            if ($existing) {
                $this->db->execute("UPDATE settings SET value = ? WHERE `key` = ?", [$value, $key]);
            } else {
                $this->db->insert("INSERT INTO settings (`key`, value, type, `group`) VALUES (?, ?, 'string', 'general')", [$key, $value]);
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Ensure all default content keys exist in the database
     */
    private function ensureContentDefaults() {
        try {
            $existingKeys = $this->db->fetchAll("SELECT `key` FROM content");
            $keysList = array_column($existingKeys, 'key');

            foreach ($this->defaultContent as $key => $data) {
                if (!in_array($key, $keysList)) {
                    $this->db->insert(
                        "INSERT INTO content (`key`, title, content, type, page, section, is_active, created_by) VALUES (?, ?, ?, ?, 'home', ?, 1, 1)",
                        [$key, $data['title'], $data['content'], $data['type'], $data['section']]
                    );
                }
            }
        } catch (\Throwable $e) {
            // Fail safely if database isn't initialized yet
        }
    }
}
