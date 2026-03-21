<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/safety-guides.css'; ?>">
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/faq.css'; ?>">

<div class="guides-wrapper">

    <a class="back-link" href="index.php?action=dashboard">← Back to Home</a>

    <div class="guides-card">

        <div class="guides-header">
            <i class="ri-question-answer-line guides-title-icon"></i>
            <h1>Frequently Asked Questions</h1>
        </div>

        <div class="guides-search">
            <i class="ri-search-line search-icon"></i>
            <input type="text" id="faqSearch" placeholder="Search questions..." autocomplete="off" />
        </div>

        <?php
        $faqs = [
            [
                'q' => 'What is BuligDiretso?',
                'a' => 'BuligDiretso is an emergency response platform designed to connect community members with rapid assistance during disasters and medical emergencies. It allows users to report incidents, track emergency responders, and access life-saving safety guides.'
            ],
            [
                'q' => 'How do I report an emergency?',
                'a' => 'Click on "Report" in the navigation bar or go to the Report System page. Fill in the incident type, your location, and any additional details. Your report will be sent immediately to local emergency responders.'
            ],
            [
                'q' => 'Is my personal information safe?',
                'a' => 'Yes. We take data privacy seriously. Your personal information is only shared with authorized emergency responders when you submit a report. We do not sell or share your data with third parties.'
            ],
            [
                'q' => 'What should I do during a flood?',
                'a' => 'Move to higher ground immediately. Do not walk or drive through floodwater. Turn off utilities if safe to do so. Follow official evacuation routes and report your situation through BuligDiretso. Check our Flood Evacuation guide for full instructions.'
            ],
            [
                'q' => 'Can I use this app without internet?',
                'a' => 'Currently, BuligDiretso requires an internet connection to submit reports and track emergencies. We recommend saving emergency hotline numbers offline as a backup. You can find all hotlines in the Contact page.'
            ],
            [
                'q' => 'How do I track my emergency report?',
                'a' => 'After submitting a report, you will be redirected to the Emergency Tracking page where you can see the real-time status and location of the assigned responder.'
            ],
            [
                'q' => 'What types of emergencies can I report?',
                'a' => 'You can report medical emergencies (cardiac arrest, injury, illness), natural disasters (flood, earthquake, fire), accidents, missing persons, and other community safety concerns.'
            ],
            [
                'q' => 'How do I contact support?',
                'a' => 'You can reach us through the Contact & Support page linked in the footer. For real emergencies, always call your local emergency number directly.'
            ],
            [
                'q' => 'Are the safety guides accurate?',
                'a' => 'Our safety guides are based on internationally recognized first aid and disaster response protocols. However, they are educational guides only. Always call emergency services in a real emergency.'
            ],
            [
                'q' => 'How do I update my profile information?',
                'a' => 'Go to your Profile page from the dashboard. You can update your name, contact number, address, and other personal details there.'
            ],
        ];
        ?>

        <div class="faq-list" id="faqList">
            <?php foreach ($faqs as $i => $faq): ?>
            <div class="faq-item" data-question="<?php echo strtolower(htmlspecialchars($faq['q'])); ?>">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <span><?php echo htmlspecialchars($faq['q']); ?></span>
                    <i class="ri-arrow-down-s-line faq-chevron"></i>
                </button>
                <div class="faq-answer">
                    <p><?php echo htmlspecialchars($faq['a']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="no-results" id="noResults" style="display:none;">
            <i class="ri-question-line"></i>
            <p>No questions found</p>
        </div>

        <div class="faq-cta">
            <p>Still have questions?</p>
            <a href="index.php?action=contact" class="faq-cta-btn">
                <i class="ri-mail-send-line"></i> Contact Support
            </a>
        </div>

    </div>
</div>

<?php include 'views/includes/footer.php'; ?>

<style>
/* Quick inline styles for FAQ — also written to faq.css */
</style>
<script>
function toggleFaq(btn) {
    const item = btn.closest('.faq-item');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(el => el.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
}

const faqSearch = document.getElementById('faqSearch');
const faqList   = document.getElementById('faqList');
const noResults = document.getElementById('noResults');

faqSearch.addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    const items = faqList.querySelectorAll('.faq-item');
    let count = 0;
    items.forEach(item => {
        const text = item.getAttribute('data-question') || '';
        const match = text.includes(q);
        item.style.display = match ? '' : 'none';
        if (match) count++;
    });
    noResults.style.display = count === 0 ? 'flex' : 'none';
});
</script>