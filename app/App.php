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
        // Landing page (PHP version of index.html)
        $this->router->get('/', 'HomeController@index', ['guest']);
        $this->router->get('/live-demos', 'PortfolioController@showcase');
        $this->router->post('/book-consultation', 'HomeController@bookConsultation');
        $this->router->post('/place-bid', 'HomeController@placeBid');
        $this->router->post('/api/ai-chat', 'HomeController@apiAiChat');

        // Authentication routes
        $this->router->get('/login', 'AuthController@login', ['guest']);
        $this->router->post('/login', 'AuthController@authenticate');
        $this->router->get('/register', 'AuthController@register', ['admin']);
        $this->router->post('/register', 'AuthController@store', ['admin']);
        $this->router->get('/forgot-password', 'AuthController@forgotPassword', ['guest']);
        $this->router->post('/forgot-password', 'AuthController@sendReset');
        $this->router->get('/reset-password', 'AuthController@resetPassword', ['guest']);
        $this->router->post('/reset-password', 'AuthController@updatePassword');
        $this->router->get('/logout', 'AuthController@logout', ['auth']);

        // Dashboard
        $this->router->get('/dashboard', 'DashboardController@index', ['auth']);
        $this->router->get('/dashboard/stats', 'DashboardController@stats', ['auth']);
        $this->router->get('/dashboard/online-users', 'DashboardController@onlineUsers', ['auth']);
        $this->router->get('/dashboard/work-status', 'DashboardController@workStatus', ['auth']);

        // Consultations & Zoom Booking
        $this->router->get('/consultations', 'ConsultationController@index', ['auth']);
        $this->router->get('/consultations/{id}', 'ConsultationController@show', ['auth']);
        $this->router->post('/consultations/{id}/status', 'ConsultationController@updateStatus', ['auth']);
        $this->router->get('/consultations/{id}/delete', 'ConsultationController@delete', ['auth']);

        // Marketplace & Awwwards Bidding
        $this->router->get('/marketplace', 'MarketplaceController@index', ['auth']);
        $this->router->get('/marketplace/create', 'MarketplaceController@create', ['auth']);
        $this->router->post('/marketplace', 'MarketplaceController@store', ['auth']);
        $this->router->get('/marketplace/bids', 'MarketplaceController@bids', ['auth']);
        $this->router->post('/marketplace/bids/{id}/status', 'MarketplaceController@updateBidStatus', ['auth']);
        $this->router->get('/marketplace/{id}/edit', 'MarketplaceController@edit', ['auth']);
        $this->router->post('/marketplace/{id}', 'MarketplaceController@update', ['auth']);
        $this->router->get('/marketplace/{id}/delete', 'MarketplaceController@delete', ['auth']);

        // Contracts & Scope Agreements
        $this->router->get('/contracts', 'ContractController@index', ['auth']);
        $this->router->get('/contracts/create', 'ContractController@create', ['auth']);
        $this->router->post('/contracts', 'ContractController@store', ['auth']);
        $this->router->get('/contracts/{id}', 'ContractController@show', ['auth']);
        $this->router->get('/contracts/{id}/edit', 'ContractController@edit', ['auth']);
        $this->router->post('/contracts/{id}', 'ContractController@update', ['auth']);
        $this->router->get('/contracts/{id}/print', 'ContractController@printContract', ['auth']);
        $this->router->get('/contracts/{id}/delete', 'ContractController@delete', ['auth']);

        // Leads / CRM
        $this->router->get('/leads', 'LeadController@index', ['auth']);
        $this->router->get('/leads/pipeline', 'LeadController@pipeline', ['auth']);
        $this->router->post('/leads/{id}/stage', 'LeadController@updateStage', ['auth']);
        $this->router->get('/leads/create', 'LeadController@create', ['auth']);
        $this->router->post('/leads', 'LeadController@store', ['auth']);
        $this->router->get('/leads/{id}', 'LeadController@show', ['auth']);
        $this->router->get('/leads/{id}/edit', 'LeadController@edit', ['auth']);
        $this->router->post('/leads/{id}', 'LeadController@update', ['auth']);
        $this->router->get('/leads/{id}/delete', 'LeadController@delete', ['auth']);
        $this->router->post('/leads/{id}/activity', 'LeadController@addActivity', ['auth']);
        $this->router->get('/leads/followup', 'LeadController@followup', ['auth']);

        // Customers
        $this->router->get('/customers', 'CustomerController@index', ['auth']);
        $this->router->get('/customers/create', 'CustomerController@create', ['auth']);
        $this->router->post('/customers', 'CustomerController@store', ['auth']);
        $this->router->get('/customers/{id}', 'CustomerController@show', ['auth']);
        $this->router->get('/customers/{id}/edit', 'CustomerController@edit', ['auth']);
        $this->router->post('/customers/{id}', 'CustomerController@update', ['auth']);
        $this->router->get('/customers/{id}/delete', 'CustomerController@delete', ['auth']);

        // Invoices
        $this->router->get('/invoices', 'InvoiceController@index', ['auth']);
        $this->router->get('/invoices/create', 'InvoiceController@create', ['auth']);
        $this->router->post('/invoices', 'InvoiceController@store', ['auth']);
        $this->router->get('/invoices/{id}', 'InvoiceController@show', ['auth']);
        $this->router->get('/invoices/{id}/edit', 'InvoiceController@edit', ['auth']);
        $this->router->post('/invoices/{id}', 'InvoiceController@update', ['auth']);
        $this->router->get('/invoices/{id}/delete', 'InvoiceController@delete', ['auth']);
        $this->router->get('/invoices/{id}/pdf', 'InvoiceController@pdf', ['auth']);

        // Finances
        $this->router->get('/finances', 'FinanceController@index', ['auth']);
        $this->router->get('/finances/create', 'FinanceController@create', ['auth']);
        $this->router->post('/finances', 'FinanceController@store', ['auth']);
        $this->router->get('/finances/{id}/edit', 'FinanceController@edit', ['auth']);
        $this->router->post('/finances/{id}', 'FinanceController@update', ['auth']);
        $this->router->get('/finances/{id}/delete', 'FinanceController@delete', ['auth']);

        // Budgets
        $this->router->get('/budgets', 'FinanceController@budgets', ['auth']);
        $this->router->get('/budgets/create', 'FinanceController@createBudget', ['auth']);
        $this->router->post('/budgets', 'FinanceController@storeBudget', ['auth']);
        $this->router->get('/budgets/{id}/edit', 'FinanceController@editBudget', ['auth']);
        $this->router->post('/budgets/{id}', 'FinanceController@updateBudget', ['auth']);

        // Taxes
        $this->router->get('/taxes', 'FinanceController@taxes', ['auth']);
        $this->router->get('/taxes/create', 'FinanceController@createTax', ['auth']);
        $this->router->post('/taxes', 'FinanceController@storeTax', ['auth']);

        // Email Marketing
        $this->router->get('/emails', 'EmailController@index', ['auth']);
        $this->router->get('/emails/create', 'EmailController@create', ['auth']);
        $this->router->post('/emails', 'EmailController@store', ['auth']);
        $this->router->get('/emails/{id}', 'EmailController@show', ['auth']);
        $this->router->get('/emails/{id}/send', 'EmailController@send', ['auth']);
        $this->router->get('/emails/subscribers', 'EmailController@subscribers', ['auth']);
        $this->router->post('/emails/subscribers', 'EmailController@addSubscriber', ['auth']);

        // Events & Calendar
        $this->router->get('/events', 'EventController@index', ['auth']);
        $this->router->get('/events/calendar', 'EventController@calendar', ['auth']);
        $this->router->get('/events/create', 'EventController@create', ['auth']);
        $this->router->post('/events', 'EventController@store', ['auth']);
        $this->router->get('/events/{id}', 'EventController@show', ['auth']);
        $this->router->get('/events/{id}/edit', 'EventController@edit', ['auth']);
        $this->router->post('/events/{id}', 'EventController@update', ['auth']);
        $this->router->get('/events/{id}/delete', 'EventController@delete', ['auth']);

        // Timetable
        $this->router->get('/timetable', 'EventController@timetable', ['auth']);
        $this->router->post('/timetable', 'EventController@storeTimetable', ['auth']);

        // Tasks
        $this->router->get('/tasks', 'TaskController@index', ['auth']);
        $this->router->get('/tasks/create', 'TaskController@create', ['auth']);
        $this->router->post('/tasks', 'TaskController@store', ['auth']);
        $this->router->get('/tasks/{id}', 'TaskController@show', ['auth']);
        $this->router->get('/tasks/{id}/edit', 'TaskController@edit', ['auth']);
        $this->router->post('/tasks/{id}', 'TaskController@update', ['auth']);
        $this->router->get('/tasks/{id}/delete', 'TaskController@delete', ['auth']);
        $this->router->post('/tasks/{id}/comment', 'TaskController@addComment', ['auth']);

        // Chat / Virtual Office
        $this->router->get('/chat', 'ChatController@index', ['auth']);
        $this->router->get('/chat/room/{id}', 'ChatController@room', ['auth']);
        $this->router->post('/chat/room/{id}/message', 'ChatController@sendMessage', ['auth']);
        $this->router->get('/chat/messages/{id}', 'ChatController@getMessages', ['auth']);
        $this->router->get('/chat/online', 'ChatController@onlineUsers', ['auth']);

        // Meetings / Teleconferencing
        $this->router->get('/meetings', 'ChatController@meetings', ['auth']);
        $this->router->get('/meetings/create', 'ChatController@createMeeting', ['auth']);
        $this->router->post('/meetings', 'ChatController@storeMeeting', ['auth']);
        $this->router->get('/meetings/{id}', 'ChatController@joinMeeting', ['auth']);
        $this->router->get('/meetings/{id}/start', 'ChatController@startMeeting', ['auth']);
        $this->router->get('/meetings/{id}/end', 'ChatController@endMeeting', ['auth']);

        // Employees
        $this->router->get('/employees', 'EmployeeController@index', ['auth']);
        $this->router->get('/employees/create', 'EmployeeController@create', ['auth']);
        $this->router->post('/employees', 'EmployeeController@store', ['auth']);
        $this->router->get('/employees/{id}', 'EmployeeController@show', ['auth']);
        $this->router->get('/employees/{id}/edit', 'EmployeeController@edit', ['auth']);
        $this->router->post('/employees/{id}', 'EmployeeController@update', ['auth']);
        $this->router->get('/employees/{id}/delete', 'EmployeeController@delete', ['auth']);

        // Departments
        $this->router->get('/departments', 'DepartmentController@index', ['auth']);
        $this->router->get('/departments/create', 'DepartmentController@create', ['auth']);
        $this->router->post('/departments', 'DepartmentController@store', ['auth']);
        $this->router->get('/departments/{id}/edit', 'DepartmentController@edit', ['auth']);
        $this->router->post('/departments/{id}', 'DepartmentController@update', ['auth']);
        $this->router->get('/departments/{id}/delete', 'DepartmentController@delete', ['auth']);

        // Demos & Hosting Projects
        $this->router->get('/demos', 'PortfolioController@index', ['auth']);
        $this->router->get('/demos/create', 'PortfolioController@create', ['auth']);
        $this->router->post('/demos/store', 'PortfolioController@store', ['auth']);
        $this->router->get('/demos/{id}/edit', 'PortfolioController@edit', ['auth']);
        $this->router->post('/demos/{id}/update', 'PortfolioController@update', ['auth']);
        $this->router->post('/demos/{id}/delete', 'PortfolioController@destroy', ['auth']);
        $this->router->get('/demos/{id}/delete', 'PortfolioController@destroy', ['auth']);

        // Content Management
        $this->router->get('/content', 'ContentController@index', ['auth']);
        $this->router->get('/content/{key}/edit', 'ContentController@edit', ['auth']);
        $this->router->post('/content/{key}', 'ContentController@update', ['auth']);

        // Settings
        $this->router->get('/settings', 'SettingController@index', ['auth']);
        $this->router->post('/settings', 'SettingController@update', ['auth']);

        // Users (Admin)
        $this->router->get('/users', 'UserController@index', ['auth']);
        $this->router->get('/users/create', 'UserController@create', ['auth']);
        $this->router->post('/users', 'UserController@store', ['auth']);
        $this->router->get('/users/{id}/edit', 'UserController@edit', ['auth']);
        $this->router->post('/users/{id}', 'UserController@update', ['auth']);
        $this->router->get('/users/{id}/delete', 'UserController@delete', ['auth']);

        // Work status update
        $this->router->post('/work-status', 'DashboardController@updateWorkStatus', ['auth']);

        // AJAX endpoints
        $this->router->get('/api/online-users', 'DashboardController@apiOnlineUsers', ['auth']);
        $this->router->get('/api/dashboard-stats', 'DashboardController@apiStats', ['auth']);
        $this->router->get('/api/chat/messages/{id}', 'ChatController@apiMessages', ['auth']);
    }
}
