@extends('admin.layout')

@section('title', 'Website Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mobile-title-responsive">Website Management</h1>
            <p class="text-sm text-gray-600 mt-1 mobile-subtitle-hidden sm:block">Manage your website content and homepage sections</p>
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

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button onclick="switchTab('banners')" 
                    id="banners-tab"
                    class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                Banners
            </button>
            <button onclick="switchTab('sections')" 
                    id="sections-tab"
                    class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Page Sections
            </button>
        </nav>
    </div>

    <!-- Banners Tab Content -->
    <div id="banners-content" class="tab-content">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0 mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Homepage Banners</h2>
            <button onclick="openBannerModal()" 
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 shadow-lg transition-all duration-200 touch-button">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Banner
            </button>
        </div>

        <!-- Banners Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @forelse($banners as $banner)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-200">
                <!-- Banner Image -->
                <div class="aspect-video bg-gray-200 relative">
                    @if($banner->getTranslation('image'))
                        <img src="{{ $banner->getImageUrl() }}" 
                             alt="{{ $banner->getTranslation('title') }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        <button onclick="toggleBanner({{ $banner->id }})" 
                                class="px-3 py-1 rounded-full text-xs font-semibold transition-colors {{ $banner->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                            {{ $banner->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </div>

                    <!-- Type Badge -->
                    <div class="absolute top-3 left-3">
                        <span class="px-2 py-1 bg-black bg-opacity-50 text-white text-xs font-semibold rounded">
                            {{ ucfirst($banner->type) }}
                        </span>
                    </div>
                </div>

                <!-- Banner Content -->
                <div class="p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">
                        {{ $banner->getTranslation('title') ?: 'No Title' }}
                    </h3>
                    
                    @if($banner->getTranslation('subtitle'))
                        <p class="text-xs sm:text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ $banner->getTranslation('subtitle') }}
                        </p>
                    @endif

                    <!-- Banner Details -->
                    <div class="space-y-2 text-xs sm:text-sm text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Position: {{ $banner->position ?: 'Not set' }}
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Sort Order: {{ $banner->sort_order }}
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-200">
                        <div class="flex items-center space-x-3">
                            <button onclick="editBanner({{ $banner->id }})" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors touch-target p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button onclick="deleteBanner({{ $banner->id }})" 
                                    class="text-red-600 hover:text-red-800 transition-colors touch-target p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                        <span class="text-xs text-gray-500">
                            {{ $banner->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No banners</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new banner.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Page Sections Tab Content -->
    <div id="sections-content" class="tab-content hidden">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0 mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Homepage Sections</h2>
            <button onclick="openSectionModal()" 
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 shadow-lg transition-all duration-200 touch-button">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Section
            </button>
        </div>

        <!-- Sections List -->
        <div class="space-y-4">
            @forelse($sections as $section)
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-transparent hover:border-blue-200 transition-all duration-200">
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
                            <h3 class="text-lg font-semibold text-gray-900">{{ $section->name }}</h3>
                            <p class="text-sm text-gray-500 capitalize">{{ str_replace('_', ' ', $section->type) }}</p>
                            @if($section->getTranslation('title'))
                                <p class="text-sm text-gray-600 mt-1">{{ $section->getTranslation('title') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <button onclick="toggleSection({{ $section->id }})" 
                                class="px-3 py-1 rounded-full text-xs font-semibold transition-colors {{ $section->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                            {{ $section->is_active ? 'Visible' : 'Hidden' }}
                        </button>
                        <button onclick="editSection({{ $section->id }})" 
                                class="text-blue-600 hover:text-blue-800 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button onclick="deleteSection({{ $section->id }})" 
                                class="text-red-600 hover:text-red-800 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-gray-50 rounded-xl">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No sections</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by adding a section to your homepage.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Banner Modal -->
<div id="banner-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeBannerModal()"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="banner-form">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Add Banner</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="banner-title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                                    <input type="text" id="banner-title" name="title" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                </div>
                                
                                <div>
                                    <label for="banner-subtitle" class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                                    <input type="text" id="banner-subtitle" name="subtitle" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="banner-type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                    <select id="banner-type" name="type" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            required>
                                        <option value="">Select Type</option>
                                        <option value="hero">Hero Banner</option>
                                        <option value="promotional">Promotional Banner</option>
                                        <option value="category">Category Banner</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="banner-image" class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                                    <input type="file" id="banner-image" name="image" accept="image/*" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <p class="mt-1 text-xs text-gray-500">Upload an image for the banner background</p>
                                </div>
                                
                                <div>
                                    <label for="banner-video" class="block text-sm font-medium text-gray-700 mb-2">Video (Optional)</label>
                                    <input type="file" id="banner-video" name="video" accept="video/*" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <p class="mt-1 text-xs text-gray-500">Upload a video file (MP4, MOV, AVI, WMV - Max 10MB)</p>
                                </div>
                                
                                <div>
                                    <label for="banner-video-url" class="block text-sm font-medium text-gray-700 mb-2">Video URL (Optional)</label>
                                    <input type="url" id="banner-video-url" name="video_url" 
                                           placeholder="https://www.youtube.com/watch?v=..." 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <p class="mt-1 text-xs text-gray-500">Or paste a YouTube/Vimeo URL instead of uploading</p>
                                </div>
                                
                                <div>
                                    <label for="banner-cta-text" class="block text-sm font-medium text-gray-700 mb-2">CTA Button Text</label>
                                    <input type="text" id="banner-cta-text" name="cta_text" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="banner-cta-link" class="block text-sm font-medium text-gray-700 mb-2">CTA Button Link</label>
                                    <input type="url" id="banner-cta-link" name="cta_link" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" id="banner-active" name="is_active" value="1" checked
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="banner-active" class="ml-2 block text-sm text-gray-900">
                                        Show this banner on the website
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Banner
                    </button>
                    <button type="button" 
                            onclick="closeBannerModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Section Modal -->
<div id="section-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeSectionModal()"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="section-form">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Add Section</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="section-name" class="block text-sm font-medium text-gray-700 mb-2">Section Name</label>
                                    <input type="text" id="section-name" name="name" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                </div>
                                
                                <div>
                                    <label for="section-type" class="block text-sm font-medium text-gray-700 mb-2">Section Type</label>
                                    <select id="section-type" name="type" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            required>
                                        <option value="">Select Type</option>
                                        <option value="hero_banner">Hero Banner</option>
                                        <option value="featured_products">Featured Products</option>
                                        <option value="category_grid">Category Grid</option>
                                        <option value="text_block">Text Block</option>
                                        <option value="image_gallery">Image Gallery</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="section-title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                                    <input type="text" id="section-title" name="title" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" id="section-visible" name="is_visible" value="1" checked
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="section-visible" class="ml-2 block text-sm text-gray-900">
                                        Show this section on the website
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Section
                    </button>
                    <button type="button" 
                            onclick="closeSectionModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@vite('resources/js/website-management.js')
@endpush
