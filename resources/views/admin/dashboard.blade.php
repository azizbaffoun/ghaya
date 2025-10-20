@extends('admin.layout')

@section('title', __('admin.dashboard.title'))

{{-- Cache bust: {{ time() }} --}}

@section('content')
<div class="space-y-6" data-page-title="{{ __('admin.dashboard.title') }}" data-page-subtitle="{{ __('admin.dashboard.subtitle') }}">
    <!-- Welcome Section -->
    <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100 card-hover">
        <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 px-4 sm:px-8 py-4 sm:py-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 sm:ml-6 min-w-0 flex-1">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white truncate">{{ __('admin.dashboard.welcome') }}</h1>
                    <p class="mt-1 sm:mt-2 text-blue-100 text-sm sm:text-base lg:text-lg truncate">{{ __('admin.dashboard.title') }} - {{ config('app.name') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Metrics Cards -->
    <!-- Mobile: 2 columns, Desktop: 4 columns -->
    <div>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-2 lg:grid-cols-4">
        <!-- Total Products -->
        <div class="relative box-border flex h-full min-w-0 flex-col justify-start gap-4 overflow-hidden rounded-xl border mobile-p-6 p-4 md:p-6 shadow bg-white">
            <div class="relative text-gray-600 mobile-text-base text-md text-nowrap font-semibold">
                {{ __('admin.metrics.total_products') }}
            </div>
            <div class="relative min-w-40">
                <div class="text-gray-900 w-full text-nowrap mobile-text-3xl text-2xl font-bold">
                    {{ number_format($metrics['total_products']) }}
                </div>
            </div>
        </div>

        <!-- Active Orders -->
        <div class="relative box-border flex h-full min-w-0 flex-col justify-start gap-4 overflow-hidden rounded-xl border mobile-p-6 p-4 md:p-6 shadow bg-white">
            <div class="relative text-gray-600 mobile-text-base text-md text-nowrap font-semibold">
                {{ __('admin.metrics.active_orders') }}
            </div>
            <div class="relative min-w-40">
                <div class="text-gray-900 w-full text-nowrap mobile-text-3xl text-2xl font-bold">
                    {{ number_format($metrics['active_orders']) }}
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="relative box-border flex h-full min-w-0 flex-col justify-start gap-4 overflow-hidden rounded-xl border mobile-p-6 p-4 md:p-6 shadow bg-white">
            <div class="relative text-gray-600 mobile-text-base text-md text-nowrap font-semibold">
                {{ __('admin.metrics.pending_orders') }}
            </div>
            <div class="relative min-w-40">
                <div class="text-gray-900 w-full text-nowrap mobile-text-3xl text-2xl font-bold">
                    {{ number_format($metrics['pending_orders']) }}
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="relative box-border flex h-full min-w-0 flex-col justify-start gap-4 overflow-hidden rounded-xl border mobile-p-6 p-4 md:p-6 shadow bg-gradient-to-r from-purple-600 to-indigo-600">
            <div class="absolute -left-24 -top-28 z-0 h-72 w-96 rounded-full" style="background: linear-gradient(to left bottom, rgb(106, 46, 178) 0%, rgb(96, 45, 154) 60%);"></div>
            <div class="relative text-white mobile-text-base text-md text-nowrap font-semibold">
                {{ __('admin.dashboard.total_revenue') }}
            </div>
            <div class="relative min-w-40">
                <div class="text-white w-full text-nowrap mobile-text-3xl text-2xl font-bold">
                    {{ number_format($metrics['total_revenue'], 2) }} TND
                </div>
            </div>
        </div>

        <!-- New Customers -->
        <div class="relative box-border flex h-full min-w-0 flex-col justify-start gap-4 overflow-hidden rounded-xl border mobile-p-6 p-4 md:p-6 shadow bg-white">
            <div class="relative text-gray-600 mobile-text-base text-md text-nowrap font-semibold">
                {{ __('admin.dashboard.new_customers') }}
            </div>
            <div class="relative min-w-40">
                <div class="text-gray-900 w-full text-nowrap mobile-text-3xl text-2xl font-bold">
                    {{ number_format($metrics['new_customers']) }}
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="relative box-border flex h-full min-w-0 flex-col justify-start gap-4 overflow-hidden rounded-xl border mobile-p-6 p-4 md:p-6 shadow bg-white">
            <div class="relative text-gray-600 mobile-text-base text-md text-nowrap font-semibold">
                {{ __('admin.dashboard.low_stock_alerts') }}
            </div>
            <div class="relative min-w-40">
                <div class="text-gray-900 w-full text-nowrap mobile-text-3xl text-2xl font-bold">
                    {{ number_format($metrics['low_stock_products']) }}
                </div>
            </div>
        </div>

        <!-- Ready for Pickup Orders -->
        <div class="relative box-border flex h-full min-w-0 flex-col justify-start gap-4 overflow-hidden rounded-xl border mobile-p-6 p-4 md:p-6 shadow bg-white">
            <div class="relative text-gray-600 mobile-text-base text-md text-nowrap font-semibold">
                {{ __('admin.dashboard.ready_for_pickup') }}
            </div>
            <div class="relative min-w-40">
                <div class="text-gray-900 w-full text-nowrap mobile-text-3xl text-2xl font-bold">
                    {{ number_format($metrics['ready_for_pickup_orders']) }}
                </div>
            </div>
        </div>

        <!-- In Delivery Orders -->
        <div class="relative box-border flex h-full min-w-0 flex-col justify-start gap-4 overflow-hidden rounded-xl border mobile-p-6 p-4 md:p-6 shadow bg-white">
            <div class="relative text-gray-600 mobile-text-base text-md text-nowrap font-semibold">
                {{ __('admin.dashboard.in_delivery') }}
            </div>
            <div class="relative min-w-40">
                <div class="text-gray-900 w-full text-nowrap mobile-text-3xl text-2xl font-bold">
                    {{ number_format($metrics['in_delivery_orders']) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6">
        <form method="GET" class="space-y-4">
            <!-- Mobile Collapsible Filters -->
            <div class="lg:hidden mb-4">
                <button type="button" onclick="toggleMobileFilters()" class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <span class="font-medium text-gray-700">Filters</span>
                    <svg id="filter-arrow" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div id="mobile-filters" class="hidden lg:block space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.common.search') }}</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="{{ __('admin.products.search_placeholder') }}"
                               class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base">
                        <button type="button" id="clear-search" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base">
                        <option value="">{{ __('admin.products.all_statuses') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('admin.orders.statuses.pending') }}</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('admin.orders.statuses.processing') }}</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>{{ __('admin.orders.statuses.shipped') }}</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>{{ __('admin.orders.statuses.delivered') }}</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('admin.orders.statuses.cancelled') }}</option>
                    </select>
                </div>
                
                <!-- Confirmation Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmation Status</label>
                    <select name="confirmation_status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base">
                        <option value="">{{ __('admin.products.all_confirmation_statuses') }}</option>
                        <option value="pending_confirmation" {{ request('confirmation_status') == 'pending_confirmation' ? 'selected' : '' }}>Pending Confirmation</option>
                        <option value="confirmed" {{ request('confirmation_status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="printed" {{ request('confirmation_status') == 'printed' ? 'selected' : '' }}>Printed</option>
                        <option value="pickup_requested" {{ request('confirmation_status') == 'pickup_requested' ? 'selected' : '' }}>Pickup Requested</option>
                        <option value="in_delivery" {{ request('confirmation_status') == 'in_delivery' ? 'selected' : '' }}>In Delivery</option>
                        <option value="delivered" {{ request('confirmation_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('confirmation_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                    <div class="flex space-x-2">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base">
                    </div>
                </div>
            </div>
            
                <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="w-full sm:w-auto px-4 py-3 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors duration-200 touch-button text-center">
                        {{ __('admin.products.clear_filters') }}
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 touch-button">
                        {{ __('admin.common.search') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('admin.dashboard.recent_orders') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 mobile-hidden sm:block">{{ __('admin.dashboard.recent_orders_description') }}</p>
                </div>
                <div id="results-count" class="text-sm text-gray-500 hidden">
                    <!-- Results count will be displayed here -->
                </div>
            </div>
        </div>
        
        <div id="orders-results-container">
        @if($recentOrders->count() > 0)
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.orders.order_number') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.customers.first_name') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.customers.last_name') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.customers.address') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.customers.phone') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.orders.status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.orders.date') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.orders.total') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.common.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50 cursor-pointer" 
                                onclick="openQuickView({
                                    id: {{ $order->id }},
                                    order_number: '{{ $order->order_number }}',
                                    customer_first_name: '{{ $order->customer_first_name }}',
                                    customer_last_name: '{{ $order->customer_last_name }}',
                                    customer_email: '{{ $order->customer_email }}',
                                    customer_phone: '{{ $order->customer_phone }}',
                                    shipping_address: '{{ is_array($order->shipping_address) ? json_encode($order->shipping_address) : $order->shipping_address }}',
                                    shipping_city: '{{ $order->shipping_city ?? '' }}',
                                    shipping_state: '{{ $order->shipping_state ?? '' }}',
                                    shipping_postal_code: '{{ $order->shipping_postal_code ?? '' }}',
                                    shipping_country: '{{ $order->shipping_country ?? '' }}',
                                    subtotal: {{ $order->subtotal }},
                                    shipping_cost: {{ $order->shipping_cost }},
                                    total: {{ $order->total }},
                                    status: '{{ $order->status }}',
                                    confirmation_status: '{{ $order->confirmation_status }}',
                                    order_items: {!! $order->orderItems->map(function($item) {
                                        return [
                                            'id' => $item->id,
                                            'product_name' => $item->product_name,
                                            'variant_name' => $item->variant_name,
                                            'quantity' => $item->quantity,
                                            'unit_price' => $item->unit_price,
                                            'total_price' => $item->total_price
                                        ];
                                    })->toJson() !!}
                                })">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <span class="text-indigo-600 hover:text-indigo-900">
                                        {{ $order->order_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->customer_first_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->customer_last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ is_array($order->shipping_address) ? Str::limit(json_encode($order->shipping_address), 30) : Str::limit($order->shipping_address, 30) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->customer_phone ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-green-100 text-green-800',
                                            'declined' => 'bg-red-100 text-red-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'shipped' => 'bg-purple-100 text-purple-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ __('admin.orders.statuses.' . $order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($order->total, 2) }} TND
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="event.stopPropagation(); openQuickView({
                                        id: {{ $order->id }},
                                        order_number: '{{ $order->order_number }}',
                                        customer_first_name: '{{ $order->customer_first_name }}',
                                        customer_last_name: '{{ $order->customer_last_name }}',
                                        customer_email: '{{ $order->customer_email }}',
                                        customer_phone: '{{ $order->customer_phone }}',
                                        shipping_address: '{{ is_array($order->shipping_address) ? json_encode($order->shipping_address) : $order->shipping_address }}',
                                        shipping_city: '{{ $order->shipping_city ?? '' }}',
                                        shipping_state: '{{ $order->shipping_state ?? '' }}',
                                        shipping_postal_code: '{{ $order->shipping_postal_code ?? '' }}',
                                        shipping_country: '{{ $order->shipping_country ?? '' }}',
                                        subtotal: {{ $order->subtotal }},
                                        shipping_cost: {{ $order->shipping_cost }},
                                        total: {{ $order->total }},
                                        status: '{{ $order->status }}',
                                        confirmation_status: '{{ $order->confirmation_status }}',
                                        order_items: {!! $order->orderItems->map(function($item) {
                                            return [
                                                'id' => $item->id,
                                                'product_name' => $item->product_name,
                                                'variant_name' => $item->variant_name,
                                                'quantity' => $item->quantity,
                                                'unit_price' => $item->unit_price,
                                                'total_price' => $item->total_price
                                            ];
                                        })->toJson() !!}
                                    })" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        {{ __('admin.common.view') }}
                                    </button>
                                    <a href="#" class="text-gray-600 hover:text-gray-900">
                                        {{ __('admin.common.edit') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden space-y-4 p-4">
                @foreach($recentOrders as $order)
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900">#{{ $order->order_number }}</h4>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-green-100 text-green-800',
                                    'declined' => 'bg-red-100 text-red-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'shipped' => 'bg-purple-100 text-purple-800',
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ __('admin.orders.statuses.' . $order->status) }}
                            </span>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">{{ __('admin.common.customer_label') }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $order->customer_first_name }} {{ $order->customer_last_name }}</span>
                            </div>
                            @if($order->customer_phone)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">{{ __('admin.common.phone_label') }}</span>
                                <a href="tel:{{ $order->customer_phone }}" class="text-sm text-blue-600">{{ $order->customer_phone }}</a>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">{{ __('admin.common.total_label') }}</span>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($order->total, 2) }} TND</span>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button onclick="openQuickView({
                                id: {{ $order->id }},
                                order_number: '{{ $order->order_number }}',
                                customer_first_name: '{{ $order->customer_first_name }}',
                                customer_last_name: '{{ $order->customer_last_name }}',
                                customer_email: '{{ $order->customer_email }}',
                                customer_phone: '{{ $order->customer_phone }}',
                                shipping_address: '{{ is_array($order->shipping_address) ? json_encode($order->shipping_address) : $order->shipping_address }}',
                                shipping_city: '{{ $order->shipping_city ?? '' }}',
                                shipping_state: '{{ $order->shipping_state ?? '' }}',
                                shipping_postal_code: '{{ $order->shipping_postal_code ?? '' }}',
                                shipping_country: '{{ $order->shipping_country ?? '' }}',
                                subtotal: {{ $order->subtotal }},
                                shipping_cost: {{ $order->shipping_cost }},
                                total: {{ $order->total }},
                                status: '{{ $order->status }}',
                                confirmation_status: '{{ $order->confirmation_status }}',
                                order_items: {!! $order->orderItems->map(function($item) {
                                    return [
                                        'id' => $item->id,
                                        'product_name' => $item->product_name,
                                        'variant_name' => $item->variant_name,
                                        'quantity' => $item->quantity,
                                        'unit_price' => $item->unit_price,
                                        'total_price' => $item->total_price
                                    ];
                                })->toJson() !!}
                            })" class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                {{ __('admin.common.view') }}
                            </button>
                            <a href="#" class="flex-1 bg-gray-200 text-gray-800 text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors">
                                {{ __('admin.common.edit') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('admin.dashboard.no_orders') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('admin.dashboard.no_orders_message') }}</p>
            </div>
        @endif
        </div>
    </div>
</div>

<script>
// Global function to open quick view modal
function openQuickView(orderData) {
    window.dispatchEvent(new CustomEvent('open-quick-view', { detail: orderData }));
}

// Mobile filters toggle function
function toggleMobileFilters() {
    const filters = document.getElementById('mobile-filters');
    const arrow = document.getElementById('filter-arrow');
    
    if (filters && arrow) {
        if (filters.classList.contains('hidden')) {
            filters.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            filters.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }
}

// Make the function globally available
window.toggleMobileFilters = toggleMobileFilters;
</script>

@include('admin.orders.partials.quick-view-modal')
@endsection
