<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/login.css">
<style>
.profile-page{max-width:640px;margin:100px auto 60px;padding:0 20px}
.profile-card{background:#fff;border-radius:14px;border:2px solid #e44d26;padding:38px}
.avatar-wrap{text-align:center;margin-bottom:24px}
.avatar-img{width:110px;height:110px;border-radius:50%;object-fit:cover;border:4px solid #e44d26;display:block;margin:0 auto}
.avatar-placeholder{width:110px;height:110px;border-radius:50%;background:#e44d26;display:flex;align-items:center;justify-content:center;font-size:48px;color:#fff;margin:0 auto}
.profile-card h2{text-align:center;margin:10px 0 4px}
.role-wrap{text-align:center;margin-bottom:20px}
.role-badge{display:inline-block;background:#fde8e8;color:#e44d26;border-radius:20px;padding:3px 16px;font-size:.78rem;font-weight:700;letter-spacing:.5px}
.change-photo-label{display:flex;align-items:center;justify-content:center;gap:6px;cursor:pointer;color:#e44d26;font-weight:600;font-size:.88rem;margin:8px 0 16px}
.alert-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb;border-radius:6px;padding:12px 16px;margin-bottom:16px;font-size:.9rem}
</style>

<div class="profile-page">
    <a class="back-link" href="<?php echo BASE_URL; ?>index.php?action=dashboard">← Back to Dashboard</a>

    <div class="profile-card">
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <!-- Avatar -->
        <div class="avatar-wrap">
            <?php if (!empty($user['profile_photo'])): ?>
                <img id="avatarImg" class="avatar-img"
                     src="<?php echo UPLOADS_URL . 'profiles/' . htmlspecialchars($user['profile_photo']); ?>"
                     alt="Profile photo"
                     onerror="this.style.display='none';document.getElementById('avatarPh').style.display='flex'">
                <div id="avatarPh" class="avatar-placeholder" style="display:none">👤</div>
            <?php else: ?>
                <div id="avatarPh" class="avatar-placeholder">👤</div>
                <img id="avatarImg" class="avatar-img" style="display:none" alt="">
            <?php endif; ?>
        </div>

        <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
        <div class="role-wrap">
            <span class="role-badge"><?php echo strtoupper($user['role']); ?></span>
        </div>

        <form action="<?php echo BASE_URL; ?>index.php?action=update_profile" method="POST" enctype="multipart/form-data">

            <label class="change-photo-label" for="photoInput">
                📷 Change Profile Photo
                <input type="file" id="photoInput" name="profile_photo" accept="image/*" hidden onchange="previewPhoto(this)">
            </label>

            <label>First Name<span>*</span></label>
            <div class="input-group">
                <span class="icon">👤</span>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>

            <label>Last Name<span>*</span></label>
            <div class="input-group">
                <span class="icon">👤</span>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>

            <label>Email (cannot be changed)</label>
            <div class="input-group" style="background:#f5f5f5">
                <span class="icon">✉️</span>
                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
            </div>

            <label>Phone Number</label>
            <div class="input-group">
                <span class="icon">📞</span>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>

            <label>Address</label>
            <div class="input-group">
                <span class="icon">🏠</span>
                <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
            </div>

            <label>New Password <small style="color:#999">(leave blank to keep current)</small></label>
            <div class="input-group">
                <span class="icon">🔒</span>
                <input type="password" name="new_password" minlength="8" placeholder="Min 8 characters">
            </div>

            <button type="submit" class="login-btn" style="margin-top:24px">💾 Save Changes</button>
        </form>
    </div>
</div>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.getElementById('avatarImg');
            var ph  = document.getElementById('avatarPh');
            img.src = e.target.result;
            img.style.display = 'block';
            ph.style.display  = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
</body>
</html>
