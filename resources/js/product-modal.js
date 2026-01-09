// Product Modal JavaScript
let selectedColors = [];
let selectedImages = []; // New files to upload
let existingImages = []; // Existing images from database
let imagesToDelete = []; // IDs of images to delete
let colorImages = {}; // New: Store images for each color
let isEditMode = false;
let currentProductId = null;

// Reset Form - Define as global function that's safe to call
function resetForm() {
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.reset();
    }
    selectedColors = [];
    selectedImages = [];
    existingImages = [];
    imagesToDelete = [];
    colorImages = {};
    
    const colorsList = document.getElementById('colors_list');
    if (colorsList) {
        colorsList.innerHTML = '';
    }
    
    const imagePreview = document.getElementById('image_preview');
    if (imagePreview) {
        imagePreview.classList.add('hidden');
        imagePreview.innerHTML = '';
    }
    
    const discountField = document.getElementById('discount_field');
    if (discountField) {
        discountField.classList.add('hidden');
    }
    
    const productId = document.getElementById('product_id');
    if (productId) {
        productId.value = '';
    }
    
    // Update sizes if the function is available (increments by 2)
    console.log('[resetForm] Updating sizes in resetForm');
    const sizeFrom = document.getElementById('size_from');
    const sizeTo = document.getElementById('size_to');
    const sizesDisplay = document.getElementById('sizes_display');
    if (sizeFrom && sizeTo && sizesDisplay) {
        const from = parseInt(sizeFrom.value) || 36;
        const to = parseInt(sizeTo.value) || 42;
        console.log('[resetForm] Size range:', from, 'to', to, 'increment: 2');
        sizesDisplay.innerHTML = '';
        const generatedSizes = [];
        for (let i = from; i <= to; i += 2) {
            generatedSizes.push(i);
            const sizeChip = document.createElement('span');
            sizeChip.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
            sizeChip.textContent = i;
            sizesDisplay.appendChild(sizeChip);
        }
        console.log('[resetForm] Generated sizes:', generatedSizes);
    } else {
        console.log('[resetForm] Size elements not found:', { sizeFrom, sizeTo, sizesDisplay });
    }
}

// Make resetForm globally available immediately
window.resetForm = resetForm;

// Modal Controls
function openProductModal(productId = null) {
    console.log('[openProductModal] Called with productId:', productId);
    isEditMode = productId !== null;
    currentProductId = productId;
    
    if (isEditMode) {
        console.log('[openProductModal] Edit mode - loading product data');
        loadProductData(productId);
        const modalTitleText = document.getElementById('modal-title-text');
        const saveText = document.getElementById('save_text');
        if (modalTitleText) modalTitleText.textContent = 'Edit Product';
        if (saveText) saveText.textContent = 'Update Product';
    } else {
        console.log('[openProductModal] Add mode - resetting form');
        resetForm();
        const modalTitleText = document.getElementById('modal-title-text');
        const saveText = document.getElementById('save_text');
        if (modalTitleText) modalTitleText.textContent = 'Add Product';
        if (saveText) saveText.textContent = 'Save Product';
    }
    
    const productModal = document.getElementById('productModal');
    if (productModal) {
        productModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        // Update sizes display when modal opens
        console.log('[openProductModal] Scheduling updateSizes in 100ms');
        setTimeout(() => {
            console.log('[openProductModal] Calling updateSizes from timeout');
            updateSizes();
        }, 100);
    }
}

// Make openProductModal globally available immediately
window.openProductModal = openProductModal;

