<?php
/**
 * Customer Controller
 * Manage customer records
 */

class CustomerController extends Controller {

    public function index() {
        $this->requireAuth();
        $search = $_GET['search'] ?? '';
        $where = $search ? "WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR company LIKE ?" : '';
        $params = $search ? ["%$search%", "%$search%", "%$search%", "%$search%"] : [];
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM customers " . ($where ? "WHERE " . substr($where, 6) : ''), $params);
        $pagination = $this->getPagination($total);
        $customers = $this->db->fetchAll(
            "SELECT * FROM customers $where ORDER BY created_at DESC LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}",
            $params
        );
        $this->render('customers/index', ['customers' => $customers, 'pagination' => $pagination, 'search' => $search]);
    }

    public function create() {
        $this->requireAuth();
        $this->render('customers/create');
    }

    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();
        $rules = ['first_name' => ['required' => true, 'label' => 'First Name'], 'last_name' => ['required' => true, 'label' => 'Last Name']];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) { $this->session->flash('error', reset($validation['errors'])); $this->session->flash('old', $_POST); redirect('/customers/create'); }
        $data = $validation['data'];
        $this->db->insert(
            "INSERT INTO customers (first_name, last_name, email, phone, company, address, city, state, zip_code, country, tax_id, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$data['first_name'], $data['last_name'], $_POST['email'] ?? null, $_POST['phone'] ?? null, $_POST['company'] ?? null, $_POST['address'] ?? null, $_POST['city'] ?? null, $_POST['state'] ?? null, $_POST['zip_code'] ?? null, $_POST['country'] ?? null, $_POST['tax_id'] ?? null, $_POST['notes'] ?? null, $_POST['status'] ?? 'active']
        );
        auditLog('create', 'customers', null, 'Created customer: ' . $data['first_name'] . ' ' . $data['last_name']);
        $this->redirectWithSuccess('/customers', 'Customer created successfully!');
    }

    public function show($id) {
        $this->requireAuth();
        $customer = $this->db->fetch("SELECT * FROM customers WHERE id = ?", [$id]);
        if (!$customer) { $this->session->flash('error', 'Customer not found.'); redirect('/customers'); }
        $invoices = $this->db->fetchAll("SELECT * FROM invoices WHERE customer_id = ? ORDER BY created_at DESC", [$id]);
        $this->render('customers/show', ['customer' => $customer, 'invoices' => $invoices]);
    }

    public function edit($id) {
        $this->requireAuth();
        $customer = $this->db->fetch("SELECT * FROM customers WHERE id = ?", [$id]);
        if (!$customer) { $this->session->flash('error', 'Customer not found.'); redirect('/customers'); }
        $this->render('customers/edit', ['customer' => $customer]);
    }

    public function update($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->execute(
            "UPDATE customers SET first_name = ?, last_name = ?, email = ?, phone = ?, company = ?, address = ?, city = ?, state = ?, zip_code = ?, country = ?, tax_id = ?, notes = ?, status = ?, updated_at = NOW() WHERE id = ?",
            [$_POST['first_name'], $_POST['last_name'], $_POST['email'] ?? null, $_POST['phone'] ?? null, $_POST['company'] ?? null, $_POST['address'] ?? null, $_POST['city'] ?? null, $_POST['state'] ?? null, $_POST['zip_code'] ?? null, $_POST['country'] ?? null, $_POST['tax_id'] ?? null, $_POST['notes'] ?? null, $_POST['status'] ?? 'active', $id]
        );
        auditLog('update', 'customers', $id, 'Updated customer');
        $this->redirectWithSuccess("/customers/$id", 'Customer updated successfully!');
    }

    public function delete($id) {
        $this->requireAuth();
        $this->db->execute("DELETE FROM customers WHERE id = ?", [$id]);
        auditLog('delete', 'customers', $id, 'Deleted customer');
        $this->redirectWithSuccess('/customers', 'Customer deleted successfully!');
    }
}
