<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/emergency-tracking.css'; ?>">

<div class="tracking-wrapper">

    <a class="back-link" href="index.php?action=dashboard">← Back to Home</a>

    <div class="tracking-card">

        <div class="tracking-header">
            <i class="ri-send-plane-line tracking-icon"></i>
            <h1>Real-Time Tracking</h1>
        </div>

        <div class="tracking-body" id="trackingBody">
            <!-- Empty state (shown when no active emergencies) -->
            <div class="empty-state" id="emptyState">
                <i class="ri-send-plane-line empty-icon"></i>
                <p class="empty-text">No active emergency requests</p>
                <a href="<?php echo BASE_URL; ?>report-system.php?action=report-emergency" class="report-link">
                    Report an Emergency
                </a>
            </div>
        </div>

    </div>

</div>


<script src="<?= ASSETS_PATH ?>js/emergency-tracking.js"></script>
<?php include 'views/includes/footer.php'; ?>