// Load Product Data for Editing
function loadProductData(productId) {
    fetch(`/admin/products/${productId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const product = data.product;
            document.getElementById('product_id').value = product.id;
            document.getElementById('name').value = product.name || '';
            document.getElementById('sku').value = product.sku || '';
            document.getElementById('category_id').value = product.category_id || '';
            document.getElementById('description').value = product.description || '';
            document.getElementById('price').value = product.price || '';
            document.getElementById('compare_price').value = product.compare_price || '';
            document.getElementById('stock_status').value = product.stock_status || 'in_stock';
            document.getElementById('is_active').checked = product.is_active;
            document.getElementById('has_discount').checked = !!product.compare_price;
            
            if (product.compare_price) {
                document.getElementById('discount_field').classList.remove('hidden');
            }
            
            // Load colors
            if (product.colors && Array.isArray(product.colors)) {
                selectedColors = product.colors;
                renderColors();
            }
            
            // Load existing general images (non-color-specific)
            if (product.images && Array.isArray(product.images)) {
                existingImages = product.images.filter(img => !img.color || img.color === null);
                renderExistingImages();
            }
            
            // Load color-specific images if available
            if (product.color_images) {
                colorImages = product.color_images;
                // Re-render colors to show existing images
                renderColors();
            }
            
            // Load sizes
            if (product.sizes && Array.isArray(product.sizes)) {
                const sizeArray = product.sizes.map(s => parseInt(s)).sort((a, b) => a - b);
                if (sizeArray.length > 0) {
                    document.getElementById('size_from').value = sizeArray[0];
                    document.getElementById('size_to').value = sizeArray[sizeArray.length - 1];
                    updateSizes();
                }
            }
        }
    })
    .catch(error => {
        console.error('Error loading product:', error);
        showNotification('Error loading product data', 'error');
    });
}

// Render Colors Function
function renderColors() {
    const colorsList = document.getElementById('colors_list');
    if (!colorsList) return;
    
    colorsList.innerHTML = '';
    
    selectedColors.forEach((color, index) => {
        const colorContainer = document.createElement('div');
        colorContainer.className = 'border border-gray-200 rounded-lg p-4 mb-3 bg-white';
        colorContainer.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 rounded-full border border-gray-300" style="background-color: ${color}"></div>
                    <span class="text-sm font-medium text-gray-700">${color}</span>
                </div>
                <button type="button" class="text-red-500 hover:text-red-700" onclick="removeColor(${index})">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-medium text-gray-600">Images for ${color}:</label>
                <input type="file" 
                       id="color_images_${index}" 
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                       multiple 
                       accept="image/*"
                       onchange="handleColorImageChange(${index}, this.files)">
                <div id="color_images_preview_${index}" class="grid grid-cols-2 gap-2 mt-2"></div>
            </div>
        `;
        colorsList.appendChild(colorContainer);
        
        // Show existing images for this color if any
        if (colorImages[color] && colorImages[color].length > 0) {
            renderColorImagesPreview(index, color, colorImages[color]);
        }
    });
}

// Update Sizes Function (increments by 2)
function updateSizes() {
    console.log('[updateSizes GLOBAL] Function called - Stack trace:', new Error().stack);
    const from = parseInt(document.getElementById('size_from').value) || 36;
    const to = parseInt(document.getElementById('size_to').value) || 42;
    const sizesDisplay = document.getElementById('sizes_display');
    
    console.log('[updateSizes GLOBAL] Size range:', from, 'to', to, 'increment: 2');
    
    if (!sizesDisplay) {
        console.log('[updateSizes GLOBAL] sizesDisplay element not found!');
        return;
    }
    
    sizesDisplay.innerHTML = '';
    const generatedSizes = [];
    
    for (let i = from; i <= to; i += 2) {
        generatedSizes.push(i);
        const sizeChip = document.createElement('span');
        sizeChip.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
        sizeChip.textContent = i;
        sizesDisplay.appendChild(sizeChip);
    }
    
    console.log('[updateSizes GLOBAL] Generated sizes:', generatedSizes);
    console.log('[updateSizes GLOBAL] Actual HTML content:', sizesDisplay.innerHTML.substring(0, 200));
}

