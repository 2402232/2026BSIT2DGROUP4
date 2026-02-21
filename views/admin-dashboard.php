<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/admin-dashboard.css'; ?>">

<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<div class="admin-wrapper">
    
    <!-- Top Bar -->
    <div class="top-bar">
         <!-- Red Emergency Banner -->
        
             <div class="banner">
                <i class="ri-shield-line"></i>
                <div class="banner-text">
                    <h2>Admin Dashboard</h2>
                    <p>Emergency Response System</p>
                </div>
             </div>
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

    <div class="active-emergencies">
        <!-- Active Emergencies Section -->
        <div class="section-header">
            <span class="section-icon"><i class="ri-error-warning-line"></i></span>
            <h2>Active Emergencies - User Needing Help</h2>
        </div>
    
        <!-- Emergency List -->
        <div class="emergency-list">
            
            <?php
    
            foreach ($emergencies as $em) {
                $statusClass = strtolower(str_replace(' ', '-', $em['status']));
                $assignedClass = strtolower(str_replace(' ', '-', $em['assign']));
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
                    <div class="em-actions">
                        <!-- Assign Responders Button -->
                        <button class="assign-btn <?php echo $assignedClass; ?>"><?php echo $em['assign'] . $em['responder']; ?></button>
                    </div>
                </div>
                <?php
            }
            ?>
    
        </div>
    </div>

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

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>

