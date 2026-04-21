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
                <div class="ep-icon-wrap">
                    <i class="ri-lock-password-line"></i>
                </div>
                <h1 class="profile-name">Change Password</h1>
                <p class="profile-role">Keep your account secure</p>
            </div>

            <!-- Form -->
            <div class="profile-body">
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=update-password"
                      id="changePasswordForm">

                    <div class="info-section">
                        <h2 class="section-title">Update Password</h2>
                        <div class="info-grid edit-grid cp-grid">

                            <div class="info-item edit-field full-span">
                                <label for="current_password"><i class="ri-lock-line"></i> Current Password <span class="req">*</span></label>
                                <div class="pw-wrap">
                                    <input type="password" id="current_password" name="current_password"
                                           required placeholder="Enter your current password">
                                    <button type="button" class="pw-toggle" data-target="current_password" title="Show/hide">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="info-item edit-field">
                                <label for="new_password"><i class="ri-lock-2-line"></i> New Password <span class="req">*</span></label>
                                <div class="pw-wrap">
                                    <input type="password" id="new_password" name="new_password"
                                           required minlength="8" placeholder="Min. 8 characters">
                                    <button type="button" class="pw-toggle" data-target="new_password" title="Show/hide">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                                <!-- Strength meter -->
                                <div class="strength-bar-wrap">
                                    <div class="strength-bar" id="strengthBar"></div>
                                </div>
                                <small class="strength-label" id="strengthLabel"></small>
                            </div>

                            <div class="info-item edit-field">
                                <label for="confirm_password"><i class="ri-lock-2-line"></i> Confirm New Password <span class="req">*</span></label>
                                <div class="pw-wrap">
                                    <input type="password" id="confirm_password" name="confirm_password"
                                           required placeholder="Repeat new password">
                                    <button type="button" class="pw-toggle" data-target="confirm_password" title="Show/hide">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                                <small class="match-label" id="matchLabel"></small>
                            </div>

                        </div>
                    </div>

                    <!-- Requirements hint -->
                    <div class="pw-requirements">
                        <p><i class="ri-information-line"></i> Password requirements:</p>
                        <ul>
                            <li id="req-len"   class="req-item"><i class="ri-close-circle-line"></i> At least 8 characters</li>
                            <li id="req-upper" class="req-item"><i class="ri-close-circle-line"></i> One uppercase letter</li>
                            <li id="req-num"   class="req-item"><i class="ri-close-circle-line"></i> One number</li>
                        </ul>
                    </div>

                    <!-- Actions -->
                    <div class="profile-actions">
                        <button type="submit" class="btn-edit btn-password-save" id="saveBtn" disabled>
                            <i class="ri-shield-check-line"></i> Update Password
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
// ── Show / hide password toggles ─────────────────────────────
document.querySelectorAll('.pw-toggle').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const input = document.getElementById(this.dataset.target);
        const icon  = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'ri-eye-off-line';
        } else {
            input.type = 'password';
            icon.className = 'ri-eye-line';
        }
    });
});

// ── Strength meter ────────────────────────────────────────────
const newPwInput   = document.getElementById('new_password');
const strengthBar  = document.getElementById('strengthBar');
const strengthLbl  = document.getElementById('strengthLabel');
const reqLen       = document.getElementById('req-len');
const reqUpper     = document.getElementById('req-upper');
const reqNum       = document.getElementById('req-num');

function checkStrength(pw) {
    let score = 0;
    const hasLen   = pw.length >= 8;
    const hasUpper = /[A-Z]/.test(pw);
    const hasNum   = /[0-9]/.test(pw);
    const hasSpec  = /[^A-Za-z0-9]/.test(pw);

    setReq(reqLen,   hasLen);
    setReq(reqUpper, hasUpper);
    setReq(reqNum,   hasNum);

    if (hasLen)   score++;
    if (hasUpper) score++;
    if (hasNum)   score++;
    if (hasSpec)  score++;

    const levels = [
        { pct: 0,   cls: '',          label: '' },
        { pct: 25,  cls: 'str-weak',  label: 'Weak' },
        { pct: 50,  cls: 'str-fair',  label: 'Fair' },
        { pct: 75,  cls: 'str-good',  label: 'Good' },
        { pct: 100, cls: 'str-great', label: 'Strong' },
    ];
    const lvl = levels[score] || levels[0];
    strengthBar.style.width = lvl.pct + '%';
    strengthBar.className   = 'strength-bar ' + lvl.cls;
    strengthLbl.textContent = lvl.label;

    return hasLen; // must be at least 8 chars to enable save
}

function setReq(el, met) {
    el.classList.toggle('req-met',  met);
    el.classList.toggle('req-fail', !met);
    el.querySelector('i').className = met ? 'ri-checkbox-circle-line' : 'ri-close-circle-line';
}

// ── Match check ───────────────────────────────────────────────
const confirmInput = document.getElementById('confirm_password');
const matchLbl     = document.getElementById('matchLabel');
const saveBtn      = document.getElementById('saveBtn');

function updateSaveBtn() {
    const pwOk     = checkStrength(newPwInput.value);
    const matches  = newPwInput.value === confirmInput.value && confirmInput.value !== '';
    const curFilled= document.getElementById('current_password').value.trim() !== '';

    if (confirmInput.value !== '') {
        matchLbl.textContent  = matches ? '✓ Passwords match' : '✗ Passwords do not match';
        matchLbl.className    = 'match-label ' + (matches ? 'match-ok' : 'match-fail');
    } else {
        matchLbl.textContent = '';
    }

    saveBtn.disabled = !(pwOk && matches && curFilled);
}

newPwInput.addEventListener('input', updateSaveBtn);
confirmInput.addEventListener('input', updateSaveBtn);
document.getElementById('current_password').addEventListener('input', updateSaveBtn);

// ── Prevent submission if mismatch ───────────────────────────
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    if (newPwInput.value !== confirmInput.value) {
        e.preventDefault();
        alert('Passwords do not match.');
        return;
    }
    if (newPwInput.value.length < 8) {
        e.preventDefault();
        alert('New password must be at least 8 characters.');
    }
});
</script>
