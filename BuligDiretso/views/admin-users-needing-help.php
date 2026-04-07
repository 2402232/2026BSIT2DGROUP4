<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/admin-users-needing-help.css'; ?>">
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/assign-modal.css'; ?>">

<?php require_once VIEW_PATH . 'includes/header.php'; ?>

<?php /* ── ADDED: SMS flash messages ── */ ?>
<?php if (!empty($_SESSION['sms_success'])): ?>
    <div style="max-width:900px;margin:90px auto 0;padding:0 20px;">
        <div class="sms-flash success">✅ <?php echo htmlspecialchars($_SESSION['sms_success']); ?></div>
    </div>
    <?php unset($_SESSION['sms_success']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['sms_error'])): ?>
    <div style="max-width:900px;margin:90px auto 0;padding:0 20px;">
        <div class="sms-flash error">❌ <?php echo htmlspecialchars($_SESSION['sms_error']); ?></div>
    </div>
    <?php unset($_SESSION['sms_error']); ?>
<?php endif; ?>

<div class="admin-wrapper">

 <!-- Top Bar -->
    <div class="top-bar">
         <!-- Red Emergency Banner -->
        
             <div class="banner">
                <i class="ri-shield-line"></i>
                <div class="banner-text">
                    <h2>Admin Dashboard</h2>
                    <p>Emergency Response System</p>
                </div>
             </div>
    </div>


        <div class="dashboard-card">

            <div class="filter-buttons">
                <button class="filter-btn active">All Emergencies</button>
                <button class="filter-btn">Critical</button>
                <button class="filter-btn">Pending Assignment</button>
                <button class="filter-btn">Responding</button>
            </div>

            <div class="emergency-list">
                                <div class="emergency-card">
                    <div class="card-header">
                        <div class="emergency-id">
                            <span class="badge badge-critical">CRITICAL</span>
                            <span class="badge badge-pending">PENDING</span>
                            <h3>ER-A7ZX</h3>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-user-line"></i>
                                <div>
                                    <span class="label">User Information</span>
                                    <span class="value">Juan Dela Cruz</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-time-line"></i>
                                <div>
                                    <span class="label">Created On</span>
                                    <span class="value">12:34:05 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-phone-line"></i>
                                <div>
                                    <span class="label">Contact Number</span>
                                    <span class="value">+63 912 345 6789</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-calendar-line"></i>
                                <div>
                                    <span class="label">Updated On</span>
                                    <span class="value">12:34:12 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-map-pin-line"></i>
                                <div>
                                    <span class="label">Location</span>
                                    <span class="value">123 Main St, Iloilo City</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-alert-line"></i>
                                <div>
                                    <span class="label">Emergency Type</span>
                                    <span class="value">Medical Emergency</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-file-text-line"></i>
                                <div>
                                    <span class="label">Description</span>
                                    <span class="value">Person having difficulty breathing</span>
                                </div>
                            </div>
                        </div>

                        <div class="responder-section">
                            <span class="responder-label">Responder Assigned:</span>
                            <span class="no-responder">No responder assigned yet</span>
                        </div>
                    </div>

                    <div class="card-actions">
                        <?php /* ADDED: triggers SMS modal with this card's data */ ?>
                        <button class="btn btn-assign" onclick="openAssignModal('ER-A7ZX','Juan Dela Cruz','+63 912 345 6789','123 Main St, Iloilo City','Medical Emergency',true,'medical')">📲 Assign &amp; Notify</button>
                        <button class="btn btn-view">View User Details</button>
                    </div>
                </div>

                <!-- Emergency Card 2 -->
                <div class="emergency-card">
                    <div class="card-header">
                        <div class="emergency-id">
                            <span class="badge badge-medium">MEDIUM</span>
                            <span class="badge badge-pending">PENDING</span>
                            <h3>ER-C3NDP</h3>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-user-line"></i>
                                <div>
                                    <span class="label">User Information</span>
                                    <span class="value">Maria Santos</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-time-line"></i>
                                <div>
                                    <span class="label">Created On</span>
                                    <span class="value">12:45:22 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-phone-line"></i>
                                <div>
                                    <span class="label">Contact Number</span>
                                    <span class="value">+63 923 456 7890</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-calendar-line"></i>
                                <div>
                                    <span class="label">Updated On</span>
                                    <span class="value">12:45:30 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-map-pin-line"></i>
                                <div>
                                    <span class="label">Location</span>
                                    <span class="value">456 Oak Avenue, Mandurriao</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-alert-line"></i>
                                <div>
                                    <span class="label">Emergency Type</span>
                                    <span class="value">Fire Emergency</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-file-text-line"></i>
                                <div>
                                    <span class="label">Description</span>
                                    <span class="value">Small kitchen fire spreading quickly</span>
                                </div>
                            </div>
                        </div>

                        <div class="responder-section">
                            <span class="responder-label">Responder Assigned:</span>
                            <span class="no-responder">No responder assigned yet</span>
                        </div>
                    </div>

                    <div class="card-actions">
                        <?php /* ADDED: triggers SMS modal with this card's data */ ?>
                        <button class="btn btn-assign" onclick="openAssignModal('ER-C3NDP','Maria Santos','+63 923 456 7890','456 Oak Avenue, Mandurriao','Fire Emergency',false,'')">📲 Assign &amp; Notify</button>
                        <button class="btn btn-view">View User Details</button>
                    </div>
                </div>

                <!-- Emergency Card 3 -->
                <div class="emergency-card">
                    <div class="card-header">
                        <div class="emergency-id">
                            <span class="badge badge-low">LOW</span>
                            <span class="badge badge-pending">PENDING</span>
                            <h3>ER-J7WBY</h3>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-user-line"></i>
                                <div>
                                    <span class="label">User Information</span>
                                    <span class="value">Pedro Garcia</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-time-line"></i>
                                <div>
                                    <span class="label">Created On</span>
                                    <span class="value">01:02:15 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-phone-line"></i>
                                <div>
                                    <span class="label">Contact Number</span>
                                    <span class="value">+63 934 567 8901</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-calendar-line"></i>
                                <div>
                                    <span class="label">Updated On</span>
                                    <span class="value">01:02:18 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-map-pin-line"></i>
                                <div>
                                    <span class="label">Location</span>
                                    <span class="value">789 Pine Street, Jaro District</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-alert-line"></i>
                                <div>
                                    <span class="label">Emergency Type</span>
                                    <span class="value">Police Assistance</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-file-text-line"></i>
                                <div>
                                    <span class="label">Description</span>
                                    <span class="value">Suspicious activity reported in the area</span>
                                </div>
                            </div>
                        </div>

                        <div class="responder-section">
                            <span class="responder-label">Responder Assigned:</span>
                            <span class="no-responder">No responder assigned yet</span>
                        </div>
                    </div>

                    <div class="card-actions">
                        <?php /* ADDED: triggers SMS modal with this card's data */ ?>
                        <button class="btn btn-assign" onclick="openAssignModal('ER-J7WBY','Pedro Garcia','+63 934 567 8901','789 Pine Street, Jaro District','Police Assistance',false,'')">📲 Assign &amp; Notify</button>
                        <button class="btn btn-view">View User Details</button>
                    </div>
                </div>

                <!-- Emergency Card 4 -->
                <div class="emergency-card">
                    <div class="card-header">
                        <div class="emergency-id">
                            <span class="badge badge-critical">CRITICAL</span>
                            <span class="badge badge-pending">PENDING</span>
                            <h3>ER-D7T2S</h3>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-user-line"></i>
                                <div>
                                    <span class="label">User Information</span>
                                    <span class="value">Ana Reyes</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-time-line"></i>
                                <div>
                                    <span class="label">Created On</span>
                                    <span class="value">01:15:45 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-phone-line"></i>
                                <div>
                                    <span class="label">Contact Number</span>
                                    <span class="value">+63 945 678 9012</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-calendar-line"></i>
                                <div>
                                    <span class="label">Updated On</span>
                                    <span class="value">01:15:52 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-map-pin-line"></i>
                                <div>
                                    <span class="label">Location</span>
                                    <span class="value">321 Elm Road, Molo District</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-alert-line"></i>
                                <div>
                                    <span class="label">Emergency Type</span>
                                    <span class="value">Vehicle Accident</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-file-text-line"></i>
                                <div>
                                    <span class="label">Description</span>
                                    <span class="value">Multi-vehicle collision with injuries</span>
                                </div>
                            </div>
                        </div>

                        <div class="responder-section">
                            <span class="responder-label">Responder Assigned:</span>
                            <span class="no-responder">No responder assigned yet</span>
                        </div>
                    </div>

                    <div class="card-actions">
                        <?php /* ADDED: triggers SMS modal with this card's data */ ?>
                        <button class="btn btn-assign" onclick="openAssignModal('ER-D7T2S','Ana Reyes','+63 945 678 9012','321 Elm Road, Molo District','Vehicle Accident',true,'pregnancy')">📲 Assign &amp; Notify</button>
                        <button class="btn btn-view">View User Details</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php /* ============================================================
         ADDED: Assign Responder + SMS Modal
         This modal opens when admin clicks "Assign & Notify"
         It sends 2-3 SMS: user notification, responder alert, optional follow-up
         ============================================================ */ ?>
