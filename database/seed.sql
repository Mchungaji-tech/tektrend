-- ============================================================
-- Tek Trend Virtual Company Management System
-- Seed Data
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- DEPARTMENTS
-- ============================================================
TRUNCATE TABLE departments;
INSERT INTO departments (id, name, slug, description, color, status) VALUES
(1, 'Executive Board', 'executive', 'Executive leadership, strategic direction and consulting oversight', '#4f46e5', 'active'),
(2, 'Solutions & Engineering', 'engineering', 'Enterprise software development, cloud systems and AI solutions', '#2563eb', 'active'),
(3, 'Sales & Client Growth', 'sales', 'Global sales, client partnerships and deal pipeline', '#059669', 'active'),
(4, 'Design & UI/UX Studio', 'design', 'Award-winning digital experience design and branding', '#d97706', 'active'),
(5, 'Finance & Contracts', 'finance', 'Financial planning, accounting, invoicing and client agreements', '#7c3aed', 'active'),
(6, 'Customer Success & Support', 'support', 'Client consulting delivery, technical support and onboarding', '#0891b2', 'active');

-- ============================================================
-- USERS & TEAM (Password: Admin@12345 for all default accounts)
-- ============================================================
TRUNCATE TABLE users;
INSERT INTO users (id, department_id, employee_id, first_name, last_name, email, phone, password, role, position, status, is_online, work_status, created_at) VALUES
(1, 1, 'EMP-001', 'David', 'Kimani', 'ceo@tektrend.com', '+254707246273', '$2y$12$npYX0ZIuU5UKVj5amKd/u.MToAK/U7KZBkBSMXEyBBZ7Msx1VAfDW', 'admin', 'Chief Executive Officer & Principal Consultant', 'active', 1, 'working', NOW()),
(2, 1, 'EMP-002', 'TekTrend', 'Administrator', 'admin@tektrend.com', '+254707246273', '$2y$12$npYX0ZIuU5UKVj5amKd/u.MToAK/U7KZBkBSMXEyBBZ7Msx1VAfDW', 'admin', 'Head of System Administration & Operations', 'active', 1, 'working', NOW()),
(3, 3, 'EMP-003', 'Sarah', 'Johnson', 'sales@tektrend.com', '+254707246274', '$2y$12$npYX0ZIuU5UKVj5amKd/u.MToAK/U7KZBkBSMXEyBBZ7Msx1VAfDW', 'sales', 'VP of Global Sales & Client Relations', 'active', 1, 'working', NOW()),
(4, 2, 'EMP-004', 'Alex', 'Morgan', 'engineering@tektrend.com', '+254707246275', '$2y$12$npYX0ZIuU5UKVj5amKd/u.MToAK/U7KZBkBSMXEyBBZ7Msx1VAfDW', 'manager', 'Chief Technology & Solutions Architect', 'active', 1, 'working', NOW()),
(5, 5, 'EMP-005', 'Emily', 'Davis', 'finance@tektrend.com', '+254707246276', '$2y$12$npYX0ZIuU5UKVj5amKd/u.MToAK/U7KZBkBSMXEyBBZ7Msx1VAfDW', 'accountant', 'Director of Financial Operations & Contracts', 'active', 0, 'offline', NOW()),
(6, 4, 'EMP-006', 'Liam', 'Vance', 'design@tektrend.com', '+254707246277', '$2y$12$npYX0ZIuU5UKVj5amKd/u.MToAK/U7KZBkBSMXEyBBZ7Msx1VAfDW', 'employee', 'Principal UI/UX & Awwwards Design Lead', 'active', 0, 'offline', NOW());

-- Update department heads
UPDATE departments SET head_id = 1 WHERE id = 1;
UPDATE departments SET head_id = 4 WHERE id = 2;
UPDATE departments SET head_id = 3 WHERE id = 3;
UPDATE departments SET head_id = 6 WHERE id = 4;
UPDATE departments SET head_id = 5 WHERE id = 5;

