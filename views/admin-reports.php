<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/admin-reports.css'; ?>">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<div class="admin-wrapper">

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="banner">
            <i class="ri-file-chart-2-line"></i>
            <div class="banner-text">
                <h2>Reports & Analytics</h2>
                <p>Emergency Response System</p>
            </div>
        </div>
        <div class="top-bar-actions">
            <button class="action-btn" onclick="window.print()"><i class="ri-printer-line"></i> Print</button>
            <button class="action-btn secondary"><i class="ri-download-2-line"></i> Export CSV</button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header"><span>Total Emergencies</span><span class="stat-icon-wrap red"><i class="ri-alarm-warning-line"></i></span></div>
            <div class="stat-number">247</div>
            <div class="stat-change positive"><i class="ri-arrow-up-line"></i> +12% this year</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span>Resolved Cases</span><span class="stat-icon-wrap green"><i class="ri-checkbox-circle-line"></i></span></div>
            <div class="stat-number">185</div>
            <div class="stat-change positive">74.9% resolution rate</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span>Avg. Response Time</span><span class="stat-icon-wrap orange"><i class="ri-timer-line"></i></span></div>
            <div class="stat-number">3.4 <span style="font-size:14px">min</span></div>
            <div class="stat-change positive">Best month on record</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span>Total Responders</span><span class="stat-icon-wrap blue"><i class="ri-team-line"></i></span></div>
            <div class="stat-number">23</div>
            <div class="stat-change">3 added this month</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <span class="filter-label"><i class="ri-filter-line"></i> Filter:</span>
        <button class="filter-pill active">All Time</button>
        <button class="filter-pill">This Month</button>
        <button class="filter-pill">This Week</button>
        <button class="filter-pill">Today</button>
        <select class="filter-select">
            <option>All Types</option>
            <option>Medical</option>
            <option>Fire</option>
            <option>Accident</option>
            <option>Animal</option>
            <option>Disaster</option>
        </select>
    </div>

    <!-- Charts Row 1 -->
    <div class="charts-row">
        <div class="chart-card wide">
            <div class="chart-card-header">
                <div>
                    <h3><i class="ri-bar-chart-grouped-line"></i> Monthly Emergency Volume</h3>
                    <p>Jan – Dec breakdown by emergency type</p>
                </div>
                <div class="chart-legend">
                    <span class="legend-dot medical"></span>Medical
                    <span class="legend-dot fire"></span>Fire
                    <span class="legend-dot accident"></span>Accident
                </div>
            </div>
            <canvas id="monthlyVolume" height="100"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <h3><i class="ri-pie-chart-2-line"></i> Type Distribution</h3>
                    <p>All-time breakdown</p>
                </div>
            </div>
            <canvas id="typeDistribution" height="160"></canvas>
            <div class="donut-legend">
                <div class="donut-item"><span class="donut-dot" style="background:#E74C3C"></span>Medical <strong>42%</strong></div>
                <div class="donut-item"><span class="donut-dot" style="background:#F39C12"></span>Fire <strong>28%</strong></div>
                <div class="donut-item"><span class="donut-dot" style="background:#3498DB"></span>Accident <strong>18%</strong></div>
                <div class="donut-item"><span class="donut-dot" style="background:#27AE60"></span>Other <strong>12%</strong></div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="charts-row three-col">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <h3><i class="ri-line-chart-line"></i> Response Time Trend</h3>
                    <p>Monthly average (minutes)</p>
                </div>
            </div>
            <canvas id="responseTimeTrend" height="140"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <h3><i class="ri-bar-chart-horizontal-line"></i> Status Breakdown</h3>
                    <p>All cases by current status</p>
                </div>
            </div>
            <canvas id="statusBreakdown" height="140"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <h3><i class="ri-time-line"></i> Peak Hours</h3>
                    <p>Emergencies by hour of day</p>
                </div>
            </div>
            <canvas id="peakHours" height="140"></canvas>
        </div>
    </div>

    <!-- Responder Performance Table -->
    <div class="report-table-section">
        <div class="section-header">
            <span class="section-icon"><i class="ri-user-star-line"></i></span>
            <h2>Responder Performance Report</h2>
        </div>
        <div class="table-wrapper">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Responder</th>
                        <th>Total Responses</th>
                        <th>Avg Response Time</th>
                        <th>Resolution Rate</th>
                        <th>Rating</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="rank gold">1</span></td>
                        <td><div class="resp-cell"><div class="avatar-sm">AC</div><span>Ana Cruz</span></div></td>
                        <td>58</td>
                        <td>2.1 min</td>
                        <td><div class="rate-bar"><div class="rate-fill" style="width:98%"></div></div> 98%</td>
                        <td><span class="star-rating">★★★★★</span> 4.9</td>
                        <td><span class="badge-status active">Active</span></td>
                    </tr>
                    <tr>
                        <td><span class="rank silver">2</span></td>
                        <td><div class="resp-cell"><div class="avatar-sm">BS</div><span>Ben Santos</span></div></td>
                        <td>51</td>
                        <td>2.6 min</td>
                        <td><div class="rate-bar"><div class="rate-fill" style="width:95%"></div></div> 95%</td>
                        <td><span class="star-rating">★★★★★</span> 4.7</td>
                        <td><span class="badge-status active">Active</span></td>
                    </tr>
                    <tr>
                        <td><span class="rank bronze">3</span></td>
                        <td><div class="resp-cell"><div class="avatar-sm">CR</div><span>Clara Reyes</span></div></td>
                        <td>47</td>
                        <td>3.0 min</td>
                        <td><div class="rate-bar"><div class="rate-fill" style="width:92%"></div></div> 92%</td>
                        <td><span class="star-rating">★★★★☆</span> 4.5</td>
                        <td><span class="badge-status responding">Responding</span></td>
                    </tr>
                    <tr>
                        <td><span class="rank">4</span></td>
                        <td><div class="resp-cell"><div class="avatar-sm">MJ</div><span>Mike Johnson</span></div></td>
                        <td>42</td>
                        <td>2.8 min</td>
                        <td><div class="rate-bar"><div class="rate-fill" style="width:88%"></div></div> 88%</td>
                        <td><span class="star-rating">★★★★☆</span> 4.3</td>
                        <td><span class="badge-status active">Active</span></td>
                    </tr>
                    <tr>
                        <td><span class="rank">5</span></td>
                        <td><div class="resp-cell"><div class="avatar-sm">JS</div><span>Jane Smith</span></div></td>
                        <td>38</td>
                        <td>4.1 min</td>
                        <td><div class="rate-bar"><div class="rate-fill" style="width:84%"></div></div> 84%</td>
                        <td><span class="star-rating">★★★★☆</span> 4.1</td>
                        <td><span class="badge-status offline">Offline</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Emergency Log Table -->
    <div class="report-table-section" style="margin-top:20px;">
        <div class="section-header">
            <span class="section-icon"><i class="ri-list-check-3"></i></span>
            <h2>Recent Emergency Log</h2>
        </div>
        <div class="table-wrapper">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Reporter</th>
                        <th>Type</th>
                        <th>Priority</th>
                        <th>Location</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Responder</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $log = [
                        ['ER-A373K','Carlos Mendoza','Medical','CRITICAL','Makati City','2024-02-08 13:45','Resolved','Ana Cruz'],
                        ['ER-B291M','Maria Santos','Fire','MODERATE','Quezon City','2024-02-08 13:30','Dispatched','Ben Santos'],
                        ['ER-C184X','Juan Dela Cruz','Accident','HIGH','Manila','2024-02-08 13:20','En Route','Mike Johnson'],
                        ['ER-D067P','Liza Reyes','Medical','LOW','Pasig','2024-02-08 13:10','Resolved','Clara Reyes'],
                        ['ER-E552R','Roberto Tan','Animal','MODERATE','Mandaluyong','2024-02-08 12:55','Pending','—'],
                    ];
                    foreach($log as $l) {
                        $pClass = strtolower($l[3]);
                        $sClass = strtolower(str_replace(' ','-',$l[6]));
                    ?>
                    <tr>
                        <td class="em-id-cell"><?php echo $l[0]; ?></td>
                        <td><?php echo $l[1]; ?></td>
                        <td><?php echo $l[2]; ?></td>
                        <td><span class="priority-badge <?php echo $pClass; ?>"><?php echo $l[3]; ?></span></td>
                        <td><?php echo $l[4]; ?></td>
                        <td><?php echo $l[5]; ?></td>
                        <td><span class="status-badge <?php echo $sClass; ?>"><?php echo $l[6]; ?></span></td>
                        <td><?php echo $l[7]; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
new Chart(document.getElementById('monthlyVolume'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [
            { label: 'Medical', data: [18,22,19,25,30,28,35,32,28,24,20,18], backgroundColor: 'rgba(231,76,60,0.82)', borderRadius: 4 },
            { label: 'Fire', data: [8,10,12,9,14,18,20,15,11,9,7,6], backgroundColor: 'rgba(243,156,18,0.82)', borderRadius: 4 },
            { label: 'Accident', data: [5,7,6,8,10,9,12,11,8,7,6,5], backgroundColor: 'rgba(52,152,219,0.82)', borderRadius: 4 }
        ]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, grid: { color: '#f0f0f0' } } } }
});

