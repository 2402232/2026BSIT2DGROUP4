<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/emergency-tracking.css'; ?>">

<div class="tracking-wrapper">

    <a class="back-link" href="index.php?action=dashboard">← Back to Home</a>

    <div class="tracking-outer">

        <!-- ── Header ── -->
        <div class="tracking-card">
            <div class="tracking-header">
                <i class="ri-radar-line tracking-icon pulse-dot"></i>
                <div>
                    <h1>Real-Time Tracking</h1>
                    <span class="live-badge"><span class="live-dot"></span> LIVE</span>
                </div>
                <div class="header-right">
                    <span class="last-update" id="lastUpdate">Updating…</span>
                    <button class="refresh-btn" onclick="loadReports()" title="Refresh">
                        <i class="ri-refresh-line" id="refreshIcon"></i>
                    </button>
                </div>
            </div>

            <!-- ── Stats Bar ── -->
            <div class="stats-bar" id="statsBar">
                <div class="stat-item">
                    <span class="stat-num" id="statTotal">0</span>
                    <span class="stat-lbl">Total</span>
                </div>
                <div class="stat-item stat-critical">
                    <span class="stat-num" id="statCritical">0</span>
                    <span class="stat-lbl">Critical</span>
                </div>
                <div class="stat-item stat-dispatched">
                    <span class="stat-num" id="statDispatched">0</span>
                    <span class="stat-lbl">Dispatched</span>
                </div>
                <div class="stat-item stat-resolved">
                    <span class="stat-num" id="statResolved">0</span>
                    <span class="stat-lbl">Resolved</span>
                </div>
            </div>

            <!-- ── Filter Tabs ── -->
            <div class="filter-tabs" id="filterTabs">
                <button class="ftab active" data-filter="all">All</button>
                <button class="ftab" data-filter="dispatched">Dispatched</button>
                <button class="ftab" data-filter="en route">En Route</button>
                <button class="ftab" data-filter="on scene">On Scene</button>
                <button class="ftab" data-filter="resolved">Resolved</button>
            </div>
        </div>

        <!-- ── Report List ── -->
        <div id="reportList"></div>

        <!-- ── Empty State ── -->
        <div class="empty-state" id="emptyState" style="display:none;">
            <div class="tracking-card" style="padding:60px 28px; text-align:center;">
                <i class="ri-map-pin-line empty-icon"></i>
                <p class="empty-text">No active emergency reports</p>
                <p style="font-size:0.8rem; color:#B0B8C8; margin:4px 0 18px;">Reports submitted will appear here in real-time</p>
                <a href="index.php?action=report-system" class="report-link-btn">
                    <i class="ri-alarm-warning-line"></i> Report an Emergency
                </a>
            </div>
        </div>

    </div>
</div>

<!-- ── Detail Modal ── -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeModal()"><i class="ri-close-line"></i></button>
        <div id="modalContent"></div>
    </div>
</div>

<?php include 'views/includes/footer.php'; ?>
<script src="<?php echo ASSETS_PATH; ?>js/emergency-tracking.js"></script>