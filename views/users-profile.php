<?php
// Mock user data - replace with database fetch
$user = [
    'id' => 1,
    'first_name' => 'Juan',
    'last_name' => 'Dela Cruz',
    'email' => 'juan.delacruz@example.com',
    'phone' => '09123456789',
    'address' => 'Barangay Bulad, Isabela, Negros Occidental',
    'date_of_birth' => '1990-05-15',
    'role' => 'Resident',
    'joined_date' => '2024-01-15',
    'profile_picture' => ASSETS_PATH . 'images/default-avatar.png' // or actual uploaded photo
];
?>

<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/emergency-tracking.css'; ?>">

<div class="profile-wrapper">

    <a class="back-link" href="<?php echo BASE_URL; ?>index.php?action=dashboard">
        ← Back to Dashboard
    </a>

    <div class="profile-container">

        <!-- Profile Card -->
        <div class="profile-card">

            <!-- Header Section -->
            <div class="profile-header">
                <div class="profile-avatar">
                    <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" id="profileImg">
                    <button class="avatar-upload-btn" id="uploadBtn" title="Change photo">
                        <i class="ri-camera-line"></i>
                    </button>
                    <input type="file" id="avatarInput" accept="image/*" hidden>
                </div>
                <h1 class="profile-name"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h1>
                <p class="profile-role"><?php echo $user['role']; ?></p>
            </div>

            <!-- Info Section -->
            <div class="profile-body">

                <div class="info-section">
                    <h2 class="section-title">Personal Information</h2>
                    <div class="info-grid">

                        <div class="info-item">
                            <label><i class="ri-user-line"></i> First Name</label>
                            <p><?php echo $user['first_name']; ?></p>
                        </div>

                        <div class="info-item">
                            <label><i class="ri-user-line"></i> Last Name</label>
                            <p><?php echo $user['last_name']; ?></p>
                        </div>

                        <div class="info-item">
                            <label><i class="ri-mail-line"></i> Email Address</label>
                            <p><?php echo $user['email']; ?></p>
                        </div>

                        <div class="info-item">
                            <label><i class="ri-phone-line"></i> Phone Number</label>
                            <p><?php echo $user['phone']; ?></p>
                        </div>

                        <div class="info-item">
                            <label><i class="ri-cake-line"></i> Date of Birth</label>
                            <p><?php echo date('F j, Y', strtotime($user['date_of_birth'])); ?></p>
                        </div>

                        <div class="info-item">
                            <label><i class="ri-map-pin-line"></i> Address</label>
                            <p><?php echo $user['address']; ?></p>
                        </div>

                    </div>
                </div>

                <div class="info-section">
                    <h2 class="section-title">Account Details</h2>
                    <div class="info-grid">

                        <div class="info-item">
                            <label><i class="ri-shield-user-line"></i> Role</label>
                            <p><span class="role-badge"><?php echo $user['role']; ?></span></p>
                        </div>

                        <div class="info-item">
                            <label><i class="ri-calendar-line"></i> Member Since</label>
                            <p><?php echo date('F j, Y', strtotime($user['joined_date'])); ?></p>
                        </div>

                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="profile-actions">
                    <button class="btn-edit" onclick="window.location.href='<?php echo BASE_URL; ?>index.php?action=edit-profile'">
                        <i class="ri-edit-line"></i> Edit Profile
                    </button>
                    <button class="btn-password" onclick="window.location.href='<?php echo BASE_URL; ?>index.php?action=change-password'">
                        <i class="ri-lock-password-line"></i> Change Password
                    </button>
                </div>

            </div>

        </div>

    </div>

</div>

<?php include VIEW_PATH . 'includes/footer.php'; ?>
<script src="<?php echo ASSETS_PATH . 'js/profile.js'; ?>"></script>