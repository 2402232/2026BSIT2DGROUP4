<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/emergency-tracking.css">
<style>
.tracking-reports{display:flex;flex-direction:column;gap:18px;margin-top:20px}
.tr-card{background:#fff;border-radius:12px;border:1px solid #eee;padding:22px 26px;box-shadow:0 2px 8px rgba(0,0,0,.06)}
.tr-head{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;margin-bottom:14px}
.tr-code{font-weight:700;font-size:1rem;color:#1a1a1a}
.tr-badge{padding:4px 14px;border-radius:20px;font-size:.77rem;font-weight:700}
.tr-badge.pending{background:#e2e8f0;color:#334155}
.tr-badge.responding{background:#dbeafe;color:#1e40af}
.tr-badge.resolved{background:#d4edda;color:#155724}
.tr-badge.cancelled{background:#f8d7da;color:#721c24}
.tr-meta{display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:.87rem;color:#444}
.tr-meta span{display:flex;align-items:center;gap:5px}
.tr-desc{margin-top:12px;background:#f9f9f9;border-radius:8px;padding:10px;font-size:.87rem;color:#555}
.tr-photo{margin-top:10px}
.tr-photo img{max-width:220px;border-radius:8px;border:1px solid #eee}
.timeline{margin-top:14px;border-left:3px solid #e44d26;padding-left:16px}
.tl-step{position:relative;margin-bottom:10px;font-size:.83rem;color:#888}
.tl-step::before{content:"●";position:absolute;left:-22px;color:#ccc;font-size:.7rem;top:2px}
.tl-step.done{color:#333;font-weight:600}
.tl-step.done::before{color:#e44d26}
.empty-wrap{text-align:center;padding:60px 20px}
.empty-wrap .empty-icon{font-size:54px;display:block;margin-bottom:16px}
.report-btn{display:inline-block;background:#e44d26;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:700;margin-top:16px}
</style>

<div class="tracking-wrapper">
    <a class="back-link" href="<?php echo BASE_URL; ?>index.php?action=dashboard">← Back to Home</a>

    <div class="tracking-card">
        <div class="tracking-header">
            <i class="ri-send-plane-line tracking-icon"></i>
            <h1>Real-Time Tracking</h1>
        </div>

        <div class="tracking-body" id="trackingBody">
            <?php if (empty($reports)): ?>
            <div class="empty-wrap">
                <span class="empty-icon">📡</span>
                <p style="color:#777">No active emergency requests</p>
                <a href="<?php echo BASE_URL; ?>index.php?action=report-system" class="report-btn">Report an Emergency</a>
            </div>
            <?php else: ?>
            <div class="tracking-reports">
                <?php foreach ($reports as $r): ?>
                <div class="tr-card">
                    <div class="tr-head">
                        <span class="tr-code">🆔 <?php echo htmlspecialchars($r['report_code']); ?></span>
                        <span class="tr-badge <?php echo $r['status']; ?>"><?php echo ucfirst($r['status']); ?></span>
                    </div>

                    <div class="tr-meta">
                        <span>🚨 <?php echo htmlspecialchars($r['emergency_type']); ?></span>
                        <span>⚡ <?php echo ucfirst($r['severity']); ?> Priority</span>
                        <span>📍 <?php echo htmlspecialchars($r['location']); ?></span>
                        <span>🕐 <?php echo date('M d, Y H:i', strtotime($r['created_at'])); ?></span>
                    </div>

                    <?php if (!empty($r['description'])): ?>
                    <div class="tr-desc">📝 <?php echo htmlspecialchars($r['description']); ?></div>
                    <?php endif; ?>

                    <?php if (!empty($r['photo'])): ?>
                    <div class="tr-photo">
                        <img src="<?php echo UPLOADS_URL . 'reports/' . htmlspecialchars($r['photo']); ?>"
                             alt="Report photo" onerror="this.parentElement.style.display='none'">
                    </div>
                    <?php endif; ?>

                    <!-- Status timeline -->
                    <div class="timeline">
                        <?php
                        $statuses = ['pending','responding','resolved'];
                        $cur      = array_search($r['status'], $statuses);
                        $cur      = $cur === false ? 0 : $cur;
                        ?>
                        <div class="tl-step <?php echo $cur >= 0 ? 'done' : ''; ?>">✅ Report Submitted</div>
                        <div class="tl-step <?php echo $cur >= 1 ? 'done' : ''; ?>">🚑 Responder Assigned</div>
                        <div class="tl-step <?php echo $cur >= 1 ? 'done' : ''; ?>">📡 Responder En Route</div>
                        <div class="tl-step <?php echo $cur >= 2 ? 'done' : ''; ?>">✔️ Resolved</div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
