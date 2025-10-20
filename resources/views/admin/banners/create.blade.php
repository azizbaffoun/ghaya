@extends('admin.layout')

@section('title', 'Add Banner')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mobile-title-responsive">Add Banner</h1>
            <p class="text-sm text-gray-600 mt-1 mobile-subtitle-hidden sm:block">Create a new promotional banner</p>
        </div>
        <a href="{{ route('admin.banners.index') }}" 
           class="inline-flex items-center px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors touch-button mobile-full-width sm:w-auto">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Banners
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
        <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Basic Settings -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Banner Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Banner Type <span class="text-red-500">*</span>
                    </label>
                    <select id="type" 
                            name="type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror text-base"
                            required>
                        <option value="">Select Type</option>
                        <option value="hero" {{ old('type') == 'hero' ? 'selected' : '' }}>Hero Banner</option>
                        <option value="banner" {{ old('type') == 'banner' ? 'selected' : '' }}>Promotional Banner</option>
                        <option value="slider" {{ old('type') == 'slider' ? 'selected' : '' }}>Slider Item</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Position -->
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                        Position
                    </label>
                    <select id="position" 
                            name="position" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('position') border-red-500 @enderror text-base">
                        <option value="">Select Position</option>
                        <option value="top" {{ old('position') == 'top' ? 'selected' : '' }}>Top</option>
                        <option value="middle" {{ old('position') == 'middle' ? 'selected' : '' }}>Middle</option>
                        <option value="bottom" {{ old('position') == 'bottom' ? 'selected' : '' }}>Bottom</option>
                        <option value="sidebar" {{ old('position') == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                    </select>
                    @error('position')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                        Sort Order
                    </label>
                    <input type="number" 
                           id="sort_order" 
                           name="sort_order" 
                           value="{{ old('sort_order', 0) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-500 @enderror text-base"
                           min="0">
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Start Date
                    </label>
                    <input type="datetime-local" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ old('start_date') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror text-base">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        End Date
                    </label>
                    <input type="datetime-local" 
                           id="end_date" 
                           name="end_date" 
                           value="{{ old('end_date') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror text-base">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="flex items-center">
                <input type="checkbox" 
                       id="is_active" 
                       name="is_active" 
                       value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}
                       class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded touch-target">
                <label for="is_active" class="ml-3 block text-sm text-gray-700 touch-target">
                    Active
                </label>
            </div>

            <!-- Language Tabs -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Translations</h3>
                
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-2 sm:space-x-8 overflow-x-auto">
                        @foreach($languages as $index => $language)
                        <button type="button" 
                                onclick="switchTab('{{ $language->code }}')"
                                class="tab-button py-3 px-2 sm:px-1 border-b-2 font-medium text-sm whitespace-nowrap touch-target {{ $index === 0 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                id="tab-{{ $language->code }}">
                            {{ $language->name }}
                        </button>
                        @endforeach
                    </nav>
                </div>

                @foreach($languages as $index => $language)
                <div id="content-{{ $language->code }}" class="tab-content {{ $index === 0 ? '' : 'hidden' }} mt-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Title -->
                        <div>
                            <label for="translations_{{ $language->code }}_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Title
                            </label>
                            <input type="text" 
                                   id="translations_{{ $language->code }}_title" 
                                   name="translations[{{ $language->code }}][title]" 
                                   value="{{ old('translations.' . $language->code . '.title') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">
                        </div>

                        <!-- Button Text -->
                        <div>
                            <label for="translations_{{ $language->code }}_button_text" class="block text-sm font-medium text-gray-700 mb-2">
                                Button Text
                            </label>
                            <input type="text" 
                                   id="translations_{{ $language->code }}_button_text" 
                                   name="translations[{{ $language->code }}][button_text]" 
                                   value="{{ old('translations.' . $language->code . '.button_text') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">
                        </div>
                    </div>

                    <!-- Subtitle -->
                    <div>
                        <label for="translations_{{ $language->code }}_subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                            Subtitle
                        </label>
                        <textarea id="translations_{{ $language->code }}_subtitle" 
                                  name="translations[{{ $language->code }}][subtitle]" 
                                  rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">{{ old('translations.' . $language->code . '.subtitle') }}</textarea>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="translations_{{ $language->code }}_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="translations_{{ $language->code }}_description" 
                                  name="translations[{{ $language->code }}][description]" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">{{ old('translations.' . $language->code . '.description') }}</textarea>
                    </div>

                    <!-- Button URL -->
                    <div>
                        <label for="translations_{{ $language->code }}_button_url" class="block text-sm font-medium text-gray-700 mb-2">
                            Button URL
                        </label>
                        <input type="url" 
                               id="translations_{{ $language->code }}_button_url" 
                               name="translations[{{ $language->code }}][button_url]" 
                               value="{{ old('translations.' . $language->code . '.button_url') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base"
                               placeholder="https://example.com">
                    </div>

                    <!-- Image Uploads -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Desktop Image -->
                        <div>
                            <label for="translations_{{ $language->code }}_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Desktop Image
                            </label>
                            <input type="file" 
                                   id="translations_{{ $language->code }}_image" 
                                   name="translations[{{ $language->code }}][image]" 
                                   accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">
                            <p class="mt-1 text-xs text-gray-500">Recommended: 1920x600px</p>
                        </div>

                        <!-- Mobile Image -->
                        <div>
                            <label for="translations_{{ $language->code }}_mobile_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Mobile Image
                            </label>
                            <input type="file" 
                                   id="translations_{{ $language->code }}_mobile_image" 
                                   name="translations[{{ $language->code }}][mobile_image]" 
                                   accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">
                            <p class="mt-1 text-xs text-gray-500">Recommended: 768x400px</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Submit Button -->
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.banners.index') }}" 
                   class="w-full sm:w-auto px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors touch-button text-center">
                    Cancel
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 shadow-lg transition-all duration-200 touch-button">
                    Create Banner
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(languageCode) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + languageCode).classList.remove('hidden');
    
    // Add active class to selected tab button
    const activeButton = document.getElementById('tab-' + languageCode);
    activeButton.classList.remove('border-transparent', 'text-gray-500');
    activeButton.classList.add('border-blue-500', 'text-blue-600');
}
</script>
@endsection





