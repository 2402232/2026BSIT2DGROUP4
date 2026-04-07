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
                ['slug' => 'cpr-instructions',    'title' => 'CPR Instructions',   'category' => 'Medical',  'read' => '5 min read', 'icon' => 'ri-heart-pulse-line'],
                ['slug' => 'treating-burns',      'title' => 'Treating Burns',      'category' => 'Medical',  'read' => '3 min read', 'icon' => 'ri-fire-line'],
                ['slug' => 'snake-bite-response', 'title' => 'Snake Bite Response', 'category' => 'Medical',  'read' => '4 min read', 'icon' => 'ri-alert-line'],
                ['slug' => 'earthquake-safety',   'title' => 'Earthquake Safety',   'category' => 'Disaster', 'read' => '6 min read', 'icon' => 'ri-earth-line'],
                ['slug' => 'choking-relief',      'title' => 'Choking Relief',      'category' => 'Medical',  'read' => '2 min read', 'icon' => 'ri-lungs-line'],
                ['slug' => 'flood-evacuation',    'title' => 'Flood Evacuation',    'category' => 'Disaster', 'read' => '5 min read', 'icon' => 'ri-flood-line'],
            ];

            foreach ($guides as $guide): ?>
            <a class="guide-item" 
               data-title="<?php echo strtolower($guide['title']); ?>"
               href="index.php?action=guide-detail&guide=<?php echo $guide['slug']; ?>"
               style="text-decoration:none; color:inherit; display:flex; align-items:center; justify-content:space-between;">
                <div class="guide-item-left">
                    <p class="guide-title">
                        <i class="<?php echo $guide['icon']; ?>" style="color:#22C55E; margin-right:6px; font-size:15px;"></i>
                        <?php echo $guide['title']; ?>
                    </p>
                    <div class="guide-meta">
                        <span class="guide-category"><?php echo $guide['category']; ?></span>
                        <span class="guide-read">
                            <i class="ri-time-line"></i>
                            <?php echo $guide['read']; ?>
                        </span>
                    </div>
                </div>
                <div class="guide-item-right">
                    <div class="guide-btn" aria-label="Read <?php echo $guide['title']; ?>">
                        <i class="ri-arrow-right-line"></i>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Empty search state -->
        <div class="no-results" id="noResults" style="display:none;">
            <i class="ri-search-line"></i>
            <p>No guides found</p>
        </div>

    </div>
</div>

<?php include 'views/includes/footer.php'; ?>

<script src="<?php echo ASSETS_PATH . 'js/safety-guides.js'; ?>"></script>