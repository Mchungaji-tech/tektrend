<?php
/**
 * Partner / Referral Portal Controller
 * Handles public partner applications plus the partner self-service area.
 * Applications create a pending user (role = partner) and a pending `partners` row;
 * a partner only reaches the dashboard once an admin approves them.
 */

class PartnerController extends Controller {

    /**
     * The partners row for the logged-in partner (creates nothing).
     */
    private function partnerRecord() {
        $userId = $this->auth->id();
        if (!$userId) {
            return null;
        }
        return $this->db->fetch("SELECT * FROM partners WHERE user_id = ? LIMIT 1", [$userId]);
    }

    /**
     * Public partner application form.
     */
    public function apply() {
        if ($this->auth->check()) {
            redirect($this->auth->homeUrl());
        }
        $this->view->setLayout('layouts/auth');
        $this->render('partner/apply', ['area' => 'partner']);
    }

    /**
     * Store a new partner application: pending user + pending partners row.
     */
    public function storeApplication() {
        if ($this->auth->check()) {
            redirect($this->auth->homeUrl());
        }
        $this->verifyCsrf();

        $rules = [
            'first_name'   => ['required' => true, 'label' => 'First Name'],
            'last_name'    => ['required' => true, 'label' => 'Last Name'],
            'email'        => ['required' => true, 'email' => true, 'label' => 'Email'],
            'password'     => ['required' => true, 'min' => 8, 'label' => 'Password'],
            'company_name' => ['required' => true, 'label' => 'Company Name'],
        ];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) {
            $this->session->flash('error', reset($validation['errors']));
            $this->session->flash('old', $_POST);
            redirect('/partner-apply');
        }

        if ($this->db->fetchColumn("SELECT id FROM users WHERE email = ?", [$validation['data']['email']])) {
            $this->session->flash('error', 'An account with that email already exists. Please sign in instead.');
            redirect('/login?area=partner');
        }

        $data = $validation['data'];
        $data['phone']        = trim($_POST['phone'] ?? '') ?: null;
        $data['role']         = 'partner';
        $data['status']       = 'pending';
        $data['employee_id']  = $this->generatePartnerEmployeeId();

        $result = $this->auth->register($data);
        if (!$result['success']) {
            $this->session->flash('error', $result['message']);
            $this->session->flash('old', $_POST);
            redirect('/partner-apply');
        }

        $userId = $result['user_id'];
        try {
            $this->db->insert(
                "INSERT INTO partners (user_id, company_name, website, about, status, commission_rate) VALUES (?, ?, ?, ?, 'pending', 10.00)",
                [
                    $userId,
                    $data['company_name'],
                    trim($_POST['website'] ?? '') ?: null,
                    trim($_POST['about'] ?? '') ?: null,
                ]
            );
        } catch (\Throwable $e) {
            error_log('PartnerController::storeApplication partners insert - ' . $e->getMessage());
        }

        // Log the pending partner in so they land on the "awaiting approval" page.
        $user = $this->db->fetch("SELECT * FROM users WHERE id = ?", [$userId]);
        $this->auth->loginUser($user, 'Partner application');

