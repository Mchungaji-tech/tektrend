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
        $rows = $this->db->fetchAll("SELECT `key`, `value` FROM settings");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }
}
