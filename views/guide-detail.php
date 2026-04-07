<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/safety-guides.css'; ?>">
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/guide-detail.css'; ?>">

<?php
require_once MODEL_PATH . 'safety_guides_data.php';
$guides = get_safety_guides_catalog();

$slug  = $_GET['guide'] ?? '';
$guide = $guides[$slug] ?? null;

if (!$guide): ?>
    <div class="guides-wrapper" style="text-align:center; padding-top:120px;">
        <h2>Guide not found.</h2>
        <a href="index.php?action=safety-guides" class="back-link">← Back to Safety Guides</a>
    </div>
<?php else: ?>

<div class="detail-wrapper">

    <a class="back-link" href="index.php?action=safety-guides">← Back to Safety Guides</a>

    <!-- Hero -->
    <div class="detail-hero">
        <div class="detail-hero-icon">
            <i class="<?php echo $guide['icon']; ?>"></i>
        </div>
        <div>
            <span class="guide-category"><?php echo $guide['category']; ?></span>
            <h1><?php echo $guide['title']; ?></h1>
            <p class="detail-intro"><?php echo $guide['intro']; ?></p>
            <span class="guide-read"><i class="ri-time-line"></i> <?php echo htmlspecialchars($guide['read']); ?></span>
            <div class="detail-hero-actions">
                <a class="btn-download-pdf" href="<?php echo BASE_URL; ?>index.php?action=guide-pdf&guide=<?php echo urlencode($slug); ?>">
                    <i class="ri-file-pdf-2-line"></i> Download PDF
                </a>
            </div>
        </div>
    </div>

    <div class="detail-body">

        <!-- Video Section -->
        <?php if (!empty($guide['video_id'])): ?>
        <div class="detail-card">
            <h2 class="section-title"><i class="ri-play-circle-line"></i> Video Tutorial</h2>
            <div class="video-container">
                <iframe
                    src="https://www.youtube.com/embed/<?php echo htmlspecialchars($guide['video_id']); ?>?rel=0"
                    title="<?php echo htmlspecialchars($guide['title']); ?> Tutorial"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
        <?php endif; ?>

        <!-- Step-by-Step Instructions -->
        <div class="detail-card">
            <h2 class="section-title"><i class="ri-list-ordered"></i> Step-by-Step Instructions</h2>
            <div class="steps-list">
                <?php foreach ($guide['steps'] as $step): ?>
                <div class="step-item">
                    <div class="step-num"><?php echo $step['num']; ?></div>
                    <div class="step-content">
                        <h3><?php echo $step['title']; ?></h3>
                        <p><?php echo $step['desc']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Tips -->
        <div class="detail-card tips-card">
            <h2 class="section-title"><i class="ri-lightbulb-line"></i> Important Tips</h2>
            <ul class="tips-list">
                <?php foreach ($guide['tips'] as $tip): ?>
                <li><i class="ri-checkbox-circle-fill"></i> <?php echo $tip; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Emergency CTA -->
        <div class="detail-card emergency-cta">
            <i class="ri-phone-line cta-icon"></i>
            <div>
                <h3>In a Real Emergency?</h3>
                <p>Do not rely on this guide alone. Call emergency services immediately.</p>
            </div>
            <a href="index.php?action=report-system" class="cta-btn">
                <i class="ri-alarm-warning-line"></i> Report Emergency
            </a>
        </div>

    </div>
</div>

<?php endif; ?>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
<script src="<?php echo ASSETS_PATH . 'js/safety-guides.js'; ?>"></script>