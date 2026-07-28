# Tek Trend Virtual Company Management System

A comprehensive PHP-based virtual company management platform with CRM, finance, HR, marketing, and communication tools.

## Features

- **Authentication & Security**: Login, registration, password reset, rate limiting, CSRF protection, audit logging
- **Dashboard**: Real-time stats, online user tracking, work status, upcoming events, tasks
- **CRM/Leads**: Lead management, follow-ups, activities, pipeline tracking
- **Finances**: Transactions, budgets, taxes, financial reporting
- **Customers & Invoices**: Customer management, invoicing, payments, PDF generation
- **Email Marketing**: Campaigns, subscribers, send tracking
- **Events & Calendar**: Event scheduling, calendar view, timetable
- **Tasks**: Task management, assignments, comments, progress tracking
- **Virtual Office**: Chat rooms, real-time messaging, video meetings/teleconferencing
- **Employees & Departments**: Employee management, department structure
- **Demos**: Demo card management with categories and tech tags
- **Content Management**: Site content editing with history tracking
- **Online User Tracking**: See who's online and at work

## Requirements

- PHP 7.4+ (PHP 8+ recommended)
- MySQL 5.7+ / MariaDB 10.3+
- Apache with mod_rewrite (or nginx)
- HTTPS recommended for production

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Mchungaji-tech/tektrend.git
   cd tektrend
   ```

2. **Configure environment**
   ```bash
   cp .env.example .env
   # Edit .env with your database and app settings
   ```

3. **Create database**
   ```bash
   mysql -u root -p
   CREATE DATABASE tektrend_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

4. **Import schema and seed data**
   ```bash
   mysql -u root -p tektrend_db < database/schema.sql
   mysql -u root -p tektrend_db < database/seed.sql
   ```

5. **Configure web server**
   - Point document root to `public/`
   - Or use the root `.htaccess` to redirect to `public/`

6. **Access the application**
   - Visit `https://your-domain.com/`
   - Login with: `admin@tektrend.com` / password from seed data

## Default Credentials

- **Email**: `admin@tektrend.com`
- **Password**: The seed data uses a bcrypt hash. After importing, reset the password:
  ```sql
  UPDATE users SET password = '$2y$12$LQvKT3hYJ8sN7pM2rQ1wEeQ8sN7pM2rQ1wEeQ8sN7pM2rQ1wEeQ8sN' WHERE email = 'admin@tektrend.com';
  ```
  Or use the password reset feature.

## Project Structure

```
tektrend/
├── public/              # Public entry point
│   ├── index.php        # Main application entry
│   ├── .htaccess        # URL rewriting & security
│   └── assets/          # CSS, JS, images
├── app/
│   ├── config/          # Configuration files
│   │   ├── config.php   # Environment config
│   │   ├── database.php # PDO database connection
│   │   └── constants.php # Constants & helper functions
│   ├── core/            # Core framework classes
│   │   ├── Auth.php     # Authentication
│   │   ├── Controller.php # Base controller
│   │   ├── Database.php # Database wrapper
│   │   ├── Middleware.php # Security middleware
│   │   ├── Model.php    # Base model
│   │   ├── Router.php   # URL router
│   │   ├── Session.php  # Session management
│   │   └── View.php     # View renderer
│   ├── controllers/     # Application controllers
│   ├── models/          # Data models
│   └── views/           # View templates
├── database/
│   ├── schema.sql       # Database schema
│   └── seed.sql         # Seed data
├── uploads/             # File uploads
├── .env                 # Environment configuration
├── .env.example         # Example environment
├── .htaccess            # Root .htaccess
└── README.md
```

## Security Features

- Password hashing with bcrypt (cost factor 12)
- CSRF token protection on all forms
- Rate limiting on login attempts (5 attempts per 15 minutes)
- Session regeneration on login
- Security headers (X-Frame-Options, X-Content-Type-Options, CSP, etc.)
- Input validation and sanitization
- Audit logging for all actions
- SQL injection prevention via prepared statements

## Deployment (cPanel)

The `.cpanel.yml` file is configured for automatic deployment:
```yaml
deployment:
  tasks:
    - export DEPLOYPATH=$HOME/public_html/
    - /bin/mkdir -p "$DEPLOYPATH"
    - /usr/bin/find . -mindepth 1 -maxdepth 1 ! -name ".git" ! -name ".cpanel.yml" ! -name ".gitignore" ! -name ".env" ! -name ".env.example" -exec /bin/cp -R {} "$DEPLOYPATH" \;
```

## License

Designed by TekTrend
