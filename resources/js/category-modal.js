// Category Modal JavaScript
let isEditMode = false;
let currentCategoryId = null;

// Modal Controls
function openCategoryModal(categoryId = null) {
        isEditMode = categoryId !== null;
        currentCategoryId = categoryId;
        
        if (isEditMode) {
            loadCategoryData(categoryId);
            document.getElementById('category-modal-title-text').textContent = 'Edit Category';
            document.getElementById('category_save_text').textContent = 'Update Category';
        } else {
            resetForm();
            document.getElementById('category-modal-title-text').textContent = 'Add Category';
            document.getElementById('category_save_text').textContent = 'Save Category';
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
                document.getElementById('category_id').value = category.id;
                document.getElementById('category_name').value = category.name || '';
                document.getElementById('category_description').value = category.description || '';
                document.getElementById('parent_category').value = category.parent_id || '';
                document.getElementById('sort_order').value = category.sort_order || 0;
                document.getElementById('category_is_active').checked = category.is_active;
                
                // Load existing image if available
                if (category.image) {
                    const preview = document.getElementById('category_image_preview');
                    const previewImg = document.getElementById('category_preview_img');
                    previewImg.src = category.image_url;
                    preview.classList.remove('hidden');
                }
            }
        })
        .catch(error => {
            console.error('Error loading category:', error);
            showNotification('Error loading category data', 'error');
        });
    }

    // Reset Form
    function resetForm() {
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

    // Make resetForm globally accessible
    window.resetForm = resetForm;

    // Notification System
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg max-w-sm w-full ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        } text-white`;
        
        notification.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button type="button" class="inline-flex text-white hover:text-gray-200" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

});

// Make functions globally available
window.openCategoryModal = openCategoryModal;
window.closeCategoryModal = closeCategoryModal;
