// emergency-tracking.js  — BuligDiretso

// ── Responder pool ──────────────────────────────────────────────
const RESPONDERS = [
    { name: 'Responder Unit 1 — LDRRMO',  phone: '0951 682 1504', eta: 6  },
    { name: 'Responder Unit 2 — BFP',     phone: '0970 465 9383', eta: 8  },
    { name: 'Responder Unit 3 — PNP',     phone: '0999 659 0677', eta: 5  },
    { name: 'Responder Unit 4 — MHO',     phone: '0963 156 6032', eta: 10 },
    { name: 'Responder Unit 5 — ILASMDH', phone: '0947 415 4761', eta: 7  },
];

const STATUS_ORDER = ['dispatched', 'en route', 'on scene', 'resolved'];

const STATUS_META = {
    'dispatched': { color: '#3B82F6', bg: '#EFF6FF', label: 'DISPATCHED',  icon: 'ri-send-plane-line' },
    'en route':   { color: '#F59E0B', bg: '#FFFBEB', label: 'EN ROUTE',    icon: 'ri-car-line' },
    'on scene':   { color: '#8B5CF6', bg: '#F5F3FF', label: 'ON SCENE',    icon: 'ri-map-pin-2-line' },
    'resolved':   { color: '#22C55E', bg: '#F0FDF4', label: 'RESOLVED',    icon: 'ri-checkbox-circle-line' },
};

const TYPE_ICONS = {
    medical: '🏥', accident: '🚗', animal: '🐾',
    disaster: '🌪️', fire: '🔥', other: '⚡',
};

const TYPE_PRIORITY = {
    medical: 'CRITICAL', fire: 'CRITICAL',
    accident: 'HIGH', disaster: 'HIGH',
    animal: 'MODERATE', other: 'LOW',
};

// ── Seed demo data ─────────────────────────────────────────────
function seedDemoData() {
    if (getReports().length > 0) return;
    const demos = [
        {
            id: 'ER-' + Math.random().toString(36).substr(2,6).toUpperCase(),
            type: 'medical',
            details: 'Person collapsed near the public market. Suspected cardiac arrest.',
            location: 'Barangay Bulad, Isabela, Negros Occidental',
            timestamp: new Date(Date.now() - 12 * 60000).toISOString(),
            status: 'on scene',
            responderIdx: 0,
        },
        {
            id: 'ER-' + Math.random().toString(36).substr(2,6).toUpperCase(),
            type: 'fire',
            details: 'Kitchen fire at residence. Smoke visible from street.',
            location: 'Sitio Maligaya, Brgy. Poblacion, Isabela',
            timestamp: new Date(Date.now() - 5 * 60000).toISOString(),
            status: 'en route',
            responderIdx: 1,
        },
    ];
    localStorage.setItem('bd_reports', JSON.stringify(demos));
}

function getReports() {
    try { return JSON.parse(localStorage.getItem('bd_reports') || '[]'); }
    catch(e) { return []; }
}

function saveReports(r) { localStorage.setItem('bd_reports', JSON.stringify(r)); }

function autoAdvance() {
    const reports = getReports();
    let changed = false;
    reports.forEach(r => {
        if (r.status === 'resolved') return;
        const idx = STATUS_ORDER.indexOf(r.status);
        if (idx < STATUS_ORDER.length - 1 && Math.random() < 0.20) {
            r.status = STATUS_ORDER[idx + 1];
            changed = true;
        }
    });
    if (changed) saveReports(reports);
}

// ── Render ────────────────────────────────────────────────────
let currentFilter = 'all';

function loadReports() {
    const icon = document.getElementById('refreshIcon');
    if (icon) { icon.style.transform='rotate(360deg)'; icon.style.transition='transform 0.5s'; setTimeout(()=>{ icon.style.transform=''; icon.style.transition=''; },500); }

    const reports = getReports();
    updateStats(reports);
    renderList(reports);
    updateLastUpdate();
}

function updateStats(reports) {
    document.getElementById('statTotal').textContent      = reports.length;
    document.getElementById('statCritical').textContent   = reports.filter(r=>TYPE_PRIORITY[r.type]==='CRITICAL').length;
    document.getElementById('statDispatched').textContent = reports.filter(r=>r.status!=='resolved').length;
    document.getElementById('statResolved').textContent   = reports.filter(r=>r.status==='resolved').length;
}

