<?php
/**
 * Department Controller
 * Manage company departments
 */

class DepartmentController extends Controller {

    public function index() {
        $this->requireAuth();
        $departments = $this->db->fetchAll(
            "SELECT d.*, u.first_name as head_first, u.last_name as head_last FROM departments d LEFT JOIN users u ON d.head_id = u.id ORDER BY d.name"
        );
        $this->render('departments/index', ['departments' => $departments]);
    }

    public function create() {
        $this->requireAuth();
        $heads = $this->db->fetchAll("SELECT id, first_name, last_name FROM users WHERE status = 'active' ORDER BY first_name");
        $this->render('departments/create', ['heads' => $heads]);
    }

    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();
        $rules = ['name' => ['required' => true, 'label' => 'Department Name'], 'slug' => ['required' => true, 'label' => 'Slug']];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) { $this->session->flash('error', reset($validation['errors'])); $this->session->flash('old', $_POST); redirect('/departments/create'); }
        $data = $validation['data'];
        $this->db->insert(
            "INSERT INTO departments (name, slug, description, head_id, color, status) VALUES (?, ?, ?, ?, ?, ?)",
            [$data['name'], $data['slug'], $_POST['description'] ?? null, $_POST['head_id'] ?? null, $_POST['color'] ?? '#3b82f6', $_POST['status'] ?? 'active']
        );
        auditLog('create', 'departments', null, 'Created department: ' . $data['name']);
        $this->redirectWithSuccess('/departments', 'Department created successfully!');
    }

    public function edit($id) {
        $this->requireAuth();
        $department = $this->db->fetch("SELECT * FROM departments WHERE id = ?", [$id]);
        if (!$department) { $this->session->flash('error', 'Department not found.'); redirect('/departments'); }
        $heads = $this->db->fetchAll("SELECT id, first_name, last_name FROM users WHERE status = 'active' ORDER BY first_name");
        $this->render('departments/edit', ['department' => $department, 'heads' => $heads]);
    }

    public function update($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->execute(
            "UPDATE departments SET name = ?, slug = ?, description = ?, head_id = ?, color = ?, status = ?, updated_at = NOW() WHERE id = ?",
            [$_POST['name'], $_POST['slug'], $_POST['description'] ?? null, $_POST['head_id'] ?? null, $_POST['color'] ?? '#3b82f6', $_POST['status'] ?? 'active', $id]
        );
        auditLog('update', 'departments', $id, 'Updated department');
        $this->redirectWithSuccess('/departments', 'Department updated successfully!');
    }

    public function delete($id) {
        $this->requireAuth();
        $this->db->execute("DELETE FROM departments WHERE id = ?", [$id]);
        auditLog('delete', 'departments', $id, 'Deleted department');
        $this->redirectWithSuccess('/departments', 'Department deleted successfully!');
    }
}
