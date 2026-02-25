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

    case 'process_login':
        require_once CONTROLLER_PATH . 'AuthController.php';
        $controller = new AuthController();
        $controller->processLogin();
        break;
        
    case 'process_signup':
        require_once CONTROLLER_PATH . 'AuthController.php';
        $controller = new AuthController();
        $controller->processSignup();
        break;

    case 'logout':
        require_once CONTROLLER_PATH . 'AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    // User module
    case 'dashboard':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->dashboard();
        break;

    case 'report-system':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->showReportSystem();
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

    case 'guide-detail':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->showGuideDetail();
        break;

    case 'faq':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->showFaq();
        break;

    case 'contact':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->showContact();
        break;
     
    // Admin module
    case 'admin-dashboard':
        require_once CONTROLLER_PATH . 'AdminController.php';
        (new AdminController())->adminDashboard();
        break;

    case 'users':
        require_once CONTROLLER_PATH . 'AdminController.php';
        (new AdminController())->usersNeedingHelp();
        break;

    case 'responders':
        require_once CONTROLLER_PATH . 'AdminController.php';
        (new AdminController())->responders();
        break;

    case 'responders':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->usersprofile();
        break;

    default:
        require_once CONTROLLER_PATH . 'HomeController.php';
        (new HomeController())->index();
        break;
    
}