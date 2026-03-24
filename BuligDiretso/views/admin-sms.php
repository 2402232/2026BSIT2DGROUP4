<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/admin-dashboard.css'; ?>">
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/admin-sms.css'; ?>">

<?php require_once VIEW_PATH . 'includes/header.php'; ?>

<div class="admin-wrapper">

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="banner">
            <i class="ri-message-2-line" style="background-color:#2980B9;"></i>
            <div class="banner-text">
                <h2>SMS Center</h2>
                <p>Send notifications to users & responders</p>
            </div>
        </div>
    </div>

    <!-- Flash messages -->
    <?php if (!empty($_SESSION['sms_success'])): ?>
        <div class="sms-alert success">
            ✅ <?php echo htmlspecialchars($_SESSION['sms_success']); ?>
        </div>
        <?php unset($_SESSION['sms_success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['sms_error'])): ?>
        <div class="sms-alert error">
            ❌ <?php echo htmlspecialchars($_SESSION['sms_error']); ?>
        </div>
        <?php unset($_SESSION['sms_error']); ?>
    <?php endif; ?>

    <!-- SMS Cards Grid -->
    <div class="sms-grid">

        <!-- Card 1: Send to Single User -->
        <div class="sms-card">
            <div class="sms-card-header blue">
                <i class="ri-user-line"></i>
                <h3>Send to a User</h3>
            </div>
            <div class="sms-card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=sms-broadcast">
                    <label>Phone Number</label>
                    <input type="text" name="phones" placeholder="e.g. 09171234567" required>

                    <label>Message</label>
                    <textarea name="message" rows="4" placeholder="Type your message here..." required></textarea>

                    <button type="submit" class="sms-btn blue">
                        <i class="ri-send-plane-line"></i> Send SMS
                    </button>
                </form>
            </div>
        </div>

        <!-- Card 2: Notify Emergency Received -->
        <div class="sms-card">
            <div class="sms-card-header red">
                <i class="ri-alarm-warning-line"></i>
                <h3>Emergency Received</h3>
            </div>
            <div class="sms-card-body">
                <p class="sms-desc">Notify a user that their emergency report was received and help is on the way.</p>
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=sms-emergency-received">
                    <label>User Phone</label>
                    <input type="text" name="user_phone" placeholder="e.g. 09171234567" required>

                    <label>Emergency ID</label>
                    <input type="text" name="emergency_id" placeholder="e.g. ER-KP376" required>

                    <label>Emergency Type</label>
                    <select name="emergency_type">
                        <option value="Medical">Medical</option>
                        <option value="Fire">Fire</option>
                        <option value="Crime">Crime</option>
                        <option value="Flood">Flood</option>
                        <option value="Accident">Accident</option>
                    </select>

                    <button type="submit" class="sms-btn red">
                        <i class="ri-send-plane-line"></i> Notify User
                    </button>
                </form>
            </div>
        </div>

        <!-- Card 3: Notify Responder Assigned -->
        <div class="sms-card">
            <div class="sms-card-header orange">
                <i class="ri-user-star-line"></i>
                <h3>Responder Assigned</h3>
            </div>
            <div class="sms-card-body">
                <p class="sms-desc">Alert a responder that they have been assigned to an emergency.</p>
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=sms-responder-assigned">
                    <label>Responder Phone</label>
                    <input type="text" name="responder_phone" placeholder="e.g. 09171234567" required>

                    <label>Emergency ID</label>
                    <input type="text" name="emergency_id" placeholder="e.g. ER-KP376" required>

                    <label>Location</label>
                    <input type="text" name="location" placeholder="e.g. Brgy. Isabela, Bacolod" required>

                    <button type="submit" class="sms-btn orange">
                        <i class="ri-send-plane-line"></i> Alert Responder
                    </button>
                </form>
            </div>
        </div>

        <!-- Card 4: Emergency Resolved -->
        <div class="sms-card">
            <div class="sms-card-header green">
                <i class="ri-checkbox-circle-line"></i>
                <h3>Emergency Resolved</h3>
            </div>
            <div class="sms-card-body">
                <p class="sms-desc">Inform the user that their emergency has been resolved.</p>
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=sms-resolved">
                    <label>User Phone</label>
                    <input type="text" name="user_phone" placeholder="e.g. 09171234567" required>

                    <label>Emergency ID</label>
                    <input type="text" name="emergency_id" placeholder="e.g. ER-KP376" required>

                    <button type="submit" class="sms-btn green">
                        <i class="ri-send-plane-line"></i> Notify Resolved
                    </button>
                </form>
            </div>
        </div>

        <!-- Card 5: Broadcast to Multiple -->
        <div class="sms-card wide">
            <div class="sms-card-header purple">
                <i class="ri-broadcast-line"></i>
                <h3>Broadcast to Multiple Users</h3>
            </div>
            <div class="sms-card-body">
                <p class="sms-desc">Send one message to many phone numbers at once.</p>
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=sms-broadcast">
                    <label>Phone Numbers <small>(separate with commas)</small></label>
                    <input type="text" name="phones" placeholder="e.g. 09171234567, 09181234567, 09191234567" required>

                    <label>Message</label>
                    <textarea name="message" rows="4" placeholder="Type your broadcast message..." required></textarea>

                    <button type="submit" class="sms-btn purple">
                        <i class="ri-broadcast-line"></i> Send Broadcast
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
