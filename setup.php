<?php
/**
 * BuligDiretso — Browser Setup Page
 * ===================================
 * 1. Upload the zip to your server and extract it
 * 2. Visit https://buligdiretso.helioho.st/setup.php
 * 3. Enter your DB password and click the button
 * 4. DELETE this file when done!
 */

$done  = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = trim($_POST['db_host'] ?? 'localhost');
    $name = trim($_POST['db_name'] ?? 'izia_db');
    $user = trim($_POST['db_user'] ?? 'izia_buligdiretso');
    $pass =      $_POST['db_pass'] ?? '';

    // 1. Test connection
    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$name;charset=utf8mb4",
            $user, $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        $error = $e->getMessage();
        goto render;
    }

    // 2. Write config.php with real password
    $cfg = file_get_contents(__DIR__ . '/config/config.php');
    $cfg = preg_replace(
        "/define\('DB_HOST',\s*'[^']*'\);\s*\/\/ <-- setup/",
        '', $cfg
    );
    // Replace the four production defines cleanly
    $cfg = preg_replace("/define\('DB_HOST',\s*'[^']*'\);/",  "define('DB_HOST', '" . addslashes($host) . "');", $cfg);
    $cfg = preg_replace("/define\('DB_NAME',\s*'[^']*'\);/",  "define('DB_NAME', '" . addslashes($name) . "');", $cfg);
    $cfg = preg_replace("/define\('DB_USER',\s*'[^']*'\);/",  "define('DB_USER', '" . addslashes($user) . "');", $cfg);
    // Only replace the PRODUCTION DB_PASS (second occurrence)
    $cfg = preg_replace_callback(
        "/define\('DB_PASS',\s*'[^']*'\);/",
        function($m) use ($pass, &$replaced) {
            if (!$replaced) { $replaced = true; return $m[0]; } // skip local empty pass
            return "define('DB_PASS', '" . addslashes($pass) . "');";
        },
        $cfg
    );
    file_put_contents(__DIR__ . '/config/config.php', $cfg);

    // 3. Build all tables
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
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
      UNIQUE KEY `uq_users_email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `responders` (
      `id`             INT(11)  NOT NULL AUTO_INCREMENT,
      `user_id`        INT(11)  NOT NULL,
      `responder_type` ENUM('Medical','Fire','Police','LDRRMO','BFP','General') NOT NULL,
      `status`         ENUM('active','responding','offline') NOT NULL DEFAULT 'active',
      `assigned_at`    DATETIME DEFAULT NULL,
      `updated_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `uq_resp_user` (`user_id`),
      CONSTRAINT `fk_resp_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `emergency_reports` (
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
      CONSTRAINT `fk_rpt_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `report_assignments` (
      `id`           INT(11)  NOT NULL AUTO_INCREMENT,
      `report_id`    INT(11)  NOT NULL,
      `responder_id` INT(11)  NOT NULL,
      `assigned_by`  INT(11)  NOT NULL,
      `assigned_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `completed_at` DATETIME DEFAULT NULL,
      `notes`        TEXT     DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `uq_rpt_resp` (`report_id`,`responder_id`),
      CONSTRAINT `fk_asgn_rpt`  FOREIGN KEY (`report_id`)    REFERENCES `emergency_reports`(`id`) ON DELETE CASCADE,
      CONSTRAINT `fk_asgn_resp` FOREIGN KEY (`responder_id`) REFERENCES `responders`(`id`)        ON DELETE CASCADE,
      CONSTRAINT `fk_asgn_adm`  FOREIGN KEY (`assigned_by`)  REFERENCES `users`(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `emergency_tracking` (
      `id`            INT(11)       NOT NULL AUTO_INCREMENT,
      `report_id`     INT(11)       NOT NULL,
      `responder_id`  INT(11)       DEFAULT NULL,
      `latitude`      DECIMAL(10,7) NOT NULL,
      `longitude`     DECIMAL(10,7) NOT NULL,
      `status_update` VARCHAR(255)  DEFAULT NULL,
      `tracked_at`    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `safety_guides` (
      `id`           INT(11)      NOT NULL AUTO_INCREMENT,
      `title`        VARCHAR(255) NOT NULL,
      `category`     ENUM('Medical','Fire','Flood','Earthquake','General') NOT NULL,
      `content`      TEXT         NOT NULL,
      `created_by`   INT(11)      DEFAULT NULL,
      `is_published` TINYINT(1)   NOT NULL DEFAULT 1,
      `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // 4. Seed data — clean first, then insert
    $seedEmails = [
        'admin@gmail.com','user@gmail.com',
        'john.santoso@buligdiretso.ph','mario.reyes@buligdiretso.ph',
        'david.cruz@buligdiretso.ph','sarah.lim@buligdiretso.ph',
        'mike.tan@buligdiretso.ph','john.santos@buligdiretso.ph',
    ];
    $in   = implode(',', array_fill(0, count($seedEmails), '?'));
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email IN ($in)");
    $stmt->execute($seedEmails);
    $ids  = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'id');

    if ($ids) {
        $idList = implode(',', array_map('intval', $ids));
        $pdo->exec("DELETE FROM report_assignments WHERE assigned_by IN ($idList)");
        $pdo->exec("DELETE FROM report_assignments WHERE report_id IN (SELECT id FROM emergency_reports WHERE user_id IN ($idList))");
        $pdo->exec("DELETE FROM emergency_reports WHERE user_id IN ($idList)");
        $pdo->exec("DELETE FROM responders WHERE user_id IN ($idList)");
        $pdo->exec("DELETE FROM safety_guides WHERE created_by IN ($idList)");
        $pdo->exec("DELETE FROM users WHERE id IN ($idList)");
    }

    // admin123 bcrypt hash
    $ha = '$2y$10$zCioVZUrUSQj7AE4NH0oRepNvwnQpDWGLkGqL1iPVITyfbCaGpoZe';
    // user123 bcrypt hash
    $hu = '$2y$10$ljAMm79CDEpi4ZjKzwrzeOhj3G.91qaqUBZCyOLpJ0988Z6tAd25e';

    $ui = $pdo->prepare("INSERT INTO users (first_name,last_name,email,phone,dob,address,password_hash,role) VALUES (?,?,?,?,?,?,?,?)");
    foreach ([
        ['Admin','User',   'admin@gmail.com',             '+63 951 682 1504','1990-01-01','Isabela City, Basilan',    $ha,'admin'],
        ['Juan', 'Dela Cruz','user@gmail.com',            '+63 912 345 6789','1995-06-15','123 Main St, Isabela City',$hu,'pwd'],
        ['John', 'Santoso','john.santoso@buligdiretso.ph','+63 917 100 0001','1988-03-10','Isabela City',             $hu,'responder'],
        ['Mario','Reyes',  'mario.reyes@buligdiretso.ph', '+63 917 100 0002','1985-07-22','Isabela City',             $hu,'responder'],
        ['David','Cruz',   'david.cruz@buligdiretso.ph',  '+63 917 100 0003','1991-11-05','Isabela City',             $hu,'responder'],
        ['Sarah','Lim',    'sarah.lim@buligdiretso.ph',   '+63 917 100 0004','1993-02-28','Isabela City',             $hu,'responder'],
        ['Mike', 'Tan',    'mike.tan@buligdiretso.ph',    '+63 917 100 0005','1987-09-14','Isabela City',             $hu,'responder'],
        ['John', 'Santos', 'john.santos@buligdiretso.ph', '+63 917 100 0006','1990-12-01','Isabela City',             $hu,'responder'],
    ] as $u) $ui->execute($u);

    $ri = $pdo->prepare("INSERT INTO responders (user_id,responder_type,status) SELECT id,?,? FROM users WHERE email=?");
    foreach ([
        ['Medical', 'active',    'john.santoso@buligdiretso.ph'],
        ['Fire',    'responding','mario.reyes@buligdiretso.ph'],
        ['Police',  'active',    'david.cruz@buligdiretso.ph'],
        ['LDRRMO',  'offline',   'sarah.lim@buligdiretso.ph'],
        ['General', 'active',    'mike.tan@buligdiretso.ph'],
        ['Medical', 'active',    'john.santos@buligdiretso.ph'],
    ] as $r) $ri->execute($r);

    $ei = $pdo->prepare("INSERT INTO emergency_reports (report_code,user_id,emergency_type,severity,status,description,location) SELECT ?,id,?,?,?,?,? FROM users WHERE email='user@gmail.com'");
    foreach ([
        ['ER-A373K','Medical','critical','responding','Chest pain, difficulty breathing.',  'Makati City'],
        ['ER-B812M','Fire',   'moderate','responding','Kitchen fire, smoke detected.',       'Quezon City'],
        ['ER-C559X','Medical','critical','responding','Car accident, multiple injuries.',    'Manila'],
        ['ER-D201R','Medical','minor',   'resolved',  'Patient stabilized after fall.',      'Pasig'],
    ] as $e) $ei->execute($e);

    $gi = $pdo->prepare("INSERT INTO safety_guides (title,category,content,created_by) SELECT ?,?,?,id FROM users WHERE email='admin@gmail.com'");
    foreach ([
        ['Basic First Aid','Medical',   'Apply pressure to wounds, keep patient calm, call emergency services.'],
        ['Fire Safety',    'Fire',      'Stay low under smoke, use stairways, meet at assembly point outside.'],
        ['Flood Guide',    'Flood',     'Prepare a go-bag, avoid flood waters, move to higher ground.'],
        ['Earthquake Tips','Earthquake','Drop, cover, hold on. Check for gas leaks after shaking stops.'],
    ] as $g) $gi->execute($g);

    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

    // 5. Create upload dirs
    @mkdir(__DIR__ . '/uploads/profiles', 0755, true);
    @mkdir(__DIR__ . '/uploads/reports',  0755, true);

    $done = true;
}

