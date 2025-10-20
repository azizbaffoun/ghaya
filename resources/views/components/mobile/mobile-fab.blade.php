{{-- Mobile Floating Action Button --}}
@props([
    'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>',
    'color' => 'blue',
    'position' => 'bottom-right',
    'onclick' => '',
    'href' => ''
])

@php
    $colorClasses = [
        'blue' => 'from-blue-600 to-purple-600',
        'green' => 'from-green-600 to-green-700',
        'red' => 'from-red-600 to-red-700',
        'purple' => 'from-purple-600 to-purple-700'
    ];
    $colorClass = $colorClasses[$color] ?? $colorClasses['blue'];
    
    $positionClasses = [
        'bottom-right' => 'bottom-6 right-6',
        'bottom-left' => 'bottom-6 left-6',
        'top-right' => 'top-6 right-6',
        'top-left' => 'top-6 left-6'
    ];
    $positionClass = $positionClasses[$position] ?? $positionClasses['bottom-right'];
@endphp

<div class="lg:hidden fixed {{ $positionClass }} z-40">
    @if($href)
        <a href="{{ $href }}" class="bg-gradient-to-r {{ $colorClass }} text-white w-14 h-14 rounded-full shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center touch-target">
            {!! $icon !!}
        </a>
    @else
        <button onclick="{{ $onclick }}" class="bg-gradient-to-r {{ $colorClass }} text-white w-14 h-14 rounded-full shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center touch-target">
            {!! $icon !!}
        </button>
    @endif
</div>
