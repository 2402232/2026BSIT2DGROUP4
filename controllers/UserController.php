<?php
class UserController
{
    protected $navItems;
    protected $footerLinks;
    protected $footerResources;
    protected $footerHotline;

    public function __construct()
    {
        $this->navItems = [
            ['action'=>'dashboard',          'label'=>'Home'],
            ['action'=>'report-system',      'label'=>'Report'],
            ['action'=>'emergency-dashboard','label'=>'Emergency Dashboard'],
            ['action'=>'emergency-tracking', 'label'=>'Tracking'],
            ['action'=>'safety-guides',      'label'=>'Safety Guides'],
        ];
        $this->footerLinks     = $this->navItems;
        $this->footerResources = [
            ['label'=>'First Aid',    'href'=>'#'],
            ['label'=>'Documentation','href'=>'#'],
            ['label'=>'FAQ',         'href'=>'#'],
            ['label'=>'Support',     'href'=>'#'],
        ];
        $this->footerHotline = [
            ['label'=>'LDRRMO 0951 682 1504',              'href'=>'#'],
            ['label'=>'MHO Isabela 0963 156 6032',         'href'=>'#'],
            ['label'=>'ILASMDH 0947 415 4761',             'href'=>'#'],
            ['label'=>'PNP Isabela 0999 659 0677',         'href'=>'#'],
            ['label'=>'NOCECO 0998 570 2725',              'href'=>'#'],
            ['label'=>'BFP (Bureau of Fire) 0970 465 9383','href'=>'#'],
        ];
    }

    protected function getSharedData(): array
    {
        return [
            'navItems'        => $this->navItems,
            'userMenuItems'   => [],
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
            header('Location: ' . BASE_URL . 'index.php?action=login'); exit;
        }
    }

    public function dashboard()
    {
        $this->requireLogin();
        $pageTitle = 'Dashboard - BuligDiretso';
        extract($this->getSharedData());
        require_once VIEW_PATH . 'dashboard.php';
    }

    public function showProfile()
    {
        $this->requireLogin();
        $pageTitle = 'My Profile - BuligDiretso';
        $stmt = db()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        extract($this->getSharedData());
        require_once VIEW_PATH . 'profile.php';
    }

    public function updateProfile()
    {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=profile'); exit;
        }
        $fname   = trim($_POST['first_name'] ?? '');
        $lname   = trim($_POST['last_name']  ?? '');
        $phone   = trim($_POST['phone']      ?? '');
        $address = trim($_POST['address']    ?? '');
        $pdo     = db();

        $photo = $_SESSION['user_photo'] ?? '';
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $mime    = mime_content_type($_FILES['profile_photo']['tmp_name']);
            $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
            if (in_array($mime, $allowed) && $_FILES['profile_photo']['size'] <= 5*1024*1024) {
                $ext   = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
                $photo = uniqid('prof_', true) . '.' . $ext;
                $dir   = UPLOAD_PATH . 'profiles';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                move_uploaded_file($_FILES['profile_photo']['tmp_name'], $dir . '/' . $photo);
            }
        }
        $extra = ''; $ep = [];
        if (!empty($_POST['new_password']) && strlen($_POST['new_password']) >= 8) {
            $extra = ', password_hash = ?';
            $ep[]  = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        }
        $pdo->prepare("UPDATE users SET first_name=?,last_name=?,phone=?,address=?,profile_photo=?$extra WHERE id=?")
            ->execute(array_merge([$fname,$lname,$phone,$address,$photo], $ep, [$_SESSION['user_id']]));
        $_SESSION['user_name']  = $fname . ' ' . $lname;
        $_SESSION['user_photo'] = $photo;
        $_SESSION['success']    = 'Profile updated!';
        header('Location: ' . BASE_URL . 'index.php?action=profile'); exit;
    }

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
            header('Location: ' . BASE_URL . 'index.php?action=report-system'); exit;
        }
        $type     = $_POST['emergency_type']    ?? 'Other';
        $severity = $_POST['severity']          ?? 'moderate';
        $desc     = trim($_POST['additional_details'] ?? '');
        $location = trim($_POST['location']     ?? 'Unknown');
        $lat      = $_POST['latitude']          ?? null;
        $lng      = $_POST['longitude']         ?? null;

        $validTypes = ['Medical','Fire','Police','Flood','Earthquake','Other'];
        $validSev   = ['critical','moderate','minor'];
        if (!in_array($type,     $validTypes)) $type     = 'Other';
        if (!in_array($severity, $validSev))   $severity = 'moderate';
        if ($location === '') $location = 'Unknown';

        $photo = '';
        if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
            $mime    = mime_content_type($_FILES['file_upload']['tmp_name']);
            $allowed = ['image/jpeg','image/png','image/gif','image/webp','video/mp4','video/quicktime'];
            if (in_array($mime, $allowed) && $_FILES['file_upload']['size'] <= 10*1024*1024) {
                $ext   = strtolower(pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION));
                $photo = uniqid('rpt_', true) . '.' . $ext;
                $dir   = UPLOAD_PATH . 'reports';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                move_uploaded_file($_FILES['file_upload']['tmp_name'], $dir . '/' . $photo);
            }
        }
        $code = 'ER-' . strtoupper(substr(uniqid(), -5));
        try {
            db()->prepare('INSERT INTO emergency_reports (report_code,user_id,emergency_type,severity,status,description,location,latitude,longitude,photo) VALUES (?,?,?,?,?,?,?,?,?,?)')
               ->execute([$code,$_SESSION['user_id'],$type,$severity,'pending',$desc,$location,$lat?:null,$lng?:null,$photo]);
            $_SESSION['success'] = "Report $code submitted!";
            header('Location: ' . BASE_URL . 'index.php?action=emergency-tracking'); exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Failed to submit report: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'index.php?action=report-system'); exit;
        }
    }

    public function showEmergencyDashboard()
    {
        $this->requireLogin();
        $pageTitle   = 'Emergency Dashboard - BuligDiretso';
        $emergencies = [];
        try {
            $stmt = db()->prepare('SELECT * FROM emergency_reports WHERE user_id = ? ORDER BY created_at DESC');
            $stmt->execute([$_SESSION['user_id']]);
            $emergencies = $stmt->fetchAll();
        } catch (PDOException $e) {}
        extract($this->getSharedData());
        require_once VIEW_PATH . 'emergency-dashboard.php';
    }

    public function showEmergencyTracking()
    {
        $this->requireLogin();
        $pageTitle = 'Tracking - BuligDiretso';
        $reports   = [];
        try {
            $stmt = db()->prepare('SELECT * FROM emergency_reports WHERE user_id = ? ORDER BY created_at DESC');
            $stmt->execute([$_SESSION['user_id']]);
            $reports = $stmt->fetchAll();
        } catch (PDOException $e) {}
        extract($this->getSharedData());
        require_once VIEW_PATH . 'emergency-tracking.php';
    }

    public function showSafetyGuides()
    {
        $this->requireLogin();
        $pageTitle = 'Safety Guides - BuligDiretso';
        extract($this->getSharedData());
        require_once VIEW_PATH . 'safety-guides.php';
    }
}
