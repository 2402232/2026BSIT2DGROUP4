<?php
session_start();
require_once __DIR__ . '/config/config.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {

    /* ---------- PUBLIC ---------- */
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

    case 'process_login':
        require_once CONTROLLER_PATH . 'AuthController.php';
        (new AuthController())->processLogin();
        break;

    case 'process_signup':
        require_once CONTROLLER_PATH . 'AuthController.php';
        (new AuthController())->processSignup();
        break;

    case 'logout':
        require_once CONTROLLER_PATH . 'AuthController.php';
        (new AuthController())->logout();
        break;

    /* ---------- USER ---------- */
    case 'dashboard':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->dashboard();
        break;

    case 'profile':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->showProfile();
        break;

    case 'update_profile':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->updateProfile();
        break;

    case 'report-system':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->showReportSystem();
        break;

    case 'submit_report':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->submitReport();
        break;

    case 'emergency-dashboard':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->showEmergencyDashboard();
        break;

    case 'emergency-tracking':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->showEmergencyTracking();
        break;

    case 'safety-guides':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->showSafetyGuides();
        break;

    /* ---------- ADMIN ---------- */
    case 'admin-dashboard':
        require_once CONTROLLER_PATH . 'AdminController.php';
        (new AdminController())->adminDashboard();
        break;

    case 'admin-profile':
        require_once CONTROLLER_PATH . 'AdminController.php';
        (new AdminController())->showProfile();
        break;

    case 'admin-update-profile':
        require_once CONTROLLER_PATH . 'AdminController.php';
        (new AdminController())->updateProfile();
        break;

    case 'users':
        require_once CONTROLLER_PATH . 'AdminController.php';
        (new AdminController())->usersNeedingHelp();
        break;

    case 'responders':
        require_once CONTROLLER_PATH . 'AdminController.php';
        (new AdminController())->responders();
        break;

    default:
        require_once CONTROLLER_PATH . 'HomeController.php';
        (new HomeController())->index();
        break;
}
