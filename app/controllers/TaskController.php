<?php
/**
 * Task Controller
 * Manage tasks, assignments, comments
 */

class TaskController extends Controller {

    public function index() {
        $this->requireAuth();
        $statusFilter = $_GET['status'] ?? '';
        $priorityFilter = $_GET['priority'] ?? '';
        $search = $_GET['search'] ?? '';

        $where = [];
        $params = [];
        if ($statusFilter) { $where[] = "t.status = ?"; $params[] = $statusFilter; }
        if ($priorityFilter) { $where[] = "t.priority = ?"; $params[] = $priorityFilter; }
        if ($search) { $where[] = "(t.title LIKE ? OR t.description LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }

        $whereSql = $where ? implode(' AND ', $where) : '1=1';
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM tasks t WHERE $whereSql", $params);
        $pagination = $this->getPagination($total);

        $tasks = $this->db->fetchAll(
            "SELECT t.*, u.first_name, u.last_name, d.name as department_name FROM tasks t LEFT JOIN users u ON t.created_by = u.id LEFT JOIN departments d ON t.department_id = d.id WHERE $whereSql ORDER BY t.created_at DESC LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}",
            $params
        );

        $this->render('tasks/index', ['tasks' => $tasks, 'pagination' => $pagination, 'statusFilter' => $statusFilter, 'priorityFilter' => $priorityFilter, 'search' => $search]);
    }

    public function create() {
        $this->requireAuth();
        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $users = $this->db->fetchAll("SELECT id, first_name, last_name FROM users WHERE status = 'active' ORDER BY first_name");
        $this->render('tasks/create', ['departments' => $departments, 'users' => $users]);
    }

    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();
        $rules = ['title' => ['required' => true, 'label' => 'Title']];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) { $this->session->flash('error', reset($validation['errors'])); $this->session->flash('old', $_POST); redirect('/tasks/create'); }
        $data = $validation['data'];
        $taskId = $this->db->insert(
            "INSERT INTO tasks (title, description, department_id, created_by, priority, status, due_date, start_date, estimated_hours, spent_hours, progress) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$data['title'], $_POST['description'] ?? null, $_POST['department_id'] ?? null, $this->auth->id(), $_POST['priority'] ?? 'medium', $_POST['status'] ?? 'todo', $_POST['due_date'] ?? null, $_POST['start_date'] ?? null, $_POST['estimated_hours'] ?? 0, $_POST['spent_hours'] ?? 0, $_POST['progress'] ?? 0]
        );
        if (isset($_POST['assignees']) && is_array($_POST['assignees'])) {
            foreach ($_POST['assignees'] as $userId) {
                $this->db->insert("INSERT INTO task_assignments (task_id, user_id, assigned_by) VALUES (?, ?, ?)", [$taskId, $userId, $this->auth->id()]);
            }
        }
        auditLog('create', 'tasks', $taskId, 'Created task: ' . $data['title']);
        $this->redirectWithSuccess('/tasks', 'Task created successfully!');
    }

    public function show($id) {
        $this->requireAuth();
        $task = $this->db->fetch(
            "SELECT t.*, d.name as department_name, u.first_name, u.last_name FROM tasks t LEFT JOIN departments d ON t.department_id = d.id LEFT JOIN users u ON t.created_by = u.id WHERE t.id = ?",
            [$id]
        );
        if (!$task) { $this->session->flash('error', 'Task not found.'); redirect('/tasks'); }
        $assignees = $this->db->fetchAll(
            "SELECT ta.*, u.first_name, u.last_name, u.avatar FROM task_assignments ta LEFT JOIN users u ON ta.user_id = u.id WHERE ta.task_id = ?",
            [$id]
        );
        $comments = $this->db->fetchAll(
            "SELECT tc.*, u.first_name, u.last_name FROM task_comments tc LEFT JOIN users u ON tc.user_id = u.id WHERE tc.task_id = ? ORDER BY tc.created_at DESC",
            [$id]
        );
        $this->render('tasks/show', ['task' => $task, 'assignees' => $assignees, 'comments' => $comments]);
    }

    public function edit($id) {
        $this->requireAuth();
        $task = $this->db->fetch("SELECT * FROM tasks WHERE id = ?", [$id]);
        if (!$task) { $this->session->flash('error', 'Task not found.'); redirect('/tasks'); }
        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $users = $this->db->fetchAll("SELECT id, first_name, last_name FROM users WHERE status = 'active' ORDER BY first_name");
        $currentAssignees = $this->db->fetchAll("SELECT user_id FROM task_assignments WHERE task_id = ?", [$id]);
        $assigneeIds = array_column($currentAssignees, 'user_id');
        $this->render('tasks/edit', ['task' => $task, 'departments' => $departments, 'users' => $users, 'assigneeIds' => $assigneeIds]);
    }

    public function update($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->execute(
            "UPDATE tasks SET title = ?, description = ?, department_id = ?, priority = ?, status = ?, due_date = ?, start_date = ?, estimated_hours = ?, spent_hours = ?, progress = ?, updated_at = NOW() WHERE id = ?",
            [$_POST['title'], $_POST['description'] ?? null, $_POST['department_id'] ?? null, $_POST['priority'] ?? 'medium', $_POST['status'] ?? 'todo', $_POST['due_date'] ?? null, $_POST['start_date'] ?? null, $_POST['estimated_hours'] ?? 0, $_POST['spent_hours'] ?? 0, $_POST['progress'] ?? 0, $id]
        );
        $this->db->execute("DELETE FROM task_assignments WHERE task_id = ?", [$id]);
        if (isset($_POST['assignees']) && is_array($_POST['assignees'])) {
            foreach ($_POST['assignees'] as $userId) {
                $this->db->insert("INSERT INTO task_assignments (task_id, user_id, assigned_by) VALUES (?, ?, ?)", [$id, $userId, $this->auth->id()]);
            }
        }
        auditLog('update', 'tasks', $id, 'Updated task');
        $this->redirectWithSuccess("/tasks/$id", 'Task updated successfully!');
    }

    public function delete($id) {
        $this->requireAuth();
        $this->db->execute("DELETE FROM tasks WHERE id = ?", [$id]);
        $this->db->execute("DELETE FROM task_assignments WHERE task_id = ?", [$id]);
        $this->db->execute("DELETE FROM task_comments WHERE task_id = ?", [$id]);
        auditLog('delete', 'tasks', $id, 'Deleted task');
        $this->redirectWithSuccess('/tasks', 'Task deleted successfully!');
    }

    public function addComment($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->insert(
            "INSERT INTO task_comments (task_id, user_id, comment) VALUES (?, ?, ?)",
            [$id, $this->auth->id(), $_POST['comment'] ?? null]
        );
        auditLog('comment', 'task_comments', null, 'Added comment to task ' . $id);
        $this->redirectWithSuccess("/tasks/$id", 'Comment added!');
    }
}
