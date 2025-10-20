@extends('admin.layout')

@section('title', __('admin.orders.ready_for_pickup'))

@section('content')
<div class="space-y-6" x-data="{ selectedOrders: [], showBulkActions: false }">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="mobile-text-2xl text-3xl font-bold text-gray-900">{{ __('admin.orders.ready_for_pickup') }}</h1>
            <p class="mt-2 mobile-text-base text-gray-600">{{ __('admin.orders.ready_for_pickup_subtitle') }}</p>
        </div>
        <div class="flex space-x-3">
            <button @click="showBulkActions = !showBulkActions" 
                    :class="showBulkActions ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-500/25' : 'bg-white text-gray-700 border border-gray-300 hover:border-gray-400 shadow-sm'"
                    class="px-6 py-3 rounded-xl font-medium transition-all duration-300 hover:shadow-md">
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
                <form method="POST" action="{{ route('admin.orders.bulk-pickup') }}" 
                      @submit="if(selectedOrders.length === 0) { event.preventDefault(); alert('Please select orders first'); }">
                    @csrf
                    <input type="hidden" name="order_ids" :value="JSON.stringify(selectedOrders)">
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200">
                        {{ __('admin.orders.request_pickup') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.orders.printed_orders_ready') }}</h3>
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
                            {{ __('admin.orders.barcode') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            {{ __('admin.orders.total') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                            {{ __('admin.orders.printed_at') }}
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
                                <span class="text-gray-400">{{ __('admin.common.n_a') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->barcode)
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-mono bg-gray-100 text-gray-800">
                                    {{ $order->barcode }}
                                </span>
                            @else
                                <span class="text-gray-400">{{ __('admin.orders.no_barcode') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($order->total, 3) }} TND
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->printed_at ? $order->printed_at->format('M d, Y H:i') : __('admin.common.n_a') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <!-- Request Pickup Button -->
                                <button onclick="quickPickup({{ $order->id }})" 
                                        class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded-lg transition-colors duration-200">
                                    {{ __('admin.orders.request_pickup') }}
                                </button>
                                
                                <!-- Mark as In Delivery Button -->
                                <button onclick="markAsInDelivery({{ $order->id }})" 
                                        class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-colors duration-200">
                                    {{ __('admin.orders.mark_in_delivery') }}
                                </button>
                                
                                <!-- View Details Button -->
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs rounded-lg transition-colors duration-200">
                                    {{ __('admin.orders.view') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            {{ __('admin.orders.no_orders_ready') }}
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

<!-- Already Picked Up Modal -->
<div id="pickedUpModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.orders.modals.mark_as_picked_up') }}</h3>
            </div>
            
            <form id="pickedUpForm" method="POST" class="p-6">
                @csrf
                @method('PATCH')
                <div>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ __('admin.orders.modals.mark_as_picked_up_description') }}
                    </p>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.orders.modals.notes_optional') }}</label>
                    <textarea name="staff_notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="{{ __('admin.orders.modals.add_pickup_notes') }}"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePickedUpModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors duration-200">
                        {{ __('admin.orders.modals.cancel') }}
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        {{ __('admin.orders.modals.mark_as_picked_up_btn') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleOrder(orderId) {
    if (typeof Alpine === 'undefined') {
        console.error('Alpine.js not loaded');
        return;
    }
    
    const selectedOrders = Alpine.store('selectedOrders') || [];
    const index = selectedOrders.indexOf(orderId);
    
    if (index > -1) {
        selectedOrders.splice(index, 1);
    } else {
        selectedOrders.push(orderId);
    }
    
    Alpine.store('selectedOrders', selectedOrders);
}

function quickPickup(orderId) {
    if (confirm('{{ __("admin.orders.js_messages.request_pickup_confirm") }}')) {
        fetch(`/admin/orders/${orderId}/quick-pickup`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message || '{{ __("admin.orders.js_messages.failed_to_request_pickup") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("admin.orders.js_messages.failed_to_request_pickup") }}');
        });
    }
}

function markAsInDelivery(orderId) {
    if (confirm('{{ __("admin.orders.js_messages.mark_in_delivery_confirm") }}')) {
        fetch(`/admin/orders/${orderId}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                status: 'processing',
                confirmation_status: 'in_delivery'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('{{ __("admin.orders.js_messages.order_marked_in_delivery") }}');
                location.reload();
            } else {
                alert(data.message || '{{ __("admin.orders.js_messages.failed_to_update_status") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("admin.orders.js_messages.failed_to_update_status") }}');
        });
    }
}

function closePickedUpModal() {
    document.getElementById('pickedUpModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('pickedUpModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePickedUpModal();
    }
});

// Initialize Alpine.js store
document.addEventListener('alpine:init', () => {
    Alpine.store('selectedOrders', []);
});
</script>

@include('admin.orders.partials.quick-view-modal')
@endsection