        $this->redirectWithSuccess('/partners/pending', 'Application submitted. We will review it shortly.');
    }

    /**
     * Generate a unique employee_id for self-registered partners.
     */
    private function generatePartnerEmployeeId() {
        do {
            $candidate = 'PB' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8)); // PB + 8 chars, <= 20
            $exists = $this->db->fetchColumn("SELECT id FROM users WHERE employee_id = ?", [$candidate]);
        } while ($exists);
        return $candidate;
    }

    /**
     * Awaiting-approval page for pending partners.
     */
    public function pending() {
        $this->requireAuth();
        $partner = $this->partnerRecord();

        // If approved while logged in, bounce to the real dashboard.
        if ($partner && $partner['status'] === 'approved') {
            $this->db->execute("UPDATE users SET status = 'active' WHERE id = ?", [$this->auth->id()]);
            $this->session->set('user_status', 'active');
            redirect('/partners');
        }
        if ($partner && $partner['status'] === 'rejected') {
            $this->session->flash('error', 'Your partner application was not approved.');
        }

        $this->view->setLayout('layouts/partner');
        $this->render('partner/pending', [
            'pageTitle'  => 'Application Pending',
            'partnerPage' => 'pending',
            'partner'    => $partner,
        ]);
    }

    /**
     * Approved partner dashboard.
     */
    public function dashboard() {
        $this->requireAuth();
        $this->ensurePartnerReferralsTable();
        $this->ensurePortfolioDemosTable();
        $partner = $this->partnerRecord();
        $partnerId = $partner['id'] ?? 0;

        $stats = [
            'total_referrals'   => 0,
            'closed_deals'      => 0,
            'earned_commission' => 0.00,
            'pending_payout'    => 0.00
        ];
        $recentReferrals = [];

        if ($partnerId) {
            try {
                $agg = $this->db->fetch("SELECT 
                    COUNT(*) as total_referrals,
                    SUM(CASE WHEN status IN ('closed_won', 'paid') THEN 1 ELSE 0 END) as closed_deals,
                    SUM(CASE WHEN status IN ('closed_won', 'paid') THEN commission_amount ELSE 0 END) as earned_commission,
                    SUM(CASE WHEN status = 'closed_won' THEN commission_amount ELSE 0 END) as pending_payout
                    FROM partner_referrals WHERE partner_id = ?", [$partnerId]);
                if ($agg) {
                    $stats = array_merge($stats, array_filter($agg, function($v) { return $v !== null; }));
                }
                $recentReferrals = $this->db->fetchAll(
                    "SELECT * FROM partner_referrals WHERE partner_id = ? ORDER BY id DESC LIMIT 5",
                    [$partnerId]
                );
            } catch (\Throwable $e) {
                error_log('PartnerController::dashboard stats - ' . $e->getMessage());
            }
        }

        // Fetch shoppable templates for quick reseller catalog
        $templates = [];
        try {
            $templates = $this->db->fetchAll("SELECT * FROM portfolio_demos WHERE is_for_sale = 1 ORDER BY sort_order ASC, id DESC LIMIT 4");
        } catch (\Throwable $e) {
            $templates = [];
        }

        $this->view->setLayout('layouts/partner');
        $this->render('partner/dashboard', [
            'pageTitle'       => 'Partner Dashboard',
            'partnerPage'     => 'dashboard',
            'partner'         => $partner,
            'referralCode'    => $this->referralCode(),
            'stats'           => $stats,
            'recentReferrals' => $recentReferrals,
            'templates'       => $templates
        ]);
    }

    /**
     * Partner Shop & Wholesale Reseller Catalog
     * Allows partners to acquire turnkey software at partner rates or order on behalf of clients.
     */
    public function shop() {
        $this->requireAuth();
        $this->ensurePortfolioDemosTable();
        $partner = $this->partnerRecord();

        $templates = [];
        try {
            $templates = $this->db->fetchAll("SELECT * FROM portfolio_demos WHERE is_for_sale = 1 ORDER BY sort_order ASC, id DESC");
        } catch (\Throwable $e) {
            $templates = [];
        }
        if (empty($templates)) {
            $all = PortfolioController::getDefaultPrototypes();
            $templates = array_filter($all, function($p) {
                return !empty($p['is_for_sale']);
            });
        }

        $this->view->setLayout('layouts/partner');
        $this->render('partner/shop', [
            'pageTitle'    => 'Partner Software Store & Reseller Catalog',
            'partnerPage'  => 'shop',
            'partner'      => $partner,
            'referralCode' => $this->referralCode(),
            'templates'    => $templates
        ]);
    }

    /**
     * Referral link + registered client referrals list.
     */
    public function referrals() {
        $this->requireAuth();
        $this->ensurePartnerReferralsTable();
        $partner = $this->partnerRecord();
        $partnerId = $partner['id'] ?? 0;

        $referrals = [];
        if ($partnerId) {
            try {
                $referrals = $this->db->fetchAll(
                    "SELECT * FROM partner_referrals WHERE partner_id = ? ORDER BY id DESC",
                    [$partnerId]
                );
            } catch (\Throwable $e) {
                $referrals = [];
            }
        }

        $this->view->setLayout('layouts/partner');
        $this->render('partner/referrals', [
            'pageTitle'    => 'My Referrals & Client Pipeline',
            'partnerPage'  => 'referrals',
            'partner'      => $partner,
            'referralCode' => $this->referralCode(),
            'referrals'    => $referrals
        ]);
    }

    /**
     * Submit a new referral directly from Partner Portal
     */
    public function submitReferral() {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->ensurePartnerReferralsTable();

        $partner = $this->partnerRecord();
        if (!$partner) {
            $this->session->flash('error', 'Partner record not found.');
            redirect('/partners');
            return;
        }

        $clientName = trim($_POST['client_name'] ?? '');
        $clientEmail = trim($_POST['client_email'] ?? '');
        $clientPhone = trim($_POST['client_phone'] ?? '');
        $projectType = trim($_POST['project_type'] ?? 'Custom Web Architecture');
        $estValue = (float)($_POST['estimated_value'] ?? 500);
        $notes = trim($_POST['notes'] ?? '');

        if (empty($clientName) || empty($clientEmail)) {
            $this->session->flash('error', 'Please provide client name and email address.');
            redirect('/partners/referrals');
            return;
        }

        $commissionRate = (float)($partner['commission_rate'] ?? 10.00);
        $commissionAmount = round(($estValue * $commissionRate) / 100, 2);

        try {
            $this->db->insert(
                "INSERT INTO partner_referrals (partner_id, client_name, client_email, client_phone, project_type, estimated_value, commission_amount, status, notes)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 'lead', ?)",
                [$partner['id'], $clientName, $clientEmail, $clientPhone, $projectType, $estValue, $commissionAmount, $notes]
            );

            // Also record in central CRM leads
            $names = explode(' ', $clientName, 2);
            $firstName = $names[0];
            $lastName = $names[1] ?? 'Client';
            $crmNotes = "Referred by Partner: " . ($partner['company_name'] ?: 'Partner #' . $partner['id']) . 
                        " (Code: " . $this->referralCode() . ")\n" .
                        "Project Type: " . $projectType . "\n" .
                        "Est Value: $" . number_format($estValue, 2) . "\n" .
                        "Notes: " . $notes;

            $this->db->insert(
                "INSERT INTO leads (first_name, last_name, email, phone, status, priority, notes, created_at)
                 VALUES (?, ?, ?, ?, 'new', 'high', ?, NOW())",
                [$firstName, $lastName, $clientEmail, $clientPhone, $crmNotes]
            );

            $this->session->flash('success', 'Referral for ' . $clientName . ' submitted successfully! Our solutions team will follow up.');
        } catch (\Throwable $e) {
            error_log('PartnerController::submitReferral - ' . $e->getMessage());
            $this->session->flash('error', 'Failed to submit referral: ' . $e->getMessage());
        }

        redirect('/partners/referrals');
    }

    /**
     * Ensure partner_referrals table exists
     */
    private function ensurePartnerReferralsTable() {
        try {
            $this->db->execute("CREATE TABLE IF NOT EXISTS `partner_referrals` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `partner_id` INT UNSIGNED NOT NULL,
                `client_name` VARCHAR(150) NOT NULL,
                `client_email` VARCHAR(150) NOT NULL,
                `client_phone` VARCHAR(50) DEFAULT NULL,
                `project_type` VARCHAR(100) DEFAULT NULL,
                `estimated_value` DECIMAL(12,2) DEFAULT 0.00,
                `commission_amount` DECIMAL(12,2) DEFAULT 0.00,
                `status` ENUM('lead','negotiation','closed_won','closed_lost','paid') DEFAULT 'lead',
                `notes` TEXT DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `partner_id` (`partner_id`),
                KEY `status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } catch (\Throwable $e) {
            error_log('ensurePartnerReferralsTable: ' . $e->getMessage());
        }
    }

    /**
     * Payout summary placeholder (no payment ledger exists yet).
     */
    public function payouts() {
        $this->requireAuth();
        $this->ensurePartnerReferralsTable();
        $partner = $this->partnerRecord();
        $partnerId = $partner['id'] ?? 0;

        $payoutStats = [
            'total_earned' => 0.00,
            'pending_payout' => 0.00,
            'paid_out' => 0.00
        ];
        if ($partnerId) {
            try {
                $agg = $this->db->fetch("SELECT 
                    SUM(CASE WHEN status IN ('closed_won', 'paid') THEN commission_amount ELSE 0 END) as total_earned,
                    SUM(CASE WHEN status = 'closed_won' THEN commission_amount ELSE 0 END) as pending_payout,
                    SUM(CASE WHEN status = 'paid' THEN commission_amount ELSE 0 END) as paid_out
                    FROM partner_referrals WHERE partner_id = ?", [$partnerId]);
                if ($agg) {
                    $payoutStats = array_merge($payoutStats, array_filter($agg, function($v) { return $v !== null; }));
                }
            } catch (\Throwable $e) {}
        }

        $this->view->setLayout('layouts/partner');
        $this->render('partner/payouts', [
            'pageTitle'    => 'Payouts & Commission Ledger',
            'partnerPage'  => 'payouts',
            'partner'      => $partner,
            'payoutStats'  => $payoutStats
        ]);
    }

    /**
     * Partner profile view + edit.
     */
    public function profile() {
        $this->requireAuth();
        $user = $this->auth->user();
        $partner = $this->partnerRecord();

        $this->view->setLayout('layouts/partner');
        $this->render('partner/profile', [
            'pageTitle'   => 'Partner Profile',
            'partnerPage' => 'profile',
            'user'        => $user,
            'partner'     => $partner,
        ]);
    }

    public function updateProfile() {
        $this->requireAuth();
        $this->verifyCsrf();

        $rules = [
            'first_name'   => ['required' => true, 'label' => 'First Name'],
            'last_name'    => ['required' => true, 'label' => 'Last Name'],
            'company_name' => ['required' => true, 'label' => 'Company Name'],
        ];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) {
            $this->session->flash('error', reset($validation['errors']));
            redirect('/partners/profile');
        }

        $userId = $this->auth->id();
        $phone = trim($_POST['phone'] ?? '') ?: null;
        try {
            $this->db->execute(
                "UPDATE users SET first_name = ?, last_name = ?, phone = ? WHERE id = ?",
                [$validation['data']['first_name'], $validation['data']['last_name'], $phone, $userId]
            );
            $partner = $this->partnerRecord();
            if ($partner) {
                $this->db->execute(
                    "UPDATE partners SET company_name = ?, website = ?, about = ? WHERE id = ?",
                    [
                        $validation['data']['company_name'],
                        trim($_POST['website'] ?? '') ?: null,
                        trim($_POST['about'] ?? '') ?: null,
                        $partner['id'],
                    ]
                );
            }
            $this->session->set('user_name', $validation['data']['first_name'] . ' ' . $validation['data']['last_name']);
            $this->redirectWithSuccess('/partners/profile', 'Profile updated.');
        } catch (\Throwable $e) {
            error_log('PartnerController::updateProfile - ' . $e->getMessage());
            $this->redirectWithError('/partners/profile', 'Could not update profile.');
        }
    }

    /**
     * A simple, stable referral identifier based on the partner's user id.
     */
    private function referralCode() {
        $userId = $this->auth->id();
        return $userId ? 'TT-PARTNER-' . strtoupper(substr(md5('tektrend-ref-' . $userId), 0, 8)) : '';
    }
}
