// Admin Responders JavaScript
// Simple and clean code that will run properly

// ========== SEARCH FUNCTIONALITY ==========
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-box input');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const name = row.querySelector('.name').textContent.toLowerCase();
                const location = row.querySelectorAll('td')[1].textContent.toLowerCase();
                const assignment = row.querySelectorAll('td')[2].textContent.toLowerCase();
                
                if (name.includes(searchTerm) || location.includes(searchTerm) || assignment.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});

// ========== FILTER BUTTONS ==========
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active to clicked button
            this.classList.add('active');
            
            // Get filter type
            const filterType = this.textContent.trim();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const statusElement = row.querySelector('.status');
                const statusText = statusElement.textContent.trim();
                
                if (filterType === 'All') {
                    row.style.display = '';
                } else if (filterType === statusText) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});

// ========== ADD BUTTON ==========
document.addEventListener('DOMContentLoaded', function() {
    const addButton = document.querySelector('.add-btn');

    if (addButton) {
        addButton.addEventListener('click', function() {
            alert('Opening form to add new responder');
            // You can change this to navigate to add page
            // window.location.href = 'add-responder.php';
        });
    }
});

// ========== ACTION BUTTONS ==========
document.addEventListener('DOMContentLoaded', function() {
    
    // View buttons (eye icon)
    const viewButtons = document.querySelectorAll('.action-buttons button:nth-child(1)');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const name = row.querySelector('.name').textContent;
            alert('Viewing details for: ' + name);
            // window.location.href = 'view-responder.php?name=' + encodeURIComponent(name);
        });
    });
    
    // Edit buttons (edit icon)
    const editButtons = document.querySelectorAll('.action-buttons button:nth-child(2)');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const name = row.querySelector('.name').textContent;
            alert('Editing responder: ' + name);
            // window.location.href = 'edit-responder.php?name=' + encodeURIComponent(name);
        });
    });
    
    // Delete buttons (trash icon)
    const deleteButtons = document.querySelectorAll('.action-buttons button:nth-child(3)');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const name = row.querySelector('.name').textContent;
            
            if (confirm('Are you sure you want to delete ' + name + '?')) {
                row.remove();
                alert(name + ' has been deleted');
                // Send delete request to server here
                // fetch('delete-responder.php?name=' + encodeURIComponent(name), {method: 'POST'});
            }
        });
    });
});

// ========== ASSIGNMENT LINKS ==========
document.addEventListener('DOMContentLoaded', function() {
    const assignmentLinks = document.querySelectorAll('.assignment-link');
    
    assignmentLinks.forEach(link => {
        link.style.cursor = 'pointer';
        link.addEventListener('click', function() {
            const emergencyId = this.textContent;
            alert('Viewing emergency: ' + emergencyId);
            // window.location.href = 'emergency-details.php?id=' + emergencyId;
        });
    });
});

// ========== TABLE ROW HOVER ==========
document.addEventListener('DOMContentLoaded', function() {
    const tableRows = document.querySelectorAll('tbody tr');

    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f0f8ff';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});

// ========== TABLE SORTING (Optional) ==========
document.addEventListener('DOMContentLoaded', function() {
    const headers = document.querySelectorAll('th');
    
    headers.forEach((header, index) => {
        // Don't make ACTIONS column sortable
        if (index < 4) {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                sortTable(index);
            });
        }
    });
});

function sortTable(columnIndex) {
    const table = document.querySelector('table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const aValue = a.querySelectorAll('td')[columnIndex].textContent.trim();
        const bValue = b.querySelectorAll('td')[columnIndex].textContent.trim();
        
        return aValue.localeCompare(bValue);
    });
    
    rows.forEach(row => tbody.appendChild(row));
}

// ========== PAGE LOAD ANIMATION ==========
window.addEventListener('load', function() {
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateX(0)';
        }, index * 50);
    });
});

// ========== UTILITY FUNCTIONS ==========

// Count responders by status
function countRespondersByStatus() {
    const rows = document.querySelectorAll('tbody tr');
    const counts = {
        Active: 0,
        Responding: 0,
        Offline: 0
    };
    
    rows.forEach(row => {
        const status = row.querySelector('.status').textContent.trim();
        if (counts[status] !== undefined) {
            counts[status]++;
        }
    });
    
    console.log('Responder Status Count:', counts);
    return counts;
}

// Call this function on page load to see counts
document.addEventListener('DOMContentLoaded', function() {
    countRespondersByStatus();
});