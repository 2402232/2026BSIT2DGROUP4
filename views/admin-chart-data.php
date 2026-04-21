<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/admin-reports.css'; ?>">
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/admin-chart-data.css'; ?>">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php require_once VIEW_PATH . 'includes/header.php'; ?>

<div class="admin-wrapper">

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="banner">
            <i class="ri-database-2-line"></i>
            <div class="banner-text">
                <h2>Chart Data Editor</h2>
                <p>Edit database values — charts update instantly</p>
            </div>
        </div>
        <a class="action-btn secondary" href="<?php echo BASE_URL; ?>index.php?action=admin-reports">
            <i class="ri-bar-chart-2-line"></i> View Live Reports
        </a>
    </div>

    <!-- How it works hint -->
    <div class="editor-hint">
        <i class="ri-information-line"></i>
        Select a chart series on the left, edit its rows in the table, then click
        <strong>Save to Database</strong>. The Reports page will immediately reflect your changes.
    </div>

    <div class="editor-layout">

        <!-- ── LEFT: dataset list ──────────────────────── -->
        <aside class="dataset-sidebar">
            <h3 class="sidebar-title"><i class="ri-layers-line"></i> Chart Series</h3>

            <?php
            $chartMeta = [
                'monthly_volume'      => ['icon' => 'ri-bar-chart-grouped-line', 'title' => 'Monthly Volume'],
                'type_distribution'   => ['icon' => 'ri-pie-chart-2-line',       'title' => 'Type Distribution'],
                'response_time_trend' => ['icon' => 'ri-line-chart-line',         'title' => 'Response Time Trend'],
                'status_breakdown'    => ['icon' => 'ri-bar-chart-horizontal-line','title' => 'Status Breakdown'],
                'peak_hours'          => ['icon' => 'ri-time-line',               'title' => 'Peak Hours'],
            ];
            $firstDs = null;
            foreach ($grouped as $parentKey => $datasets):
                $meta = $chartMeta[$parentKey] ?? ['icon' => 'ri-bar-chart-line', 'title' => ucwords(str_replace('_',' ',$parentKey))];
            ?>
                <div class="sidebar-group">
                    <div class="sidebar-group-title">
                        <i class="<?php echo $meta['icon']; ?>"></i>
                        <?php echo htmlspecialchars($meta['title']); ?>
                    </div>
                    <?php foreach ($datasets as $ds):
                        if (!$firstDs) $firstDs = $ds;
                    ?>
                        <button class="ds-btn" data-id="<?php echo $ds['id']; ?>"
                                data-label="<?php echo htmlspecialchars($ds['dataset_label']); ?>"
                                data-color="<?php echo htmlspecialchars($ds['color']); ?>"
                                data-type="<?php echo htmlspecialchars($ds['chart_type']); ?>"
                                data-parent="<?php echo htmlspecialchars($parentKey); ?>"
                                onclick="selectDataset(this)">
                            <span class="ds-dot" style="background:<?php echo htmlspecialchars($ds['color']); ?>"></span>
                            <?php echo htmlspecialchars($ds['dataset_label']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </aside>

        <!-- ── RIGHT: editor panel ────────────────────── -->
        <main class="editor-panel">

            <!-- Series meta editor -->
            <div class="meta-bar" id="metaBar">
                <div class="meta-fields">
                    <div class="meta-field">
                        <label>Series Label</label>
                        <input type="text" id="metaLabel" placeholder="e.g. Medical" maxlength="80">
                    </div>
                    <div class="meta-field">
                        <label>Color</label>
                        <div class="color-row">
                            <input type="color" id="metaColorPicker" value="#E74C3C" title="Pick color">
                            <input type="text" id="metaColor" placeholder="#E74C3C" maxlength="25">
                        </div>
                    </div>
                    <div class="meta-field">
                        <label>Chart Type</label>
                        <select id="metaType">
                            <option value="bar">Bar</option>
                            <option value="line">Line</option>
                            <option value="doughnut">Doughnut</option>
                            <option value="pie">Pie</option>
                        </select>
                    </div>
                </div>
                <button class="btn-meta-save" id="btnMetaSave" onclick="saveDatasetMeta()">
                    <i class="ri-save-line"></i> Save Series Info
                </button>
            </div>

            <!-- Data rows editor -->
            <div class="data-editor-card">

                <div class="de-header">
                    <div>
                        <h3 id="editorTitle"><i class="ri-table-line"></i> Select a series →</h3>
                        <p id="editorSubtitle" class="de-sub">Click a series on the left to start editing</p>
                    </div>
                    <div class="de-actions">
                        <button class="btn-add-row" onclick="addRow()">
                            <i class="ri-add-line"></i> Add Row
                        </button>
                        <button class="btn-save-db" id="btnSaveDb" onclick="saveToDatabase()">
                            <i class="ri-database-line"></i> Save to Database
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-scroll">
                    <table class="data-table" id="dataTable">
                        <thead>
                            <tr>
                                <th class="col-order">#</th>
                                <th class="col-label">Label</th>
                                <th class="col-value">Value</th>
                                <th class="col-color" id="colorColHead">Color</th>
                                <th class="col-del"></th>
                            </tr>
                        </thead>
                        <tbody id="dataTableBody">
                            <tr class="empty-row">
                                <td colspan="5">
                                    <i class="ri-arrow-left-line"></i>
                                    Select a chart series from the left panel to begin
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Save feedback -->
                <div class="save-feedback" id="saveFeedback"></div>
            </div>

            <!-- Live preview chart -->
            <div class="preview-card" id="previewCard">
                <div class="preview-header">
                    <h3><i class="ri-eye-line"></i> Live Preview</h3>
                    <span class="preview-note">Updates as you type</span>
                </div>
                <div class="preview-canvas-wrap">
                    <canvas id="previewChart" height="90"></canvas>
                </div>
            </div>

        </main>
    </div><!-- /.editor-layout -->

</div>

<?php
// Pass all dataset data to JS as a JSON blob
$jsData = [];
foreach ($grouped as $parentKey => $datasets) {
    foreach ($datasets as $ds) {
        $jsData[$ds['id']] = [
            'id'            => (int)$ds['id'],
            'parent_chart'  => $parentKey,
            'dataset_label' => $ds['dataset_label'],
            'chart_type'    => $ds['chart_type'],
            'color'         => $ds['color'],
            'points'        => array_map(fn($p) => [
                'label'       => $p['label'],
                'value'       => (float)$p['value'],
                'point_color' => $p['point_color'] ?? '',
            ], $ds['points']),
        ];
    }
}
?>

<script>
// ── Dataset store (PHP → JS) ──────────────────────────────────
const DB_DATA   = <?php echo json_encode($jsData, JSON_UNESCAPED_UNICODE); ?>;
const BASE_URL  = '<?php echo BASE_URL; ?>';

let currentDatasetId = null;
let previewChartObj  = null;

// ── Select a dataset ──────────────────────────────────────────
function selectDataset(btn) {
    document.querySelectorAll('.ds-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const id     = parseInt(btn.dataset.id);
    const ds     = DB_DATA[id];
    currentDatasetId = id;

    // Populate meta bar
    document.getElementById('metaLabel').value        = ds.dataset_label;
    document.getElementById('metaColor').value        = ds.color;
    document.getElementById('metaColorPicker').value  = toHex6(ds.color);
    document.getElementById('metaType').value         = ds.chart_type;

    // Show/hide per-point color column
    const showColorCol = ['doughnut','pie','status_breakdown'].includes(ds.chart_type)
                      || ds.parent_chart === 'status_breakdown';
    document.getElementById('colorColHead').style.display = showColorCol ? '' : 'none';

    // Editor heading
    document.getElementById('editorTitle').innerHTML =
        '<i class="ri-table-line"></i> ' + escHtml(ds.dataset_label);
    document.getElementById('editorSubtitle').textContent =
        ds.points.length + ' data points  ·  ' + ds.chart_type + ' chart  ·  parent: ' + ds.parent_chart;

    // Build table rows
    buildTable(ds.points, showColorCol);

    // Render preview
    renderPreview();
}

// ── Build table ───────────────────────────────────────────────
function buildTable(points, showColorCol) {
    const tbody = document.getElementById('dataTableBody');
    tbody.innerHTML = '';

    points.forEach((p, i) => {
        tbody.appendChild(makeRow(i + 1, p.label, p.value, p.point_color || '', showColorCol));
    });

    // Watch all inputs to auto-refresh preview
    attachInputListeners();
}

function makeRow(n, label, value, color, showColorCol) {
    const tr  = document.createElement('tr');
    tr.innerHTML = `
        <td class="col-order">${n}</td>
        <td class="col-label"><input type="text" class="cell-input label-input" value="${escHtml(label)}" placeholder="Label"></td>
        <td class="col-value"><input type="number" class="cell-input value-input" value="${value}" step="0.01" placeholder="0"></td>
        <td class="col-color" style="display:${showColorCol ? '' : 'none'}">
            <div class="color-row">
                <input type="color" class="cell-color-picker" value="${toHex6(color) || '#E74C3C'}">
                <input type="text"  class="cell-input color-text-input" value="${escHtml(color)}" placeholder="#RRGGBB" maxlength="25">
            </div>
        </td>
        <td class="col-del"><button class="btn-del-row" onclick="deleteRow(this)" title="Remove row"><i class="ri-delete-bin-line"></i></button></td>
    `;
    // Sync color picker ↔ text
    const picker = tr.querySelector('.cell-color-picker');
    const text   = tr.querySelector('.color-text-input');
    if (picker && text) {
        picker.addEventListener('input', () => { text.value = picker.value; renderPreview(); });
        text.addEventListener('input',  () => {
            if (/^#[0-9A-Fa-f]{6}$/.test(text.value)) picker.value = text.value;
            renderPreview();
        });
    }
    return tr;
}

function attachInputListeners() {
    document.querySelectorAll('#dataTableBody .cell-input').forEach(inp => {
        inp.addEventListener('input', renderPreview);
    });
    // Re-number rows on label change isn't needed; just update preview
}

// ── Add / delete rows ─────────────────────────────────────────
function addRow() {
    if (!currentDatasetId) return;
    const ds = DB_DATA[currentDatasetId];
    const showColorCol = ['doughnut','pie'].includes(ds.chart_type)
                      || ds.parent_chart === 'status_breakdown';
    const tbody = document.getElementById('dataTableBody');
    const n     = tbody.querySelectorAll('tr:not(.empty-row)').length + 1;
    const row   = makeRow(n, '', 0, '#E74C3C', showColorCol);
    tbody.appendChild(row);
    row.querySelector('.label-input').focus();
    attachInputListeners();
    renderPreview();
}

function deleteRow(btn) {
    btn.closest('tr').remove();
    renumber();
    renderPreview();
}

function renumber() {
    document.querySelectorAll('#dataTableBody tr:not(.empty-row)').forEach((tr, i) => {
        const first = tr.querySelector('.col-order');
        if (first) first.textContent = i + 1;
    });
}

// ── Live preview renderer ─────────────────────────────────────
function renderPreview() {
    if (!currentDatasetId) return;
    const ds     = DB_DATA[currentDatasetId];
    const rows   = collectRows();
    const labels = rows.map(r => r.label);
    const values = rows.map(r => r.value);
    const colors = rows.map(r => r.point_color || ds.color);

    const type   = document.getElementById('metaType').value || ds.chart_type;
    const color  = document.getElementById('metaColor').value || ds.color;
    const label  = document.getElementById('metaLabel').value || ds.dataset_label;

    const datasetCfg = {
        label:           label,
        data:            values,
        backgroundColor: (['doughnut','pie'].includes(type)) ? colors : color,
        borderColor:     (['line'].includes(type)) ? color : undefined,
        borderRadius:    4,
        tension:         0.4,
        fill:            false,
        borderWidth:     (['doughnut','pie'].includes(type)) ? 2 : undefined,
        borderColor:     (['doughnut','pie'].includes(type)) ? '#fff' : color,
        pointRadius:     (['line'].includes(type)) ? 4 : undefined,
        pointBackgroundColor: (['line'].includes(type)) ? color : undefined,
    };

    const cfg = {
        type: type,
        data: { labels, datasets: [datasetCfg] },
        options: {
            responsive: true,
            indexAxis: (ds.parent_chart === 'status_breakdown') ? 'y' : 'x',
            plugins: { legend: { display: false } },
            cutout: (['doughnut'].includes(type)) ? '60%' : undefined,
            scales: (['doughnut','pie'].includes(type)) ? undefined : {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } }
            }
        }
    };

    const canvas = document.getElementById('previewChart');
    if (previewChartObj) { previewChartObj.destroy(); }
    previewChartObj = new Chart(canvas, cfg);
}

// ── Collect rows from table ───────────────────────────────────
function collectRows() {
    const rows = [];
    document.querySelectorAll('#dataTableBody tr:not(.empty-row)').forEach(tr => {
        const label = tr.querySelector('.label-input')?.value?.trim() || '';
        const value = parseFloat(tr.querySelector('.value-input')?.value) || 0;
        const color = tr.querySelector('.color-text-input')?.value?.trim() || '';
        if (label) rows.push({ label, value, point_color: color });
    });
    return rows;
}

// ── Save dataset meta ─────────────────────────────────────────
function saveDatasetMeta() {
    if (!currentDatasetId) return;
    const btn = document.getElementById('btnMetaSave');
    btn.disabled = true;
    btn.textContent = 'Saving…';

    fetch(BASE_URL + 'index.php?action=save-dataset-meta', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            id:             currentDatasetId,
            dataset_label:  document.getElementById('metaLabel').value,
            color:          document.getElementById('metaColor').value,
            chart_type:     document.getElementById('metaType').value,
        })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            // Update local store
            DB_DATA[currentDatasetId].dataset_label = document.getElementById('metaLabel').value;
            DB_DATA[currentDatasetId].color         = document.getElementById('metaColor').value;
            DB_DATA[currentDatasetId].chart_type    = document.getElementById('metaType').value;
            // Update sidebar dot + label
            const sideBtn = document.querySelector(`.ds-btn[data-id="${currentDatasetId}"]`);
            if (sideBtn) {
                sideBtn.querySelector('.ds-dot').style.background = document.getElementById('metaColor').value;
                sideBtn.childNodes[sideBtn.childNodes.length - 1].textContent = ' ' + document.getElementById('metaLabel').value;
            }
            showFeedback('Series info saved!', 'success');
        } else {
            showFeedback('Failed to save series info.', 'error');
        }
    })
    .catch(() => showFeedback('Network error.', 'error'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ri-save-line"></i> Save Series Info';
    });
}

