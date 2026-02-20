<?php
class UserController
{
    protected $navItems;
    protected $userMenuItems;
    protected $footerLinks;
    protected $footerResources;
    protected $footerHotline;

    public function __construct()
    {
        $this->navItems = [
            ['action' => 'dashboard',          'label' => 'Home'],
            ['action' => 'report-system',       'label' => 'Report'],
            ['action' => 'emergency-dashboard', 'label' => 'Emergency Dashboard'],
            ['action' => 'emergency-tracking',  'label' => 'Tracking'],
            ['action' => 'safety-guides',       'label' => 'Safety Guides'],
        ];
        $this->footerLinks     = $this->navItems;
        $this->footerResources = [
            ['label' => 'First Aid',     'href' => '#'],
            ['label' => 'Documentation', 'href' => '#'],
            ['label' => 'FAQ',           'href' => '#'],
            ['label' => 'Support',       'href' => '#'],
        ];
        $this->footerHotline = [
            ['label' => 'LDRRMO 0951 682 1504',                'href' => '#'],
            ['label' => 'MHO Isabela 0963 156 6032',           'href' => '#'],
            ['label' => 'ILASMDH 0947 415 4761',               'href' => '#'],
            ['label' => 'PNP Isabela 0999 659 0677',           'href' => '#'],
            ['label' => 'NOCECO 0998 570 2725',                'href' => '#'],
            ['label' => 'BFP (Bureau of Fire) 0970 465 9383',  'href' => '#'],
        ];
    }

    protected function getSharedData(): array
    {
        return [
            'navItems'        => $this->navItems,
            'userMenuItems'   => $this->userMenuItems ?? [],
            'footerLinks'     => $this->footerLinks,
            'footerResources' => $this->footerResources,
            'footerHotline'   => $this->footerHotline,
            'currentAction'   => $_GET['action'] ?? 'dashboard',
        ];
    }

