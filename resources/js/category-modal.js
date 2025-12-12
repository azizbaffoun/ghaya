// Category Modal JavaScript
let isEditMode = false;
let currentCategoryId = null;

// Modal Controls
function openCategoryModal(categoryId = null) {
        isEditMode = categoryId !== null;
        currentCategoryId = categoryId;
        
        const categoryModal = document.getElementById('categoryModal');
        if (!categoryModal) return;
        
        if (isEditMode) {
            loadCategoryData(categoryId);
            const titleText = document.getElementById('category-modal-title-text');
            const saveText = document.getElementById('category_save_text');
            if (titleText) titleText.textContent = 'Edit Category';
            if (saveText) saveText.textContent = 'Update Category';
        } else {
            resetForm();
            const titleText = document.getElementById('category-modal-title-text');
            const saveText = document.getElementById('category_save_text');
            if (titleText) titleText.textContent = 'Add Category';
            if (saveText) saveText.textContent = 'Save Category';
        }
        
        categoryModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

function closeCategoryModal() {
    const categoryModal = document.getElementById('categoryModal');
    categoryModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    resetForm();
}

// Delete Modal Variables
let deleteCategoryId = null;
let deleteCategoryCard = null;

// Delete Modal Functions
function openDeleteCategoryModal(categoryId, categoryName, productsCount, childrenCount) {
    deleteCategoryId = categoryId;
    
    // Find the category card element using data attribute
    deleteCategoryCard = document.querySelector(`[data-category-id="${categoryId}"]`);
    
    const deleteModal = document.getElementById('deleteCategoryModal');
    const deleteMessage = document.getElementById('delete-modal-message');
    const warningInfo = document.getElementById('delete-warning-info');
    const warningText = document.getElementById('delete-warning-text');
    
    if (!deleteModal || !deleteMessage) return;
    
    // Set message
    deleteMessage.textContent = `Are you sure you want to delete "${categoryName}"? This action cannot be undone.`;
    
    // Show warnings if category has products or children
    if (warningInfo && warningText) {
        if (productsCount > 0 || childrenCount > 0) {
            warningInfo.classList.remove('hidden');
            let warningMessages = [];
            if (productsCount > 0) {
                warningMessages.push(`This category has ${productsCount} ${productsCount === 1 ? 'product' : 'products'}.`);
            }
            if (childrenCount > 0) {
                warningMessages.push(`This category has ${childrenCount} ${childrenCount === 1 ? 'subcategory' : 'subcategories'}.`);
            }
            warningText.textContent = warningMessages.join(' ') + ' You must remove them first before deleting this category.';
        } else {
            warningInfo.classList.add('hidden');
        }
    }
    
    deleteModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeDeleteCategoryModal() {
    const deleteModal = document.getElementById('deleteCategoryModal');
    if (deleteModal) {
        deleteModal.classList.add('hidden');
    }
    document.body.classList.remove('overflow-hidden');
    deleteCategoryId = null;
    deleteCategoryCard = null;
    
    // Reset button state
    const deleteBtn = document.getElementById('confirmDeleteCategory');
    const deleteSpinner = document.getElementById('delete-loading-spinner');
    const deleteText = document.getElementById('delete-button-text');
    if (deleteBtn) {
        deleteBtn.disabled = false;
    }
    if (deleteSpinner) {
        deleteSpinner.classList.add('hidden');
    }
    if (deleteText) {
        deleteText.textContent = 'Delete';
    }
}

// Load Category Data for Editing
function loadCategoryData(categoryId) {
    fetch(`/admin/categories/${categoryId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const category = data.category;
            const categoryIdEl = document.getElementById('category_id');
            const categoryNameEl = document.getElementById('category_name');
            const categoryDescEl = document.getElementById('category_description');
            const parentCategoryEl = document.getElementById('parent_category');
            const sortOrderEl = document.getElementById('sort_order');
            const isActiveEl = document.getElementById('category_is_active');
            
            if (categoryIdEl) categoryIdEl.value = category.id;
            if (categoryNameEl) categoryNameEl.value = category.name || '';
            if (categoryDescEl) categoryDescEl.value = category.description || '';
            if (parentCategoryEl) parentCategoryEl.value = category.parent_id || '';
            if (sortOrderEl) sortOrderEl.value = category.sort_order || 0;
            if (isActiveEl) isActiveEl.checked = category.is_active;
            
            // Load existing image if available
            if (category.image) {
                const preview = document.getElementById('category_image_preview');
                const previewImg = document.getElementById('category_preview_img');
                if (preview && previewImg) {
                    previewImg.src = category.image_url;
                    preview.classList.remove('hidden');
                }
            }
        }
    })
    .catch(error => {
        console.error('Error loading category:', error);
        if (typeof showNotification === 'function') {
            showNotification('Error loading category data', 'error');
        }
    });
}

// Reset Form
function resetForm() {
    const categoryForm = document.getElementById('categoryForm');
    if (categoryForm) {
        categoryForm.reset();
    }
    const imagePreview = document.getElementById('category_image_preview');
    if (imagePreview) {
        imagePreview.classList.add('hidden');
    }
    const categoryId = document.getElementById('category_id');
    if (categoryId) {
        categoryId.value = '';
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const categoryModal = document.getElementById('categoryModal');
    const categoryForm = document.getElementById('categoryForm');

    // Event Listeners with null checks
    const closeCategoryModalBtn = document.getElementById('closeCategoryModal');
    if (closeCategoryModalBtn) {
        closeCategoryModalBtn.addEventListener('click', closeCategoryModal);
    }
    
    const cancelCategoryBtn = document.getElementById('cancelCategory');
    if (cancelCategoryBtn) {
        cancelCategoryBtn.addEventListener('click', closeCategoryModal);
    }

    // Close modal when clicking outside
    if (categoryModal) {
        categoryModal.addEventListener('click', function(e) {
            if (e.target === categoryModal) {
                closeCategoryModal();
            }
        });
    }

    // Image Preview
    const categoryImageInput = document.getElementById('category_image');
    if (categoryImageInput) {
        categoryImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('category_image_preview');
            const previewImg = document.getElementById('category_preview_img');
            
            if (file && preview && previewImg) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else if (preview) {
                preview.classList.add('hidden');
            }
        });
    }

    // Form Submission
    if (categoryForm) {
        categoryForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        const submitBtn = document.getElementById('saveCategory');
        const loadingSpinner = document.getElementById('category_loading_spinner');
        const saveText = document.getElementById('category_save_text');
        
        // Show loading state
        submitBtn.disabled = true;
        loadingSpinner.classList.remove('hidden');
        saveText.textContent = isEditMode ? 'Updating...' : 'Saving...';
        
        // Add form data
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('name', document.getElementById('category_name').value);
        formData.append('description', document.getElementById('category_description').value);
        formData.append('parent_id', document.getElementById('parent_category').value);
        formData.append('sort_order', document.getElementById('sort_order').value);
        formData.append('is_active', document.getElementById('category_is_active').checked ? '1' : '0');
        
        // Add image if selected
        const imageFile = document.getElementById('category_image').files[0];
        console.log('Category image file:', imageFile);
        if (imageFile) {
            console.log('Adding category image:', imageFile.name, imageFile.type, imageFile.size);
            formData.append('image', imageFile);
        }
        
        if (isEditMode) {
            formData.append('_method', 'PUT');
        }
        
        const url = isEditMode ? `/admin/categories/${currentCategoryId}` : '/admin/categories';
        
        console.log('Submitting category form to:', url);
        console.log('Category FormData contents:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Category Response status:', response.status);
            console.log('Category Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return response.text().then(text => {
                console.log('Category Raw response:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse JSON:', e);
                    throw new Error('Invalid JSON response');
                }
            });
        })
        .then(data => {
            console.log('Category Parsed response:', data);
            if (data.success) {
                // Show success message
                showNotification('Category saved successfully!', 'success');
                closeCategoryModal();
                // Update the table dynamically instead of reloading
                // location.reload();
            } else {
                showNotification(data.message || 'Error saving category', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error saving category: ' + error.message, 'error');
        })
        .finally(() => {
            // Reset loading state
            submitBtn.disabled = false;
            loadingSpinner.classList.add('hidden');
            saveText.textContent = isEditMode ? 'Update Category' : 'Save Category';
        });
        });
    }


    // Notification System
    function showNotification(message, type = 'info') {
        // Remove existing notifications first
        const existingNotifications = document.querySelectorAll('.notification-toast');
        existingNotifications.forEach(n => n.remove());
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification-toast fixed top-4 right-4 z-50 p-4 rounded-lg shadow-xl max-w-sm w-full transform transition-all duration-300 ${
            type === 'success' ? 'bg-gradient-to-r from-green-500 to-green-600' : 
            type === 'error' ? 'bg-gradient-to-r from-red-500 to-red-600' : 'bg-gradient-to-r from-blue-500 to-blue-600'
        } text-white`;
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        
        // Icon based on type
        let iconSvg = '';
        if (type === 'success') {
            iconSvg = `<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />`;
        } else if (type === 'error') {
            iconSvg = `<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />`;
        } else {
            iconSvg = `<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />`;
        }
        
        notification.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        ${iconSvg}
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-semibold">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button type="button" class="inline-flex text-white hover:text-gray-200 focus:outline-none transition-colors" onclick="this.closest('.notification-toast').remove()">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Auto remove after 5 seconds with fade out
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
    }


    function deleteCategory() {
        if (!deleteCategoryId) return;
        
        const deleteBtn = document.getElementById('confirmDeleteCategory');
        const deleteSpinner = document.getElementById('delete-loading-spinner');
        const deleteText = document.getElementById('delete-button-text');
        
        // Show loading state
        deleteBtn.disabled = true;
        deleteSpinner.classList.remove('hidden');
        deleteText.textContent = 'Deleting...';
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/admin/categories/${deleteCategoryId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Failed to delete category');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success notification
                showNotification(data.message || 'Category deleted successfully!', 'success');
                
                // Remove category card from DOM with animation
                if (deleteCategoryCard) {
                    deleteCategoryCard.style.transition = 'all 0.3s ease-out';
                    deleteCategoryCard.style.opacity = '0';
                    deleteCategoryCard.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        deleteCategoryCard.remove();
                        
                        // Check if there are no more categories (excluding the "Add Category" button)
                        const remainingCards = document.querySelectorAll('[data-category-id]');
                        if (remainingCards.length === 0) {
                            // Reload page to show empty state
                            setTimeout(() => {
                                location.reload();
                            }, 300);
                        }
                    }, 300);
                } else {
                    // Fallback: reload page
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }
                
                // Close modal
                closeDeleteCategoryModal();
            } else {
                throw new Error(data.message || 'Failed to delete category');
            }
        })
        .catch(error => {
            console.error('Error deleting category:', error);
            showNotification(error.message || 'Error deleting category. Please try again.', 'error');
            
            // Reset button state
            deleteBtn.disabled = false;
            deleteSpinner.classList.add('hidden');
            deleteText.textContent = 'Delete';
        });
    }

    // Delete Modal Event Listeners
    const deleteModal = document.getElementById('deleteCategoryModal');
    if (deleteModal) {
        const closeDeleteBtn = document.getElementById('closeDeleteModal');
        const cancelDeleteBtn = document.getElementById('cancelDeleteCategory');
        const confirmDeleteBtn = document.getElementById('confirmDeleteCategory');
        
        if (closeDeleteBtn) {
            closeDeleteBtn.addEventListener('click', closeDeleteCategoryModal);
        }
        
        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', closeDeleteCategoryModal);
        }
        
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', deleteCategory);
        }
        
        // Close modal when clicking outside
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                closeDeleteCategoryModal();
            }
        });
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
                closeDeleteCategoryModal();
            }
        });
    }

});


// Make functions globally available
window.openCategoryModal = openCategoryModal;
window.closeCategoryModal = closeCategoryModal;
window.openDeleteCategoryModal = openDeleteCategoryModal;
window.closeDeleteCategoryModal = closeDeleteCategoryModal;
