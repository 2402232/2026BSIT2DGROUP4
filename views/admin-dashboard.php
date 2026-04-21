<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/admin-dashboard.css'; ?>">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<div class="admin-wrapper">
    
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="banner">
            <i class="ri-shield-line"></i>
            <div class="banner-text">
                <h2>Admin Dashboard</h2>
                <p>Emergency Response System</p>
            </div>
        </div>
        <div class="top-bar-actions">
            <span class="date-label"><i class="ri-calendar-line"></i> <?php echo date('F d, Y'); ?></span>
            <a href="<?php echo BASE_URL; ?>index.php?action=admin-reports" class="action-btn"><i class="ri-file-chart-line"></i> Reports</a>
            <a href="<?php echo BASE_URL; ?>index.php?action=admin-settings" class="action-btn secondary"><i class="ri-settings-3-line"></i> Settings</a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span>Total Responses</span>
                <span class="stat-icon-wrap red"><i class="ri-alarm-warning-line"></i></span>
            </div>
            <div class="stat-number">247</div>
            <div class="stat-change positive"><i class="ri-arrow-up-line"></i> +5% vs last month</div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <span>Active Responders</span>
                <span class="stat-icon-wrap green"><i class="ri-user-heart-line"></i></span>
            </div>
            <div class="stat-number">15</div>
            <div class="stat-change"><i class="ri-checkbox-circle-line"></i> Available for dispatch</div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <span>On Duty</span>
                <span class="stat-icon-wrap orange"><i class="ri-run-line"></i></span>
            </div>
            <div class="stat-number">2</div>
            <div class="stat-change">Currently responding</div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <span>Avg Response Time</span>
                <span class="stat-icon-wrap blue"><i class="ri-timer-line"></i></span>
            </div>
            <div class="stat-number">3.4 <span style="font-size:14px">min</span></div>
            <div class="stat-change positive"><i class="ri-arrow-down-line"></i> 2 min faster than target</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="charts-row">
        <div class="chart-card wide">
            <div class="chart-card-header">
                <div>
                    <h3><i class="ri-bar-chart-grouped-line"></i> Emergency Trends</h3>
                    <p>Monthly emergency reports by type</p>
                </div>
                <div class="chart-legend">
                    <span class="legend-dot medical"></span>Medical
                    <span class="legend-dot fire"></span>Fire
                    <span class="legend-dot accident"></span>Accident
                </div>
            </div>
            <canvas id="emergencyTrendsChart" height="110"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <h3><i class="ri-pie-chart-2-line"></i> By Type</h3>
                    <p>Current month breakdown</p>
                </div>
            </div>
            <canvas id="emergencyTypeChart" height="160"></canvas>
            <div class="donut-legend">
                <div class="donut-item"><span class="donut-dot" style="background:#E74C3C"></span>Medical <strong>42%</strong></div>
                <div class="donut-item"><span class="donut-dot" style="background:#F39C12"></span>Fire <strong>28%</strong></div>
                <div class="donut-item"><span class="donut-dot" style="background:#3498DB"></span>Accident <strong>18%</strong></div>
                <div class="donut-item"><span class="donut-dot" style="background:#27AE60"></span>Other <strong>12%</strong></div>
            </div>
        </div>
    </div>

    <!-- Second Charts Row -->
    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <h3><i class="ri-donut-chart-line"></i> Response Status</h3>
                    <p>All-time resolution rates</p>
                </div>
            </div>
            <canvas id="statusChart" height="170"></canvas>
        </div>
        <div class="chart-card wide">
            <div class="chart-card-header">
                <div>
                    <h3><i class="ri-line-chart-line"></i> Weekly Activity</h3>
                    <p>Emergencies reported this week</p>
                </div>
            </div>
            <canvas id="weeklyChart" height="110"></canvas>
        </div>
    </div>

    <!-- Active Emergencies Section -->
    <div class="active-emergencies">
        <div class="section-header">
            <span class="section-icon"><i class="ri-error-warning-line"></i></span>
            <h2>Active Emergencies – Users Needing Help</h2>
            <a href="<?php echo BASE_URL; ?>index.php?action=users" class="view-all-btn">View All <i class="ri-arrow-right-line"></i></a>
        </div>
        <div class="emergency-list">
            <?php foreach ($emergencies as $em) {
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
                    <p class="em-detail"><i class="ri-flashlight-line"></i> <?php echo $em['type']; ?></p>
                    <p class="em-detail"><i class="ri-time-line"></i> <?php echo $em['time']; ?></p>
                    <p class="em-detail"><i class="ri-map-pin-line"></i> <?php echo $em['location']; ?></p>
                    <ul class="em-details-list">
                        <?php foreach ($em['details'] as $detail) { ?>
                            <li><?php echo $detail; ?></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="em-actions">
                    <button class="assign-btn <?php echo $assignedClass; ?>"><?php echo $em['assign'] . $em['responder']; ?></button>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- Bottom Stats -->
    <div class="bottom-stats">
        <div class="stats-box">
            <div class="stats-box-header">
                <h3>Response Time by Responders</h3>
                <a href="<?php echo BASE_URL; ?>index.php?action=responders" class="stats-link">View all</a>
            </div>
            <div class="responders-list">
                <div class="responder-row">
                    <span class="responder-name">Carlos Mendoza</span>
                    <div class="progress-bar"><div class="progress-fill" style="width: 85%"></div></div>
                    <span class="time-text">3.2 min</span>
                </div>
                <div class="responder-row">
                    <span class="responder-name">Jane Smith</span>
                    <div class="progress-bar"><div class="progress-fill" style="width: 75%"></div></div>
                    <span class="time-text">4.1 min</span>
                </div>
                <div class="responder-row">
                    <span class="responder-name">Mike Johnson</span>
                    <div class="progress-bar"><div class="progress-fill" style="width: 90%"></div></div>
                    <span class="time-text">2.8 min</span>
                </div>
                <div class="responder-row">
                    <span class="responder-name">Ana Cruz</span>
                    <div class="progress-bar"><div class="progress-fill green" style="width: 95%"></div></div>
                    <span class="time-text">2.1 min</span>
                </div>
            </div>
        </div>
        <div class="stats-box">
            <div class="stats-box-header">
                <h3>Top Performers</h3>
                <a href="<?php echo BASE_URL; ?>index.php?action=admin-reports" class="stats-link">Full report</a>
            </div>
            <div class="performers-list">
                <div class="performer-row">
                    <div class="performer-info">
                        <span class="rank gold">1</span>
                        <span class="performer-name">Ana Cruz</span>
                    </div>
                    <span class="performer-score">98%</span>
                </div>
                <div class="performer-row">
                    <div class="performer-info">
                        <span class="rank silver">2</span>
                        <span class="performer-name">Ben Santos</span>
                    </div>
                    <span class="performer-score">95%</span>
                </div>
                <div class="performer-row">
                    <div class="performer-info">
                        <span class="rank bronze">3</span>
                        <span class="performer-name">Clara Reyes</span>
                    </div>
                    <span class="performer-score">92%</span>
                </div>
                <div class="performer-row">
                    <div class="performer-info">
                        <span class="rank">4</span>
                        <span class="performer-name">Mike Johnson</span>
                    </div>
                    <span class="performer-score">88%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links Admin Tools -->
    <div class="quick-links-section">
        <h3 class="section-title"><i class="ri-apps-line"></i> Admin Tools</h3>
        <div class="quick-links-grid">
            <a href="<?php echo BASE_URL; ?>index.php?action=admin-reports" class="quick-link-card">
                <div class="ql-icon red"><i class="ri-file-chart-2-line"></i></div>
                <div class="ql-text">
                    <span class="ql-title">Reports & Analytics</span>
                    <span class="ql-desc">View detailed statistics</span>
                </div>
                <i class="ri-arrow-right-s-line ql-arrow"></i>
            </a>
            <a href="<?php echo BASE_URL; ?>index.php?action=admin-settings" class="quick-link-card">
                <div class="ql-icon blue"><i class="ri-settings-3-line"></i></div>
                <div class="ql-text">
                    <span class="ql-title">System Settings</span>
                    <span class="ql-desc">Configure the platform</span>
                </div>
                <i class="ri-arrow-right-s-line ql-arrow"></i>
            </a>
            <a href="<?php echo BASE_URL; ?>index.php?action=users" class="quick-link-card">
                <div class="ql-icon green"><i class="ri-wheelchair-line"></i></div>
                <div class="ql-text">
                    <span class="ql-title">Users Needing Help</span>
                    <span class="ql-desc">Manage users</span>
                </div>
                <i class="ri-arrow-right-s-line ql-arrow"></i>
            </a>
            <a href="<?php echo BASE_URL; ?>index.php?action=responders" class="quick-link-card">
                <div class="ql-icon orange"><i class="ri-team-line"></i></div>
                <div class="ql-text">
                    <span class="ql-title">Responders</span>
                    <span class="ql-desc">Manage field responders</span>
                </div>
                <i class="ri-arrow-right-s-line ql-arrow"></i>
            </a>
        </div>
    </div>

</div>

<script>
const trendsCtx = document.getElementById('emergencyTrendsChart').getContext('2d');
new Chart(trendsCtx, {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [
            { label: 'Medical', data: [18,22,19,25,30,28,35,32,28,24,20,18], backgroundColor: 'rgba(231,76,60,0.82)', borderRadius: 5 },
            { label: 'Fire', data: [8,10,12,9,14,18,20,15,11,9,7,6], backgroundColor: 'rgba(243,156,18,0.82)', borderRadius: 5 },
            { label: 'Accident', data: [5,7,6,8,10,9,12,11,8,7,6,5], backgroundColor: 'rgba(52,152,219,0.82)', borderRadius: 5 }
        ]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, grid: { color: '#f0f0f0' } } } }
});

