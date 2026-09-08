<?php
/**
 * Lead Controller (CRM)
 * Manage leads, follow-ups, activities
 */

class LeadController extends Controller {

    public function index() {
        $this->requireAuth();
        $statusFilter = $_GET['status'] ?? '';
        $priorityFilter = $_GET['priority'] ?? '';
        $search = $_GET['search'] ?? '';

        $where = [];
        $params = [];

        if ($statusFilter) { $where[] = "status = ?"; $params[] = $statusFilter; }
        if ($priorityFilter) { $where[] = "priority = ?"; $params[] = $priorityFilter; }
        if ($search) { $where[] = "(first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR company LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; $params[] = "%$search%"; $params[] = "%$search%"; }

        $whereSql = $where ? implode(' AND ', $where) : '1=1';
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM leads WHERE $whereSql", $params);
        $pagination = $this->getPagination($total);

        $leads = $this->db->fetchAll(
            "SELECT l.*, s.name as source_name, u.first_name as assigned_first, u.last_name as assigned_last
             FROM leads l
             LEFT JOIN lead_sources s ON l.source_id = s.id
             LEFT JOIN users u ON l.assigned_to = u.id
             WHERE $whereSql
             ORDER BY l.created_at DESC
             LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}",
            $params
        );

        $sources = $this->db->fetchAll("SELECT id, name FROM lead_sources WHERE status = 'active' ORDER BY name");
        $users = $this->db->fetchAll("SELECT id, first_name, last_name FROM users WHERE status = 'active' ORDER BY first_name");

        $this->render('leads/index', [
            'leads' => $leads,
            'sources' => $sources,
            'users' => $users,
            'pagination' => $pagination,
            'statusFilter' => $statusFilter,
            'priorityFilter' => $priorityFilter,
            'search' => $search
        ]);
    }

    public function create() {
        $this->requireAuth();
        $sources = $this->db->fetchAll("SELECT id, name FROM lead_sources WHERE status = 'active' ORDER BY name");
        $users = $this->db->fetchAll("SELECT id, first_name, last_name FROM users WHERE status = 'active' ORDER BY first_name");
        $this->render('leads/create', ['sources' => $sources, 'users' => $users]);
    }

    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();

