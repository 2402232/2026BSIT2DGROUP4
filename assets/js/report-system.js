// report-system.js — BuligDiretso

// ── Emergency type selection ──────────────────────────────────
document.querySelectorAll('.emergency-box').forEach(function(box) {
    box.addEventListener('click', function() {
        document.querySelectorAll('.emergency-box').forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('emergency_type').value = this.getAttribute('data-type');
    });
});

// ── File Upload with Preview ──────────────────────────────────
const uploadZone  = document.getElementById('uploadZone');
const fileInput   = document.getElementById('file_upload');
const previewGrid = document.getElementById('previewGrid');
const fileCount   = document.getElementById('fileCount');

const MAX_FILES   = 5;
const MAX_MB      = 10;
const MAX_BYTES   = MAX_MB * 1024 * 1024;

// We maintain our own file list because FileList is read-only
let selectedFiles = [];

// ── Drag & drop visual feedback ───────────────────────────────
uploadZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('drag-over');
});

uploadZone.addEventListener('dragleave', function(e) {
    // Only remove if leaving the zone itself (not a child)
    if (!this.contains(e.relatedTarget)) {
        this.classList.remove('drag-over');
    }
});

uploadZone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('drag-over');
    handleFiles(e.dataTransfer.files);
});

// ── File input change ─────────────────────────────────────────
fileInput.addEventListener('change', function() {
    handleFiles(this.files);
    // Reset input so the same file can be re-added after removal
    this.value = '';
});

// ── Handle incoming files ─────────────────────────────────────
function handleFiles(incoming) {
    const errors = [];

    Array.from(incoming).forEach(function(file) {
        // Check total cap
        if (selectedFiles.length >= MAX_FILES) {
            errors.push('Maximum ' + MAX_FILES + ' files allowed.');
            return;
        }

        // Duplicate check (by name + size)
        const isDupe = selectedFiles.some(f => f.name === file.name && f.size === file.size);
        if (isDupe) return;

        // Type check
        if (!file.type.startsWith('image/') && !file.type.startsWith('video/')) {
            errors.push('"' + file.name + '" is not a supported image or video file.');
            return;
        }

        // Size check
        if (file.size > MAX_BYTES) {
            errors.push('"' + file.name + '" exceeds the ' + MAX_MB + ' MB limit.');
            return;
        }

        selectedFiles.push(file);
    });

    if (errors.length > 0) {
        // Show first error only to avoid overwhelming
        showUploadError(errors[0]);
    }

    renderPreviews();
    updateCounter();
}

// ── Render preview grid ───────────────────────────────────────
function renderPreviews() {
    previewGrid.innerHTML = '';

    selectedFiles.forEach(function(file, index) {
        const item = document.createElement('div');
        item.className = 'preview-item';

        if (file.type.startsWith('image/')) {
            // Image preview with FileReader
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = file.name;

                const label = document.createElement('div');
                label.className = 'file-label';
                label.textContent = file.name;

                item.appendChild(img);
                item.appendChild(label);
            };
            reader.readAsDataURL(file);
        } else {
            // Video placeholder
            const thumb = document.createElement('div');
            thumb.className = 'video-thumb';
            thumb.innerHTML = '<i class="ri-video-line"></i><span>' + escapeHtml(file.name) + '</span>';
            item.appendChild(thumb);
        }

        // Remove button
        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-btn';
        removeBtn.type = 'button';
        removeBtn.innerHTML = '&#x2715;';
        removeBtn.title = 'Remove file';
        removeBtn.addEventListener('click', function() {
            removeFile(index);
        });

        item.appendChild(removeBtn);
        previewGrid.appendChild(item);
    });
}

// ── Remove a file by index ────────────────────────────────────
function removeFile(index) {
    selectedFiles.splice(index, 1);
    renderPreviews();
    updateCounter();
}

// ── File counter ──────────────────────────────────────────────
function updateCounter() {
    const count = selectedFiles.length;

    if (count === 0) {
        fileCount.style.display = 'none';
        return;
    }

    fileCount.style.display = 'block';

    let cls, msg;
    if (count < MAX_FILES) {
        cls = 'fc-ok';
        msg = count + ' file' + (count > 1 ? 's' : '') + ' selected — you can add up to ' + (MAX_FILES - count) + ' more.';
    } else {
        cls = 'fc-warn';
        msg = MAX_FILES + ' files selected (maximum reached).';
    }

    fileCount.innerHTML = '<span class="' + cls + '">' + msg + '</span>';
}

// ── Error flash ───────────────────────────────────────────────
function showUploadError(msg) {
    fileCount.style.display = 'block';
    fileCount.innerHTML = '<span class="fc-err"><i class="ri-error-warning-line"></i> ' + escapeHtml(msg) + '</span>';
    setTimeout(function() {
        updateCounter();
    }, 3500);
}

function escapeHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Form submit ───────────────────────────────────────────────
document.getElementById('emergencyForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const type = document.getElementById('emergency_type').value;
    if (!type) {
        alert('Please select an emergency type!');
        return;
    }

    const details  = document.querySelector('textarea[name="additional_details"]')?.value || '';
    const location = document.querySelector('.location-alert strong')?.textContent?.replace('Location detected: ', '')
                     || 'Barangay Bulad, Isabela, Negros Occidental';

    // Build report object (files noted by count for localStorage demo)
    const report = {
        id:           'ER-' + Math.random().toString(36).substr(2, 6).toUpperCase(),
        type:         type,
        details:      details,
        location:     location,
        timestamp:    new Date().toISOString(),
        status:       'dispatched',
        responderIdx: Math.floor(Math.random() * 5),
        attachments:  selectedFiles.length,
    };

    let reports = [];
    try { reports = JSON.parse(localStorage.getItem('bd_reports') || '[]'); } catch(err) { reports = []; }
    reports.unshift(report);
    localStorage.setItem('bd_reports', JSON.stringify(reports));

    const btn = document.getElementById('submitBtn');
    btn.textContent = '✓ Report Submitted! Redirecting…';
    btn.style.background = '#22C55E';
    btn.disabled = true;

    setTimeout(function() {
        window.location.href = 'index.php?action=emergency-tracking';
    }, 1200);
});