    private function requireLogin(): void
    {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to access this page.';
            header('Location: ' . BASE_URL . 'index.php?action=login');
            exit;
        }
    }

    /* ------------------------------------------------------------------ */
    public function dashboard()
    {
        $this->requireLogin();
        $pageTitle = 'Dashboard - BuligDiretso';
        extract($this->getSharedData());
        require_once VIEW_PATH . 'dashboard.php';
    }

    /* ------------------------------------------------------------------ */
    public function showProfile()
    {
        $this->requireLogin();
        $pageTitle = 'My Profile - BuligDiretso';
        $pdo  = db();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        extract($this->getSharedData());
        require_once VIEW_PATH . 'profile.php';
    }

    public function updateProfile()
    {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=profile');
            exit;
        }

        $fname   = trim($_POST['first_name'] ?? '');
        $lname   = trim($_POST['last_name']  ?? '');
        $phone   = trim($_POST['phone']      ?? '');
        $address = trim($_POST['address']    ?? '');
        $pdo     = db();

        // Handle optional photo upload
        $photoFilename = $_SESSION['user_photo'] ?? '';
        if (!empty($_FILES['profile_photo']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (in_array($_FILES['profile_photo']['type'], $allowed)
                && $_FILES['profile_photo']['size'] <= 5 * 1024 * 1024
            ) {
                $ext           = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
                $photoFilename = uniqid('prof_', true) . '.' . strtolower($ext);
                $profileDir = UPLOAD_PATH . 'profiles';
                if (!is_dir($profileDir)) mkdir($profileDir, 0755, true);
                move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profileDir . '/' . $photoFilename);
            }
        }

        // Optional password change
        $extraSql    = '';
        $extraParams = [];
        if (!empty($_POST['new_password']) && strlen($_POST['new_password']) >= 8) {
            $extraSql      = ', password_hash = ?';
            $extraParams[] = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        }

        $params = array_merge([$fname, $lname, $phone, $address, $photoFilename], $extraParams, [$_SESSION['user_id']]);
        $pdo->prepare("UPDATE users SET first_name=?, last_name=?, phone=?, address=?, profile_photo=? $extraSql WHERE id=?")
            ->execute($params);

        $_SESSION['user_name']  = $fname . ' ' . $lname;
        $_SESSION['user_photo'] = $photoFilename;
        $_SESSION['success']    = 'Profile updated successfully!';
        header('Location: ' . BASE_URL . 'index.php?action=profile');
        exit;
    }

    /* ------------------------------------------------------------------ */
    public function showReportSystem()
    {
        $this->requireLogin();
        $pageTitle = 'Report Emergency - BuligDiretso';
        extract($this->getSharedData());
        require_once VIEW_PATH . 'report-system.php';
    }

    public function submitReport()
    {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=report-system');
            exit;
        }

        $type     = $_POST['emergency_type']     ?? 'Other';
        $severity = $_POST['severity']           ?? 'moderate';
        $desc     = trim($_POST['additional_details'] ?? '');
        $location = trim($_POST['location']      ?? 'Unknown');
        $lat      = $_POST['latitude']           ?? null;
        $lng      = $_POST['longitude']          ?? null;

        // Validate type against ENUM
        $validTypes = ['Medical', 'Fire', 'Police', 'Flood', 'Earthquake', 'Other'];
        if (!in_array($type, $validTypes)) $type = 'Other';

        $validSev = ['critical', 'moderate', 'minor'];
        if (!in_array($severity, $validSev)) $severity = 'moderate';

        // Handle report media upload
        $photoFilename = '';
        if (!empty($_FILES['file_upload']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'video/mp4', 'video/quicktime'];
            if (in_array($_FILES['file_upload']['type'], $allowed)
                && $_FILES['file_upload']['size'] <= 10 * 1024 * 1024
            ) {
                $ext           = pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION);
                $photoFilename = uniqid('rpt_', true) . '.' . strtolower($ext);
                $reportsDir = UPLOAD_PATH . 'reports';
                if (!is_dir($reportsDir)) mkdir($reportsDir, 0755, true);
                move_uploaded_file($_FILES['file_upload']['tmp_name'], $reportsDir . '/' . $photoFilename);
            }
        }

        $code = 'ER-' . strtoupper(substr(uniqid(), -5));

        try {
            $pdo = db();
            $pdo->prepare('
                INSERT INTO emergency_reports
                    (report_code, user_id, emergency_type, severity, status, description, location, latitude, longitude, photo)
                VALUES (?, ?, ?, ?, \'pending\', ?, ?, ?, ?, ?)
            ')->execute([$code, $_SESSION['user_id'], $type, $severity, $desc, $location, $lat ?: null, $lng ?: null, $photoFilename]);

            $_SESSION['success'] = "Emergency report $code submitted successfully!";
            header('Location: ' . BASE_URL . 'index.php?action=emergency-tracking');
            exit;

        } catch (PDOException $e) {
            $_SESSION['error'] = 'Failed to submit report — please try again.';
            header('Location: ' . BASE_URL . 'index.php?action=report-system');
            exit;
        }
    }

    /* ------------------------------------------------------------------ */
    public function showEmergencyDashboard()
    {
        $this->requireLogin();
        $pageTitle   = 'Emergency Dashboard - BuligDiretso';
        $emergencies = [];
        try {
            $stmt = db()->prepare('SELECT * FROM emergency_reports WHERE user_id = ? ORDER BY created_at DESC');
            $stmt->execute([$_SESSION['user_id']]);
            $emergencies = $stmt->fetchAll();
        } catch (PDOException $e) { /* leave empty */ }
        extract($this->getSharedData());
        require_once VIEW_PATH . 'emergency-dashboard.php';
    }

    /* ------------------------------------------------------------------ */
    public function showEmergencyTracking()
    {
        $this->requireLogin();
        $pageTitle = 'Emergency Tracking - BuligDiretso';
        $reports   = [];
        try {
            $stmt = db()->prepare('SELECT * FROM emergency_reports WHERE user_id = ? ORDER BY created_at DESC');
            $stmt->execute([$_SESSION['user_id']]);
            $reports = $stmt->fetchAll();
        } catch (PDOException $e) { /* leave empty */ }
        extract($this->getSharedData());
        require_once VIEW_PATH . 'emergency-tracking.php';
    }

    /* ------------------------------------------------------------------ */
    public function showSafetyGuides()
    {
        $this->requireLogin();
        $pageTitle = 'Safety Guides - BuligDiretso';
        extract($this->getSharedData());
        require_once VIEW_PATH . 'safety-guides.php';
    }
}
