// report-system.js — BuligDiretso

// Emergency type selection
document.querySelectorAll('.emergency-box').forEach(function(box) {
    box.addEventListener('click', function() {
        document.querySelectorAll('.emergency-box').forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('emergency_type').value = this.getAttribute('data-type');
    });
});

// File upload — show filename
document.getElementById('file_upload').addEventListener('change', function() {
    if (this.files.length > 0) {
        var p = document.querySelector('.upload-area p');
        p.textContent = 'Selected: ' + this.files[0].name;
        p.style.color = '#16A34A';
    }
});

// ── Save report to localStorage and redirect to Tracking ──────
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();

    const type = document.getElementById('emergency_type').value;
    if (!type) {
        alert('Please select an emergency type!');
        return;
    }

    const details  = document.querySelector('textarea[name="additional_details"]')?.value || '';
    const location = document.querySelector('.location-alert strong')?.textContent?.replace('Location detected: ', '') 
                     || 'Barangay Bulad, Isabela, Negros Occidental';

    // Build report object
    const report = {
        id:           'ER-' + Math.random().toString(36).substr(2, 6).toUpperCase(),
        type:         type,
        details:      details,
        location:     location,
        timestamp:    new Date().toISOString(),
        status:       'dispatched',
        responderIdx: Math.floor(Math.random() * 5),
    };

    // Append to existing reports
    let reports = [];
    try { reports = JSON.parse(localStorage.getItem('bd_reports') || '[]'); } catch(e) { reports = []; }
    reports.unshift(report);
    localStorage.setItem('bd_reports', JSON.stringify(reports));

    // Brief success feedback then redirect
    const btn = document.querySelector('.btn-submit');
    btn.textContent = '✓ Report Submitted! Redirecting…';
    btn.style.background = '#22C55E';
    btn.disabled = true;

    setTimeout(() => {
        window.location.href = 'index.php?action=emergency-tracking';
    }, 1200);
});