<div id="assignModal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h2><i class="ri-user-star-line"></i> Assign Responder &amp; Send SMS</h2>
            <button class="modal-close" onclick="closeAssignModal()">✕</button>
        </div>
        <div class="modal-summary">
            <span id="modal-em-id" class="em-tag"></span>
            <span id="modal-em-type"></span>
        </div>
        <p class="modal-user-info">👤 <strong id="modal-user-name"></strong> &nbsp;|&nbsp; 📍 <span id="modal-user-loc"></span></p>

        <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=sms-assign-responder">
            <input type="hidden" name="emergency_id"   id="f-emergency-id">
            <input type="hidden" name="user_phone"     id="f-user-phone">
            <input type="hidden" name="user_name"      id="f-user-name">
            <input type="hidden" name="location"       id="f-location">
            <input type="hidden" name="emergency_type" id="f-emergency-type">
            <input type="hidden" name="needs_followup" id="f-needs-followup">
            <input type="hidden" name="followup_type"  id="f-followup-type">

            <div class="modal-section">
                <h4><i class="ri-user-star-line"></i> Responder Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label>Responder Name</label>
                        <input type="text" name="responder_name" id="inp-responder-name" placeholder="e.g. Kim Taehyung" required>
                    </div>
                    <div class="form-group">
                        <label>Responder Phone</label>
                        <input type="text" name="responder_phone" placeholder="e.g. 09171234567" required>
                    </div>
                </div>
            </div>

            <div class="modal-section">
                <h4><i class="ri-time-line"></i> Estimated Time of Arrival</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label>ETA</label>
                        <select name="eta_minutes" id="eta-select" onchange="updateEtaLabel(this.value)">
                            <option value="5">5 minutes</option>
                            <option value="10" selected>10 minutes</option>
                            <option value="15">15 minutes</option>
                            <option value="20">20 minutes</option>
                            <option value="30">30 minutes</option>
                            <option value="45">45 minutes</option>
                            <option value="60">1 hour</option>
                            <option value="custom">Custom...</option>
                        </select>
                    </div>
                    <div class="form-group" id="custom-eta-group" style="display:none;">
                        <label>Custom (minutes)</label>
                        <input type="number" name="eta_custom" id="eta-custom" min="1" max="240" placeholder="e.g. 25">
                    </div>
                </div>
            </div>

            <div class="modal-section">
                <h4><i class="ri-message-2-line"></i> SMS Preview — User will receive:</h4>
                <div class="sms-preview" id="sms-preview-box"></div>
            </div>

            <div class="modal-section" id="followup-section" style="display:none;">
                <h4><i class="ri-hospital-line"></i> Follow-up Clinic Reminder</h4>
                <div class="followup-toggle">
                    <label class="toggle-label">
                        <input type="checkbox" name="send_followup" id="send-followup-chk" onchange="toggleFollowupMsg()">
                        Send follow-up Barangay clinic reminder SMS
                    </label>
                </div>
                <div id="followup-msg-area" style="display:none;">
                    <div class="form-group">
                        <label>Follow-up Message <small>(editable)</small></label>
                        <textarea name="followup_message" id="followup-msg-text" rows="4" style="width:100%;box-sizing:border-box;padding:10px;border:1px solid #ddd;border-radius:8px;font-size:13px;"></textarea>
                    </div>
                    <p class="hint">Sent as a separate SMS to remind the user to visit the Barangay Health Center.</p>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeAssignModal()">Cancel</button>
                <button type="submit" class="btn-confirm"><i class="ri-send-plane-line"></i> Assign &amp; Send SMS</button>
            </div>
        </form>
    </div>