        $rules = [
            'first_name' => ['required' => true, 'label' => 'First Name'],
            'last_name'  => ['required' => true, 'label' => 'Last Name'],
            'email'      => ['email' => true, 'label' => 'Email'],
        ];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) {
            $this->session->flash('error', reset($validation['errors']));
            $this->session->flash('old', $_POST);
            redirect('/leads/create');
        }

        $data = $validation['data'];
        $this->db->insert(
            "INSERT INTO leads (source_id, assigned_to, first_name, last_name, email, phone, company, position, value, status, priority, notes, next_followup)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $_POST['source_id'] ?? null,
                $_POST['assigned_to'] ?? null,
                $data['first_name'], $data['last_name'],
                $data['email'] ?? null,
                $_POST['phone'] ?? null,
                $_POST['company'] ?? null,
                $_POST['position'] ?? null,
                $_POST['value'] ?? 0,
                $_POST['status'] ?? 'new',
                $_POST['priority'] ?? 'medium',
                $_POST['notes'] ?? null,
                $_POST['next_followup'] ?? null
            ]
        );

        auditLog('create', 'leads', null, 'Created lead: ' . $data['first_name'] . ' ' . $data['last_name']);
        $this->redirectWithSuccess('/leads', 'Lead created successfully!');
    }

    public function show($id) {
        $this->requireAuth();
        $lead = $this->db->fetch(
            "SELECT l.*, s.name as source_name, u.first_name as assigned_first, u.last_name as assigned_last
             FROM leads l
             LEFT JOIN lead_sources s ON l.source_id = s.id
             LEFT JOIN users u ON l.assigned_to = u.id
             WHERE l.id = ?",
            [$id]
        );
        if (!$lead) { $this->session->flash('error', 'Lead not found.'); redirect('/leads'); }

        $activities = $this->db->fetchAll(
            "SELECT la.*, u.first_name, u.last_name FROM lead_activities la LEFT JOIN users u ON la.user_id = u.id WHERE la.lead_id = ? ORDER BY la.activity_at DESC",
            [$id]
        );

        $this->render('leads/show', ['lead' => $lead, 'activities' => $activities]);
    }

    public function edit($id) {
        $this->requireAuth();
        $lead = $this->db->fetch("SELECT * FROM leads WHERE id = ?", [$id]);
        if (!$lead) { $this->session->flash('error', 'Lead not found.'); redirect('/leads'); }
        $sources = $this->db->fetchAll("SELECT id, name FROM lead_sources WHERE status = 'active' ORDER BY name");
        $users = $this->db->fetchAll("SELECT id, first_name, last_name FROM users WHERE status = 'active' ORDER BY first_name");
        $this->render('leads/edit', ['lead' => $lead, 'sources' => $sources, 'users' => $users]);
    }

    public function update($id) {
        $this->requireAuth();
        $this->verifyCsrf();

        $lead = $this->db->fetch("SELECT * FROM leads WHERE id = ?", [$id]);
        if (!$lead) { $this->session->flash('error', 'Lead not found.'); redirect('/leads'); }

        $this->db->execute(
            "UPDATE leads SET source_id = ?, assigned_to = ?, first_name = ?, last_name = ?, email = ?, phone = ?, company = ?, position = ?, value = ?, status = ?, priority = ?, notes = ?, next_followup = ?, updated_at = NOW() WHERE id = ?",
            [
                $_POST['source_id'] ?? null,
                $_POST['assigned_to'] ?? null,
                $_POST['first_name'], $_POST['last_name'],
                $_POST['email'] ?? null,
                $_POST['phone'] ?? null,
                $_POST['company'] ?? null,
                $_POST['position'] ?? null,
                $_POST['value'] ?? 0,
                $_POST['status'] ?? 'new',
                $_POST['priority'] ?? 'medium',
                $_POST['notes'] ?? null,
                $_POST['next_followup'] ?? null,
                $id
            ]
        );

        auditLog('update', 'leads', $id, 'Updated lead');
        $this->redirectWithSuccess("/leads/$id", 'Lead updated successfully!');
    }

    public function delete($id) {
        $this->requireAuth();
        $this->db->execute("DELETE FROM leads WHERE id = ?", [$id]);
        $this->db->execute("DELETE FROM lead_activities WHERE lead_id = ?", [$id]);
        auditLog('delete', 'leads', $id, 'Deleted lead');
        $this->redirectWithSuccess('/leads', 'Lead deleted successfully!');
    }

    public function addActivity($id) {
        $this->requireAuth();
        $this->verifyCsrf();

        $this->db->insert(
            "INSERT INTO lead_activities (lead_id, user_id, type, subject, description) VALUES (?, ?, ?, ?, ?)",
            [
                $id,
                $this->auth->id(),
                $_POST['type'] ?? 'note',
                $_POST['subject'] ?? null,
                $_POST['description'] ?? null
            ]
        );

        auditLog('activity', 'lead_activities', null, 'Added activity to lead ' . $id);
        $this->redirectWithSuccess("/leads/$id", 'Activity added successfully!');
    }

    public function followup() {
        $this->requireAuth();
        $leads = $this->db->fetchAll(
            "SELECT l.*, u.first_name, u.last_name FROM leads l LEFT JOIN users u ON l.assigned_to = u.id WHERE l.next_followup <= NOW() AND l.status NOT IN ('closed_won', 'closed_lost') ORDER BY l.next_followup ASC"
        );
        $this->render('leads/followup', ['leads' => $leads]);
    }

    /**
     * Visual CRM Sales Pipeline (Kanban)
     */
    public function pipeline() {
        $this->requireAuth();
        $allLeads = $this->db->fetchAll(
            "SELECT l.*, u.first_name, u.last_name, s.name as source_name 
             FROM leads l 
             LEFT JOIN users u ON l.assigned_to = u.id 
             LEFT JOIN lead_sources s ON l.source_id = s.id 
             ORDER BY l.value DESC"
        );

        $stages = [
            'new'          => ['name' => 'New Inquiries', 'color' => '#3b82f6', 'deals' => [], 'total' => 0],
            'contacted'    => ['name' => 'Contacted / Zoom', 'color' => '#6366f1', 'deals' => [], 'total' => 0],
            'qualified'    => ['name' => 'Qualified Scope', 'color' => '#8b5cf6', 'deals' => [], 'total' => 0],
            'proposal'     => ['name' => 'Proposal Sent', 'color' => '#f59e0b', 'deals' => [], 'total' => 0],
            'negotiation'  => ['name' => 'Negotiation', 'color' => '#ec4899', 'deals' => [], 'total' => 0],
            'closed_won'   => ['name' => 'Closed Won', 'color' => '#10b981', 'deals' => [], 'total' => 0],
            'closed_lost'  => ['name' => 'Closed Lost', 'color' => '#94a3b8', 'deals' => [], 'total' => 0]
        ];

        foreach ($allLeads as $lead) {
            $status = $lead['status'] ?? 'new';
            if (isset($stages[$status])) {
                $stages[$status]['deals'][] = $lead;
                $stages[$status]['total'] += (float)$lead['value'];
            }
        }

        $this->render('leads/pipeline', [
            'pageTitle' => 'Sales Pipeline (Kanban)',
            'stages'    => $stages,
            'totalLeads'=> count($allLeads)
        ]);
    }

    /**
     * Update Lead Stage from Kanban
     */
    public function updateStage($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $newStage = $_POST['stage'] ?? 'new';
        $this->db->execute("UPDATE leads SET status = ?, updated_at = NOW() WHERE id = ?", [$newStage, $id]);
        auditLog('pipeline_move', 'leads', $id, "Moved lead to $newStage");

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            $this->json(['success' => true]);
            return;
        }

        $this->redirectWithSuccess('/leads/pipeline', 'Lead stage updated!');
    }
}
