<?php
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'emergency_contact.php';

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
        $pageTitle = "My Profile - BuligDiretso";

        $user = User::findById((int) $_SESSION['user_id']);
        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header("Location: " . BASE_URL . "index.php?action=dashboard");
            exit();
        }
        $user['joined_date'] = $user['created_at'] ?? $user['updated_at'];
        $user['profile_picture'] = !empty($user['profile_photo']) ? (BASE_URL . $user['profile_photo']) : (ASSETS_PATH . 'images/default-avatar.png');
        $emergencyContacts = EmergencyContact::getByUserId((int) $_SESSION['user_id']);
        $contactLimit = 5;

        extract($this->getSharedData());
        require_once VIEW_PATH . 'users-profile.php';
    }

    public function addEmergencyContact() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . BASE_URL . "index.php?action=users-profile");
            exit();
        }

        $this->requireLogin();
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $name = trim($_POST['contact_name'] ?? '');
        $relationship = trim($_POST['relationship'] ?? '');
        if ($relationship === '__other__') {
            $relationship = trim($_POST['relationship_other'] ?? '');
        }
        $phone = trim($_POST['contact_phone'] ?? '');

        if ($name === '' || $relationship === '' || $phone === '') {
            $_SESSION['contact_error'] = "All contact fields are required.";
            header("Location: " . BASE_URL . "index.php?action=users-profile");
            exit();
        }

        if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
            $_SESSION['contact_error'] = "Please enter a valid contact phone number.";
            header("Location: " . BASE_URL . "index.php?action=users-profile");
            exit();
        }

        if (EmergencyContact::countByUserId($userId) >= 5) {
            $_SESSION['contact_error'] = "Maximum of 5 emergency contacts reached.";
            header("Location: " . BASE_URL . "index.php?action=users-profile");
            exit();
        }

        if (EmergencyContact::create($userId, $name, $relationship, $phone)) {
            $_SESSION['contact_success'] = "Emergency contact added.";
        } else {
            $_SESSION['contact_error'] = "Unable to add contact. Please try again.";
        }

        header("Location: " . BASE_URL . "index.php?action=users-profile");
        exit();
    }

    public function deleteEmergencyContact() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . BASE_URL . "index.php?action=users-profile");
            exit();
        }

        $this->requireLogin();
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $contactId = (int) ($_POST['contact_id'] ?? 0);

        if ($contactId <= 0) {
            $_SESSION['contact_error'] = "Invalid contact selected.";
            header("Location: " . BASE_URL . "index.php?action=users-profile");
            exit();
        }

        if (EmergencyContact::deleteForUser($contactId, $userId)) {
            $_SESSION['contact_success'] = "Emergency contact removed.";
        } else {
            $_SESSION['contact_error'] = "Unable to remove contact.";
        }

        header("Location: " . BASE_URL . "index.php?action=users-profile");
        exit();
    }

    /**
     * Show the profile edit page (used by "Edit Profile" button).
     */
    public function showEditProfile() {
        $this->requireLogin();
        $pageTitle = "Edit Profile - BuligDiretso";

        $user = User::findById((int) $_SESSION['user_id']);
        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header("Location: " . BASE_URL . "index.php?action=dashboard");
            exit();
        }

        $user['profile_picture'] = !empty($user['profile_photo'])
            ? (BASE_URL . $user['profile_photo'])
            : (ASSETS_PATH . 'images/default-avatar.png');

        extract($this->getSharedData());
        require_once VIEW_PATH . 'profile.php';
    }

    /**
     * Handle profile updates (name/phone/address/password + optional photo upload).
     */
    public function updateProfile() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . BASE_URL . "index.php?action=edit-profile");
            exit();
        }

        $this->requireLogin();
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        if ($userId <= 0) {
            $_SESSION['error'] = "Please login to update your profile.";
            header("Location: " . BASE_URL . "index.php?action=login");
            exit();
        }

        $fname   = trim($_POST['first_name'] ?? '');
        $lname   = trim($_POST['last_name'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';

        $errors = [];
        if (empty($fname))   $errors[] = "First name is required.";
        if (empty($lname))   $errors[] = "Last name is required.";
        if (empty($address)) $errors[] = "Address is required.";

        if (empty($phone)) {
            $errors[] = "Phone number is required.";
        } elseif (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
            $errors[] = "Please enter a valid phone number.";
        }

        $passwordHash = null;
        $newPassword = trim($newPassword);
        if ($newPassword !== '') {
            if (strlen($newPassword) < 8) {
                $errors[] = "New password must be at least 8 characters.";
            } else {
                $passwordHash = hash_password($newPassword);
            }
        }

        // Optional: profile photo upload
        $profilePhotoPath = null; // relative path stored in DB
        if (isset($_FILES['profile_photo']) && is_array($_FILES['profile_photo'])) {
            $file = $_FILES['profile_photo'];
            if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK && !empty($file['name'])) {
                if (!empty($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (!in_array($ext, $allowed, true)) {
                        $errors[] = "Invalid photo format. Allowed: jpg, png, gif, webp.";
                    } else {
                        $uploadDir = ROOT_PATH . 'uploads' . DIRECTORY_SEPARATOR . 'profiles' . DIRECTORY_SEPARATOR;
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }

                        $fileName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                        $targetPath = $uploadDir . $fileName;

                        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                            $profilePhotoPath = 'uploads/profiles/' . $fileName;
                        } else {
                            $errors[] = "Failed to upload profile photo.";
                        }
                    }
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = $errors[0];
            header("Location: " . BASE_URL . "index.php?action=edit-profile");
            exit();
        }

        // Build update query
        $pdo = db();
        $sql = "UPDATE users SET first_name = ?, last_name = ?, phone = ?, address = ?";
        $params = [$fname, $lname, $phone, $address];

        if ($passwordHash !== null) {
            $sql .= ", password_hash = ?";
            $params[] = $passwordHash;
        }

        if ($profilePhotoPath !== null) {
            $sql .= ", profile_photo = ?";
            $params[] = $profilePhotoPath;
        }

        $sql .= " WHERE id = ? LIMIT 1";
        $params[] = $userId;

        $ok = $pdo->prepare($sql)->execute($params);
        if ($ok) {
            $_SESSION['success'] = "Profile updated successfully!";
            header("Location: " . BASE_URL . "index.php?action=users-profile");
            exit();
        }

        $_SESSION['error'] = "Update failed. Please try again.";
        header("Location: " . BASE_URL . "index.php?action=edit-profile");
        exit();
    }

    /**
     * Keep button working: reuse edit profile page.
     */
    public function showChangePassword() {
        $this->showEditProfile();
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