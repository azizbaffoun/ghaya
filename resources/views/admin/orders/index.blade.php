@extends('admin.layout')

@section('title', __('admin.orders.title'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('admin.orders.title') }}</h1>
            <p class="mt-2 text-gray-600 mobile-hidden sm:block">{{ __('admin.orders.subtitle', [], 'Manage customer orders and tracking') }}</p>
        </div>
    </div>

    <!-- First Delivery Bulk Actions -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.delivery.first_delivery_actions') }}</h3>
        <div class="flex flex-col sm:flex-row gap-4">
            <form method="POST" action="{{ route('admin.delivery.bulk-sync') }}" class="inline">
                @csrf
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-3 rounded-xl font-medium shadow-lg shadow-purple-500/25 hover:shadow-xl transition-all duration-300 touch-button">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    {{ __('admin.delivery.bulk_sync') }}
                </button>
            </form>
            
            <button onclick="openPickupModal()" class="w-full sm:w-auto inline-flex items-center justify-center bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-xl font-medium shadow-lg shadow-green-500/25 hover:shadow-xl transition-all duration-300 touch-button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                {{ __('admin.delivery.request_pickup') }}
            </button>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.orders.orders_list') }}</h3>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.orders.order_number') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.orders.customer') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.orders.total') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.orders.status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.orders.date') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.delivery.first_delivery') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.common.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->customer_full_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
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
                                {{ __('admin.orders.statuses.' . $order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->isSyncedToFirstDelivery())
                                <div class="space-y-1">
                                    <div class="text-xs text-gray-600">
                                        ID: {{ $order->first_delivery_id }}
                                    </div>
                                    @if($order->first_delivery_tracking_number)
                                        <div class="text-xs text-blue-600">
                                            Track: {{ $order->first_delivery_tracking_number }}
                                        </div>
                                    @endif
                                    @if($order->first_delivery_status)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($order->first_delivery_status === 'delivered') bg-green-100 text-green-800
                                            @elseif($order->first_delivery_status === 'in_transit') bg-blue-100 text-blue-800
                                            @elseif($order->first_delivery_status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $order->first_delivery_status)) }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-gray-400">Not synced</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                    {{ __('admin.common.view') }}
                                </button>
                                
                                @if($order->isSyncedToFirstDelivery())
                                    <form method="POST" action="{{ route('admin.delivery.status', $order) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 transition-colors duration-200">
                                            {{ __('admin.delivery.check_status') }}
                                        </button>
                                    </form>
                                    
                                    @if(!in_array($order->first_delivery_status, ['delivered', 'cancelled']))
                                        <form method="POST" action="{{ route('admin.delivery.cancel', $order) }}" class="inline"
                                              onsubmit="return confirm('Are you sure you want to cancel this delivery?')">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <form method="POST" action="{{ route('admin.delivery.sync', $order) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-purple-600 hover:text-purple-900 transition-colors duration-200">
                                            Sync to FD
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            {{ __('admin.orders.no_orders') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden p-4 space-y-4">
            @forelse($orders as $order)
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-semibold text-gray-900">#{{ $order->order_number }}</h4>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ __('admin.orders.statuses.' . $order->status) }}
                    </span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Customer:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $order->customer_full_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Email:</span>
                        <a href="mailto:{{ $order->customer_email }}" class="text-sm text-blue-600">{{ $order->customer_email }}</a>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($order->total, 3) }} TND</span>
                    </div>
                    
                    @if($order->isSyncedToFirstDelivery())
                    <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                        <div class="text-xs text-gray-600 mb-1">{{ __('admin.delivery.first_delivery') }}</div>
                        <div class="text-xs text-gray-800">ID: {{ $order->first_delivery_id }}</div>
                        @if($order->first_delivery_tracking_number)
                        <div class="text-xs text-blue-600">{{ __('admin.delivery.track') }}: {{ $order->first_delivery_tracking_number }}</div>
                        @endif
                        @if($order->first_delivery_status)
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1
                            @if($order->first_delivery_status === 'delivered') bg-green-100 text-green-800
                            @elseif($order->first_delivery_status === 'in_transit') bg-blue-100 text-blue-800
                            @elseif($order->first_delivery_status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->first_delivery_status)) }}
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
                
                <div class="flex flex-col space-y-2">
                    <button class="w-full bg-blue-600 text-white text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        {{ __('admin.common.view') }}
                    </button>
                    
                    @if($order->isSyncedToFirstDelivery())
                        <form method="POST" action="{{ route('admin.delivery.status', $order) }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-green-100 text-green-700 text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-green-200 transition-colors">
                                Check Status
                            </button>
                        </form>
                        
                        @if(!in_array($order->first_delivery_status, ['delivered', 'cancelled']))
                        <form method="POST" action="{{ route('admin.delivery.cancel', $order) }}" class="w-full"
                              onsubmit="return confirm('Are you sure you want to cancel this delivery?')">
                            @csrf
                            <button type="submit" class="w-full bg-red-100 text-red-700 text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors">
                                            {{ __('admin.delivery.cancel_delivery') }}
                            </button>
                        </form>
                        @endif
                    @else
                        <form method="POST" action="{{ route('admin.delivery.sync', $order) }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-purple-100 text-purple-700 text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-purple-200 transition-colors">
                                            {{ __('admin.delivery.sync_to_first_delivery') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No orders</h3>
                <p class="mt-1 text-sm text-gray-500">Start by creating your first order.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Pickup Request Modal -->
<div id="pickupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-end sm:items-center justify-center min-h-screen p-0 sm:p-4">
        <div class="bg-white rounded-t-2xl sm:rounded-2xl shadow-xl w-full sm:max-w-md sm:w-full">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Request Pickup</h3>
                    <button onclick="closePickupModal()" class="sm:hidden p-2 rounded-lg text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.delivery.pickup') }}" class="p-4 sm:p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Date</label>
                        <input type="date" name="pickup_date" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Time</label>
                        <select name="pickup_time" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base">
                            <option value="09:00">9:00 AM</option>
                            <option value="10:00">10:00 AM</option>
                            <option value="11:00">11:00 AM</option>
                            <option value="12:00">12:00 PM</option>
                            <option value="13:00">1:00 PM</option>
                            <option value="14:00">2:00 PM</option>
                            <option value="15:00">3:00 PM</option>
                            <option value="16:00">4:00 PM</option>
                            <option value="17:00">5:00 PM</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea name="notes" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base"
                                  placeholder="Any special instructions for pickup..."></textarea>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-6">
                    <button type="button" onclick="closePickupModal()" 
                            class="w-full sm:w-auto px-4 py-3 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors duration-200 touch-button">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="w-full sm:w-auto px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200 touch-button">
                        Request Pickup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openPickupModal() {
    document.getElementById('pickupModal').classList.remove('hidden');
}

function closePickupModal() {
    document.getElementById('pickupModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('pickupModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePickupModal();
    }
});
</script>
@endsection
