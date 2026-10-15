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
        $partner = $this->partnerRecord();

        $this->view->setLayout('layouts/partner');
        $this->render('partner/dashboard', [
            'pageTitle'   => 'Partner Dashboard',
            'partnerPage' => 'dashboard',
            'partner'     => $partner,
            'referralCode' => $this->referralCode(),
        ]);
    }

    /**
     * Referral link + (not yet tracked) referral list placeholder.
     */
    public function referrals() {
        $this->requireAuth();
        $partner = $this->partnerRecord();

        $this->view->setLayout('layouts/partner');
        $this->render('partner/referrals', [
            'pageTitle'   => 'My Referrals',
            'partnerPage' => 'referrals',
            'partner'     => $partner,
            'referralCode' => $this->referralCode(),
        ]);
    }

    /**
     * Payout summary placeholder (no payment ledger exists yet).
     */
    public function payouts() {
        $this->requireAuth();
        $partner = $this->partnerRecord();

        $this->view->setLayout('layouts/partner');
        $this->render('partner/payouts', [
            'pageTitle'   => 'Payouts',
            'partnerPage' => 'payouts',
            'partner'     => $partner,
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
