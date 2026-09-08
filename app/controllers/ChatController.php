<?php
/**
 * Chat Controller (Virtual Office)
 * Manage chat rooms, messages, meetings
 */

class ChatController extends Controller {

    public function index() {
        $this->requireAuth();
        $userId = $this->auth->id();
        $rooms = $this->db->fetchAll(
            "SELECT cr.*, crm.role FROM chat_rooms cr LEFT JOIN chat_room_members crm ON cr.id = crm.room_id AND crm.user_id = ? WHERE cr.type IN ('department', 'group', 'broadcast') ORDER BY cr.name",
            [$userId]
        );
        if (empty($rooms)) {
            // Create default rooms if none exist
            $this->db->insert("INSERT INTO chat_rooms (name, type, created_by) VALUES ('General', 'broadcast', 1)");
            $rooms = $this->db->fetchAll(
                "SELECT cr.*, crm.role FROM chat_rooms cr LEFT JOIN chat_room_members crm ON cr.id = crm.room_id AND crm.user_id = ? WHERE cr.type IN ('department', 'group', 'broadcast') ORDER BY cr.name",
                [$userId]
            );
        }
        $onlineUsers = $this->auth->getOnlineUsers();
        $this->render('chat/index', ['rooms' => $rooms, 'onlineUsers' => $onlineUsers]);
    }

    public function room($id) {
        $this->requireAuth();
        $userId = $this->auth->id();
        $room = $this->db->fetch("SELECT * FROM chat_rooms WHERE id = ?", [$id]);
        if (!$room) { $this->session->flash('error', 'Room not found.'); redirect('/chat'); }
        // Ensure user is a member
        $member = $this->db->fetch("SELECT * FROM chat_room_members WHERE room_id = ? AND user_id = ?", [$id, $userId]);
        if (!$member) {
            $this->db->insert("INSERT INTO chat_room_members (room_id, user_id, role) VALUES (?, ?, 'member')", [$id, $userId]);
        }
        $messages = $this->db->fetchAll(
            "SELECT cm.*, u.first_name, u.last_name, u.avatar FROM chat_messages cm LEFT JOIN users u ON cm.user_id = u.id WHERE cm.room_id = ? ORDER BY cm.created_at ASC LIMIT 100",
            [$id]
        );
        $members = $this->db->fetchAll(
            "SELECT crm.*, u.first_name, u.last_name, u.avatar, u.is_online, u.work_status FROM chat_room_members crm LEFT JOIN users u ON crm.user_id = u.id WHERE crm.room_id = ?",
            [$id]
        );
        $this->render('chat/room', ['room' => $room, 'messages' => $messages, 'members' => $members]);
    }

    public function sendMessage($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->insert(
            "INSERT INTO chat_messages (room_id, user_id, message, type) VALUES (?, ?, ?, ?)",
            [$id, $this->auth->id(), $_POST['message'] ?? null, $_POST['type'] ?? 'text']
        );
        if (!$this->isAjax()) {
            redirect("/chat/room/$id");
        }
    }

    public function getMessages($id) {
        $this->requireAuth();
        $messages = $this->db->fetchAll(
            "SELECT cm.*, u.first_name, u.last_name, u.avatar FROM chat_messages cm LEFT JOIN users u ON cm.user_id = u.id WHERE cm.room_id = ? ORDER BY cm.created_at ASC LIMIT 50",
            [$id]
        );
        $this->json($messages);
    }

    public function onlineUsers() {
        $this->requireAuth();
        $onlineUsers = $this->auth->getOnlineUsers();
        $this->render('chat/online', ['onlineUsers' => $onlineUsers]);
    }

    // Meetings / Teleconferencing
    public function meetings() {
        $this->requireAuth();
        $meetings = $this->db->fetchAll(
            "SELECT m.*, u.first_name, u.last_name FROM meetings m LEFT JOIN users u ON m.host_id = u.id ORDER BY m.scheduled_at DESC"
        );
        $this->render('chat/meetings', ['meetings' => $meetings]);
    }

    public function createMeeting() {
        $this->requireAuth();
        $users = $this->db->fetchAll("SELECT id, first_name, last_name FROM users WHERE status = 'active' ORDER BY first_name");
        $this->render('chat/create_meeting', ['users' => $users]);
    }

    public function storeMeeting() {
        $this->requireAuth();
        $this->verifyCsrf();
        $rules = ['title' => ['required' => true, 'label' => 'Title']];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) { $this->session->flash('error', reset($validation['errors'])); $this->session->flash('old', $_POST); redirect('/meetings/create'); }
        $data = $validation['data'];
        $roomId = 'room_' . uniqid();
        $meetingId = $this->db->insert(
            "INSERT INTO meetings (title, description, host_id, room_id, scheduled_at, status, max_participants) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$data['title'], $_POST['description'] ?? null, $this->auth->id(), $roomId, $_POST['scheduled_at'] ?? null, 'scheduled', $_POST['max_participants'] ?? 50]
        );
        if (isset($_POST['participants']) && is_array($_POST['participants'])) {
            foreach ($_POST['participants'] as $userId) {
                $this->db->insert("INSERT INTO meeting_participants (meeting_id, user_id, status) VALUES (?, ?, 'invited')", [$meetingId, $userId]);
            }
        }
        auditLog('create', 'meetings', $meetingId, 'Created meeting: ' . $data['title']);
        $this->redirectWithSuccess('/meetings', 'Meeting created successfully!');
    }

    public function joinMeeting($id) {
        $this->requireAuth();
        $meeting = $this->db->fetch("SELECT * FROM meetings WHERE id = ?", [$id]);
        if (!$meeting) { $this->session->flash('error', 'Meeting not found.'); redirect('/meetings'); }
        $this->render('chat/join_meeting', ['meeting' => $meeting]);
    }

    public function startMeeting($id) {
        $this->requireAuth();
        $this->db->execute("UPDATE meetings SET status = 'in_progress', started_at = NOW() WHERE id = ?", [$id]);
        auditLog('start', 'meetings', $id, 'Started meeting');
        $this->redirectWithSuccess("/meetings/$id", 'Meeting started!');
    }

    public function endMeeting($id) {
        $this->requireAuth();
        $this->db->execute("UPDATE meetings SET status = 'ended', ended_at = NOW() WHERE id = ?", [$id]);
        auditLog('end', 'meetings', $id, 'Ended meeting');
        $this->redirectWithSuccess('/meetings', 'Meeting ended!');
    }

    private function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
