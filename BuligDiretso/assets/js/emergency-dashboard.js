// Emergency Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard
    initializeFilters();
    initializeEmergencyItems();
    updateStatusCards();
    
    // Optional: Set up auto-refresh for real-time updates
    // setInterval(refreshEmergencyData, 30000); // Refresh every 30 seconds
});

// Filter functionality
function initializeFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get filter value
            const filterValue = this.textContent.trim().toUpperCase();
            
            // Filter emergencies
            filterEmergencies(filterValue);
        });
    });
}

// Filter emergency items based on status
function filterEmergencies(status) {
    const emergencyItems = document.querySelectorAll('.emergency-item');
    
    emergencyItems.forEach(item => {
        const itemStatus = item.querySelector('.em-status').textContent.trim().toUpperCase();
        
        if (status === 'ALL EMERGENCIES') {
            item.style.display = 'block';
        } else if (itemStatus === status) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
    
    // Update count after filtering
    updateVisibleCount();
}

// Update visible emergency count
function updateVisibleCount() {
    const visibleItems = document.querySelectorAll('.emergency-item[style*="display: block"], .emergency-item:not([style*="display: none"])');
    console.log(`Showing ${visibleItems.length} emergencies`);
}

// Add click handlers to emergency items
function initializeEmergencyItems() {
    const emergencyItems = document.querySelectorAll('.emergency-item');
    
    emergencyItems.forEach(item => {
        item.addEventListener('click', function() {
            const emergencyId = this.querySelector('.em-id').textContent;
            viewEmergencyDetails(emergencyId);
        });
        
        // Add hover effect
        item.style.cursor = 'pointer';
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
}

// View emergency details (can be expanded to show modal or redirect)
function viewEmergencyDetails(emergencyId) {
    console.log('Viewing details for:', emergencyId);
    // TODO: Implement modal or redirect to details page
    // Example: window.location.href = `emergency-details.php?id=${emergencyId}`;
    alert(`Viewing details for ${emergencyId}\n\nThis can be replaced with a modal or details page.`);
}

// Update status cards with current counts
function updateStatusCards() {
    const emergencyItems = document.querySelectorAll('.emergency-item');
    const statusCounts = {
        'CRITICAL': 0,
        'HIGH PRIORITY': 0,
        'MODERATE': 0,
        'LOW PRIORITY': 0,
        'RESOLVED': 0
    };
    
    emergencyItems.forEach(item => {
        const status = item.querySelector('.em-status').textContent.trim().toUpperCase();
        
        if (status === 'CRITICAL') {
            statusCounts['CRITICAL']++;
        } else if (status === 'HIGH PRIORITY') {
            statusCounts['HIGH PRIORITY']++;
        } else if (status === 'MODERATE') {
            statusCounts['MODERATE']++;
        } else if (status === 'LOW PRIORITY' || status === 'RESOLVED') {
            statusCounts['LOW PRIORITY']++;
        }
    });
    
    // Update card values (if you want dynamic updates)
    // document.querySelector('.card.critical h2').textContent = statusCounts['CRITICAL'];
    // document.querySelector('.card.high-priority h2').textContent = statusCounts['HIGH PRIORITY'];
    // document.querySelector('.card.moderate h2').textContent = statusCounts['MODERATE'];
    // document.querySelector('.card.low-priority h2').textContent = statusCounts['LOW PRIORITY'];
}

// Search functionality (optional)
function searchEmergencies(searchTerm) {
    const emergencyItems = document.querySelectorAll('.emergency-item');
    searchTerm = searchTerm.toLowerCase();
    
    emergencyItems.forEach(item => {
        const id = item.querySelector('.em-id').textContent.toLowerCase();
        const desc = item.querySelector('.em-desc').textContent.toLowerCase();
        const loc = item.querySelector('.em-loc').textContent.toLowerCase();
        const type = item.querySelector('.em-type').textContent.toLowerCase();
        
        if (id.includes(searchTerm) || 
            desc.includes(searchTerm) || 
            loc.includes(searchTerm) || 
            type.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Refresh emergency data (for future AJAX implementation)
function refreshEmergencyData() {
    // TODO: Implement AJAX call to fetch updated emergency data
    console.log('Refreshing emergency data...');
    
    /*
    fetch('api/get-emergencies.php')
        .then(response => response.json())
        .then(data => {
            updateEmergencyList(data);
            updateStatusCards();
        })
        .catch(error => console.error('Error fetching emergencies:', error));
    */
}

// Update emergency list with new data
function updateEmergencyList(emergencies) {
    const emergencyList = document.querySelector('.emergency-list');
    emergencyList.innerHTML = '';
    
    emergencies.forEach(em => {
        const emergencyHTML = `
            <div class="emergency-item">
                <div class="emergency-top">
                    <span class="em-id">${em.id}</span>
                    <span class="em-status" style="background-color: ${em.color}">
                        ${em.status}
                    </span>
                    <span class="em-type">${em.type}</span>
                </div>
                <div class="emergency-info">
                    <p class="em-desc">${em.desc}</p>
                    <p class="em-loc">Location: ${em.loc}</p>
                </div>
            </div>
        `;
        emergencyList.insertAdjacentHTML('beforeend', emergencyHTML);
    });
    
    // Re-initialize event listeners
    initializeEmergencyItems();
}

// Add notification for new emergencies (optional)
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background-color: ${type === 'critical' ? '#E74C3C' : '#3498DB'};
        color: white;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

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
`;
document.head.appendChild(style);

// Export functions for use in other scripts if needed
window.emergencyDashboard = {
    searchEmergencies,
    viewEmergencyDetails,
    refreshEmergencyData,
    showNotification
};