function renderList(reports) {
    const list  = document.getElementById('reportList');
    const empty = document.getElementById('emptyState');
    const filtered = currentFilter === 'all' ? reports : reports.filter(r=>r.status===currentFilter);
    if (filtered.length === 0) { list.innerHTML=''; empty.style.display='block'; return; }
    empty.style.display = 'none';
    const sorted = [...filtered].sort((a,b)=>{
        if (a.status==='resolved' && b.status!=='resolved') return 1;
        if (b.status==='resolved' && a.status!=='resolved') return -1;
        return new Date(b.timestamp)-new Date(a.timestamp);
    });
    list.innerHTML = sorted.map(r=>reportCard(r)).join('');
}

function reportCard(r) {
    const meta      = STATUS_META[r.status] || STATUS_META['dispatched'];
    const responder = RESPONDERS[r.responderIdx || 0];
    const priority  = TYPE_PRIORITY[r.type] || 'LOW';
    const icon      = TYPE_ICONS[r.type] || '⚡';
    const age       = timeAgo(r.timestamp);
    const stepsDone = STATUS_ORDER.indexOf(r.status) + 1;
    const pColor    = {CRITICAL:'#DC2626',HIGH:'#F59E0B',MODERATE:'#8B5CF6',LOW:'#22C55E'}[priority]||'#667085';

    const steps = STATUS_ORDER.map((s,i) => {
        const sm = STATUS_META[s];
        return `<div class="rc-step ${i<stepsDone?'done':''} ${i===stepsDone-1?'active':''}">
            <div class="rc-step-dot" style="${i<stepsDone?'background:'+sm.color+';border-color:'+sm.color+';':''}"></div>
            <span>${capitalize(s)}</span>
        </div>${i<STATUS_ORDER.length-1?`<div class="rc-step-line ${i<stepsDone-1?'filled':''}"></div>`:''}`;
    }).join('');

    return `<div class="tracking-card report-card" onclick="openModal('${r.id}')">
        <div class="rc-top">
            <div class="rc-left">
                <span class="rc-icon">${icon}</span>
                <div>
                    <div class="rc-id-row">
                        <span class="rc-id">${r.id}</span>
                        <span class="rc-priority" style="color:${pColor};background:${pColor}18;">${priority}</span>
                    </div>
                    <p class="rc-type">${capitalize(r.type)} Emergency</p>
                </div>
            </div>
            <span class="rc-status" style="color:${meta.color};background:${meta.bg};">
                <i class="${meta.icon}"></i> ${meta.label}
            </span>
        </div>
        <p class="rc-desc">${r.details||'No additional details provided.'}</p>
        <div class="rc-meta-row">
            <span class="rc-loc"><i class="ri-map-pin-line"></i> ${r.location||'Location not provided'}</span>
            <span class="rc-age"><i class="ri-time-line"></i> ${age}</span>
        </div>
        <div class="rc-progress">${steps}</div>
        <div class="rc-responder">
            <i class="ri-user-3-line"></i>
            <span>${responder.name}</span>
            ${r.status!=='resolved'
                ? `<span class="rc-eta"><i class="ri-timer-line"></i> ETA ~${responder.eta} min</span>`
                : `<span class="rc-eta resolved-tag"><i class="ri-checkbox-circle-line"></i> Completed</span>`}
        </div>
    </div>`;
}

