<?php
/**
 * Partner Admin Controller
 * Staff console (/admin/partners): review and approve/reject partner applications.
 * Effectively admin-only: guarded by ['staff'] routing + requireRole('admin').
 */

class PartnerAdminController extends Controller {

    public function index() {
        $this->requireRole('admin');

        $statusFilter = $_GET['status'] ?? '';
        $where  = $statusFilter ? "WHERE p.status = ?" : '';
        $params = $statusFilter ? [$statusFilter] : [];

        $applications = $this->db->fetchAll(
            "SELECT p.*, u.first_name, u.last_name, u.email, u.phone, u.status AS user_status
             FROM partners p LEFT JOIN users u ON p.user_id = u.id
             $where ORDER BY p.created_at DESC",
            $params
        );

        $counts = $this->db->fetch(
            "SELECT
                COALESCE(SUM(status = 'pending'), 0)  AS pending,
                COALESCE(SUM(status = 'approved'), 0) AS approved,
                COALESCE(SUM(status = 'rejected'), 0) AS rejected
             FROM partners"
        );

        $this->render('admin/partners', [
            'pageTitle'    => 'Partner Applications',
            'currentPage'  => 'partners',
            'applications' => $applications,
            'counts'       => $counts,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function approve($id) {
        $this->requireRole('admin');
        $this->verifyCsrf();

        $id = (int)$id;
        $partner = $this->db->fetch("SELECT * FROM partners WHERE id = ?", [$id]);
        if (!$partner) {
            $this->redirectWithError('/admin/partners', 'Application not found.');
        }

        try {
            $this->db->execute("UPDATE partners SET status = 'approved' WHERE id = ?", [$id]);
            if (!empty($partner['user_id'])) {
                $this->db->execute("UPDATE users SET status = 'active' WHERE id = ?", [$partner['user_id']]);
            }
            auditLog('approve', 'partners', $id, 'Approved partner application');
            $this->redirectWithSuccess('/admin/partners', 'Partner approved.');
        } catch (\Throwable $e) {
            error_log('PartnerAdminController::approve - ' . $e->getMessage());
            $this->redirectWithError('/admin/partners', 'Could not approve partner.');
        }
    }

    public function reject($id) {
        $this->requireRole('admin');
        $this->verifyCsrf();

        $id = (int)$id;
        $partner = $this->db->fetch("SELECT * FROM partners WHERE id = ?", [$id]);
        if (!$partner) {
            $this->redirectWithError('/admin/partners', 'Application not found.');
        }

        try {
            $this->db->execute("UPDATE partners SET status = 'rejected' WHERE id = ?", [$id]);
            if (!empty($partner['user_id'])) {
                $this->db->execute("UPDATE users SET status = 'inactive' WHERE id = ?", [$partner['user_id']]);
            }
            auditLog('reject', 'partners', $id, 'Rejected partner application');
            $this->redirectWithSuccess('/admin/partners', 'Partner application rejected.');
        } catch (\Throwable $e) {
            error_log('PartnerAdminController::reject - ' . $e->getMessage());
            $this->redirectWithError('/admin/partners', 'Could not reject partner.');
        }
    }
}
