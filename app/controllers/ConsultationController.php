<?php
/**
 * Consultation Controller
 * Manage client video consultations and their auto-created Google Meet join links
 */

class ConsultationController extends Controller {

    /**
     * Ensure the meeting_link column exists on older installs (no manual migration needed).
     */
    private function ensureRoomSchema() {
        try {
            $cols = $this->db->fetchAll("SHOW COLUMNS FROM `consultations`");
            $names = array_column($cols, 'Field');
            if (!in_array('meeting_link', $names)) {
                $this->db->execute("ALTER TABLE `consultations` ADD COLUMN `meeting_link` VARCHAR(255) DEFAULT NULL AFTER `duration_minutes`");
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

    public function updateStatus($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->ensureRoomSchema();
        $status = $_POST['status'] ?? 'confirmed';
        $notes = $_POST['notes'] ?? null;

        // Staff may paste a Meet link manually; otherwise auto-create one when a booking is confirmed.
        $consultation = $this->db->fetch("SELECT * FROM consultations WHERE id = ?", [$id]);
        $meetingLink = (string)($consultation['meeting_link'] ?? '');
        $manualLink = trim($_POST['meeting_link'] ?? '');

        if ($manualLink !== '') {
            $meetingLink = $manualLink;
        } elseif ($meetingLink === '' && $status === 'confirmed' && $consultation && GoogleMeet::isConfigured()) {
            $start = date('Y-m-d H:i:s', strtotime(($consultation['preferred_date'] ?? date('Y-m-d')) . ' ' . ($consultation['preferred_time'] ?? '10:00 AM')));
            $end = date('Y-m-d H:i:s', strtotime($start . ' +45 minutes'));
            $link = GoogleMeet::createMeeting(
                'Consultation: ' . ($consultation['name'] ?? 'Client'),
                $start, $end,
                array_values(array_filter([$consultation['email'] ?? ''])),
                (string)($consultation['notes'] ?? '')
            );
            if ($link) { $meetingLink = $link; }
        }

        $this->db->execute(
            "UPDATE consultations SET status = ?, notes = ?, meeting_link = ?, updated_at = NOW() WHERE id = ?",
            [$status, $notes, $meetingLink, $id]
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
