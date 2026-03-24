<?php

class HomeController {
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
            ['action' => 'login','label' => 'Get Started'],
            ['action' => 'home#features', 'label' => 'Features'],
            ['action' => 'home#howItWorks', 'label' => 'How It Works'],
            ['action' => 'home#footer', 'label' => 'Contact'],
        ];

        // Footer quick links
        $this->footerLinks = [
            ['action' => 'login','label' => 'Get Started'],
            ['action' => 'home#features', 'label' => 'Features'],
            ['action' => 'home#howItWorks', 'label' => 'How It Works'],
            ['action' => 'home#footer', 'label' => 'Contact'],
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

    public function index()
    {
        $pageTitle = "Home";
        $features = [
            [
                'icon' => 'ri-alert-line',
                'title' => 'Quick Emergency Reporting',
                'desc' => 'Report accidents, medical emergencies, disasters, and more with just a few taps. Include photos and detailed descriptions.',
                'link' => 'Learn More',
                'arrow' => 'ri-arrow-right-line'
            ],
            [
                'icon' => 'ri-map-pin-line',
                'title' => 'Auto Location Detection',
                'desc' => 'Your precise location is automatically captured and sent to responders, ensuring the fastest possible response time.',
                'link' => 'Learn More',
                'arrow' => 'ri-arrow-right-line'
            ],
            [
                'icon' => 'ri-map-2-line',
                'title' => 'Real-Time Tracking',
                'desc' => 'Track the status of your emergency in real-time. See when responders are en route and get updates on their estimated arrival time.',
                'link' => 'Learn More',
                'arrow' => 'ri-arrow-right-line'
            ],
            [
                'icon' => 'ri-notification-line',
                'title' => 'Instant Responder Alerts',
                'desc' => 'Emergency alerts are instantly sent to available responders in your area, minimizing response delays.',
                'link' => 'Learn More',
                'arrow' => 'ri-arrow-right-line'
            ],
            [
                'icon' => 'ri-book-open-line',
                'title' => 'First Aid Guides',
                'desc' => 'Access comprehensive first aid guides and step-by-step instructions for handling common emergencies.',
                'link' => 'Learn More',
                'arrow' => 'ri-arrow-right-line'
            ],
            [
                'icon' => 'ri-dashboard-line',
                'title' => 'Priority Dashboard',
                'desc' => 'View and manage all emergency reports in one centralized dashboard for quick access and efficient handling.',
                'link' => 'Learn More',
                'arrow' => 'ri-arrow-right-line'
            ],
        ];

        $how_it_works = [
            [
                'number' => '1',
                'title' => 'Report Emergency',
                'desc' => 'Select the type of emergency, add details, and submit your report. Your location is automatically captured.'
            ],

            [
                'number' => '2',
                'title' => 'Report Assigned',
                'desc' => 'The nearest available responder receives your alert and accepts the emergency within seconds.'
            ],

            [
                'number' => '3',
                'title' => 'Track & Receive Help',
                'desc' => 'Track your responder in real-time, view their ETA, and follow first aid guidance while waiting.'
            ]
        ];

        // Load the home view

        // Shared header/footer data
        extract($this->getSharedData());

       
        require_once VIEW_PATH . 'home.php';
    }
}
