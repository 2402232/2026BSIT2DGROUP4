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
    
            <h2>Create Account</h2>
            <p class="subtitle">Join our emergency response community</p>
    
            <!-- TABS -->
            <div class="tabs">
                <a class="tab" href="index.php?action=login">Login</a>
                <a class="tab active">Sign Up</a>
            </div>
    
            <!-- FLASH MESSAGES -->
            <?php
                $old    = $_SESSION['signup_old']    ?? [];
                $errors = $_SESSION['signup_errors'] ?? [];
                unset($_SESSION['signup_old'], $_SESSION['signup_errors']);
            ?>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul style="margin:0;padding-left:18px;">
                        <?php foreach ($errors as $e): ?>
                            <li><?php echo htmlspecialchars($e); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- FORM -->
            <form action="<?php echo BASE_URL; ?>index.php?action=process_signup" method="POST">
    
                <label>First Name<span>*</span></label>
                <div class="input-group">
                    <span class="icon">👤</span>
                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($old['first_name'] ?? ''); ?>" required>
                </div>
    
                <label>Last Name<span>*</span></label>
                <div class="input-group">
                    <span class="icon">👤</span>
                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($old['last_name'] ?? ''); ?>" required>
                </div>
    
                <label>Phone Number<span>*</span></label>
                <div class="input-group">
                    <span class="icon">📞</span>
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>" required>
                </div>
                <small class="hint">Used for emergency notification</small>
    
                <label>Date of Birth<span>*</span></label>
                <div class="input-group">
                    <span class="icon">📅</span>
                    <input type="date" name="dob" value="<?php echo htmlspecialchars($old['dob'] ?? ''); ?>" required>
                </div>
    
                <label>Address<span>*</span></label>
                <div class="input-group">
                    <span class="icon">🏠</span>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($old['address'] ?? ''); ?>" required>
                </div>
    
                <label>Email Address<span>*</span></label>
                <div class="input-group">
                    <span class="icon">✉️</span>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
                </div>
    
                
                <label for="role">Role</label>
                <select id="role" name="role" class="input-group" required>
                    <option value="" disabled <?php echo empty($old['role']) ? 'selected' : ''; ?>>Select Role</option>
                    <option value="pwd"   <?php echo (($old['role'] ?? '') === 'pwd')   ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo (($old['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>Administrator</option>
                </select>
                
    
                <label>Password<span>*</span></label>
                <div class="input-group">
                    <span class="icon">🔒</span>
                    <input type="password" name="password" minlength="8" required>
                </div>
                <small class="hint">Must be at least 8 characters</small>
    
                <label>Confirm Password<span>*</span></label>
                <div class="input-group">
                    <span class="icon">🔒</span>
                    <input type="password" name="confirm_password" required>
                </div>
    
                <div class="terms">
                    <input type="checkbox" required>
                    <span>
                        I agree to the
                        <a href="#">terms and Conditions</a> and
                        <a href="#">Privacy Policy</a>
                    </span>
                </div>
    
                <button type="submit" class="login-btn">
                    Create My Account
                </button>
    
            </form>
    
            <div class="divider"></div>
    
            <p class="signup-text">
                Already have an account?
                <a href="index.php?action=login">Login here</a>
            </p>
    
        </div>
    </div>
</div>


<?php include 'views/includes/footer.php'; ?>

</body>
</html>