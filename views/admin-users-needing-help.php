<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/admin-users-needing-help.css">
<style>
.rpt-photo{width:56px;height:56px;border-radius:8px;object-fit:cover;border:1px solid #eee}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:.73rem;font-weight:700}
.b-critical{background:#fde8e8;color:#c0381c}
.b-moderate{background:#fff3cd;color:#856404}
.b-minor{background:#e2f0cb;color:#3d6b22}
.b-pending{background:#e2e8f0;color:#334155}
.b-responding{background:#dbeafe;color:#1e40af}
.b-resolved{background:#d4edda;color:#155724}
.b-cancelled{background:#f8d7da;color:#721c24}
</style>

<div class="admin-wrapper">
    <div class="top-bar">
        <div class="banner">
            <i class="ri-shield-line"></i>
            <div class="banner-text">
                <h2>Users Needing Help</h2>
                <p>Emergency Response System</p>
            </div>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="filter-buttons">
            <button class="filter-btn active" onclick="filterCards('all',this)">All Emergencies</button>
            <button class="filter-btn" onclick="filterCards('critical',this)">Critical</button>
            <button class="filter-btn" onclick="filterCards('pending',this)">Pending</button>
            <button class="filter-btn" onclick="filterCards('responding',this)">Responding</button>
        </div>

        <div class="emergency-list">
            <?php if (empty($reports)): ?>
                <p style="text-align:center;color:#aaa;padding:40px">No emergency reports yet.</p>
            <?php else: ?>
                <?php foreach ($reports as $r): ?>
                <div class="emergency-card" data-severity="<?php echo $r['severity']; ?>" data-status="<?php echo $r['status']; ?>">
                    <div class="card-header">
                        <div class="emergency-id">
                            <span class="badge b-<?php echo $r['severity']; ?>"><?php echo strtoupper($r['severity']); ?></span>
                            <span class="badge b-<?php echo $r['status']; ?>"><?php echo strtoupper($r['status']); ?></span>
                            <h3><?php echo htmlspecialchars($r['report_code']); ?></h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-user-line"></i>
                                <div>
                                    <span class="label">User</span>
                                    <span class="value"><?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-time-line"></i>
                                <div>
                                    <span class="label">Reported</span>
                                    <span class="value"><?php echo date('M d, Y H:i', strtotime($r['created_at'])); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-phone-line"></i>
                                <div>
                                    <span class="label">Contact</span>
                                    <span class="value"><?php echo htmlspecialchars($r['phone']); ?></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-calendar-line"></i>
                                <div>
                                    <span class="label">Updated</span>
                                    <span class="value"><?php echo date('M d, Y H:i', strtotime($r['updated_at'])); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-map-pin-line"></i>
                                <div>
                                    <span class="label">Location</span>
                                    <span class="value"><?php echo htmlspecialchars($r['location']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-alert-line"></i>
                                <div>
                                    <span class="label">Emergency Type</span>
                                    <span class="value"><?php echo htmlspecialchars($r['emergency_type']); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($r['description'])): ?>
                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-file-text-line"></i>
                                <div>
                                    <span class="label">Description</span>
                                    <span class="value"><?php echo htmlspecialchars($r['description']); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($r['photo'])): ?>
                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-image-line"></i>
                                <div>
                                    <span class="label">Uploaded Photo</span><br>
                                    <img class="rpt-photo"
                                         src="<?php echo UPLOADS_URL . 'reports/' . htmlspecialchars($r['photo']); ?>"
                                         alt="Report photo"
                                         onerror="this.style.display='none'">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-actions">
                        <button class="btn btn-assign">Assign Responders</button>
                        <button class="btn btn-view">View User Details</button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function filterCards(type, btn) {
    document.querySelectorAll('.filter-btn').forEach(function(b){ b.classList.remove('active'); });
    btn.classList.add('active');
    document.querySelectorAll('.emergency-card').forEach(function(card){
        if (type === 'all') { card.style.display = ''; return; }
        var match = card.dataset.severity === type || card.dataset.status === type;
        card.style.display = match ? '' : 'none';
    });
}
</script>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
<script src="<?php echo ASSETS_PATH; ?>js/admin-users-needing-help.js"></script>
