<?php
/**
 * Consultation Controller
 * Manage client Zoom consultations, scheduling and status
 */

class ConsultationController extends Controller {

    public function index() {
        $this->requireAuth();
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
            'pageTitle'     => 'Zoom Consultations',
            'consultations' => $consultations,
            'pagination'    => $pagination,
            'statusFilter'  => $statusFilter
        ]);
    }

    public function show($id) {
        $this->requireAuth();
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
        $status = $_POST['status'] ?? 'confirmed';
        $zoomLink = $_POST['zoom_link'] ?? null;
        $notes = $_POST['notes'] ?? null;

        $this->db->execute(
            "UPDATE consultations SET status = ?, zoom_link = COALESCE(?, zoom_link), notes = ?, updated_at = NOW() WHERE id = ?",
            [$status, $zoomLink, $notes, $id]
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
