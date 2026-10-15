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
        $this->ensureTypingTable();
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
        $this->render('chat/room', [
            'room' => $room,
            'messages' => $messages,
            'members' => $members,
            'currentUserId' => $userId,
        ]);
    }

    public function sendMessage($id) {
        $this->requireAuth();
        $message = trim($_POST['message'] ?? '');
        $type = $_POST['type'] ?? 'text';
        $isAjax = $this->isAjax();

        // AJAX callers must present a valid token; fall back to redirect flow otherwise.
        $token = $_POST['csrf_token'] ?? $_POST['_token'] ?? null;
        if ($isAjax) {
            if (!$token || !verifyCsrf($token)) { $this->json(['error' => 'Invalid security token.'], 419); }
            if ($message === '') { $this->json(['error' => 'Message cannot be empty.'], 422); }
        } else {
            $this->verifyCsrf();
            if ($message === '') { $this->session->flash('error', 'Message cannot be empty.'); redirect("/chat/room/$id"); }
        }

        $newId = $this->db->insert(
            "INSERT INTO chat_messages (room_id, user_id, message, type) VALUES (?, ?, ?, ?)",
            [$id, $this->auth->id(), $message, $type]
        );
        // Clear this user's typing indicator now that the message is sent.
        $this->db->execute("DELETE FROM chat_typing WHERE room_id = ? AND user_id = ?", [$id, $this->auth->id()]);

        if ($isAjax) {
            $row = $this->db->fetch(
                "SELECT cm.*, u.first_name, u.last_name, u.avatar FROM chat_messages cm LEFT JOIN users u ON cm.user_id = u.id WHERE cm.id = ?",
                [$newId]
            );
            $this->json(['message' => $this->formatMessage($row, $this->auth->id())]);
        }
        redirect("/chat/room/$id");
    }

    public function getMessages($id) {
        $this->requireAuth();
        $this->ensureTypingTable();
        $userId = $this->auth->id();
        $after = (int)($_GET['after'] ?? 0);

        if ($after > 0) {
            $rows = $this->db->fetchAll(
                "SELECT cm.*, u.first_name, u.last_name, u.avatar FROM chat_messages cm LEFT JOIN users u ON cm.user_id = u.id WHERE cm.room_id = ? AND cm.id > ? ORDER BY cm.id ASC LIMIT 100",
                [$id, $after]
            );
        } else {
            $rows = $this->db->fetchAll(
                "SELECT cm.*, u.first_name, u.last_name, u.avatar FROM chat_messages cm LEFT JOIN users u ON cm.user_id = u.id WHERE cm.room_id = ? ORDER BY cm.created_at ASC LIMIT 100",
                [$id]
            );
        }
        $messages = array_map(fn($r) => $this->formatMessage($r, $userId), $rows);
        $maxId = $rows ? (int)end($rows)['id'] : $after;

        // Anyone typing in this room within the last 6 seconds (excluding me).
        $typing = $this->db->fetchAll(
            "SELECT ct.user_id, u.first_name, u.last_name FROM chat_typing ct LEFT JOIN users u ON ct.user_id = u.id
             WHERE ct.room_id = ? AND ct.updated_at > (NOW() - INTERVAL 6 SECOND) AND ct.user_id <> ?",
            [$id, $userId]
        );
        $typing = array_map(function ($t) {
            $name = trim(($t['first_name'] ?? '') . ' ' . ($t['last_name'] ?? ''));
            return ['id' => (int)$t['user_id'], 'name' => $name ?: 'Someone'];
        }, $typing);

        $this->json(['messages' => $messages, 'typing' => $typing, 'max_id' => $maxId]);
    }

    /**
     * Lightweight presence ping so other clients can show a typing indicator.
     */
    public function typing($id) {
        $this->requireAuth();
        $this->ensureTypingTable();
        $userId = $this->auth->id();
        $token = $_POST['csrf_token'] ?? $_POST['_token'] ?? null;
        if (!$token || !verifyCsrf($token)) { $this->json(['ok' => false, 'error' => 'invalid_token'], 419); }

        $isTyping = !empty($_POST['typing']);
        if ($isTyping) {
            $this->db->execute(
                "INSERT INTO chat_typing (room_id, user_id, updated_at) VALUES (?, ?, NOW())
                 ON DUPLICATE KEY UPDATE updated_at = NOW()",
                [$id, $userId]
            );
        } else {
            $this->db->execute("DELETE FROM chat_typing WHERE room_id = ? AND user_id = ?", [$id, $userId]);
        }
        $this->json(['ok' => true]);
    }

    /**
     * Shape a chat_messages row into the JSON payload consumed by the front end.
     */
    private function formatMessage($row, $currentUserId) {
        if (!$row) { return null; }
        $fullName = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
        return [
            'id' => (int)$row['id'],
            'user_id' => (int)$row['user_id'],
            'name' => $fullName ?: 'Unknown',
            'initial' => strtoupper(substr($row['first_name'] ?? '?', 0, 1)),
            'message' => (string)($row['message'] ?? ''),
            'type' => $row['type'] ?? 'text',
            'time' => formatDateTime($row['created_at']),
            'is_self' => (int)$row['user_id'] === (int)$currentUserId,
        ];
    }

    /**
     * Ensure the ephemeral typing-presence table exists (no manual migration needed).
     */
    private function ensureTypingTable() {
        try {
            $this->db->execute("CREATE TABLE IF NOT EXISTS `chat_typing` (
                `room_id` INT UNSIGNED NOT NULL,
                `user_id` INT UNSIGNED NOT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`room_id`, `user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } catch (\Throwable $e) {
            error_log('ensureTypingTable error: ' . $e->getMessage());
        }
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

    /**
     * Instant meeting: create a live room and drop the user straight into it (no scheduling, no redirect off-site).
     */
    public function startInstantMeeting() {
        $this->requireAuth();
        $this->verifyCsrf();
        $title = trim($_POST['title'] ?? '');
        if ($title === '') { $title = 'Instant Meeting - ' . date('M j, Y H:i'); }
        $roomId = $this->buildRoomName($title);
        $meetingId = $this->db->insert(
            "INSERT INTO meetings (title, description, host_id, room_id, scheduled_at, started_at, status, max_participants) VALUES (?, ?, ?, ?, NOW(), NOW(), 'in_progress', ?)",
            [$title, $_POST['description'] ?? 'Instant video meeting.', $this->auth->id(), $roomId, $_POST['max_participants'] ?? 50]
        );
        // Host counts as a participant.
        $this->db->insert("INSERT INTO meeting_participants (meeting_id, user_id, status, joined_at) VALUES (?, ?, 'joined', NOW())", [$meetingId, $this->auth->id()]);
        auditLog('start', 'meetings', $meetingId, 'Started instant meeting: ' . $title);
        redirect("/meetings/$meetingId");
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
