<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/admin-users-needing-help.css'; ?>">
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/assign-modal.css'; ?>">

<?php require_once VIEW_PATH . 'includes/header.php'; ?>

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
                <?php if (empty($emergencies)): ?>
                    <p class="no-emergencies">No emergency reports found.</p>
                <?php else: ?>
                    <?php foreach ($emergencies as $emergency): ?>
                        <div class="emergency-card">
                            <div class="card-header">
                                <div class="emergency-id">
                                    <span class="badge badge-<?php echo strtolower($emergency['severity']); ?>">
                                        <?php echo strtoupper($emergency['severity']); ?>
                                    </span>
                                    <span class="badge badge-<?php echo $emergency['status']; ?>">
                                        <?php echo strtoupper($emergency['status']); ?>
                                    </span>
                                    <h3><?php echo htmlspecialchars($emergency['report_code']); ?></h3>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <div class="info-row">
                                    <div class="info-item">
                                        <i class="ri-user-line"></i>
                                        <div>
                                            <span class="label">User Information</span>
                                            <span class="value"><?php echo htmlspecialchars($emergency['first_name'] . ' ' . $emergency['last_name']); ?></span>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="ri-time-line"></i>
                                        <div>
                                            <span class="label">Created On</span>
                                            <span class="value"><?php echo date('h:i:s A', strtotime($emergency['created_at'])); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-item">
                                        <i class="ri-phone-line"></i>
                                        <div>
                                            <span class="label">Contact Number</span>
                                            <span class="value"><?php echo htmlspecialchars($emergency['phone']); ?></span>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="ri-calendar-line"></i>
                                        <div>
                                            <span class="label">Updated On</span>
                                            <span class="value"><?php echo date('h:i:s A', strtotime($emergency['updated_at'])); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-item full-width">
                                        <i class="ri-map-pin-line"></i>
                                        <div>
                                            <span class="label">Location</span>
                                            <span class="value"><?php echo htmlspecialchars($emergency['location']); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-item full-width">
                                        <i class="ri-alert-line"></i>
                                        <div>
                                            <span class="label">Emergency Type</span>
                                            <span class="value"><?php echo htmlspecialchars($emergency['emergency_type']); ?> Emergency</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-item full-width">
                                        <i class="ri-file-text-line"></i>
                                        <div>
                                            <span class="label">Description</span>
                                            <span class="value"><?php echo htmlspecialchars($emergency['description'] ?: 'No description provided'); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="responder-section">
                                    <span class="responder-label">Responder Assigned:</span>
                                    <span class="no-responder">No responder assigned yet</span>
                                </div>
                            </div>

                            <div class="card-actions">
                                <button class="btn btn-assign" onclick="openAssignModal('<?php echo $emergency['report_code']; ?>','<?php echo htmlspecialchars($emergency['first_name'] . ' ' . $emergency['last_name']); ?>','<?php echo htmlspecialchars($emergency['phone']); ?>','<?php echo htmlspecialchars($emergency['location']); ?>','<?php echo htmlspecialchars($emergency['emergency_type']); ?> Emergency',true,'<?php echo strtolower($emergency['emergency_type']); ?>')">Assign &amp; Notify</button>
                                <button class="btn btn-view">View User Details</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
                        <button class="btn btn-assign" onclick="openAssignModal('ER-C3NDP','Maria Santos','+63 923 456 7890','456 Oak Avenue, Mandurriao','Fire Emergency',false,'')">Assign &amp; Notify</button>
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
                        <button class="btn btn-assign" onclick="openAssignModal('ER-J7WBY','Pedro Garcia','+63 934 567 8901','789 Pine Street, Jaro District','Police Assistance',false,'')">Assign &amp; Notify</button>
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
                        <button class="btn btn-assign" onclick="openAssignModal('ER-D7T2S','Ana Reyes','+63 945 678 9012','321 Elm Road, Molo District','Vehicle Accident',true,'pregnancy')">Assign &amp; Notify</button>
                        <button class="btn btn-view">View User Details</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
        <p class="modal-user-info">👤 <strong id="modal-user-name"></strong> | 📍 <span id="modal-user-loc"></span></p>

        <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=sms-assign-responder">
            <input type="hidden" name="emergency_id" id="f-emergency-id">
            <input type="hidden" name="user_phone" id="f-user-phone">
            <input type="hidden" name="user_name" id="f-user-name">
            <input type="hidden" name="location" id="f-location">
            <input type="hidden" name="emergency_type" id="f-emergency-type">

            <div class="modal-section">
                <h4><i class="ri-user-star-line"></i> Responder Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label>Responder Name</label>
                        <input type="text" name="responder_name" id="inp-responder-name" required>
                    </div>
                    <div class="form-group">
                        <label>Responder Phone</label>
                        <input type="text" name="responder_phone" placeholder="09171234567 (optional)">
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
                        <input type="number" name="eta_custom" id="eta-custom" min="1" max="240" placeholder="25">
                    </div>
                </div>
            </div>

            <div class="modal-section">
                <h4><i class="ri-message-2-line"></i> SMS Preview</h4>
                <div class="sms-preview" id="sms-preview-box"></div>
            </div>

            <div class="modal-section" id="followup-section" style="display:none;">
                <h4><i class="ri-hospital-line"></i> Follow-up Clinic Reminder</h4>
                <label class="toggle-label">
                    <input type="checkbox" name="send_followup" id="send-followup-chk" onchange="toggleFollowupMsg()">
                    Send Barangay clinic follow-up SMS
                </label>
                <div id="followup-msg-area" style="display:none;margin-top:10px;">
                    <div class="form-group">
                        <label>Follow-up Message</label>
                        <textarea name="followup_message" id="followup-msg-text" rows="4"></textarea>
                    </div>
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
<script>
let _md = {};
function openAssignModal(id, name, phone, location, type, needsFollowup, followupType) {
    _md = { id, name, phone, location, type, needsFollowup, followupType };
    document.getElementById('f-emergency-id').value = id;
    document.getElementById('f-user-phone').value = phone;
    document.getElementById('f-user-name').value = name;
    document.getElementById('f-location').value = location;
    document.getElementById('f-emergency-type').value = type;
    document.getElementById('modal-em-id').textContent = id;
    document.getElementById('modal-em-type').textContent = type;
    document.getElementById('modal-user-name').textContent = name;
    document.getElementById('modal-user-loc').textContent = location;

    document.getElementById('followup-section').style.display = needsFollowup ? 'block' : 'none';
    if (needsFollowup) {
        const msgs = {
            pregnancy: `BuligDiretso: Hi ${name}, as a follow-up, please visit your Barangay Health Center for a prenatal check-up. Bring your records and follow your health worker's advice.`,
            children: `BuligDiretso: Hi ${name}, as a follow-up, your child may need a check-up at the Barangay Health Center within 24-48 hours.`,
            medical: `BuligDiretso: Hi ${name}, as a follow-up, please visit your Barangay Health Center within 24 hours for further assessment.`
        };
        document.getElementById('followup-msg-text').value = msgs[followupType] || msgs.medical;
    }
    updateSmsPreview();
    document.getElementById('assignModal').style.display = 'flex';
}
function closeAssignModal() { document.getElementById('assignModal').style.display = 'none'; }
function updateEtaLabel(val) {
    document.getElementById('custom-eta-group').style.display = val === 'custom' ? 'block' : 'none';
    updateSmsPreview();
}
function getEta() {
    const sel = document.getElementById('eta-select').value;
    if (sel === 'custom') {
        const v = document.getElementById('eta-custom').value;
        return v ? `${v} minutes` : '?';
    }
    return parseInt(sel, 10) >= 60 ? '1 hour' : `${sel} minutes`;
}
function updateSmsPreview() {
    const responder = document.getElementById('inp-responder-name').value || '[Responder Name]';
    const eta = getEta();
    document.getElementById('sms-preview-box').textContent =
        `BuligDiretso: Hi ${_md.name || ''}, your ${_md.type || 'emergency'} report (#${_md.id || ''}) has been received. ` +
        `Responder ${responder} is now ON THE WAY to your location at ${_md.location || ''}. ` +
        `Estimated arrival: ${eta}. Please stay calm and keep your phone on. Stay safe!`;
}
function toggleFollowupMsg() {
    document.getElementById('followup-msg-area').style.display =
        document.getElementById('send-followup-chk').checked ? 'block' : 'none';
}
document.addEventListener('input', function (e) {
    if (e.target.id === 'inp-responder-name' || e.target.id === 'eta-custom') updateSmsPreview();
});
</script>