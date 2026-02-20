-- ============================================================
-- BuligDiretso — MySQL / MariaDB (HelioHost)
-- Safe to re-import: uses INSERT IGNORE + truncates seed tables first
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- TABLES (create only if not already there)
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
  `profile_photo` VARCHAR(255) DEFAULT NULL,
  `is_active`     TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `responders` (
  `id`             INT(11)  NOT NULL AUTO_INCREMENT,
  `user_id`        INT(11)  NOT NULL,
  `responder_type` ENUM('Medical','Fire','Police','LDRRMO','BFP','General') NOT NULL,
  `status`         ENUM('active','responding','offline') NOT NULL DEFAULT 'active',
  `assigned_at`    DATETIME DEFAULT NULL,
  `updated_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_resp_user` (`user_id`),
  CONSTRAINT `fk_resp_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `photo`          VARCHAR(255)  DEFAULT NULL,
  `created_at`     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_report_code` (`report_code`),
  KEY `idx_reports_status` (`status`),
  KEY `idx_reports_user`   (`user_id`),
  CONSTRAINT `fk_rpt_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `report_assignments` (
  `id`           INT(11)  NOT NULL AUTO_INCREMENT,
  `report_id`    INT(11)  NOT NULL,
  `responder_id` INT(11)  NOT NULL,
  `assigned_by`  INT(11)  NOT NULL,
  `assigned_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` DATETIME DEFAULT NULL,
  `notes`        TEXT     DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rpt_resp` (`report_id`,`responder_id`),
  CONSTRAINT `fk_asgn_rpt`  FOREIGN KEY (`report_id`)    REFERENCES `emergency_reports` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_asgn_resp` FOREIGN KEY (`responder_id`) REFERENCES `responders`        (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_asgn_adm`  FOREIGN KEY (`assigned_by`)  REFERENCES `users`             (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `emergency_tracking` (
  `id`            INT(11)       NOT NULL AUTO_INCREMENT,
  `report_id`     INT(11)       NOT NULL,
  `responder_id`  INT(11)       DEFAULT NULL,
  `latitude`      DECIMAL(10,7) NOT NULL,
  `longitude`     DECIMAL(10,7) NOT NULL,
  `status_update` VARCHAR(255)  DEFAULT NULL,
  `tracked_at`    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_track_rpt` (`report_id`),
  CONSTRAINT `fk_track_rpt`  FOREIGN KEY (`report_id`)   REFERENCES `emergency_reports` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_track_resp` FOREIGN KEY (`responder_id`) REFERENCES `responders`       (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  CONSTRAINT `fk_guide_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Add profile_photo column to users if it doesn't exist yet
-- ============================================================
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `profile_photo` VARCHAR(255) DEFAULT NULL;

-- Add photo column to emergency_reports if it doesn't exist yet
ALTER TABLE `emergency_reports`
  ADD COLUMN IF NOT EXISTS `photo` VARCHAR(255) DEFAULT NULL;

-- ============================================================
-- CLEAR OLD SEED DATA (safe: only removes seeded rows by email)
-- ============================================================
DELETE FROM `report_assignments` WHERE report_id IN (
  SELECT id FROM `emergency_reports` WHERE report_code IN ('ER-A373K','ER-B812M','ER-C559X','ER-D201R')
);
DELETE FROM `emergency_reports` WHERE report_code IN ('ER-A373K','ER-B812M','ER-C559X','ER-D201R');
DELETE FROM `responders` WHERE user_id IN (
  SELECT id FROM `users` WHERE email IN (
    'admin@gmail.com','user@gmail.com',
    'john.santoso@buligdiretso.ph','mario.reyes@buligdiretso.ph',
    'david.cruz@buligdiretso.ph','sarah.lim@buligdiretso.ph',
    'mike.tan@buligdiretso.ph','john.santos@buligdiretso.ph'
  )
);
DELETE FROM `users` WHERE email IN (
  'admin@gmail.com','user@gmail.com',
  'john.santoso@buligdiretso.ph','mario.reyes@buligdiretso.ph',
  'david.cruz@buligdiretso.ph','sarah.lim@buligdiretso.ph',
  'mike.tan@buligdiretso.ph','john.santos@buligdiretso.ph'
);
DELETE FROM `safety_guides` WHERE created_by IS NULL OR created_by <= 8;

-- ============================================================
-- FRESH SEED DATA
-- admin@gmail.com  → admin123
-- user@gmail.com   → user123
-- responders       → user123
-- ============================================================

INSERT INTO `users`
    (`first_name`,`last_name`,`email`,`phone`,`dob`,`address`,`password_hash`,`role`)
VALUES
('Admin','User',     'admin@gmail.com',             '+63 951 682 1504','1990-01-01','Isabela City, Basilan',    '$2y$10$zCioVZUrUSQj7AE4NH0oRepNvwnQpDWGLkGqL1iPVITyfbCaGpoZe','admin'),
('Juan', 'Dela Cruz','user@gmail.com',              '+63 912 345 6789','1995-06-15','123 Main St, Isabela City','$2y$10$ljAMm79CDEpi4ZjKzwrzeOhj3G.91qaqUBZCyOLpJ0988Z6tAd25e','pwd'),
('John', 'Santoso',  'john.santoso@buligdiretso.ph','+63 917 100 0001','1988-03-10','Isabela City',            '$2y$10$ljAMm79CDEpi4ZjKzwrzeOhj3G.91qaqUBZCyOLpJ0988Z6tAd25e','responder'),
('Mario','Reyes',    'mario.reyes@buligdiretso.ph', '+63 917 100 0002','1985-07-22','Isabela City',            '$2y$10$ljAMm79CDEpi4ZjKzwrzeOhj3G.91qaqUBZCyOLpJ0988Z6tAd25e','responder'),
('David','Cruz',     'david.cruz@buligdiretso.ph',  '+63 917 100 0003','1991-11-05','Isabela City',            '$2y$10$ljAMm79CDEpi4ZjKzwrzeOhj3G.91qaqUBZCyOLpJ0988Z6tAd25e','responder'),
('Sarah','Lim',      'sarah.lim@buligdiretso.ph',   '+63 917 100 0004','1993-02-28','Isabela City',            '$2y$10$ljAMm79CDEpi4ZjKzwrzeOhj3G.91qaqUBZCyOLpJ0988Z6tAd25e','responder'),
('Mike', 'Tan',      'mike.tan@buligdiretso.ph',    '+63 917 100 0005','1987-09-14','Isabela City',            '$2y$10$ljAMm79CDEpi4ZjKzwrzeOhj3G.91qaqUBZCyOLpJ0988Z6tAd25e','responder'),
('John', 'Santos',   'john.santos@buligdiretso.ph', '+63 917 100 0006','1990-12-01','Isabela City',            '$2y$10$ljAMm79CDEpi4ZjKzwrzeOhj3G.91qaqUBZCyOLpJ0988Z6tAd25e','responder');

INSERT INTO `responders` (`user_id`,`responder_type`,`status`)
SELECT id,'Medical','active'   FROM users WHERE email='john.santoso@buligdiretso.ph'
UNION ALL
SELECT id,'Fire','responding'  FROM users WHERE email='mario.reyes@buligdiretso.ph'
UNION ALL
SELECT id,'Police','active'    FROM users WHERE email='david.cruz@buligdiretso.ph'
UNION ALL
SELECT id,'LDRRMO','offline'   FROM users WHERE email='sarah.lim@buligdiretso.ph'
UNION ALL
SELECT id,'General','active'   FROM users WHERE email='mike.tan@buligdiretso.ph'
UNION ALL
SELECT id,'Medical','active'   FROM users WHERE email='john.santos@buligdiretso.ph';

INSERT INTO `emergency_reports`
    (`report_code`,`user_id`,`emergency_type`,`severity`,`status`,`description`,`location`)
SELECT 'ER-A373K',id,'Medical','critical','responding','Chest pain, difficulty breathing. Patient conscious but in severe pain.','Makati City'
FROM users WHERE email='user@gmail.com'
UNION ALL
SELECT 'ER-B812M',id,'Fire','moderate','responding','Kitchen fire, smoke detected. Residents evacuating.','Quezon City'
FROM users WHERE email='user@gmail.com'
UNION ALL
SELECT 'ER-C559X',id,'Medical','critical','responding','Car accident, multiple injuries. Road blocked, require police assist.','Manila'
FROM users WHERE email='user@gmail.com'
UNION ALL
SELECT 'ER-D201R',id,'Medical','minor','resolved','Patient stabilized after minor fall.','Pasig'
FROM users WHERE email='user@gmail.com';

INSERT INTO `report_assignments` (`report_id`,`responder_id`,`assigned_by`)
SELECT er.id, r.id, u.id
FROM emergency_reports er, responders r, users u
WHERE er.report_code='ER-A373K'
  AND r.user_id=(SELECT id FROM users WHERE email='john.santoso@buligdiretso.ph')
  AND u.email='admin@gmail.com';

INSERT INTO `report_assignments` (`report_id`,`responder_id`,`assigned_by`)
SELECT er.id, r.id, u.id
FROM emergency_reports er, responders r, users u
WHERE er.report_code='ER-B812M'
  AND r.user_id=(SELECT id FROM users WHERE email='mario.reyes@buligdiretso.ph')
  AND u.email='admin@gmail.com';

INSERT INTO `report_assignments` (`report_id`,`responder_id`,`assigned_by`)
SELECT er.id, r.id, u.id
FROM emergency_reports er, responders r, users u
WHERE er.report_code='ER-C559X'
  AND r.user_id=(SELECT id FROM users WHERE email='david.cruz@buligdiretso.ph')
  AND u.email='admin@gmail.com';

INSERT INTO `safety_guides` (`title`,`category`,`content`,`created_by`)
SELECT 'Basic First Aid for Medical Emergencies','Medical','Apply pressure to wounds, keep the patient calm, call emergency services immediately.',id FROM users WHERE email='admin@gmail.com'
UNION ALL
SELECT 'Fire Safety & Evacuation Procedures','Fire','Stay low under smoke, use stairways not elevators, meet at the assembly point outside.',id FROM users WHERE email='admin@gmail.com'
UNION ALL
SELECT 'Flood Preparedness Guide','Flood','Prepare a go-bag, avoid flood waters, move to higher ground, monitor PAGASA updates.',id FROM users WHERE email='admin@gmail.com'
UNION ALL
SELECT 'Earthquake Safety Tips','Earthquake','Drop, cover, and hold on. Move away from windows. Check for gas leaks after shaking stops.',id FROM users WHERE email='admin@gmail.com';

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
