<?php
session_start();

// Load config FIRST using real path
require_once __DIR__ . '/config/config.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {

    case 'home':
        require_once CONTROLLER_PATH . 'HomeController.php';
        (new HomeController())->index();
        break;

    case 'login':
        require_once CONTROLLER_PATH . 'AuthController.php';
        (new AuthController())->showLogin();
        break;

    case 'signup':
        require_once CONTROLLER_PATH . 'AuthController.php';
        (new AuthController())->showSignup();
        break;

    case 'dashboard':
        require_once CONTROLLER_PATH . 'AuthController.php';
        (new AuthController())->showDashboard();
        break;

    case 'admin':
        require_once CONTROLLER_PATH . 'AdminController.php';
        (new AdminController())->adminDashboard();
        break;

    default:
        require_once CONTROLLER_PATH . 'HomeController.php';
        (new HomeController())->index();
        break;
}
