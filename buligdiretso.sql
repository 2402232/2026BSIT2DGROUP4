-- ============================================================
-- BuligDiretso Database Schema (MySQL / MariaDB - HelioHost)
-- NOTE: Import this while already INSIDE your database.
-- Do NOT run CREATE DATABASE here — HelioHost manages that.
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- ============================================================
-- TABLE: users
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id`            INT(11)      NOT NULL AUTO_INCREMENT,
  `first_name`    VARCHAR(100) NOT NULL,
  `last_name`     VARCHAR(100) NOT NULL,
  `email`         VARCHAR(191) NOT NULL,
  `phone`         VARCHAR(30)  NOT NULL,
  `dob`           DATE         NOT NULL,
  `address`       TEXT         NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role`          ENUM('admin','pwd','responder') NOT NULL,
  `is_active`     TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: responders
-- ============================================================
CREATE TABLE IF NOT EXISTS `responders` (
  `id`             INT(11)  NOT NULL AUTO_INCREMENT,
  `user_id`        INT(11)  NOT NULL,
  `responder_type` ENUM('Medical','Fire','Police','LDRRMO','BFP','General') NOT NULL,
  `status`         ENUM('active','responding','offline') NOT NULL DEFAULT 'active',
  `assigned_at`    DATETIME DEFAULT NULL,
  `updated_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_responders_user` (`user_id`),
  CONSTRAINT `fk_responders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: emergency_reports
-- ============================================================
CREATE TABLE IF NOT EXISTS `emergency_reports` (
  `id`             INT(11)       NOT NULL AUTO_INCREMENT,
  `report_code`    VARCHAR(20)   NOT NULL,
  `user_id`        INT(11)       NOT NULL,
  `emergency_type` ENUM('Medical','Fire','Police','Flood','Earthquake','Other') NOT NULL,
  `severity`       ENUM('critical','moderate','minor') NOT NULL DEFAULT 'moderate',
  `status`         ENUM('pending','responding','resolved','cancelled') NOT NULL DEFAULT 'pending',
  `description`    TEXT          NOT NULL,
  `location`       VARCHAR(255)  NOT NULL,
  `latitude`       DECIMAL(10,7) DEFAULT NULL,
  `longitude`      DECIMAL(10,7) DEFAULT NULL,
  `created_at`     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_report_code` (`report_code`),
  KEY `idx_reports_status` (`status`),
  KEY `idx_reports_user`   (`user_id`),
  CONSTRAINT `fk_reports_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: report_assignments
-- ============================================================
CREATE TABLE IF NOT EXISTS `report_assignments` (
  `id`           INT(11)  NOT NULL AUTO_INCREMENT,
  `report_id`    INT(11)  NOT NULL,
  `responder_id` INT(11)  NOT NULL,
  `assigned_by`  INT(11)  NOT NULL,
  `assigned_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` DATETIME DEFAULT NULL,
  `notes`        TEXT     DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_report_responder` (`report_id`, `responder_id`),
  CONSTRAINT `fk_assign_report`    FOREIGN KEY (`report_id`)    REFERENCES `emergency_reports` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_assign_responder` FOREIGN KEY (`responder_id`) REFERENCES `responders` (`id`)        ON DELETE CASCADE,
  CONSTRAINT `fk_assign_admin`     FOREIGN KEY (`assigned_by`)  REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: emergency_tracking
-- ============================================================
CREATE TABLE IF NOT EXISTS `emergency_tracking` (
  `id`            INT(11)       NOT NULL AUTO_INCREMENT,
  `report_id`     INT(11)       NOT NULL,
  `responder_id`  INT(11)       DEFAULT NULL,
  `latitude`      DECIMAL(10,7) NOT NULL,
  `longitude`     DECIMAL(10,7) NOT NULL,
  `status_update` VARCHAR(255)  DEFAULT NULL,
  `tracked_at`    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tracking_report` (`report_id`),
  CONSTRAINT `fk_tracking_report`    FOREIGN KEY (`report_id`)    REFERENCES `emergency_reports` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tracking_responder` FOREIGN KEY (`responder_id`) REFERENCES `responders` (`id`)        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: safety_guides
-- ============================================================
CREATE TABLE IF NOT EXISTS `safety_guides` (
  `id`           INT(11)      NOT NULL AUTO_INCREMENT,
  `title`        VARCHAR(255) NOT NULL,
  `category`     ENUM('Medical','Fire','Flood','Earthquake','General') NOT NULL,
  `content`      TEXT         NOT NULL,
  `created_by`   INT(11)      DEFAULT NULL,
  `is_published` TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_guides_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED DATA
-- ============================================================

INSERT INTO `users` (`first_name`, `last_name`, `email`, `phone`, `dob`, `address`, `password_hash`, `role`) VALUES
('Admin', 'User',      'admin@gmail.com',              '+63 951 682 1504', '1990-01-01', 'Isabela City, Basilan',     '$2y$10$adminHashPlaceholder001', 'admin'),
('Juan',  'Dela Cruz', 'user@gmail.com',               '+63 912 345 6789', '1995-06-15', '123 Main St, Isabela City', '$2y$10$userHashPlaceholder0001', 'pwd'),
('John',  'Santoso',   'john.santoso@buligdiretso.ph', '+63 917 100 0001', '1988-03-10', 'Isabela City',              '$2y$10$respHashPlaceholder001',  'responder'),
('Mario', 'Reyes',     'mario.reyes@buligdiretso.ph',  '+63 917 100 0002', '1985-07-22', 'Isabela City',              '$2y$10$respHashPlaceholder002',  'responder'),
('David', 'Cruz',      'david.cruz@buligdiretso.ph',   '+63 917 100 0003', '1991-11-05', 'Isabela City',              '$2y$10$respHashPlaceholder003',  'responder'),
('Sarah', 'Lim',       'sarah.lim@buligdiretso.ph',    '+63 917 100 0004', '1993-02-28', 'Isabela City',              '$2y$10$respHashPlaceholder004',  'responder'),
('Mike',  'Tan',       'mike.tan@buligdiretso.ph',     '+63 917 100 0005', '1987-09-14', 'Isabela City',              '$2y$10$respHashPlaceholder005',  'responder'),
('John',  'Santos',    'john.santos@buligdiretso.ph',  '+63 917 100 0006', '1990-12-01', 'Isabela City',              '$2y$10$respHashPlaceholder006',  'responder');

INSERT INTO `responders` (`user_id`, `responder_type`, `status`) VALUES
(3, 'Medical',  'active'),
(4, 'Fire',     'responding'),
(5, 'Police',   'active'),
(6, 'LDRRMO',   'offline'),
(7, 'General',  'active'),
(8, 'Medical',  'active');

INSERT INTO `emergency_reports` (`report_code`, `user_id`, `emergency_type`, `severity`, `status`, `description`, `location`, `latitude`, `longitude`) VALUES
('ER-A373K', 2, 'Medical', 'critical', 'responding', 'Chest pain, difficulty breathing. Patient conscious but in severe pain.', 'Makati City', 14.5547000, 121.0244000),
('ER-B812M', 2, 'Fire',    'moderate', 'responding', 'Kitchen fire, smoke detected. Residents evacuating.',                    'Quezon City', 14.6760000, 121.0437000),
('ER-C559X', 2, 'Medical', 'critical', 'responding', 'Car accident, multiple injuries. Road blocked, require police assist.',  'Manila',      14.5995000, 120.9842000),
('ER-D201R', 2, 'Medical', 'minor',    'resolved',   'Patient stabilized after minor fall.',                                  'Pasig',       14.5764000, 121.0851000);

INSERT INTO `report_assignments` (`report_id`, `responder_id`, `assigned_by`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1);

INSERT INTO `safety_guides` (`title`, `category`, `content`, `created_by`) VALUES
('Basic First Aid for Medical Emergencies', 'Medical',    'Apply pressure to wounds, keep the patient calm, call emergency services immediately.', 1),
('Fire Safety & Evacuation Procedures',     'Fire',       'Stay low under smoke, use stairways not elevators, meet at assembly point outside.',    1),
('Flood Preparedness Guide',                'Flood',      'Prepare go-bag, avoid flood waters, move to higher ground, monitor PAGASA updates.',    1),
('Earthquake Safety Tips',                  'Earthquake', 'Drop, cover, and hold on. Move away from windows. Check for gas leaks after shaking stops.', 1);

COMMIT;
