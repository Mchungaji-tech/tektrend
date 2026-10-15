<?php
/**
 * Application Bootstrap
 * Initializes the application, loads dependencies, registers routes
 */

class App {
    private $router;
    private $middleware;

    public function __construct() {
        $this->router = new Router();
        $this->middleware = new Middleware();
    }

    /**
     * Bootstrap the application
     */
    public function bootstrap() {
        // Run middleware
        $this->middleware->handle();

        // Ensure role/portal schema exists (idempotent runtime migration)
        $this->ensureRoleSchema();

        // Clean expired sessions (random chance to avoid overhead)
        if (rand(1, 100) <= 5) {
            $this->middleware->cleanExpiredSessions();
        }

        // Register all routes
        $this->registerRoutes();

        // Dispatch request
        $this->router->dispatch();
    }

    /**
     * Register all application routes
     */
    private function registerRoutes() {
        // Landing page & Public Live Demos
        $this->router->get('/', 'HomeController@index');
        $this->router->get('/live-demos', 'PortfolioController@showcase');
        $this->router->get('/live-demo', 'PortfolioController@showcase');
        $this->router->get('/live_demo', 'PortfolioController@showcase');
        $this->router->post('/live-demos/request', 'PortfolioController@requestSystem');
        $this->router->post('/demos/request-system', 'PortfolioController@requestSystem');
        $this->router->post('/book-consultation', 'HomeController@bookConsultation');
        $this->router->post('/place-bid', 'HomeController@placeBid');
        $this->router->post('/invest', 'HomeController@submitInvestment');
        $this->router->post('/api/ai/chat', 'AiChatController@chat');
        $this->router->post('/api/ai-chat', 'AiChatController@chat');

        // SEO discovery endpoints
        $this->router->get('/sitemap.xml', 'HomeController@sitemap');
        $this->router->get('/robots.txt', 'HomeController@robots');

        // Blog / Insights (public SEO content + admin CMS)
        $this->router->get('/blog', 'BlogController@index');
        $this->router->get('/blog-manage', 'BlogController@manage', ['staff']);
        $this->router->get('/blog-manage/create', 'BlogController@create', ['staff']);
        $this->router->post('/blog-manage', 'BlogController@store', ['staff']);
        $this->router->get('/blog-manage/{id}/edit', 'BlogController@edit', ['staff']);
        $this->router->post('/blog-manage/{id}', 'BlogController@update', ['staff']);
        $this->router->get('/blog-manage/{id}/delete', 'BlogController@destroy', ['staff']);
        $this->router->get('/blog/{slug}', 'BlogController@show');

        // Authentication routes
        $this->router->get('/login', 'AuthController@login', ['guest']);
        $this->router->post('/login', 'AuthController@authenticate');
        $this->router->get('/register', 'AuthController@register', ['admin']);
        $this->router->post('/register', 'AuthController@store', ['admin']);
        $this->router->get('/forgot-password', 'AuthController@forgotPassword', ['guest']);
        $this->router->post('/forgot-password', 'AuthController@sendReset');
        $this->router->get('/reset-password', 'AuthController@resetPassword', ['guest']);
        $this->router->post('/reset-password', 'AuthController@updatePassword');
        $this->router->get('/logout', 'AuthController@logout', ['staff']);

        // Google OAuth 2.0 (Sign in with Google)
        $this->router->get('/auth/google', 'GoogleAuthController@redirect', ['guest']);
        $this->router->get('/auth/google/callback', 'GoogleAuthController@callback', ['guest']);

        // GitHub OAuth 2.0 (Sign in with GitHub)
        $this->router->get('/auth/github', 'GithubAuthController@redirect', ['guest']);
        $this->router->get('/auth/github/callback', 'GithubAuthController@callback', ['guest']);

        // Dashboard
        $this->router->get('/dashboard', 'DashboardController@index', ['staff']);
        $this->router->get('/dashboard/stats', 'DashboardController@stats', ['staff']);
        $this->router->get('/dashboard/online-users', 'DashboardController@onlineUsers', ['staff']);
        $this->router->get('/dashboard/work-status', 'DashboardController@workStatus', ['staff']);

        // Consultations & Zoom Booking
        $this->router->get('/consultations', 'ConsultationController@index', ['staff']);
        $this->router->get('/consultations/{id}', 'ConsultationController@show', ['staff']);
        $this->router->post('/consultations/{id}/status', 'ConsultationController@updateStatus', ['staff']);
        $this->router->get('/consultations/{id}/delete', 'ConsultationController@delete', ['staff']);

        // Investor Relations & Capital
        $this->router->get('/investments', 'InvestmentController@index', ['staff']);
        $this->router->get('/investments/{id}', 'InvestmentController@show', ['staff']);
        $this->router->post('/investments/{id}/status', 'InvestmentController@updateStatus', ['staff']);
        $this->router->get('/investments/{id}/delete', 'InvestmentController@delete', ['staff']);

        // Marketplace & Awwwards Bidding
        $this->router->get('/marketplace', 'MarketplaceController@index', ['staff']);
        $this->router->get('/marketplace/create', 'MarketplaceController@create', ['staff']);
        $this->router->post('/marketplace', 'MarketplaceController@store', ['staff']);
        $this->router->get('/marketplace/bids', 'MarketplaceController@bids', ['staff']);
        $this->router->post('/marketplace/bids/{id}/status', 'MarketplaceController@updateBidStatus', ['staff']);
        $this->router->get('/marketplace/{id}/edit', 'MarketplaceController@edit', ['staff']);
        $this->router->post('/marketplace/{id}', 'MarketplaceController@update', ['staff']);
        $this->router->get('/marketplace/{id}/delete', 'MarketplaceController@delete', ['staff']);

        // Contracts & Scope Agreements
        $this->router->get('/contracts', 'ContractController@index', ['staff']);
        $this->router->get('/contracts/create', 'ContractController@create', ['staff']);
        $this->router->post('/contracts', 'ContractController@store', ['staff']);
        $this->router->get('/contracts/{id}', 'ContractController@show', ['staff']);
        $this->router->get('/contracts/{id}/edit', 'ContractController@edit', ['staff']);
        $this->router->post('/contracts/{id}', 'ContractController@update', ['staff']);
        $this->router->get('/contracts/{id}/print', 'ContractController@printContract', ['staff']);
        $this->router->get('/contracts/{id}/delete', 'ContractController@delete', ['staff']);

        // Leads / CRM
        $this->router->get('/leads', 'LeadController@index', ['staff']);
        $this->router->get('/leads/pipeline', 'LeadController@pipeline', ['staff']);
        $this->router->post('/leads/{id}/stage', 'LeadController@updateStage', ['staff']);
        $this->router->get('/leads/create', 'LeadController@create', ['staff']);
        $this->router->post('/leads', 'LeadController@store', ['staff']);
        $this->router->get('/leads/{id}', 'LeadController@show', ['staff']);
        $this->router->get('/leads/{id}/edit', 'LeadController@edit', ['staff']);
        $this->router->post('/leads/{id}', 'LeadController@update', ['staff']);
        $this->router->get('/leads/{id}/delete', 'LeadController@delete', ['staff']);
        $this->router->post('/leads/{id}/activity', 'LeadController@addActivity', ['staff']);
        $this->router->get('/leads/followup', 'LeadController@followup', ['staff']);

        // Customers
        $this->router->get('/customers', 'CustomerController@index', ['staff']);
        $this->router->get('/customers/create', 'CustomerController@create', ['staff']);
        $this->router->post('/customers', 'CustomerController@store', ['staff']);
        $this->router->get('/customers/{id}', 'CustomerController@show', ['staff']);
        $this->router->get('/customers/{id}/edit', 'CustomerController@edit', ['staff']);
        $this->router->post('/customers/{id}', 'CustomerController@update', ['staff']);
        $this->router->get('/customers/{id}/delete', 'CustomerController@delete', ['staff']);

        // Invoices
        $this->router->get('/invoices', 'InvoiceController@index', ['staff']);
        $this->router->get('/invoices/create', 'InvoiceController@create', ['staff']);
        $this->router->post('/invoices', 'InvoiceController@store', ['staff']);
        $this->router->get('/invoices/{id}', 'InvoiceController@show', ['staff']);
        $this->router->get('/invoices/{id}/edit', 'InvoiceController@edit', ['staff']);
        $this->router->post('/invoices/{id}', 'InvoiceController@update', ['staff']);
        $this->router->get('/invoices/{id}/delete', 'InvoiceController@delete', ['staff']);
        $this->router->get('/invoices/{id}/pdf', 'InvoiceController@pdf', ['staff']);

        // Finances
        $this->router->get('/finances', 'FinanceController@index', ['staff']);
        $this->router->get('/finances/create', 'FinanceController@create', ['staff']);
        $this->router->post('/finances', 'FinanceController@store', ['staff']);
        $this->router->get('/finances/{id}/edit', 'FinanceController@edit', ['staff']);
        $this->router->post('/finances/{id}', 'FinanceController@update', ['staff']);
        $this->router->get('/finances/{id}/delete', 'FinanceController@delete', ['staff']);

        // Budgets
        $this->router->get('/budgets', 'FinanceController@budgets', ['staff']);
        $this->router->get('/budgets/create', 'FinanceController@createBudget', ['staff']);
        $this->router->post('/budgets', 'FinanceController@storeBudget', ['staff']);
        $this->router->get('/budgets/{id}/edit', 'FinanceController@editBudget', ['staff']);
        $this->router->post('/budgets/{id}', 'FinanceController@updateBudget', ['staff']);

        // Taxes
        $this->router->get('/taxes', 'FinanceController@taxes', ['staff']);
        $this->router->get('/taxes/create', 'FinanceController@createTax', ['staff']);
        $this->router->post('/taxes', 'FinanceController@storeTax', ['staff']);

        // Email Marketing
        $this->router->get('/emails', 'EmailController@index', ['staff']);
        $this->router->get('/emails/create', 'EmailController@create', ['staff']);
        $this->router->post('/emails', 'EmailController@store', ['staff']);
        $this->router->get('/emails/{id}', 'EmailController@show', ['staff']);
        $this->router->get('/emails/{id}/send', 'EmailController@send', ['staff']);
        $this->router->get('/emails/subscribers', 'EmailController@subscribers', ['staff']);
        $this->router->post('/emails/subscribers', 'EmailController@addSubscriber', ['staff']);

        // Events & Calendar
        $this->router->get('/events', 'EventController@index', ['staff']);
        $this->router->get('/events/calendar', 'EventController@calendar', ['staff']);
        $this->router->get('/events/create', 'EventController@create', ['staff']);
        $this->router->post('/events', 'EventController@store', ['staff']);
        $this->router->get('/events/{id}', 'EventController@show', ['staff']);
        $this->router->get('/events/{id}/edit', 'EventController@edit', ['staff']);
        $this->router->post('/events/{id}', 'EventController@update', ['staff']);
        $this->router->get('/events/{id}/delete', 'EventController@delete', ['staff']);

        // Timetable
        $this->router->get('/timetable', 'EventController@timetable', ['staff']);
        $this->router->post('/timetable', 'EventController@storeTimetable', ['staff']);

        // Tasks
        $this->router->get('/tasks', 'TaskController@index', ['staff']);
        $this->router->get('/tasks/create', 'TaskController@create', ['staff']);
        $this->router->post('/tasks', 'TaskController@store', ['staff']);
        $this->router->get('/tasks/{id}', 'TaskController@show', ['staff']);
        $this->router->get('/tasks/{id}/edit', 'TaskController@edit', ['staff']);
        $this->router->post('/tasks/{id}', 'TaskController@update', ['staff']);
        $this->router->get('/tasks/{id}/delete', 'TaskController@delete', ['staff']);
        $this->router->post('/tasks/{id}/comment', 'TaskController@addComment', ['staff']);

        // Chat / Virtual Office
        $this->router->get('/chat', 'ChatController@index', ['staff']);
        $this->router->get('/chat/room/{id}', 'ChatController@room', ['staff']);
        $this->router->post('/chat/room/{id}/message', 'ChatController@sendMessage', ['staff']);
        $this->router->post('/chat/room/{id}/send', 'ChatController@sendMessage', ['staff']);
        $this->router->get('/chat/messages/{id}', 'ChatController@getMessages', ['staff']);
        $this->router->get('/chat/online', 'ChatController@onlineUsers', ['staff']);

        // Meetings / Teleconferencing
        $this->router->get('/meetings', 'ChatController@meetings', ['staff']);
        $this->router->get('/meetings/create', 'ChatController@createMeeting', ['staff']);
        $this->router->post('/meetings', 'ChatController@storeMeeting', ['staff']);
        $this->router->get('/meetings/{id}', 'ChatController@joinMeeting', ['staff']);
        $this->router->get('/meetings/{id}/start', 'ChatController@startMeeting', ['staff']);
        $this->router->get('/meetings/{id}/end', 'ChatController@endMeeting', ['staff']);

        // Employees
        $this->router->get('/employees', 'EmployeeController@index', ['staff']);
        $this->router->get('/employees/create', 'EmployeeController@create', ['staff']);
        $this->router->post('/employees', 'EmployeeController@store', ['staff']);
        $this->router->get('/employees/{id}', 'EmployeeController@show', ['staff']);
        $this->router->get('/employees/{id}/edit', 'EmployeeController@edit', ['staff']);
        $this->router->post('/employees/{id}', 'EmployeeController@update', ['staff']);
        $this->router->get('/employees/{id}/delete', 'EmployeeController@delete', ['staff']);

        // Departments
        $this->router->get('/departments', 'DepartmentController@index', ['staff']);
        $this->router->get('/departments/create', 'DepartmentController@create', ['staff']);
        $this->router->post('/departments', 'DepartmentController@store', ['staff']);
        $this->router->get('/departments/{id}/edit', 'DepartmentController@edit', ['staff']);
        $this->router->post('/departments/{id}', 'DepartmentController@update', ['staff']);
        $this->router->get('/departments/{id}/delete', 'DepartmentController@delete', ['staff']);

        // Demos & Hosting Projects (Public showcase for guests, management panel for admins)
        $this->router->get('/demos', 'PortfolioController@index');
        $this->router->get('/demos/create', 'PortfolioController@create', ['staff']);
        $this->router->post('/demos/store', 'PortfolioController@store', ['staff']);
        $this->router->get('/demos/{id}/edit', 'PortfolioController@edit', ['staff']);
        $this->router->post('/demos/{id}/update', 'PortfolioController@update', ['staff']);
        $this->router->post('/demos/{id}/delete', 'PortfolioController@destroy', ['staff']);
        $this->router->get('/demos/{id}/delete', 'PortfolioController@destroy', ['staff']);

        // Content Management
        $this->router->get('/content', 'ContentController@index', ['staff']);
        $this->router->post('/content/bulk-update', 'ContentController@bulkUpdate', ['staff']);
        $this->router->get('/content/{key}/edit', 'ContentController@edit', ['staff']);
        $this->router->post('/content/{key}', 'ContentController@update', ['staff']);

        // Settings
        $this->router->get('/settings', 'SettingController@index', ['staff']);
        $this->router->post('/settings', 'SettingController@update', ['staff']);

        // Users (Admin)
        $this->router->get('/users', 'UserController@index', ['staff']);
        $this->router->get('/users/create', 'UserController@create', ['staff']);
        $this->router->post('/users', 'UserController@store', ['staff']);
        $this->router->get('/users/{id}/edit', 'UserController@edit', ['staff']);
        $this->router->post('/users/{id}', 'UserController@update', ['staff']);
        $this->router->get('/users/{id}/delete', 'UserController@delete', ['staff']);

        // Work status update
        $this->router->post('/work-status', 'DashboardController@updateWorkStatus', ['staff']);

        // AJAX endpoints
        $this->router->get('/api/online-users', 'DashboardController@apiOnlineUsers', ['staff']);
        $this->router->get('/api/dashboard-stats', 'DashboardController@apiStats', ['staff']);
        $this->router->get('/api/chat/messages/{id}', 'ChatController@apiMessages', ['staff']);

        // ---- Staff console home (alias) ----
        $this->router->get('/admin', 'DashboardController@index', ['staff']);
        $this->router->get('/admin/register', 'AuthController@register', ['admin']);
        $this->router->post('/admin/register', 'AuthController@store', ['admin']);

        // ---- Admin: Partner applications & approval ----
        $this->router->get('/admin/partners', 'PartnerAdminController@index', ['staff']);
        $this->router->post('/admin/partners/{id}/approve', 'PartnerAdminController@approve', ['staff']);
        $this->router->post('/admin/partners/{id}/reject', 'PartnerAdminController@reject', ['staff']);

        // ---- Public: self-service registration ----
        $this->router->get('/signup', 'AuthController@signup', ['guest']);
        $this->router->post('/signup', 'AuthController@createClient');
        $this->router->get('/partner-apply', 'PartnerController@apply', ['guest']);
        $this->router->post('/partner-apply', 'PartnerController@storeApplication');

        // ---- Client portal (self-service) ----
        $this->router->get('/portal', 'ClientController@dashboard', ['client']);
        $this->router->get('/portal/invoices', 'ClientController@invoices', ['client']);
        $this->router->get('/portal/invoices/{id}', 'ClientController@invoice', ['client']);
        $this->router->get('/portal/projects', 'ClientController@projects', ['client']);
        $this->router->get('/portal/profile', 'ClientController@profile', ['client']);
        $this->router->post('/portal/profile', 'ClientController@updateProfile', ['client']);
        $this->router->get('/portal/logout', 'AuthController@logout', ['client']);

        // ---- Partner portal ----
        $this->router->get('/partners/pending', 'PartnerController@pending', ['partner']);
        $this->router->get('/partners', 'PartnerController@dashboard', ['approved_partner']);
        $this->router->get('/partners/referrals', 'PartnerController@referrals', ['approved_partner']);
        $this->router->get('/partners/payouts', 'PartnerController@payouts', ['approved_partner']);
        $this->router->get('/partners/profile', 'PartnerController@profile', ['partner']);
        $this->router->post('/partners/profile', 'PartnerController@updateProfile', ['partner']);
        $this->router->get('/partners/logout', 'AuthController@logout', ['partner']);
    }

