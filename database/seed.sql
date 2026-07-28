-- ============================================================
-- Tek Trend Virtual Company Management System
-- Seed Data
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- DEPARTMENTS
-- ============================================================
INSERT INTO departments (name, slug, description, color, status) VALUES
('Executive', 'executive', 'Executive leadership and management', '#ef4444', 'active'),
('Engineering', 'engineering', 'Software development and engineering', '#3b82f6', 'active'),
('Sales', 'sales', 'Sales and business development', '#10b981', 'active'),
('Marketing', 'marketing', 'Marketing and brand management', '#8b5cf6', 'active'),
('Finance', 'finance', 'Financial management and accounting', '#f59e0b', 'active'),
('Human Resources', 'hr', 'Human resources and administration', '#ec4899', 'active'),
('Support', 'support', 'Customer support and service', '#06b6d4', 'active'),
('Operations', 'operations', 'Operations and logistics', '#84cc16', 'active');

-- ============================================================
-- USERS (Admin)
-- ============================================================
INSERT INTO users (department_id, employee_id, first_name, last_name, email, phone, password, role, position, status, is_online, work_status, created_at) VALUES
(1, 'EMP-001', 'Admin', 'User', 'admin@tektrend.com', '0707246273', '$2y$12$LQvKT3hYJ8sN7pM2rQ1wEeQ8sN7pM2rQ1wEeQ8sN7pM2rQ1wEeQ8sN', 'admin', 'System Administrator', 'active', 0, 'offline', NOW()),
(2, 'EMP-002', 'John', 'Smith', 'john@tektrend.com', '0707246274', '$2y$12$LQvKT3hYJ8sN7pM2rQ1wEeQ8sN7pM2rQ1wEeQ8sN7pM2rQ1wEeQ8sN', 'manager', 'Engineering Manager', 'active', 0, 'offline', NOW()),
(3, 'EMP-003', 'Sarah', 'Johnson', 'sarah@tektrend.com', '0707246275', '$2y$12$LQvKT3hYJ8sN7pM2rQ1wEeQ8sN7pM2rQ1wEeQ8sN7pM2rQ1wEeQ8sN', 'sales', 'Sales Representative', 'active', 0, 'offline', NOW()),
(4, 'EMP-004', 'Michael', 'Brown', 'michael@tektrend.com', '0707246276', '$2y$12$LQvKT3hYJ8sN7pM2rQ1wEeQ8sN7pM2rQ1wEeQ8sN7pM2rQ1wEeQ8sN', 'employee', 'Marketing Specialist', 'active', 0, 'offline', NOW()),
(5, 'EMP-005', 'Emily', 'Davis', 'emily@tektrend.com', '0707246277', '$2y$12$LQvKT3hYJ8sN7pM2rQ1wEeQ8sN7pM2rQ1wEeQ8sN7pM2rQ1wEeQ8sN', 'accountant', 'Accountant', 'active', 0, 'offline', NOW());

-- Update department heads
UPDATE departments SET head_id = 1 WHERE slug = 'executive';
UPDATE departments SET head_id = 2 WHERE slug = 'engineering';
UPDATE departments SET head_id = 3 WHERE slug = 'sales';
UPDATE departments SET head_id = 4 WHERE slug = 'marketing';
UPDATE departments SET head_id = 5 WHERE slug = 'finance';

-- ============================================================
-- LEAD SOURCES
-- ============================================================
INSERT INTO lead_sources (name, type, description, status) VALUES
('Website Contact Form', 'online', 'Leads from website contact form', 'active'),
('Google Ads', 'online', 'Leads from Google Ads campaigns', 'active'),
('Facebook', 'social', 'Leads from Facebook', 'active'),
('LinkedIn', 'social', 'Leads from LinkedIn', 'active'),
('Referral', 'referral', 'Referral from existing customers', 'active'),
('Trade Show', 'offline', 'Leads from trade shows and events', 'active'),
('Email Campaign', 'email', 'Leads from email marketing', 'active'),
('Cold Call', 'offline', 'Leads from cold calling', 'active');

