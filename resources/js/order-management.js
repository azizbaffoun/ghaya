// Order Management JavaScript - Optimized for performance
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Alpine.js store for selected orders
    if (typeof Alpine !== 'undefined') {
        Alpine.store('selectedOrders', []);
    }
    
    // Initialize bulk selection functionality
    initializeBulkSelection();
    
    // Initialize print functionality
    initializePrintFunctionality();
    
    // Initialize status checking
    initializeStatusChecking();
});

function initializeBulkSelection() {
    // Handle select all checkbox
    const selectAllCheckbox = document.querySelector('input[type="checkbox"][onchange*="selectedOrders"]');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][value]');
            const isChecked = this.checked;
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                if (typeof Alpine !== 'undefined') {
                    toggleOrder(parseInt(checkbox.value));
                }
            });
        });
    }
}

function initializePrintFunctionality() {
    // Handle print selected functionality
    window.printSelected = function() {
        const selectedOrders = Alpine.store('selectedOrders') || [];
        if (selectedOrders.length === 0) {
            alert('Please select orders first');
            return;
        }
        
        // Get print URLs for selected orders
        const printUrls = [];
        selectedOrders.forEach(orderId => {
            const row = document.querySelector(`input[value="${orderId}"]`).closest('tr');
            const printLink = row.querySelector('a[href^="http"]');
            if (printLink) {
                printUrls.push(printLink.href);
            }
        });
        
        // Open all print URLs in new tabs
        printUrls.forEach(url => {
            window.open(url, '_blank');
        });
    };
}

