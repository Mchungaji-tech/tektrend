<?php
/**
 * Finance Controller
 * Manage finances, budgets, taxes
 */

class FinanceController extends Controller {

    public function index() {
        $this->requireAuth();
        $typeFilter = $_GET['type'] ?? '';
        $categoryFilter = $_GET['category'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';

        $where = [];
        $params = [];
        if ($typeFilter) { $where[] = "type = ?"; $params[] = $typeFilter; }
        if ($categoryFilter) { $where[] = "category = ?"; $params[] = $categoryFilter; }
        if ($dateFrom) { $where[] = "transaction_date >= ?"; $params[] = $dateFrom; }
        if ($dateTo) { $where[] = "transaction_date <= ?"; $params[] = $dateTo; }

        $whereSql = $where ? implode(' AND ', $where) : '1=1';
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM finance_transactions WHERE $whereSql", $params);
        $pagination = $this->getPagination($total);

        $transactions = $this->db->fetchAll(
            "SELECT ft.*, d.name as department_name, c.first_name, c.last_name, u.first_name as created_first, u.last_name as created_last
             FROM finance_transactions ft
             LEFT JOIN departments d ON ft.department_id = d.id
             LEFT JOIN customers c ON ft.customer_id = c.id
             LEFT JOIN users u ON ft.created_by = u.id
             WHERE $whereSql
             ORDER BY ft.transaction_date DESC
             LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}",
            $params
        );

        $income = $this->db->fetchColumn("SELECT COALESCE(SUM(amount), 0) FROM finance_transactions WHERE type = 'income'");
        $expenses = $this->db->fetchColumn("SELECT COALESCE(SUM(amount), 0) FROM finance_transactions WHERE type = 'expense'");

        $this->render('finances/index', [
            'transactions' => $transactions,
            'pagination' => $pagination,
            'income' => $income,
            'expenses' => $expenses,
            'net' => $income - $expenses,
            'typeFilter' => $typeFilter,
            'categoryFilter' => $categoryFilter,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo
        ]);
    }