render:
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>BuligDiretso Setup</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:Arial,sans-serif;background:#f0f4f8;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{background:#fff;border-radius:16px;padding:40px;max-width:500px;width:100%;box-shadow:0 4px 30px rgba(0,0,0,.12)}
h1{color:#e44d26;margin-bottom:6px;font-size:1.6rem}
.sub{color:#666;font-size:.9rem;margin-bottom:28px}
label{display:block;font-weight:700;font-size:.85rem;color:#333;margin-bottom:5px}
input{width:100%;padding:11px 14px;border:2px solid #e2e8f0;border-radius:8px;font-size:.95rem;margin-bottom:18px;transition:.2s}
input:focus{outline:none;border-color:#e44d26}
.btn{width:100%;background:#e44d26;color:#fff;border:none;border-radius:8px;padding:15px;font-size:1.05rem;font-weight:700;cursor:pointer;margin-top:4px}
.btn:hover{background:#c0381c}
.success{background:#d4edda;border:2px solid #c3e6cb;border-radius:10px;padding:24px;text-align:center}
.success h2{color:#155724;margin-bottom:12px;font-size:1.3rem}
.success p{color:#155724;margin-bottom:6px;font-size:.92rem}
.success .go{display:inline-block;margin-top:18px;background:#e44d26;color:#fff;padding:13px 32px;border-radius:8px;text-decoration:none;font-weight:700;font-size:1rem}
.err{background:#fde8e8;border:2px solid #f8b4b4;border-radius:10px;padding:18px;color:#9b1c1c;margin-bottom:22px;font-size:.88rem;word-break:break-all}
.warn{background:#fff8e1;border:2px solid #ffe082;border-radius:8px;padding:14px;color:#5d4037;font-size:.82rem;margin-bottom:22px;line-height:1.6}
small{display:block;color:#888;font-size:.76rem;margin-top:-14px;margin-bottom:18px}
</style>
</head>
<body>
<div class="card">
  <h1>🔧 BuligDiretso Setup</h1>
  <p class="sub">One-click database setup. Enter your HelioHost credentials below.</p>

  <?php if ($done): ?>
  <div class="success">
    <h2>✅ Everything is set up!</h2>
    <p>Database connected ✓</p>
    <p>All tables created ✓</p>
    <p>Seed data loaded ✓</p>
    <p>Upload folders created ✓</p>
    <br>
    <p><strong>Login with:</strong></p>
    <p>Admin → admin@gmail.com / <strong>admin123</strong></p>
    <p>User &nbsp; → user@gmail.com &nbsp;/ <strong>user123</strong></p>
    <a class="go" href="index.php?action=login">Go to Login →</a>
    <br><br>
    <p style="color:#b71c1c;font-weight:700;margin-top:10px">⚠️ Delete setup.php from File Manager now!</p>
  </div>

  <?php else: ?>

  <?php if ($error): ?>
  <div class="err">
    <strong>❌ Connection failed:</strong><br><br>
    <?php echo htmlspecialchars($error); ?><br><br>
    <strong>What to do:</strong><br>
    1. Go to HelioHost cPanel → MySQL Databases<br>
    2. Under "Current Users" find <strong>izia_buligdiretso</strong><br>
    3. Click <strong>Change Password</strong> → set a new password<br>
    4. Under "Add User To Database" → select the user + database → Add → Check All → Save<br>
    5. Come back here and enter that exact password
  </div>
  <?php endif; ?>

  <div class="warn">
    📋 Your details from HelioHost:<br>
    <strong>DB Name:</strong> izia_db &nbsp;|&nbsp; <strong>DB User:</strong> izia_buligdiretso<br>
    Only your <strong>password</strong> needs to be filled in below.
  </div>

  <form method="POST">
    <label>Database Host</label>
    <input name="db_host" value="localhost" required>
    <small>Keep as "localhost" for HelioHost</small>

    <label>Database Name</label>
    <input name="db_name" value="izia_db" required>

    <label>Database Username</label>
    <input name="db_user" value="izia_buligdiretso" required>

    <label>Database Password</label>
    <input type="password" name="db_pass" placeholder="Enter your MySQL password here" required autofocus>
    <small>Find/reset this in cPanel → MySQL Databases → Change Password</small>

    <button class="btn" type="submit">🚀 Setup Everything Now</button>
  </form>
  <?php endif; ?>
</div>
</body>
</html>
