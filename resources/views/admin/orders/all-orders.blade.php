@extends('admin.layout')

@section('title', __('admin.orders.all_orders'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-xl sm:text-3xl font-bold text-gray-900 mobile-title-responsive">{{ __('admin.orders.all_orders') }}</h1>
            <p class="mt-2 text-gray-600 mobile-subtitle-hidden sm:block">{{ __('admin.orders.all_orders_subtitle') }}</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6">
        <form method="GET" class="space-y-4">
            <!-- Mobile Collapsible Filters -->
            <div class="lg:hidden mb-4">
                <button type="button" onclick="toggleMobileFilters()" class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <span class="font-medium text-gray-700">{{ __('admin.orders.filters') }}</span>
                    <svg id="filter-arrow" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div id="mobile-filters" class="hidden lg:block form-grid">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.orders.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ __('admin.orders.search_placeholder') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base">
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.orders.status') }}</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base">
                        <option value="">{{ __('admin.orders.all_statuses') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('admin.orders.statuses.pending') }}</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('admin.orders.statuses.processing') }}</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>{{ __('admin.orders.statuses.shipped') }}</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>{{ __('admin.orders.statuses.delivered') }}</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('admin.orders.statuses.cancelled') }}</option>
                    </select>
                </div>
                
                <!-- Confirmation Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.orders.confirmation_status') }}</label>
                    <select name="confirmation_status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base">
                        <option value="">{{ __('admin.orders.all_confirmation_statuses') }}</option>
                        <option value="pending_confirmation" {{ request('confirmation_status') == 'pending_confirmation' ? 'selected' : '' }}>{{ __('admin.orders.confirmation_statuses.pending_confirmation') }}</option>
                        <option value="confirmed" {{ request('confirmation_status') == 'confirmed' ? 'selected' : '' }}>{{ __('admin.orders.confirmation_statuses.confirmed') }}</option>
                        <option value="printed" {{ request('confirmation_status') == 'printed' ? 'selected' : '' }}>{{ __('admin.orders.confirmation_statuses.printed') }}</option>
                        <option value="pickup_requested" {{ request('confirmation_status') == 'pickup_requested' ? 'selected' : '' }}>{{ __('admin.orders.confirmation_statuses.pickup_requested') }}</option>
                        <option value="in_delivery" {{ request('confirmation_status') == 'in_delivery' ? 'selected' : '' }}>{{ __('admin.orders.confirmation_statuses.in_delivery') }}</option>
                        <option value="delivered" {{ request('confirmation_status') == 'delivered' ? 'selected' : '' }}>{{ __('admin.orders.confirmation_statuses.delivered') }}</option>
                        <option value="cancelled" {{ request('confirmation_status') == 'cancelled' ? 'selected' : '' }}>{{ __('admin.orders.confirmation_statuses.cancelled') }}</option>
                    </select>
                </div>
                
                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.orders.date_range') }}</label>
                    <div class="flex space-x-2">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base">
                    </div>
                </div>
            </div>
            
                <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('admin.orders.index') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-gray-700 bg-white border border-gray-300 hover:border-gray-400 rounded-xl font-medium shadow-sm hover:shadow-md transition-all duration-300 touch-button text-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        {{ __('admin.orders.clear_filters') }}
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium shadow-lg shadow-blue-500/25 hover:shadow-xl transition-all duration-300 touch-button">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        {{ __('admin.orders.search') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.orders.orders_list') }}</h3>
        </div>
        
        <!-- Desktop Table -->
        <div class="hidden lg:block table-container">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
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
                            {{ __('admin.orders.status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                            {{ __('admin.orders.confirmation_status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                            {{ __('admin.orders.date') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-80">
                            {{ __('admin.orders.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 cursor-pointer" 
                        onclick="openQuickView({
                            id: {{ $order->id }},
                            order_number: '{{ $order->order_number }}',
                            customer_first_name: '{{ $order->customer_first_name }}',
                            customer_last_name: '{{ $order->customer_last_name }}',
                            customer_email: '{{ $order->customer_email }}',
                            customer_phone: '{{ $order->customer_phone }}',
                            shipping_address: '{{ $order->shipping_address }}',
                            shipping_city: '{{ $order->shipping_city }}',
                            shipping_state: '{{ $order->shipping_state }}',
                            shipping_postal_code: '{{ $order->shipping_postal_code }}',
                            shipping_country: '{{ $order->shipping_country }}',
                            subtotal: {{ $order->subtotal }},
                            shipping_cost: {{ $order->shipping_cost }},
                            total: {{ $order->total }},
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
                                <span class="text-gray-400">{{ __('admin.common.n_a') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($order->total, 3) }} TND
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($order->confirmation_status === 'pending_confirmation') bg-yellow-100 text-yellow-800
                                @elseif($order->confirmation_status === 'confirmed') bg-green-100 text-green-800
                                @elseif($order->confirmation_status === 'printed') bg-blue-100 text-blue-800
                                @elseif($order->confirmation_status === 'pickup_requested') bg-purple-100 text-purple-800
                                @elseif($order->confirmation_status === 'in_delivery') bg-indigo-100 text-indigo-800
                                @elseif($order->confirmation_status === 'delivered') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->confirmation_status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium table-actions">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-medium rounded-xl shadow-md shadow-blue-500/25 hover:shadow-lg transition-all duration-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ __('admin.orders.view') }}
                                </a>
                                <button onclick="openNotesModal({{ $order->id }}, '{{ $order->staff_notes }}')" 
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-sm font-medium rounded-xl shadow-md shadow-green-500/25 hover:shadow-lg transition-all duration-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ __('admin.common.notes') }}
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            {{ __('admin.orders.no_orders_found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden p-4 space-y-4">
            @forelse($orders as $order)
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 cursor-pointer" 
                 onclick="openQuickView({
                     id: {{ $order->id }},
                     order_number: '{{ $order->order_number }}',
                     customer_first_name: '{{ $order->customer_first_name }}',
                     customer_last_name: '{{ $order->customer_last_name }}',
                     customer_email: '{{ $order->customer_email }}',
                     customer_phone: '{{ $order->customer_phone }}',
                     shipping_address: '{{ $order->shipping_address }}',
                     shipping_city: '{{ $order->shipping_city }}',
                     shipping_state: '{{ $order->shipping_state }}',
                     shipping_postal_code: '{{ $order->shipping_postal_code }}',
                     shipping_country: '{{ $order->shipping_country }}',
                     subtotal: {{ $order->subtotal }},
                     shipping_cost: {{ $order->shipping_cost }},
                     total: {{ $order->total }},
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
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-semibold text-gray-900">#{{ $order->order_number }}</h4>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('admin.orders.customer') }}:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $order->customer_full_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('admin.orders.customer_email') }}:</span>
                        <a href="mailto:{{ $order->customer_email }}" class="text-sm text-blue-600">{{ $order->customer_email }}</a>
                    </div>
                    @if($order->customer_phone)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('admin.orders.phone') }}:</span>
                        <a href="tel:{{ $order->customer_phone }}" class="text-sm text-blue-600">{{ $order->formatted_phone }}</a>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('admin.orders.total') }}:</span>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($order->total, 3) }} TND</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('admin.orders.confirmation_status') }}:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($order->confirmation_status === 'pending_confirmation') bg-yellow-100 text-yellow-800
                            @elseif($order->confirmation_status === 'confirmed') bg-green-100 text-green-800
                            @elseif($order->confirmation_status === 'printed') bg-blue-100 text-blue-800
                            @elseif($order->confirmation_status === 'pickup_requested') bg-purple-100 text-purple-800
                            @elseif($order->confirmation_status === 'in_delivery') bg-indigo-100 text-indigo-800
                            @elseif($order->confirmation_status === 'delivered') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->confirmation_status)) }}
                        </span>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('admin.orders.show', $order) }}" 
                       class="flex-1 inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-center py-3 px-4 rounded-xl text-sm font-medium shadow-md shadow-blue-500/25 hover:shadow-lg transition-all duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ __('admin.orders.view') }}
                    </a>
                    <button onclick="openNotesModal({{ $order->id }}, '{{ $order->staff_notes }}')" 
                            class="flex-1 inline-flex items-center justify-center bg-gradient-to-r from-green-100 to-green-200 hover:from-green-200 hover:to-green-300 text-green-700 text-center py-3 px-4 rounded-xl text-sm font-medium shadow-sm hover:shadow-md transition-all duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('admin.common.notes') }}
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('admin.orders.no_orders_found') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('admin.orders.try_adjusting_search') }}</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Notes Modal -->
<div id="notesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-end sm:items-center justify-center min-h-screen p-0 sm:p-4">
        <div class="bg-white rounded-t-2xl sm:rounded-2xl shadow-xl w-full sm:max-w-md sm:w-full">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.orders.modals.order_notes') }}</h3>
                    <button onclick="closeNotesModal()" class="sm:hidden p-2 rounded-lg text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <form id="notesForm" method="POST" class="p-4 sm:p-6">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.orders.modals.staff_notes') }}</label>
                    <textarea name="staff_notes" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                              placeholder="{{ __('admin.orders.modals.add_internal_notes') }}"></textarea>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-6">
                    <button type="button" onclick="closeNotesModal()" 
                            class="w-full sm:w-auto px-4 py-3 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors duration-200 touch-button text-center">
                        {{ __('admin.orders.modals.cancel') }}
                    </button>
                    <button type="submit" 
                            class="w-full sm:w-auto px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 touch-button">
                        {{ __('admin.orders.modals.save_notes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openNotesModal(orderId, currentNotes) {
    document.getElementById('notesForm').action = `/admin/orders/${orderId}/notes`;
    document.querySelector('textarea[name="staff_notes"]').value = currentNotes || '';
    document.getElementById('notesModal').classList.remove('hidden');
}

function closeNotesModal() {
    document.getElementById('notesModal').classList.add('hidden');
}

// toggleMobileFilters function is now globally available from mobile-enhancements.js

// Close modal when clicking outside
document.getElementById('notesModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeNotesModal();
    }
});
</script>

@include('admin.orders.partials.quick-view-modal')
@endsection
