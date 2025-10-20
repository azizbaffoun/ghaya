{{-- Mobile Data Card Component --}}
@props([
    'title' => '',
    'subtitle' => '',
    'value' => '',
    'icon' => '',
    'badge' => null,
    'actions' => [],
    'clickable' => false
])

<div class="bg-gray-50 rounded-xl p-4 border border-gray-200 {{ $clickable ? 'cursor-pointer hover:bg-gray-100 transition-colors' : '' }}">
    <div class="flex items-start space-x-4">
        @if($icon)
        <div class="flex-shrink-0">
            <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                {!! $icon !!}
            </div>
        </div>
        @endif
        
        <div class="flex-1 min-w-0">
            <div class="flex justify-between items-start mb-2">
                <h4 class="font-semibold text-gray-900 truncate">{{ $title }}</h4>
                @if($badge)
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $badge['class'] ?? 'bg-gray-100 text-gray-800' }} ml-2">
                        {{ $badge['text'] }}
                    </span>
                @endif
            </div>
            
            @if($subtitle)
            <p class="text-sm text-gray-500 mb-2">{{ $subtitle }}</p>
            @endif
            
            @if($value)
            <div class="flex justify-between items-center mb-3">
                <div>
                    <span class="text-lg font-bold text-gray-900">{{ $value }}</span>
                </div>
            </div>
            @endif
            
            @if(count($actions) > 0)
            <div class="flex space-x-2">
                @foreach($actions as $action)
                    <button class="flex-1 {{ $action['class'] ?? 'bg-blue-600 text-white' }} text-center py-2 px-3 rounded-lg text-sm font-medium hover:opacity-90 transition-colors">
                        {{ $action['text'] }}
                    </button>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
