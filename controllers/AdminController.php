<?php

class AdminController  {
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
            ['action' => 'admin-dashboard','label' => 'Home'],
            ['action' => 'users', 'label' => 'Users Needing Help'],
            ['action' => 'responders', 'label' => 'Responders'],
        ];

        // Footer quick links
        $this->footerLinks = [
            ['action' => 'admin-dashboard','label' => 'Home'],
            ['action' => 'users', 'label' => 'Users Needing Help'],
            ['action' => 'responders', 'label' => 'Responders'],
        ];

        // Footer resources links
        $this->footerResources = [
            ['label' => 'First Aid', 'href' => '#'],
            ['label' => 'Documentation', 'href' => '#'],
            ['label' => 'FAQ', 'href' => '#'],
            ['label' => 'Support', 'href' => '#'],
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
    protected function getSharedData()
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

    public function adminDashboard() {
        $pageTitle = "Admin Dashboard - BuligDiretso";
        // Sample emergency data
        $emergencies = [
            ['id' => 'ER-A373K', 'status' => 'CRITICAL', 'badge' => 'Responding', 'name' => 'Carlos Mendoza', 'type' => 'Medical', 'time' => '2024-02-08 13:45', 'location' => 'Makati City', 'details' => ['Chest pain', 'Difficulty breathing', 'Patient conscious but in severe pain'], 'assign' => 'Assigned to', 'responder' => ': Kim Taehyung'],
            ['id' => 'ER-A373K', 'status' => 'MODERATE', 'badge' => 'Responding', 'name' => 'Carlos Mendoza', 'type' => 'Fire', 'time' => '2024-02-08 13:30', 'location' => 'Quezon City', 'details' => ['Kitchen fire', 'Smoke detected', 'Residents evacuating'], 'assign' => 'Assigned to', 'responder' => ': Janelle Ba-al'],
            ['id' => 'ER-A373K', 'status' => 'CRITICAL', 'badge' => 'Responding', 'name' => 'Carlos Mendoza', 'type' => 'Medical', 'time' => '2024-02-08 13:20', 'location' => 'Manila', 'details' => ['Car accident', 'Multiple injuries', 'Road blocked, require police assist'], 'assign' => 'Assigned to', 'responder' => ': Jeon Jungkook'],
            ['id' => 'ER-A373K', 'status' => 'RESOLVED', 'badge' => 'Pending', 'name' => 'Carlos Mendoza', 'type' => 'Medical', 'time' => '2024-02-08 13:10', 'location' => 'Pasig', 'details' => ['Resolved', 'Patient stabilized'], 'assign' => 'Assign Responders', 'responder' => ''    ]
        ];

        // Shared header/footer data
        extract($this->getSharedData());

        require_once VIEW_PATH . 'admin-dashboard.php';
    }

    public function usersNeedingHelp() {
        $pageTitle = "Users Needing Help - BuligDiretso";

        // Shared header/footer data
        extract($this->getSharedData());

        require_once VIEW_PATH . 'admin-users-needing-help.php';
    }
    public function responders() {
        $pageTitle = "Responders - BuligDiretso";

        // Shared header/footer data
        extract($this->getSharedData());

        require_once VIEW_PATH . 'admin-responders.php';
    }
}