new Chart(document.getElementById('typeDistribution'), {
    type: 'doughnut',
    data: {
        labels: ['Medical','Fire','Accident','Other'],
        datasets: [{ data: [42,28,18,12], backgroundColor: ['#E74C3C','#F39C12','#3498DB','#27AE60'], borderWidth: 2, borderColor: '#fff' }]
    },
    options: { cutout: '65%', plugins: { legend: { display: false } } }
});

new Chart(document.getElementById('responseTimeTrend'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{ label: 'Avg Time (min)', data: [5.2,4.8,4.5,4.1,3.9,3.7,3.5,3.4,3.4,3.3,3.4,3.4], borderColor: '#E74C3C', backgroundColor: 'rgba(231,76,60,0.08)', tension: 0.4, fill: true, pointRadius: 4, pointBackgroundColor: '#E74C3C' }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: false, grid: { color: '#f0f0f0' } } } }
});

new Chart(document.getElementById('statusBreakdown'), {
    type: 'bar',
    data: {
        labels: ['Resolved','Dispatched','En Route','On Scene','Pending','Cancelled'],
        datasets: [{ data: [185,22,12,8,14,6], backgroundColor: ['#27AE60','#3498DB','#9B59B6','#F39C12','#E74C3C','#95A5A6'], borderRadius: 5 }]
    },
    options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, grid: { color: '#f0f0f0' } }, y: { grid: { display: false } } } }
});

new Chart(document.getElementById('peakHours'), {
    type: 'bar',
    data: {
        labels: ['6am','8am','10am','12pm','2pm','4pm','6pm','8pm','10pm','12am'],
        datasets: [{ label: 'Emergencies', data: [3,7,10,14,18,22,28,19,12,5], backgroundColor: 'rgba(231,76,60,0.7)', borderRadius: 4 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, grid: { color: '#f0f0f0' } } } }
});
</script>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
