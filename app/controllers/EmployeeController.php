<?php
/**
 * Employee Controller
 * Manage employee records (users)
 */

class EmployeeController extends Controller {

    public function index() {
        $this->requireAuth();
        $search = $_GET['search'] ?? '';
        $where = $search ? "WHERE u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ? OR u.employee_id LIKE ?" : '';
        $params = $search ? ["%$search%", "%$search%", "%$search%", "%$search%"] : [];
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM users u " . ($where ? "WHERE " . substr($where, 6) : ''), $params);
        $pagination = $this->getPagination($total);
        $employees = $this->db->fetchAll(
            "SELECT u.*, d.name as department_name FROM users u LEFT JOIN departments d ON u.department_id = d.id $where ORDER BY u.created_at DESC LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}",
            $params
        );
        $this->render('employees/index', ['employees' => $employees, 'pagination' => $pagination, 'search' => $search]);
    }

    public function create() {
        $this->requireAuth();
        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $this->render('employees/create', ['departments' => $departments]);
    }

    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();
        $rules = ['employee_id' => ['required' => true, 'label' => 'Employee ID'], 'first_name' => ['required' => true, 'label' => 'First Name'], 'last_name' => ['required' => true, 'label' => 'Last Name'], 'email' => ['required' => true, 'email' => true, 'label' => 'Email'], 'password' => ['required' => true, 'min' => 8, 'label' => 'Password']];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) { $this->session->flash('error', reset($validation['errors'])); $this->session->flash('old', $_POST); redirect('/employees/create'); }
        $data = $validation['data'];
        $result = $this->auth->register([
            'department_id' => $_POST['department_id'] ?? null, 'employee_id' => $data['employee_id'], 'first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'email' => $data['email'], 'phone' => $_POST['phone'] ?? null, 'password' => $data['password'], 'role' => $_POST['role'] ?? 'employee', 'position' => $_POST['position'] ?? null, 'status' => $_POST['status'] ?? 'active'
        ]);
        if ($result['success']) { $this->redirectWithSuccess('/employees', 'Employee created successfully!'); }
        else { $this->session->flash('error', $result['message']); $this->session->flash('old', $_POST); redirect('/employees/create'); }
    }

    public function show($id) {
        $this->requireAuth();
        $employee = $this->db->fetch("SELECT u.*, d.name as department_name FROM users u LEFT JOIN departments d ON u.department_id = d.id WHERE u.id = ?", [$id]);
        if (!$employee) { $this->session->flash('error', 'Employee not found.'); redirect('/employees'); }
        $this->render('employees/show', ['employee' => $employee]);
    }

    public function edit($id) {
        $this->requireAuth();
        $employee = $this->db->fetch("SELECT * FROM users WHERE id = ?", [$id]);
        if (!$employee) { $this->session->flash('error', 'Employee not found.'); redirect('/employees'); }
        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $this->render('employees/edit', ['employee' => $employee, 'departments' => $departments]);
    }

    public function update($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->execute(
            "UPDATE users SET department_id = ?, employee_id = ?, first_name = ?, last_name = ?, email = ?, phone = ?, role = ?, position = ?, status = ?, updated_at = NOW() WHERE id = ?",
            [$_POST['department_id'] ?? null, $_POST['employee_id'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'] ?? null, $_POST['role'] ?? 'employee', $_POST['position'] ?? null, $_POST['status'] ?? 'active', $id]
        );
        if (!empty($_POST['password'])) {
            $this->db->execute("UPDATE users SET password = ? WHERE id = ?", [$this->auth->hashPassword($_POST['password']), $id]);
        }
        auditLog('update', 'users', $id, 'Updated employee');
        $this->redirectWithSuccess("/employees/$id", 'Employee updated successfully!');
    }

    public function delete($id) {
        $this->requireAuth();
        $this->db->execute("DELETE FROM users WHERE id = ?", [$id]);
        $this->db->execute("DELETE FROM user_sessions WHERE user_id = ?", [$id]);
        auditLog('delete', 'users', $id, 'Deleted employee');
        $this->redirectWithSuccess('/employees', 'Employee deleted successfully!');
    }
}
