<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/admin-settings.css'; ?>">

<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<div class="admin-wrapper">

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="banner">
            <i class="ri-settings-3-line"></i>
            <div class="banner-text">
                <h2>System Settings</h2>
                <p>Emergency Response System</p>
            </div>
        </div>
    </div>

    <div class="settings-layout">
        <!-- Sidebar Tabs -->
        <div class="settings-sidebar">
            <button class="settings-tab active" data-target="general"><i class="ri-home-gear-line"></i> General</button>
            <button class="settings-tab" data-target="notifications"><i class="ri-notification-3-line"></i> Notifications</button>
            <button class="settings-tab" data-target="security"><i class="ri-shield-keyhole-line"></i> Security</button>
            <button class="settings-tab" data-target="hotlines"><i class="ri-phone-line"></i> Hotlines</button>
            <button class="settings-tab" data-target="appearance"><i class="ri-palette-line"></i> Appearance</button>
        </div>

        <!-- Settings Panels -->
        <div class="settings-content">

            <!-- General -->
            <div class="settings-panel active" id="general">
                <h3 class="panel-title">General Settings</h3>
                <div class="form-section">
                    <label>System Name</label>
                    <input type="text" class="settings-input" value="BuligDiretso">
                    <p class="input-hint">Displayed in the header and email notifications.</p>
                </div>
                <div class="form-section">
                    <label>Admin Email</label>
                    <input type="email" class="settings-input" value="admin@buligdiretso.ph">
                </div>
                <div class="form-section">
                    <label>Default Response Target (minutes)</label>
                    <input type="number" class="settings-input" value="5" min="1" max="60">
                </div>
                <div class="form-section">
                    <label>Timezone</label>
                    <select class="settings-input">
                        <option selected>Asia/Manila (GMT+8)</option>
                        <option>UTC</option>
                    </select>
                </div>
                <div class="form-section toggle-row">
                    <div>
                        <label>Maintenance Mode</label>
                        <p class="input-hint">Disables user-facing pages while maintenance is in progress.</p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <button class="save-btn"><i class="ri-save-line"></i> Save Changes</button>
            </div>

            <!-- Notifications -->
            <div class="settings-panel" id="notifications">
                <h3 class="panel-title">Notification Settings</h3>
                <div class="form-section toggle-row">
                    <div>
                        <label>SMS Alerts for New Emergencies</label>
                        <p class="input-hint">Send SMS to on-duty responders when a new emergency is reported.</p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="form-section toggle-row">
                    <div>
                        <label>Email Summary Reports</label>
                        <p class="input-hint">Daily summary emailed to the admin account.</p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="form-section toggle-row">
                    <div>
                        <label>Critical Emergency Push Alerts</label>
                        <p class="input-hint">Immediately alert all active responders for CRITICAL cases.</p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="form-section">
                    <label>Alert Threshold (Priority Level)</label>
                    <select class="settings-input">
                        <option>Critical only</option>
                        <option selected>High & Critical</option>
                        <option>All priorities</option>
                    </select>
                </div>
                <button class="save-btn"><i class="ri-save-line"></i> Save Changes</button>
            </div>

            <!-- Security -->
            <div class="settings-panel" id="security">
                <h3 class="panel-title">Security Settings</h3>
                <div class="form-section">
                    <label>Change Admin Password</label>
                    <input type="password" class="settings-input" placeholder="Current password">
                    <input type="password" class="settings-input" placeholder="New password" style="margin-top:8px">
                    <input type="password" class="settings-input" placeholder="Confirm new password" style="margin-top:8px">
                </div>
                <div class="form-section toggle-row">
                    <div>
                        <label>Two-Factor Authentication</label>
                        <p class="input-hint">Require OTP on each admin login.</p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="form-section">
                    <label>Session Timeout (minutes)</label>
                    <input type="number" class="settings-input" value="60" min="5" max="480">
                </div>
                <button class="save-btn"><i class="ri-save-line"></i> Save Changes</button>
                <button class="danger-btn" style="margin-top:8px"><i class="ri-logout-circle-r-line"></i> Terminate All Sessions</button>
            </div>

            <!-- Hotlines -->
            <div class="settings-panel" id="hotlines">
                <h3 class="panel-title">Emergency Hotlines</h3>
                <p class="input-hint" style="margin-bottom:16px">These numbers are shown to users on the contact page and footer.</p>
                <?php
                $hotlines = [
                    ['LDRRMO', '0951 682 1504'],
                    ['MHO Isabela', '0963 156 6032'],
                    ['ILASMDH', '0947 415 4761'],
                    ['PNP Isabela', '0999 659 0677'],
                    ['NOCECO', '0998 570 2725'],
                    ['BFP (Bureau of Fire Protection)', '0970 465 9383'],
                ];
                foreach ($hotlines as $h): ?>
                <div class="hotline-row">
                    <input type="text" class="settings-input sm" value="<?php echo $h[0]; ?>">
                    <input type="text" class="settings-input sm" value="<?php echo $h[1]; ?>">
                    <button class="icon-btn danger"><i class="ri-delete-bin-line"></i></button>
                </div>
                <?php endforeach; ?>
                <button class="add-hotline-btn"><i class="ri-add-line"></i> Add Hotline</button>
                <button class="save-btn" style="margin-top:12px"><i class="ri-save-line"></i> Save Changes</button>
            </div>

            <!-- Appearance -->
            <div class="settings-panel" id="appearance">
                <h3 class="panel-title">Appearance</h3>
                <div class="form-section">
                    <label>Primary Color</label>
                    <div class="color-row">
                        <input type="color" value="#E74C3C" class="color-picker">
                        <input type="text" class="settings-input" value="#E74C3C" style="width:100px">
                    </div>
                </div>
                <div class="form-section">
                    <label>System Logo</label>
                    <div class="upload-area">
                        <i class="ri-image-add-line"></i>
                        <span>Click to upload or drag & drop</span>
                        <span class="input-hint">PNG, JPG up to 2MB</span>
                    </div>
                </div>
                <div class="form-section toggle-row">
                    <div>
                        <label>Dark Mode (Admin Panel)</label>
                        <p class="input-hint">Apply a dark theme to the admin interface.</p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <button class="save-btn"><i class="ri-save-line"></i> Save Changes</button>
            </div>

        </div>
    </div>
</div>

<script>
document.querySelectorAll('.settings-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById(tab.dataset.target).classList.add('active');
    });
});
</script>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
