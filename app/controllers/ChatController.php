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
        $roomId = $this->buildRoomName($data['title']);
        $scheduledAt = $_POST['start_time'] ?? ($_POST['scheduled_at'] ?? null);
        $meetingId = $this->db->insert(
            "INSERT INTO meetings (title, description, host_id, room_id, scheduled_at, status, max_participants) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$data['title'], $_POST['description'] ?? null, $this->auth->id(), $roomId, $scheduledAt ?: null, 'scheduled', $_POST['max_participants'] ?? 50]
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
        $meeting = $this->db->fetch(
            "SELECT m.*, u.first_name AS host_first, u.last_name AS host_last
             FROM meetings m LEFT JOIN users u ON m.host_id = u.id WHERE m.id = ?",
            [$id]
        );
        if (!$meeting) { $this->session->flash('error', 'Meeting not found.'); redirect('/meetings'); }

        // The backend owns the room name. Guarantee one exists (older rows may be NULL).
        $roomName = $meeting['room_id'] ?: $this->buildRoomName($meeting['title']);
        if (!$meeting['room_id']) {
            $this->db->execute("UPDATE meetings SET room_id = ? WHERE id = ?", [$roomName, $id]);
        }

        // Mark the current user as joined for attendance tracking.
        $userId = $this->auth->id();
        $existing = $this->db->fetch("SELECT id FROM meeting_participants WHERE meeting_id = ? AND user_id = ?", [$id, $userId]);
        if ($existing) {
            $this->db->execute("UPDATE meeting_participants SET status = 'joined', joined_at = NOW() WHERE id = ?", [$existing['id']]);
        } else {
            $this->db->insert("INSERT INTO meeting_participants (meeting_id, user_id, status, joined_at) VALUES (?, ?, 'joined', NOW())", [$id, $userId]);
        }

        $currentUser = $this->auth->user();
        $displayName = trim(($currentUser['first_name'] ?? '') . ' ' . ($currentUser['last_name'] ?? '')) ?: 'Guest';

        $this->render('chat/join_meeting', [
            'meeting'     => $meeting,
            'roomName'    => $roomName,
            'jitsiDomain' => JITSI_DOMAIN,
            'displayName' => $displayName,
            'userEmail'   => $currentUser['email'] ?? '',
        ]);
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

    /**
     * Build a Jitsi-safe, backend-controlled room name from the meeting title.
     * Result looks like: tektrend-architecture-review-5f3c9a  (lowercase, hyphenated, unique).
     */
    private function buildRoomName($title) {
        $prefix = preg_replace('/[^a-z0-9]+/i', '', JITSI_ROOM_PREFIX) ?: 'tektrend';
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', (string)$title), '-'));
        $slug = substr($slug ?: 'meeting', 0, 40);
        return $prefix . '-' . $slug . '-' . substr(uniqid(), -6);
    }
}
