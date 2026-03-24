// Admin Emergency Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    
    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    const emergencyCards = document.querySelectorAll('.emergency-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get filter type
            const filterType = this.textContent.trim();
            
            // Filter emergency cards
            filterEmergencies(filterType);
        });
    });
    
    function filterEmergencies(filterType) {
        emergencyCards.forEach(card => {
            const badges = card.querySelectorAll('.badge');
            let shouldShow = false;
            
            if (filterType === 'All Emergencies') {
                shouldShow = true;
            } else if (filterType === 'Critical') {
                badges.forEach(badge => {
                    if (badge.classList.contains('badge-critical')) {
                        shouldShow = true;
                    }
                });
            } else if (filterType === 'Pending Assignment') {
                badges.forEach(badge => {
                    if (badge.classList.contains('badge-pending')) {
                        shouldShow = true;
                    }
                });
            } else if (filterType === 'Responding') {
                // Check if responder is assigned (not "No responder assigned yet")
                const responderSection = card.querySelector('.responder-section');
                const noResponder = responderSection.querySelector('.no-responder');
                if (!noResponder) {
                    shouldShow = true;
                }
            }
            
            // Show/hide card with animation
            if (shouldShow) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 10);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
    }
    
    // Assign Responders button functionality
    const assignButtons = document.querySelectorAll('.btn-assign');
    
    assignButtons.forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.emergency-card');
            const emergencyId = card.querySelector('.emergency-id h3').textContent;
            const userName = card.querySelector('.info-item .value').textContent;
            
            // Show confirmation dialog
            const confirmed = confirm(`Assign responders to emergency ${emergencyId} for ${userName}?`);
            
            if (confirmed) {
                // You can add AJAX call here to assign responders
                showNotification(`Responders assigned to ${emergencyId}`, 'success');
                
                // Update UI to show responder assigned
                const responderSection = card.querySelector('.responder-section');
                responderSection.innerHTML = `
                    <span class="responder-label">Responder Assigned:</span>
                    <span class="responder-name" style="font-size: 13px; color: #27ae60; font-weight: 600;">Team Alpha - En Route</span>
                `;
                
                // Change pending badge to responding
                const pendingBadge = card.querySelector('.badge-pending');
                if (pendingBadge) {
                    pendingBadge.textContent = 'RESPONDING';
                    pendingBadge.style.background = '#27ae60';
                }
            }
        });
    });
    
    // View User Details button functionality
    const viewButtons = document.querySelectorAll('.btn-view');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.emergency-card');
            const emergencyId = card.querySelector('.emergency-id h3').textContent;
            
            // You can redirect to user details page or open modal
            // For now, we'll show an alert
            showNotification(`Loading details for ${emergencyId}...`, 'info');
            
            // Example: Redirect to details page
            // window.location.href = `user-details.php?id=${emergencyId}`;
        });
    });
    
    // Emergency card hover effects
    emergencyCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'all 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = 'none';
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Notification function
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Style notification
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: ${type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : '#3498db'};
            color: white;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            animation: slideIn 0.3s ease;
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
    
    // Auto-refresh emergency list every 30 seconds
    let autoRefreshInterval;
    
    function startAutoRefresh() {
        autoRefreshInterval = setInterval(() => {
            // You can add AJAX call here to refresh emergency list
            console.log('Auto-refreshing emergency list...');
            // fetchEmergencies();
        }, 30000); // 30 seconds
    }
    
    // Uncomment to enable auto-refresh
    // startAutoRefresh();
    
    // Search functionality (optional - add search input to HTML first)
    function addSearchFunctionality() {
        const searchInput = document.getElementById('emergency-search');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                
                emergencyCards.forEach(card => {
                    const userName = card.querySelector('.info-item .value').textContent.toLowerCase();
                    const emergencyId = card.querySelector('.emergency-id h3').textContent.toLowerCase();
                    const location = card.querySelectorAll('.info-item .value')[2].textContent.toLowerCase();
                    
                    if (userName.includes(searchTerm) || 
                        emergencyId.includes(searchTerm) || 
                        location.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    }
    
    // Sort functionality
    function sortEmergencies(sortBy) {
        const emergencyList = document.querySelector('.emergency-list');
        const cardsArray = Array.from(emergencyCards);
        
        cardsArray.sort((a, b) => {
            if (sortBy === 'priority') {
                // Sort by priority (Critical > Medium > Low)
                const priorityOrder = { 'CRITICAL': 0, 'MEDIUM': 1, 'LOW': 2 };
                const aPriority = a.querySelector('.badge').textContent;
                const bPriority = b.querySelector('.badge').textContent;
                return priorityOrder[aPriority] - priorityOrder[bPriority];
            } else if (sortBy === 'time') {
                // Sort by creation time (newest first)
                const aTime = a.querySelector('.info-item .value').textContent;
                const bTime = b.querySelector('.info-item .value').textContent;
                return bTime.localeCompare(aTime);
            }
        });
        
        // Clear and re-append sorted cards
        emergencyList.innerHTML = '';
        cardsArray.forEach(card => emergencyList.appendChild(card));
    }
    
    // Initialize animations
    emergencyCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Count emergencies by type
    function updateEmergencyCounts() {
        const counts = {
            total: emergencyCards.length,
            critical: 0,
            medium: 0,
            low: 0,
            pending: 0
        };
        
        emergencyCards.forEach(card => {
            const badges = card.querySelectorAll('.badge');
            badges.forEach(badge => {
                if (badge.classList.contains('badge-critical')) counts.critical++;
                if (badge.classList.contains('badge-medium')) counts.medium++;
                if (badge.classList.contains('badge-low')) counts.low++;
                if (badge.classList.contains('badge-pending')) counts.pending++;
            });
        });
        
        console.log('Emergency Counts:', counts);
        return counts;
    }
    
    // Update counts on page load
    const counts = updateEmergencyCounts();
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Alt + 1-4 for quick filter switching
        if (e.altKey) {
            const keyMap = {
                '1': 0, // All Emergencies
                '2': 1, // Critical
                '3': 2, // Pending Assignment
                '4': 3  // Responding
            };
            
            if (keyMap[e.key] !== undefined) {
                filterButtons[keyMap[e.key]].click();
            }
        }
    });
    
    console.log('Admin Emergency Dashboard initialized');
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .emergency-card {
        transition: all 0.3s ease;
    }
`;
document.head.appendChild(style);