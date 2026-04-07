<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/header.css'; ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/home.css'; ?>">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>

    <header>
        <nav class="navbar">

            <a href="<?php echo BASE_URL; ?>index.php?action=dashboard" class="header-logo">
                <img src="<?php echo ASSETS_PATH . 'images/logo.png'; ?>" alt="Logo" class="logo">
                <span>BuligDiretso.</span>
            </a>

            <!-- Desktop nav -->
            <ul class="nav-links">
                <?php foreach ($navItems as $item): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>index.php?action=<?php echo $item['action']; ?>"
                            class="nav-item <?php echo ($currentAction === $item['action']) ? 'active' : ''; ?>">
                            <?php echo $item['label']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                
                <?php if (isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>index.php?action=logout" class="nav-item logout-btn">
                            <i class="ri-logout-box-line"></i> Logout
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Hamburger button -->
            <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

        </nav>

        <!-- Mobile dropdown -->
        <div class="mobile-menu" id="mobileMenu">
            <ul class="mobile-nav-links">
                <?php foreach ($navItems as $item): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>index.php?action=<?php echo $item['action']; ?>"
                            class="mobile-nav-item <?php echo ($currentAction === $item['action']) ? 'active' : ''; ?>">
                            <?php echo $item['label']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                
                <?php if (isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>index.php?action=logout" class="mobile-nav-item logout-mobile">
                            <i class="ri-logout-box-line"></i> Logout
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <script src="<?php echo ASSETS_PATH . 'js/header.js'; ?>"></script>