<?php
/**
 * Event Controller
 * Manage events, calendar, timetable
 */

class EventController extends Controller {

    public function index() {
        $this->requireAuth();
        $events = $this->db->fetchAll(
            "SELECT e.*, d.name as department_name, u.first_name, u.last_name FROM events e LEFT JOIN departments d ON e.department_id = d.id LEFT JOIN users u ON e.created_by = u.id ORDER BY e.start_datetime DESC"
        );
        $this->render('events/index', ['events' => $events]);
    }

    public function calendar() {
        $this->requireAuth();
        $events = $this->db->fetchAll(
            "SELECT e.*, d.name as department_name FROM events e LEFT JOIN departments d ON e.department_id = d.id WHERE e.start_datetime >= DATE_SUB(NOW(), INTERVAL 1 MONTH) ORDER BY e.start_datetime ASC"
        );
        $this->render('events/calendar', ['events' => $events]);
    }

    public function create() {
        $this->requireAuth();
        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $users = $this->db->fetchAll("SELECT id, first_name, last_name FROM users WHERE status = 'active' ORDER BY first_name");
        $this->render('events/create', ['departments' => $departments, 'users' => $users]);
    }

    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();
        $rules = ['title' => ['required' => true, 'label' => 'Title'], 'start_datetime' => ['required' => true, 'label' => 'Start Date'], 'end_datetime' => ['required' => true, 'label' => 'End Date']];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) { $this->session->flash('error', reset($validation['errors'])); $this->session->flash('old', $_POST); redirect('/events/create'); }
        $data = $validation['data'];
        $eventId = $this->db->insert(
            "INSERT INTO events (title, description, type, start_datetime, end_datetime, all_day, location, department_id, created_by, priority, status, color) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$data['title'], $_POST['description'] ?? null, $_POST['type'] ?? 'meeting', $data['start_datetime'], $data['end_datetime'], $_POST['all_day'] ?? 0, $_POST['location'] ?? null, $_POST['department_id'] ?? null, $this->auth->id(), $_POST['priority'] ?? 'medium', $_POST['status'] ?? 'scheduled', $_POST['color'] ?? '#3b82f6']
        );
        // Add attendees
        if (isset($_POST['attendees']) && is_array($_POST['attendees'])) {
            foreach ($_POST['attendees'] as $userId) {
                $this->db->insert("INSERT INTO event_attendees (event_id, user_id, status) VALUES (?, ?, 'invited')", [$eventId, $userId]);
            }
        }
        auditLog('create', 'events', $eventId, 'Created event: ' . $data['title']);
        $this->redirectWithSuccess('/events', 'Event created successfully!');
    }

    public function show($id) {
        $this->requireAuth();
        $event = $this->db->fetch(
            "SELECT e.*, d.name as department_name, u.first_name, u.last_name FROM events e LEFT JOIN departments d ON e.department_id = d.id LEFT JOIN users u ON e.created_by = u.id WHERE e.id = ?",
            [$id]
        );
        if (!$event) { $this->session->flash('error', 'Event not found.'); redirect('/events'); }
        $attendees = $this->db->fetchAll(
            "SELECT ea.*, u.first_name, u.last_name, u.avatar FROM event_attendees ea LEFT JOIN users u ON ea.user_id = u.id WHERE ea.event_id = ?",
            [$id]
        );
        $this->render('events/show', ['event' => $event, 'attendees' => $attendees]);
    }

    public function edit($id) {
        $this->requireAuth();
        $event = $this->db->fetch("SELECT * FROM events WHERE id = ?", [$id]);
        if (!$event) { $this->session->flash('error', 'Event not found.'); redirect('/events'); }
        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $users = $this->db->fetchAll("SELECT id, first_name, last_name FROM users WHERE status = 'active' ORDER BY first_name");
        $currentAttendees = $this->db->fetchAll("SELECT user_id FROM event_attendees WHERE event_id = ?", [$id]);
        $attendeeIds = array_column($currentAttendees, 'user_id');
        $this->render('events/edit', ['event' => $event, 'departments' => $departments, 'users' => $users, 'attendeeIds' => $attendeeIds]);
    }

    public function update($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->execute(
            "UPDATE events SET title = ?, description = ?, type = ?, start_datetime = ?, end_datetime = ?, all_day = ?, location = ?, department_id = ?, priority = ?, status = ?, color = ?, updated_at = NOW() WHERE id = ?",
            [$_POST['title'], $_POST['description'] ?? null, $_POST['type'] ?? 'meeting', $_POST['start_datetime'], $_POST['end_datetime'], $_POST['all_day'] ?? 0, $_POST['location'] ?? null, $_POST['department_id'] ?? null, $_POST['priority'] ?? 'medium', $_POST['status'] ?? 'scheduled', $_POST['color'] ?? '#3b82f6', $id]
        );
        $this->db->execute("DELETE FROM event_attendees WHERE event_id = ?", [$id]);
        if (isset($_POST['attendees']) && is_array($_POST['attendees'])) {
            foreach ($_POST['attendees'] as $userId) {
                $this->db->insert("INSERT INTO event_attendees (event_id, user_id, status) VALUES (?, ?, 'invited')", [$id, $userId]);
            }
        }
        auditLog('update', 'events', $id, 'Updated event');
        $this->redirectWithSuccess("/events/$id", 'Event updated successfully!');
    }

    public function delete($id) {
        $this->requireAuth();
        $this->db->execute("DELETE FROM events WHERE id = ?", [$id]);
        $this->db->execute("DELETE FROM event_attendees WHERE event_id = ?", [$id]);
        auditLog('delete', 'events', $id, 'Deleted event');
        $this->redirectWithSuccess('/events', 'Event deleted successfully!');
    }

    // Timetable
    public function timetable() {
        $this->requireAuth();
        $slots = $this->db->fetchAll(
            "SELECT ts.*, d.name as department_name FROM timetable_slots ts LEFT JOIN departments d ON ts.department_id = d.id ORDER BY ts.day_of_week, ts.start_time"
        );
        $departments = $this->db->fetchAll("SELECT id, name FROM departments WHERE status = 'active' ORDER BY name");
        $this->render('events/timetable', ['slots' => $slots, 'departments' => $departments]);
    }

    public function storeTimetable() {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->insert(
            "INSERT INTO timetable_slots (department_id, day_of_week, start_time, end_time, title, description, color, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$_POST['department_id'] ?? null, $_POST['day_of_week'], $_POST['start_time'], $_POST['end_time'], $_POST['title'], $_POST['description'] ?? null, $_POST['color'] ?? '#3b82f6', $this->auth->id()]
        );
        auditLog('create', 'timetable_slots', null, 'Created timetable slot: ' . $_POST['title']);
        $this->redirectWithSuccess('/timetable', 'Timetable slot created successfully!');
    }
}
