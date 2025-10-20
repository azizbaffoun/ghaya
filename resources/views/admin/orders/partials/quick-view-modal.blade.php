<!-- Quick View Modal -->
<div id="quickViewModal" 
     x-data="quickViewModal()"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="close()"></div>
    
    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold">Order Details</h3>
                        <p class="text-blue-100" x-text="order ? '#' + order.order_number : ''"></p>
                    </div>
                    <button @click="close()" 
                            class="p-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                <template x-if="order">
                    <div class="space-y-6">
                        <!-- Customer Information -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Customer Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Name</p>
                                    <p class="font-medium text-gray-900" x-text="order ? order.customer_first_name + ' ' + order.customer_last_name : ''"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Phone</p>
                                    <a :href="order ? 'tel:' + order.customer_phone : '#'" 
                                       class="font-medium text-blue-600 hover:text-blue-800 text-lg"
                                       x-text="order ? order.customer_phone : ''"></a>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-600">Email</p>
                                    <a :href="order ? 'mailto:' + order.customer_email : '#'" 
                                       class="font-medium text-blue-600 hover:text-blue-800"
                                       x-text="order ? order.customer_email : ''"></a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Shipping Address -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Shipping Address
                            </h4>
                            <div class="text-gray-700">
                                <p class="font-medium" x-text="order ? order.shipping_address : ''"></p>
                                <p x-text="order ? order.shipping_city + ', ' + order.shipping_state + ' ' + order.shipping_postal_code : ''"></p>
                                <p x-text="order ? order.shipping_country : ''"></p>
                            </div>
                        </div>
                        
                        <!-- Order Items -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Order Items
                            </h4>
                            <div class="space-y-3">
                                <template x-for="item in order.order_items" :key="item.id">
                                    <div class="flex justify-between items-center bg-white rounded-lg p-3">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900" x-text="item.product_name"></p>
                                            <p class="text-sm text-gray-600" x-text="item.variant_name || 'Standard'"></p>
                                            <p class="text-sm text-gray-500">Qty: <span x-text="item.quantity"></span></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium text-gray-900" x-text="parseFloat(item.unit_price).toFixed(3) + ' TND'"></p>
                                            <p class="text-sm text-gray-600" x-text="'Total: ' + parseFloat(item.total_price).toFixed(3) + ' TND'"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Order Summary
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="font-medium" x-text="order ? parseFloat(order.subtotal).toFixed(3) + ' TND' : ''"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Shipping:</span>
                                    <span class="font-medium" x-text="order ? parseFloat(order.shipping_cost).toFixed(3) + ' TND' : ''"></span>
                                </div>
                                <div class="border-t pt-2">
                                    <div class="flex justify-between text-lg font-bold">
                                        <span>Total:</span>
                                        <span class="text-blue-600" x-text="order ? parseFloat(order.total).toFixed(3) + ' TND' : ''"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Footer Actions -->
            <div class="bg-gray-50 px-6 py-4 border-t">
                <div class="flex flex-col sm:flex-row gap-3">
                    <button @click="close()" 
                            class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                        Close
                    </button>
                    <template x-if="order && order.confirmation_status === 'pending_confirmation'">
                        <div class="flex gap-3">
                            <button @click="quickAction('quick-confirm', order.id)" 
                                    :disabled="loading"
                                    class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 disabled:bg-green-400 transition-colors min-h-[48px]">
                                <span x-show="!loading">Confirm Order</span>
                                <span x-show="loading" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                            <button @click="quickAction('quick-cancel', order.id)" 
                                    :disabled="loading"
                                    class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 disabled:bg-red-400 transition-colors min-h-[48px]">
                                <span x-show="!loading">Cancel Order</span>
                                <span x-show="loading" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </template>
                    <template x-if="order && order.confirmation_status === 'confirmed'">
                        <div class="flex gap-3">
                            <button @click="quickAction('quick-print', order.id)" 
                                    :disabled="loading"
                                    class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 disabled:bg-blue-400 transition-colors min-h-[48px]">
                                <span x-show="!loading">Mark as Printed</span>
                                <span x-show="loading" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </template>
                    <template x-if="order && order.confirmation_status === 'printed'">
                        <div class="flex gap-3">
                            <button @click="quickAction('quick-pickup', order.id)" 
                                    :disabled="loading"
                                    class="flex-1 px-6 py-3 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 disabled:bg-purple-400 transition-colors min-h-[48px]">
                                <span x-show="!loading">Request Pickup</span>
                                <span x-show="loading" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </template>
                    <template x-if="order && ['pickup_requested', 'in_delivery'].includes(order.confirmation_status)">
                        <div class="flex gap-3">
                            <button @click="quickAction('mark-delivered', order.id)" 
                                    :disabled="loading"
                                    class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 disabled:bg-green-400 transition-colors min-h-[48px]">
                                <span x-show="!loading">Mark as Delivered</span>
                                <span x-show="loading" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </template>
                    <template x-if="order && order.confirmation_status === 'delivered'">
                        <div class="flex-1 px-6 py-3 bg-gray-200 text-gray-500 rounded-lg font-medium text-center">
                            Order delivered
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Alpine.js component for quick view modal
function quickViewModal() {
    return {
        order: null,
        show: false,
        loading: false,
        
        open(orderData) {
            this.order = orderData;
            this.show = true;
            document.body.style.overflow = 'hidden';
        },
        
        close() {
            this.show = false;
            this.order = null;
            document.body.style.overflow = 'auto';
        },
        
        async quickAction(action, orderId) {
            this.loading = true;
            try {
                const response = await fetch(`/admin/orders/${orderId}/${action}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                });
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    // Update the order data in the modal
                    if (result.order) {
                        this.order = result.order;
                    }
                    
                    // Show success message
                    alert(result.message || 'Action completed successfully');
                    
                    // Close the modal
                    this.close();
                    
                    // Update the table dynamically instead of reloading
                    // window.location.reload();
                } else {
                    alert(result.message || 'An error occurred');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            } finally {
                this.loading = false;
            }
        }
    };
}

// Global function to open quick view modal
function openQuickView(orderData) {
    window.dispatchEvent(new CustomEvent('open-quick-view', { detail: orderData }));
}

// Listen for the custom event
document.addEventListener('open-quick-view', function(event) {
    const modal = document.getElementById('quickViewModal');
    const alpineData = Alpine.$data(modal);
    if (alpineData && alpineData.open) {
        alpineData.open(event.detail);
    }
});
</script>
