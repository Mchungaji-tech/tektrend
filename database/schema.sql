-- ============================================================
-- Tek Trend Virtual Company Management System
-- Database Schema
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- CORE TABLES
-- ============================================================

CREATE TABLE `departments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `head_id` INT UNSIGNED DEFAULT NULL,
    `color` VARCHAR(7) DEFAULT '#3b82f6',
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`),
    KEY `head_id` (`head_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `department_id` INT UNSIGNED DEFAULT NULL,
    `employee_id` VARCHAR(20) NOT NULL,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin','manager','employee','accountant','sales','support') DEFAULT 'employee',
    `position` VARCHAR(100) DEFAULT NULL,
    `avatar` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('active','inactive','suspended') DEFAULT 'active',
    `last_login` TIMESTAMP NULL DEFAULT NULL,
    `last_activity` TIMESTAMP NULL DEFAULT NULL,
    `is_online` TINYINT(1) DEFAULT 0,
    `work_status` ENUM('offline','working','away','break','meeting') DEFAULT 'offline',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    UNIQUE KEY `employee_id` (`employee_id`),
    KEY `department_id` (`department_id`),
    KEY `is_online` (`is_online`),
    KEY `work_status` (`work_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_sessions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `session_id` VARCHAR(128) NOT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT,
    `login_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `last_activity` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `session_id` (`session_id`),
    KEY `last_activity` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `settings` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(100) NOT NULL,
    `value` TEXT,
    `type` ENUM('string','integer','boolean','json') DEFAULT 'string',
    `group` VARCHAR(50) DEFAULT 'general',
    `description` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `key` (`key`),
    KEY `group` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CRM / LEADS
-- ============================================================

CREATE TABLE `lead_sources` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `type` ENUM('online','offline','referral','social','email','other') DEFAULT 'other',
    `description` TEXT,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `leads` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `source_id` INT UNSIGNED DEFAULT NULL,
    `assigned_to` INT UNSIGNED DEFAULT NULL,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `company` VARCHAR(100) DEFAULT NULL,
    `position` VARCHAR(100) DEFAULT NULL,
    `value` DECIMAL(12,2) DEFAULT 0.00,
    `status` ENUM('new','contacted','qualified','proposal','negotiation','closed_won','closed_lost') DEFAULT 'new',
    `priority` ENUM('low','medium','high','urgent') DEFAULT 'medium',
    `notes` TEXT,
    `next_followup` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `source_id` (`source_id`),
    KEY `assigned_to` (`assigned_to`),
    KEY `status` (`status`),
    KEY `priority` (`priority`),
    KEY `next_followup` (`next_followup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lead_activities` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `lead_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `type` ENUM('call','email','meeting','note','task','other') DEFAULT 'note',
    `subject` VARCHAR(255) DEFAULT NULL,
    `description` TEXT,
    `activity_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `lead_id` (`lead_id`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CUSTOMERS & INVOICES
-- ============================================================

CREATE TABLE `customers` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `company` VARCHAR(100) DEFAULT NULL,
    `address` TEXT,
    `city` VARCHAR(100) DEFAULT NULL,
    `state` VARCHAR(100) DEFAULT NULL,
    `zip_code` VARCHAR(20) DEFAULT NULL,
    `country` VARCHAR(100) DEFAULT NULL,
    `tax_id` VARCHAR(50) DEFAULT NULL,
    `notes` TEXT,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `email` (`email`),
    KEY `company` (`company`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `invoices` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `customer_id` INT UNSIGNED NOT NULL,
    `invoice_number` VARCHAR(50) NOT NULL,
    `issue_date` DATE NOT NULL,
    `due_date` DATE NOT NULL,
    `subtotal` DECIMAL(12,2) DEFAULT 0.00,
    `tax_amount` DECIMAL(12,2) DEFAULT 0.00,
    `discount_amount` DECIMAL(12,2) DEFAULT 0.00,
    `total` DECIMAL(12,2) DEFAULT 0.00,
    `tax_rate_id` INT UNSIGNED DEFAULT NULL,
    `status` ENUM('draft','sent','paid','partial','overdue','cancelled') DEFAULT 'draft',
    `notes` TEXT,
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `invoice_number` (`invoice_number`),
    KEY `customer_id` (`customer_id`),
    KEY `status` (`status`),
    KEY `due_date` (`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `invoice_items` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` INT UNSIGNED NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `quantity` DECIMAL(10,2) DEFAULT 1.00,
    `unit_price` DECIMAL(12,2) DEFAULT 0.00,
    `tax_rate` DECIMAL(5,2) DEFAULT 0.00,
    `line_total` DECIMAL(12,2) DEFAULT 0.00,
    `sort_order` INT DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `invoice_payments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` INT UNSIGNED NOT NULL,
    `amount` DECIMAL(12,2) NOT NULL,
    `payment_method` ENUM('cash','bank','card','mobile','other') DEFAULT 'bank',
    `reference` VARCHAR(100) DEFAULT NULL,
    `paid_by` INT UNSIGNED DEFAULT NULL,
    `paid_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- FINANCES, TAXES & BUDGETING
-- ============================================================

CREATE TABLE `tax_rates` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `rate` DECIMAL(5,2) NOT NULL,
    `type` ENUM('percentage','fixed') DEFAULT 'percentage',
    `country` VARCHAR(100) DEFAULT NULL,
    `state` VARCHAR(100) DEFAULT NULL,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `finance_transactions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `type` ENUM('income','expense','transfer','adjustment') NOT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `department_id` INT UNSIGNED DEFAULT NULL,
    `customer_id` INT UNSIGNED DEFAULT NULL,
    `invoice_id` INT UNSIGNED DEFAULT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `amount` DECIMAL(12,2) NOT NULL,
    `tax_amount` DECIMAL(12,2) DEFAULT 0.00,
    `tax_rate_id` INT UNSIGNED DEFAULT NULL,
    `payment_method` ENUM('cash','bank','card','mobile','other') DEFAULT 'bank',
    `reference` VARCHAR(100) DEFAULT NULL,
    `transaction_date` DATE NOT NULL,
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `type` (`type`),
    KEY `category` (`category`),
    KEY `department_id` (`department_id`),
    KEY `transaction_date` (`transaction_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `budgets` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `department_id` INT UNSIGNED DEFAULT NULL,
    `name` VARCHAR(255) NOT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `planned_amount` DECIMAL(12,2) NOT NULL,
    `spent_amount` DECIMAL(12,2) DEFAULT 0.00,
    `period` ENUM('monthly','quarterly','yearly') DEFAULT 'monthly',
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `status` ENUM('active','completed','cancelled') DEFAULT 'active',
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `department_id` (`department_id`),
    KEY `period` (`period`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tax_records` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tax_rate_id` INT UNSIGNED NOT NULL,
    `period` VARCHAR(20) NOT NULL,
    `taxable_amount` DECIMAL(12,2) DEFAULT 0.00,
    `tax_amount` DECIMAL(12,2) DEFAULT 0.00,
    `status` ENUM('pending','filed','paid') DEFAULT 'pending',
    `due_date` DATE DEFAULT NULL,
    `filed_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `tax_rate_id` (`tax_rate_id`),
    KEY `period` (`period`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- EMAIL MARKETING
-- ============================================================

CREATE TABLE `email_subscribers` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(100) NOT NULL,
    `first_name` VARCHAR(50) DEFAULT NULL,
    `last_name` VARCHAR(50) DEFAULT NULL,
    `source` VARCHAR(100) DEFAULT 'manual',
    `status` ENUM('active','unsubscribed','bounced') DEFAULT 'active',
    `subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `unsubscribed_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `email_campaigns` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `content_html` LONGTEXT,
    `content_text` LONGTEXT,
    `from_name` VARCHAR(100) DEFAULT NULL,
    `from_email` VARCHAR(100) DEFAULT NULL,
    `status` ENUM('draft','scheduled','sending','sent','failed') DEFAULT 'draft',
    `scheduled_at` DATETIME DEFAULT NULL,
    `sent_at` TIMESTAMP NULL DEFAULT NULL,
    `total_recipients` INT DEFAULT 0,
    `total_sent` INT DEFAULT 0,
    `total_opened` INT DEFAULT 0,
    `total_clicked` INT DEFAULT 0,
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `status` (`status`),
    KEY `scheduled_at` (`scheduled_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `email_sends` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `campaign_id` INT UNSIGNED NOT NULL,
    `subscriber_id` INT UNSIGNED NOT NULL,
    `status` ENUM('pending','sent','failed','opened','clicked') DEFAULT 'pending',
    `sent_at` TIMESTAMP NULL DEFAULT NULL,
    `opened_at` TIMESTAMP NULL DEFAULT NULL,
    `clicked_at` TIMESTAMP NULL DEFAULT NULL,
    `error_message` TEXT,
    PRIMARY KEY (`id`),
    KEY `campaign_id` (`campaign_id`),
    KEY `subscriber_id` (`subscriber_id`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- EVENTS, CALENDAR & TIMETABLE
-- ============================================================

CREATE TABLE `events` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `type` ENUM('meeting','appointment','task','holiday','reminder','other') DEFAULT 'meeting',
    `start_datetime` DATETIME NOT NULL,
    `end_datetime` DATETIME NOT NULL,
    `all_day` TINYINT(1) DEFAULT 0,
    `location` VARCHAR(255) DEFAULT NULL,
    `department_id` INT UNSIGNED DEFAULT NULL,
    `created_by` INT UNSIGNED DEFAULT NULL,
    `priority` ENUM('low','medium','high','urgent') DEFAULT 'medium',
    `status` ENUM('scheduled','in_progress','completed','cancelled') DEFAULT 'scheduled',
    `color` VARCHAR(7) DEFAULT '#3b82f6',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `start_datetime` (`start_datetime`),
    KEY `department_id` (`department_id`),
    KEY `created_by` (`created_by`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `event_attendees` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `event_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `status` ENUM('invited','accepted','declined','tentative') DEFAULT 'invited',
    `responded_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `event_user` (`event_id`, `user_id`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `timetable_slots` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `department_id` INT UNSIGNED DEFAULT NULL,
    `day_of_week` TINYINT(1) NOT NULL COMMENT '0=Sunday, 1=Monday, ..., 6=Saturday',
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `title` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `color` VARCHAR(7) DEFAULT '#3b82f6',
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `department_id` (`department_id`),
    KEY `day_of_week` (`day_of_week`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TASKS
-- ============================================================

CREATE TABLE `tasks` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `project_id` INT UNSIGNED DEFAULT NULL,
    `department_id` INT UNSIGNED DEFAULT NULL,
    `created_by` INT UNSIGNED NOT NULL,
    `priority` ENUM('low','medium','high','urgent') DEFAULT 'medium',
    `status` ENUM('todo','in_progress','review','completed','cancelled') DEFAULT 'todo',
    `due_date` DATETIME DEFAULT NULL,
    `start_date` DATETIME DEFAULT NULL,
    `estimated_hours` DECIMAL(5,2) DEFAULT 0.00,
    `spent_hours` DECIMAL(5,2) DEFAULT 0.00,
    `progress` TINYINT(3) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `created_by` (`created_by`),
    KEY `department_id` (`department_id`),
    KEY `status` (`status`),
    KEY `priority` (`priority`),
    KEY `due_date` (`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `task_assignments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `task_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `assigned_by` INT UNSIGNED DEFAULT NULL,
    `assigned_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `task_user` (`task_id`, `user_id`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `task_comments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `task_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `comment` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `task_id` (`task_id`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- VIRTUAL OFFICE - CHAT & TELECONFERENCE
-- ============================================================

CREATE TABLE `chat_rooms` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `type` ENUM('department','private','group','broadcast') DEFAULT 'group',
    `department_id` INT UNSIGNED DEFAULT NULL,
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `department_id` (`department_id`),
    KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `chat_room_members` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `room_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `role` ENUM('owner','admin','member') DEFAULT 'member',
    `joined_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `room_user` (`room_id`, `user_id`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `chat_messages` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `room_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `message` TEXT,
    `type` ENUM('text','image','file','system') DEFAULT 'text',
    `file_path` VARCHAR(255) DEFAULT NULL,
    `file_name` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `room_id` (`room_id`),
    KEY `user_id` (`user_id`),
    KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `meetings` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `host_id` INT UNSIGNED NOT NULL,
    `room_id` VARCHAR(100) DEFAULT NULL,
    `scheduled_at` DATETIME DEFAULT NULL,
    `started_at` TIMESTAMP NULL DEFAULT NULL,
    `ended_at` TIMESTAMP NULL DEFAULT NULL,
    `status` ENUM('scheduled','in_progress','ended','cancelled') DEFAULT 'scheduled',
    `max_participants` INT DEFAULT 50,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `host_id` (`host_id`),
    KEY `room_id` (`room_id`),
    KEY `scheduled_at` (`scheduled_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `meeting_participants` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `meeting_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `joined_at` TIMESTAMP NULL DEFAULT NULL,
    `left_at` TIMESTAMP NULL DEFAULT NULL,
    `status` ENUM('invited','joined','left','declined') DEFAULT 'invited',
    PRIMARY KEY (`id`),
    UNIQUE KEY `meeting_user` (`meeting_id`, `user_id`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DEMOS (Cards)
-- ============================================================

CREATE TABLE `demos` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `short_description` VARCHAR(255) DEFAULT NULL,
    `icon` VARCHAR(50) DEFAULT 'fas fa-cube',
    `image` VARCHAR(255) DEFAULT NULL,
    `url` VARCHAR(255) DEFAULT NULL,
    `category` VARCHAR(100) DEFAULT 'general',
    `visibility` ENUM('public','private','password') DEFAULT 'public',
    `password` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `view_count` INT DEFAULT 0,
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`),
    KEY `category` (`category`),
    KEY `is_active` (`is_active`),
    KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `demo_tech` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `demo_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `sort_order` INT DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `demo_id` (`demo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CONTENT MANAGEMENT
-- ============================================================

CREATE TABLE `content` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(100) NOT NULL,
    `title` VARCHAR(255) DEFAULT NULL,
    `content` LONGTEXT,
    `type` ENUM('text','html','image','json','markdown') DEFAULT 'html',
    `page` VARCHAR(50) DEFAULT 'home',
    `section` VARCHAR(50) DEFAULT 'main',
    `is_active` TINYINT(1) DEFAULT 1,
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `key` (`key`),
    KEY `page` (`page`),
    KEY `section` (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `content_history` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `content_id` INT UNSIGNED NOT NULL,
    `content` LONGTEXT,
    `changed_by` INT UNSIGNED DEFAULT NULL,
    `changed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `content_id` (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SECURITY & AUDIT
-- ============================================================

CREATE TABLE `audit_logs` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `action` VARCHAR(100) NOT NULL,
    `table_name` VARCHAR(100) DEFAULT NULL,
    `record_id` INT UNSIGNED DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT,
    `details` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `action` (`action`),
    KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `login_attempts` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(100) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` TEXT,
    `success` TINYINT(1) DEFAULT 0,
    `attempted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `email` (`email`),
    KEY `ip_address` (`ip_address`),
    KEY `attempted_at` (`attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CONSULTATIONS & ZOOM BOOKINGS
-- ============================================================

CREATE TABLE IF NOT EXISTS `consultations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(30) DEFAULT NULL,
    `company` VARCHAR(100) DEFAULT NULL,
    `service_type` VARCHAR(100) NOT NULL DEFAULT 'Web Architecture',
    `preferred_date` DATE NOT NULL,
    `preferred_time` VARCHAR(20) NOT NULL DEFAULT '10:00 AM',
    `duration_minutes` INT DEFAULT 45,
    `zoom_link` VARCHAR(255) DEFAULT 'https://zoom.us/j/9924883102?pwd=tektrend_consult',
    `meeting_id` VARCHAR(50) DEFAULT '992-488-3102',
    `status` ENUM('pending','confirmed','completed','cancelled') DEFAULT 'pending',
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `email` (`email`),
    KEY `preferred_date` (`preferred_date`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- E-COMMERCE DESIGN MARKETPLACE & AWWWARDS BIDDING
-- ============================================================

CREATE TABLE IF NOT EXISTS `design_items` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL,
    `category` VARCHAR(100) DEFAULT 'SaaS & Enterprise',
    `award_badge` VARCHAR(100) DEFAULT 'Site of the Day',
    `description` TEXT,
    `short_description` VARCHAR(255) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `demo_url` VARCHAR(255) DEFAULT NULL,
    `sale_type` ENUM('bid','buy_now','both') DEFAULT 'both',
    `starting_bid` DECIMAL(12,2) DEFAULT 500.00,
    `current_bid` DECIMAL(12,2) DEFAULT 500.00,
    `buy_now_price` DECIMAL(12,2) DEFAULT 1800.00,
    `bid_end_date` DATETIME DEFAULT NULL,
    `total_bids` INT DEFAULT 0,
    `views_count` INT DEFAULT 0,
    `status` ENUM('active','sold','ended','draft') DEFAULT 'active',
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`),
    KEY `category` (`category`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `design_bids` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `design_item_id` INT UNSIGNED NOT NULL,
    `bidder_name` VARCHAR(100) NOT NULL,
    `bidder_email` VARCHAR(100) NOT NULL,
    `bidder_phone` VARCHAR(30) DEFAULT NULL,
    `bid_amount` DECIMAL(12,2) NOT NULL,
    `message` TEXT,
    `status` ENUM('pending','accepted','outbid','rejected') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `design_item_id` (`design_item_id`),
    KEY `bid_amount` (`bid_amount`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CONTRACTS MANAGEMENT
-- ============================================================

CREATE TABLE IF NOT EXISTS `contracts` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `contract_number` VARCHAR(50) NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `customer_id` INT UNSIGNED DEFAULT NULL,
    `lead_id` INT UNSIGNED DEFAULT NULL,
    `value` DECIMAL(12,2) DEFAULT 0.00,
    `start_date` DATE NOT NULL,
    `end_date` DATE DEFAULT NULL,
    `terms` LONGTEXT,
    `status` ENUM('draft','sent','signed','active','completed','cancelled') DEFAULT 'draft',
    `signed_at` DATETIME DEFAULT NULL,
    `signed_by_name` VARCHAR(100) DEFAULT NULL,
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `contract_number` (`contract_number`),
    KEY `customer_id` (`customer_id`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
