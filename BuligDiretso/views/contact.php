<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/safety-guides.css'; ?>">
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/contact.css'; ?>">

<div class="guides-wrapper">

    <a class="back-link" href="index.php?action=dashboard">← Back to Home</a>

    <div class="contact-grid">

        <!-- Left: Hotlines -->
        <div class="guides-card hotlines-card">
            <div class="guides-header">
                <i class="ri-phone-line guides-title-icon"></i>
                <h1>Emergency Hotlines</h1>
            </div>
            <p class="hotlines-desc">For life-threatening emergencies, call these numbers directly. Do not wait — call immediately.</p>

            <?php
            $hotlines = [
                ['name' => 'LDRRMO',                       'number' => '0951 682 1504', 'desc' => 'Local Disaster Risk Reduction & Management', 'icon' => 'ri-shield-line',       'color' => '#2563EB'],
                ['name' => 'MHO Isabela',                  'number' => '0963 156 6032', 'desc' => 'Municipal Health Office',                     'icon' => 'ri-heart-pulse-line', 'color' => '#16A34A'],
                ['name' => 'ILASMDH',                      'number' => '0947 415 4761', 'desc' => 'Isabela Local Area Social & Medical Dev. Hub', 'icon' => 'ri-hospital-line',    'color' => '#9333EA'],
                ['name' => 'PNP Isabela',                  'number' => '0999 659 0677', 'desc' => 'Philippine National Police',                   'icon' => 'ri-police-car-line',  'color' => '#1C2333'],
                ['name' => 'NOCECO',                       'number' => '0998 570 2725', 'desc' => 'Northern Cebu Electric Cooperative',           'icon' => 'ri-flashlight-line',  'color' => '#D97706'],
                ['name' => 'BFP',                          'number' => '0970 465 9383', 'desc' => 'Bureau of Fire Protection',                    'icon' => 'ri-fire-line',        'color' => '#DC2626'],
            ];
            ?>

            <div class="hotline-list">
                <?php foreach ($hotlines as $h): ?>
                <div class="hotline-item">
                    <div class="hotline-icon" style="background:<?php echo $h['color']; ?>18; color:<?php echo $h['color']; ?>;">
                        <i class="<?php echo $h['icon']; ?>"></i>
                    </div>
                    <div class="hotline-info">
                        <p class="hotline-name"><?php echo $h['name']; ?></p>
                        <p class="hotline-desc"><?php echo $h['desc']; ?></p>
                    </div>
                    <a class="hotline-call" href="tel:<?php echo preg_replace('/\s/', '', $h['number']); ?>">
                        <i class="ri-phone-fill"></i>
                        <?php echo $h['number']; ?>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Right: Contact Form -->
        <div>
            <div class="guides-card contact-form-card">
                <div class="guides-header">
                    <i class="ri-mail-send-line guides-title-icon"></i>
                    <h1>Send a Message</h1>
                </div>
                <p class="hotlines-desc">Have a question or feedback? Fill out the form below and we'll get back to you.</p>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-input" placeholder="Juan dela Cruz" />
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" class="form-input" placeholder="juan@example.com" />
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <select class="form-input">
                        <option value="">Select a topic...</option>
                        <option>Report a Bug</option>
                        <option>Account Issue</option>
                        <option>Emergency Response Concern</option>
                        <option>General Feedback</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea class="form-input" rows="5" placeholder="Describe your concern here..."></textarea>
                </div>
                <button class="submit-btn" onclick="handleSubmit(this)">
                    <i class="ri-send-plane-line"></i> Send Message
                </button>
                <div class="form-success" id="formSuccess" style="display:none;">
                    <i class="ri-checkbox-circle-fill"></i> Message sent! We'll respond within 24 hours.
                </div>
            </div>

            <!-- Quick Links -->
            <div class="guides-card quick-links-card" style="margin-top:16px;">
                <div class="guides-header">
                    <i class="ri-links-line guides-title-icon"></i>
                    <h1>Quick Links</h1>
                </div>
                <div class="quick-links-grid">
                    <a href="index.php?action=safety-guides" class="quick-link-item">
                        <i class="ri-book-open-line"></i>
                        <span>Safety Guides</span>
                    </a>
                    <a href="index.php?action=faq" class="quick-link-item">
                        <i class="ri-question-answer-line"></i>
                        <span>FAQ</span>
                    </a>
                    <a href="index.php?action=report-system" class="quick-link-item">
                        <i class="ri-alarm-warning-line"></i>
                        <span>Report Emergency</span>
                    </a>
                    <a href="index.php?action=emergency-tracking" class="quick-link-item">
                        <i class="ri-map-pin-line"></i>
                        <span>Track Response</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'views/includes/footer.php'; ?>

<script>
function handleSubmit(btn) {
    btn.disabled = true;
    btn.innerHTML = '<i class="ri-loader-4-line"></i> Sending...';
    setTimeout(() => {
        btn.style.display = 'none';
        document.getElementById('formSuccess').style.display = 'flex';
    }, 1200);
}
</script>