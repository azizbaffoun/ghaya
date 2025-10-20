@extends('admin.layout')

@section('title', 'Order Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-xl sm:text-3xl font-bold text-gray-900 mobile-title-responsive">Order #{{ $order->order_number }}</h1>
            <p class="mt-2 text-gray-600 mobile-subtitle-hidden sm:block">Order details and management</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.orders.edit', $order) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Order
            </a>
            <a href="{{ route('admin.orders.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Orders
            </a>
        </div>
    </div>

    <!-- Order Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->customer_phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                        <p class="mt-1 text-sm text-gray-900 font-semibold">${{ number_format((float)$order->total, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Address Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ is_array($order->shipping_address) ? json_encode($order->shipping_address, JSON_PRETTY_PRINT) : $order->shipping_address }}</p>
                    </div>
                    @if($order->billing_address && $order->billing_address !== $order->shipping_address)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Billing Address</label>
                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ is_array($order->billing_address) ? json_encode($order->billing_address, JSON_PRETTY_PRINT) : $order->billing_address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            @if($order->orderItems && $order->orderItems->count() > 0)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Order Items</h3>
                <div class="space-y-3">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">{{ $item->product_name }}</h4>
                            @if($item->size || $item->color)
                            <p class="text-xs text-gray-500">
                                @if($item->size) Size: {{ $item->size }} @endif
                                @if($item->size && $item->color) | @endif
                                @if($item->color) Color: {{ $item->color }} @endif
                            </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">Qty: {{ $item->quantity }}</p>
                            <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Order Status -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Order Status</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status == 'delivered') bg-green-100 text-green-800
                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirmation Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($order->confirmation_status == 'pending_confirmation') bg-yellow-100 text-yellow-800
                            @elseif($order->confirmation_status == 'confirmed') bg-blue-100 text-blue-800
                            @elseif($order->confirmation_status == 'printed') bg-purple-100 text-purple-800
                            @elseif($order->confirmation_status == 'pickup_requested') bg-indigo-100 text-indigo-800
                            @elseif($order->confirmation_status == 'in_delivery') bg-orange-100 text-orange-800
                            @elseif($order->confirmation_status == 'delivered') bg-green-100 text-green-800
                            @elseif($order->confirmation_status == 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->confirmation_status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- First Delivery Status -->
            @if($order->barcode)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">First Delivery Status</h3>
                    <button onclick="checkFirstDeliveryStatus('{{ $order->barcode }}', {{ $order->id }})" 
                            id="checkStatusBtn-{{ $order->id }}"
                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span id="checkStatusText-{{ $order->id }}">Check Status</span>
                    </button>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Barcode</label>
                        <p class="mt-1 text-sm text-gray-900 font-mono">{{ $order->barcode }}</p>
                    </div>
                    <div id="fd-status-{{ $order->id }}">
                        <label class="block text-sm font-medium text-gray-700">First Delivery Status</label>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <svg class="animate-spin -ml-1 mr-2 h-3 w-3 text-gray-500 hidden" id="loading-{{ $order->id }}" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span id="fd-status-text-{{ $order->id }}">
                                    @if($order->first_delivery_response && isset($order->first_delivery_response['result']['state']))
                                        {{ ucfirst(str_replace('_', ' ', $order->first_delivery_response['result']['state'])) }}
                                    @else
                                        Click "Check Status" to get current status
                                    @endif
                                </span>
                            </span>
                        </div>
                    </div>
                    @if($order->print_url)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Print URL</label>
                        <a href="{{ $order->print_url }}" target="_blank" 
                           class="mt-1 text-sm text-blue-600 hover:text-blue-800 underline">
                            View Print Label
                        </a>
                    </div>
                    @endif
                    <div id="fd-last-checked-{{ $order->id }}" class="text-xs text-gray-500">
                        @if($order->first_delivery_response)
                            Last checked: {{ $order->updated_at->format('M d, Y H:i') }}
                        @endif
                    </div>
                    @if($order->first_delivery_response)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last API Response</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                            <pre class="text-xs text-gray-600 overflow-x-auto">{{ json_encode($order->first_delivery_response, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Order Details -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Order Details</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order Number</label>
                        <p class="mt-1 text-sm text-gray-900 font-mono">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created At</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Updated At</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    @if($order->confirmed_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirmed At</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->confirmed_at->format('M d, Y H:i') }}</p>
                    </div>
                    @endif
                    @if($order->printed_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Printed At</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->printed_at->format('M d, Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Notes</h3>
                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $order->notes }}</p>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.orders.edit', $order) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Order
                    </a>
                    
                    @if($order->status == 'pending' && $order->confirmation_status == 'pending_confirmation')
                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="w-full" onsubmit="return confirm('Are you sure you want to delete this order?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Order
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function checkFirstDeliveryStatus(barcode, orderId) {
    const button = document.getElementById(`checkStatusBtn-${orderId}`);
    const buttonText = document.getElementById(`checkStatusText-${orderId}`);
    const loadingIcon = document.getElementById(`loading-${orderId}`);
    const statusText = document.getElementById(`fd-status-text-${orderId}`);
    
    // Show loading state
    button.disabled = true;
    buttonText.textContent = 'Checking...';
    loadingIcon.classList.remove('hidden');
    
    // Make API call
    fetch(`/admin/orders/${orderId}/check-delivery-status`, {
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
            statusText.textContent = data.fd_status ? 
                data.fd_status.charAt(0).toUpperCase() + data.fd_status.slice(1).replace(/_/g, ' ') : 
                'Status updated';
            
            // Update status badge color based on First Delivery status
            const statusContainer = document.getElementById(`fd-status-${orderId}`);
            const statusBadge = statusContainer.querySelector('span');
            
            // Remove existing color classes
            statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
            
            // Add appropriate color class
            if (data.fd_status === 'pending') {
                statusBadge.classList.add('bg-yellow-100', 'text-yellow-800');
            } else if (data.fd_status === 'picked_up') {
                statusBadge.classList.add('bg-blue-100', 'text-blue-800');
            } else if (data.fd_status === 'in_transit') {
                statusBadge.classList.add('bg-purple-100', 'text-purple-800');
            } else if (data.fd_status === 'delivered') {
                statusBadge.classList.add('bg-green-100', 'text-green-800');
            } else if (data.fd_status === 'cancelled') {
                statusBadge.classList.add('bg-red-100', 'text-red-800');
            } else {
                statusBadge.classList.add('bg-gray-100', 'text-gray-800');
            }
            
            // Update last checked time
            const lastChecked = document.getElementById(`fd-last-checked-${orderId}`);
            if (lastChecked) {
                lastChecked.textContent = `Last checked: ${new Date().toLocaleString()}`;
            }
            
            // Show success message
            showNotification('Status updated successfully!', 'success');
        } else {
            showNotification(data.message || 'Failed to check status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to check status. Please try again.', 'error');
    })
    .finally(() => {
        // Reset button state
        button.disabled = false;
        buttonText.textContent = 'Check Status';
        loadingIcon.classList.add('hidden');
    });
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection