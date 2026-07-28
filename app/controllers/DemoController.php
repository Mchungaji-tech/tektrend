<?php
/**
 * Demo Controller
 * Manage demo cards
 */

class DemoController extends Controller {

    public function index() {
        $this->requireAuth();
        $demos = $this->db->fetchAll(
            "SELECT d.*, u.first_name, u.last_name FROM demos d LEFT JOIN users u ON d.created_by = u.id ORDER BY d.sort_order, d.created_at DESC"
        );
        $this->render('demos/index', ['demos' => $demos]);
    }

    public function create() {
        $this->requireAuth();
        $this->render('demos/create');
    }

    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();
        $rules = ['title' => ['required' => true, 'label' => 'Title'], 'slug' => ['required' => true, 'label' => 'Slug']];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) { $this->session->flash('error', reset($validation['errors'])); $this->session->flash('old', $_POST); redirect('/demos/create'); }
        $data = $validation['data'];
        $this->db->insert(
            "INSERT INTO demos (title, slug, description, short_description, icon, image, url, category, visibility, password, is_active, sort_order, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$data['title'], $data['slug'], $_POST['description'] ?? null, $_POST['short_description'] ?? null, $_POST['icon'] ?? 'fas fa-cube', $_POST['image'] ?? null, $_POST['url'] ?? null, $_POST['category'] ?? 'general', $_POST['visibility'] ?? 'public', $_POST['password'] ?? null, $_POST['is_active'] ?? 1, $_POST['sort_order'] ?? 0, $this->auth->id()]
        );
        auditLog('create', 'demos', null, 'Created demo: ' . $data['title']);
        $this->redirectWithSuccess('/demos', 'Demo created successfully!');
    }

    public function edit($id) {
        $this->requireAuth();
        $demo = $this->db->fetch("SELECT * FROM demos WHERE id = ?", [$id]);
        if (!$demo) { $this->session->flash('error', 'Demo not found.'); redirect('/demos'); }
        $tech = $this->db->fetchAll("SELECT name FROM demo_tech WHERE demo_id = ? ORDER BY sort_order", [$id]);
        $this->render('demos/edit', ['demo' => $demo, 'tech' => $tech]);
    }

    public function update($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->execute(
            "UPDATE demos SET title = ?, slug = ?, description = ?, short_description = ?, icon = ?, image = ?, url = ?, category = ?, visibility = ?, password = ?, is_active = ?, sort_order = ?, updated_at = NOW() WHERE id = ?",
            [$_POST['title'], $_POST['slug'], $_POST['description'] ?? null, $_POST['short_description'] ?? null, $_POST['icon'] ?? 'fas fa-cube', $_POST['image'] ?? null, $_POST['url'] ?? null, $_POST['category'] ?? 'general', $_POST['visibility'] ?? 'public', $_POST['password'] ?? null, $_POST['is_active'] ?? 1, $_POST['sort_order'] ?? 0, $id]
        );
        $this->db->execute("DELETE FROM demo_tech WHERE demo_id = ?", [$id]);
        if (isset($_POST['tech']) && is_array($_POST['tech'])) {
            $i = 0;
            foreach ($_POST['tech'] as $techName) {
                if (!empty($techName)) {
                    $this->db->insert("INSERT INTO demo_tech (demo_id, name, sort_order) VALUES (?, ?, ?)", [$id, $techName, $i++]);
                }
            }
        }
        auditLog('update', 'demos', $id, 'Updated demo');
        $this->redirectWithSuccess('/demos', 'Demo updated successfully!');
    }

    public function delete($id) {
        $this->requireAuth();
        $this->db->execute("DELETE FROM demos WHERE id = ?", [$id]);
        $this->db->execute("DELETE FROM demo_tech WHERE demo_id = ?", [$id]);
        auditLog('delete', 'demos', $id, 'Deleted demo');
        $this->redirectWithSuccess('/demos', 'Demo deleted successfully!');
    }
}
