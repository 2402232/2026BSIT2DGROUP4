  <?php require_once VIEW_PATH . 'includes/header.php';?>
  <link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/emergency-dashboard.css'; ?>">

  <!-- Main Dashboard Area -->
    <div class="dashboard-content">
        <a class="back-link" href="index.php?action=dashboard">← Back to Home</a>
        
        <h1>Emergency Dashboard</h1>

        <!-- Status Summary Cards -->
        <div class="status-cards">
            <div class="card critical">
                <p>Critical</p>
                <h2>2</h2>
            </div>
            <div class="card high-priority">
                <p>High Priority</p>
                <h2>2</h2>
            </div>
            <div class="card moderate">
                <p>Moderate</p>
                <h2>2</h2>
            </div>
            <div class="card low-priority">
                <p>Low Priority</p>
                <h2>1</h2>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="filters">
            <button class="filter-btn active">All emergencies</button>
            <button class="filter-btn">CRITICAL</button>
            <button class="filter-btn">MODERATE</button>
            <button class="filter-btn">RESOLVED</button>
            <button class="filter-btn">UNTRACKED</button>
        </div>

        <!-- Emergency List -->
        <div class="emergency-list">
            <?php
            if (empty($emergencies)) {
                echo '<p class="no-emergencies">No emergency reports found.</p>';
            } else {
                foreach ($emergencies as $emergency) {
                    $statusColor = match($emergency['status']) {
                        'pending' => '#F39C12',
                        'responding' => '#E74C3C',
                        'resolved' => '#27AE60',
                        'cancelled' => '#95A5A6',
                        default => '#95A5A6'
                    };
                    ?>
                    <div class="emergency-item">
                        <div class="emergency-top">
                            <span class="em-id"><?php echo htmlspecialchars($emergency['report_code']); ?></span>
                            <span class="em-status" style="background-color: <?php echo $statusColor; ?>">
                                <?php echo htmlspecialchars(strtoupper($emergency['status'])); ?>
                            </span>
                            <span class="em-type"><?php echo htmlspecialchars($emergency['emergency_type']); ?></span>
                        </div>
                        <div class="emergency-info">
                            <p class="em-desc"><?php echo htmlspecialchars($emergency['description'] ?: 'No description'); ?></p>
                            <p class="em-loc">Location: <?php echo htmlspecialchars($emergency['location']); ?></p>
                            <p class="em-time"><?php echo date('M d, Y H:i', strtotime($emergency['created_at'])); ?></p>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>

<?php include 'views/includes/footer.php'; ?>

<script src="<?php echo ASSETS_PATH . 'js/emergency-dashboard.js'; ?>"></script>
