@extends('admin.layout')

@section('title', __('admin.page_builder.title'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.page_builder.title') }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ __('admin.page_builder.subtitle') }}</p>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Page Selector -->
            <select id="page-selector" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="home">{{ __('admin.page_builder.home_page') }}</option>
                <option value="about">{{ __('admin.page_builder.about_page') }}</option>
                <option value="contact">{{ __('admin.page_builder.contact_page') }}</option>
                <option value="products">{{ __('admin.page_builder.products_page') }}</option>
            </select>
            
            <!-- Add Section Button -->
            <button onclick="openAddSectionModal()" 
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 shadow-lg transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('admin.page_builder.add_section') }}
            </button>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Page Sections -->
    <div id="sections-container" class="space-y-4">
        <!-- This will be populated by JavaScript -->
        <div class="text-center py-12 bg-gray-50 rounded-xl">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No sections</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by adding a section to your page.</p>
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div id="add-section-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeAddSectionModal()"></div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="add-section-form">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Add Section</h3>
                            
                            <div class="space-y-4">
                                <!-- Section Type -->
                                <div>
                                    <label for="section-type" class="block text-sm font-medium text-gray-700 mb-2">
                                        Section Type
                                    </label>
                                    <select id="section-type" 
                                            name="type" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            required>
                                        <option value="">Select Section Type</option>
                                        <option value="hero_banner">Hero Banner</option>
                                        <option value="featured_products">Featured Products</option>
                                        <option value="category_grid">Category Grid</option>
                                        <option value="text_block">Text Block</option>
                                        <option value="image_gallery">Image Gallery</option>
                                        <option value="testimonials">Testimonials</option>
                                    </select>
                                </div>

                                <!-- Section Name -->
                                <div>
                                    <label for="section-name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Section Name
                                    </label>
                                    <input type="text" 
                                           id="section-name" 
                                           name="name" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="e.g., Main Hero Banner"
                                           required>
                                </div>

                                <!-- Sort Order -->
                                <div>
                                    <label for="section-sort-order" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sort Order
                                    </label>
                                    <input type="number" 
                                           id="section-sort-order" 
                                           name="sort_order" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           value="0"
                                           min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Add Section
                    </button>
                    <button type="button" 
                            onclick="closeAddSectionModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
let sortable;

document.addEventListener('DOMContentLoaded', function() {
    loadSections();
    initializeSortable();
});

function loadSections() {
    const page = document.getElementById('page-selector').value;
    
    fetch(`/admin/page-builder/sections?page=${page}`)
        .then(response => response.json())
        .then(data => {
            renderSections(data.sections);
        })
        .catch(error => {
            console.error('Error loading sections:', error);
        });
}

function renderSections(sections) {
    const container = document.getElementById('sections-container');
    
    if (sections.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12 bg-gray-50 rounded-xl">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No sections</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by adding a section to your page.</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = sections.map(section => `
        <div class="section-item bg-white rounded-xl shadow-lg p-6 border-2 border-transparent hover:border-blue-200 transition-all duration-200" 
             data-id="${section.id}">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">${section.name}</h3>
                        <p class="text-sm text-gray-500 capitalize">${section.type.replace('_', ' ')}</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <button onclick="toggleSection(${section.id})" 
                            class="px-3 py-1 rounded-full text-xs font-semibold transition-colors ${section.is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200'}">
                        ${section.is_active ? 'Active' : 'Inactive'}
                    </button>
                    <button onclick="editSection(${section.id})" 
                            class="text-blue-600 hover:text-blue-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <button onclick="deleteSection(${section.id})" 
                            class="text-red-600 hover:text-red-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
    
    initializeSortable();
}

function initializeSortable() {
    const container = document.getElementById('sections-container');
    if (sortable) {
        sortable.destroy();
    }
    
    sortable = new Sortable(container, {
        animation: 150,
        ghostClass: 'opacity-50',
        onEnd: function(evt) {
            const sectionIds = Array.from(container.children).map(item => item.dataset.id);
            updateSectionOrder(sectionIds);
        }
    });
}

function updateSectionOrder(sectionIds) {
    fetch('/admin/page-builder/reorder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            section_ids: sectionIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Section order updated');
        }
    })
    .catch(error => {
        console.error('Error updating section order:', error);
    });
}

function openAddSectionModal() {
    document.getElementById('add-section-modal').classList.remove('hidden');
}

function closeAddSectionModal() {
    document.getElementById('add-section-modal').classList.add('hidden');
    document.getElementById('add-section-form').reset();
}

function toggleSection(id) {
    fetch(`/admin/page-builder/${id}/toggle`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadSections();
        }
    })
    .catch(error => {
        console.error('Error toggling section:', error);
    });
}

function editSection(id) {
    // TODO: Implement edit functionality
    alert('Edit functionality will be implemented');
}

function deleteSection(id) {
    if (confirm('Are you sure you want to delete this section?')) {
        fetch(`/admin/page-builder/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadSections();
            }
        })
        .catch(error => {
            console.error('Error deleting section:', error);
        });
    }
}

// Handle page selector change
document.getElementById('page-selector').addEventListener('change', loadSections);

// Handle add section form submission
document.getElementById('add-section-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('page', document.getElementById('page-selector').value);
    
    fetch('/admin/page-builder', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddSectionModal();
            loadSections();
        }
    })
    .catch(error => {
        console.error('Error creating section:', error);
    });
});
</script>
@endsection





