// Website Management JavaScript Functions

// Make functions globally available to prevent ReferenceError
window.switchTab = function(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    const targetContent = document.getElementById(tabName + '-content');
    if (targetContent) {
        targetContent.classList.remove('hidden');
    }
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    if (activeTab) {
        activeTab.classList.remove('border-transparent', 'text-gray-500');
        activeTab.classList.add('border-blue-500', 'text-blue-600');
    }
}

// Banner functions
window.openBannerModal = function() {
    const modal = document.getElementById('banner-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

window.closeBannerModal = function() {
    const modal = document.getElementById('banner-modal');
    if (modal) {
        modal.classList.add('hidden');
        const form = document.getElementById('banner-form');
        if (form) {
            form.reset();
        }
    }
}

window.editBanner = function(id) {
    // TODO: Implement edit functionality
    alert('Edit banner functionality will be implemented');
}

window.deleteBanner = function(id) {
    if (confirm('Are you sure you want to delete this banner?')) {
        fetch(`/admin/banners/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                location.reload(); // Refresh to show updated state
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting banner');
        });
    }
}

window.toggleBanner = function(id) {
    fetch(`/admin/banners/${id}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            location.reload(); // Refresh to show updated state
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error toggling banner');
    });
}

// Section functions
window.openSectionModal = function() {
    const modal = document.getElementById('section-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

window.closeSectionModal = function() {
    const modal = document.getElementById('section-modal');
    if (modal) {
        modal.classList.add('hidden');
        const form = document.getElementById('section-form');
        if (form) {
            form.reset();
        }
    }
}

window.editSection = function(id) {
    // TODO: Implement edit functionality
    alert('Edit section functionality will be implemented');
}

window.deleteSection = function(id) {
    if (confirm('Are you sure you want to delete this section?')) {
        fetch(`/admin/sections/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                location.reload(); // Refresh to show updated state
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting section');
        });
    }
}

window.toggleSection = function(id) {
    fetch(`/admin/sections/${id}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            location.reload(); // Refresh to show updated state
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error toggling section');
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on the website management page
    if (document.getElementById('banner-modal') || document.getElementById('section-modal')) {
        // Form submissions
        const bannerForm = document.getElementById('banner-form');
        if (bannerForm) {
            bannerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(bannerForm);
                
                fetch('/admin/banners', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        closeBannerModal();
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error creating banner');
                });
            });
        }

        const sectionForm = document.getElementById('section-form');
        if (sectionForm) {
            sectionForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(sectionForm);
                
                fetch('/admin/sections', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        closeSectionModal();
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error creating section');
                });
            });
        }
    }
});
