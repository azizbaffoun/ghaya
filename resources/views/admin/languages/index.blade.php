@extends('admin.layout')

@section('title', __('admin.languages.title'))

@section('content')
<div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mobile-title-responsive">{{ __('admin.languages.title') }}</h1>
            <p class="text-sm text-gray-600 mt-1 mobile-subtitle-hidden sm:block">{{ __('admin.languages.subtitle') }}</p>
        </div>
        <a href="{{ route('admin.languages.create') }}" 
           class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 shadow-lg transition-all duration-200 touch-button">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('admin.languages.add_language') }}
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

    <!-- Languages Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.languages.language_code') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.languages.language_name') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.languages.native_name') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.languages.is_active') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.languages.is_default') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.languages.sort_order') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('admin.common.actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($languages as $language)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($language->flag)
                                <img src="{{ asset('storage/' . $language->flag) }}" alt="{{ $language->name }}" class="w-6 h-4 mr-2 rounded">
                            @endif
                            <span class="text-lg font-semibold">{{ strtoupper($language->code) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium">{{ $language->name }}</td>
                    <td class="px-6 py-4">{{ $language->native_name }}</td>
                    <td class="px-6 py-4">
                        <button onclick="toggleLanguage({{ $language->id }})" 
                                class="px-3 py-1 rounded-full text-xs font-semibold transition-colors {{ $language->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                            {{ $language->is_active ? __('admin.languages.is_active') : __('admin.languages.inactive') }}
                        </button>
                    </td>
                    <td class="px-6 py-4">
                        @if($language->is_default)
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">{{ __('admin.languages.is_default') }}</span>
                        @else
                            <form method="POST" action="{{ route('admin.languages.set-default', $language) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 transition-colors">{{ __('admin.languages.set_as_default') }}</button>
                            </form>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-sm">{{ $language->sort_order }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.languages.edit', $language) }}" 
                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            @if(!$language->is_default)
                            <form method="POST" action="{{ route('admin.languages.destroy', $language) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this language? This action cannot be undone.')" 
                                        class="text-red-600 hover:text-red-800 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No languages</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new language.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden p-4 space-y-4">
            @forelse($languages as $language)
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $language->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $language->native_name }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($language->is_active)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Inactive
                            </span>
                        @endif
                        
                        @if($language->is_default)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Default
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Code:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $language->code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Order:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $language->sort_order }}</span>
                    </div>
                </div>
                
                <div class="flex flex-col space-y-2">
                    <button onclick="toggleLanguage({{ $language->id }})" 
                            class="w-full bg-blue-600 text-white text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        {{ $language->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.languages.edit', $language) }}" 
                           class="flex-1 bg-gray-100 text-gray-700 text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                            Edit
                        </a>
                        
                        @if(!$language->is_default)
                        <form method="POST" action="{{ route('admin.languages.destroy', $language) }}" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this language?')" 
                                    class="w-full bg-red-100 text-red-700 text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors">
                                Delete
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No languages</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new language.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function toggleLanguage(id) {
    fetch(`/admin/languages/${id}/toggle`, {
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
            console.log('Language status updated successfully');
        } else {
            alert('Error toggling language status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error toggling language status');
    });
}
</script>
@endsection





