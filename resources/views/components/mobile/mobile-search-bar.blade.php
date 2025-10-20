{{-- Mobile Search Bar Component --}}
@props([
    'placeholder' => 'Search...',
    'value' => '',
    'filters' => [],
    'onSearch' => '',
    'onFilter' => ''
])

<div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6">
    <div class="flex flex-col space-y-4">
        <!-- Search Input -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" 
                   placeholder="{{ $placeholder }}"
                   value="{{ $value }}"
                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                   onkeyup="{{ $onSearch }}">
        </div>
        
        @if(count($filters) > 0)
        <!-- Filters -->
        <div class="flex flex-wrap gap-2">
            @foreach($filters as $filter)
                <button onclick="{{ $onFilter }}('{{ $filter['key'] }}')" 
                        class="px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                    {{ $filter['label'] }}
                </button>
            @endforeach
        </div>
        @endif
    </div>
</div>