-- ============================================================
-- LEAD SOURCES
-- ============================================================
TRUNCATE TABLE lead_sources;
INSERT INTO lead_sources (name, type, description, status) VALUES
('Direct Website & Zoom Booking', 'online', 'Direct consultation booking via website', 'active'),
('WhatsApp Direct Inquiries', 'online', 'Instant WhatsApp consultation messages', 'active'),
('Awwwards Design Marketplace', 'online', 'Bids & inquiries from design showcase', 'active'),
('Google Search & Organic SEO', 'online', 'Organic search engine traffic', 'active'),
('Executive Referral', 'referral', 'Referrals from enterprise clients', 'active'),
('Tech Conferences & Keynotes', 'offline', 'Speaking engagements & summits', 'active');

-- ============================================================
-- LEADS & PIPELINE DEALS
-- ============================================================
TRUNCATE TABLE leads;
INSERT INTO leads (id, source_id, assigned_to, first_name, last_name, email, phone, company, position, value, status, priority, notes, next_followup, created_at) VALUES
(1, 1, 3, 'James', 'Rotich', 'j.rotich@safariholding.com', '+254711223344', 'Safari Holdings Ltd', 'Managing Director', 18500.00, 'proposal', 'urgent', 'Needs enterprise ERP + Google Workspace workflow automation', DATE_ADD(NOW(), INTERVAL 1 DAY), NOW()),
(2, 2, 3, 'Grace', 'Mwangi', 'grace@peakfintech.co.ke', '+254722334455', 'Peak Fintech Kenya', 'Head of Product', 12000.00, 'qualified', 'high', 'Zoom consultation completed. Preparing custom API architecture scope.', DATE_ADD(NOW(), INTERVAL 2 DAY), NOW()),
(3, 3, 3, 'Arthur', 'Pendleton', 'arthur@vanguard-us.com', '+14155552671', 'Vanguard Global Real Estate', 'CEO', 25000.00, 'negotiation', 'urgent', 'Bidding on Apex Luxury Portal + Custom CRM backend integration.', DATE_ADD(NOW(), INTERVAL 1 DAY), NOW()),
(4, 1, 3, 'Fatima', 'Al-Mansoor', 'fatima@dubaiventures.ae', '+971501234567', 'Al-Mansoor Ventures', 'VP Innovation', 34000.00, 'contacted', 'high', 'Booked Zoom session on AI Chatbot + Customer Portal.', DATE_ADD(NOW(), INTERVAL 3 DAY), NOW()),
(5, 5, 3, 'Marcus', 'Chen', 'm.chen@novatech.sg', '+6591234567', 'NovaTech Singapore', 'COO', 45000.00, 'closed_won', 'high', 'Signed enterprise contract for 12-month tech consulting partnership.', NULL, NOW()),
(6, 4, 3, 'Elena', 'Rostova', 'elena@nordicconsulting.se', '+46812345678', 'Nordic Horizon Media', 'Creative Director', 8500.00, 'new', 'medium', 'Inquired about Awwwards-style interactive agency template.', DATE_ADD(NOW(), INTERVAL 2 DAY), NOW());

-- ============================================================
-- CUSTOMERS
-- ============================================================
TRUNCATE TABLE customers;
INSERT INTO customers (id, first_name, last_name, email, phone, company, address, city, state, zip_code, country, tax_id, notes, status, created_at) VALUES
(1, 'Marcus', 'Chen', 'm.chen@novatech.sg', '+6591234567', 'NovaTech Singapore', '79 Anson Road #14-01', 'Singapore', 'Singapore', '079906', 'Singapore', 'SG-20239481A', 'Enterprise client on retainer.', 'active', NOW()),
(2, 'Arthur', 'Pendleton', 'arthur@vanguard-us.com', '+14155552671', 'Vanguard Global Real Estate', '555 California Street, Suite 3200', 'San Francisco', 'CA', '94104', 'United States', 'US-94812390', 'High-value digital estate platform.', 'active', NOW()),
(3, 'Grace', 'Mwangi', 'grace@peakfintech.co.ke', '+254722334455', 'Peak Fintech Kenya', 'Delta Corner Annex, Westlands', 'Nairobi', 'Nairobi', '00100', 'Kenya', 'P051239481Z', 'Fintech integration & dashboard client.', 'active', NOW());