-- ============================================================
-- TAX RATES
-- ============================================================
INSERT INTO tax_rates (name, rate, type, country, state, status) VALUES
('VAT', 16.00, 'percentage', 'Kenya', NULL, 'active'),
('Sales Tax', 7.50, 'percentage', 'USA', 'California', 'active'),
('GST', 5.00, 'percentage', 'Canada', NULL, 'active'),
('Corporate Tax', 30.00, 'percentage', 'Kenya', NULL, 'active'),
('Income Tax', 15.00, 'percentage', 'Kenya', NULL, 'active');

-- ============================================================
-- SETTINGS
-- ============================================================
INSERT INTO settings (key, value, type, group, description) VALUES
('company_name', 'Tek Trend Innovations', 'string', 'general', 'Company name'),
('company_email', 'info@tektrend.com', 'string', 'general', 'Company email'),
('company_phone', '0707246273', 'string', 'general', 'Company phone'),
('company_address', '123 Innovation Drive, Tech City', 'string', 'general', 'Company address'),
('currency_symbol', '$', 'string', 'finance', 'Currency symbol'),
('currency_code', 'USD', 'string', 'finance', 'Currency code'),
('timezone', 'Africa/Nairobi', 'string', 'general', 'Timezone'),
('date_format', 'Y-m-d', 'string', 'general', 'Date format'),
('items_per_page', '20', 'integer', 'general', 'Items per page'),
('session_timeout', '1440', 'integer', 'security', 'Session timeout in seconds'),
('password_min_length', '8', 'integer', 'security', 'Minimum password length'),
('login_rate_limit', '5', 'integer', 'security', 'Max login attempts before rate limiting'),
('email_from_name', 'Tek Trend', 'string', 'email', 'Default email from name'),
('email_from_address', 'info@tektrend.com', 'string', 'email', 'Default email from address'),
('enable_registration', '0', 'boolean', 'security', 'Enable user registration'),
('enable_https', '0', 'boolean', 'security', 'Force HTTPS'),
('maintenance_mode', '0', 'boolean', 'general', 'Maintenance mode'),
('logo_url', '', 'string', 'general', 'Company logo URL'),
('favicon_url', '', 'string', 'general', 'Favicon URL');

-- ============================================================
-- CONTENT (Site Content for CMS)
-- ============================================================
INSERT INTO content (key, title, content, type, page, section, is_active, created_at) VALUES
('hero_title', 'Hero Title', 'code · design · systems', 'text', 'home', 'hero', 1, NOW()),
('hero_subtitle', 'Hero Subtitle', 'PHP · HTML · CSS · Google Script · Dashboards · School Management', 'text', 'home', 'hero', 1, NOW()),
('hero_badge', 'Hero Badge', 'Tek Trend Certified', 'text', 'home', 'hero', 1, NOW()),
('contact_email', 'Contact Email', 'info@tektrend', 'text', 'home', 'contact', 1, NOW()),
('contact_phone', 'Contact Phone', '0707246273', 'text', 'home', 'contact', 1, NOW()),
('contact_address', 'Contact Address', '123 Innovation Drive, Tech City', 'text', 'home', 'contact', 1, NOW()),
('about_title', 'About Title', 'About Tek Trend', 'text', 'home', 'about', 1, NOW()),
('about_content', 'About Content', 'We are a world-class technology company specializing in web design, PHP development, and dashboard systems.', 'html', 'home', 'about', 1, NOW()),
('footer_brand', 'Footer Brand', 'Designed by TekTrend', 'text', 'home', 'footer', 1, NOW()),
('footer_email', 'Footer Email', 'info@tektrend', 'text', 'home', 'footer', 1, NOW()),
('footer_phone', 'Footer Phone', '0707246273', 'text', 'home', 'footer', 1, NOW()),
('footer_portfolio', 'Footer Portfolio', 'Portfolio', 'text', 'home', 'footer', 1, NOW());

