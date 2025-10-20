{{-- Mobile Stat Card Component --}}
@props([
    'title' => '',
    'value' => '0',
    'icon' => '',
    'color' => 'blue',
    'subtitle' => '',
    'trend' => null
])

@php
    $colorClasses = [
        'blue' => 'from-blue-500 to-blue-600',
        'green' => 'from-green-500 to-green-600',
        'yellow' => 'from-yellow-500 to-orange-500',
        'purple' => 'from-purple-500 to-purple-600',
        'red' => 'from-red-500 to-red-600',
        'indigo' => 'from-indigo-500 to-indigo-600'
    ];
    $colorClass = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

<div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100 card-hover">
    <div class="p-4 sm:p-6">
        <div class="flex items-center sm:flex-row flex-col text-center sm:text-left">
            <div class="flex-shrink-0 mx-auto sm:mx-0 mb-2 sm:mb-0">
                <div class="w-12 h-12 bg-gradient-to-r {{ $colorClass }} rounded-xl flex items-center justify-center">
                    @if($icon)
                        {!! $icon !!}
                    @else
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    @endif
                </div>
            </div>
            <div class="ml-0 sm:ml-4 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500">{{ $title }}</dt>
                    <dd class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $value }}</dd>
                    @if($subtitle)
                        <dd class="text-xs text-gray-400 mt-1">{{ $subtitle }}</dd>
                    @endif
                    @if($trend)
                        <dd class="text-xs {{ $trend['positive'] ? 'text-green-600' : 'text-red-600' }} mt-1">
                            <span class="inline-flex items-center">
                                @if($trend['positive'])
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7" />
                                    </svg>
                                @else
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10" />
                                    </svg>
                                @endif
                                {{ $trend['value'] }}
                            </span>
                        </dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