function initializeStatusChecking() {
    // Handle status checking with rate limiting
    let lastStatusCheck = 0;
    const STATUS_CHECK_INTERVAL = 1000; // 1 second
    
    window.checkOrderStatus = function(barcode, orderId) {
        if (!barcode) {
            alert('No barcode available for this order');
            return;
        }
        
        const now = Date.now();
        if (now - lastStatusCheck < STATUS_CHECK_INTERVAL) {
            alert('Please wait before checking another status (rate limited)');
            return;
        }
        lastStatusCheck = now;
        
        // Show loading state
        const statusElement = document.querySelector(`[data-order-id="${orderId}"]`);
        if (statusElement) {
            const loadingEl = statusElement.querySelector('.loading');
            const statusEl = statusElement.querySelector('.status');
            if (loadingEl) loadingEl.style.display = 'block';
            if (statusEl) statusEl.style.display = 'none';
        }
        
        // Make API call to check status
        fetch(`/admin/orders/check-status/${barcode}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update status display
                if (statusElement) {
                    const loadingEl = statusElement.querySelector('.loading');
                    const statusEl = statusElement.querySelector('.status');
                    const statusTextEl = statusElement.querySelector('.status-text');
                    
                    if (loadingEl) loadingEl.style.display = 'none';
                    if (statusEl) statusEl.style.display = 'block';
                    if (statusTextEl) statusTextEl.textContent = data.status.state;
                }
            } else {
                alert('Failed to check status: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to check status');
        });
    };
}

function toggleOrder(orderId) {
    if (typeof Alpine === 'undefined') return;
    
    const selectedOrders = Alpine.store('selectedOrders') || [];
    const index = selectedOrders.indexOf(orderId);
    
    if (index > -1) {
        selectedOrders.splice(index, 1);
    } else {
        selectedOrders.push(orderId);
    }
    
    Alpine.store('selectedOrders', selectedOrders);
}

// Modal functions
function openNotesModal(orderId, currentNotes) {
    const form = document.getElementById('notesForm');
    const textarea = document.querySelector('textarea[name="staff_notes"]');
    
    if (form) form.action = `/admin/orders/${orderId}/notes`;
    if (textarea) textarea.value = currentNotes || '';
    
    const modal = document.getElementById('notesModal');
    if (modal) modal.classList.remove('hidden');
}

function closeNotesModal() {
    const modal = document.getElementById('notesModal');
    if (modal) modal.classList.add('hidden');
}

function openReminderModal(orderId) {
    const form = document.getElementById('reminderForm');
    if (form) form.action = `/admin/orders/${orderId}/reminder`;
    
    const modal = document.getElementById('reminderModal');
    if (modal) modal.classList.remove('hidden');
}

function closeReminderModal() {
    const modal = document.getElementById('reminderModal');
    if (modal) modal.classList.add('hidden');
}

function setReminderTime(hours) {
    const now = new Date();
    now.setHours(now.getHours() + hours);
    
    const datetimeLocal = now.toISOString().slice(0, 16);
    const input = document.querySelector('input[name="reminder_at"]');
    if (input) input.value = datetimeLocal;
}

function markAsPickedUp(orderId) {
    const form = document.getElementById('pickedUpForm');
    if (form) form.action = `/admin/orders/${orderId}/status`;
    
    const statusInput = document.querySelector('input[name="status"]');
    if (statusInput) statusInput.value = 'pickup_requested';
    
    const modal = document.getElementById('pickedUpModal');
    if (modal) modal.classList.remove('hidden');
}

function closePickedUpModal() {
    const modal = document.getElementById('pickedUpModal');
    if (modal) modal.classList.add('hidden');
}

// Auto-refresh functionality
function refreshAllStatuses() {
    const checkButtons = document.querySelectorAll('button[onclick^="checkOrderStatus"]');
    checkButtons.forEach(button => {
        button.click();
    });
}

function refreshSelectedStatuses() {
    if (typeof Alpine === 'undefined') return;
    
    const selectedOrders = Alpine.store('selectedOrders') || [];
    selectedOrders.forEach(orderId => {
        const row = document.querySelector(`input[value="${orderId}"]`).closest('tr');
        const checkButton = row.querySelector('button[onclick^="checkOrderStatus"]');
        if (checkButton) {
            checkButton.click();
        }
    });
}

// Auto-refresh disabled - manual refresh only
// Clear any existing intervals to prevent unwanted refreshes
function clearAllIntervals() {
    // Clear the specific interval we know about
    if (window.orderManagementInterval) {
        clearInterval(window.orderManagementInterval);
        window.orderManagementInterval = null;
    }
    
    // Clear any other potential intervals (safety measure)
    for (let i = 1; i < 10000; i++) {
        clearInterval(i);
    }
}

// Run cleanup immediately
clearAllIntervals();

// Debug: Log any potential refresh triggers
console.log('Order management script loaded - auto-refresh disabled');
console.log('Current URL:', window.location.href);
console.log('Page path:', window.location.pathname);

// Monitor for any unexpected page reloads
let reloadCount = 0;
window.addEventListener('beforeunload', function() {
    reloadCount++;
    console.log('Page is about to reload/reload count:', reloadCount);
});

// Check if page is being refreshed by something else
if (performance.navigation && performance.navigation.type === 1) {
    console.log('Page was refreshed by user or script');
}

// if (window.location.pathname.includes('in-delivery')) {
//     // Prevent multiple intervals
//     if (!window.orderManagementInterval) {
//         window.orderManagementInterval = setInterval(() => {
//             refreshAllStatuses();
//         }, 30000);
//     }
// }

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    const notesModal = document.getElementById('notesModal');
    const reminderModal = document.getElementById('reminderModal');
    const pickedUpModal = document.getElementById('pickedUpModal');
    
    if (notesModal && e.target === notesModal) {
        closeNotesModal();
    }
    
    if (reminderModal && e.target === reminderModal) {
        closeReminderModal();
    }
    
    if (pickedUpModal && e.target === pickedUpModal) {
        closePickedUpModal();
    }
});

// Export functions for global access
window.toggleOrder = toggleOrder;
window.openNotesModal = openNotesModal;
window.closeNotesModal = closeNotesModal;
window.openReminderModal = openReminderModal;
window.closeReminderModal = closeReminderModal;
window.setReminderTime = setReminderTime;
window.markAsPickedUp = markAsPickedUp;
window.closePickedUpModal = closePickedUpModal;
window.refreshAllStatuses = refreshAllStatuses;
window.refreshSelectedStatuses = refreshSelectedStatuses;