-- ============================================================
-- DEMOS (Cards)
-- ============================================================
INSERT INTO demos (title, slug, description, short_description, icon, url, category, visibility, is_active, sort_order, created_at) VALUES
('Analytics Dashboard', 'analytics-dashboard', 'KPI cards, trend views, navigation and modern admin layout.', 'KPI cards, trend views, navigation and modern admin layout.', 'fas fa-chart-line', 'dashboard.html', 'dashboard', 'public', 1, 1, NOW()),
('Digital Marketing', 'digital-marketing', 'Landing page demo for marketing services and lead capture.', 'Landing page demo for marketing services and lead capture.', 'fas fa-bullhorn', 'live_demo/digital_markting.html', 'marketing', 'public', 1, 2, NOW()),
('E-commerce', 'e-commerce', 'Product showcase layout with shop-style sections.', 'Product showcase layout with shop-style sections.', 'fas fa-shopping-cart', 'live_demo/e-commerce.html', 'ecommerce', 'public', 1, 3, NOW()),
('Engineering', 'engineering', 'Corporate engineering/services style layout.', 'Corporate engineering/services style layout.', 'fas fa-gear', 'live_demo/engineering.html', 'corporate', 'public', 1, 4, NOW()),
('Graphic Design', 'graphic-design', 'Portfolio-style design layout for creative services.', 'Portfolio-style design layout for creative services.', 'fas fa-pen-nib', 'live_demo/graphic.html', 'portfolio', 'public', 1, 5, NOW()),
('Law Firm', 'law-firm', 'Professional legal firm layout with strong typography.', 'Professional legal firm layout with strong typography.', 'fas fa-gavel', 'live_demo/law_firm (2).html', 'business', 'public', 1, 6, NOW()),
('Church Website', 'church', 'Bright church website demo for ministries, events and community updates.', 'Bright church website demo for ministries, events and community updates.', 'fas fa-church', 'live_demo/church.html', 'church', 'public', 1, 7, NOW()),
('Magazine', 'magazine', 'Editorial layout demo for news, blog, or magazine sites.', 'Editorial layout demo for news, blog, or magazine sites.', 'fas fa-newspaper', 'live_demo/magazine.html', 'editorial', 'public', 1, 8, NOW()),
('Nexus', 'nexus', 'Modern tech-style landing page template.', 'Modern tech-style landing page template.', 'fas fa-network-wired', 'live_demo/nexus.html', 'tech', 'public', 1, 9, NOW()),
('Restaurant', 'restaurant', 'Restaurant / food business landing page demo.', 'Restaurant / food business landing page demo.', 'fas fa-utensils', 'live_demo/restaurant.html', 'food', 'public', 1, 10, NOW());

-- Demo tech tags
INSERT INTO demo_tech (demo_id, name, sort_order) VALUES
(1, 'Dashboard', 1), (1, 'Admin UI', 2), (1, 'Inter', 3),
(2, 'Landing', 1), (2, 'Marketing', 2), (2, 'UI', 3),
(3, 'Shop', 1), (3, 'Products', 2), (3, 'UI', 3),
(4, 'Corporate', 1), (4, 'Services', 2), (4, 'UI', 3),
(5, 'Portfolio', 1), (5, 'Branding', 2), (5, 'UI', 3),
(6, 'Business', 1), (6, 'Services', 2), (6, 'UI', 3),
(7, 'Church', 1), (7, 'Ministry', 2), (7, 'Community', 3),
(8, 'Editorial', 1), (8, 'Blog', 2), (8, 'UI', 3),
(9, 'Tech', 1), (9, 'Landing', 2), (9, 'UI', 3),
(10, 'Food', 1), (10, 'Menu', 2), (10, 'UI', 3);

-- ============================================================
-- SAMPLE DATA
-- ============================================================

-- Sample customers
INSERT INTO customers (first_name, last_name, email, phone, company, address, city, state, zip_code, country, status, created_at) VALUES
('Sarah', 'Chen', 'sarah.chen@example.com', '555-0101', 'Tech Solutions Inc', '123 Tech Street', 'San Francisco', 'CA', '94101', 'USA', 'active', NOW()),
('Michael', 'Torres', 'michael.torres@example.com', '555-0102', 'Global Systems LLC', '456 Business Ave', 'New York', 'NY', '10001', 'USA', 'active', NOW()),
('Emily', 'Watson', 'emily.watson@example.com', '555-0103', 'Innovation Labs', '789 Innovation Blvd', 'Austin', 'TX', '73301', 'USA', 'active', NOW()),
('James', 'Kim', 'james.kim@example.com', '555-0104', 'Digital Dynamics', '321 Digital Way', 'Seattle', 'WA', '98101', 'USA', 'active', NOW()),
('Lisa', 'Park', 'lisa.park@example.com', '555-0105', 'Future Tech Corp', '654 Future St', 'Boston', 'MA', '02101', 'USA', 'active', NOW());

