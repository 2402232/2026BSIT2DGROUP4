-- ============================================================
--  BuligDiretso — Emergency Response System
--  Database: buligdiretso
--  Compatible: MySQL 5.7+ / MariaDB 10.3+
-- ============================================================


-- ============================================================
-- 1. USERS
--    Roles: 'admin' | 'pwd' (user needing help) | 'responder'
-- ============================================================
CREATE TABLE users (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    first_name      VARCHAR(80)     NOT NULL,
    last_name       VARCHAR(80)     NOT NULL,
    email           VARCHAR(180)    NOT NULL,
    phone           VARCHAR(25)     NOT NULL,
    date_of_birth   DATE            DEFAULT NULL,
    address         VARCHAR(255)    NOT NULL,
    role            ENUM('admin','pwd','responder') NOT NULL DEFAULT 'pwd',
    password_hash   VARCHAR(255)    NOT NULL,
    profile_photo   VARCHAR(255)    DEFAULT NULL,
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. RESPONDERS
--    Extra detail for users with role = 'responder'
-- ============================================================
CREATE TABLE responders (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    user_id         INT UNSIGNED    NOT NULL,
    status          ENUM('active','responding','offline') NOT NULL DEFAULT 'offline',
    current_location VARCHAR(255)   DEFAULT NULL,
    total_responses INT UNSIGNED    NOT NULL DEFAULT 0,
    avg_rating      DECIMAL(3,2)    NOT NULL DEFAULT 0.00,    -- e.g. 4.50
    rating_count    INT UNSIGNED    NOT NULL DEFAULT 0,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_responders_user (user_id),
    CONSTRAINT fk_responders_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. EMERGENCIES
--    One row per emergency report submitted by a user
-- ============================================================
CREATE TABLE emergencies (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    reference_code  VARCHAR(20)     NOT NULL,                  -- e.g. ER-A7ZX
    reporter_id     INT UNSIGNED    NOT NULL,                  -- FK → users
    emergency_type  ENUM(
                        'medical',
                        'accident',
                        'animal',
                        'disaster',
                        'fire',
                        'other'
                    )               NOT NULL,
    priority_level  ENUM('critical','high','moderate','low') NOT NULL DEFAULT 'moderate',
    description     TEXT            DEFAULT NULL,
    latitude        DECIMAL(10,7)   DEFAULT NULL,
    longitude       DECIMAL(10,7)   DEFAULT NULL,
    location_text   VARCHAR(255)    NOT NULL DEFAULT 'Unknown',
    photo_path      VARCHAR(255)    DEFAULT NULL,              -- uploaded image
    status          ENUM(
                        'pending',
                        'dispatched',
                        'en_route',
                        'on_scene',
                        'resolved',
                        'cancelled'
                    )               NOT NULL DEFAULT 'pending',
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    resolved_at     DATETIME        DEFAULT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY uq_emergencies_refcode (reference_code),
    KEY idx_emergencies_reporter   (reporter_id),
    KEY idx_emergencies_status     (status),
    KEY idx_emergencies_priority   (priority_level),
    CONSTRAINT fk_emergencies_reporter
        FOREIGN KEY (reporter_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. EMERGENCY ASSIGNMENTS
--    Links one responder to one emergency (many-to-many allowed
--    so multiple responders can be assigned to one emergency)
-- ============================================================
CREATE TABLE emergency_assignments (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    emergency_id    INT UNSIGNED    NOT NULL,
    responder_id    INT UNSIGNED    NOT NULL,                  -- FK → responders
    assigned_at     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    completed_at    DATETIME        DEFAULT NULL,
    rating          TINYINT UNSIGNED DEFAULT NULL,             -- 1-5 star rating
    notes           TEXT            DEFAULT NULL,

    PRIMARY KEY (id),
    KEY idx_ea_emergency  (emergency_id),
    KEY idx_ea_responder  (responder_id),
    CONSTRAINT fk_ea_emergency
        FOREIGN KEY (emergency_id) REFERENCES emergencies(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ea_responder
        FOREIGN KEY (responder_id) REFERENCES responders(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. SAFETY GUIDES
--    Content for the First Aid & Safety Guides section
-- ============================================================
CREATE TABLE safety_guides (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    slug            VARCHAR(100)    NOT NULL,                  -- URL-friendly key
    title           VARCHAR(200)    NOT NULL,
    category        ENUM('medical','disaster','other') NOT NULL DEFAULT 'other',
    icon_class      VARCHAR(80)     DEFAULT NULL,              -- Remix icon class
    read_time       VARCHAR(20)     DEFAULT NULL,              -- e.g. '5 min read'
    content         LONGTEXT        NOT NULL,
    is_published    TINYINT(1)      NOT NULL DEFAULT 1,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_guides_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. FAQS
-- ============================================================
CREATE TABLE faqs (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    question        VARCHAR(500)    NOT NULL,
    answer          TEXT            NOT NULL,
    display_order   SMALLINT        NOT NULL DEFAULT 0,
    is_published    TINYINT(1)      NOT NULL DEFAULT 1,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. CONTACT SUBMISSIONS
--    Messages sent via the Contact & Support page
-- ============================================================
CREATE TABLE contact_submissions (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    user_id         INT UNSIGNED    DEFAULT NULL,              -- NULL if not logged in
    full_name       VARCHAR(160)    NOT NULL,
    email           VARCHAR(180)    NOT NULL,
    phone           VARCHAR(25)     DEFAULT NULL,
    subject         VARCHAR(255)    NOT NULL,
    message         TEXT            NOT NULL,
    is_read         TINYINT(1)      NOT NULL DEFAULT 0,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    KEY idx_contact_user (user_id),
    CONSTRAINT fk_contact_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 8. ACTIVITY LOG  (audit trail)
-- ============================================================
CREATE TABLE activity_logs (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    user_id         INT UNSIGNED    DEFAULT NULL,
    action          VARCHAR(100)    NOT NULL,                  -- e.g. 'login', 'report_submitted'
    target_type     VARCHAR(50)     DEFAULT NULL,              -- e.g. 'emergency', 'responder'
    target_id       INT UNSIGNED    DEFAULT NULL,
    details         TEXT            DEFAULT NULL,
    ip_address      VARCHAR(45)     DEFAULT NULL,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    KEY idx_log_user   (user_id),
    KEY idx_log_action (action),
    CONSTRAINT fk_log_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- ============================================================
--  SEED DATA
-- ============================================================
-- ============================================================

-- ------------------------------------------------------------
-- Users (password_hash is bcrypt of the plain-text shown)
-- admin123  → bcrypt
-- user123   → bcrypt
-- responder123 → bcrypt
-- ------------------------------------------------------------
INSERT INTO users (id, first_name, last_name, email, phone, date_of_birth, address, role, password_hash) VALUES
(1,  'Admin',    'User',     'admin@gmail.com',        '09000000001', '1990-01-01', 'Isabela, Negros Occidental',          'admin',     '$2y$12$KIXzH3A0zVm1gRMRPuFIaOjvBJp5jFr3GWVVSnMBhbPxwPdJV4nOK'),
(2,  'Juan',     'Dela Cruz','user@gmail.com',         '09123456789', '1995-06-15', '123 Main St, Isabela, Negros Occ.',   'pwd',       '$2y$12$ByTGxHSl4E9R7kV2Yv5eCeEW7zDdp0i4yCaWPsUmFqLg5HUOkB.iW'),
(3,  'Maria',    'Santos',   'maria.santos@email.com', '09987654321', '1993-03-22', 'Brgy. Balud, Isabela, Negros Occ.',   'pwd',       '$2y$12$ByTGxHSl4E9R7kV2Yv5eCeEW7zDdp0i4yCaWPsUmFqLg5HUOkB.iW'),
(4,  'John',     'Santoso',  'john.santoso@email.com', '09171112233', '1988-09-10', 'Brgy. Balud, Isabela, Negros Occ.',   'responder', '$2y$12$QpLvLMz6Nk8r2jT5qXbB7.HZ4cXkFDp5G3sOT7MkiK6Dm9Z3REqsO'),
(5,  'Mario',    'Reyes',    'mario.reyes@email.com',  '09229998877', '1985-12-05', 'Brgy. Cabcab, Isabela, Negros Occ.', 'responder', '$2y$12$QpLvLMz6Nk8r2jT5qXbB7.HZ4cXkFDp5G3sOT7MkiK6Dm9Z3REqsO'),
(6,  'Kim',      'Taehyung', 'kim.taehyung@email.com', '09331234567', '1997-02-14', 'Isabela, Negros Occidental',          'responder', '$2y$12$QpLvLMz6Nk8r2jT5qXbB7.HZ4cXkFDp5G3sOT7MkiK6Dm9Z3REqsO'),
(7,  'Janelle',  'Ba-al',    'janelle.baal@email.com', '09441234567', '1996-07-21', 'Brgy. Puso, Isabela, Negros Occ.',   'responder', '$2y$12$QpLvLMz6Nk8r2jT5qXbB7.HZ4cXkFDp5G3sOT7MkiK6Dm9Z3REqsO'),
(8,  'Jeon',     'Jungkook', 'jeon.jungkook@email.com','09551234567', '1997-09-01', 'Brgy. Quintin Remo, Isabela, Neg.',  'responder', '$2y$12$QpLvLMz6Nk8r2jT5qXbB7.HZ4cXkFDp5G3sOT7MkiK6Dm9Z3REqsO'),
(9,  'Carlos',   'Mendoza',  'carlos.mendoza@email.com','09661112233','1992-04-30', '123 Main St, Isabela, Negros Occ.',  'pwd',       '$2y$12$ByTGxHSl4E9R7kV2Yv5eCeEW7zDdp0i4yCaWPsUmFqLg5HUOkB.iW');

-- ------------------------------------------------------------
-- Responders (one row per responder user)
-- ------------------------------------------------------------
INSERT INTO responders (id, user_id, status, current_location, total_responses, avg_rating, rating_count) VALUES
(1, 4, 'active',     'Brgy. Balud, Isabela',            82, 4.50, 82),
(2, 5, 'responding', 'Brgy. Cabcab, Isabela',           65, 4.20, 65),
(3, 6, 'active',     'Isabela Town Proper',             90, 4.80, 90),
(4, 7, 'responding', 'Brgy. Puso, Isabela',             55, 4.30, 55),
(5, 8, 'offline',    'Brgy. Quintin Remo, Isabela',     70, 4.60, 70);

-- ------------------------------------------------------------
-- Emergencies
-- ------------------------------------------------------------
INSERT INTO emergencies
    (id, reference_code, reporter_id, emergency_type, priority_level, description,
     latitude, longitude, location_text, status, created_at, resolved_at)
VALUES
(1,  'ER-A7ZX', 2, 'medical',  'critical', 'Chest pain and difficulty breathing. Patient is conscious but in severe pain.',
     10.2074, 122.9771, 'Brgy. Balud, Isabela, Negros Occ.',   'dispatched', '2024-02-08 13:45:00', NULL),
(2,  'ER-B4FG', 9, 'fire',     'moderate', 'Kitchen fire. Smoke detected. Residents are evacuating.',
     10.2090, 122.9810, 'Brgy. Puso, Isabela, Negros Occ.',    'en_route',   '2024-02-08 13:30:00', NULL),
(3,  'ER-C9KL', 3, 'accident', 'critical', 'Car accident. Multiple injuries. Road is blocked — police assist required.',
     10.2055, 122.9750, 'Isabela Town Proper, Negros Occ.',     'on_scene',   '2024-02-08 13:20:00', NULL),
(4,  'ER-D2MN', 2, 'medical',  'low',      'Minor wound from a fall. Patient is stable.',
     10.2080, 122.9800, 'Brgy. Cabcab, Isabela, Negros Occ.', 'resolved',   '2024-02-08 13:10:00', '2024-02-08 14:00:00'),
(5,  'ER-E5PQ', 9, 'disaster', 'high',     'Flash flood threatening residential area.',
     10.2100, 122.9830, 'Brgy. Quintin Remo, Isabela, Neg.', 'pending',    '2024-02-08 14:00:00', NULL);

-- ------------------------------------------------------------
-- Emergency Assignments
-- ------------------------------------------------------------
INSERT INTO emergency_assignments (emergency_id, responder_id, assigned_at, completed_at, rating, notes) VALUES
(1, 3, '2024-02-08 13:47:00', NULL,                   NULL, 'Kim dispatched to ER-A7ZX'),
(2, 4, '2024-02-08 13:32:00', NULL,                   NULL, 'Janelle en route to ER-B4FG'),
(3, 5, '2024-02-08 13:22:00', NULL,                   NULL, 'Jungkook on scene at ER-C9KL'),
(4, 1, '2024-02-08 13:12:00', '2024-02-08 14:00:00',  5,   'Resolved minor wound — patient sent home');

-- ------------------------------------------------------------
-- Safety Guides
-- ------------------------------------------------------------
INSERT INTO safety_guides (slug, title, category, icon_class, read_time, content) VALUES
('cpr-instructions', 'CPR Instructions', 'medical', 'ri-heart-pulse-line', '5 min read',
 'CPR (Cardiopulmonary Resuscitation) steps:\n1. Call for emergency help immediately.\n2. Place heel of hand on the center of the chest.\n3. Push down hard and fast — at least 2 inches deep, 100-120 compressions per minute.\n4. After 30 compressions, give 2 rescue breaths if trained.\n5. Continue until help arrives or person recovers.'),

('treating-burns', 'Treating Burns', 'medical', 'ri-fire-line', '3 min read',
 'For minor burns:\n1. Cool the burn under cool (not cold) running water for 10-20 minutes.\n2. Do NOT use ice, butter, or toothpaste.\n3. Cover with a sterile non-stick bandage.\n4. Take a mild painkiller if needed.\n5. Seek medical help for large or severe burns.'),

('snake-bite-response', 'Snake Bite Response', 'medical', 'ri-alert-line', '4 min read',
 'Snake bite first aid:\n1. Keep the victim calm and still.\n2. Remove rings or tight clothing near the bite.\n3. Keep the bitten limb below heart level.\n4. Do NOT cut the wound or suck out venom.\n5. Call emergency services immediately and note the snake appearance.'),

('earthquake-safety', 'Earthquake Safety', 'disaster', 'ri-earth-line', '6 min read',
 'During an earthquake:\n1. DROP, COVER, and HOLD ON.\n2. Stay away from windows and falling objects.\n3. If outdoors, move away from buildings and power lines.\n4. After shaking stops, check for injuries and hazards.\n5. Be prepared for aftershocks.'),

('choking-relief', 'Choking Relief', 'medical', 'ri-lungs-line', '2 min read',
 'Heimlich maneuver for choking adults:\n1. Stand behind the person and place one foot forward.\n2. Make a fist and place it just above the navel.\n3. Grasp your fist with the other hand.\n4. Give quick upward thrusts until the object is expelled.\n5. Call emergency services if the person loses consciousness.'),

('flood-evacuation', 'Flood Evacuation', 'disaster', 'ri-flood-line', '5 min read',
 'Flood evacuation tips:\n1. Move immediately to higher ground.\n2. Do NOT walk or drive through floodwater.\n3. Take emergency kit: water, food, medicine, documents.\n4. Follow official evacuation routes.\n5. Stay informed via local emergency broadcasts.');

-- ------------------------------------------------------------
-- FAQs
-- ------------------------------------------------------------
INSERT INTO faqs (question, answer, display_order) VALUES
('How do I report an emergency?',         'Log in to BuligDiretso, go to "Report", choose the emergency type and priority, then submit. Our team will be notified immediately.',  1),
('What areas does BuligDiretso cover?',   'BuligDiretso currently covers the municipality of Isabela, Negros Occidental and its surrounding barangays.',                          2),
('How long does a responder take to arrive?', 'Average response time is 5-15 minutes depending on location and responder availability.',                                         3),
('Can I track my emergency report?',      'Yes. Go to "Tracking" in the app to see real-time status updates for your submitted emergency report.',                               4),
('Who are the responders?',               'Responders are trained volunteers and official personnel coordinated with LDRRMO, PNP, BFP, and MHO of Isabela.',                     5),
('What should I do while waiting for help?', 'Stay calm, keep the affected person still, follow any guidance from the emergency coordinator, and do not leave the scene.',        6);

-- ------------------------------------------------------------
-- Sample Contact Submission
-- ------------------------------------------------------------
INSERT INTO contact_submissions (user_id, full_name, email, phone, subject, message) VALUES
(2, 'Juan Dela Cruz', 'user@gmail.com', '09123456789',
 'Question about response times',
 'I submitted an emergency report but I am unsure how to track it. Can you help?');

-- ------------------------------------------------------------
-- Activity Logs
-- ------------------------------------------------------------
INSERT INTO activity_logs (user_id, action, target_type, target_id, details, ip_address) VALUES
(1, 'login',                NULL,        NULL, 'Admin logged in',                             '127.0.0.1'),
(2, 'login',                NULL,        NULL, 'User logged in',                              '192.168.1.10'),
(2, 'report_submitted',     'emergency', 1,    'Emergency ER-A7ZX submitted (medical/critical)','192.168.1.10'),
(1, 'responder_assigned',   'emergency', 1,    'Responder Kim Taehyung assigned to ER-A7ZX',  '127.0.0.1'),
(9, 'report_submitted',     'emergency', 2,    'Emergency ER-B4FG submitted (fire/moderate)',  '192.168.1.20'),
(3, 'report_submitted',     'emergency', 3,    'Emergency ER-C9KL submitted (accident/critical)','192.168.1.30'),
(2, 'report_submitted',     'emergency', 4,    'Emergency ER-D2MN submitted (medical/low)',   '192.168.1.10'),
(1, 'emergency_resolved',   'emergency', 4,    'Emergency ER-D2MN marked as resolved',        '127.0.0.1');