const typeCtx = document.getElementById('emergencyTypeChart').getContext('2d');
new Chart(typeCtx, {
    type: 'doughnut',
    data: {
        labels: ['Medical','Fire','Accident','Other'],
        datasets: [{ data: [42,28,18,12], backgroundColor: ['#E74C3C','#F39C12','#3498DB','#27AE60'], borderWidth: 2, borderColor: '#fff' }]
    },
    options: { cutout: '65%', plugins: { legend: { display: false } }, responsive: true }
});

const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'bar',
    data: {
        labels: ['Resolved','Dispatched','En Route','On Scene','Pending','Cancelled'],
        datasets: [{ label: 'Cases', data: [185,22,12,8,14,6], backgroundColor: ['#27AE60','#3498DB','#9B59B6','#F39C12','#E74C3C','#95A5A6'], borderRadius: 6 }]
    },
    options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, grid: { color: '#f0f0f0' } }, y: { grid: { display: false } } } }
});

const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
new Chart(weeklyCtx, {
    type: 'line',
    data: {
        labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        datasets: [{ label: 'Emergencies', data: [5,9,7,12,8,15,11], borderColor: '#E74C3C', backgroundColor: 'rgba(231,76,60,0.10)', tension: 0.4, fill: true, pointBackgroundColor: '#E74C3C', pointRadius: 5 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, grid: { color: '#f0f0f0' } } } }
});
</script>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