-- Sample leads
INSERT INTO leads (source_id, assigned_to, first_name, last_name, email, phone, company, position, value, status, priority, notes, next_followup, created_at) VALUES
(1, 3, 'David', 'Wilson', 'david.wilson@startup.com', '555-0201', 'StartupXYZ', 'CTO', 15000.00, 'new', 'high', 'Interested in our dashboard solution', DATE_ADD(NOW(), INTERVAL 2 DAY), NOW()),
(2, 3, 'Jennifer', 'Taylor', 'jennifer.taylor@corp.com', '555-0202', 'Corporation Inc', 'IT Director', 25000.00, 'contacted', 'medium', 'Requested demo for CRM system', DATE_ADD(NOW(), INTERVAL 1 DAY), NOW()),
(3, 3, 'Robert', 'Anderson', 'robert.anderson@agency.com', '555-0203', 'Creative Agency', 'Managing Partner', 8000.00, 'qualified', 'medium', 'Ready for proposal', DATE_ADD(NOW(), INTERVAL 3 DAY), NOW()),
(4, NULL, 'Maria', 'Garcia', 'maria.garcia@retail.com', '555-0204', 'Retail Chain', 'Operations Manager', 12000.00, 'new', 'low', 'Inquiry about e-commerce solution', DATE_ADD(NOW(), INTERVAL 5 DAY), NOW()),
(7, 3, 'Thomas', 'Lee', 'thomas.lee@finance.com', '555-0205', 'Finance Group', 'CFO', 30000.00, 'proposal', 'urgent', 'Needs financial dashboard', DATE_ADD(NOW(), INTERVAL 1 DAY), NOW());

-- Sample invoices
INSERT INTO invoices (customer_id, invoice_number, issue_date, due_date, subtotal, tax_amount, discount_amount, total, status, notes, created_by, created_at) VALUES
(1, 'INV-2026-001', '2026-07-01', '2026-07-15', 2450.00, 0.00, 0.00, 2450.00, 'paid', 'Web design project', 1, NOW()),
(2, 'INV-2026-002', '2026-07-05', '2026-07-20', 1200.00, 180.00, 0.00, 1380.00, 'sent', 'Dashboard development', 1, NOW()),
(3, 'INV-2026-003', '2026-07-10', '2026-07-25', 3500.00, 525.00, 200.00, 3825.00, 'partial', 'CRM system - Phase 1', 1, NOW()),
(4, 'INV-2026-004', '2026-07-15', '2026-07-30', 800.00, 120.00, 0.00, 920.00, 'sent', 'Email marketing setup', 1, NOW()),
(5, 'INV-2026-005', '2026-07-20', '2026-08-05', 5600.00, 840.00, 0.00, 6440.00, 'draft', 'E-commerce platform', 1, NOW());

-- Sample invoice items
INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, tax_rate, line_total, sort_order) VALUES
(1, 'Website Design (10 pages)', 1, 2000.00, 0.00, 2000.00, 1),
(1, 'Responsive Layout', 1, 450.00, 0.00, 450.00, 2),
(2, 'Dashboard Development', 20, 60.00, 0.00, 1200.00, 1),
(3, 'CRM System Phase 1', 1, 3500.00, 0.00, 3500.00, 1),
(4, 'Email Marketing Setup', 1, 800.00, 0.00, 800.00, 1),
(5, 'E-commerce Platform', 1, 5600.00, 0.00, 5600.00, 1);

