<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/report-system.css'; ?>" />

<div class="page-wrapper">

    <!-- Top Bar -->
    <div class="top-bar">
         <!-- Red Emergency Banner -->
        
             <div class="banner-text">
                <i class="ri-error-warning-line"></i> 
                <h2>Emergency Response System</h2>
             </div>
             
             <div class="banner-icon">
                <i class="ri-group-fill"></i>
                <i class="ri-pulse-line"></i>
             </div>
         
    </div>

    <!-- Page Content -->
    <div class="page">

        <a class="back-link" href="index.php?action=dashboard">← Back to Home</a>

        <div class="section-head">
            <span class="sh-icon"><i class="ri-alert-line"></i></span>
            Report Emergency
        </div>

        <div class="card">
            <form action="submit_emergency.php" method="POST" enctype="multipart/form-data">

                <!-- Hidden fields -->
                <input type="hidden" name="emergency_type" id="emergency_type" required>
                <input type="hidden" name="fullname" value="User Name">
                <input type="hidden" name="contact" value="0000000000">
                <input type="hidden" name="address" value="Auto-detected location">

                <!-- Priority Level -->
                <p class="priority-label">Priority level</p>
                <div class="priority-grid">
                    <div class="priority-box pb-critical">
                        <span class="pb-badge">CRITICAL</span>
                        <p class="pb-desc">Life-threatening emergency</p>
                    </div>
                    <div class="priority-box pb-high">
                        <span class="pb-badge">HIGH PRIORITY</span>
                        <p class="pb-desc">Urgent response needed</p>
                    </div>
                    <div class="priority-box pb-moderate">
                        <span class="pb-badge">MODERATE</span>
                        <p class="pb-desc">Important but stable</p>
                    </div>
                    <div class="priority-box pb-low">
                        <span class="pb-badge">LOW PRIORITY</span>
                        <p class="pb-desc">Non-urgent assistance</p>
                    </div>
                </div>

                <!-- Emergency Type -->
                <p class="type-label">Select Emergency Type</p>
                <div class="emergency-grid">
                    <button type="button" class="emergency-box" data-type="medical">
                        <span class="box-icon">🏥</span>
                        <p>Medical Emergency</p>
                    </button>
                    <button type="button" class="emergency-box" data-type="accident">
                        <span class="box-icon">🚗</span>
                        <p>Accident</p>
                    </button>
                    <button type="button" class="emergency-box" data-type="animal">
                        <span class="box-icon">🐾</span>
                        <p>Animal Attack</p>
                    </button>
                    <button type="button" class="emergency-box" data-type="disaster">
                        <span class="box-icon">🌪️</span>
                        <p>Natural Disaster</p>
                    </button>
                    <button type="button" class="emergency-box" data-type="fire">
                        <span class="box-icon">🔥</span>
                        <p>Fire</p>
                    </button>
                    <button type="button" class="emergency-box" data-type="other">
                        <span class="box-icon">⚡</span>
                        <p>Another Emergency</p>
                    </button>
                </div>

                <!-- Location -->
                <div class="location-alert">
                    <span class="la-icon">📍</span>
                    <div>
                        <strong>Location detected: Barangay Bulad, Isabela, Negros Occidental</strong>
                        <span>Your location is automatically captured</span>
                    </div>
                </div>
                <p class="location-sub">Your location is automatically captured</p>

                <!-- Additional Details -->
                <div class="form-field">
                    <label>Additional Details</label>
                    <textarea name="additional_details" rows="4" placeholder="Describe the emergency situation..."></textarea>
                </div>

                <!-- Upload -->
                <div class="form-field">
                    <label>Upload photos/videos (Optional)</label>
                    <div class="upload-area" onclick="document.getElementById('file_upload').click()">
                        <div class="ua-icon">📷</div>
                        <p>Click to upload or drag and drop</p>
                        <small>Images, videos up to 10mb</small>
                        <input type="file" name="file_upload" id="file_upload" hidden accept="image/*,video/*">
                    </div>
                </div>

                <button type="submit" class="btn-submit">SUBMIT</button>

            </form>
        </div>

    </div>

</div>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
<script src="<?php echo ASSETS_PATH . 'js/report-system.js'; ?>"></script>