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

    // Meetings / Teleconferencing (Google Meet links, auto-created server-side)
    public function meetings() {
        $this->requireAuth();
        $this->ensureMeetingSchema();
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
        $this->ensureMeetingSchema();
        $rules = ['title' => ['required' => true, 'label' => 'Title']];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) { $this->session->flash('error', reset($validation['errors'])); $this->session->flash('old', $_POST); redirect('/meetings/create'); }
        $data = $validation['data'];
        $scheduledAt = $_POST['start_time'] ?? ($_POST['scheduled_at'] ?? null);
        $participantIds = isset($_POST['participants']) && is_array($_POST['participants']) ? $_POST['participants'] : [];

        // Best-effort: auto-create a Google Meet link for the scheduled slot.
        $meetingUrl = $this->generateMeetLink($data['title'], $scheduledAt ?: date('Y-m-d H:i:s'), $participantIds, (string)($_POST['description'] ?? ''));

        $meetingId = $this->db->insert(
            "INSERT INTO meetings (title, description, host_id, meeting_url, scheduled_at, status, max_participants) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$data['title'], $_POST['description'] ?? null, $this->auth->id(), $meetingUrl, $scheduledAt ?: null, 'scheduled', $_POST['max_participants'] ?? 50]
        );
        foreach ($participantIds as $userId) {
            $this->db->insert("INSERT INTO meeting_participants (meeting_id, user_id, status) VALUES (?, ?, 'invited')", [$meetingId, $userId]);
        }
        auditLog('create', 'meetings', $meetingId, 'Created meeting: ' . $data['title']);
        $this->redirectWithSuccess('/meetings', 'Meeting created!' . ($meetingUrl ? ' Google Meet link is ready.' : ' Open it to generate a Meet link.'));
    }

    /**
     * Instant meeting: create the record with a Google Meet link and drop the host straight into it.
     */
    public function startInstantMeeting() {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->ensureMeetingSchema();
        $title = trim($_POST['title'] ?? '');
        if ($title === '') { $title = 'Instant Meeting - ' . date('M j, Y H:i'); }
        $description = $_POST['description'] ?? 'Instant video meeting.';
        $meetingUrl = $this->generateMeetLink($title, date('Y-m-d H:i:s'), [], (string)$description);
        $meetingId = $this->db->insert(
            "INSERT INTO meetings (title, description, host_id, meeting_url, scheduled_at, started_at, status, max_participants) VALUES (?, ?, ?, ?, NOW(), NOW(), 'in_progress', ?)",
            [$title, $description, $this->auth->id(), $meetingUrl, $_POST['max_participants'] ?? 50]
        );
        // Host counts as a participant.
        $this->db->insert("INSERT INTO meeting_participants (meeting_id, user_id, status, joined_at) VALUES (?, ?, 'joined', NOW())", [$meetingId, $this->auth->id()]);
        auditLog('start', 'meetings', $meetingId, 'Started instant meeting: ' . $title);
        redirect("/meetings/$meetingId");
    }

    public function joinMeeting($id) {
        $this->requireAuth();
        $this->ensureMeetingSchema();
        $meeting = $this->db->fetch(
            "SELECT m.*, u.first_name AS host_first, u.last_name AS host_last
             FROM meetings m LEFT JOIN users u ON m.host_id = u.id WHERE m.id = ?",
            [$id]
        );
        if (!$meeting) { $this->session->flash('error', 'Meeting not found.'); redirect('/meetings'); }

        // Guarantee a Google Meet link exists; create one on demand when it is still empty.
        if (empty($meeting['meeting_url']) && GoogleMeet::isConfigured()) {
            $participantIds = array_column(
                $this->db->fetchAll("SELECT user_id FROM meeting_participants WHERE meeting_id = ?", [$id]),
                'user_id'
            );
            $url = $this->generateMeetLink($meeting['title'], $meeting['scheduled_at'] ?: date('Y-m-d H:i:s'), $participantIds, (string)($meeting['description'] ?? ''));
            if ($url) {
                $this->db->execute("UPDATE meetings SET meeting_url = ? WHERE id = ?", [$url, $id]);
                $meeting['meeting_url'] = $url;
            }
        }

        // Mark the current user as joined for attendance tracking.
        $userId = $this->auth->id();
        $existing = $this->db->fetch("SELECT id FROM meeting_participants WHERE meeting_id = ? AND user_id = ?", [$id, $userId]);
        if ($existing) {
            $this->db->execute("UPDATE meeting_participants SET status = 'joined', joined_at = NOW() WHERE id = ?", [$existing['id']]);
        } else {
            $this->db->insert("INSERT INTO meeting_participants (meeting_id, user_id, status, joined_at) VALUES (?, ?, 'joined', NOW())", [$id, $userId]);
        }

        $this->render('chat/join_meeting', [
            'meeting' => $meeting,
        ]);
    }

    public function startMeeting($id) {
        $this->requireAuth();
        $this->ensureMeetingSchema();
        // Ensure a Meet link exists before flipping the meeting to live.
        $meeting = $this->db->fetch("SELECT * FROM meetings WHERE id = ?", [$id]);
        if ($meeting && empty($meeting['meeting_url'])) {
            $participantIds = array_column($this->db->fetchAll("SELECT user_id FROM meeting_participants WHERE meeting_id = ?", [$id]), 'user_id');
            $url = $this->generateMeetLink($meeting['title'], $meeting['scheduled_at'] ?: date('Y-m-d H:i:s'), $participantIds, (string)($meeting['description'] ?? ''));
            if ($url) { $this->db->execute("UPDATE meetings SET meeting_url = ? WHERE id = ?", [$url, $id]); }
        }
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
     * Build a Google Meet link via the Calendar API (best-effort). Returns the meet.google.com
     * URL, or null when Google Meet is not configured or the request fails.
     */
    private function generateMeetLink($title, $start, array $participantIds = [], $description = '') {
        if (!GoogleMeet::isConfigured()) {
            return null;
        }
        $startTs = strtotime((string)$start) ?: time();
        $end = date('Y-m-d H:i:s', $startTs + 3600);
        $attendees = $this->participantEmails($participantIds);
        // Always invite the host so the event has an owner.
        $host = $this->auth->user();
        if (!empty($host['email'])) { $attendees[] = $host['email']; }
        return GoogleMeet::createMeeting((string)$title, date('Y-m-d H:i:s', $startTs), $end, array_values(array_unique($attendees)), (string)$description);
    }

    /** Resolve user IDs to their email addresses for Meet invites. */
    private function participantEmails(array $ids) {
        $ids = array_values(array_filter(array_map('intval', $ids)));
        if (empty($ids)) { return []; }
        $in = implode(',', array_fill(0, count($ids), '?'));
        $rows = $this->db->fetchAll("SELECT email FROM users WHERE id IN ($in)", $ids);
        return array_column($rows, 'email');
    }

    /**
     * Ensure the meetings table has a column for the stored Google Meet URL (no manual migration).
     */
    private function ensureMeetingSchema() {
        try {
            $cols = $this->db->fetchAll("SHOW COLUMNS FROM `meetings`");
            $names = array_column($cols, 'Field');
            if (!in_array('meeting_url', $names)) {
                $this->db->execute("ALTER TABLE `meetings` ADD COLUMN `meeting_url` VARCHAR(255) DEFAULT NULL AFTER `room_id`");
            }
        } catch (\Throwable $e) {
            error_log('ChatController::ensureMeetingSchema - ' . $e->getMessage());
        }
    }
}
