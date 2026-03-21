<?php
// $user is set by UserController from database (User::findById)
if (empty($user)) {
    header('Location: ' . BASE_URL . 'index.php?action=dashboard');
    exit();
}
$joined_date = $user['joined_date'] ?? $user['created_at'] ?? null;
$profile_picture = $user['profile_picture'] ?? (ASSETS_PATH . 'images/default-avatar.png');

// Map database role to user-friendly display label
$role_labels = ['admin' => 'Admin', 'pwd' => 'User', 'responder' => 'Responder'];
$role_display = $role_labels[$user['role']] ?? ucfirst($user['role']);
?>

<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/users-profile.css'; ?>">

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
                    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" id="profileImg">
                    <button class="avatar-upload-btn" id="uploadBtn" title="Change photo">
                        <i class="ri-camera-line"></i>
                    </button>
                    <input type="file" id="avatarInput" accept="image/*" hidden>
                </div>
                <h1 class="profile-name"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h1>
                <p class="profile-role"><?php echo $role_display; ?></p>
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
                            <p><?php echo !empty($user['date_of_birth']) ? date('F j, Y', strtotime($user['date_of_birth'])) : '—'; ?></p>
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
                            <p><span class="role-badge"><?php echo $role_display; ?></span></p>
                        </div>

                        <div class="info-item">
                            <label><i class="ri-calendar-line"></i> Member Since</label>
                            <p><?php echo $joined_date ? date('F j, Y', strtotime($joined_date)) : '—'; ?></p>
                        </div>

                    </div>
                </div>

                <div class="info-section emergency-contacts-section">
                    <div class="section-head-row">
                        <h2 class="section-title">Emergency Contacts</h2>
                        <span class="contact-count"><?php echo (int) count($emergencyContacts ?? []); ?> / <?php echo (int) ($contactLimit ?? 5); ?></span>
                    </div>

                    <?php if (!empty($_SESSION['contact_success'])): ?>
                        <div class="contact-alert contact-success">
                            <?php echo htmlspecialchars($_SESSION['contact_success']); unset($_SESSION['contact_success']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['contact_error'])): ?>
                        <div class="contact-alert contact-error">
                            <?php echo htmlspecialchars($_SESSION['contact_error']); unset($_SESSION['contact_error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php $contactCount = count($emergencyContacts ?? []); ?>

                    <?php if ($contactCount < ($contactLimit ?? 5)): ?>
                        <form class="contact-form" method="POST" action="<?php echo BASE_URL; ?>index.php?action=add-emergency-contact" id="emergencyContactForm">
                            <div class="contact-form-grid">
                                <div class="contact-field">
                                    <label for="contact_name"><i class="ri-user-3-line label-icon" aria-hidden="true"></i> Full name</label>
                                    <div class="contact-field-line">
                                        <span class="contact-input-icon" aria-hidden="true"><i class="ri-user-smile-line"></i></span>
                                        <input id="contact_name" type="text" name="contact_name" placeholder="Who should we call?" autocomplete="name" required>
                                    </div>
                                </div>
                                <div class="contact-field contact-field-relationship">
                                    <label for="relationship"><i class="ri-heart-line label-icon" aria-hidden="true"></i> Relationship to you</label>
                                    <div class="contact-field-line contact-field-select">
                                        <span class="contact-input-icon" aria-hidden="true"><i class="ri-group-line"></i></span>
                                        <select id="relationship" name="relationship" required>
                                            <option value="" disabled selected>Select from list</option>
                                            <option value="Mother">Mother</option>
                                            <option value="Father">Father</option>
                                            <option value="Spouse / Partner">Spouse / Partner</option>
                                            <option value="Sibling">Sibling</option>
                                            <option value="Child">Child</option>
                                            <option value="Guardian">Guardian</option>
                                            <option value="Friend">Friend</option>
                                            <option value="Neighbor">Neighbor</option>
                                            <option value="Relative">Other relative</option>
                                            <option value="__other__">Other — specify below</option>
                                        </select>
                                    </div>
                                    <div class="contact-field-line contact-relationship-other" id="relationshipOtherWrap" hidden>
                                        <span class="contact-input-icon" aria-hidden="true"><i class="ri-edit-2-line"></i></span>
                                        <input type="text" name="relationship_other" id="relationship_other" placeholder="Type relationship (e.g. Aunt, Cousin)" autocomplete="off">
                                    </div>
                                </div>
                                <div class="contact-field">
                                    <label for="contact_phone"><i class="ri-phone-line label-icon" aria-hidden="true"></i> Phone number</label>
                                    <div class="contact-field-line">
                                        <span class="contact-input-icon" aria-hidden="true"><i class="ri-smartphone-line"></i></span>
                                        <input id="contact_phone" type="tel" name="contact_phone" inputmode="tel" placeholder="09XX XXX XXXX" autocomplete="tel" required>
                                    </div>
                                </div>
                            </div>
                            <div class="contact-form-actions">
                                <button type="submit" class="btn-add-contact"><i class="ri-user-add-line"></i> Add emergency contact</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <p class="contact-limit-msg">You already added 5 emergency contacts.</p>
                    <?php endif; ?>

                    <div class="contact-list">
                        <?php if (!empty($emergencyContacts)): ?>
                            <?php foreach ($emergencyContacts as $contact): ?>
                                <div class="contact-item">
                                    <div class="contact-main">
                                        <p class="contact-name"><?php echo htmlspecialchars($contact['contact_name']); ?></p>
                                        <p class="contact-meta"><span>Relationship:</span> <?php echo htmlspecialchars($contact['relationship']); ?></p>
                                        <p class="contact-meta"><span>Phone:</span> <?php echo htmlspecialchars($contact['phone']); ?></p>
                                    </div>
                                    <form class="contact-remove-form" method="POST" action="<?php echo BASE_URL; ?>index.php?action=delete-emergency-contact">
                                        <input type="hidden" name="contact_id" value="<?php echo (int) $contact['id']; ?>">
                                        <button type="submit" class="btn-remove-contact">
                                            <i class="ri-delete-bin-line"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="contact-empty">No emergency contacts yet.</p>
                        <?php endif; ?>
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
<script>
(function () {
    var sel = document.getElementById('relationship');
    var wrap = document.getElementById('relationshipOtherWrap');
    var other = document.getElementById('relationship_other');
    if (!sel || !wrap || !other) return;
    function sync() {
        var show = sel.value === '__other__';
        wrap.hidden = !show;
        other.required = show;
        if (!show) other.value = '';
    }
    sel.addEventListener('change', sync);
    sync();
})();
</script>