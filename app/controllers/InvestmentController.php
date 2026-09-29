<?php
/**
 * Investment Controller
 * Manage investor relations inquiries, capital allocation tiers, NDAs and term sheets
 */

class InvestmentController extends Controller {

    private function ensureTable() {
        try {
            $this->db->execute("
                CREATE TABLE IF NOT EXISTS investments (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    investor_name VARCHAR(191) NOT NULL,
                    email VARCHAR(191) NOT NULL,
                    phone VARCHAR(50) DEFAULT NULL,
                    investor_type VARCHAR(100) DEFAULT 'Angel Investor',
                    target_amount DECIMAL(15,2) DEFAULT 1000.00,
                    status ENUM('inquiry', 'nda_sent', 'data_room_access', 'term_sheet', 'funded', 'archived') DEFAULT 'inquiry',
                    notes TEXT DEFAULT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        } catch (\Throwable $e) {}
    }

    public function index() {
        $this->requireAuth();
        $this->ensureTable();

        $statusFilter = $_GET['status'] ?? '';
        $where = $statusFilter ? "WHERE status = ?" : '';
        $params = $statusFilter ? [$statusFilter] : [];

        $total = (int)$this->db->fetchColumn("SELECT COUNT(*) FROM investments " . $where, $params);
        $pagination = $this->getPagination($total);

        $investments = $this->db->fetchAll(
            "SELECT * FROM investments $where ORDER BY created_at DESC LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}",
            $params
        );

        // Stats
        $stats = [
            'total_inquiries' => (int)$this->db->fetchColumn("SELECT COUNT(*) FROM investments"),
            'total_target'    => (float)$this->db->fetchColumn("SELECT COALESCE(SUM(target_amount), 0) FROM investments"),
            'funded_amount'   => (float)$this->db->fetchColumn("SELECT COALESCE(SUM(target_amount), 0) FROM investments WHERE status = 'funded'"),
            'active_pipeline' => (int)$this->db->fetchColumn("SELECT COUNT(*) FROM investments WHERE status IN ('inquiry', 'nda_sent', 'data_room_access', 'term_sheet')"),
        ];

        $this->render('investments/index', [
            'pageTitle'    => 'Investor Relations & Growth Capital',
            'investments'  => $investments,
            'stats'        => $stats,
            'pagination'   => $pagination,
            'statusFilter' => $statusFilter
        ]);
    }

    public function show($id) {
        $this->requireAuth();
        $this->ensureTable();

        $investment = $this->db->fetch("SELECT * FROM investments WHERE id = ?", [$id]);
        if (!$investment) {
            $this->session->flash('error', 'Investor inquiry record not found.');
            redirect('/investments');
            return;
        }

        $this->render('investments/show', [
            'pageTitle'  => 'Investor Profile · ' . $investment['investor_name'],
            'investment' => $investment
        ]);
    }

    public function updateStatus($id) {
        $this->requireAuth();
        $this->ensureTable();
        $this->verifyCsrf();

        $status = $_POST['status'] ?? 'inquiry';
        $targetAmount = (float)($_POST['target_amount'] ?? 0);
        $notes = $_POST['notes'] ?? '';

        $this->db->execute(
            "UPDATE investments SET status = ?, target_amount = ?, notes = ?, updated_at = NOW() WHERE id = ?",
            [$status, $targetAmount, $notes, $id]
        );

        auditLog('update_investment', 'investments', $id, "Updated investment inquiry status to $status");
        $this->redirectWithSuccess("/investments/$id", 'Investor details updated successfully!');
    }

    public function delete($id) {
        $this->requireAuth();
        $this->ensureTable();

        $this->db->execute("DELETE FROM investments WHERE id = ?", [$id]);
        auditLog('delete_investment', 'investments', $id, "Deleted investor inquiry record #$id");
        $this->redirectWithSuccess('/investments', 'Investor inquiry deleted.');
    }
}