// Render Color Images Preview Function
function renderColorImagesPreview(colorIndex, color, images) {
    const preview = document.getElementById(`color_images_preview_${colorIndex}`);
    if (!preview) return;
    
    preview.innerHTML = '';
    
    if (images && images.length > 0) {
        images.forEach((image, fileIndex) => {
            const imageDiv = document.createElement('div');
            imageDiv.className = 'relative group';
            
            if (image instanceof File) {
                // New file upload
                const reader = new FileReader();
                reader.onload = function(e) {
                    imageDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${fileIndex + 1}" style="width: 100%; height: 80px; object-fit: contain; border: 2px solid #e5e7eb; border-radius: 8px; background: white;" onload="console.log('Color image ${fileIndex} loaded successfully')" onerror="console.error('Color image ${fileIndex} failed to load')">
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0); transition: all 0.2s; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <button type="button" style="opacity: 0; background: #ef4444; color: white; border: none; border-radius: 50%; padding: 4px; cursor: pointer;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'" onclick="removeColorImage('${color}', ${fileIndex})">
                                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    `;
                };
                reader.readAsDataURL(image);
            } else {
                // Existing image URL
                imageDiv.innerHTML = `
                    <img src="${image}" alt="Preview ${fileIndex + 1}" style="width: 100%; height: 80px; object-fit: contain; border: 2px solid #e5e7eb; border-radius: 8px; background: white;" onload="console.log('Existing color image ${fileIndex} loaded successfully')" onerror="console.error('Existing color image ${fileIndex} failed to load')">
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0); transition: all 0.2s; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <button type="button" style="opacity: 0; background: #ef4444; color: white; border: none; border-radius: 50%; padding: 4px; cursor: pointer;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'" onclick="removeColorImage('${color}', ${fileIndex})">
                            <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                `;
            }
            preview.appendChild(imageDiv);
        });
    }
}

// Global Notification System
function showNotification(message, type = 'info') {
    // Remove existing notifications first
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(n => {
        n.style.transform = 'translateX(100%)';
        n.style.opacity = '0';
        setTimeout(() => n.remove(), 300);
    });
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification-toast fixed top-4 right-4 z-[9999] p-4 rounded-lg shadow-2xl max-w-sm w-full transform transition-all duration-300';
    
    // Set colors based on type
    const colors = {
        success: 'bg-gradient-to-r from-green-500 to-green-600',
        error: 'bg-gradient-to-r from-red-500 to-red-600',
        warning: 'bg-gradient-to-r from-yellow-500 to-yellow-600',
        info: 'bg-gradient-to-r from-blue-500 to-blue-600'
    };
    
    notification.className += ` ${colors[type] || colors.info} text-white`;
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(100%)';
    
    // Icon based on type
    let iconSvg = '';
    if (type === 'success') {
        iconSvg = `<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />`;
    } else if (type === 'error') {
        iconSvg = `<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />`;
    } else if (type === 'warning') {
        iconSvg = `<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />`;
    } else {
        iconSvg = `<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />`;
    }
    
    notification.innerHTML = `
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                    ${iconSvg}
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium">${message}</p>
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
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

// Delete Product Modal Functions
let deleteProductId = null;

function openDeleteProductModal(productId, productName) {
    deleteProductId = productId;
    
    const deleteModal = document.getElementById('deleteProductModal');
    const deleteMessage = document.getElementById('delete-product-modal-message');
    
    if (!deleteModal || !deleteMessage) return;
    
    // Set message
    deleteMessage.textContent = `Are you sure you want to delete "${productName}"? This action cannot be undone.`;
    
    deleteModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeDeleteProductModal() {
    const deleteModal = document.getElementById('deleteProductModal');
    if (deleteModal) {
        deleteModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    deleteProductId = null;
}
window.openDeleteProductModal = openDeleteProductModal;
window.closeDeleteProductModal = closeDeleteProductModal;

function closeProductModal() {
    const productModal = document.getElementById('productModal');
    if (productModal) {
        productModal.classList.add('hidden');
    }
    document.body.classList.remove('overflow-hidden');
    resetForm();
}
window.closeProductModal = closeProductModal;

function openCategoryModal() {
    const categoryModal = document.getElementById('quickCategoryModal');
    categoryModal.classList.remove('hidden');
}
window.openCategoryModal = openCategoryModal;

function closeCategoryModal() {
    const categoryModal = document.getElementById('quickCategoryModal');
    const quickCategoryForm = document.getElementById('quickCategoryForm');
    categoryModal.classList.add('hidden');
    quickCategoryForm.reset();
}
window.closeCategoryModal = closeCategoryModal;

// Initialize when DOM is loaded
console.log('Product modal JavaScript loaded!');
document.addEventListener('DOMContentLoaded', function() {
    const productModal = document.getElementById('productModal');
    const categoryModal = document.getElementById('quickCategoryModal');
    const productForm = document.getElementById('productForm');
    const quickCategoryForm = document.getElementById('quickCategoryForm');

    // Event Listeners with null checks
    const closeModal = document.getElementById('closeModal');
    if (closeModal) {
        closeModal.addEventListener('click', closeProductModal);
    }
    
    const cancelProduct = document.getElementById('cancelProduct');
    if (cancelProduct) {
        cancelProduct.addEventListener('click', closeProductModal);
    }
    
    const closeCategoryModal = document.getElementById('closeCategoryModal');
    if (closeCategoryModal) {
        closeCategoryModal.addEventListener('click', closeCategoryModal);
    }
    
    const cancelCategory = document.getElementById('cancelCategory');
    if (cancelCategory) {
        cancelCategory.addEventListener('click', closeCategoryModal);
    }
    
    const addCategoryBtn = document.getElementById('addCategoryBtn');
    if (addCategoryBtn) {
        addCategoryBtn.addEventListener('click', function() {
            const quickForm = document.getElementById('quickCategoryForm');
            if (quickForm) {
                if (quickForm.classList.contains('hidden')) {
                    quickForm.classList.remove('hidden');
                } else {
                    quickForm.classList.add('hidden');
                }
            }
        });
    }

    // Close modals when clicking outside
    if (productModal) {
        productModal.addEventListener('click', function(e) {
            if (e.target === productModal) {
                closeProductModal();
            }
        });
    }

    if (categoryModal) {
        categoryModal.addEventListener('click', function(e) {
            if (e.target === categoryModal) {
                closeCategoryModal();
            }
        });
    }

    // Discount Toggle
    const hasDiscount = document.getElementById('has_discount');
    if (hasDiscount) {
        hasDiscount.addEventListener('change', function() {
            const discountField = document.getElementById('discount_field');
            if (discountField) {
                if (this.checked) {
                    discountField.classList.remove('hidden');
                } else {
                    discountField.classList.add('hidden');
                    const comparePrice = document.getElementById('compare_price');
                    if (comparePrice) {
                        comparePrice.value = '';
                    }
                }
            }
        });
    }

    // Color Management
    const addColor = document.getElementById('add_color');
    const colorPicker = document.getElementById('color_picker');
    
    // Track the last selected color value
    let lastSelectedColor = '#FFFFFF';
    
    if (colorPicker) {
        // Listen for color changes to track the actual selected color
        colorPicker.addEventListener('input', function(e) {
            lastSelectedColor = e.target.value;
            console.log('Color picker changed to:', lastSelectedColor);
        });
        
        // Also listen for change event
        colorPicker.addEventListener('change', function(e) {
            lastSelectedColor = e.target.value;
            console.log('Color picker changed (change event) to:', lastSelectedColor);
        });
    }
    
    if (addColor) {
        addColor.addEventListener('click', function(e) {
            e.preventDefault();
            if (colorPicker) {
                // Use the tracked color value instead of reading directly
                // This ensures we get the color the user actually selected
                let color = lastSelectedColor || colorPicker.value;
                
                console.log('=== ADD COLOR CLICKED ===');
                console.log('Color picker current value:', colorPicker.value);
                console.log('Last selected color:', lastSelectedColor);
                console.log('Using color:', color);
                
                // Ensure color is in uppercase format (#RRGGBB)
                if (color && color.startsWith('#')) {
                    color = color.toUpperCase();
                } else if (color) {
                    color = '#' + color.toUpperCase();
                }
                
                console.log('Processed color:', color);
                console.log('Current selectedColors before add:', selectedColors);
                
                // Only add if color is valid and not already in the list
                if (color && color.length >= 4 && !selectedColors.includes(color)) {
                    selectedColors.push(color);
                    console.log('✅ Added color to selectedColors:', color);
                    console.log('Updated selectedColors:', selectedColors);
                    renderColors();
                    // Reset color picker to white after adding (not black)
                    colorPicker.value = '#FFFFFF';
                    lastSelectedColor = '#FFFFFF';
                } else if (selectedColors.includes(color)) {
                    alert('This color has already been added.');
                } else {
                    console.error('❌ Invalid color value:', color, 'Length:', color ? color.length : 0);
                    alert('Please select a valid color first.');
                }
            } else {
                console.error('Color picker element not found!');
            }
        });
    }


    window.removeColor = function(index) {
        const color = selectedColors[index];
        selectedColors.splice(index, 1);
        // Remove images for this color
        delete colorImages[color];
        renderColors();
    };

    // Handle color image changes
    window.handleColorImageChange = function(colorIndex, files) {
        const color = selectedColors[colorIndex];
        if (!color) return;
        
        const fileArray = Array.from(files);
        colorImages[color] = fileArray;
        
        // Update preview
        renderColorImagesPreview(colorIndex, color, fileArray);
    };

    // Render color images preview
    function renderColorImagesPreview(colorIndex, color, images) {
        const preview = document.getElementById(`color_images_preview_${colorIndex}`);
        if (!preview) return;
        
        preview.innerHTML = '';
        
        if (images && images.length > 0) {
            images.forEach((image, fileIndex) => {
                const imageDiv = document.createElement('div');
                imageDiv.className = 'relative group';
                
                if (image instanceof File) {
                    // New file upload
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imageDiv.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${fileIndex + 1}" style="width: 100%; height: 80px; object-fit: contain; border: 2px solid #e5e7eb; border-radius: 8px; background: white;" onload="console.log('Color image ${fileIndex} loaded successfully')" onerror="console.error('Color image ${fileIndex} failed to load')">
                            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0); transition: all 0.2s; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <button type="button" style="opacity: 0; background: #ef4444; color: white; border: none; border-radius: 50%; padding: 4px; cursor: pointer;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'" onclick="removeColorImage('${color}', ${fileIndex})">
                                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        `;
                    };
                    reader.readAsDataURL(image);
                } else {
                    // Existing image URL
                    imageDiv.innerHTML = `
                        <img src="${image}" alt="Preview ${fileIndex + 1}" style="width: 100%; height: 80px; object-fit: contain; border: 2px solid #e5e7eb; border-radius: 8px; background: white;" onload="console.log('Existing color image ${fileIndex} loaded successfully')" onerror="console.error('Existing color image ${fileIndex} failed to load')">
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0); transition: all 0.2s; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <button type="button" style="opacity: 0; background: #ef4444; color: white; border: none; border-radius: 50%; padding: 4px; cursor: pointer;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'" onclick="removeColorImage('${color}', ${fileIndex})">
                                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    `;
                }
                preview.appendChild(imageDiv);
            });
        }
    }

    // Remove specific image from a color
    window.removeColorImage = function(color, imageIndex) {
        if (colorImages[color]) {
            colorImages[color].splice(imageIndex, 1);
            // Re-render the color to update preview
            const colorIndex = selectedColors.indexOf(color);
            if (colorIndex !== -1) {
                renderColorImagesPreview(colorIndex, color, colorImages[color]);
            }
        }
    };

    // Size Range Management (increments by 2)
    function updateSizes() {
        console.log('[updateSizes LOCAL] Function called from DOMContentLoaded');
        const from = parseInt(document.getElementById('size_from').value) || 36;
        const to = parseInt(document.getElementById('size_to').value) || 42;
        const sizesDisplay = document.getElementById('sizes_display');
        
        console.log('[updateSizes LOCAL] Size range:', from, 'to', to, 'increment: 2');
        
        if (!sizesDisplay) {
            console.log('[updateSizes LOCAL] sizesDisplay element not found!');
            return;
        }
        
        sizesDisplay.innerHTML = '';
        const generatedSizes = [];
        
        for (let i = from; i <= to; i += 2) {
            generatedSizes.push(i);
            const sizeChip = document.createElement('span');
            sizeChip.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
            sizeChip.textContent = i;
            sizesDisplay.appendChild(sizeChip);
        }
        
        console.log('[updateSizes LOCAL] Generated sizes:', generatedSizes);
        console.log('[updateSizes LOCAL] Actual HTML content:', sizesDisplay.innerHTML.substring(0, 200));
    }

    const sizeFrom = document.getElementById('size_from');
    if (sizeFrom) {
        console.log('[DOMContentLoaded] Adding input listener to size_from');
        sizeFrom.addEventListener('input', function() {
            console.log('[size_from input] Event triggered, calling updateSizes');
            updateSizes();
        });
    } else {
        console.log('[DOMContentLoaded] size_from element not found!');
    }
    
    const sizeTo = document.getElementById('size_to');
    if (sizeTo) {
        console.log('[DOMContentLoaded] Adding input listener to size_to');
        sizeTo.addEventListener('input', function() {
            console.log('[size_to input] Event triggered, calling updateSizes');
            updateSizes();
        });
    } else {
        console.log('[DOMContentLoaded] size_to element not found!');
    }

    // Image Preview
    const imagesInput = document.getElementById('images');
    console.log('Images input element:', imagesInput);
    if (imagesInput) {
        imagesInput.addEventListener('change', function(e) {
            console.log('Image input changed!');
            const files = Array.from(e.target.files);
            console.log('Selected files:', files);
            const preview = document.getElementById('image_preview');
            console.log('Preview element:', preview);
            
            if (preview) {
                selectedImages = files;
                // Re-render to show both existing and new images
                renderExistingImages();
            }
        });
    }

    // Render existing images from database
    function renderExistingImages() {
        const preview = document.getElementById('image_preview');
        if (!preview) return;
        
        preview.innerHTML = '';
        
        // Show existing images
        if (existingImages.length > 0) {
            preview.classList.remove('hidden');
            existingImages.forEach((image, index) => {
                const imageDiv = document.createElement('div');
                imageDiv.className = 'relative group';
                imageDiv.innerHTML = `
                    <img src="${image.url}" alt="${image.alt_text || 'Product image ' + (index + 1)}" style="width: 100%; height: 96px; object-fit: contain; border: 2px solid #e5e7eb; border-radius: 8px; background: white;">
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0); transition: all 0.2s; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <button type="button" style="opacity: 0; background: #ef4444; color: white; border: none; border-radius: 50%; padding: 6px; cursor: pointer;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'" onclick="deleteExistingImage(${image.id}, ${index})" title="Delete image">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    ${image.is_primary ? '<span style="position: absolute; top: 4px; left: 4px; background: #10b981; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold;">PRIMARY</span>' : ''}
                `;
                preview.appendChild(imageDiv);
            });
        }
        
        // Also show newly selected images
        if (selectedImages.length > 0) {
            preview.classList.remove('hidden');
            selectedImages.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'relative group';
                    imageDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}" style="width: 100%; height: 96px; object-fit: contain; border: 2px solid #e5e7eb; border-radius: 8px; background: white;">
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0); transition: all 0.2s; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <button type="button" style="opacity: 0; background: #ef4444; color: white; border: none; border-radius: 50%; padding: 4px; cursor: pointer;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'" onclick="removeImage(${index})">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    `;
                    preview.appendChild(imageDiv);
                };
                reader.readAsDataURL(file);
            });
        }
        
        if (existingImages.length === 0 && selectedImages.length === 0) {
            preview.classList.add('hidden');
        }
    }

    // Delete existing image
    window.deleteExistingImage = function(imageId, index) {
        if (confirm('Are you sure you want to delete this image?')) {
            // Mark image for deletion
            imagesToDelete.push(imageId);
            // Remove from existing images array
            existingImages.splice(index, 1);
            // Re-render
            renderExistingImages();
        }
    };

    window.removeImage = function(index) {
        selectedImages.splice(index, 1);
        // Re-render preview
        renderExistingImages();
    };

    // Form Submission
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            const submitBtn = document.getElementById('saveProduct');
            const loadingSpinner = document.getElementById('loading_spinner');
            const saveText = document.getElementById('save_text');
            
            // Show loading state
            if (submitBtn) submitBtn.disabled = true;
            if (loadingSpinner) loadingSpinner.classList.remove('hidden');
            if (saveText) saveText.textContent = isEditMode ? 'Updating...' : 'Saving...';
        
            // Add form data
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            console.log('CSRF Token element:', csrfToken);
            if (csrfToken) {
                console.log('CSRF Token value:', csrfToken.getAttribute('content'));
                formData.append('_token', csrfToken.getAttribute('content'));
            } else {
                console.error('CSRF token not found!');
            }
            
            const nameEl = document.getElementById('name');
            const skuEl = document.getElementById('sku');
            const categoryIdEl = document.getElementById('category_id');
            const descriptionEl = document.getElementById('description');
            const priceEl = document.getElementById('price');
            const comparePriceEl = document.getElementById('compare_price');
            const stockStatusEl = document.getElementById('stock_status');
            const isActiveEl = document.getElementById('is_active');
            const hasDiscountEl = document.getElementById('has_discount');
            const sizeFromEl = document.getElementById('size_from');
            const sizeToEl = document.getElementById('size_to');
            
            if (nameEl) formData.append('name', nameEl.value);
            if (skuEl) formData.append('sku', skuEl.value);
            if (categoryIdEl) formData.append('category_id', categoryIdEl.value);
            if (descriptionEl) formData.append('description', descriptionEl.value);
            if (priceEl) formData.append('price', priceEl.value);
            if (comparePriceEl) formData.append('compare_price', comparePriceEl.value);
            if (stockStatusEl) formData.append('stock_status', stockStatusEl.value);
            if (isActiveEl) formData.append('is_active', isActiveEl.checked ? '1' : '0');
            if (hasDiscountEl) formData.append('has_discount', hasDiscountEl.checked ? '1' : '0');
            
            // Add colors as individual array elements
            console.log('=== FORM SUBMISSION ===');
            console.log('selectedColors to send:', selectedColors);
            console.log('selectedColors type:', typeof selectedColors);
            console.log('selectedColors is array:', Array.isArray(selectedColors));
            console.log('selectedColors length:', selectedColors.length);
            
            if (selectedColors && Array.isArray(selectedColors) && selectedColors.length > 0) {
                selectedColors.forEach((color, index) => {
                    console.log(`Appending color[${index}]:`, color, 'Type:', typeof color);
                    if (color && color.trim() !== '') {
                        formData.append(`colors[${index}]`, color);
                    }
                });
                console.log('✅ Total colors being sent:', selectedColors.length);
            } else {
                console.warn('⚠️ WARNING: selectedColors is empty or invalid!', selectedColors);
                console.warn('⚠️ Colors will NOT be sent in the request!');
            }
            if (sizeFromEl) formData.append('size_from', sizeFromEl.value);
            if (sizeToEl) formData.append('size_to', sizeToEl.value);
            
            // Add color-specific images
            Object.keys(colorImages).forEach(color => {
                if (colorImages[color] && colorImages[color].length > 0) {
                    colorImages[color].forEach((file, index) => {
                        formData.append(`color_images[${color}][${index}]`, file);
                    });
                }
            });
            
            // Add general images (if any)
            console.log('Selected images:', selectedImages);
            selectedImages.forEach((file, index) => {
                console.log(`Adding image ${index}:`, file.name, file.type, file.size);
                formData.append(`images[${index}]`, file);
            });
            
            // Add images to delete (if any)
            if (isEditMode && imagesToDelete.length > 0) {
                imagesToDelete.forEach((imageId, index) => {
                    formData.append(`delete_images[${index}]`, imageId);
                });
                console.log('Images to delete:', imagesToDelete);
            }
            
            if (isEditMode) {
                formData.append('_method', 'PUT');
            }
            
            const url = isEditMode ? `/admin/products/${currentProductId}` : '/admin/products';
            
            console.log('Submitting form to:', url);
            console.log('FormData contents:');
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
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.text().then(text => {
                    console.log('Raw response:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON:', e);
                        throw new Error('Invalid JSON response');
                    }
                });
            })
            .then(data => {
                console.log('Parsed response:', data);
                if (data.success) {
                    // Show success message
                    showNotification('Product saved successfully!', 'success');
                    closeProductModal();
                    // Reload page to show the new/updated product
                    window.location.reload();
                } else {
                    showNotification(data.message || 'Error saving product', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error saving product: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset loading state
                if (submitBtn) submitBtn.disabled = false;
                if (loadingSpinner) loadingSpinner.classList.add('hidden');
                if (saveText) saveText.textContent = isEditMode ? 'Update Product' : 'Save Product';
            });
        });
    }

    // Quick Category Creation
    const saveQuickCategory = document.getElementById('saveQuickCategory');
    if (saveQuickCategory) {
        saveQuickCategory.addEventListener('click', function() {
            const nameInput = document.getElementById('quick_category_name');
            const descriptionInput = document.getElementById('quick_category_description');
            
            if (!nameInput || !descriptionInput) return;
            
            const name = nameInput.value;
            const description = descriptionInput.value;
            
            if (!name.trim()) {
                showNotification('Category name is required', 'error');
                return;
            }
            
            const formData = new FormData();
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                formData.append('_token', csrfToken.getAttribute('content'));
            }
            formData.append('name', name);
            formData.append('description', description);
            
            fetch('/admin/categories', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new category to dropdown
                    const categorySelect = document.getElementById('category_id');
                    if (categorySelect) {
                        const newOption = document.createElement('option');
                        newOption.value = data.category.id;
                        newOption.textContent = data.category.name;
                        newOption.selected = true;
                        categorySelect.appendChild(newOption);
                    }
                    
                    // Hide quick form and clear fields
                    const quickForm = document.getElementById('quickCategoryForm');
                    if (quickForm) {
                        quickForm.classList.add('hidden');
                    }
                    nameInput.value = '';
                    descriptionInput.value = '';
                    
                    showNotification('Category created successfully!', 'success');
                } else {
                    showNotification(data.message || 'Error creating category', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error creating category', 'error');
            });
        });
    }
    
    const cancelQuickCategory = document.getElementById('cancelQuickCategory');
    if (cancelQuickCategory) {
        cancelQuickCategory.addEventListener('click', function() {
            const quickForm = document.getElementById('quickCategoryForm');
            const nameInput = document.getElementById('quick_category_name');
            const descriptionInput = document.getElementById('quick_category_description');
            
            if (quickForm) {
                quickForm.classList.add('hidden');
            }
            if (nameInput) {
                nameInput.value = '';
            }
            if (descriptionInput) {
                descriptionInput.value = '';
            }
        });
    }


    // Note: resetForm is already defined globally at the top of the file

    // Delete Product Modal Event Listeners
    const confirmDeleteProduct = document.getElementById('confirmDeleteProduct');
    if (confirmDeleteProduct) {
        confirmDeleteProduct.addEventListener('click', function() {
            if (!deleteProductId) return;
            
            const button = this;
            const buttonText = document.getElementById('delete-product-button-text');
            const loadingSpinner = document.getElementById('delete-product-loading-spinner');
            
            // Show loading state
            button.disabled = true;
            if (buttonText) buttonText.textContent = 'Deleting...';
            if (loadingSpinner) loadingSpinner.classList.remove('hidden');
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            // Delete product
            fetch(`/admin/products/${deleteProductId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Product deleted successfully', 'success');
                    closeDeleteProductModal();
                    // Reload page to update the list
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showNotification(data.message || 'Error deleting product', 'error');
                    // Reset button state
                    button.disabled = false;
                    if (buttonText) buttonText.textContent = 'Delete';
                    if (loadingSpinner) loadingSpinner.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error deleting product: ' + error.message, 'error');
                // Reset button state
                button.disabled = false;
                if (buttonText) buttonText.textContent = 'Delete';
                if (loadingSpinner) loadingSpinner.classList.add('hidden');
            });
        });
    }
    
    // Close modal on cancel or outside click
    const cancelDeleteProduct = document.querySelector('#deleteProductModal button[onclick="closeDeleteProductModal()"]');
    if (cancelDeleteProduct) {
        cancelDeleteProduct.addEventListener('click', closeDeleteProductModal);
    }

    // Initialize sizes on page load
    console.log('[DOMContentLoaded] Initializing sizes on page load');
    updateSizes();
});