-- ============================================================
-- CONSULTATIONS & ZOOM SESSIONS
-- ============================================================
TRUNCATE TABLE consultations;
INSERT INTO consultations (id, name, email, phone, company, service_type, preferred_date, preferred_time, duration_minutes, zoom_link, meeting_id, status, notes, created_at) VALUES
(1, 'Fatima Al-Mansoor', 'fatima@dubaiventures.ae', '+971501234567', 'Al-Mansoor Ventures', 'AI & Enterprise Automation', CURDATE(), '03:00 PM', 45, 'https://zoom.us/j/9924883102?pwd=tektrend_consult', '992-488-3102', 'confirmed', 'Exploring automated AI customer service & lead intake workflow.', NOW()),
(2, 'Dr. Samuel Kariuki', 'samuel@medicareplus.org', '+254733445566', 'MediCare Plus Africa', 'Full Stack PHP Architecture', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:00 AM', 60, 'https://zoom.us/j/9924883102?pwd=tektrend_consult', '992-488-3102', 'confirmed', 'Consultation on scaling clinic management system across 8 branches.', NOW()),
(3, 'Sophie Dubois', 'sophie@luxeparis.fr', '+33140506070', 'Luxe Paris Digital', 'UI/UX & Awwwards Web Design', DATE_ADD(CURDATE(), INTERVAL 2 DAY), '02:30 PM', 45, 'https://zoom.us/j/9924883102?pwd=tektrend_consult', '992-488-3102', 'pending', 'Interested in custom bidding for brand launch web platform.', NOW());

-- ============================================================
-- E-COMMERCE DESIGN MARKETPLACE & AWWWARDS BIDDING ITEMS
-- ============================================================
TRUNCATE TABLE design_items;
INSERT INTO design_items (id, title, slug, category, award_badge, description, short_description, image, demo_url, sale_type, starting_bid, current_bid, buy_now_price, bid_end_date, total_bids, views_count, status, created_by, created_at) VALUES
(1, 'Apex Luxury Real Estate Portal', 'apex-luxury-real-estate', 'Real Estate & Architecture', 'Site of the Day', 'Ultra-modern real estate platform with 3D virtual tour support, interactive map filters, agent matrix, and high-conversion lead generation architecture.', 'Luxury property showcase with 3D tours & real-time valuation.', 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80', 'https://tektrend.com/demos/apex-real-estate', 'both', 1200.00, 2400.00, 4500.00, DATE_ADD(NOW(), INTERVAL 5 DAY), 8, 1420, 'active', 6, NOW()),
(2, 'Horizon Cloud SaaS & Analytics', 'horizon-saas-analytics', 'Fintech & SaaS', 'Developer Award', 'Dark/Light adaptive dashboard system built for high-scale metrics, subscription billing, API rate monitoring, and automated report generators.', 'Fintech analytics engine with live metric streams.', 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80', 'https://tektrend.com/demos/horizon-saas', 'both', 950.00, 1850.00, 3800.00, DATE_ADD(NOW(), INTERVAL 3 DAY), 6, 980, 'active', 6, NOW()),
(3, 'Aura Creative Studio & Agency Showcase', 'aura-creative-studio', 'Creative & Agency', 'Site of the Month', 'Award-winning smooth kinetic typography, magnetic hover effects, interactive 3D WebGL hero showcases, and instant project booking.', 'High-end agency portfolio with WebGL interactions.', 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=80', 'https://tektrend.com/demos/aura-studio', 'both', 800.00, 1600.00, 3200.00, DATE_ADD(NOW(), INTERVAL 7 DAY), 5, 1150, 'active', 6, NOW()),
(4, 'Vanguard Freight & Fleet Enterprise ERP', 'vanguard-freight-erp', 'Logistics & Supply Chain', 'Honorable Mention', 'Comprehensive enterprise logistics suite featuring real-time driver tracking, cargo manifest generators, automated route fuel billing, and client tracking.', 'Supply chain ERP with live dispatch & GPS routing.', 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=80', 'https://tektrend.com/demos/vanguard-erp', 'both', 1500.00, 3100.00, 5800.00, DATE_ADD(NOW(), INTERVAL 4 DAY), 9, 870, 'active', 6, NOW());

-- ============================================================
-- DESIGN BIDS
-- ============================================================
TRUNCATE TABLE design_bids;
INSERT INTO design_bids (id, design_item_id, bidder_name, bidder_email, bidder_phone, bid_amount, message, status, created_at) VALUES
(1, 1, 'Arthur Pendleton', 'arthur@vanguard-us.com', '+14155552671', 2400.00, 'We want this for our high-end Beverly Hills listing portal.', 'pending', NOW()),
(2, 1, 'Julian Rossi', 'julian@milanorealestate.it', '+39021234567', 2100.00, 'Placing bid for European rollout.', 'outbid', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 2, 'Peak Fintech Group', 'invest@peakfintech.co.ke', '+254722334455', 1850.00, 'Integrating with our micro-lending API system.', 'pending', NOW()),
(4, 3, 'Sophie Dubois', 'sophie@luxeparis.fr', '+33140506070', 1600.00, 'Perfect branding foundation for our Paris fashion studio.', 'pending', NOW());

-- ============================================================
-- CONTRACTS
-- ============================================================
TRUNCATE TABLE contracts;
INSERT INTO contracts (id, contract_number, title, customer_id, lead_id, value, start_date, end_date, terms, status, signed_at, signed_by_name, created_by, created_at) VALUES
(1, 'CNT-2026-001', 'Enterprise Technology & AI Architecture Advisory', 1, 5, 45000.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 'Tek Trend Innovations shall provide dedicated technology advisory, full-stack PHP architecture oversight, automated CI/CD pipelines, and cloud optimization for NovaTech Singapore.', 'signed', NOW(), 'Marcus Chen', 1, NOW()),
(2, 'CNT-2026-002', 'Digital Real Estate Bidding Platform Delivery', 2, 3, 25000.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 6 MONTH), 'Development, customization, deployment, and 6-month support of the Apex Luxury Estate portal including CRM lead integration.', 'active', NOW(), 'Arthur Pendleton', 1, NOW()),
(3, 'CNT-2026-003', 'Fintech Payment Gateway & Workflow Automation', 3, 2, 12000.00, DATE_ADD(CURDATE(), INTERVAL 7 DAY), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), 'Custom integration of M-Pesa, card processing, automated receipt generation, and Google Sheets reconciliation system.', 'draft', NULL, NULL, 1, NOW());

-- ============================================================
-- INVOICES & ITEMS
-- ============================================================
TRUNCATE TABLE invoices;
INSERT INTO invoices (id, customer_id, invoice_number, issue_date, due_date, subtotal, tax_amount, discount_amount, total, tax_rate_id, status, notes, created_by, created_at) VALUES
(1, 1, 'INV-2026-001', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), 15000.00, 2400.00, 0.00, 17400.00, 1, 'paid', 'First retainer tranche for Enterprise Tech Architecture.', 1, NOW()),
(2, 2, 'INV-2026-002', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), 12500.00, 2000.00, 500.00, 14000.00, 1, 'sent', '50% initial milestone for Apex Platform delivery.', 1, NOW()),
(3, 3, 'INV-2026-003', DATE_SUB(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 10 DAY), 6000.00, 960.00, 0.00, 6960.00, 1, 'sent', 'Milestone 1 for Fintech automation workflows.', 1, NOW());

TRUNCATE TABLE invoice_items;
INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, tax_rate, line_total, sort_order) VALUES
(1, 'Enterprise Advisory Retainer (Q1 2026)', 1.00, 15000.00, 16.00, 15000.00, 1),
(2, 'Apex Luxury Estate Template & Backend Core', 1.00, 10000.00, 16.00, 10000.00, 1),
(2, 'Custom Google Sheets & CRM Webhook Integration', 1.00, 2500.00, 16.00, 2500.00, 2),
(3, 'Fintech Payment Gateway & M-Pesa Integration', 1.00, 6000.00, 16.00, 6000.00, 1);

