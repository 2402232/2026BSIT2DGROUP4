<?php
require_once MODEL_PATH . 'user.php';

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
     * Get user's emergency reports
     */
    private function getUserEmergencies() {
        try {
            $pdo = db();
            $stmt = $pdo->prepare("
                SELECT id, report_code, emergency_type, severity, status, description, location, created_at
                FROM emergency_reports 
                WHERE user_id = ? 
                ORDER BY created_at DESC
            ");
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching emergencies: " . $e->getMessage());
            return [];
        }
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

        // Fetch emergency data from database
        $emergencies = $this->getUserEmergencies();

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
        $pageTitle = "My Profile - BuligDiretso";

        $user = User::findById((int) $_SESSION['user_id']);
        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header("Location: " . BASE_URL . "index.php?action=dashboard");
            exit();
        }
        $user['joined_date'] = $user['created_at'] ?? $user['updated_at'];
        $user['profile_picture'] = !empty($user['profile_photo']) ? (BASE_URL . $user['profile_photo']) : (ASSETS_PATH . 'images/default-avatar.png');

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
     * Download a safety guide as PDF (FPDF).
     */
    public function downloadSafetyGuidePdf(): void
    {
        $this->requireLogin();
        require_once ROOT_PATH . 'services/SafetyGuidePdfService.php';
        SafetyGuidePdfService::outputDownload($_GET['guide'] ?? '');
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

    /**
     * Show Edit Profile form
     */
    public function showEditProfile() {
        $this->requireLogin();
        $pageTitle = "Edit Profile - BuligDiretso";

        $user = User::findById((int) $_SESSION['user_id']);
        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header("Location: " . BASE_URL . "index.php?action=users-profile");
            exit();
        }
        $user['profile_picture'] = !empty($user['profile_photo'])
            ? (BASE_URL . $user['profile_photo'])
            : (ASSETS_PATH . 'images/default-avatar.png');

        extract($this->getSharedData());
        require_once VIEW_PATH . 'edit-profile.php';
    }

    /**
     * Handle Edit Profile form submission
     */
    public function updateProfile() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "index.php?action=edit-profile");
            exit();
        }

        $user_id    = (int) $_SESSION['user_id'];
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name  = trim($_POST['last_name']  ?? '');
        $phone      = trim($_POST['phone']       ?? '');
        $address    = trim($_POST['address']     ?? '');
        $dob        = trim($_POST['date_of_birth'] ?? '');

        if (!$first_name || !$last_name) {
            $_SESSION['error'] = "First name and last name are required.";
            header("Location: " . BASE_URL . "index.php?action=edit-profile");
            exit();
        }

        // Handle profile picture upload
        $profile_photo = null;
        if (!empty($_FILES['profile_photo']['tmp_name'])) {
            $file     = $_FILES['profile_photo'];
            $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed  = ['jpg','jpeg','png','gif','webp'];

            if (!in_array($ext, $allowed)) {
                $_SESSION['error'] = "Only image files (JPG, PNG, GIF, WEBP) are allowed.";
                header("Location: " . BASE_URL . "index.php?action=edit-profile");
                exit();
            }
            if ($file['size'] > 5 * 1024 * 1024) {
                $_SESSION['error'] = "Profile picture must be under 5 MB.";
                header("Location: " . BASE_URL . "index.php?action=edit-profile");
                exit();
            }

            $upload_dir = __DIR__ . '/../uploads/profile_pictures/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $filename     = uniqid() . '_' . uniqid() . '.' . $ext;
            $dest         = $upload_dir . $filename;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $profile_photo = 'uploads/profile_pictures/' . $filename;
            }
        }

        $ok = User::updateProfile($user_id, [
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'phone'         => $phone,
            'address'       => $address,
            'date_of_birth' => $dob ?: null,
            'profile_photo' => $profile_photo,
        ]);

        if ($ok) {
            // Refresh session name
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name']  = $last_name;
            $_SESSION['success']    = "Profile updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update profile. Please try again.";
        }

        header("Location: " . BASE_URL . "index.php?action=users-profile");
        exit();
    }

    /**
     * Show Change Password form
     */
    public function showChangePassword() {
        $this->requireLogin();
        $pageTitle = "Change Password - BuligDiretso";
        extract($this->getSharedData());
        require_once VIEW_PATH . 'change-password.php';
    }

    /**
     * Handle Change Password form submission
     */
    public function updatePassword() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "index.php?action=change-password");
            exit();
        }

        $user_id      = (int) $_SESSION['user_id'];
        $current_pw   = $_POST['current_password']  ?? '';
        $new_pw       = $_POST['new_password']       ?? '';
        $confirm_pw   = $_POST['confirm_password']   ?? '';

        if (!$current_pw || !$new_pw || !$confirm_pw) {
            $_SESSION['error'] = "All password fields are required.";
            header("Location: " . BASE_URL . "index.php?action=change-password");
            exit();
        }
        if ($new_pw !== $confirm_pw) {
            $_SESSION['error'] = "New password and confirmation do not match.";
            header("Location: " . BASE_URL . "index.php?action=change-password");
            exit();
        }
        if (strlen($new_pw) < 8) {
            $_SESSION['error'] = "New password must be at least 8 characters.";
            header("Location: " . BASE_URL . "index.php?action=change-password");
            exit();
        }

        $user = User::findById($user_id);
        if (!$user || !password_verify($current_pw, $user['password_hash'])) {
            $_SESSION['error'] = "Current password is incorrect.";
            header("Location: " . BASE_URL . "index.php?action=change-password");
            exit();
        }

        $new_hash = password_hash($new_pw, PASSWORD_BCRYPT);
        $ok = User::updatePassword($user_id, $new_hash);

        if ($ok) {
            $_SESSION['success'] = "Password changed successfully!";
            header("Location: " . BASE_URL . "index.php?action=users-profile");
        } else {
            $_SESSION['error'] = "Failed to change password. Please try again.";
            header("Location: " . BASE_URL . "index.php?action=change-password");
        }
        exit();
    }

    // End of UserController class
}