</div>


<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
<script src="<?php echo ASSETS_PATH . 'js/admin-users-needing-help.js'; ?>"></script>

<?php /* ADDED: Modal JS — controls the popup, SMS preview, follow-up messages */ ?>
<script>
let _md = {};

function openAssignModal(id, name, phone, location, type, needsFollowup, followupType) {
    _md = { id, name, phone, location, type, needsFollowup, followupType };
    document.getElementById('f-emergency-id').value   = id;
    document.getElementById('f-user-phone').value     = phone;
    document.getElementById('f-user-name').value      = name;
    document.getElementById('f-location').value       = location;
    document.getElementById('f-emergency-type').value = type;
    document.getElementById('f-needs-followup').value = needsFollowup ? '1' : '0';
    document.getElementById('f-followup-type').value  = followupType;
    document.getElementById('modal-em-id').textContent    = id;
    document.getElementById('modal-em-type').textContent  = type;
    document.getElementById('modal-user-name').textContent = name;
    document.getElementById('modal-user-loc').textContent  = location;

    // Show follow-up section only for cases that need it (pregnancy, children, medical)
    document.getElementById('followup-section').style.display = needsFollowup ? 'block' : 'none';
    if (needsFollowup) {
        const msgs = {
            pregnancy: `BuligDiretso: Hi ${name}, as a follow-up to your recent emergency, we recommend visiting your Barangay Health Center for a prenatal check-up. Please bring your health records. Your health and your baby\'s health are important to us. Stay safe!`,
            children:  `BuligDiretso: Hi ${name}, as a follow-up to your recent emergency, your child may need a check-up at the Barangay Health Center within 24-48 hours for a routine assessment. Stay safe!`,
            medical:   `BuligDiretso: Hi ${name}, as a follow-up to your recent emergency, we recommend a check-up at your Barangay Health Center within the next 24 hours. Bring any medications you are currently taking. Stay safe!`,
        };
        document.getElementById('followup-msg-text').value = msgs[followupType] || msgs['medical'];
    }
    updateSmsPreview();
    document.getElementById('assignModal').style.display = 'flex';
}

