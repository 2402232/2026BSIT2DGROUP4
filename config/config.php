<?php

// ── Environment detection ───────────────────────────────────────────────────
$isLocal = (
    $_SERVER['HTTP_HOST'] === 'localhost' ||
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
    strpos($_SERVER['HTTP_HOST'], 'localhost:')  !== false
);

// ── Site ────────────────────────────────────────────────────────────────────
define('SITE_NAME', 'BuligDiretso');

// ── URLs ────────────────────────────────────────────────────────────────────
if ($isLocal) {
    define('BASE_URL', 'http://localhost/BuligDiretso/');
} else {
    define('BASE_URL', 'https://buligdiretso.helioho.st/');
}

// ── Paths ───────────────────────────────────────────────────────────────────
define('ROOT_PATH',       dirname(__DIR__) . '/');
define('VIEW_PATH',       ROOT_PATH . 'views/');
define('CONTROLLER_PATH', ROOT_PATH . 'controllers/');
define('MODEL_PATH',      ROOT_PATH . 'models/');
define('CONFIG_PATH',     ROOT_PATH . 'config/');
define('UPLOAD_PATH',     ROOT_PATH . 'uploads/');
define('UPLOADS_URL',     BASE_URL  . 'uploads/');
define('ASSETS_PATH',     BASE_URL  . 'assets/');

// ── Database ─────────────────────────────────────────────────────────────────
// LOCAL credentials
if ($isLocal) {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'buligdiretso');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    // !! PRODUCTION — filled in automatically by setup.php !!
    // If you need to change these, visit: https://buligdiretso.helioho.st/setup.php
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'izia_db');
    define('DB_USER', 'izia_buligdiretso');
    define('DB_PASS', 'REPLACE_THIS_PASSWORD');   // <-- setup.php replaces this line
}

// ── DB connection singleton ──────────────────────────────────────────────────
function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}
