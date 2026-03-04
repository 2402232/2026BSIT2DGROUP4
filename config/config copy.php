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
    define('DB_HOST', 'morty.heliohost.org'); // e.g. morty.heliohost.org
    define('DB_NAME', 'izia_buligdiretso'); // e.g. johnny_buligdiretso
    define('DB_USER', 'izia_user');       // e.g. johnny_dbuser
    define('DB_PASS', 'Buligdiretso');
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
        if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

// ===================================
// DETECT ENVIRONMENT
// ===================================
$isLocal = (
    $_SERVER['HTTP_HOST'] === 'localhost' || 
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
    strpos($_SERVER['HTTP_HOST'], 'localhost:') !== false
);

// ===================================
// ENVIRONMENT SETTINGS
// ===================================
if ($isLocal) {
    // LOCAL DEVELOPMENT
    define('BASE_URL', 'http://localhost/2026BSIT2DGROUP4/BuligDiretso/');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'buligdiretso');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    // PRODUCTION (HelioHost)
    define('BASE_URL', 'https://izia.helioho.st/');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'your_db_name');
    define('DB_USER', 'your_username');
    define('DB_PASS', 'your_password');
}

// ===================================
// PATH DEFINITIONS
// ===================================
define('CONFIG_PATH', __DIR__ . '/');
define('MODEL_PATH', __DIR__ . '/../models/');
define('CONTROLLER_PATH', __DIR__ . '/../controllers/');
define('VIEW_PATH', __DIR__ . '/../views/');
define('ASSETS_PATH', BASE_URL . 'assets/');

// ===================================
// SITE SETTINGS
// ===================================
define('SITE_NAME', 'BuligDiretso');
define('ADMIN_EMAIL', 'admin@buligdiretso.com');
define('DB_CHARSET', 'utf8mb4');
define('SESSION_TIMEOUT', 3600);
define('CSRF_TOKEN_EXPIRE', 1800);

// ===================================
// DATABASE CONNECTION
// ===================================
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            die("Database connection failed.");
        }
    }
    
            public static function getInstance() {
                if (self::$instance === null) {
                    self::$instance = new self();
                }
                return self::$instance;
            }
            
            public function getConnection() {
                return $this->connection;
            }
            
            private function __clone() {}
            public function __wakeup() {
                throw new Exception("Cannot unserialize singleton");
            }
        }

        function sanitize_input($data) {
            return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
        }

        function validate_email($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        }

        function redirect($url) {
            if (strpos($url, 'http') !== 0) {
                $url = BASE_URL . $url;
            }
            header("Location: " . $url);
            exit;
        }

        function is_logged_in() {
            return isset($_SESSION['user_id']) && isset($_SESSION['email']);
        }

        function require_login() {
            if (!is_logged_in()) {
                redirect('login.php');
            }
        }

        function hash_password($password) {
            return password_hash($password, PASSWORD_DEFAULT);
        }

        function verify_password($password, $hash) {
            return password_verify($password, $hash);
        }

        // ===================================
        // SESSION CONFIGURATION
        // ===================================
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            
            session_start();
            
            if (isset($_SESSION['last_activity']) && 
                (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
                session_unset();
                session_destroy();
                session_start();
            }
            $_SESSION['last_activity'] = time();
        }
    return $pdo;
}
