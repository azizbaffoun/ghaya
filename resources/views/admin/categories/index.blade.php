@extends('admin.layout')

@section('title', __('admin.categories.title'))

@section('content')
<div class="space-y-6" data-page-title="{{ __('admin.categories.title') }}" data-page-subtitle="{{ __('admin.categories.subtitle') }}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-xl sm:text-3xl font-bold text-gray-900 mobile-title-responsive">{{ __('admin.categories.title') }}</h1>
            <p class="mt-2 text-gray-600 mobile-subtitle-hidden sm:block">{{ __('admin.categories.subtitle', [], 'Organize your products with categories') }}</p>
        </div>
        <button onclick="openCategoryModal()" class="bg-gradient-to-r from-green-600 to-teal-600 text-white px-4 sm:px-6 py-3 rounded-xl font-medium hover:from-green-700 hover:to-teal-700 transition-all duration-200 shadow-lg hover:shadow-xl touch-button mobile-full-width sm:w-auto inline-flex items-center">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            {{ __('admin.categories.add_category') }}
        </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 min-[480px]:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        @forelse($categories as $category)
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 card-hover overflow-hidden" data-category-id="{{ $category->id }}">
            <div class="h-24 sm:h-32 {{ $category->image ? '' : 'bg-gradient-to-r from-blue-500 to-purple-600' }} flex items-center justify-center">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" 
                         alt="{{ $category->getTranslatedNameAttribute() ?: $category->name }}" 
                         class="h-full w-full object-cover">
                @else
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                @endif
            </div>
            <div class="p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">
                    {{ $category->getTranslatedNameAttribute() ?: $category->name }}
                </h3>
                <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">
                    {{ $category->getTranslatedDescriptionAttribute() ?: $category->description ?: 'No description available' }}
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-xs sm:text-sm text-gray-500">{{ $category->products_count }} {{ $category->products_count === 1 ? 'product' : 'products' }}</span>
                    <div class="flex space-x-2">
                        <button onclick="openCategoryModal({{ $category->id }})" class="text-blue-600 hover:text-blue-900 transition-colors duration-200 touch-target p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button onclick="openDeleteCategoryModal({{ $category->id }}, '{{ addslashes($category->getTranslatedNameAttribute() ?: $category->name) }}', {{ $category->products_count }}, {{ $category->children()->count() }})" 
                                class="text-red-600 hover:text-red-900 transition-colors duration-200 touch-target p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-gray-50 rounded-2xl p-8 border border-gray-200 text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <p class="text-lg font-medium text-gray-900 mb-2">{{ __('admin.categories.no_categories') }}</p>
            <p class="text-sm text-gray-600">{{ __('admin.categories.no_categories_description') }}</p>
        </div>
        @endforelse

        <!-- Add Category Card -->
        <button onclick="openCategoryModal()" class="bg-white rounded-2xl shadow-xl border-2 border-dashed border-gray-300 card-hover overflow-hidden flex items-center justify-center hover:border-blue-400 transition-colors duration-200 touch-target w-full">
            <div class="text-center p-4 sm:p-6">
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400 mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <p class="text-gray-500 font-medium text-sm sm:text-base">{{ __('admin.categories.add_new') }}</p>
            </div>
        </button>
    </div>

    <!-- Include Category Modal -->
    @include('admin.categories.partials.category-modal')
    
    <!-- Include Delete Confirmation Modal -->
    @include('admin.categories.partials.delete-modal')
</div>
@endsection

@push('scripts')
@vite(['resources/js/category-modal.js'])
@endpush

