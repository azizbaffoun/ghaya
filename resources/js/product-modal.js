// Product Modal JavaScript
let selectedColors = [];
let selectedImages = [];
let colorImages = {}; // New: Store images for each color
let isEditMode = false;
let currentProductId = null;

// Modal Controls
function openProductModal(productId = null) {
    isEditMode = productId !== null;
    currentProductId = productId;
    
    if (isEditMode) {
        loadProductData(productId);
        document.getElementById('modal-title-text').textContent = 'Edit Product';
        document.getElementById('save_text').textContent = 'Update Product';
    } else {
        resetForm();
        document.getElementById('modal-title-text').textContent = 'Add Product';
        document.getElementById('save_text').textContent = 'Save Product';
    }
    
    const productModal = document.getElementById('productModal');
    productModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

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

// Update Sizes Function
function updateSizes() {
    const from = parseInt(document.getElementById('size_from').value) || 36;
    const to = parseInt(document.getElementById('size_to').value) || 42;
    const sizesDisplay = document.getElementById('sizes_display');
    
    if (!sizesDisplay) return;
    
    sizesDisplay.innerHTML = '';
    
    for (let i = from; i <= to; i++) {
        const sizeChip = document.createElement('span');
        sizeChip.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
        sizeChip.textContent = i;
        sizesDisplay.appendChild(sizeChip);
    }
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

// Show Notification Function
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
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

function closeProductModal() {
    const productModal = document.getElementById('productModal');
    productModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    resetForm();
}

function openCategoryModal() {
    const categoryModal = document.getElementById('quickCategoryModal');
    categoryModal.classList.remove('hidden');
}

function closeCategoryModal() {
    const categoryModal = document.getElementById('quickCategoryModal');
    const quickCategoryForm = document.getElementById('quickCategoryForm');
    categoryModal.classList.add('hidden');
    quickCategoryForm.reset();
}

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
    if (addColor) {
        addColor.addEventListener('click', function() {
            const colorPicker = document.getElementById('color_picker');
            if (colorPicker) {
                const color = colorPicker.value;
                
                if (color && !selectedColors.includes(color)) {
                    selectedColors.push(color);
                    renderColors();
                }
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

    // Size Range Management
    function updateSizes() {
        const from = parseInt(document.getElementById('size_from').value) || 36;
        const to = parseInt(document.getElementById('size_to').value) || 42;
        const sizesDisplay = document.getElementById('sizes_display');
        
        sizesDisplay.innerHTML = '';
        
        for (let i = from; i <= to; i++) {
            const sizeChip = document.createElement('span');
            sizeChip.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
            sizeChip.textContent = i;
            sizesDisplay.appendChild(sizeChip);
        }
    }

    const sizeFrom = document.getElementById('size_from');
    if (sizeFrom) {
        sizeFrom.addEventListener('input', updateSizes);
    }
    
    const sizeTo = document.getElementById('size_to');
    if (sizeTo) {
        sizeTo.addEventListener('input', updateSizes);
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
                preview.innerHTML = '';
                selectedImages = files;
                
                if (files.length > 0) {
                    preview.classList.remove('hidden');
                    console.log('Preview container classes after removing hidden:', preview.className);
                    console.log('Preview container is visible:', !preview.classList.contains('hidden'));
                    
                    files.forEach((file, index) => {
                        console.log(`Processing file ${index}:`, file.name, file.type, file.size);
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            console.log(`File ${index} loaded successfully, data URL length:`, e.target.result.length);
                            const imageDiv = document.createElement('div');
                            imageDiv.className = 'relative group';
                            imageDiv.innerHTML = `
                                <img src="${e.target.result}" alt="Preview ${index + 1}" style="width: 100%; height: 96px; object-fit: contain; border: 2px solid #e5e7eb; border-radius: 8px; background: white;" onload="console.log('Image ${index} loaded successfully')" onerror="console.error('Image ${index} failed to load')">
                                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0); transition: all 0.2s; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <button type="button" style="opacity: 0; background: #ef4444; color: white; border: none; border-radius: 50%; padding: 4px; cursor: pointer;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'" onclick="removeImage(${index})">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            `;
                            preview.appendChild(imageDiv);
                            console.log(`Image div added for file ${index}`);
                        };
                        reader.onerror = function(e) {
                            console.error(`Error reading file ${index}:`, e);
                        };
                        reader.readAsDataURL(file);
                    });
                } else {
                    preview.classList.add('hidden');
                }
            }
        });
    }

    window.removeImage = function(index) {
        selectedImages.splice(index, 1);
        // Re-render preview
        const preview = document.getElementById('image_preview');
        preview.innerHTML = '';
        
        if (selectedImages.length > 0) {
            preview.classList.remove('hidden');
            selectedImages.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'relative group';
                    imageDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-24 object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center">
                            <button type="button" class="opacity-0 group-hover:opacity-100 text-white bg-red-500 hover:bg-red-600 rounded-full p-1" onclick="removeImage(${index})">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    `;
                    preview.appendChild(imageDiv);
                };
                reader.readAsDataURL(file);
            });
        } else {
            preview.classList.add('hidden');
        }
    };

    // Form Submission
    productForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        const submitBtn = document.getElementById('saveProduct');
        const loadingSpinner = document.getElementById('loading_spinner');
        const saveText = document.getElementById('save_text');
        
        // Show loading state
        submitBtn.disabled = true;
        loadingSpinner.classList.remove('hidden');
        saveText.textContent = isEditMode ? 'Updating...' : 'Saving...';
        
        // Add form data
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        console.log('CSRF Token element:', csrfToken);
        if (csrfToken) {
            console.log('CSRF Token value:', csrfToken.getAttribute('content'));
            formData.append('_token', csrfToken.getAttribute('content'));
        } else {
            console.error('CSRF token not found!');
        }
        formData.append('name', document.getElementById('name').value);
        formData.append('sku', document.getElementById('sku').value);
        formData.append('category_id', document.getElementById('category_id').value);
        formData.append('description', document.getElementById('description').value);
        formData.append('price', document.getElementById('price').value);
        formData.append('compare_price', document.getElementById('compare_price').value);
        formData.append('stock_status', document.getElementById('stock_status').value);
        formData.append('is_active', document.getElementById('is_active').checked ? '1' : '0');
        formData.append('has_discount', document.getElementById('has_discount').checked ? '1' : '0');
        // Add colors as individual array elements
        selectedColors.forEach((color, index) => {
            formData.append(`colors[${index}]`, color);
        });
        formData.append('size_from', document.getElementById('size_from').value);
        formData.append('size_to', document.getElementById('size_to').value);
        
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
            submitBtn.disabled = false;
            loadingSpinner.classList.add('hidden');
            saveText.textContent = isEditMode ? 'Update Product' : 'Save Product';
        });
    });

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


    // Reset Form
    function resetForm() {
        if (productForm) {
            productForm.reset();
        }
        selectedColors = [];
        selectedImages = [];
        colorImages = {}; // Reset color images
        
        const colorsList = document.getElementById('colors_list');
        if (colorsList) {
            colorsList.innerHTML = '';
        }
        
        const imagePreview = document.getElementById('image_preview');
        if (imagePreview) {
            imagePreview.classList.add('hidden');
        }
        
        const discountField = document.getElementById('discount_field');
        if (discountField) {
            discountField.classList.add('hidden');
        }
        
        const productId = document.getElementById('product_id');
        if (productId) {
            productId.value = '';
        }
        
        updateSizes();
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

    // Initialize sizes on page load
    updateSizes();
});

// Make functions globally available
window.openProductModal = openProductModal;
window.closeProductModal = closeProductModal;
window.openCategoryModal = openCategoryModal;
window.closeCategoryModal = closeCategoryModal;
