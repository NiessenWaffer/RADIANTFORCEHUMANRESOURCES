-- Radiant Force HR - Complete Database Setup
-- Database: dbronnie

CREATE DATABASE IF NOT EXISTS `dbronnie`;
USE `dbronnie`;

-- --------------------------------------------------------
-- Drop tables in correct order (child tables first)
-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS=0;

-- Drop new feature tables first (they have foreign keys)
DROP TABLE IF EXISTS `interview_schedule`;
DROP TABLE IF EXISTS `email_campaigns`;
DROP TABLE IF EXISTS `email_templates`;
DROP TABLE IF EXISTS `analytics`;
DROP TABLE IF EXISTS `page_analytics`;
DROP TABLE IF EXISTS `referral_program`;
DROP TABLE IF EXISTS `seo_meta`;
DROP TABLE IF EXISTS `social_media`;
DROP TABLE IF EXISTS `blog_posts`;
DROP TABLE IF EXISTS `testimonials`;
DROP TABLE IF EXISTS `faqs`;
DROP TABLE IF EXISTS `contact_inquiries`;
DROP TABLE IF EXISTS `newsletter_subscribers`;

-- Drop original tables
DROP TABLE IF EXISTS `location_applications`;
DROP TABLE IF EXISTS `job_positions`;
DROP TABLE IF EXISTS `locations`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `companies`;
DROP TABLE IF EXISTS `cities`;
DROP TABLE IF EXISTS `job_applications`;
DROP TABLE IF EXISTS `admin_users`;

SET FOREIGN_KEY_CHECKS=1;

-- --------------------------------------------------------
-- Table structure for table `admin_users`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (skip if already exists)
-- Email: radiantforce@gmail.com
-- Password: Youth2025
INSERT IGNORE INTO `admin_users` (`email`, `password`) VALUES
('radiantforce@gmail.com', '$2y$10$s.KAKwJlsjo26rYwVfuhIewZLBqiM3AUVWpL0TVOAcyzDtBR3xmO6');

-- --------------------------------------------------------
-- Table structure for table `job_applications`
-- Updated structure for new application form
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `job_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_preferred` varchar(255) NOT NULL COMMENT 'Position applied for',
  `location` varchar(255) DEFAULT NULL COMMENT 'Location/branch where applicant is applying',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL COMMENT 'Height in centimeters',
  `weight` decimal(5,2) DEFAULT NULL COMMENT 'Weight in kilograms',
  `age` int(3) DEFAULT NULL,
  `additional_message` text DEFAULT NULL COMMENT 'Cover letter or additional message',
  `resume_files` text DEFAULT NULL COMMENT 'JSON array of uploaded file names',
  `status` enum('unread','read','shortlisted','rejected','hired') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  KEY `idx_location` (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Job applications from website form';

-- --------------------------------------------------------
-- Table structure for table `cities`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_name` varchar(255) NOT NULL,
  `island` enum('Luzon','Visayas','Mindanao') NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `city_name` (`city_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample cities (skip if already exists)
INSERT IGNORE INTO `cities` (`city_name`, `island`, `status`) VALUES
('Manila', 'Luzon', 'active'),
('Cebu', 'Visayas', 'active'),
('Davao', 'Mindanao', 'active');

-- --------------------------------------------------------
-- Table structure for table `companies`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_name` (`company_name`),
  KEY `city_id` (`city_id`),
  CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `jobs`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `company` varchar(255) NOT NULL,
  `category` enum('tech','healthcare','finance','marketing','engineering','other') NOT NULL DEFAULT 'other',
  `job_type` enum('full-time','part-time','contract') NOT NULL DEFAULT 'full-time',
  `description` text NOT NULL,
  `requirements` text NOT NULL,
  `salary_range` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `locations`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `city_id` (`city_id`),
  CONSTRAINT `locations_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert one sample location (skip if already exists)
INSERT IGNORE INTO `locations` (`city_id`, `location_name`, `address`, `landmark`, `status`) VALUES
(1, 'SM Manila', 'Arroceros St, Manila', 'Near City Hall', 'active');

-- --------------------------------------------------------
-- Table structure for table `job_positions`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `job_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `position_title` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT NULL,
  `job_type` enum('full-time','part-time','contract','temporary') NOT NULL DEFAULT 'full-time',
  `description` text NOT NULL,
  `requirements` text NOT NULL,
  `salary_range` varchar(100) DEFAULT NULL,
  `slots_available` int(11) DEFAULT 1,
  `status` enum('active','inactive','filled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `job_positions_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------
-- Table structure for table `location_applications`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `location_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_position_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text DEFAULT NULL,
  `experience` text,
  `cover_letter` text,
  `resume_path` varchar(500),
  `status` enum('pending','reviewed','shortlisted','rejected','hired') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `job_position_id` (`job_position_id`),
  CONSTRAINT `location_applications_ibfk_1` FOREIGN KEY (`job_position_id`) REFERENCES `job_positions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Contact Form Inquiries
CREATE TABLE IF NOT EXISTS `contact_inquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `inquiry_type` enum('job_inquiry','service_inquiry','partnership','other') NOT NULL DEFAULT 'other',
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','responded','closed') DEFAULT 'new',
  `response` text DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Newsletter Subscribers
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `subscription_type` enum('jobs','news','all') DEFAULT 'all',
  `status` enum('active','unsubscribed') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Blog/News Posts
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `excerpt` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `author` varchar(255) DEFAULT 'Radiant Force HR',
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `published_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Testimonials/Success Stories
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) NOT NULL,
  `client_title` varchar(255) DEFAULT NULL,
  `client_company` varchar(255) DEFAULT NULL,
  `testimonial_text` text NOT NULL,
  `rating` int(1) DEFAULT 5,
  `image_path` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- FAQ
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `order_by` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- ADMIN & MANAGEMENT TABLES
-- ========================================

