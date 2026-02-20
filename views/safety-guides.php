<?php require_once VIEW_PATH . 'includes/header.php';?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/safety-guides.css'; ?>">

<div class="guides-wrapper">

    <a class="back-link" href="index.php?action=dashboard">← Back to Home</a>

    <div class="guides-card">

        <!-- Header -->
        <div class="guides-header">
            <i class="ri-book-open-line guides-title-icon"></i>
            <h1>First Aid &amp; Safety Guides</h1>
        </div>

        <!-- Search -->
        <div class="guides-search">
            <i class="ri-search-line search-icon"></i>
            <input
                type="text"
                id="guideSearch"
                placeholder="Search guides..."
                autocomplete="off"
            />
        </div>

        <!-- Guide List -->
        <div class="guide-list" id="guideList">
            <?php
            $guides = [
                ['title' => 'CPR Instructions',     'category' => 'Medical', 'read' => '5 min read'],
                ['title' => 'Treating Burns',        'category' => 'Medical', 'read' => '3 min read'],
                ['title' => 'Snake Bite Response',   'category' => 'Medical', 'read' => '4 min read'],
                ['title' => 'Earthquake Safety',     'category' => 'Medical', 'read' => '6 min read'],
                ['title' => 'Choking Relief',        'category' => 'Medical', 'read' => '2 min read'],
                ['title' => 'Flood Evacuation',      'category' => 'Medical', 'read' => '5 min read'],
            ];

            foreach ($guides as $guide): ?>
            <div class="guide-item" data-title="<?php echo strtolower($guide['title']); ?>">
                <div class="guide-item-left">
                    <p class="guide-title"><?php echo $guide['title']; ?></p>
                    <div class="guide-meta">
                        <span class="guide-category"><?php echo $guide['category']; ?></span>
                        <span class="guide-read">
                            <i class="ri-time-line"></i>
                            <?php echo $guide['read']; ?>
                        </span>
                    </div>
                </div>
                <div class="guide-item-right">
                    <button class="guide-btn" aria-label="Read <?php echo $guide['title']; ?>">
                        <i class="ri-book-open-line"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty search state -->
        <div class="no-results" id="noResults" style="display:none;">
            <i class="ri-search-line"></i>
            <p>No guides found</p>
        </div>

    </div>
</div>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>

<script src="<?php echo ASSETS_PATH . 'js/safety-guides.js'; ?>"></script>
