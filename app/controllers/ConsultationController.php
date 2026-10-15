<?php
/**
 * Consultation Controller
 * Manage client video consultations, scheduling and the embedded open-source meeting room
 */

class ConsultationController extends Controller {

    /**
     * Ensure the meeting-room columns exist on older installs and backfill secure room tokens.
     */
    private function ensureRoomSchema() {
        try {
            $cols = $this->db->fetchAll("SHOW COLUMNS FROM `consultations`");
            $names = array_column($cols, 'Field');
            if (!in_array('room_token', $names)) {
                $this->db->execute("ALTER TABLE `consultations` ADD COLUMN `room_token` VARCHAR(40) DEFAULT NULL AFTER `meeting_id`");
                $this->db->execute("ALTER TABLE `consultations` ADD KEY `room_token` (`room_token`)");
            }
            if (!in_array('meeting_link', $names)) {
                $this->db->execute("ALTER TABLE `consultations` ADD COLUMN `meeting_link` VARCHAR(255) DEFAULT NULL AFTER `duration_minutes`");
            }
            // Give every booking that lacks a token a secure, unique one.
            $this->db->execute(
                "UPDATE `consultations` SET `room_token` = SUBSTRING(MD5(CONCAT(`id`, RAND())), 1, 18)
                 WHERE `room_token` IS NULL OR `room_token` = ''"
            );
            // Build the internal share link from the token (never an external service).
            $pending = $this->db->fetchAll("SELECT id, room_token FROM `consultations` WHERE `meeting_link` IS NULL OR `meeting_link` = ''");
            foreach ($pending as $p) {
                $link = rtrim(APP_URL, '/') . '/consult/' . $p['room_token'];
                $this->db->execute("UPDATE `consultations` SET `meeting_link` = ? WHERE id = ?", [$link, $p['id']]);
            }
        } catch (\Throwable $e) {
            error_log('ConsultationController::ensureRoomSchema - ' . $e->getMessage());
        }
    }

    public function index() {
        $this->requireAuth();
        $this->ensureRoomSchema();
        $statusFilter = $_GET['status'] ?? '';
        $where = $statusFilter ? "WHERE status = ?" : '';
        $params = $statusFilter ? [$statusFilter] : [];

        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM consultations " . $where, $params);
        $pagination = $this->getPagination($total);

        $consultations = $this->db->fetchAll(
            "SELECT * FROM consultations $where ORDER BY preferred_date DESC, created_at DESC LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}",
            $params
        );

        $this->render('consultations/index', [
            'pageTitle'     => 'Video Consultations & Meetings',
            'consultations' => $consultations,
            'pagination'    => $pagination,
            'statusFilter'  => $statusFilter
        ]);
    }

    public function show($id) {
        $this->requireAuth();
        $this->ensureRoomSchema();
        $consultation = $this->db->fetch("SELECT * FROM consultations WHERE id = ?", [$id]);
        if (!$consultation) {
            $this->session->flash('error', 'Consultation record not found.');
            redirect('/consultations');
        }

        $this->render('consultations/show', [
            'pageTitle'    => 'Consultation Details',
            'consultation' => $consultation
        ]);
    }

    /**
     * Public embedded meeting room. Anyone holding the secure token (client or staff) joins the
     * open-source video room directly inside the app - no external service, no redirect.
     */
    public function room($token) {
        $this->ensureRoomSchema();
        $consultation = $this->db->fetch("SELECT * FROM consultations WHERE room_token = ?", [$token]);
        if (!$consultation) {
            http_response_code(404);
            $this->session->flash('error', 'This meeting room link is invalid or has expired.');
            redirect('/');
            return;
        }

        // Prefer the logged-in participant's real name; otherwise default to the booked client.
        $displayName = $consultation['name'] ?: 'Guest';
        $userEmail = $consultation['email'] ?: '';
        if ($this->auth->check()) {
            $u = $this->auth->user();
            if ($u) {
                $displayName = trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? '')) ?: $displayName;
                $userEmail = $u['email'] ?? $userEmail;
            }
        }

        $this->render('consultations/room', [
            'pageTitle'     => 'Meeting Room: ' . ($consultation['name'] ?? 'Consultation'),
            'consultation'  => $consultation,
            'token'         => $token,
            'roomName'      => LiveKit::roomName('consult-' . $token),
            'livekitUrl'    => LiveKit::url(),
            'lkConfigured'  => LiveKit::isConfigured(),
            'displayName'   => $displayName,
            'userEmail'     => $userEmail,
        ], null);
    }

    /**
     * Public, token-gated LiveKit token endpoint. The unguessable room_token IS the access
     * secret; a valid signed, short-lived token is minted server-side (API secret never sent).
     */
    public function roomAccessToken($token) {
        $this->ensureRoomSchema();

        if (!LiveKit::isConfigured()) {
            return $this->json(['error' => 'The live meeting server is not configured yet. Set LIVEKIT_URL and API keys in .env.'], 503);
        }

        $consultation = $this->db->fetch("SELECT * FROM consultations WHERE room_token = ?", [$token]);
        if (!$consultation) {
            return $this->json(['error' => 'This meeting room link is invalid or has expired.'], 404);
        }

        $roomName = LiveKit::roomName('consult-' . $consultation['room_token']);

        // Logged-in staff keep their real identity; anonymous guests get a stable, scoped id.
        if ($this->auth->check()) {
            $u = $this->auth->user();
            $identity = LiveKit::identity('staff-' . ($u['id'] ?? 0), 'staff');
            $displayName = trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? '')) ?: ($consultation['name'] ?: 'Guest');
        } else {
            $identity = LiveKit::identity('guest-' . substr(hash('sha256', (string)$token), 0, 12), 'guest');
            $displayName = $consultation['name'] ?: 'Guest';
        }

        $accessToken = LiveKit::accessToken($identity, $displayName, $roomName);
        auditLog('livekit_token', 'consultations', $consultation['id'], "Issued LiveKit room token for {$identity}");

        return $this->json([
            'url'         => LiveKit::url(),
            'token'       => $accessToken,
            'room'        => $roomName,
            'identity'    => $identity,
            'displayName' => $displayName,
        ]);
    }

    public function updateStatus($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $status = $_POST['status'] ?? 'confirmed';
        $notes = $_POST['notes'] ?? null;

        // The meeting link is derived from the secure room token (managed by the backend),
        // so staff no longer paste external links.
        $this->db->execute(
            "UPDATE consultations SET status = ?, notes = ?, updated_at = NOW() WHERE id = ?",
            [$status, $notes, $id]
        );

        auditLog('update_consultation', 'consultations', $id, "Updated consultation status to $status");
        $this->redirectWithSuccess("/consultations/$id", 'Consultation updated successfully!');
    }

    public function delete($id) {
        $this->requireAuth();
        $this->db->execute("DELETE FROM consultations WHERE id = ?", [$id]);
        auditLog('delete', 'consultations', $id, 'Deleted consultation record');
        $this->redirectWithSuccess('/consultations', 'Consultation deleted.');
    }
}