    /**
     * Idempotent runtime migration for role/portal schema (client & partner areas).
     */
    private function ensureRoleSchema() {
        try {
            $db = Database::getInstance();
        } catch (\Throwable $e) {
            return; // No DB - skip silently
        }

        try {
            // Widen users.role to include client + partner (only if not already)
            $roleCol = $db->fetchAll("SHOW COLUMNS FROM `users` LIKE 'role'");
            $roleDef = $roleCol[0]['Type'] ?? '';
            if ($roleDef && stripos($roleDef, 'client') === false) {
                $db->execute("ALTER TABLE `users` MODIFY `role` ENUM('admin','manager','employee','accountant','sales','support','client','partner') DEFAULT 'employee'");
            }

            // Widen users.status to include pending
            $statusCol = $db->fetchAll("SHOW COLUMNS FROM `users` LIKE 'status'");
            $statusDef = $statusCol[0]['Type'] ?? '';
            if ($statusDef && stripos($statusDef, 'pending') === false) {
                $db->execute("ALTER TABLE `users` MODIFY `status` ENUM('active','inactive','suspended','pending') DEFAULT 'active'");
            }

            // Link customers to a login user
            $uidCol = $db->fetchAll("SHOW COLUMNS FROM `customers` LIKE 'user_id'");
            if (empty($uidCol)) {
                $db->execute("ALTER TABLE `customers` ADD COLUMN `user_id` INT UNSIGNED DEFAULT NULL AFTER `id`");
                $db->execute("ALTER TABLE `customers` ADD KEY `user_id` (`user_id`)");
            }

            // Partners table
            $db->execute("CREATE TABLE IF NOT EXISTS `partners` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` INT UNSIGNED NOT NULL,
                `company_name` VARCHAR(150) DEFAULT NULL,
                `website` VARCHAR(255) DEFAULT NULL,
                `about` TEXT DEFAULT NULL,
                `status` ENUM('pending','approved','rejected') DEFAULT 'pending',
                `commission_rate` DECIMAL(5,2) DEFAULT 10.00,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `user_id` (`user_id`),
                KEY `status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } catch (\Throwable $e) {
            error_log('ensureRoleSchema: ' . $e->getMessage());
        }
    }
}
