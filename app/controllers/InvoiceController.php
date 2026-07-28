<?php
/**
 * Invoice Controller
 * Manage customer invoices
 */

class InvoiceController extends Controller {

    public function index() {
        $this->requireAuth();
        $statusFilter = $_GET['status'] ?? '';
        $where = $statusFilter ? "WHERE i.status = ?" : '';
        $params = $statusFilter ? [$statusFilter] : [];
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM invoices i " . ($where ? "WHERE i.status = ?" : ''), $params);
        $pagination = $this->getPagination($total);
        $invoices = $this->db->fetchAll(
            "SELECT i.*, c.first_name, c.last_name, c.company FROM invoices i LEFT JOIN customers c ON i.customer_id = c.id $where ORDER BY i.created_at DESC LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}",
            $params
        );
        $this->render('invoices/index', ['invoices' => $invoices, 'pagination' => $pagination, 'statusFilter' => $statusFilter]);
    }

    public function create() {
        $this->requireAuth();
        $customers = $this->db->fetchAll("SELECT id, first_name, last_name, company FROM customers WHERE status = 'active' ORDER BY first_name");
        $taxRates = $this->db->fetchAll("SELECT id, name, rate FROM tax_rates WHERE status = 'active' ORDER BY name");
        $this->render('invoices/create', ['customers' => $customers, 'taxRates' => $taxRates]);
    }

    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();
        $rules = ['customer_id' => ['required' => true, 'label' => 'Customer'], 'invoice_number' => ['required' => true, 'label' => 'Invoice Number']];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) { $this->session->flash('error', reset($validation['errors'])); $this->session->flash('old', $_POST); redirect('/invoices/create'); }
        $data = $validation['data'];
        $invoiceId = $this->db->insert(
            "INSERT INTO invoices (customer_id, invoice_number, issue_date, due_date, subtotal, tax_amount, discount_amount, total, tax_rate_id, status, notes, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$data['customer_id'], $data['invoice_number'], $_POST['issue_date'], $_POST['due_date'], $_POST['subtotal'] ?? 0, $_POST['tax_amount'] ?? 0, $_POST['discount_amount'] ?? 0, $_POST['total'] ?? 0, $_POST['tax_rate_id'] ?? null, $_POST['status'] ?? 'draft', $_POST['notes'] ?? null, $this->auth->id()]
        );
        // Add invoice items
        if (isset($_POST['items']) && is_array($_POST['items'])) {
            foreach ($_POST['items'] as $item) {
                if (!empty($item['description'])) {
                    $this->db->insert(
                        "INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, tax_rate, line_total) VALUES (?, ?, ?, ?, ?, ?)",
                        [$invoiceId, $item['description'], $item['quantity'] ?? 1, $item['unit_price'] ?? 0, $item['tax_rate'] ?? 0, $item['line_total'] ?? 0]
                    );
                }
            }
        }
        auditLog('create', 'invoices', $invoiceId, 'Created invoice ' . $data['invoice_number']);
        $this->redirectWithSuccess('/invoices', 'Invoice created successfully!');
    }

    public function show($id) {
        $this->requireAuth();
        $invoice = $this->db->fetch(
            "SELECT i.*, c.first_name, c.last_name, c.company, c.email, c.phone, c.address, c.city, c.state, c.zip_code, c.country, c.tax_id FROM invoices i LEFT JOIN customers c ON i.customer_id = c.id WHERE i.id = ?",
            [$id]
        );
        if (!$invoice) { $this->session->flash('error', 'Invoice not found.'); redirect('/invoices'); }
        $items = $this->db->fetchAll("SELECT * FROM invoice_items WHERE invoice_id = ? ORDER BY sort_order", [$id]);
        $payments = $this->db->fetchAll("SELECT * FROM invoice_payments WHERE invoice_id = ? ORDER BY paid_at DESC", [$id]);
        $this->render('invoices/show', ['invoice' => $invoice, 'items' => $items, 'payments' => $payments]);
    }

    public function edit($id) {
        $this->requireAuth();
        $invoice = $this->db->fetch("SELECT * FROM invoices WHERE id = ?", [$id]);
        if (!$invoice) { $this->session->flash('error', 'Invoice not found.'); redirect('/invoices'); }
        $customers = $this->db->fetchAll("SELECT id, first_name, last_name, company FROM customers WHERE status = 'active' ORDER BY first_name");
        $taxRates = $this->db->fetchAll("SELECT id, name, rate FROM tax_rates WHERE status = 'active' ORDER BY name");
        $items = $this->db->fetchAll("SELECT * FROM invoice_items WHERE invoice_id = ? ORDER BY sort_order", [$id]);
        $this->render('invoices/edit', ['invoice' => $invoice, 'customers' => $customers, 'taxRates' => $taxRates, 'items' => $items]);
    }

    public function update($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->execute(
            "UPDATE invoices SET customer_id = ?, invoice_number = ?, issue_date = ?, due_date = ?, subtotal = ?, tax_amount = ?, discount_amount = ?, total = ?, tax_rate_id = ?, status = ?, notes = ?, updated_at = NOW() WHERE id = ?",
            [$_POST['customer_id'], $_POST['invoice_number'], $_POST['issue_date'], $_POST['due_date'], $_POST['subtotal'] ?? 0, $_POST['tax_amount'] ?? 0, $_POST['discount_amount'] ?? 0, $_POST['total'] ?? 0, $_POST['tax_rate_id'] ?? null, $_POST['status'] ?? 'draft', $_POST['notes'] ?? null, $id]
        );
        // Update items
        $this->db->execute("DELETE FROM invoice_items WHERE invoice_id = ?", [$id]);
        if (isset($_POST['items']) && is_array($_POST['items'])) {
            foreach ($_POST['items'] as $item) {
                if (!empty($item['description'])) {
                    $this->db->insert(
                        "INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, tax_rate, line_total) VALUES (?, ?, ?, ?, ?, ?)",
                        [$id, $item['description'], $item['quantity'] ?? 1, $item['unit_price'] ?? 0, $item['tax_rate'] ?? 0, $item['line_total'] ?? 0]
                    );
                }
            }
        }
        auditLog('update', 'invoices', $id, 'Updated invoice');
        $this->redirectWithSuccess("/invoices/$id", 'Invoice updated successfully!');
    }

    public function delete($id) {
        $this->requireAuth();
        $this->db->execute("DELETE FROM invoices WHERE id = ?", [$id]);
        $this->db->execute("DELETE FROM invoice_items WHERE invoice_id = ?", [$id]);
        $this->db->execute("DELETE FROM invoice_payments WHERE invoice_id = ?", [$id]);
        auditLog('delete', 'invoices', $id, 'Deleted invoice');
        $this->redirectWithSuccess('/invoices', 'Invoice deleted successfully!');
    }

    public function pdf($id) {
        $this->requireAuth();
        $invoice = $this->db->fetch(
            "SELECT i.*, c.first_name, c.last_name, c.company, c.email, c.phone, c.address, c.city, c.state, c.zip_code, c.country, c.tax_id FROM invoices i LEFT JOIN customers c ON i.customer_id = c.id WHERE i.id = ?",
            [$id]
        );
        if (!$invoice) { $this->session->flash('error', 'Invoice not found.'); redirect('/invoices'); }
        $items = $this->db->fetchAll("SELECT * FROM invoice_items WHERE invoice_id = ? ORDER BY sort_order", [$id]);
        $this->view->setLayout(null);
        $this->render('invoices/pdf', ['invoice' => $invoice, 'items' => $items]);
    }
}