// ── Save data points to database ──────────────────────────────
function saveToDatabase() {
    if (!currentDatasetId) { alert('Select a series first.'); return; }

    const rows = collectRows();
    if (rows.length === 0) {
        if (!confirm('You are about to save 0 rows. This will clear all data for this series. Continue?')) return;
    }

    const btn = document.getElementById('btnSaveDb');
    btn.disabled = true;
    btn.innerHTML = '<i class="ri-loader-4-line"></i> Saving…';

    fetch(BASE_URL + 'index.php?action=save-chart-points', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            dataset_id: currentDatasetId,
            rows:       JSON.stringify(rows),
        })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            // Update local store
            DB_DATA[currentDatasetId].points = rows;
            showFeedback('✓ Saved to database! The Reports page now reflects these changes.', 'success');
        } else {
            showFeedback('Save failed: ' + (d.message || 'Unknown error'), 'error');
        }
    })
    .catch(() => showFeedback('Network error — changes not saved.', 'error'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ri-database-line"></i> Save to Database';
    });
}

// ── Feedback banner ───────────────────────────────────────────
function showFeedback(msg, type) {
    const el = document.getElementById('saveFeedback');
    el.textContent  = msg;
    el.className    = 'save-feedback ' + type;
    el.style.display = 'block';
    clearTimeout(el._timer);
    el._timer = setTimeout(() => { el.style.display = 'none'; }, 4000);
}

// ── Color picker ↔ hex text sync in meta bar ──────────────────
document.getElementById('metaColorPicker').addEventListener('input', function() {
    document.getElementById('metaColor').value = this.value;
    renderPreview();
});
document.getElementById('metaColor').addEventListener('input', function() {
    if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
        document.getElementById('metaColorPicker').value = this.value;
    }
    renderPreview();
});

// ── Utilities ─────────────────────────────────────────────────
function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function toHex6(color) {
    // If already valid #RRGGBB return as-is; otherwise return fallback
    if (/^#[0-9A-Fa-f]{6}$/.test(color)) return color;
    if (/^#[0-9A-Fa-f]{3}$/.test(color)) {
        return '#' + color[1]+color[1]+color[2]+color[2]+color[3]+color[3];
    }
    return '#E74C3C';
}

// ── Auto-select first dataset on load ────────────────────────
window.addEventListener('DOMContentLoaded', () => {
    const firstBtn = document.querySelector('.ds-btn');
    if (firstBtn) firstBtn.click();
});
</script>

<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
