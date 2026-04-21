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

<a class="action-btn secondary" style="display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:7px;text-decoration:none;font-size:12px;font-weight:700;background:#1A3A52;color:#fff;margin-bottom:18px;"
   href="<?php echo BASE_URL; ?>index.php?action=admin-chart-data">
    <i class="ri-database-2-line"></i> Edit Chart Data
</a>

<script>
// ── Fetch chart data from database and render ─────────────────
const BASE_URL = '<?php echo BASE_URL; ?>';

function fetchChart(parentKey) {
    return fetch(BASE_URL + 'index.php?action=chart-data-json&chart=' + parentKey)
        .then(r => r.json())
        .catch(() => null);
}

async function initCharts() {
    // Monthly Volume (grouped bar)
    const mv = await fetchChart('monthly_volume');
    if (mv && mv.datasets.length) {
        new Chart(document.getElementById('monthlyVolume'), {
            type: 'bar',
            data: { labels: mv.labels, datasets: mv.datasets.map(ds => ({ ...ds, borderRadius: 4 })) },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, grid: { color: '#f0f0f0' } } } }
        });
        // Update legend dots dynamically
        const dots = document.querySelectorAll('.chart-legend .legend-dot');
        mv.datasets.forEach((ds, i) => { if (dots[i]) dots[i].style.background = ds.backgroundColor; });
    }

    // Type Distribution (doughnut)
    const td = await fetchChart('type_distribution');
    if (td && td.datasets.length) {
        const pts = td.datasets[0];
        new Chart(document.getElementById('typeDistribution'), {
            type: 'doughnut',
            data: { labels: td.labels, datasets: [{ data: pts.data, backgroundColor: pts.backgroundColor, borderWidth: 2, borderColor: '#fff' }] },
            options: { cutout: '65%', plugins: { legend: { display: false } } }
        });
        // Update legend
        const items = document.querySelectorAll('.donut-item');
        td.labels.forEach((lbl, i) => {
            if (items[i]) {
                items[i].querySelector('.donut-dot').style.background = pts.backgroundColor[i];
                items[i].childNodes[items[i].childNodes.length - 1].textContent = lbl;
                const strong = items[i].querySelector('strong');
                if (strong) {
                    const total = pts.data.reduce((a, b) => a + b, 0);
                    strong.textContent = total ? Math.round(pts.data[i] / total * 100) + '%' : '0%';
                }
            }
        });
    }

    // Response Time Trend (line)
    const rt = await fetchChart('response_time_trend');
    if (rt && rt.datasets.length) {
        const ds = rt.datasets[0];
        new Chart(document.getElementById('responseTimeTrend'), {
            type: 'line',
            data: { labels: rt.labels, datasets: [{ label: ds.label, data: ds.data, borderColor: ds.borderColor, backgroundColor: ds.borderColor.replace(')', ',0.08)').replace('rgb','rgba'), tension: 0.4, fill: true, pointRadius: 4, pointBackgroundColor: ds.borderColor }] },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: false, grid: { color: '#f0f0f0' } } } }
        });
    }

    // Status Breakdown (horizontal bar)
    const sb = await fetchChart('status_breakdown');
    if (sb && sb.datasets.length) {
        const ds = sb.datasets[0];
        new Chart(document.getElementById('statusBreakdown'), {
            type: 'bar',
            data: { labels: sb.labels, datasets: [{ data: ds.data, backgroundColor: Array.isArray(ds.backgroundColor) ? ds.backgroundColor : sb.labels.map((_, i) => ds.backgroundColor), borderRadius: 5 }] },
            options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, grid: { color: '#f0f0f0' } }, y: { grid: { display: false } } } }
        });
    }

    // Peak Hours (bar)
    const ph = await fetchChart('peak_hours');
    if (ph && ph.datasets.length) {
        const ds = ph.datasets[0];
        new Chart(document.getElementById('peakHours'), {
            type: 'bar',
            data: { labels: ph.labels, datasets: [{ label: ds.label, data: ds.data, backgroundColor: ds.backgroundColor, borderRadius: 4 }] },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, grid: { color: '#f0f0f0' } } } }
        });
    }
}

initCharts();
</script>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
