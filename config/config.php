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
    // LOCAL
    define('BASE_URL', 'http://localhost/BuligDiretso/');
} else {
    // PRODUCTION (HelioHost)
    define('BASE_URL', 'https://buligdiretso.helioho.st/');
}

// ========================================
// PATH CONSTANTS
// ========================================
define('ROOT_PATH', dirname(__DIR__) . '/');
define('VIEW_PATH', ROOT_PATH . 'views/');
define('CONTROLLER_PATH', ROOT_PATH . 'controllers/');
define('MODEL_PATH', ROOT_PATH . 'models/');
define('CONFIG_PATH', ROOT_PATH . 'config/');

// ========================================
// PUBLIC / ASSETS
// ========================================
define('ASSETS_PATH', BASE_URL . 'assets/');