-- ============================================================
-- FINANCES & TRANSACTIONS
-- ============================================================
TRUNCATE TABLE tax_rates;
INSERT INTO tax_rates (id, name, rate, type, country, state, status) VALUES
(1, 'VAT Standard (Kenya)', 16.00, 'percentage', 'Kenya', NULL, 'active'),
(2, 'Corporate Services Tax (US)', 8.25, 'percentage', 'USA', 'California', 'active'),
(3, 'GST Digital Services', 8.00, 'percentage', 'Singapore', NULL, 'active'),
(4, 'Withholding Tax (Consulting)', 5.00, 'percentage', 'Kenya', NULL, 'active');

TRUNCATE TABLE finance_transactions;
INSERT INTO finance_transactions (type, category, department_id, customer_id, invoice_id, title, description, amount, tax_amount, tax_rate_id, payment_method, reference, transaction_date, created_by) VALUES
('income', 'Consulting Retainer', 1, 1, 1, 'Client Retainer Payment - NovaTech SG', 'Q1 Advisory tranche settled via wire transfer.', 17400.00, 2400.00, 1, 'bank', 'WIRE-SG-98214', CURDATE(), 1),
('income', 'Design Marketplace Sale', 4, 2, NULL, 'Design Template Deposit - Vanguard US', 'Downpayment on Apex Luxury Portal.', 5000.00, 800.00, 1, 'bank', 'WIRE-US-11204', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 1),
('expense', 'Cloud Infrastructure & AWS', 2, NULL, NULL, 'AWS Cloud & Dedicated Server Cluster', 'Monthly server hosting, AI endpoints and GPU instances.', 850.00, 0.00, NULL, 'card', 'CARD-AWS-902', CURDATE(), 1),
('expense', 'Zoom Enterprise & Video API', 1, NULL, NULL, 'Zoom Pro Enterprise Teleconferencing', 'Annual video consultation licenses.', 240.00, 0.00, NULL, 'card', 'ZOOM-ANN-2026', DATE_SUB(CURDATE(), INTERVAL 10 DAY), 1),
('expense', 'Design & Typography Licenses', 4, NULL, NULL, 'Awwwards / Foundry Font Licenses', 'Commercial font and 3D asset library pack.', 420.00, 0.00, NULL, 'card', 'FONT-FOUNDRY-33', DATE_SUB(CURDATE(), INTERVAL 15 DAY), 1);

