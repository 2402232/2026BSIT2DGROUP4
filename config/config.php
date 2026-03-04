<?php
// config/config.php - Global Configuration File

// ============================================================
// ENVIRONMENT DETECTION
// ============================================================
$isLocal = (
    $_SERVER['HTTP_HOST'] === 'localhost' ||
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
    strpos($_SERVER['HTTP_HOST'], 'localhost:') !== false
);
define('IS_LOCAL', $isLocal);

// ============================================================
// SITE SETTINGS
// ============================================================
define('SITE_NAME', 'BuligDiretso');

// ============================================================
// BASE URL
// ============================================================
if ($isLocal) {
    define('BASE_URL', 'http://localhost/BuligDiretso/');
} else {
    // PRODUCTION (HelioHost)
    define('BASE_URL', 'https://buligdiretso.helioho.st/');
}

// ============================================================
// PATH CONSTANTS
// ============================================================
define('ROOT_PATH',       dirname(__DIR__) . '/');
define('VIEW_PATH',       ROOT_PATH . 'views/');
define('CONTROLLER_PATH', ROOT_PATH . 'controllers/');
define('MODEL_PATH',      ROOT_PATH . 'models/');
define('CONFIG_PATH',     ROOT_PATH . 'config/');

// ============================================================
// PUBLIC / ASSETS
// ============================================================
define('ASSETS_PATH', BASE_URL . 'assets/');

// ============================================================
// DATABASE — MySQL / MariaDB
// ============================================================
if ($isLocal) {
    // --- LOCAL (XAMPP / phpMyAdmin) ---
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'buligdiretso');
    define('DB_USER', 'root');
    define('DB_PASS', '');          // XAMPP default has no password
} else {
    // --- PRODUCTION (HelioHost cPanel) ---
    define('DB_HOST', 'morty.heliohost.org');
    define('DB_NAME', 'izia_buligdiretso');
    define('DB_USER', 'izia_user');
    define('DB_PASS', 'Buligdiretso');
}

define('DB_CHARSET', 'utf8mb4');

// ============================================================
// SESSION & SECURITY SETTINGS
// ============================================================
define('SESSION_TIMEOUT', 3600);      // 1 hour in seconds
define('CSRF_TOKEN_EXPIRE', 1800);    // 30 minutes in seconds
define('ADMIN_EMAIL', 'admin@buligdiretso.com');

// ============================================================
// SESSION INITIALIZATION
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
    
    // Handle session timeout
    if (isset($_SESSION['last_activity']) && 
        (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        session_start();
    }
    $_SESSION['last_activity'] = time();
}

// ============================================================
// UTILITY FUNCTIONS
// ============================================================

/**
 * Get a shared PDO database instance
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST
             . ';dbname='    . DB_NAME
             . ';charset='   . DB_CHARSET;

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            die('Database connection failed. Please try again later.');
        }
    }

    return $pdo;
}

/**
 * Sanitize user input
 */
function sanitize_input($data)
{
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email format
 */
function validate_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Redirect to a URL
 */
function redirect($url)
{
    if (strpos($url, 'http') !== 0) {
        $url = BASE_URL . $url;
    }
    header("Location: " . $url);
    exit;
}

/**
 * Check if user is logged in
 */
function is_logged_in()
{
    return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
}

/**
 * Require user to be logged in
 */
function require_login()
{
    if (!is_logged_in()) {
        redirect('index.php?action=login');
    }
}

/**
 * Hash a password
 */
function hash_password($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify a password against its hash
 */
function verify_password($password, $hash)
{
    return password_verify($password, $hash);
}