-- Email Templates
CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` longtext NOT NULL,
  `template_type` enum('application_received','application_status','job_alert','newsletter','inquiry_response') NOT NULL,
  `variables` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `template_type` (`template_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Email Campaigns
CREATE TABLE IF NOT EXISTS `email_campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_name` varchar(255) NOT NULL,
  `template_id` int(11) NOT NULL,
  `recipient_type` enum('all_subscribers','job_subscribers','news_subscribers','custom') DEFAULT 'all_subscribers',
  `subject` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `status` enum('draft','scheduled','sent','failed') DEFAULT 'draft',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `total_recipients` int(11) DEFAULT 0,
  `sent_count` int(11) DEFAULT 0,
  `failed_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `template_id` (`template_id`),
  KEY `status` (`status`),
  CONSTRAINT `email_campaigns_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `email_templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Interview Schedule
CREATE TABLE IF NOT EXISTS `interview_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `interview_date` datetime NOT NULL,
  `interview_type` enum('phone','video','in-person') DEFAULT 'in-person',
  `interviewer_name` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('scheduled','completed','cancelled','rescheduled') DEFAULT 'scheduled',
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  KEY `interview_date` (`interview_date`),
  CONSTRAINT `interview_schedule_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `location_applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Analytics/Reports
CREATE TABLE IF NOT EXISTS `analytics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metric_type` enum('applications','placements','conversions','page_views','newsletter_signups') NOT NULL,
  `metric_value` int(11) DEFAULT 0,
  `date` date NOT NULL,
  `details` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `metric_date` (`metric_type`, `date`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- SEO & MARKETING TABLES
-- ========================================

-- SEO Meta Tags
CREATE TABLE IF NOT EXISTS `seo_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_url` varchar(500) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `og_title` varchar(255) DEFAULT NULL,
  `og_description` varchar(500) DEFAULT NULL,
  `og_image` varchar(500) DEFAULT NULL,
  `canonical_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_url` (`page_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Social Media Links
CREATE TABLE IF NOT EXISTS `social_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` varchar(100) NOT NULL,
  `url` varchar(500) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `platform` (`platform`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Page Analytics
CREATE TABLE IF NOT EXISTS `page_analytics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_url` varchar(500) NOT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `unique_visitors` int(11) DEFAULT 0,
  `bounce_rate` decimal(5,2) DEFAULT 0.00,
  `avg_time_on_page` int(11) DEFAULT 0,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_date` (`page_url`, `date`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Referral Program
CREATE TABLE IF NOT EXISTS `referral_program` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referrer_name` varchar(255) NOT NULL,
  `referrer_email` varchar(255) NOT NULL,
  `referred_candidate_name` varchar(255) NOT NULL,
  `referred_candidate_email` varchar(255) NOT NULL,
  `position_id` int(11) DEFAULT NULL,
  `status` enum('pending','hired','rejected') DEFAULT 'pending',
  `reward_status` enum('pending','awarded','cancelled') DEFAULT 'pending',
  `reward_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `reward_status` (`reward_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
