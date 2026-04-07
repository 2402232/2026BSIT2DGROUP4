// guides.js

const searchInput = document.getElementById('guideSearch');
const guideList   = document.getElementById('guideList');
const noResults   = document.getElementById('noResults');

// Live search filter
searchInput.addEventListener('input', function () {
    const query = this.value.toLowerCase().trim();
    const items = guideList.querySelectorAll('.guide-item');
    let visibleCount = 0;

    items.forEach(function (item) {
        const title = item.getAttribute('data-title') || '';
        const match = title.includes(query);
        item.style.display = match ? '' : 'none';
        if (match) visibleCount++;
    });

    noResults.style.display = visibleCount === 0 ? 'flex' : 'none';
});

// Read button click — placeholder for opening a guide detail view
guideList.addEventListener('click', function (e) {
    const btn = e.target.closest('.guide-btn');
    if (!btn) return;

    const item  = btn.closest('.guide-item');
    const title = item.querySelector('.guide-title').textContent;

    // TODO: replace with navigation to guide detail page
    console.log('Opening guide:', title);
});