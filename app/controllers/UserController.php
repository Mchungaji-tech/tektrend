<?php
/**
 * User Controller (Admin)
 * Manage system users
 */

class UserController extends Controller {

    public function index() {
        $this->requireRole('admin');
        $search = $_GET['search'] ?? '';
        $where = $search ? "WHERE u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ? OR u.employee_id LIKE ?" : '';
        $params = $search ? ["%$search%", "%$search%", "%$search%", "%$search%"] : [];
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM users u " . ($where ? "WHERE " . substr($where, 6) : ''), $params);
        $pagination = $this->getPagination($total);
        $users = $this->db->fetchAll(
            "SELECT u.*, d.name as department_name FROM users u LEFT JOIN departments d ON u.department_id = d.id $where ORDER BY u.created_at DESC LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}",
            $params
        );
        $this->render('users/index', ['users' => $users, 'pagination' => $pagination, 'search' => $search]);
    }

    public function create() {
        $this->requireRole('admin');
        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $this->render('users/create', ['departments' => $departments]);
    }

    public function store() {
        $this->requireRole('admin');
        $this->verifyCsrf();
        $rules = ['employee_id' => ['required' => true, 'label' => 'Employee ID'], 'first_name' => ['required' => true, 'label' => 'First Name'], 'last_name' => ['required' => true, 'label' => 'Last Name'], 'email' => ['required' => true, 'email' => true, 'label' => 'Email'], 'password' => ['required' => true, 'min' => 8, 'label' => 'Password']];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) { $this->session->flash('error', reset($validation['errors'])); $this->session->flash('old', $_POST); redirect('/users/create'); }
        $data = $validation['data'];
        $result = $this->auth->register([
            'department_id' => $_POST['department_id'] ?? null, 'employee_id' => $data['employee_id'], 'first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'email' => $data['email'], 'phone' => $_POST['phone'] ?? null, 'password' => $data['password'], 'role' => $_POST['role'] ?? 'employee', 'position' => $_POST['position'] ?? null, 'status' => $_POST['status'] ?? 'active'
        ]);
        if ($result['success']) { $this->redirectWithSuccess('/users', 'User created successfully!'); }
        else { $this->session->flash('error', $result['message']); $this->session->flash('old', $_POST); redirect('/users/create'); }
    }

    public function edit($id) {
        $this->requireRole('admin');
        $user = $this->db->fetch("SELECT * FROM users WHERE id = ?", [$id]);
        if (!$user) { $this->session->flash('error', 'User not found.'); redirect('/users'); }
        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $this->render('users/edit', ['user' => $user, 'departments' => $departments]);
    }

    public function update($id) {
        $this->requireRole('admin');
        $this->verifyCsrf();
        $this->db->execute(
            "UPDATE users SET department_id = ?, employee_id = ?, first_name = ?, last_name = ?, email = ?, phone = ?, role = ?, position = ?, status = ?, updated_at = NOW() WHERE id = ?",
            [$_POST['department_id'] ?? null, $_POST['employee_id'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'] ?? null, $_POST['role'] ?? 'employee', $_POST['position'] ?? null, $_POST['status'] ?? 'active', $id]
        );
        if (!empty($_POST['password'])) {
            $this->db->execute("UPDATE users SET password = ? WHERE id = ?", [$this->auth->hashPassword($_POST['password']), $id]);
        }
        auditLog('update', 'users', $id, 'Updated user');
        $this->redirectWithSuccess('/users', 'User updated successfully!');
    }

    public function delete($id) {
        $this->requireRole('admin');
        $this->db->execute("DELETE FROM users WHERE id = ?", [$id]);
        $this->db->execute("DELETE FROM user_sessions WHERE user_id = ?", [$id]);
        auditLog('delete', 'users', $id, 'Deleted user');
        $this->redirectWithSuccess('/users', 'User deleted successfully!');
    }
}
