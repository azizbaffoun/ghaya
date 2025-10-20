@extends('admin.layout')

@section('title', __('admin.orders.in_delivery'))

@section('content')
<div class="space-y-6" x-data="{ selectedOrders: [], showBulkActions: false, orderStatus: {} }">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('admin.orders.in_delivery') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('admin.orders.in_delivery_subtitle') }}</p>
        </div>
        <div class="flex space-x-3">
            <button @click="refreshAllStatuses()" 
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                {{ __('admin.orders.refresh_all_statuses') }}
            </button>
            <button @click="showBulkActions = !showBulkActions" 
                    :class="showBulkActions ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                    class="px-4 py-2 rounded-lg transition-colors duration-200">
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
                <button @click="refreshSelectedStatuses()" 
                        :disabled="selectedOrders.length === 0"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white rounded-lg transition-colors duration-200">
                    {{ __('admin.orders.refresh_selected') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.orders.orders_in_delivery') }}</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" 
                                   @change="selectedOrders = selectedOrders.length === {{ $orders->count() }} ? [] : {{ $orders->pluck('id')->toJson() }}"
                                   :checked="selectedOrders.length === {{ $orders->count() }}"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.orders.order_number') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.orders.customer') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.orders.phone') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.orders.barcode') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.orders.delivery_status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.orders.last_updated') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.orders.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
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
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div x-data="{ status: null, loading: false }" 
                                 x-init="checkStatus('{{ $order->barcode }}', '{{ $order->id }}')">
                                <div x-show="loading" class="text-sm text-gray-500">
                                    <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ __('admin.orders.checking') }}
                                </div>
                                <div x-show="!loading && status" class="text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                          :class="{
                                              'bg-yellow-100 text-yellow-800': status.state === 'En attente',
                                              'bg-blue-100 text-blue-800': status.state === 'En cours',
                                              'bg-green-100 text-green-800': status.state === 'Livré',
                                              'bg-red-100 text-red-800': status.state === 'Retour Expéditeur',
                                              'bg-gray-100 text-gray-800': !['En attente', 'En cours', 'Livré', 'Retour Expéditeur'].includes(status.state)
                                          }">
                                        <span x-text="status.state"></span>
                                    </span>
                                </div>
                                <div x-show="!loading && !status" class="text-sm text-gray-400">
                                    {{ __('admin.orders.no_status') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->pickup_requested_at ? $order->pickup_requested_at->format('M d, Y H:i') : __('admin.common.n_a') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <!-- Check Status Button -->
                                <button onclick="checkOrderStatus('{{ $order->barcode }}', {{ $order->id }})" 
                                        class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-colors duration-200">
                                    {{ __('admin.orders.check_status') }}
                                </button>
                                
                                <!-- Mark as Delivered Button -->
                                <button onclick="markAsDelivered({{ $order->id }})" 
                                        class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded-lg transition-colors duration-200">
                                    {{ __('admin.orders.mark_delivered') }}
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
                            {{ __('admin.orders.no_orders_in_delivery') }}
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

function checkOrderStatus(barcode, orderId) {
    if (!barcode) {
        alert('{{ __("admin.orders.js_messages.no_barcode_available") }}');
        return;
    }
    
    // Show loading state
    const statusElement = document.querySelector(`[data-order-id="${orderId}"]`);
    if (statusElement) {
        statusElement.querySelector('.loading').style.display = 'block';
        statusElement.querySelector('.status').style.display = 'none';
    }
    
    // Make API call to check status
    fetch(`/admin/orders/check-status/${barcode}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update status display
            if (statusElement) {
                statusElement.querySelector('.loading').style.display = 'none';
                statusElement.querySelector('.status').style.display = 'block';
                statusElement.querySelector('.status-text').textContent = data.status.state;
            }
        } else {
            alert('{{ __("admin.orders.js_messages.failed_to_update_status") }}: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __("admin.orders.js_messages.failed_to_update_status") }}');
    });
}

function refreshAllStatuses() {
    const checkButtons = document.querySelectorAll('button[onclick^="checkOrderStatus"]');
    checkButtons.forEach(button => {
        button.click();
    });
}

function refreshSelectedStatuses() {
    const selectedOrders = Alpine.store('selectedOrders') || [];
    selectedOrders.forEach(orderId => {
        const row = document.querySelector(`input[value="${orderId}"]`).closest('tr');
        const checkButton = row.querySelector('button[onclick^="checkOrderStatus"]');
        if (checkButton) {
            checkButton.click();
        }
    });
}

function markAsDelivered(orderId) {
    if (confirm('{{ __("admin.orders.js_messages.mark_delivered_confirm") }}')) {
        fetch(`/admin/orders/${orderId}/mark-delivered`, {
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
                alert(data.message || '{{ __("admin.orders.js_messages.failed_to_mark_delivered") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("admin.orders.js_messages.failed_to_mark_delivered") }}');
        });
    }
}

// Initialize Alpine.js store
document.addEventListener('alpine:init', () => {
    Alpine.store('selectedOrders', []);
});

// Auto-refresh is handled by order-management.js
</script>
@endsection
