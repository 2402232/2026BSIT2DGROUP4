<?php
class AdminController
{
    protected $navItems;
    protected $userMenuItems;
    protected $footerLinks;
    protected $footerResources;
    protected $footerHotline;

    public function __construct()
    {
        $this->navItems = [
            ['action' => 'admin-dashboard', 'label' => 'Home'],
            ['action' => 'users',           'label' => 'Users Needing Help'],
            ['action' => 'responders',      'label' => 'Responders'],
        ];
        $this->footerLinks     = $this->navItems;
        $this->footerResources = [
            ['label' => 'First Aid',     'href' => '#'],
            ['label' => 'Documentation', 'href' => '#'],
            ['label' => 'FAQ',           'href' => '#'],
            ['label' => 'Support',       'href' => '#'],
        ];
        $this->footerHotline = [
            ['label' => 'LDRRMO 0951 682 1504',               'href' => '#'],
            ['label' => 'MHO Isabela 0963 156 6032',          'href' => '#'],
            ['label' => 'ILASMDH 0947 415 4761',              'href' => '#'],
            ['label' => 'PNP Isabela 0999 659 0677',          'href' => '#'],
            ['label' => 'NOCECO 0998 570 2725',               'href' => '#'],
            ['label' => 'BFP (Bureau of Fire) 0970 465 9383', 'href' => '#'],
        ];
    }

    private function requireAdmin(): void
    {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: ' . BASE_URL . 'index.php?action=login');
            exit;
        }
    }

    protected function getSharedData(): array
    {
        return [
            'navItems'        => $this->navItems,
            'userMenuItems'   => $this->userMenuItems ?? [],
            'footerLinks'     => $this->footerLinks,
            'footerResources' => $this->footerResources,
            'footerHotline'   => $this->footerHotline,
            'currentAction'   => $_GET['action'] ?? 'admin-dashboard',
        ];
    }

    /* ------------------------------------------------------------------ */
    public function adminDashboard()
    {
        $this->requireAdmin();
        $pageTitle = 'Admin Dashboard - BuligDiretso';
        $period    = $_GET['period'] ?? 'weekly';
        $pdo       = db();

        // Summary stats
        $totalReports     = (int) $pdo->query('SELECT COUNT(*) FROM emergency_reports')->fetchColumn();
        $activeResponders = (int) $pdo->query("SELECT COUNT(*) FROM responders WHERE status='active'")->fetchColumn();
        $onDuty           = (int) $pdo->query("SELECT COUNT(*) FROM responders WHERE status='responding'")->fetchColumn();
        $resolved         = (int) $pdo->query("SELECT COUNT(*) FROM emergency_reports WHERE status='resolved'")->fetchColumn();

        // Chart data
        if ($period === 'monthly') {
            $chartData = $pdo->query("
                SELECT DATE_FORMAT(created_at,'%b %Y') AS label, COUNT(*) AS total
                FROM emergency_reports
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(created_at,'%Y-%m')
                ORDER BY MIN(created_at)
            ")->fetchAll();
        } else {
            $chartData = $pdo->query("
                SELECT DATE_FORMAT(created_at,'%a') AS label, COUNT(*) AS total
                FROM emergency_reports
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY DATE(created_at)
                ORDER BY DATE(created_at)
            ")->fetchAll();
        }

        // All reports for the table (join users)
        $emergencies = $pdo->query("
            SELECT er.*, u.first_name, u.last_name, u.phone, u.profile_photo
            FROM emergency_reports er
            JOIN users u ON er.user_id = u.id
            ORDER BY er.created_at DESC
        ")->fetchAll();

        extract($this->getSharedData());
        require_once VIEW_PATH . 'admin-dashboard.php';
    }

    /* ------------------------------------------------------------------ */
    public function showProfile()
    {
        $this->requireAdmin();
        $pageTitle = 'Admin Profile - BuligDiretso';
        $stmt      = db()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        extract($this->getSharedData());
        require_once VIEW_PATH . 'admin-profile.php';
    }

    public function updateProfile()
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=admin-profile');
            exit;
        }

        $fname   = trim($_POST['first_name'] ?? '');
        $lname   = trim($_POST['last_name']  ?? '');
        $phone   = trim($_POST['phone']      ?? '');
        $address = trim($_POST['address']    ?? '');
        $pdo     = db();

        $photoFilename = $_SESSION['user_photo'] ?? '';
        if (!empty($_FILES['profile_photo']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (in_array($_FILES['profile_photo']['type'], $allowed)
                && $_FILES['profile_photo']['size'] <= 5 * 1024 * 1024
            ) {
                $ext           = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
                $photoFilename = uniqid('prof_', true) . '.' . strtolower($ext);
                move_uploaded_file($_FILES['profile_photo']['tmp_name'], UPLOAD_PATH . 'profiles/' . $photoFilename);
            }
        }

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
        header('Location: ' . BASE_URL . 'index.php?action=admin-profile');
        exit;
    }

    /* ------------------------------------------------------------------ */
    public function usersNeedingHelp()
    {
        $this->requireAdmin();
        $pageTitle = 'Users Needing Help - BuligDiretso';
        $reports   = db()->query("
            SELECT er.*, u.first_name, u.last_name, u.phone, u.address, u.profile_photo
            FROM emergency_reports er
            JOIN users u ON er.user_id = u.id
            ORDER BY er.created_at DESC
        ")->fetchAll();
        extract($this->getSharedData());
        require_once VIEW_PATH . 'admin-users-needing-help.php';
    }

    /* ------------------------------------------------------------------ */
    public function responders()
    {
        $this->requireAdmin();
        $pageTitle      = 'Responders - BuligDiretso';
        $respondersList = db()->query("
            SELECT r.*, u.first_name, u.last_name, u.email, u.phone, u.profile_photo
            FROM responders r
            JOIN users u ON r.user_id = u.id
            ORDER BY u.first_name
        ")->fetchAll();
        extract($this->getSharedData());
        require_once VIEW_PATH . 'admin-responders.php';
    }
}
