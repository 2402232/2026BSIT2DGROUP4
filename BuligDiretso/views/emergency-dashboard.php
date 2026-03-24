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
            // Sample emergency data (later replace with database)
            $emergencies = [
                ['id' => 'ER-KP376', 'status' => 'CRITICAL', 'type' => 'Medical', 'desc' => 'Heart attack at City', 'loc' => 'Central St.', 'color' => '#E74C3C'],
                ['id' => 'ER-GH201', 'status' => 'MODERATE', 'type' => 'Fire', 'desc' => 'Kitchen fire', 'loc' => '5th Ave', 'color' => '#F39C12'],
                ['id' => 'ER-TU190', 'status' => 'CRITICAL', 'type' => 'Medical', 'desc' => 'Severe bleeding', 'loc' => 'Park Rd.', 'color' => '#E74C3C'],
                ['id' => 'ER-QP443', 'status' => 'MODERATE', 'type' => 'Crime', 'desc' => 'Robbery in progress', 'loc' => 'Market St.', 'color' => '#F39C12'],
                ['id' => 'ER-MN928', 'status' => 'RESOLVED', 'type' => 'Traffic', 'desc' => 'Car accident', 'loc' => 'Highway 5', 'color' => '#27AE60'],
                ['id' => 'ER-FT837', 'status' => 'MODERATE', 'type' => 'Medical', 'desc' => 'Broken arm', 'loc' => 'School', 'color' => '#F39C12']
            ];

            foreach ($emergencies as $em) {
                ?>
                <div class="emergency-item">
                    <div class="emergency-top">
                        <span class="em-id"><?php echo $em['id']; ?></span>
                        <span class="em-status" style="background-color: <?php echo $em['color']; ?>">
                            <?php echo $em['status']; ?>
                        </span>
                        <span class="em-type"><?php echo $em['type']; ?></span>
                    </div>
                    <div class="emergency-info">
                        <p class="em-desc"><?php echo $em['desc']; ?></p>
                        <p class="em-loc">Location: <?php echo $em['loc']; ?></p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php include 'views/includes/footer.php'; ?>

<script src="<?php echo ASSETS_PATH . 'js/emergency-dashboard.js'; ?>"></script>
