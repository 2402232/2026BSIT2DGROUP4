// Header JavaScript - Hamburger Menu Toggle

document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function() {
            // Toggle classes
            hamburger.classList.toggle('open');
            mobileMenu.classList.toggle('open');
            
            // Update aria-expanded for accessibility
            const isOpen = hamburger.classList.contains('open');
            hamburger.setAttribute('aria-expanded', isOpen);
        });
        
        // Close menu when clicking on a link
        const mobileLinks = mobileMenu.querySelectorAll('.mobile-nav-item');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                hamburger.classList.remove('open');
                mobileMenu.classList.remove('open');
                hamburger.setAttribute('aria-expanded', 'false');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideMenu = mobileMenu.contains(event.target);
            const isClickOnHamburger = hamburger.contains(event.target);
            
            if (!isClickInsideMenu && !isClickOnHamburger && mobileMenu.classList.contains('open')) {
                hamburger.classList.remove('open');
                mobileMenu.classList.remove('open');
                hamburger.setAttribute('aria-expanded', 'false');
            }
        });
    }
});