-- Sample finance transactions
INSERT INTO finance_transactions (type, category, department_id, customer_id, invoice_id, title, description, amount, tax_amount, payment_method, reference, transaction_date, created_by, created_at) VALUES
('income', 'Sales', 2, 1, 1, 'Website Design Payment', 'Payment for INV-2026-001', 2450.00, 0.00, 'bank', 'TXN-001', '2026-07-01', 1, NOW()),
('income', 'Sales', 2, 2, 2, 'Dashboard Development', 'Payment for INV-2026-002', 1380.00, 0.00, 'card', 'TXN-002', '2026-07-05', 1, NOW()),
('income', 'Sales', 2, 3, 3, 'CRM System Partial Payment', 'Partial payment for INV-2026-003', 2000.00, 0.00, 'bank', 'TXN-003', '2026-07-10', 1, NOW()),
('expense', 'Software', 2, NULL, NULL, 'Figma Subscription', 'Monthly design tool subscription', 15.00, 0.00, 'card', 'SUB-001', '2026-07-01', 1, NOW()),
('expense', 'Hosting', 2, NULL, NULL, 'Server Hosting', 'Monthly cloud hosting', 89.00, 0.00, 'card', 'SUB-002', '2026-07-01', 1, NOW()),
('expense', 'Marketing', 4, NULL, NULL, 'Google Ads', 'PPC campaign', 500.00, 0.00, 'card', 'ADS-001', '2026-07-05', 1, NOW()),
('expense', 'Office', 1, NULL, NULL, 'Office Supplies', 'Monthly office supplies', 120.00, 0.00, 'card', 'OFF-001', '2026-07-10', 1, NOW()),
('expense', 'Software', 2, NULL, NULL, 'GitHub Pro', 'Development tool subscription', 7.00, 0.00, 'card', 'SUB-003', '2026-07-15', 1, NOW());

-- Sample budgets
INSERT INTO budgets (department_id, name, category, planned_amount, spent_amount, period, start_date, end_date, status, created_by, created_at) VALUES
(2, 'Q3 Engineering Budget', 'Development', 25000.00, 12000.00, 'quarterly', '2026-07-01', '2026-09-30', 'active', 1, NOW()),
(4, 'Q3 Marketing Budget', 'Marketing', 5000.00, 2500.00, 'quarterly', '2026-07-01', '2026-09-30', 'active', 1, NOW()),
(5, 'Q3 Operations Budget', 'Operations', 3000.00, 800.00, 'quarterly', '2026-07-01', '2026-09-30', 'active', 1, NOW()),
(2, 'Software Subscriptions', 'Software', 500.00, 251.00, 'monthly', '2026-07-01', '2026-07-31', 'active', 1, NOW());

-- Sample email subscribers
INSERT INTO email_subscribers (email, first_name, last_name, source, status, subscribed_at) VALUES
('john.doe@example.com', 'John', 'Doe', 'manual', 'active', NOW()),
('jane.smith@example.com', 'Jane', 'Smith', 'manual', 'active', NOW()),
('bob.wilson@example.com', 'Bob', 'Wilson', 'website', 'active', NOW()),
('alice.brown@example.com', 'Alice', 'Brown', 'website', 'active', NOW()),
('charlie.davis@example.com', 'Charlie', 'Davis', 'referral', 'active', NOW());

-- Sample email campaign
INSERT INTO email_campaigns (name, subject, content_html, content_text, from_name, from_email, status, total_recipients, created_by, created_at) VALUES
('Welcome Campaign', 'Welcome to Tek Trend!', '<h1>Welcome!</h1><p>Thank you for subscribing to Tek Trend.</p>', 'Welcome! Thank you for subscribing to Tek Trend.', 'Tek Trend', 'info@tektrend.com', 'sent', 5, 1, NOW());

-- Sample events
INSERT INTO events (title, description, type, start_datetime, end_datetime, all_day, location, department_id, created_by, priority, status, color, created_at) VALUES
('Team Standup', 'Daily team standup meeting', 'meeting', '2026-07-28 09:00:00', '2026-07-28 09:30:00', 0, 'Conference Room A', 2, 2, 'medium', 'scheduled', '#3b82f6', NOW()),
('Client Presentation', 'Presentation for Tech Solutions Inc', 'meeting', '2026-07-29 14:00:00', '2026-07-29 15:00:00', 0, 'Online', 3, 3, 'high', 'scheduled', '#ef4444', NOW()),
('Q3 Planning', 'Quarterly planning session', 'meeting', '2026-07-30 10:00:00', '2026-07-30 12:00:00', 0, 'Conference Room B', 1, 1, 'urgent', 'scheduled', '#8b5cf6', NOW()),
('Team Building', 'Monthly team building activity', 'appointment', '2026-08-05 15:00:00', '2026-08-05 18:00:00', 0, 'Local Restaurant', 2, 2, 'medium', 'scheduled', '#10b981', NOW());

