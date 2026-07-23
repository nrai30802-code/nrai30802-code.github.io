-- Digital Internship Tracking System Database Schema
-- Database Name: internship_tracker

CREATE DATABASE IF NOT EXISTS `internship_tracker` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `internship_tracker`;

-- 1. Users (Students) Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `roll_no` VARCHAR(50) NOT NULL UNIQUE,
  `department` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(15) NULL,
  `profile_pic` VARCHAR(255) DEFAULT 'assets/images/default-avatar.png',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Admins Table
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Internships Table
CREATE TABLE IF NOT EXISTS `internships` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `company_name` VARCHAR(100) NOT NULL,
  `role` VARCHAR(100) NOT NULL,
  `duration` VARCHAR(50) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `status` ENUM('Applied', 'Shortlisted', 'Interview', 'Selected', 'Ongoing', 'Completed') DEFAULT 'Applied',
  `mentor_name` VARCHAR(100) NOT NULL,
  `stipend` VARCHAR(30) NOT NULL,
  `location` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  `certificate_path` VARCHAR(255) NULL,
  `certificate_status` ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
  `certificate_feedback` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Progress Logs Table (For timeline status changes)
CREATE TABLE IF NOT EXISTS `progress_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `internship_id` INT NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `notes` VARCHAR(255) NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Notifications Table
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `message` VARCHAR(255) NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- SEED DATA
-- --------------------------------------------------------

-- Insert a Default Student: student@tracker.com (Password: student123)
-- BCrypt hash of 'student123': $2y$10$fV3.vO9Gz3L2WpZ071L3H.8W9V5u.n9.o68ZgUj8b5M0mC1mC/z2G
INSERT INTO `users` (`id`, `email`, `password`, `full_name`, `roll_no`, `department`, `phone`, `profile_pic`)
VALUES (1, 'student@tracker.com', '$2y$10$fV3.vO9Gz3L2WpZ071L3H.8W9V5u.n9.o68ZgUj8b5M0mC1mC/z2G', 'Neha Rai', 'MCA-2024-089', 'Computer Applications', '+91 9876543210', 'assets/images/default-avatar.png')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Insert a Default Admin: admin@tracker.com (Password: admin123)
-- BCrypt hash of 'admin123': $2y$10$n7/q4504c/8Hh52a32P7UexF2KqOa4r9K41C34U67eM.WpWw9G/7a
INSERT INTO `admins` (`id`, `email`, `password`, `full_name`)
VALUES (1, 'admin@tracker.com', '$2y$10$n7/q4504c/8Hh52a32P7UexF2KqOa4r9K41C34U67eM.WpWw9G/7a', 'System Admin')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Insert Mock Internships for Neha Rai (Student ID: 1)
-- Internship 1: Completed Web Development Internship
INSERT INTO `internships` (`id`, `student_id`, `company_name`, `role`, `duration`, `start_date`, `end_date`, `status`, `mentor_name`, `stipend`, `location`, `description`, `certificate_path`, `certificate_status`, `certificate_feedback`)
VALUES (1, 1, 'Google', 'Software Engineering Intern', '3 Months', '2026-01-01', '2026-03-31', 'Completed', 'Mr. Amit Sharma', '$50,000 / Month', 'Bangalore (Remote)', 'Assisted in building frontend components for cloud consoles and tracking application performance.', 'uploads/google_cert.pdf', 'Approved', 'Excellent performance. Keep it up!')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Internship 2: Ongoing Data Analyst Internship
INSERT INTO `internships` (`id`, `student_id`, `company_name`, `role`, `duration`, `start_date`, `end_date`, `status`, `mentor_name`, `stipend`, `location`, `description`, `certificate_path`, `certificate_status`)
VALUES (2, 1, 'Microsoft', 'Data Analyst Intern', '6 Months', '2026-05-01', '2026-10-31', 'Ongoing', 'Dr. Rachel Green', '$60,000 / Month', 'Hyderabad (Hybrid)', 'Developing dashboard pipelines in Power BI and writing SQL data extraction jobs.', NULL, 'Pending')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Internship 3: Applied Internship (Interview stage)
INSERT INTO `internships` (`id`, `student_id`, `company_name`, `role`, `duration`, `start_date`, `end_date`, `status`, `mentor_name`, `stipend`, `location`, `description`, `certificate_path`, `certificate_status`)
VALUES (3, 1, 'Amazon', 'Cloud Solutions Architecture Intern', '2 Months', '2026-08-01', '2026-09-30', 'Interview', 'Mr. David Miller', 'Unpaid', 'Delhi NCR', 'Working on setting up EC2 instances and configuring VPC subnets.', NULL, 'Pending')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Seed Progress Logs
INSERT INTO `progress_logs` (`internship_id`, `status`, `notes`) VALUES
(1, 'Applied', 'Applied via referral code.'),
(1, 'Shortlisted', 'Resume shortlisted. Scheduled coding test.'),
(1, 'Interview', 'Passed DSA interview and technical round.'),
(1, 'Selected', 'Received offer letter.'),
(1, 'Ongoing', 'Began onboarding and team alignment.'),
(1, 'Completed', 'Successfully finished project and submitted report.'),
(2, 'Applied', 'Applied on career portal.'),
(2, 'Shortlisted', 'Shortlisted based on SQL assessment.'),
(2, 'Interview', 'Completed panel interview.'),
(2, 'Selected', 'Offer received and accepted.'),
(2, 'Ongoing', 'Working on power BI dashboards.'),
(3, 'Applied', 'Applied on LinkedIn.'),
(3, 'Interview', 'Interview scheduled for 25th July.')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Seed Notifications
INSERT INTO `notifications` (`student_id`, `message`, `is_read`) VALUES
(1, 'Welcome to the Digital Internship Tracking System! Start by adding your active internships.', 1),
(1, 'Your certificate for Google Software Engineering Intern has been APPROVED by the admin.', 0)
ON DUPLICATE KEY UPDATE `id`=`id`;
