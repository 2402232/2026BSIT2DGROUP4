<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/admin-dashboard.css'; ?>">

<div class="admin-wrapper">
    
    <!-- Top Header -->
    <div class="top-header">
        <span class="admin-badge">
            <span class="badge-icon"></span>
            Admin Dashboard
        </span>
    </div>

    <!-- Back Link -->
    <div class="back-nav">
        <a href="index.php" class="back-link">← Back to Home</a>
    </div>

    <!-- Tab Navigation -->
    <div class="tab-nav">
        <button class="tab-btn active">Overview</button>
        <button class="tab-btn">Users Needing Help</button>
        <button class="tab-btn">Responders</button>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        
        <!-- Total Responses -->
        <div class="stat-card">
            <div class="stat-header">
                <span>Total Responses</span>
                <span class="stat-icon"></span>
            </div>
            <div class="stat-number">20</div>
            <div class="stat-change positive">+5% vs last month</div>
        </div>

        <!-- Active Now -->
        <div class="stat-card">
            <div class="stat-header">
                <span>Active Now</span>
                <span class="stat-icon green">✓</span>
            </div>
            <div class="stat-number">15</div>
            <div class="stat-change">Available for dispatch</div>
        </div>

        <!-- On Duty -->
        <div class="stat-card">
            <div class="stat-header">
                <span>On Duty</span>
                <span class="stat-icon red"></span>
            </div>
            <div class="stat-number">2</div>
            <div class="stat-change">Currently responding</div>
        </div>

        <!-- Average Response Time -->
        <div class="stat-card">
            <div class="stat-header">
                <span>Average Response Time</span>
                <span class="stat-icon orange"></span>
            </div>
            <div class="stat-number">3.4 min</div>
            <div class="stat-change">2 min faster than target</div>
        </div>

    </div>

    <!-- Active Emergencies Section -->
    <div class="section-header">
        <span class="section-icon"></span>
        <h2>Active Emergencies-User Needing Help</h2>
    </div>

    <!-- Emergency List -->
    <div class="emergency-list">
        
        <?php
        // Sample emergency data
        $emergencies = [
            ['id' => 'ER-A373K', 'status' => 'CRITICAL', 'badge' => 'Responding', 'name' => 'Carlos Mendoza', 'type' => 'Medical', 'time' => '2024-02-08 13:45', 'location' => 'Makati City', 'details' => ['Chest pain', 'Difficulty breathing', 'Patient conscious but in severe pain']],
            ['id' => 'ER-A373K', 'status' => 'MODERATE', 'badge' => 'Responding', 'name' => 'Carlos Mendoza', 'type' => 'Fire', 'time' => '2024-02-08 13:30', 'location' => 'Quezon City', 'details' => ['Kitchen fire', 'Smoke detected', 'Residents evacuating']],
            ['id' => 'ER-A373K', 'status' => 'CRITICAL', 'badge' => 'Responding', 'name' => 'Carlos Mendoza', 'type' => 'Medical', 'time' => '2024-02-08 13:20', 'location' => 'Manila', 'details' => ['Car accident', 'Multiple injuries', 'Road blocked, require police assist']],
            ['id' => 'ER-A373K', 'status' => 'RESOLVED', 'badge' => 'Pending', 'name' => 'Carlos Mendoza', 'type' => 'Medical', 'time' => '2024-02-08 13:10', 'location' => 'Pasig', 'details' => ['Resolved', 'Patient stabilized']]
        ];

        foreach ($emergencies as $em) {
            $statusClass = strtolower(str_replace(' ', '-', $em['status']));
            ?>
            <div class="emergency-item">
                <div class="em-header">
                    <div class="em-left">
                        <span class="em-id"><?php echo $em['id']; ?></span>
                        <span class="em-status <?php echo $statusClass; ?>"><?php echo $em['status']; ?></span>
                        <span class="em-badge"><?php echo $em['badge']; ?></span>
                    </div>
                </div>
                <div class="em-body">
                    <p class="em-name">User: <?php echo $em['name']; ?></p>
                    <p class="em-detail"><span class="icon"></span> <?php echo $em['type']; ?></p>
                    <p class="em-detail"><span class="icon"></span> <?php echo $em['time']; ?></p>
                    <p class="em-detail"><span class="icon"></span> <?php echo $em['location']; ?></p>
                    <ul class="em-details-list">
                        <?php foreach ($em['details'] as $detail) { ?>
                            <li><?php echo $detail; ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php
        }
        ?>

    </div>

    <!-- Assign Responders Button -->
    <button class="assign-btn">Assign Responders</button>

    <!-- Bottom Stats Section -->
    <div class="bottom-stats">
        
        <!-- Response Time by Responders -->
        <div class="stats-box">
            <div class="stats-box-header">
                <h3>Response Time by Responders</h3>
                <span class="stats-icon">⋯</span>
            </div>
            <div class="responders-list">
                <div class="responder-row">
                    <span class="responder-name">Carlos Mendoza</span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 85%"></div>
                    </div>
                    <span class="time-text">3.2 min</span>
                </div>
                <div class="responder-row">
                    <span class="responder-name">Jane Smith</span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 75%"></div>
                    </div>
                    <span class="time-text">4.1 min</span>
                </div>
                <div class="responder-row">
                    <span class="responder-name">Mike Johnson</span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 90%"></div>
                    </div>
                    <span class="time-text">2.8 min</span>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="stats-box">
            <div class="stats-box-header">
                <h3>Top Performers</h3>
                <span class="stats-icon">⋯</span>
            </div>
            <div class="performers-list">
                <div class="performer-row">
                    <div class="performer-info">
                        <span class="rank">1</span>
                        <span class="performer-name">Ana Cruz</span>
                    </div>
                    <span class="performer-score">98%</span>
                </div>
                <div class="performer-row">
                    <div class="performer-info">
                        <span class="rank">2</span>
                        <span class="performer-name">Ben Santos</span>
                    </div>
                    <span class="performer-score">95%</span>
                </div>
                <div class="performer-row">
                    <div class="performer-info">
                        <span class="rank">3</span>
                        <span class="performer-name">Clara Reyes</span>
                    </div>
                    <span class="performer-score">92%</span>
                </div>
            </div>
        </div>

    </div>

</div>


