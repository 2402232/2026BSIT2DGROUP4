<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/emergency-dashboard.css">
<style>
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:.73rem;font-weight:700}
.b-critical{background:#fde8e8;color:#c0381c}
.b-moderate{background:#fff3cd;color:#856404}
.b-minor{background:#e2f0cb;color:#3d6b22}
.b-pending{background:#e2e8f0;color:#334155}
.b-responding{background:#dbeafe;color:#1e40af}
.b-resolved{background:#d4edda;color:#155724}
.em-thumb{width:52px;height:52px;border-radius:6px;object-fit:cover;border:1px solid #eee;float:right;margin-left:10px}
.track-btn{display:inline-block;background:#e44d26;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:.8rem;font-weight:700;margin-top:8px}
</style>

<div class="dashboard-content">
    <a class="back-link" href="<?php echo BASE_URL; ?>index.php?action=dashboard">← Back to Home</a>
    <h1>Emergency Dashboard</h1>

    <!-- Summary cards (from DB) -->
    <?php
    $countCritical   = 0; $countResponding = 0; $countModerate = 0; $countResolved = 0;
    foreach ($emergencies as $e) {
        if ($e['severity'] === 'critical')        $countCritical++;
        if ($e['status']   === 'responding')      $countResponding++;
        if ($e['severity'] === 'moderate')        $countModerate++;
        if ($e['status']   === 'resolved')        $countResolved++;
    }
    ?>
    <div class="status-cards">
        <div class="card critical">   <p>Critical</p>   <h2><?php echo $countCritical; ?></h2></div>
        <div class="card high-priority"><p>Responding</p><h2><?php echo $countResponding; ?></h2></div>
        <div class="card moderate">   <p>Moderate</p>   <h2><?php echo $countModerate; ?></h2></div>
        <div class="card low-priority"><p>Resolved</p>  <h2><?php echo $countResolved; ?></h2></div>
    </div>

    <!-- Filter buttons -->
    <div class="filters">
        <button class="filter-btn active" onclick="filterEm('all',this)">All</button>
        <button class="filter-btn" onclick="filterEm('critical',this)">CRITICAL</button>
        <button class="filter-btn" onclick="filterEm('moderate',this)">MODERATE</button>
        <button class="filter-btn" onclick="filterEm('resolved',this)">RESOLVED</button>
        <button class="filter-btn" onclick="filterEm('pending',this)">PENDING</button>
    </div>

    <!-- Emergency list from DB -->
    <div class="emergency-list">
        <?php if (empty($emergencies)): ?>
        <div style="text-align:center;padding:50px 20px;color:#aaa">
            <p style="font-size:1.1rem">No emergency reports yet.</p>
            <a href="<?php echo BASE_URL; ?>index.php?action=report-system"
               style="display:inline-block;margin-top:14px;background:#e44d26;color:#fff;padding:11px 28px;border-radius:8px;text-decoration:none;font-weight:700">
                Report an Emergency
            </a>
        </div>
        <?php else: ?>
            <?php foreach ($emergencies as $em):
                $colors = ['critical'=>'#E74C3C','moderate'=>'#F39C12','minor'=>'#27AE60'];
                $col    = $colors[$em['severity']] ?? '#999';
            ?>
            <div class="emergency-item"
                 data-severity="<?php echo $em['severity']; ?>"
                 data-status="<?php echo $em['status']; ?>">
                <div class="emergency-top">
                    <?php if (!empty($em['photo'])): ?>
                    <img class="em-thumb"
                         src="<?php echo UPLOADS_URL . 'reports/' . htmlspecialchars($em['photo']); ?>"
                         alt="Photo" onerror="this.style.display='none'">
                    <?php endif; ?>
                    <span class="em-id"><?php echo htmlspecialchars($em['report_code']); ?></span>
                    <span class="em-status" style="background:<?php echo $col; ?>;color:#fff;border-radius:20px;padding:3px 10px;font-size:.73rem;font-weight:700">
                        <?php echo strtoupper($em['severity']); ?>
                    </span>
                    <span class="em-type"><?php echo htmlspecialchars($em['emergency_type']); ?></span>
                </div>
                <div class="emergency-info">
                    <p class="em-desc"><?php echo htmlspecialchars($em['description']); ?></p>
                    <p class="em-loc">📍 <?php echo htmlspecialchars($em['location']); ?></p>
                    <p class="em-loc">🕐 <?php echo date('M d, Y H:i', strtotime($em['created_at'])); ?></p>
                    <p class="em-loc">Status: <strong><?php echo ucfirst($em['status']); ?></strong></p>
                </div>
                <a href="<?php echo BASE_URL; ?>index.php?action=emergency-tracking" class="track-btn">🔗 Track This</a>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function filterEm(type, btn) {
    document.querySelectorAll('.filter-btn').forEach(function(b){ b.classList.remove('active'); });
    btn.classList.add('active');
    document.querySelectorAll('.emergency-item').forEach(function(el){
        if (type === 'all') { el.style.display = ''; return; }
        var match = el.dataset.severity === type || el.dataset.status === type;
        el.style.display = match ? '' : 'none';
    });
}
</script>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
<script src="<?php echo ASSETS_PATH; ?>js/emergency-dashboard.js"></script>