-- ============================================================
-- BUDGETS
-- ============================================================
TRUNCATE TABLE budgets;
INSERT INTO budgets (department_id, name, category, planned_amount, spent_amount, period, start_date, end_date, status, created_by) VALUES
(2, 'Cloud AI & Server Infrastructure', 'Technology', 12000.00, 2850.00, 'quarterly', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 90 DAY), 'active', 1),
(3, 'Global Growth & Client Acquisition', 'Marketing & Sales', 15000.00, 4200.00, 'quarterly', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 90 DAY), 'active', 1),
(4, 'Awwwards Submissions & Studio Assets', 'Design & UI/UX', 8000.00, 1650.00, 'quarterly', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 90 DAY), 'active', 1);

-- ============================================================
-- TASKS
-- ============================================================
TRUNCATE TABLE tasks;
INSERT INTO tasks (id, title, description, department_id, created_by, priority, status, due_date, start_date, estimated_hours, spent_hours, progress) VALUES
(1, 'Deploy Apex Real Estate Live Bidding Demo', 'Finalize interactive 3D floor plan viewer and connect bid modal webhook.', 4, 1, 'high', 'in_progress', DATE_ADD(CURDATE(), INTERVAL 2 DAY), CURDATE(), 12.00, 8.00, 75),
(2, 'Host Zoom Consultation with Fatima (Dubai Ventures)', 'Prepare custom deck on AI Chatbot customer intake architecture.', 1, 1, 'urgent', 'in_progress', CURDATE(), CURDATE(), 2.00, 1.00, 50),
(3, 'Draft Scope Agreement for Safari Holdings ERP', 'Breakdown milestones for Google Sheets + PHP custom logistics ERP.', 3, 1, 'medium', 'todo', DATE_ADD(CURDATE(), INTERVAL 3 DAY), CURDATE(), 6.00, 0.00, 0);

TRUNCATE TABLE task_assignments;
INSERT INTO task_assignments (task_id, user_id, assigned_by) VALUES
(1, 6, 1),
(2, 1, 1),
(2, 3, 1),
(3, 3, 1);

