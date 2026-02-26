<?php include 'views/includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/login.css'; ?>">


<div class="main-container">
    <div class="back-to-home">
        <a class="back-link" href="index.php?action=home">← Back to Home</a>
    </div>

    <div class="login-wrapper">
        <div class="login-card">

            <!-- ICON -->
            <div class="user-icon">
                <span>👤</span>
            </div>

            <h2>User Login</h2>
            <p class="subtitle">Access emergency services</p>

            <!-- TABS -->
            <div class="tabs">
                <a class="tab active">Login</a>
                <a class="tab" href="index.php?action=signup">Sign Up</a>
            </div>

            <!-- FLASH MESSAGES -->
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- FORM -->
            <form action="<?php echo BASE_URL; ?>index.php?action=process_login" method="POST">
                <label>Email Address<span>*</span></label>
                <div class="input-group">
                    <span class="icon">✉️</span>
                    <input type="email" name="email" required>
                </div>

                <label>Password<span>*</span></label>
                <div class="input-group">
                    <span class="icon">🔒</span>
                    <input type="password" name="password" required>
                </div>

                <!-- DEMO ACCOUNT -->
                <div class="demo-box">
                    <strong>Demo Account</strong><br>
                    Email: user@gmail.com<br>
                    Password: user123
                </div>

                <!-- OPTIONS -->
                <div class="options">
                    <label class="remember">
                        <input type="checkbox"> Remember me
                    </label>
                    <a href="#" class="forgot">Forgot password?</a>
                </div>

                <button type="submit" class="login-btn">
                    Login to Access Services
                </button>
            </form>

            <div class="divider"></div>

            <p class="signup-text">
                Don’t have an account?
                <a href="index.php?action=signup">Sign up now</a>
            </p>

        </div>
    </div>
</div>
<?php include 'views/includes/footer.php'; ?>

</body>

</html>