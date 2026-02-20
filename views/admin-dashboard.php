<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/admin-dashboard.css">
<style>
/* ---- Chart ---- */
.chart-section{background:#fff;border-radius:12px;padding:24px;margin:20px;box-shadow:0 2px 8px rgba(0,0,0,.07)}
.chart-top{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:20px}
.chart-top h3{margin:0;font-size:1.05rem}
.period-btns{display:flex;gap:8px}
.period-btn{padding:6px 18px;border-radius:20px;border:1.5px solid #e44d26;background:#fff;color:#e44d26;cursor:pointer;font-size:.83rem;font-weight:700;text-decoration:none;transition:.2s}
.period-btn.active,.period-btn:hover{background:#e44d26;color:#fff}
.bar-chart{display:flex;align-items:flex-end;gap:10px;height:150px}
.bar-col{flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;height:100%;justify-content:flex-end}
.bar-fill{width:100%;background:#e44d26;border-radius:4px 4px 0 0;min-height:4px}
.bar-lbl{font-size:.7rem;color:#666;white-space:nowrap}
.bar-num{font-size:.73rem;font-weight:700;color:#e44d26}
/* ---- Table ---- */
.rpt-section{background:#fff;border-radius:12px;padding:24px;margin:20px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow-x:auto}
.rpt-section h3{margin:0 0 16px;font-size:1.05rem}
.rpt-table{width:100%;border-collapse:collapse;font-size:.86rem;min-width:700px}
.rpt-table th{background:#fde8e8;color:#c0381c;text-align:left;padding:10px 12px;white-space:nowrap}
.rpt-table td{padding:10px 12px;border-bottom:1px solid #f2f2f2;vertical-align:middle}
.rpt-table tr:hover td{background:#fef9f9}
.rpt-thumb{width:44px;height:44px;border-radius:6px;object-fit:cover;border:1px solid #eee}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:.73rem;font-weight:700;white-space:nowrap}
.b-critical{background:#fde8e8;color:#c0381c}
.b-moderate{background:#fff3cd;color:#856404}
.b-minor{background:#e2f0cb;color:#3d6b22}
.b-pending{background:#e2e8f0;color:#334155}
.b-responding{background:#dbeafe;color:#1e40af}
.b-resolved{background:#d4edda;color:#155724}
.b-cancelled{background:#f8d7da;color:#721c24}
.track-link{color:#e44d26;font-weight:700;font-size:.8rem;text-decoration:none}
.track-link:hover{text-decoration:underline}
/* ---- Emergency list badges (reuse) ---- */
.em-status.critical{background:#e74c3c;color:#fff}
.em-status.moderate{background:#f39c12;color:#fff}
.em-status.minor{background:#27ae60;color:#fff}
</style>

<div class="admin-wrapper">

    <!-- Top bar -->
    <div class="top-bar">
        <div class="banner">
            <i class="ri-shield-line"></i>
            <div class="banner-text">
                <h2>Admin Dashboard</h2>
                <p>Emergency Response System</p>
            </div>
        </div>
    </div>

    <!-- Stats grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header"><span>Total Reports</span><span class="stat-icon">📋</span></div>
            <div class="stat-number"><?php echo $totalReports; ?></div>
            <div class="stat-change positive">All time reports</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span>Active Responders</span><span class="stat-icon green">✓</span></div>
            <div class="stat-number"><?php echo $activeResponders; ?></div>
            <div class="stat-change">Available for dispatch</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span>On Duty</span><span class="stat-icon red">🚨</span></div>
            <div class="stat-number"><?php echo $onDuty; ?></div>
            <div class="stat-change">Currently responding</div>
        </div>
        <div class="stat-card">
            <div class="stat-header"><span>Resolved</span><span class="stat-icon orange">✅</span></div>
            <div class="stat-number"><?php echo $resolved; ?></div>
            <div class="stat-change">Successfully closed</div>
        </div>
    </div>

    <!-- Weekly / Monthly chart -->
    <div class="chart-section">
        <div class="chart-top">
            <h3>📊 Reports — <?php echo $period === 'monthly' ? 'Monthly' : 'Weekly'; ?> Overview</h3>
            <div class="period-btns">
                <a href="<?php echo BASE_URL; ?>index.php?action=admin-dashboard&period=weekly"
                   class="period-btn <?php echo $period !== 'monthly' ? 'active' : ''; ?>">Weekly</a>
                <a href="<?php echo BASE_URL; ?>index.php?action=admin-dashboard&period=monthly"
                   class="period-btn <?php echo $period === 'monthly' ? 'active' : ''; ?>">Monthly</a>
            </div>
        </div>
        <?php if (empty($chartData)): ?>
            <p style="text-align:center;color:#aaa;padding:30px 0">No data yet for this period.</p>
        <?php else:
            $maxVal = max(1, ...array_column($chartData, 'total')); ?>
            <div class="bar-chart">
                <?php foreach ($chartData as $row):
                    $h = (int) round(($row['total'] / $maxVal) * 130); ?>
                <div class="bar-col">
                    <div class="bar-num"><?php echo $row['total']; ?></div>
                    <div class="bar-fill" style="height:<?php echo $h; ?>px"></div>
                    <div class="bar-lbl"><?php echo htmlspecialchars($row['label']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Active emergencies list -->
    <div class="active-emergencies">
        <div class="section-header">
            <span class="section-icon"><i class="ri-error-warning-line"></i></span>
            <h2>Active Emergencies — Users Needing Help</h2>
        </div>
        <div class="emergency-list">
            <?php if (empty($emergencies)): ?>
                <p style="text-align:center;color:#aaa;padding:30px">No active emergencies.</p>
            <?php else: ?>
                <?php foreach ($emergencies as $em): ?>
                <div class="emergency-item">
                    <div class="em-header">
                        <div class="em-left">
                            <span class="em-id"><?php echo htmlspecialchars($em['report_code']); ?></span>
                            <span class="em-status <?php echo htmlspecialchars($em['severity']); ?>"><?php echo strtoupper($em['severity']); ?></span>
                            <span class="em-badge"><?php echo ucfirst($em['status']); ?></span>
                        </div>
                    </div>
                    <div class="em-body">
                        <p class="em-name">User: <?php echo htmlspecialchars($em['first_name'] . ' ' . $em['last_name']); ?></p>
                        <p class="em-detail">📋 <?php echo htmlspecialchars($em['emergency_type']); ?></p>
                        <p class="em-detail">⏰ <?php echo htmlspecialchars($em['created_at']); ?></p>
                        <p class="em-detail">📍 <?php echo htmlspecialchars($em['location']); ?></p>
                        <p class="em-detail">📞 <?php echo htmlspecialchars($em['phone']); ?></p>
                        <?php if (!empty($em['description'])): ?>
                        <p class="em-detail">📝 <?php echo htmlspecialchars($em['description']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="em-actions">
                        <a href="<?php echo BASE_URL; ?>index.php?action=users" class="assign-btn">View Full Report</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Full report table -->
    <div class="rpt-section">
        <h3>📋 All Emergency Reports</h3>
        <?php if (empty($emergencies)): ?>
            <p style="color:#aaa">No reports found.</p>
        <?php else: ?>
        <table class="rpt-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Photo</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Severity</th>
                    <th>Status</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Track</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($emergencies as $em): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($em['report_code']); ?></strong></td>
                    <td>
                        <?php if (!empty($em['photo'])): ?>
                            <img class="rpt-thumb"
                                 src="<?php echo UPLOADS_URL . 'reports/' . htmlspecialchars($em['photo']); ?>"
                                 alt="Photo"
                                 onerror="this.style.display='none'">
                        <?php else: ?>
                            <span style="color:#ccc">—</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($em['first_name'] . ' ' . $em['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($em['emergency_type']); ?></td>
                    <td><span class="badge b-<?php echo $em['severity']; ?>"><?php echo ucfirst($em['severity']); ?></span></td>
                    <td><span class="badge b-<?php echo $em['status']; ?>"><?php echo ucfirst($em['status']); ?></span></td>
                    <td><?php echo htmlspecialchars($em['location']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($em['created_at'])); ?></td>
                    <td><a class="track-link" href="<?php echo BASE_URL; ?>index.php?action=users">🔗 Track</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

</div>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
