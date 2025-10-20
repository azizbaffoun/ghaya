@extends('admin.layout')

@section('title', __('admin.banners.title'))

@section('content')
<div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mobile-title-responsive">{{ __('admin.banners.title') }}</h1>
            <p class="text-sm text-gray-600 mt-1 mobile-subtitle-hidden sm:block">{{ __('admin.banners.subtitle') }}</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" 
           class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 shadow-lg transition-all duration-200 touch-button">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Banner
        </a>
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
                        {{ $banner->is_active ? __('admin.banners.active') : __('admin.banners.inactive') }}
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
                    {{ $banner->getTranslation('title') ?: __('admin.banners.no_banners') }}
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
                        {{ __('admin.banners.banner_position') }}: {{ $banner->position ?: __('admin.common.no_data') }}
                    </div>
                    
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        {{ __('admin.banners.sort_order') }}: {{ $banner->sort_order }}
                    </div>

                    @if($banner->start_date || $banner->end_date)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        @if($banner->start_date && $banner->end_date)
                            {{ $banner->start_date->format('M d') }} - {{ $banner->end_date->format('M d, Y') }}
                        @elseif($banner->start_date)
                            From {{ $banner->start_date->format('M d, Y') }}
                        @elseif($banner->end_date)
                            Until {{ $banner->end_date->format('M d, Y') }}
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-200">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.banners.edit', $banner) }}" 
                           class="text-blue-600 hover:text-blue-800 transition-colors touch-target p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this banner? This action cannot be undone.')" 
                                    class="text-red-600 hover:text-red-800 transition-colors touch-target p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
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
                <div class="mt-6">
                    <a href="{{ route('admin.banners.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 shadow-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Banner
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($banners->hasPages())
        <div class="mt-6">
            {{ $banners->links() }}
        </div>
    @endif
</div>

<script>
function toggleBanner(id) {
    fetch(`/admin/banners/${id}/toggle`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the button text and classes dynamically
            const button = event.target;
            const isActive = data.is_active;
            
            button.textContent = isActive ? 'Active' : 'Inactive';
            button.className = button.className.replace(
                isActive ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'bg-green-100 text-green-800 hover:bg-green-200',
                isActive ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200'
            );
            
            // Show success message
            console.log('Banner status updated successfully');
        } else {
            alert('Error toggling banner status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error toggling banner status');
    });
}
</script>
@endsection





