<?php
session_start();

// Load config FIRST using real path
require_once __DIR__ . '/config/config.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        require_once CONTROLLER_PATH . 'HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    default:
        require_once CONTROLLER_PATH . 'HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
}
?>