// ── Modal ────────────────────────────────────────────────────
function openModal(id) {
    const r = getReports().find(rep=>rep.id===id);
    if (!r) return;
    const meta      = STATUS_META[r.status]||STATUS_META['dispatched'];
    const responder = RESPONDERS[r.responderIdx||0];

    document.getElementById('modalContent').innerHTML = `
        <div class="modal-header">
            <span class="modal-icon">${TYPE_ICONS[r.type]||'⚡'}</span>
            <div>
                <h2>${capitalize(r.type)} Emergency</h2>
                <span class="rc-id">${r.id}</span>
            </div>
        </div>
        <div class="modal-status-row">
            <span class="rc-status" style="color:${meta.color};background:${meta.bg};font-size:0.9rem;padding:6px 14px;">
                <i class="${meta.icon}"></i> ${meta.label}
            </span>
            <span class="modal-age">${timeAgo(r.timestamp)}</span>
        </div>
        <div class="modal-section">
            <h3><i class="ri-information-line"></i> Incident Details</h3>
            <p>${r.details||'No additional details provided.'}</p>
        </div>
        <div class="modal-section">
            <h3><i class="ri-map-pin-line"></i> Location</h3>
            <p>${r.location||'Location not captured'}</p>
            <div class="map-placeholder">
                <i class="ri-map-2-line"></i>
                <span>${r.location||'Barangay Bulad, Isabela, Negros Occidental'}</span>
            </div>
        </div>
        <div class="modal-section">
            <h3><i class="ri-user-3-line"></i> Assigned Responder</h3>
            <div class="responder-box">
                <div class="responder-avatar"><i class="ri-user-3-line"></i></div>
                <div>
                    <p class="resp-name">${responder.name}</p>
                    <a class="resp-phone" href="tel:${responder.phone.replace(/\s/g,'')}">
                        <i class="ri-phone-line"></i> ${responder.phone}
                    </a>
                </div>
                ${r.status!=='resolved'?`<span class="rc-eta" style="margin-left:auto;"><i class="ri-timer-line"></i> ETA ~${responder.eta} min</span>`:''}
            </div>
        </div>
        <div class="modal-section">
            <h3><i class="ri-list-check"></i> Status Timeline</h3>
            <div class="timeline">${buildTimeline(r)}</div>
        </div>
        ${r.status!=='resolved'
            ? `<div class="modal-actions">
                <button class="action-btn btn-advance" onclick="advanceStatus('${r.id}')">
                    <i class="ri-arrow-right-circle-line"></i> Advance Status
                </button>
                <button class="action-btn btn-resolve" onclick="resolveReport('${r.id}')">
                    <i class="ri-checkbox-circle-line"></i> Mark Resolved
                </button>
               </div>`
            : `<div class="resolved-banner"><i class="ri-checkbox-circle-fill"></i> This emergency has been resolved.</div>`}
    `;
    document.getElementById('modalOverlay').classList.add('open');
}

function buildTimeline(r) {
    const currentIdx = STATUS_ORDER.indexOf(r.status);
    return STATUS_ORDER.map((s,i) => {
        const done = i <= currentIdx;
        const m    = STATUS_META[s];
        const t    = done ? (i===currentIdx ? 'Just now' : getStepTime(r.timestamp,i,currentIdx)) : 'Pending';
        return `<div class="tl-item ${done?'done':''}">
            <div class="tl-dot" style="${done?'background:'+m.color+';border-color:'+m.color+';':''}" ><i class="${m.icon}"></i></div>
            <div class="tl-info">
                <p class="tl-label">${m.label}</p>
                <p class="tl-time">${t}</p>
            </div>
        </div>`;
    }).join('');
}

function getStepTime(base, stepIdx, currentIdx) {
    const ms = new Date(base).getTime() + (stepIdx - currentIdx) * 4 * 60000;
    return new Date(ms).toLocaleTimeString([],{hour:'2-digit',minute:'2-digit'});
}

function advanceStatus(id) {
    const reports = getReports();
    const r = reports.find(rep=>rep.id===id);
    if (!r) return;
    const idx = STATUS_ORDER.indexOf(r.status);
    if (idx < STATUS_ORDER.length-1) { r.status=STATUS_ORDER[idx+1]; saveReports(reports); closeModal(); loadReports(); }
}

function resolveReport(id) {
    const reports = getReports();
    const r = reports.find(rep=>rep.id===id);
    if (!r) return;
    r.status='resolved'; saveReports(reports); closeModal(); loadReports();
}

function closeModal() { document.getElementById('modalOverlay').classList.remove('open'); }

// ── Filter tabs ──────────────────────────────────────────────
document.getElementById('filterTabs').addEventListener('click', function(e) {
    const btn = e.target.closest('.ftab');
    if (!btn) return;
    document.querySelectorAll('.ftab').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    currentFilter = btn.dataset.filter;
    loadReports();
});

// ── Keyboard close ───────────────────────────────────────────
document.addEventListener('keydown', e => { if(e.key==='Escape') closeModal(); });

// ── Helpers ───────────────────────────────────────────────────
function timeAgo(iso) {
    const diff = Math.floor((Date.now()-new Date(iso))/1000);
    if(diff<60) return diff+'s ago';
    if(diff<3600) return Math.floor(diff/60)+'m ago';
    return Math.floor(diff/3600)+'h ago';
}

function capitalize(s) { return s?s.charAt(0).toUpperCase()+s.slice(1):''; }

function updateLastUpdate() {
    const el = document.getElementById('lastUpdate');
    if(el) el.textContent = 'Updated '+new Date().toLocaleTimeString([],{hour:'2-digit',minute:'2-digit'});
}

// ── Init ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    seedDemoData();
    loadReports();
    setInterval(()=>{ autoAdvance(); loadReports(); }, 30000);
});