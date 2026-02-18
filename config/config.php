<?php

// Detect environment
$isLocal = (
    $_SERVER['HTTP_HOST'] === 'localhost' ||
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
    strpos($_SERVER['HTTP_HOST'], 'localhost:') !== false
);

// ========================================
// SITE SETTINGS
// ========================================
define('SITE_NAME', 'BuligDiretso');

// ========================================
// BASE URL
// ========================================
if ($isLocal) {
    define('BASE_URL', 'http://localhost/BuligDiretso/');
} else {
    // PRODUCTION (HelioHost)
    define('BASE_URL', 'https://buligdiretso.helioho.st/');
}

// ========================================
// PATH CONSTANTS
// ========================================
define('ROOT_PATH',       dirname(__DIR__) . '/');
define('VIEW_PATH',       ROOT_PATH . 'views/');
define('CONTROLLER_PATH', ROOT_PATH . 'controllers/');
define('MODEL_PATH',      ROOT_PATH . 'models/');
define('CONFIG_PATH',     ROOT_PATH . 'config/');

// ========================================
// PUBLIC / ASSETS
// ========================================
define('ASSETS_PATH', BASE_URL . 'assets/');

// ========================================
// DATABASE — MySQL / MariaDB
// ========================================
if ($isLocal) {
    // --- LOCAL (XAMPP / phpMyAdmin) ---
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'buligdiretso');
    define('DB_USER', 'root');
    define('DB_PASS', '');          // XAMPP default has no password
} else {
    // --- PRODUCTION (HelioHost cPanel) ---
    // Replace these values with the ones from your HelioHost cPanel → MySQL Databases
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'yourcpaneluser_buligdiretso'); // e.g. johnny_buligdiretso
    define('DB_USER', 'yourcpaneluser_dbuser');       // e.g. johnny_dbuser
    define('DB_PASS', 'your_db_password_here');
}

/**
 * Returns a shared PDO instance (MySQL).
 * Usage anywhere in the project:
 *
 *   $pdo  = db();
 *   $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
 *   $stmt->execute([$email]);
 *   $user = $stmt->fetch();
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST
             . ';dbname='    . DB_NAME
             . ';charset=utf8mb4';

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            // In production, log this instead of displaying raw errors
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    return $pdo;
}
