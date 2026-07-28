<?php
/**
 * Dashboard Controller
 * Main dashboard with widgets, online users, work status
 */

class DashboardController extends Controller {

    /**
     * Main dashboard
     */
    public function index() {
        $this->requireAuth();

        $user = $this->auth->user();

        // Get dashboard stats
        $stats = $this->getDashboardStats();

        // Get online users
        $onlineUsers = $this->auth->getOnlineUsers();
        $usersAtWork = $this->auth->getUsersAtWork();

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        // Get upcoming events
        $upcomingEvents = $this->getUpcomingEvents();

        // Get tasks due soon
        $tasksDueSoon = $this->getTasksDueSoon();

        // Get financial summary
        $financialSummary = $this->getFinancialSummary();

        // Get lead summary
        $leadSummary = $this->getLeadSummary();

        $this->render('dashboard/index', [
            'user'              => $user,
            'stats'             => $stats,
            'onlineUsers'       => $onlineUsers,
            'usersAtWork'       => $usersAtWork,
            'recentActivities'  => $recentActivities,
            'upcomingEvents'    => $upcomingEvents,
            'tasksDueSoon'      => $tasksDueSoon,
            'financialSummary'  => $financialSummary,
            'leadSummary'       => $leadSummary
        ]);
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats() {
        $stats = [];

        // Total users
        $stats['total_users'] = $this->db->fetchColumn("SELECT COUNT(*) FROM users WHERE status = 'active'");

        // Online users
        $threshold = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        $stats['online_users'] = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM users WHERE is_online = 1 AND last_activity >= ?",
            [$threshold]
        );

        // Total leads
        $stats['total_leads'] = $this->db->fetchColumn("SELECT COUNT(*) FROM leads");

        // Leads this month
        $stats['leads_this_month'] = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM leads WHERE created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')"
        );

        // Total customers
        $stats['total_customers'] = $this->db->fetchColumn("SELECT COUNT(*) FROM customers WHERE status = 'active'");

        // Total invoices
        $stats['total_invoices'] = $this->db->fetchColumn("SELECT COUNT(*) FROM invoices");

        // Total revenue
        $stats['total_revenue'] = $this->db->fetchColumn(
            "SELECT COALESCE(SUM(total), 0) FROM invoices WHERE status IN ('paid', 'partial', 'sent')"
        );

        // Revenue this month
        $stats['revenue_this_month'] = $this->db->fetchColumn(
            "SELECT COALESCE(SUM(amount), 0) FROM finance_transactions WHERE type = 'income' AND transaction_date >= DATE_FORMAT(NOW(), '%Y-%m-01')"
        );

        // Expenses this month
        $stats['expenses_this_month'] = $this->db->fetchColumn(
            "SELECT COALESCE(SUM(amount), 0) FROM finance_transactions WHERE type = 'expense' AND transaction_date >= DATE_FORMAT(NOW(), '%Y-%m-01')"
        );

        // Total tasks
        $stats['total_tasks'] = $this->db->fetchColumn("SELECT COUNT(*) FROM tasks");

