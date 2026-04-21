<?php
if (empty($user)) {
    header('Location: ' . BASE_URL . 'index.php?action=users-profile');
    exit();
}
$profile_picture = $user['profile_picture'] ?? (ASSETS_PATH . 'images/default-avatar.png');
?>

<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/users-profile.css'; ?>">
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/edit-profile.css'; ?>">

<div class="profile-wrapper">

    <a class="back-link" href="<?php echo BASE_URL; ?>index.php?action=users-profile">
        ← Back to Profile
    </a>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="flash flash-error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="profile-container">
        <div class="profile-card">

            <!-- Header -->
            <div class="profile-header">
                <div class="profile-avatar" id="avatarWrapper">
                    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" id="profileImg">
                    <label class="avatar-upload-btn" for="profile_photo" title="Change photo">
                        <i class="ri-camera-line"></i>
                    </label>
                </div>
                <h1 class="profile-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
                <p class="profile-role"><?php echo htmlspecialchars($user['role']); ?></p>
                <p class="ep-hint"><i class="ri-edit-2-line"></i> Editing your profile</p>
            </div>

            <!-- Form -->
            <div class="profile-body">
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=update-profile"
                      enctype="multipart/form-data" id="editProfileForm">

                    <!-- Hidden photo input (triggered by camera label above) -->
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" hidden>

                    <div class="info-section">
                        <h2 class="section-title">Personal Information</h2>
                        <div class="info-grid edit-grid">

                            <div class="info-item edit-field">
                                <label for="first_name"><i class="ri-user-line"></i> First Name <span class="req">*</span></label>
                                <input type="text" id="first_name" name="first_name"
                                       value="<?php echo htmlspecialchars($user['first_name']); ?>"
                                       required maxlength="80" placeholder="First name">
                            </div>

                            <div class="info-item edit-field">
                                <label for="last_name"><i class="ri-user-line"></i> Last Name <span class="req">*</span></label>
                                <input type="text" id="last_name" name="last_name"
                                       value="<?php echo htmlspecialchars($user['last_name']); ?>"
                                       required maxlength="80" placeholder="Last name">
                            </div>

                            <div class="info-item edit-field">
                                <label for="email"><i class="ri-mail-line"></i> Email Address</label>
                                <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                                       disabled class="field-disabled"
                                       title="Email cannot be changed. Contact support if needed.">
                                <small class="field-note">Email cannot be changed.</small>
                            </div>

                            <div class="info-item edit-field">
                                <label for="phone"><i class="ri-phone-line"></i> Phone Number</label>
                                <input type="tel" id="phone" name="phone"
                                       value="<?php echo htmlspecialchars($user['phone']); ?>"
                                       maxlength="25" placeholder="e.g. 09XX XXX XXXX">
                            </div>

                            <div class="info-item edit-field">
                                <label for="date_of_birth"><i class="ri-cake-line"></i> Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth"
                                       value="<?php echo htmlspecialchars($user['date_of_birth'] ?? ''); ?>">
                            </div>

                            <div class="info-item edit-field full-span">
                                <label for="address"><i class="ri-map-pin-line"></i> Address</label>
                                <input type="text" id="address" name="address"
                                       value="<?php echo htmlspecialchars($user['address']); ?>"
                                       maxlength="255" placeholder="Full address">
                            </div>

                        </div>
                    </div><!-- /.info-section -->

                    <!-- Photo preview notice -->
                    <p class="photo-preview-note" id="photoNote" style="display:none;">
                        <i class="ri-image-line"></i> New photo selected — it will be saved when you click Save Changes.
                    </p>

                    <!-- Actions -->
                    <div class="profile-actions">
                        <button type="submit" class="btn-edit">
                            <i class="ri-save-line"></i> Save Changes
                        </button>
                        <a href="<?php echo BASE_URL; ?>index.php?action=users-profile" class="btn-cancel">
                            <i class="ri-close-line"></i> Cancel
                        </a>
                    </div>

                </form>
            </div><!-- /.profile-body -->

        </div><!-- /.profile-card -->
    </div><!-- /.profile-container -->
</div><!-- /.profile-wrapper -->

<?php include VIEW_PATH . 'includes/footer.php'; ?>

<script>
// Live avatar preview when a new photo is selected
document.getElementById('profile_photo').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        alert('Please select an image file (JPG, PNG, GIF, WEBP).');
        this.value = '';
        return;
    }
    if (file.size > 5 * 1024 * 1024) {
        alert('Image must be under 5 MB.');
        this.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('profileImg').src = e.target.result;
    };
    reader.readAsDataURL(file);
    document.getElementById('photoNote').style.display = 'block';
});

// Basic client-side validation before submit
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    const fn = document.getElementById('first_name').value.trim();
    const ln = document.getElementById('last_name').value.trim();
    if (!fn || !ln) {
        e.preventDefault();
        alert('First name and last name are required.');
    }
});
</script>