    public function create() {
        $this->requireAuth();
        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $customers = $this->db->fetchAll("SELECT id, first_name, last_name, company FROM customers WHERE status = 'active' ORDER BY first_name");
        $taxRates = $this->db->fetchAll("SELECT id, name, rate FROM tax_rates WHERE status = 'active' ORDER BY name");
        $this->render('finances/create', ['departments' => $departments, 'customers' => $customers, 'taxRates' => $taxRates]);
    }

    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();
        $rules = ['title' => ['required' => true, 'label' => 'Title'], 'amount' => ['required' => true, 'numeric' => true, 'label' => 'Amount'], 'transaction_date' => ['required' => true, 'date' => true, 'label' => 'Date']];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) { $this->session->flash('error', reset($validation['errors'])); $this->session->flash('old', $_POST); redirect('/finances/create'); }
        $data = $validation['data'];
        $this->db->insert(
            "INSERT INTO finance_transactions (type, category, department_id, customer_id, invoice_id, title, description, amount, tax_amount, tax_rate_id, payment_method, reference, transaction_date, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$data['type'] ?? 'expense', $_POST['category'] ?? null, $_POST['department_id'] ?? null, $_POST['customer_id'] ?? null, $_POST['invoice_id'] ?? null, $data['title'], $_POST['description'] ?? null, $data['amount'], $_POST['tax_amount'] ?? 0, $_POST['tax_rate_id'] ?? null, $_POST['payment_method'] ?? 'bank', $_POST['reference'] ?? null, $data['transaction_date'], $this->auth->id()]
        );
        auditLog('create', 'finance_transactions', null, 'Created transaction: ' . $data['title']);
        $this->redirectWithSuccess('/finances', 'Transaction created successfully!');
    }

    public function edit($id) {
        $this->requireAuth();
        $transaction = $this->db->fetch("SELECT * FROM finance_transactions WHERE id = ?", [$id]);
        if (!$transaction) { $this->session->flash('error', 'Transaction not found.'); redirect('/finances'); }
        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $customers = $this->db->fetchAll("SELECT id, first_name, last_name, company FROM customers WHERE status = 'active' ORDER BY first_name");
        $taxRates = $this->db->fetchAll("SELECT id, name, rate FROM tax_rates WHERE status = 'active' ORDER BY name");
        $this->render('finances/edit', ['transaction' => $transaction, 'departments' => $departments, 'customers' => $customers, 'taxRates' => $taxRates]);
    }

    public function update($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->execute(
            "UPDATE finance_transactions SET type = ?, category = ?, department_id = ?, customer_id = ?, invoice_id = ?, title = ?, description = ?, amount = ?, tax_amount = ?, tax_rate_id = ?, payment_method = ?, reference = ?, transaction_date = ?, updated_at = NOW() WHERE id = ?",
            [$_POST['type'], $_POST['category'] ?? null, $_POST['department_id'] ?? null, $_POST['customer_id'] ?? null, $_POST['invoice_id'] ?? null, $_POST['title'], $_POST['description'] ?? null, $_POST['amount'], $_POST['tax_amount'] ?? 0, $_POST['tax_rate_id'] ?? null, $_POST['payment_method'] ?? 'bank', $_POST['reference'] ?? null, $_POST['transaction_date'], $id]
        );
        auditLog('update', 'finance_transactions', $id, 'Updated transaction');
        $this->redirectWithSuccess('/finances', 'Transaction updated successfully!');
    }

    public function delete($id) {
        $this->requireAuth();
        $this->db->execute("DELETE FROM finance_transactions WHERE id = ?", [$id]);
        auditLog('delete', 'finance_transactions', $id, 'Deleted transaction');
        $this->redirectWithSuccess('/finances', 'Transaction deleted successfully!');
    }

    // Budgets
    public function budgets() {
        $this->requireAuth();
        $budgets = $this->db->fetchAll(
            "SELECT b.*, d.name as department_name FROM budgets b LEFT JOIN departments d ON b.department_id = d.id ORDER BY b.created_at DESC"
        );
        $totalPlanned = $this->db->fetchColumn("SELECT COALESCE(SUM(planned_amount), 0) FROM budgets WHERE status = 'active'");
        $totalSpent = $this->db->fetchColumn("SELECT COALESCE(SUM(spent_amount), 0) FROM budgets WHERE status = 'active'");
        $this->render('finances/budgets', ['budgets' => $budgets, 'totalPlanned' => $totalPlanned, 'totalSpent' => $totalSpent]);
    }

    public function createBudget() {
        $this->requireAuth();
        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $this->render('finances/create_budget', ['departments' => $departments]);
    }

    public function storeBudget() {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->insert(
            "INSERT INTO budgets (department_id, name, category, planned_amount, spent_amount, period, start_date, end_date, status, created_by) VALUES (?, ?, ?, ?, 0, ?, ?, ?, ?, ?)",
            [$_POST['department_id'] ?? null, $_POST['name'], $_POST['category'] ?? null, $_POST['planned_amount'], $_POST['period'] ?? 'monthly', $_POST['start_date'], $_POST['end_date'], $_POST['status'] ?? 'active', $this->auth->id()]
        );
        auditLog('create', 'budgets', null, 'Created budget: ' . $_POST['name']);
        $this->redirectWithSuccess('/budgets', 'Budget created successfully!');
    }

    public function editBudget($id) {
        $this->requireAuth();
        $budget = $this->db->fetch("SELECT * FROM budgets WHERE id = ?", [$id]);
        if (!$budget) { $this->session->flash('error', 'Budget not found.'); redirect('/budgets'); }
        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $this->render('finances/edit_budget', ['budget' => $budget, 'departments' => $departments]);
    }

    public function updateBudget($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->execute(
            "UPDATE budgets SET department_id = ?, name = ?, category = ?, planned_amount = ?, period = ?, start_date = ?, end_date = ?, status = ?, updated_at = NOW() WHERE id = ?",
            [$_POST['department_id'] ?? null, $_POST['name'], $_POST['category'] ?? null, $_POST['planned_amount'], $_POST['period'] ?? 'monthly', $_POST['start_date'], $_POST['end_date'], $_POST['status'] ?? 'active', $id]
        );
        auditLog('update', 'budgets', $id, 'Updated budget');
        $this->redirectWithSuccess('/budgets', 'Budget updated successfully!');
    }

    // Taxes
    public function taxes() {
        $this->requireAuth();
        $taxRates = $this->db->fetchAll("SELECT * FROM tax_rates ORDER BY name");
        $taxRecords = $this->db->fetchAll(
            "SELECT tr.*, tr2.name as tax_rate_name FROM tax_records tr LEFT JOIN tax_rates tr2 ON tr.tax_rate_id = tr2.id ORDER BY tr.created_at DESC"
        );
        $this->render('finances/taxes', ['taxRates' => $taxRates, 'taxRecords' => $taxRecords]);
    }

    public function createTax() {
        $this->requireAuth();
        $this->render('finances/create_tax');
    }

    public function storeTax() {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->insert(
            "INSERT INTO tax_rates (name, rate, type, country, state, status) VALUES (?, ?, ?, ?, ?, ?)",
            [$_POST['name'], $_POST['rate'], $_POST['type'] ?? 'percentage', $_POST['country'] ?? null, $_POST['state'] ?? null, $_POST['status'] ?? 'active']
        );
        auditLog('create', 'tax_rates', null, 'Created tax rate: ' . $_POST['name']);
        $this->redirectWithSuccess('/taxes', 'Tax rate created successfully!');
    }
}
