<?php
/**
 * Client Portal Controller
 * Self-service area for customers (role = client). Every query is scoped to the
 * signed-in client's own customer record so no other customer's data can be read.
 */

class ClientController extends Controller {

    /**
     * Resolve the customer_id for the logged-in client, auto-linking (or creating)
     * a customers row by email the first time they enter the portal.
     */
    private function customerId() {
        $userId = $this->auth->id();
        if (!$userId) {
            return null;
        }

        $row = $this->db->fetch("SELECT id FROM customers WHERE user_id = ? LIMIT 1", [$userId]);
        if ($row) {
            return (int)$row['id'];
        }

        // First login: link by email to an existing (unlinked) customer, else create one.
        $user = $this->db->fetch("SELECT first_name, last_name, email, phone FROM users WHERE id = ?", [$userId]);
        if (!$user) {
            return null;
        }

        try {
            $existing = $this->db->fetch("SELECT id FROM customers WHERE email = ? AND user_id IS NULL LIMIT 1", [$user['email']]);
            if ($existing) {
                $this->db->execute("UPDATE customers SET user_id = ? WHERE id = ?", [$userId, $existing['id']]);
                return (int)$existing['id'];
            }
            $newId = $this->db->insert(
                "INSERT INTO customers (user_id, first_name, last_name, email, phone, status) VALUES (?, ?, ?, ?, ?, 'active')",
                [$userId, $user['first_name'], $user['last_name'], $user['email'], $user['phone'] ?? null]
            );
            return (int)$newId;
        } catch (\Throwable $e) {
            error_log('ClientController::customerId - ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Client dashboard: invoice totals, upcoming consultations, project/contract status.
     */
    public function dashboard() {
        $this->requireAuth();
        $customerId = $this->customerId();
        $email = $this->session->get('user_email');

        $stats = ['total_billed' => 0, 'amount_paid' => 0, 'amount_due' => 0, 'open_invoices' => 0];
        $recentInvoices = [];
        $upcomingConsultations = [];
        $projects = [];

        if ($customerId) {
            try {
                $agg = $this->db->fetch(
                    "SELECT
                        COALESCE(SUM(total), 0) AS total_billed,
                        COALESCE(SUM(CASE WHEN status = 'paid' THEN total ELSE 0 END), 0) AS amount_paid,
                        COALESCE(SUM(CASE WHEN status IN ('sent','partial','overdue') THEN total ELSE 0 END), 0) AS amount_due,
                        COALESCE(SUM(CASE WHEN status IN ('draft','sent','partial','overdue') THEN 1 ELSE 0 END), 0) AS open_invoices
                     FROM invoices WHERE customer_id = ?",
                    [$customerId]
                );
                if ($agg) {
                    $stats = array_merge($stats, $agg);
                }
                $recentInvoices = $this->db->fetchAll(
                    "SELECT id, invoice_number, issue_date, due_date, total, status FROM invoices
                     WHERE customer_id = ? ORDER BY created_at DESC LIMIT 5",
                    [$customerId]
                );
                $projects = $this->db->fetchAll(
                    "SELECT id, contract_number, title, value, start_date, end_date, status FROM contracts
                     WHERE customer_id = ? ORDER BY created_at DESC LIMIT 5",
                    [$customerId]
                );
            } catch (\Throwable $e) {
                error_log('ClientController::dashboard invoices/contracts - ' . $e->getMessage());
            }
        }

        // Consultations are linked by email (no customer_id column).
        if ($email) {
            try {
                $upcomingConsultations = $this->db->fetchAll(
                    "SELECT id, service_type, preferred_date, preferred_time, status, zoom_link FROM consultations
                     WHERE email = ? AND preferred_date >= CURDATE() AND status IN ('pending','confirmed')
                     ORDER BY preferred_date ASC LIMIT 5",
                    [$email]
                );
            } catch (\Throwable $e) {
                error_log('ClientController::dashboard consultations - ' . $e->getMessage());
            }
        }

        $this->view->setLayout('layouts/portal');
        $this->render('portal/dashboard', [
            'pageTitle'              => 'Client Dashboard',
            'portalPage'             => 'dashboard',
            'stats'                  => $stats,
            'recentInvoices'         => $recentInvoices,
            'upcomingConsultations'  => $upcomingConsultations,
            'projects'               => $projects,
            'hasCustomer'            => $customerId !== null,
        ]);
    }

    /**
     * List the client's own invoices.
     */
    public function invoices() {
        $this->requireAuth();
        $customerId = $this->customerId();
        $invoices = $customerId ? $this->db->fetchAll(
            "SELECT id, invoice_number, issue_date, due_date, subtotal, tax_amount, total, status
             FROM invoices WHERE customer_id = ? ORDER BY created_at DESC",
            [$customerId]
        ) : [];

        $this->view->setLayout('layouts/portal');
        $this->render('portal/invoices', [
            'pageTitle'  => 'My Invoices',
            'portalPage' => 'invoices',
            'invoices'   => $invoices,
        ]);
    }

    /**
     * Show a single invoice, but only if it belongs to this client.
     */
    public function invoice($id) {
        $this->requireAuth();
        $customerId = $this->customerId();
        $id = (int)$id;

        $invoice = $customerId ? $this->db->fetch(
            "SELECT * FROM invoices WHERE id = ? AND customer_id = ?",
            [$id, $customerId]
        ) : null;

        if (!$invoice) {
            $this->session->flash('error', 'Invoice not found.');
            redirect('/portal/invoices');
        }

        $items = $this->db->fetchAll(
            "SELECT * FROM invoice_items WHERE invoice_id = ? ORDER BY sort_order ASC, id ASC",
            [$id]
        );

        $this->view->setLayout('layouts/portal');
        $this->render('portal/invoice', [
            'pageTitle'  => 'Invoice ' . $invoice['invoice_number'],
            'portalPage' => 'invoices',
            'invoice'    => $invoice,
            'items'      => $items,
        ]);
    }

    /**
     * List the client's projects (contracts scoped to their customer record).
     */
    public function projects() {
        $this->requireAuth();
        $customerId = $this->customerId();
        $projects = $customerId ? $this->db->fetchAll(
            "SELECT id, contract_number, title, value, start_date, end_date, status
             FROM contracts WHERE customer_id = ? ORDER BY created_at DESC",
            [$customerId]
        ) : [];

        $this->view->setLayout('layouts/portal');
        $this->render('portal/projects', [
            'pageTitle'  => 'My Projects',
            'portalPage' => 'projects',
            'projects'   => $projects,
        ]);
    }

    /**
     * Show the client's profile.
     */
    public function profile() {
        $this->requireAuth();
        $user = $this->auth->user();
        $customerId = $this->customerId();
        $customer = $customerId ? $this->db->fetch("SELECT * FROM customers WHERE id = ?", [$customerId]) : null;

        $this->view->setLayout('layouts/portal');
        $this->render('portal/profile', [
            'pageTitle'  => 'My Profile',
            'portalPage' => 'profile',
            'user'       => $user,
            'customer'   => $customer,
        ]);
    }

    /**
     * Update the client's own contact details (name/phone/company). Password change
     * is intentionally out of scope for this phase.
     */
    public function updateProfile() {
        $this->requireAuth();
        $this->verifyCsrf();

        $rules = [
            'first_name' => ['required' => true, 'label' => 'First Name'],
            'last_name'  => ['required' => true, 'label' => 'Last Name'],
        ];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) {
            $this->session->flash('error', reset($validation['errors']));
            redirect('/portal/profile');
        }

        $userId = $this->auth->id();
        $phone   = trim($_POST['phone'] ?? '') ?: null;
        $company = trim($_POST['company'] ?? '') ?: null;

        try {
            $this->db->execute(
                "UPDATE users SET first_name = ?, last_name = ?, phone = ? WHERE id = ?",
                [$validation['data']['first_name'], $validation['data']['last_name'], $phone, $userId]
            );
            $customerId = $this->customerId();
            if ($customerId) {
                $this->db->execute(
                    "UPDATE customers SET first_name = ?, last_name = ?, phone = ?, company = ? WHERE id = ?",
                    [$validation['data']['first_name'], $validation['data']['last_name'], $phone, $company, $customerId]
                );
            }
            // Refresh session name
            $this->session->set('user_name', $validation['data']['first_name'] . ' ' . $validation['data']['last_name']);
            $this->redirectWithSuccess('/portal/profile', 'Profile updated.');
        } catch (\Throwable $e) {
            error_log('ClientController::updateProfile - ' . $e->getMessage());
            $this->redirectWithError('/portal/profile', 'Could not update profile.');
        }
    }
}