-- Sample tasks
INSERT INTO tasks (title, description, department_id, created_by, priority, status, due_date, start_date, estimated_hours, spent_hours, progress, created_at) VALUES
('Design Dashboard UI', 'Create UI mockups for the analytics dashboard', 2, 2, 'high', 'in_progress', '2026-07-29 17:00:00', '2026-07-28 09:00:00', 8.00, 4.00, 50, NOW()),
('Develop CRM Module', 'Build the CRM/leads management module', 2, 2, 'urgent', 'in_progress', '2026-08-05 17:00:00', '2026-07-27 09:00:00', 40.00, 15.00, 35, NOW()),
('Setup Email Campaign', 'Configure email marketing system', 4, 4, 'medium', 'todo', '2026-07-30 17:00:00', '2026-07-29 09:00:00', 4.00, 0.00, 0, NOW()),
('Prepare Q3 Reports', 'Generate quarterly financial reports', 5, 5, 'high', 'in_progress', '2026-07-31 17:00:00', '2026-07-28 09:00:00', 6.00, 2.00, 30, NOW()),
('Update Website Content', 'Update homepage with new projects', 4, 4, 'low', 'completed', '2026-07-27 17:00:00', '2026-07-26 09:00:00', 2.00, 2.00, 100, NOW());

-- Task assignments
INSERT INTO task_assignments (task_id, user_id, assigned_by) VALUES
(1, 2, 2),
(2, 2, 2),
(3, 4, 4),
(4, 5, 5),
(5, 4, 4);

-- Sample chat rooms
INSERT INTO chat_rooms (name, type, department_id, created_by, created_at) VALUES
('General', 'department', 1, 1, NOW()),
('Engineering', 'department', 2, 1, NOW()),
('Sales Team', 'department', 3, 1, NOW()),
('Marketing Hub', 'department', 4, 1, NOW()),
('Finance Desk', 'department', 5, 1, NOW()),
('HR Corner', 'department', 6, 1, NOW()),
('Support Channel', 'department', 7, 1, NOW()),
('All Staff', 'broadcast', NULL, 1, NOW());

-- Chat room members
INSERT INTO chat_room_members (room_id, user_id, role) VALUES
(1, 1, 'owner'), (1, 2, 'member'), (1, 3, 'member'), (1, 4, 'member'), (1, 5, 'member'),
(2, 2, 'owner'), (2, 1, 'member'),
(3, 3, 'owner'), (3, 1, 'member'),
(4, 4, 'owner'), (4, 1, 'member'),
(5, 5, 'owner'), (5, 1, 'member'),
(6, 1, 'owner'), (6, 2, 'member'),
(7, 1, 'owner'), (7, 2, 'member'), (7, 3, 'member'),
(8, 1, 'owner'), (8, 2, 'member'), (8, 3, 'member'), (8, 4, 'member'), (8, 5, 'member');

-- Sample chat messages
INSERT INTO chat_messages (room_id, user_id, message, type, created_at) VALUES
(1, 1, 'Welcome to the General channel!', 'system', NOW()),
(1, 2, 'Good morning team! Ready for the standup?', 'text', NOW()),
(1, 3, 'Yes, ready!', 'text', NOW()),
(1, 4, 'Me too!', 'text', NOW()),
(1, 5, 'Good morning everyone!', 'text', NOW()),
(2, 2, 'Working on the CRM module today', 'text', NOW()),
(2, 1, 'Great! Let me know if you need any help', 'text', NOW());

-- Sample lead activities
INSERT INTO lead_activities (lead_id, user_id, type, subject, description, activity_at) VALUES
(1, 3, 'note', 'Initial Contact', 'Left voicemail for David. Will follow up tomorrow.', NOW()),
(2, 3, 'call', 'Follow-up Call', 'Spoke with Jennifer. She is interested in the dashboard.', NOW()),
(3, 3, 'email', 'Proposal Sent', 'Sent proposal for CRM system.', NOW()),
(4, 3, 'note', 'New Lead', 'Lead assigned. Will contact within 24 hours.', NOW()),
(5, 3, 'meeting', 'Demo Scheduled', 'Scheduled demo for financial dashboard.', NOW());

-- Sample tax records
INSERT INTO tax_records (tax_rate_id, period, taxable_amount, tax_amount, status, due_date, created_at) VALUES
(1, '2026-07', 15000.00, 2400.00, 'pending', '2026-08-15', NOW()),
(4, '2026-07', 15000.00, 4500.00, 'pending', '2026-08-20', NOW());

SET FOREIGN_KEY_CHECKS = 1;
