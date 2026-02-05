<?php

    $isLocal = (
        $_SERVER['HTTP_HOST'] === 'localhost' || 
        strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
        strpos($_SERVER['HTTP_HOST'], 'localhost:') !== false
    );

    // Site settings
    define('SITE_NAME', 'BuligDiretso');

    if ($isLocal) {
        // ========================================
        // LOCAL DEVELOPMENT SETTINGS
        // ========================================
         define('BASE_URL', 'http://localhost/2026BSIT2DGROUP4/BuligDiretso/'); 
       
        
    } else {
        // ========================================
        // HELIOHOST PRODUCTION SETTINGS
        // ========================================
        
        define('BASE_URL', 'https://izia.helioho.st/'); 
    }

    define('VIEW_PATH', __DIR__ . '/../views/');
    define('CONTROLLER_PATH', __DIR__ . '/../controllers/');
    define('MODEL_PATH', __DIR__ . '/../models/');
    define('ASSETS_PATH', BASE_URL . 'assets/');
?>
