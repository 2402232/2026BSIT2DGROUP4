<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/report-system.css">
<style>
.priority-box.selected,.emergency-box.selected{outline:3px solid #e44d26;outline-offset:2px}
.upload-preview-img{max-width:200px;border-radius:8px;margin-top:10px;display:none}
.upload-preview-img.show{display:block}
.alert{border-radius:6px;padding:12px 16px;margin-bottom:14px;font-size:.9rem}
.alert-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb}
.alert-error{background:#fde8e8;color:#9b1c1c;border:1px solid #f8b4b4}
</style>

<div class="page-wrapper">
    <div class="top-bar">
        <div class="banner-text"><i class="ri-error-warning-line"></i><h2>Emergency Response System</h2></div>
        <div class="banner-icon"><i class="ri-group-fill"></i><i class="ri-pulse-line"></i></div>
    </div>

    <div class="page">
        <a class="back-link" href="<?php echo BASE_URL; ?>index.php?action=dashboard">← Back to Home</a>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="section-head"><span class="sh-icon"><i class="ri-alert-line"></i></span>Report Emergency</div>

        <div class="card">
            <form action="<?php echo BASE_URL; ?>index.php?action=submit_report" method="POST" enctype="multipart/form-data">

                <!-- Hidden fields -->
                <input type="hidden" name="emergency_type" id="emergency_type">
                <input type="hidden" name="severity"       id="severity_field" value="moderate">
                <input type="hidden" name="latitude"       id="lat_field">
                <input type="hidden" name="longitude"      id="lng_field">
                <input type="hidden" name="location"       id="loc_field" value="Unknown">

                <!-- Priority -->
                <p class="priority-label">Priority level</p>
                <div class="priority-grid">
                    <div class="priority-box pb-critical" onclick="setPriority('critical',this)">
                        <span class="pb-badge">CRITICAL</span>
                        <p class="pb-desc">Life-threatening emergency</p>
                    </div>
                    <div class="priority-box pb-high" onclick="setPriority('moderate',this)">
                        <span class="pb-badge">HIGH PRIORITY</span>
                        <p class="pb-desc">Urgent response needed</p>
                    </div>
                    <div class="priority-box pb-moderate" onclick="setPriority('moderate',this)">
                        <span class="pb-badge">MODERATE</span>
                        <p class="pb-desc">Important but stable</p>
                    </div>
                    <div class="priority-box pb-low" onclick="setPriority('minor',this)">
                        <span class="pb-badge">LOW PRIORITY</span>
                        <p class="pb-desc">Non-urgent assistance</p>
                    </div>
                </div>

                <!-- Emergency type -->
                <p class="type-label">Select Emergency Type</p>
                <div class="emergency-grid">
                    <button type="button" class="emergency-box" data-type="Medical"    onclick="setType(this)"><span class="box-icon">🏥</span><p>Medical Emergency</p></button>
                    <button type="button" class="emergency-box" data-type="Other"      onclick="setType(this)"><span class="box-icon">🚗</span><p>Accident</p></button>
                    <button type="button" class="emergency-box" data-type="Other"      onclick="setType(this)"><span class="box-icon">🐾</span><p>Animal Attack</p></button>
                    <button type="button" class="emergency-box" data-type="Earthquake" onclick="setType(this)"><span class="box-icon">🌪️</span><p>Natural Disaster</p></button>
                    <button type="button" class="emergency-box" data-type="Fire"       onclick="setType(this)"><span class="box-icon">🔥</span><p>Fire</p></button>
                    <button type="button" class="emergency-box" data-type="Other"      onclick="setType(this)"><span class="box-icon">⚡</span><p>Another Emergency</p></button>
                </div>

                <!-- Location -->
                <div class="location-alert">
                    <span class="la-icon">📍</span>
                    <div>
                        <strong id="locDisplay">Detecting your location...</strong>
                        <span>Your location is automatically captured</span>
                    </div>
                </div>
                <div class="form-field" style="margin-top:10px">
                    <label>Location (edit if needed)</label>
                    <input type="text" id="manualLoc" placeholder="e.g. Barangay Bulad, Isabela City"
                           style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;font-size:.93rem;margin-top:4px"
                           oninput="document.getElementById('loc_field').value=this.value">
                </div>

                <!-- Details -->
                <div class="form-field">
                    <label>Additional Details</label>
                    <textarea name="additional_details" rows="4" placeholder="Describe the emergency situation..."></textarea>
                </div>

                <!-- File upload -->
                <div class="form-field">
                    <label>Upload Photo / Video (Optional)</label>
                    <div class="upload-area" onclick="document.getElementById('file_upload').click()">
                        <div class="ua-icon">📷</div>
                        <p>Click to upload or drag and drop</p>
                        <small>Images, videos up to 10 MB</small>
                        <input type="file" name="file_upload" id="file_upload" hidden
                               accept="image/*,video/*" onchange="previewFile(this)">
                    </div>
                    <img id="uploadPreview" class="upload-preview-img" alt="Preview">
                </div>

                <button type="submit" class="btn-submit">SUBMIT EMERGENCY REPORT</button>
            </form>
        </div>
    </div>
</div>

<script>
// Geolocation
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(pos) {
        var lat = pos.coords.latitude.toFixed(5);
        var lng = pos.coords.longitude.toFixed(5);
        var loc = 'Lat ' + lat + ', Lng ' + lng;
        document.getElementById('lat_field').value   = lat;
        document.getElementById('lng_field').value   = lng;
        document.getElementById('loc_field').value   = loc;
        document.getElementById('locDisplay').textContent = loc;
        document.getElementById('manualLoc').value   = loc;
    }, function() {
        document.getElementById('locDisplay').textContent = 'Location unavailable — please enter manually';
    });
}

function setPriority(val, el) {
    document.querySelectorAll('.priority-box').forEach(function(b){ b.classList.remove('selected'); });
    el.classList.add('selected');
    document.getElementById('severity_field').value = val;
}

function setType(el) {
    document.querySelectorAll('.emergency-box').forEach(function(b){ b.classList.remove('selected'); });
    el.classList.add('selected');
    document.getElementById('emergency_type').value = el.dataset.type;
}

function previewFile(input) {
    if (input.files && input.files[0] && input.files[0].type.startsWith('image/')) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.getElementById('uploadPreview');
            img.src = e.target.result;
            img.classList.add('show');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
<script src="<?php echo ASSETS_PATH; ?>js/report-system.js"></script>
