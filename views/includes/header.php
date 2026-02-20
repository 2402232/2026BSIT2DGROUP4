<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'BuligDiretso'); ?></title>
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/header.css">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
    /* ===== POPUP OVERLAY ===== */
    .bd-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center}
    .bd-overlay.show{display:flex}
    .bd-popup{background:#fff;border-radius:16px;padding:44px 36px;text-align:center;max-width:380px;width:90%;box-shadow:0 10px 40px rgba(0,0,0,.2);animation:popIn .35s cubic-bezier(.34,1.56,.64,1)}
    @keyframes popIn{from{transform:scale(.7);opacity:0}to{transform:scale(1);opacity:1}}
    .bd-popup-icon{width:72px;height:72px;background:#e8f5e9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;font-size:38px}
    .bd-popup h2{margin:0 0 8px;font-size:1.3rem;color:#1a1a1a}
    .bd-popup p{color:#555;margin:0 0 26px;font-size:.95rem}
    .bd-popup-btn{background:#e44d26;color:#fff;border:none;border-radius:8px;padding:12px 36px;font-size:1rem;cursor:pointer;font-weight:700}
    .bd-popup-btn:hover{background:#c0381c}
    /* ===== NAV AVATAR ===== */
    .nav-avatar{width:34px;height:34px;border-radius:50%;object-fit:cover;border:2px solid #e44d26;vertical-align:middle;margin-right:5px}
    .nav-profile-link{display:flex;align-items:center;gap:6px;text-decoration:none;color:inherit;font-weight:600;font-size:.9rem}
    </style>
</head>
<body>

<?php /* ---- Login success popup ---- */
if (!empty($_SESSION['login_success'])):
    unset($_SESSION['login_success']); ?>
<div class="bd-overlay show" id="bdLoginPopup">
    <div class="bd-popup">
        <div class="bd-popup-icon">✅</div>
        <h2>Logged in Successfully!</h2>
        <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></strong>!</p>
        <button class="bd-popup-btn" onclick="document.getElementById('bdLoginPopup').classList.remove('show')">Continue</button>
    </div>
</div>
<?php endif; ?>

<?php /* ---- Signup success popup ---- */
if (!empty($_SESSION['signup_success'])):
    unset($_SESSION['signup_success']); ?>
<div class="bd-overlay show" id="bdSignupPopup">
    <div class="bd-popup">
        <div class="bd-popup-icon">🎉</div>
        <h2>Account Created!</h2>
        <p>Your account was created successfully. Please log in to continue.</p>
        <button class="bd-popup-btn" onclick="document.getElementById('bdSignupPopup').classList.remove('show')">Login Now</button>
    </div>
</div>
<?php endif; ?>

<header>
    <nav class="navbar">
        <?php
        $isAdmin     = (($_SESSION['user_role'] ?? '') === 'admin');
        $homeAction  = $isAdmin ? 'admin-dashboard' : 'dashboard';
        $profileAct  = $isAdmin ? 'admin-profile'   : 'profile';
        $photoFile   = $_SESSION['user_photo'] ?? '';
        $avatarSrc   = $photoFile
            ? UPLOADS_URL . 'profiles/' . htmlspecialchars($photoFile)
            : ASSETS_PATH . 'images/logo.png';
        ?>
        <a href="<?php echo BASE_URL; ?>index.php?action=<?php echo $homeAction; ?>" class="header-logo">
            <img src="<?php echo ASSETS_PATH; ?>images/logo.png" alt="Logo" class="logo">
            <span>BuligDiretso.</span>
        </a>

        <!-- Desktop nav -->
        <ul class="nav-links">
            <?php foreach ($navItems as $item): ?>
            <li>
                <a href="<?php echo BASE_URL; ?>index.php?action=<?php echo $item['action']; ?>"
                   class="nav-item <?php echo (($currentAction ?? '') === $item['action']) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($item['label']); ?>
                </a>
            </li>
            <?php endforeach; ?>

            <?php if (!empty($_SESSION['user_id'])): ?>
            <li>
                <a href="<?php echo BASE_URL; ?>index.php?action=<?php echo $profileAct; ?>" class="nav-profile-link nav-item">
                    <img src="<?php echo $avatarSrc; ?>" alt="Avatar" class="nav-avatar"
                         onerror="this.src='<?php echo ASSETS_PATH; ?>images/logo.png'">
                    <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Profile'); ?>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>index.php?action=logout" class="nav-item logout-btn">
                    <i class="ri-logout-box-line"></i> Logout
                </a>
            </li>
            <?php endif; ?>
        </ul>

        <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </nav>

    <!-- Mobile menu -->
    <div class="mobile-menu" id="mobileMenu">
        <ul class="mobile-nav-links">
            <?php foreach ($navItems as $item): ?>
            <li>
                <a href="<?php echo BASE_URL; ?>index.php?action=<?php echo $item['action']; ?>"
                   class="mobile-nav-item <?php echo (($currentAction ?? '') === $item['action']) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($item['label']); ?>
                </a>
            </li>
            <?php endforeach; ?>
            <?php if (!empty($_SESSION['user_id'])): ?>
            <li>
                <a href="<?php echo BASE_URL; ?>index.php?action=<?php echo $profileAct; ?>" class="mobile-nav-item">
                    👤 My Profile
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>index.php?action=logout" class="mobile-nav-item logout-mobile">
                    <i class="ri-logout-box-line"></i> Logout
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</header>

<script src="<?php echo ASSETS_PATH; ?>js/header.js"></script>
