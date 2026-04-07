<?php
// Load config FIRST so session is started with secure settings
require_once __DIR__ . '/config/config.php';

require_once CONTROLLER_PATH . 'AuthController.php';
AuthController::autoLoginFromRememberCookie();

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

    case 'users-profile':
        require_once CONTROLLER_PATH . 'UserController.php';
        (new UserController())->showUsersprofile();
        break;

    case 'sms-assign-responder':
        require_once CONTROLLER_PATH . 'SmsController.php';
        (new SmsController())->assignResponder();
        break;

    // ── SMS routes ───────────────────────────────────────────────────
    case 'sms-emergency-received':
        require_once CONTROLLER_PATH . 'SmsController.php';
        (new SmsController())->emergencyReceived();
        break;

    case 'sms-responder-assigned':
        require_once CONTROLLER_PATH . 'SmsController.php';
        (new SmsController())->responderAssigned();
        break;

    case 'sms-resolved':
        require_once CONTROLLER_PATH . 'SmsController.php';
        (new SmsController())->emergencyResolved();
        break;

    case 'sms-broadcast':
        require_once CONTROLLER_PATH . 'SmsController.php';
        (new SmsController())->broadcast();
        break;
    // ────────────────────────────────────────────────────────────────

    case 'admin-sms':
        require_once CONTROLLER_PATH . 'AdminController.php';
        (new AdminController())->smsCenter();
        break;

    default:
        require_once CONTROLLER_PATH . 'HomeController.php';
        (new HomeController())->index();
        break;
    
}