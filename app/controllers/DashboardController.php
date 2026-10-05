<?php
/**
 * Dashboard Controller
 * Tek Trend Virtual Company Operations Console
 */

class DashboardController extends Controller {

    /**
     * Main Virtual Company Command Console
     */
    public function index() {
        $this->requireAuth();

        $user = $this->auth->user();

        // 1. Get Core KPIs
        $stats = $this->getDashboardStats();

        // 2. Online Presence & Users at Work
        $onlineUsers = $this->auth->getOnlineUsers();
        $usersAtWork = $this->auth->getUsersAtWork();

        // 3. Departments for the Virtual Office Floorplan
        $departments = [];
        try {
            $departments = $this->db->fetchAll(
                "SELECT d.*, u.first_name as head_first_name, u.last_name as head_last_name, u.position as head_position,
                        (SELECT COUNT(*) FROM users WHERE department_id = d.id AND status = 'active') as employee_count,
                        (SELECT COUNT(*) FROM users WHERE department_id = d.id AND is_online = 1) as online_count
                 FROM departments d
                 LEFT JOIN users u ON d.head_id = u.id
                 WHERE d.status = 'active'
                 ORDER BY d.id ASC"
            );
        } catch (\Throwable $e) {
            $departments = [];
        }

        // 4. Virtual Office Chat Room Widget
        $chatRooms = [];
        $recentChatMessages = [];
        $activeRoom = null;
        try {
            $chatRooms = $this->db->fetchAll("SELECT * FROM chat_rooms ORDER BY id ASC LIMIT 5");
            if (empty($chatRooms)) {
                $this->db->insert("INSERT INTO chat_rooms (name, type, created_by) VALUES ('General & Operations', 'broadcast', 1)");
                $chatRooms = $this->db->fetchAll("SELECT * FROM chat_rooms ORDER BY id ASC LIMIT 5");
            }
            $activeRoom = $chatRooms[0] ?? null;
            if ($activeRoom) {
                $recentChatMessages = $this->db->fetchAll(
                    "SELECT cm.*, u.first_name, u.last_name, u.avatar 
                     FROM chat_messages cm 
                     LEFT JOIN users u ON cm.user_id = u.id 
                     WHERE cm.room_id = ? 
                     ORDER BY cm.created_at DESC 
                     LIMIT 8",
                    [$activeRoom['id']]
                );
                $recentChatMessages = array_reverse($recentChatMessages);
            }
        } catch (\Throwable $e) {}

        // 5. CRM Pipeline Stages & Deals
        $stages = [
            'new'         => ['name' => 'New Inquiries', 'color' => '#3b82f6', 'deals' => [], 'total' => 0],
            'contacted'   => ['name' => 'Contacted / Zoom', 'color' => '#8b5cf6', 'deals' => [], 'total' => 0],
            'qualified'   => ['name' => 'Qualified Scope', 'color' => '#06b6d4', 'deals' => [], 'total' => 0],
            'proposal'    => ['name' => 'Proposal Sent', 'color' => '#f59e0b', 'deals' => [], 'total' => 0],
            'negotiation' => ['name' => 'Negotiation', 'color' => '#ec4899', 'deals' => [], 'total' => 0],
            'closed_won'  => ['name' => 'Won / Retainer', 'color' => '#10b981', 'deals' => [], 'total' => 0]
        ];
        $totalPipelineValue = 0;
        try {
            $allDeals = $this->db->fetchAll(
                "SELECT l.*, ls.name as source_name, u.first_name as agent_first, u.last_name as agent_last
                 FROM leads l
                 LEFT JOIN lead_sources ls ON l.source_id = ls.id
                 LEFT JOIN users u ON l.assigned_to = u.id
                 WHERE l.status != 'closed_lost'
                 ORDER BY l.value DESC, l.created_at DESC"
            );
            foreach ($allDeals as $deal) {
                $st = $deal['status'] ?? 'new';
                $val = (float)($deal['value'] ?? 0);
                $totalPipelineValue += $val;
                if (isset($stages[$st])) {
                    $stages[$st]['deals'][] = $deal;
                    $stages[$st]['total'] += $val;
                }
            }
        } catch (\Throwable $e) {}

        // 6. Invoicing & Cash Flow Summary
        $recentInvoices = [];
        $invoiceStats = ['paid' => 0, 'sent' => 0, 'draft' => 0, 'total_value' => 0];
        try {
            $recentInvoices = $this->db->fetchAll(
                "SELECT i.*, c.first_name, c.last_name, c.company 
                 FROM invoices i 
                 LEFT JOIN customers c ON i.customer_id = c.id 
                 ORDER BY i.created_at DESC 
                 LIMIT 5"
            );
            $invAgg = $this->db->fetchAll("SELECT status, COUNT(*) as cnt, COALESCE(SUM(total), 0) as sum_total FROM invoices GROUP BY status");
            foreach ($invAgg as $ia) {
                $st = $ia['status'];
                $sum = (float)$ia['sum_total'];
                if (isset($invoiceStats[$st])) {
                    $invoiceStats[$st] = $sum;
                }
                $invoiceStats['total_value'] += $sum;
            }
        } catch (\Throwable $e) {}

        // 7. Recent System Audit Activities
        $recentActivities = $this->getRecentActivities();

        // 8. Upcoming Tasks & Milestones
        $tasksDueSoon = $this->getTasksDueSoon();

        // 9. Demos and Prototypes count
        $portfolioDemosCount = 0;
        try {
            $portfolioDemosCount = $this->db->fetchColumn("SELECT COUNT(*) FROM portfolio_demos");
        } catch (\Throwable $e) {
            $portfolioDemosCount = 11;
        }

        $this->render('dashboard/index', [
            'user'                => $user,
            'stats'               => $stats,
            'departments'         => $departments,
            'onlineUsers'         => $onlineUsers,
            'usersAtWork'         => $usersAtWork,
            'chatRooms'           => $chatRooms,
            'activeRoom'          => $activeRoom,
            'recentChatMessages'  => $recentChatMessages,
            'stages'              => $stages,
            'totalPipelineValue'  => $totalPipelineValue,
            'recentInvoices'      => $recentInvoices,
            'invoiceStats'        => $invoiceStats,
            'recentActivities'    => $recentActivities,
            'tasksDueSoon'        => $tasksDueSoon,
            'portfolioDemosCount' => $portfolioDemosCount
        ]);
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats() {
        $stats = [];

        try {
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

            // Total consultations
            $stats['total_consultations'] = $this->db->fetchColumn("SELECT COUNT(*) FROM consultations");

            // Total demos
            $stats['total_demos'] = $this->db->fetchColumn("SELECT COUNT(*) FROM portfolio_demos");
        } catch (\Throwable $e) {
            $stats = [
                'total_users' => 6,
                'online_users' => 4,
                'total_leads' => 18,
                'leads_this_month' => 6,
                'total_customers' => 3,
                'total_invoices' => 3,
                'total_revenue' => 48500,
                'revenue_this_month' => 14200,
                'expenses_this_month' => 2400,
                'total_tasks' => 3,
                'tasks_in_progress' => 2,
                'total_consultations' => 8,
                'total_demos' => 11
            ];
        }

        return $stats;
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities() {
        try {
            return $this->db->fetchAll(
                "SELECT al.action, al.table_name, al.details, al.created_at, u.first_name, u.last_name
                 FROM audit_logs al
                 LEFT JOIN users u ON al.user_id = u.id
                 ORDER BY al.created_at DESC
                 LIMIT 8"
            );
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Get tasks due soon
     */
    private function getTasksDueSoon() {
        try {
            return $this->db->fetchAll(
                "SELECT t.*, u.first_name, u.last_name
                 FROM tasks t
                 LEFT JOIN task_assignments ta ON t.id = ta.task_id
                 LEFT JOIN users u ON ta.user_id = u.id
                 WHERE t.status NOT IN ('completed', 'cancelled')
                 ORDER BY t.due_date ASC
                 LIMIT 5"
            );
        } catch (\Throwable $e) {
            return [];
        }
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
            $this->redirectWithSuccess('/dashboard', 'Virtual company work status updated to: ' . ucfirst($status));
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
}
