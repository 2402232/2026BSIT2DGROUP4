<?php 
    class UserController {

    // Shared data for header and footer
    protected $navItems;
    protected $userMenuItems;
    protected $footerLinks;
    protected $footerResources;
    protected $footerHotline;

    /**
     * Constructor - Initialize shared data
     */
    public function __construct()
    {
        $this->initSharedData();
    }

    /**
     * Initialize shared header/footer data
     */
    private function initSharedData()
    {
        // Header navigation items
        $this->navItems = [
            ['action' => 'dashboard', 'label' => 'Home'],
            ['action' => 'report-system', 'label' => 'Report'],
            ['action' => 'emergency-dashboard','label' => 'Emergency Dashboard'],
            ['action' => 'emergency-tracking', 'label' => 'Tracking'],
            ['action' => 'safety-guides', 'label' => 'Safety Guides'],
        ];

        // Footer quick links
        $this->footerLinks = [
            ['action' => 'dashboard', 'label' => 'Home'],
            ['action' => 'report-system', 'label' => 'Report'],
            ['action' => 'emergency-dashboard','label' => 'Emergency Dashboard'],
            ['action' => 'emergency-tracking', 'label' => 'Tracking'],
            ['action' => 'safety-guides', 'label' => 'Safety Guides'],
        ];

        // Footer resources links
        $this->footerResources = [
            ['label' => 'Safety Guides', 'href' => 'safety-guides'],
            ['label' => 'FAQ', 'href' => 'faq'],
            ['label' => 'Contact & Support', 'href' => 'contact'],
        ];

        // Footer social links
        $this->footerHotline = [
            ['label' => 'LDRRMO 0951 682 1504', 'href' => '#'],
            ['label' => 'MHO Isabela 0963 156 6032', 'href' => '#'],
            ['label' => 'ILASMDH 0947 415 4761', 'href' => '#'],
            ['label' => 'PNP Isabela 0999 659 0677', 'href' => '#'],
            ['label' => 'NOCECO 0998 570 2725', 'href' => '#'],
            ['label' => 'BFP (Bureau of Fire Protection) 0970 465 9383', 'href' => '#'],
        ];
    }

    /**
     * Get shared data for views
     */
    private function getSharedData()
    {
        return [
            'navItems' => $this->navItems,
            'userMenuItems' => $this->userMenuItems,
            'footerLinks' => $this->footerLinks,
            'footerResources' => $this->footerResources,
            'footerHotline' => $this->footerHotline,
            'currentAction' => $_GET['action'] ?? 'dashboard',
        ];
    }
        /**
     * Check if user is logged in
     */
    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Please login to access this page.";
            header("Location: " . BASE_URL . "index.php?action=login");
            exit();
        }
    }
    
    /**
     * User Dashboard
     */
    public function dashboard() {
        $this->requireLogin();
        $pageTitle = "Dashboard - Bulig Diretso";

        // Shared header/footer data
        extract($this->getSharedData());
        
        require_once VIEW_PATH . 'dashboard.php';
    }
    
    /**
     * Report System
     */
    public function showReportSystem() {
        $this->requireLogin();
        $pageTitle = "Report System - Bulig Diretso";

        // Shared header/footer data
        extract($this->getSharedData());

        require_once VIEW_PATH . 'report-system.php';

    }

    /**
     * Emergency Dashboard
     */
    public function showEmergencyDashboard() {
        $this->requireLogin();
        $pageTitle = "Emergency Dashboard - Bulig Diretso";

        // Shared header/footer data
        extract($this->getSharedData());

        require_once VIEW_PATH . 'emergency-dashboard.php';

    }
    /**
     * Tracking
     */
    public function showEmergencyTracking() {
        $this->requireLogin();
        $pageTitle = "Emergency Tracking - Bulig Diretso";

        // Shared header/footer data
        extract($this->getSharedData());

        require_once VIEW_PATH . 'emergency-tracking.php';

    }
    /**
     * Safety Guides
     */
    public function showSafetyGuides() {
        $this->requireLogin();
        $pageTitle = "Safety Guides - Bulig Diretso";

        // Shared header/footer data
        extract($this->getSharedData());

        require_once VIEW_PATH . 'safety-guides.php';
    }

    public function showUsersprofile() {
        $this->requireLogin();
        $pageTitle = "Users-profile - Bulig Diretso";

        // Shared header/footer data
        extract($this->getSharedData());

        require_once VIEW_PATH . 'users-profile.php';
    }


    /**
     * Guide Detail
     */
    public function showGuideDetail() {
        $this->requireLogin();
        $pageTitle = "Safety Guide - Bulig Diretso";
        extract($this->getSharedData());
        require_once VIEW_PATH . 'guide-detail.php';
    }

    /**
     * FAQ
     */
    public function showFaq() {
        $this->requireLogin();
        $pageTitle = "FAQ - Bulig Diretso";
        extract($this->getSharedData());
        require_once VIEW_PATH . 'faq.php';
    }

    /**
     * Contact & Support
     */
    public function showContact() {
        $this->requireLogin();
        $pageTitle = "Contact & Support - Bulig Diretso";
        extract($this->getSharedData());
        require_once VIEW_PATH . 'contact.php';
    }

    // End of UserController class
}