function closeAssignModal() {
    document.getElementById('assignModal').style.display = 'none';
}

function updateEtaLabel(val) {
    document.getElementById('custom-eta-group').style.display = val === 'custom' ? 'block' : 'none';
    updateSmsPreview();
}

function getEta() {
    const sel = document.getElementById('eta-select').value;
    if (sel === 'custom') {
        const v = document.getElementById('eta-custom').value;
        return v ? v + ' minutes' : '?';
    }
    return parseInt(sel) >= 60 ? '1 hour' : sel + ' minutes';
}

function updateSmsPreview() {
    const respName = document.getElementById('inp-responder-name')?.value || '[Responder Name]';
    const eta = getEta();
    document.getElementById('sms-preview-box').textContent =
        `BuligDiretso: Hi ${_md.name || '...'}, your ${_md.type || 'emergency'} report (${_md.id || ''}) has been received. ` +
        `Responder ${respName} is now ON THE WAY to your location at ${_md.location || '...'}. ` +
        `Estimated arrival: ${eta}. Please stay calm and keep your phone on. Stay safe!`;
}

function toggleFollowupMsg() {
    document.getElementById('followup-msg-area').style.display =
        document.getElementById('send-followup-chk').checked ? 'block' : 'none';
}

document.addEventListener('input', e => {
    if (e.target.id === 'inp-responder-name' || e.target.id === 'eta-custom') updateSmsPreview();
});
document.getElementById('assignModal').addEventListener('click', function(e) {
    if (e.target === this) closeAssignModal();
});
</script>