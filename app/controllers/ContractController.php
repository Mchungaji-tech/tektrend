<?php
/**
 * Contract Controller
 * Manage client consulting contracts, scope agreements, and signatures
 */

class ContractController extends Controller {

    public function index() {
        $this->requireAuth();
        $statusFilter = $_GET['status'] ?? '';
        $where = $statusFilter ? "WHERE c.status = ?" : '';
        $params = $statusFilter ? [$statusFilter] : [];

        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM contracts c " . $where, $params);
        $pagination = $this->getPagination($total);

        $contracts = $this->db->fetchAll(
            "SELECT c.*, cu.first_name as customer_first, cu.last_name as customer_last, cu.company as customer_company 
             FROM contracts c 
             LEFT JOIN customers cu ON c.customer_id = cu.id 
             $where 
             ORDER BY c.created_at DESC 
             LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}",
            $params
        );

        $this->render('contracts/index', [
            'pageTitle'    => 'Contracts & Agreements',
            'contracts'    => $contracts,
            'pagination'   => $pagination,
            'statusFilter' => $statusFilter
        ]);
    }

    public function create() {
        $this->requireAuth();
        $customers = $this->db->fetchAll("SELECT id, first_name, last_name, company FROM customers WHERE status = 'active' ORDER BY first_name");
        $leads = $this->db->fetchAll("SELECT id, first_name, last_name, company FROM leads ORDER BY first_name");
        
        $this->render('contracts/create', [
            'pageTitle' => 'Draft New Contract',
            'customers' => $customers,
            'leads'     => $leads
        ]);
    }

    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();

        $rules = [
            'title'      => ['required' => true, 'label' => 'Contract Title'],
            'start_date' => ['required' => true, 'label' => 'Start Date'],
            'value'      => ['required' => true, 'numeric' => true, 'label' => 'Contract Value']
        ];

        $val = $this->validatePost($rules);
        if (!$val['valid']) {
            $this->session->flash('error', reset($val['errors']));
            redirect('/contracts/create');
        }

        $contractNum = 'CNT-' . date('Y') . '-' . str_pad((string)rand(10, 999), 3, '0', STR_PAD_LEFT);

        $contractId = $this->db->insert(
            "INSERT INTO contracts (contract_number, title, customer_id, lead_id, value, start_date, end_date, terms, status, signed_by_name, created_by) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $contractNum,
                $val['data']['title'],
                $_POST['customer_id'] ?? null,
                $_POST['lead_id'] ?? null,
                $val['data']['value'],
                $val['data']['start_date'],
                $_POST['end_date'] ?? null,
                $_POST['terms'] ?? null,
                $_POST['status'] ?? 'draft',
                $_POST['signed_by_name'] ?? null,
                $this->auth->id()
            ]
        );

        auditLog('create_contract', 'contracts', $contractId, "Created contract $contractNum");
        $this->redirectWithSuccess('/contracts', 'Contract agreement created successfully!');
    }

    public function show($id) {
        $this->requireAuth();
        $contract = $this->db->fetch(
            "SELECT c.*, cu.first_name as customer_first, cu.last_name as customer_last, cu.company as customer_company, cu.email as customer_email, cu.phone as customer_phone, cu.address, cu.city, cu.country 
             FROM contracts c 
             LEFT JOIN customers cu ON c.customer_id = cu.id 
             WHERE c.id = ?",
            [$id]
        );

        if (!$contract) {
            $this->session->flash('error', 'Contract not found.');
            redirect('/contracts');
        }

        $this->render('contracts/show', [
            'pageTitle' => 'Contract ' . $contract['contract_number'],
            'contract'  => $contract
        ]);
    }

    public function edit($id) {
        $this->requireAuth();
        $contract = $this->db->fetch("SELECT * FROM contracts WHERE id = ?", [$id]);
        if (!$contract) {
            $this->session->flash('error', 'Contract not found.');
            redirect('/contracts');
        }

        $customers = $this->db->fetchAll("SELECT id, first_name, last_name, company FROM customers WHERE status = 'active' ORDER BY first_name");
        $leads = $this->db->fetchAll("SELECT id, first_name, last_name, company FROM leads ORDER BY first_name");

        $this->render('contracts/edit', [
            'pageTitle' => 'Edit Contract',
            'contract'  => $contract,
            'customers' => $customers,
            'leads'     => $leads
        ]);
    }

    public function update($id) {
        $this->requireAuth();
        $this->verifyCsrf();

        $this->db->execute(
            "UPDATE contracts SET title = ?, customer_id = ?, lead_id = ?, value = ?, start_date = ?, end_date = ?, terms = ?, status = ?, signed_by_name = ?, updated_at = NOW() WHERE id = ?",
            [
                $_POST['title'],
                $_POST['customer_id'] ?? null,
                $_POST['lead_id'] ?? null,
                $_POST['value'] ?? 0,
                $_POST['start_date'],
                $_POST['end_date'] ?? null,
                $_POST['terms'] ?? null,
                $_POST['status'] ?? 'draft',
                $_POST['signed_by_name'] ?? null,
                $id
            ]
        );

        auditLog('update_contract', 'contracts', $id, "Updated contract $id");
        $this->redirectWithSuccess("/contracts/$id", 'Contract updated successfully!');
    }

    public function printContract($id) {
        $this->requireAuth();
        $contract = $this->db->fetch(
            "SELECT c.*, cu.first_name as customer_first, cu.last_name as customer_last, cu.company as customer_company, cu.email as customer_email, cu.phone as customer_phone, cu.address, cu.city, cu.country 
             FROM contracts c 
             LEFT JOIN customers cu ON c.customer_id = cu.id 
             WHERE c.id = ?",
            [$id]
        );

        if (!$contract) {
            $this->session->flash('error', 'Contract not found.');
            redirect('/contracts');
        }

        $this->view->setLayout(null);
        $this->render('contracts/print', ['contract' => $contract]);
    }

    public function delete($id) {
        $this->requireAuth();
        $this->db->execute("DELETE FROM contracts WHERE id = ?", [$id]);
        auditLog('delete_contract', 'contracts', $id, "Deleted contract $id");
        $this->redirectWithSuccess('/contracts', 'Contract deleted.');
    }
}
