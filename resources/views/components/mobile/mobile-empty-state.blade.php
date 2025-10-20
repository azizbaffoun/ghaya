{{-- Mobile Empty State Component --}}
@props([
    'icon' => '<svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
    'title' => 'No data available',
    'description' => 'Get started by creating your first item.',
    'action' => null
])

<div class="text-center py-12 px-4">
    <div class="mb-4">
        {!! $icon !!}
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $title }}</h3>
    <p class="text-sm text-gray-500 mb-6">{{ $description }}</p>
    
    @if($action)
        <button onclick="{{ $action['onclick'] ?? '' }}" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors touch-button">
            {{ $action['text'] ?? 'Get Started' }}
        </button>
    @endif
</div>