-- ============================================================
-- CHAT ROOMS & MESSAGES
-- ============================================================
TRUNCATE TABLE chat_rooms;
INSERT INTO chat_rooms (id, name, type, created_by) VALUES
(1, 'General & Strategic Overview', 'broadcast', 1),
(2, 'Consulting & Client Delivery', 'department', 1),
(3, 'Sales Pipeline & Design Bids', 'department', 3);

TRUNCATE TABLE chat_room_members;
INSERT INTO chat_room_members (room_id, user_id, role) VALUES
(1, 1, 'admin'), (1, 2, 'member'), (1, 3, 'member'), (1, 4, 'member'), (1, 5, 'member'), (1, 6, 'member'),
(2, 1, 'admin'), (2, 4, 'member'), (2, 6, 'member'),
(3, 1, 'admin'), (3, 3, 'admin'), (3, 6, 'member');

TRUNCATE TABLE chat_messages;
INSERT INTO chat_messages (room_id, user_id, message, type, created_at) VALUES
(1, 1, 'Welcome to Tek Trend Virtual Office. All client consultations, design marketplace bids, and contracts are active.', 'text', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(3, 3, 'Arthur from Vanguard US just placed a $2,400 bid on the Apex Luxury Estate portal. Following up on Zoom.', 'text', DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(2, 4, 'Cloud AI demo endpoints are live and integrated on the consultancy frontend.', 'text', DATE_SUB(NOW(), INTERVAL 30 MINUTE));

-- ============================================================
-- SETTINGS
-- ============================================================
TRUNCATE TABLE settings;
INSERT INTO settings (`key`, `value`, `type`, `group`, `description`) VALUES
('company_name', 'Tek Trend Innovations', 'string', 'general', 'Company full brand name'),
('company_tagline', 'World-Class Software Architecture, AI & Design Consultancy', 'string', 'general', 'Company slogan'),
('company_email', 'info@tektrend.com', 'string', 'general', 'Primary company email'),
('company_phone', '0707246273', 'string', 'general', 'Direct call phone number'),
('company_whatsapp', '254707246273', 'string', 'general', 'Official WhatsApp number (international without +)'),
('company_address', '123 Innovation Drive, Tech City, Nairobi', 'string', 'general', 'Physical office address'),
('currency_symbol', '$', 'string', 'finance', 'Default display currency symbol'),
('currency_code', 'USD', 'string', 'finance', 'Default ISO currency code'),
('timezone', 'Africa/Nairobi', 'string', 'general', 'System timezone'),
('zoom_default_link', 'https://zoom.us/j/9924883102?pwd=tektrend_consult', 'string', 'general', 'Default Zoom meeting link for consultations'),
('zoom_meeting_id', '992-488-3102', 'string', 'general', 'Default Zoom meeting ID');

-- ============================================================
-- CMS CONTENT
-- ============================================================
TRUNCATE TABLE content;
INSERT INTO content (`key`, `title`, `content`, `type`, `page`, `section`, `is_active`, `created_at`) VALUES
('hero_title', 'Hero Title', 'High-Impact Software Architecture & Design Consultancy', 'text', 'home', 'hero', 1, NOW()),
('hero_subtitle', 'Hero Subtitle', 'We engineer world-class PHP systems, Google Script enterprise workflows, AI chatbots, and Awwwards-caliber digital platforms for ambitious global companies.', 'text', 'home', 'hero', 1, NOW()),
('hero_badge', 'Hero Badge', 'Certified Enterprise Tech Consultancy', 'text', 'home', 'hero', 1, NOW()),
('about_title', 'About Tek Trend', 'Transforming Ideas Into Precision Engineering', 'text', 'home', 'about', 1, NOW()),
('about_content', 'About Content', 'Tek Trend Innovations is a premier software engineering and design consultancy. From full-scale SaaS dashboards and school/enterprise ERPs to real-time AI automation and award-winning design templates, we deliver mission-critical solutions.', 'html', 'home', 'about', 1, NOW());

-- ============================================================
-- PORTFOLIO DEMOS & LIVE TEMPLATES
-- ============================================================
INSERT INTO portfolio_demos (id, title, category, short_description, icon, demo_type, demo_url, hosting_domain, tech_stack, preview_image, award_badge, price, is_featured, sort_order, created_at) VALUES
(1, 'Studio Maven · Creative Studio', 'Creative & Design', 'Minimalist luxury branding portfolio with case studies, smooth motion reels, interactive typography, and instant project booking.', 'fas fa-palette', 'local', '/live_demo/graphic.html', 'Turnkey Template', 'HTML5, CSS3, GSAP, Responsive', 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80', 'Site of the Day', 49.00, 1, 1, NOW()),
(2, 'GrowthPulse · Marketing Agency', 'Marketing & SEO', 'Growth-driven marketing agency portal featuring live campaign tracking, analytics metrics, interactive ROI calculator, and lead generation.', 'fas fa-chart-line', 'local', '/live_demo/digital_markting.html', 'Turnkey Template', 'HTML5, Bootstrap, CSS3, Chart.js', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80', 'Top Conversion', 59.00, 1, 2, NOW()),
(3, 'LuxeCart · Modern E-Commerce', 'E-Commerce & Retail', 'High-speed storefront with dynamic category filtering, cart management, instant checkout flow, customer wishlist, and payment readiness.', 'fas fa-store', 'local', '/live_demo/e-commerce.html', 'Turnkey Template', 'HTML5, CSS3, JavaScript, eCommerce UI', 'https://images.unsplash.com/photo-1556742049-0a67c5574f73?auto=format&fit=crop&w=800&q=80', 'Best Architecture', 79.00, 1, 3, NOW()),
(4, 'Vanguard & Sterling · Law Firm', 'Legal & Corporate', 'High-trust legal advisory platform with practice areas directory, attorney profiles, consultation booking vaults, and case study breakdowns.', 'fas fa-scale-balanced', 'local', '/live_demo/law_firm.html', 'Turnkey Template', 'HTML5, Modern CSS, JavaScript, Legal Portal', 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=800&q=80', 'Corporate Elite', 69.00, 1, 4, NOW()),
(5, 'Grace Fellowship · Community Hub', 'Non-Profit & Community', 'Welcoming community portal with sermon live broadcast layout, event timetables, online donation workflows, and member connection registry.', 'fas fa-church', 'local', '/live_demo/church.html', 'Turnkey Template', 'HTML5, CSS3, Audio Stream UI, Grid', 'https://images.unsplash.com/photo-1548625361-195b0662d083?auto=format&fit=crop&w=800&q=80', 'Community Choice', 39.00, 1, 5, NOW()),
(6, 'Titan Engineering · Industrial Systems', 'Engineering & Tech', 'Industrial automation showcase with technical specification viewers, interactive blueprint galleries, equipment catalogs, and RFQ request forms.', 'fas fa-cogs', 'local', '/live_demo/engineering.html', 'Turnkey Template', 'HTML5, Canvas, WebGL, Industrial CSS', 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80', 'Industry Benchmark', 79.00, 1, 6, NOW()),
(7, 'Le Jardin · Gourmet Restaurant', 'Hospitality & Dining', 'Atmospheric dining experience platform with seasonal menu showcases, chef stories, interactive table reservation system, and food gallery.', 'fas fa-utensils', 'local', '/live_demo/restaurant.html', 'Turnkey Template', 'HTML5, Playfair Display, CSS Animations', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80', 'Michelin Aesthetic', 49.00, 1, 7, NOW()),
(8, 'Verve · Modern Tech Magazine', 'Publishing & Media', 'Ultra-sleek editorial platform designed for high readership with dark-mode aesthetic, typography hierarchy, category tabs, and newsletter forms.', 'fas fa-newspaper', 'local', '/live_demo/magazine.html', 'Turnkey Template', 'HTML5, Modern Typography, Responsive Grid', 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=800&q=80', 'Editorial Pick', 39.00, 1, 8, NOW()),
(9, 'Nexus Commercial · Enterprise SaaS', 'Fintech & SaaS', 'Enterprise-grade technology portal with interactive software feature matrices, live pricing tiers, API doc viewer, and sales funnel.', 'fas fa-network-wired', 'local', '/live_demo/nexus.html', 'Turnkey Template', 'HTML5, CSS3, Inter Font, SaaS UI', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80', 'Developer Award', 89.00, 1, 9, NOW())
ON DUPLICATE KEY UPDATE title = VALUES(title), price = VALUES(price);

SET FOREIGN_KEY_CHECKS = 1;
