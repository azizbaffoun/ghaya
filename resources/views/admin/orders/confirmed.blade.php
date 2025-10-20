@extends('admin.layout')

@section('title', __('admin.orders.confirmed_orders'))

@section('content')
<div class="space-y-6" x-data="{ selectedOrders: [], showBulkActions: false, expandedOrders: [] }" data-page-title="{{ __('admin.orders.confirmed_orders') }}" data-page-subtitle="{{ __('admin.orders.confirmed_orders_subtitle') }}">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="mobile-text-2xl text-3xl font-bold text-gray-900">{{ __('admin.orders.confirmed_orders') }}</h1>
            <p class="mt-2 mobile-text-base text-gray-600">{{ __('admin.orders.confirmed_orders_subtitle') }}</p>
        </div>
        <div class="flex space-x-3">
            <button @click="showBulkActions = !showBulkActions" 
                    :class="showBulkActions ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-500/25' : 'bg-white text-gray-700 border border-gray-300 hover:border-gray-400 shadow-sm'"
                    class="mobile-min-h-12 px-6 py-3 rounded-xl font-medium transition-all duration-300 hover:shadow-md touch-button">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                {{ __('admin.orders.bulk_actions') }}
            </button>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div x-show="showBulkActions" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-blue-900">
                    <span x-text="selectedOrders.length"></span> {{ __('admin.orders.orders_selected') }}
                </span>
                <button @click="selectedOrders = []; showBulkActions = false" 
                        class="text-blue-600 hover:text-blue-800 text-sm">
                    {{ __('admin.orders.clear_selection') }}
                </button>
            </div>
            <div class="flex space-x-3">
                <button @click="printSelected()" 
                        :disabled="selectedOrders.length === 0"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 disabled:from-gray-400 disabled:to-gray-500 text-white rounded-xl font-medium shadow-lg shadow-blue-500/25 hover:shadow-xl transition-all duration-300 disabled:shadow-none">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    {{ __('admin.orders.print_selected') }}
                </button>
                <form method="POST" action="{{ route('admin.orders.bulk-print') }}" 
                      @submit="if(selectedOrders.length === 0) { event.preventDefault(); alert('Please select orders first'); }">
                    @csrf
                    <input type="hidden" name="order_ids" :value="JSON.stringify(selectedOrders)">
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-xl font-medium shadow-lg shadow-green-500/25 hover:shadow-xl transition-all duration-300">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ __('admin.orders.mark_as_printed') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Summary Section -->
    @if($productSummary->count() > 0)
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Product Summary for Confirmed Orders
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Aggregated quantities by product, size, and color</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-green-600">{{ $orders->total() }}</div>
                    <div class="text-sm text-gray-500">Total Orders</div>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div id="productSummaryGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($productSummary as $index => $item)
                <div class="product-summary-item bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-all duration-200 {{ $index >= 12 ? 'hidden' : '' }}">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900 truncate" title="{{ $item->product_name }}">
                                {{ $item->product_name }}
                            </h4>
                            @if($item->variant_name && $item->variant_name !== 'Default')
                                <p class="text-xs text-gray-600 mt-1">{{ $item->variant_name }}</p>
                            @endif
                        </div>
                        <div class="ml-2 flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $item->total_quantity }} pcs
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        @php
                            // Extract size and color from variant_name if available
                            $size = null;
                            $color = null;
                            
                            // If variant_name contains size/color info, try to extract it
                            if ($item->variant_name && $item->variant_name !== 'Default') {
                                $variantParts = explode(' - ', $item->variant_name);
                                if (count($variantParts) >= 2) {
                                    $color = $variantParts[0];
                                    $size = $variantParts[1];
                                } elseif (count($variantParts) === 1) {
                                    // If only one part, it could be just color or just size
                                    $color = $variantParts[0];
                                }
                            }
                            
                            // Function to convert color code to readable name
                            function getColorName($colorCode) {
                                $colorMap = [
                                    '#000000' => 'Black',
                                    '#FFFFFF' => 'White',
                                    '#FF0000' => 'Red',
                                    '#00FF00' => 'Green',
                                    '#0000FF' => 'Blue',
                                    '#FFFF00' => 'Yellow',
                                    '#FF00FF' => 'Magenta',
                                    '#00FFFF' => 'Cyan',
                                    '#FFA500' => 'Orange',
                                    '#800080' => 'Purple',
                                    '#FFC0CB' => 'Pink',
                                    '#A52A2A' => 'Brown',
                                    '#808080' => 'Gray',
                                    '#C0C0C0' => 'Silver',
                                    '#FFD700' => 'Gold',
                                ];
                                
                                // Check if it's a hex color code
                                if (str_starts_with($colorCode, '#')) {
                                    return $colorMap[$colorCode] ?? ucfirst(strtolower($colorCode));
                                }
                                
                                // If it's already a color name, return as is
                                return ucfirst(strtolower($colorCode));
                            }
                        @endphp
                        
                        @if($size)
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Size:</span>
                            <span class="font-medium text-gray-700">{{ $size }}</span>
                        </div>
                        @endif
                        
                        @if($color)
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Color:</span>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full border border-gray-300 mr-2" 
                                     style="background-color: {{ str_starts_with($color, '#') ? $color : (strtolower($color) === 'black' ? '#000000' : (strtolower($color) === 'white' ? '#ffffff' : strtolower($color))) }}"></div>
                                <span class="font-medium text-gray-700">{{ getColorName($color) }}</span>
                            </div>
                        </div>
                        @endif
                        
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Orders:</span>
                            <span class="font-medium text-gray-700">{{ $item->order_count }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($productSummary->count() > 12)
            <div class="mt-4 text-center">
                <button onclick="toggleSummary()" 
                        class="text-green-600 hover:text-green-800 text-sm font-medium transition-colors duration-200">
                    <span id="summaryToggleText">Show All Products</span>
                    <svg class="w-4 h-4 ml-1 inline transform transition-transform duration-200" 
                         id="summaryToggleIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.orders.confirmed_orders_with_print_links') }}</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" 
                                   @change="selectedOrders = selectedOrders.length === {{ $orders->count() }} ? [] : {{ $orders->pluck('id')->toJson() }}"
                                   :checked="selectedOrders.length === {{ $orders->count() }}"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                            {{ __('admin.orders.order_number') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-64">
                            {{ __('admin.orders.customer') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                            {{ __('admin.orders.phone') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            {{ __('admin.orders.total') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                            {{ __('admin.orders.print_link') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                            {{ __('admin.orders.confirmed_at') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-80">
                            {{ __('admin.common.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <!-- Main Order Row -->
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" 
                                   value="{{ $order->id }}"
                                   @change="toggleOrder({{ $order->id }})"
                                   :checked="selectedOrders.includes({{ $order->id }})"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->customer_full_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->customer_phone)
                                <a href="tel:{{ $order->customer_phone }}" 
                                   class="text-blue-600 hover:text-blue-900 font-medium">
                                    {{ $order->formatted_phone }}
                                </a>
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex flex-col">
                                <span class="font-semibold">{{ number_format($order->total, 3) }} TND</span>
                                @if($order->shipping_cost > 0)
                                    <span class="text-xs text-gray-500">(incl. {{ number_format($order->shipping_cost, 3) }} TND delivery)</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->print_url)
                                <a href="{{ $order->print_url }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-medium rounded-xl shadow-md shadow-blue-500/25 hover:shadow-lg transition-all duration-300">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    {{ __('admin.orders.print') }}
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">{{ __('admin.orders.no_print_link') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->confirmed_at ? $order->confirmed_at->format('M d, Y H:i') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-wrap gap-2">
                                    <!-- Print Button -->
                                    @if($order->print_url)
                                        <a href="{{ $order->print_url }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-medium rounded-xl shadow-md shadow-blue-500/25 hover:shadow-lg transition-all duration-300">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                            {{ __('admin.orders.print') }}
                                        </a>
                                    @endif
                                    
                                    <!-- Mark as Printed Button -->
                                    <form method="POST" action="{{ route('admin.orders.bulk-print') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="order_ids[]" value="{{ $order->id }}">
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-sm font-medium rounded-xl shadow-md shadow-green-500/25 hover:shadow-lg transition-all duration-300">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ __('admin.orders.mark_printed') }}
                                        </button>
                                    </form>
                                    
                                    <!-- View Details Button -->
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white text-sm font-medium rounded-xl shadow-md shadow-gray-500/25 hover:shadow-lg transition-all duration-300">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ __('admin.orders.view') }}
                                    </a>
                                </div>
                                
                                <!-- Expand/Collapse Button -->
                                <button @click="expandedOrders.includes({{ $order->id }}) ? expandedOrders = expandedOrders.filter(id => id !== {{ $order->id }}) : expandedOrders.push({{ $order->id }})"
                                        class="ml-4 p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                    <svg class="w-5 h-5 transform transition-transform duration-200" 
                                         :class="{ 'rotate-180': expandedOrders.includes({{ $order->id }}) }"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Expanded Order Details Row -->
                    <tr x-show="expandedOrders.includes({{ $order->id }})" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="bg-gray-50">
                        <td colspan="8" class="px-6 py-6">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <!-- Customer Information -->
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Customer Information
                                        </h4>
                                        <div class="space-y-3">
                                            <div class="flex">
                                                <span class="w-24 text-sm font-medium text-gray-500">Name:</span>
                                                <span class="text-sm text-gray-900">{{ $order->customer_first_name }} {{ $order->customer_last_name }}</span>
                                            </div>
                                            <div class="flex">
                                                <span class="w-24 text-sm font-medium text-gray-500">Email:</span>
                                                <a href="mailto:{{ $order->customer_email }}" class="text-sm text-blue-600 hover:text-blue-800">{{ $order->customer_email }}</a>
                                            </div>
                                        </div>
                                        
                                        <!-- Shipping Address -->
                                        <h5 class="text-md font-semibold text-gray-900 mt-6 mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Shipping Address
                                        </h5>
                                        <div class="space-y-2 text-sm text-gray-700">
                                            @if($order->shipping_address)
                                                <div>{{ is_array($order->shipping_address) ? implode(', ', $order->shipping_address) : $order->shipping_address }}</div>
                                            @endif
                                            @if($order->shipping_city)
                                                <div>{{ $order->shipping_city }}{{ $order->shipping_state ? ', ' . $order->shipping_state : '' }}</div>
                                            @endif
                                            @if($order->shipping_postal_code)
                                                <div>{{ $order->shipping_postal_code }}</div>
                                            @endif
                                            @if($order->shipping_country)
                                                <div>{{ $order->shipping_country }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Order Details & Products -->
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                            Order Details
                                        </h4>
                                        
                                        <!-- Order Summary -->
                                        <div class="space-y-2 text-sm mb-6">
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Order Number:</span>
                                                <span class="font-medium text-gray-900">#{{ $order->order_number }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Order Date:</span>
                                                <span class="text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Confirmed At:</span>
                                                <span class="text-gray-900">{{ $order->confirmed_at ? $order->confirmed_at->format('M d, Y H:i') : 'N/A' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Payment Method:</span>
                                                <span class="text-gray-900">{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Payment Status:</span>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->payment_status === 'paid') bg-green-100 text-green-800
                                                    @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($order->payment_status ?? 'N/A') }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Products -->
                                        <h5 class="text-md font-semibold text-gray-900 mb-3">Products Ordered</h5>
                                        <div class="space-y-4">
                                            @forelse($order->orderItems as $item)
                                                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                                    <!-- Product Image -->
                                                    <div class="flex-shrink-0">
                                                        @if($item->product && $item->product->images && $item->product->images->count() > 0)
                                                            <img src="{{ Storage::url($item->product->images->first()->image_path) }}" 
                                                                 alt="{{ $item->product_name }}"
                                                                 class="w-16 h-16 object-cover rounded-lg border border-gray-300">
                                                        @else
                                                            <div class="w-16 h-16 bg-gray-200 rounded-lg border border-gray-300 flex items-center justify-center">
                                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Product Details -->
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-start justify-between">
                                                            <div class="flex-1">
                                                                <h6 class="text-lg font-semibold text-gray-900 mb-1">{{ $item->product_name }}</h6>
                                                                
                                                                <!-- Product Variants (Color, Size, etc.) -->
                                                                @if($item->variant_name)
                                                                    @php
                                                                        // Parse variant name for display
                                                                        $variantParts = explode(' - ', $item->variant_name);
                                                                        $displayVariant = '';
                                                                        if (count($variantParts) >= 2) {
                                                                            $color = $variantParts[0];
                                                                            $size = $variantParts[1];
                                                                            $displayVariant = getColorName($color) . ' - ' . $size;
                                                                        } elseif (count($variantParts) === 1) {
                                                                            $displayVariant = getColorName($variantParts[0]);
                                                                        } else {
                                                                            $displayVariant = $item->variant_name;
                                                                        }
                                                                    @endphp
                                                                    <div class="flex items-center space-x-4 mb-2">
                                                                        <div class="flex items-center space-x-2">
                                                                            <span class="text-sm font-medium text-gray-600">Variant:</span>
                                                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                                                                {{ $displayVariant }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                
                                                                <!-- SKU -->
                                                                @if($item->product_sku)
                                                                    <div class="text-xs text-gray-500 mb-2">SKU: {{ $item->product_sku }}</div>
                                                                @endif
                                                                
                                                                <!-- Quantity and Price -->
                                                                <div class="flex items-center space-x-4 text-sm">
                                                                    <span class="font-medium text-gray-700">Quantity: <span class="text-blue-600 font-semibold">{{ $item->quantity }}</span></span>
                                                                    <span class="text-gray-500">|</span>
                                                                    <span class="font-medium text-gray-700">Unit Price: <span class="text-green-600 font-semibold">{{ number_format($item->unit_price, 3) }} TND</span></span>
                                                                    <span class="text-gray-500">|</span>
                                                                    <span class="font-semibold text-gray-900">Total: {{ number_format($item->total_price, 3) }} TND</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center py-8 text-gray-500">
                                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                    <p class="text-lg font-medium">No products found</p>
                                                    <p class="text-sm">This order doesn't have any items</p>
                                                </div>
                                            @endforelse
                                        </div>
                                        
                                        
                                        <!-- Staff Notes -->
                                        @if($order->staff_notes)
                                            <div class="mt-6 pt-4 border-t border-gray-200">
                                                <h5 class="text-md font-semibold text-gray-900 mb-2">Staff Notes</h5>
                                                <div class="text-sm text-gray-700 bg-yellow-50 p-3 rounded-lg">
                                                    {{ $order->staff_notes }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            {{ __('admin.orders.no_confirmed_orders_found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<script>
function toggleOrder(orderId) {
    const component = Alpine.$data(document.querySelector('[x-data]'));
    const index = component.selectedOrders.indexOf(orderId);
    
    if (index > -1) {
        component.selectedOrders.splice(index, 1);
    } else {
        component.selectedOrders.push(orderId);
    }
}

function printSelected() {
    const selectedOrders = Alpine.store('selectedOrders') || [];
    if (selectedOrders.length === 0) {
        alert('Please select orders first');
        return;
    }
    
    // Get print URLs for selected orders
    const printUrls = [];
    selectedOrders.forEach(orderId => {
        const row = document.querySelector(`input[value="${orderId}"]`).closest('tr');
        const printLink = row.querySelector('a[href^="http"]');
        if (printLink) {
            printUrls.push(printLink.href);
        }
    });
    
    // Open all print URLs in new tabs
    printUrls.forEach(url => {
        window.open(url, '_blank');
    });
}

// Initialize Alpine.js store
document.addEventListener('alpine:init', () => {
    Alpine.store('selectedOrders', []);
});

function toggleSummary() {
    const items = document.querySelectorAll('.product-summary-item');
    const toggleText = document.getElementById('summaryToggleText');
    const toggleIcon = document.getElementById('summaryToggleIcon');
    const isExpanded = !items[12]?.classList.contains('hidden');
    
    items.forEach((item, index) => {
        if (index >= 12) {
            if (isExpanded) {
                item.classList.add('hidden');
            } else {
                item.classList.remove('hidden');
            }
        }
    });
    
    if (isExpanded) {
        toggleText.textContent = 'Show All Products';
        toggleIcon.style.transform = 'rotate(0deg)';
    } else {
        toggleText.textContent = 'Show Less';
        toggleIcon.style.transform = 'rotate(180deg)';
    }
}
</script>

@include('admin.orders.partials.quick-view-modal')
@endsection