        // Tasks in progress
        $stats['tasks_in_progress'] = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM tasks WHERE status = 'in_progress'"
        );

        // Tasks completed
        $stats['tasks_completed'] = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM tasks WHERE status = 'completed'"
        );

        // Open invoices
        $stats['open_invoices'] = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM invoices WHERE status IN ('sent', 'partial', 'overdue')"
        );

        // Overdue invoices
        $stats['overdue_invoices'] = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM invoices WHERE status = 'overdue' OR (status IN ('sent', 'partial') AND due_date < CURDATE())"
        );

        // Total budget
        $stats['total_budget'] = $this->db->fetchColumn(
            "SELECT COALESCE(SUM(planned_amount), 0) FROM budgets WHERE status = 'active'"
        );

        // Total spent
        $stats['total_spent'] = $this->db->fetchColumn(
            "SELECT COALESCE(SUM(spent_amount), 0) FROM budgets WHERE status = 'active'"
        );

        return $stats;
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities() {
        return $this->db->fetchAll(
            "SELECT al.action, al.table_name, al.details, al.created_at, u.first_name, u.last_name
             FROM audit_logs al
             LEFT JOIN users u ON al.user_id = u.id
             ORDER BY al.created_at DESC
             LIMIT 15"
        );
    }

    /**
     * Get upcoming events
     */
    private function getUpcomingEvents() {
        return $this->db->fetchAll(
            "SELECT * FROM events
             WHERE start_datetime >= NOW() AND status IN ('scheduled', 'in_progress')
             ORDER BY start_datetime ASC
             LIMIT 10"
        );
    }

    /**
     * Get tasks due soon
     */
    private function getTasksDueSoon() {
        return $this->db->fetchAll(
            "SELECT t.*, u.first_name, u.last_name
             FROM tasks t
             LEFT JOIN task_assignments ta ON t.id = ta.task_id
             LEFT JOIN users u ON ta.user_id = u.id
             WHERE t.due_date >= NOW() AND t.status NOT IN ('completed', 'cancelled')
             ORDER BY t.due_date ASC
             LIMIT 10"
        );
    }

    /**
     * Get financial summary
     */
    private function getFinancialSummary() {
        $income = $this->db->fetchColumn(
            "SELECT COALESCE(SUM(amount), 0) FROM finance_transactions WHERE type = 'income'"
        );
        $expenses = $this->db->fetchColumn(
            "SELECT COALESCE(SUM(amount), 0) FROM finance_transactions WHERE type = 'expense'"
        );
        return [
            'total_income'   => $income,
            'total_expenses' => $expenses,
            'net_profit'     => $income - $expenses,
            'balance'        => $income - $expenses
        ];
    }

    /**
     * Get lead summary
     */
    private function getLeadSummary() {
        $summary = [];
        $statuses = ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];
        foreach ($statuses as $status) {
            $summary[$status] = $this->db->fetchColumn(
                "SELECT COUNT(*) FROM leads WHERE status = ?",
                [$status]
            );
        }
        return $summary;
    }

    /**
     * Update work status
     */
    public function updateWorkStatus() {
        $this->requireAuth();
        $this->verifyCsrf();

        $status = $_POST['status'] ?? 'working';
        $this->auth->setWorkStatus($status);

        if (!$this->isAjax()) {
            $this->redirectWithSuccess('/dashboard', 'Work status updated.');
        }
    }

    /**
     * Online users page
     */
    public function onlineUsers() {
        $this->requireAuth();
        $onlineUsers = $this->auth->getOnlineUsers();
        $usersAtWork = $this->auth->getUsersAtWork();
        $this->render('dashboard/online-users', [
            'onlineUsers' => $onlineUsers,
            'usersAtWork' => $usersAtWork
        ]);
    }

    /**
     * Work status page
     */
    public function workStatus() {
        $this->requireAuth();
        $allUsers = $this->db->fetchAll(
            "SELECT u.*, d.name as department_name
             FROM users u
             LEFT JOIN departments d ON u.department_id = d.id
             WHERE u.status = 'active'
             ORDER BY u.last_activity DESC"
        );
        $this->render('dashboard/work-status', ['users' => $allUsers]);
    }

    /**
     * API: Online users
     */
    public function apiOnlineUsers() {
        $this->requireAuth();
        $onlineUsers = $this->auth->getOnlineUsers();
        $this->json([
            'online' => $onlineUsers,
            'count'  => count($onlineUsers)
        ]);
    }

    /**
     * API: Dashboard stats
     */
    public function apiStats() {
        $this->requireAuth();
        $stats = $this->getDashboardStats();
        $this->json($stats);
    }

    /**
     * Check if request is AJAX
     */
    private function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
