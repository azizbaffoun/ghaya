@extends('admin.layout')

@section('title', 'Admin Tests')

@section('content')
<div class="space-y-6" x-data="testPage()">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Admin Tests</h1>
            <p class="mt-2 text-gray-600">Test order processes and First Delivery APIs</p>
        </div>
    </div>

    <!-- API Configuration -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            First Delivery API Configuration
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">API Key (Optional)</label>
                <input type="password" 
                       x-model="apiKey"
                       placeholder="Leave empty to use stored API key from settings"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="mt-1 text-xs text-gray-500">If left empty, the system will use the API key stored in First Delivery settings</p>
            </div>
            <div class="flex items-end">
                <button @click="testConnection()" 
                        :disabled="loading"
                        class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white rounded-lg font-medium transition-colors">
                    <span x-show="!loading">Test Connection</span>
                    <span x-show="loading" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Testing...
                    </span>
                </button>
            </div>
        </div>
        
        <div x-show="connectionResult" class="mt-4 p-4 rounded-lg" 
             :class="connectionResult.success ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
            <div class="flex items-center">
                <svg x-show="connectionResult.success" class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <svg x-show="!connectionResult.success" class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span x-text="connectionResult.message" 
                      :class="connectionResult.success ? 'text-green-800' : 'text-red-800'"></span>
            </div>
        </div>
    </div>

    <!-- System Health Dashboard -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            System Health Dashboard
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="p-4 border rounded-lg" :class="systemHealth?.database?.status === 'connected' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50'">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full mr-2" :class="systemHealth?.database?.status === 'connected' ? 'bg-green-500' : 'bg-red-500'"></div>
                    <span class="font-medium" :class="systemHealth?.database?.status === 'connected' ? 'text-green-800' : 'text-red-800'">Database</span>
                </div>
                <p class="text-sm mt-1" :class="systemHealth?.database?.status === 'connected' ? 'text-green-700' : 'text-red-700'" x-text="systemHealth?.database?.message || 'Checking...'"></p>
            </div>
            
            <div class="p-4 border rounded-lg" :class="systemHealth?.first_delivery_settings?.status === 'ok' ? 'border-green-200 bg-green-50' : systemHealth?.first_delivery_settings?.status === 'warning' ? 'border-yellow-200 bg-yellow-50' : 'border-red-200 bg-red-50'">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full mr-2" :class="systemHealth?.first_delivery_settings?.status === 'ok' ? 'bg-green-500' : systemHealth?.first_delivery_settings?.status === 'warning' ? 'bg-yellow-500' : 'bg-red-500'"></div>
                    <span class="font-medium" :class="systemHealth?.first_delivery_settings?.status === 'ok' ? 'text-green-800' : systemHealth?.first_delivery_settings?.status === 'warning' ? 'text-yellow-800' : 'text-red-800'">First Delivery Settings</span>
                </div>
                <p class="text-sm mt-1" :class="systemHealth?.first_delivery_settings?.status === 'ok' ? 'text-green-700' : systemHealth?.first_delivery_settings?.status === 'warning' ? 'text-yellow-700' : 'text-red-700'" x-text="systemHealth?.first_delivery_settings?.message || 'Checking...'"></p>
            </div>
            
            <div class="p-4 border rounded-lg" :class="systemHealth?.api_key_encryption?.status === 'ok' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50'">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full mr-2" :class="systemHealth?.api_key_encryption?.status === 'ok' ? 'bg-green-500' : 'bg-red-500'"></div>
                    <span class="font-medium" :class="systemHealth?.api_key_encryption?.status === 'ok' ? 'text-green-800' : 'text-red-800'">API Key Encryption</span>
                </div>
                <p class="text-sm mt-1" :class="systemHealth?.api_key_encryption?.status === 'ok' ? 'text-green-700' : 'text-red-700'" x-text="systemHealth?.api_key_encryption?.message || 'Checking...'"></p>
            </div>
        </div>
        
        <div class="mt-4 flex justify-end">
            <button @click="checkSystemHealth()" 
                    :disabled="loading"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white rounded-lg transition-colors">
                <span x-show="!loading">Refresh Health Check</span>
                <span x-show="loading" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Checking...
                </span>
            </button>
        </div>
    </div>

    <!-- API Key Diagnostics -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            API Key Diagnostics
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-3">API Key Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Encrypted Key (truncated)</label>
                        <input type="text" readonly 
                               :value="apiKeyDiagnostics?.encrypted_key || 'Not loaded'"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm font-mono">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Decrypted Key (masked)</label>
                        <input type="text" readonly 
                               :value="apiKeyDiagnostics?.decrypted_key || 'Not loaded'"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm font-mono">
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-2" :class="apiKeyDiagnostics?.is_valid_uuid ? 'bg-green-500' : 'bg-red-500'"></div>
                            <span class="text-sm" :class="apiKeyDiagnostics?.is_valid_uuid ? 'text-green-700' : 'text-red-700'">
                                Valid UUID Format
                            </span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-2" :class="apiKeyDiagnostics?.encryption_works ? 'bg-green-500' : 'bg-red-500'"></div>
                            <span class="text-sm" :class="apiKeyDiagnostics?.encryption_works ? 'text-green-700' : 'text-red-700'">
                                Encryption Works
                            </span>
                        </div>
                    </div>
                    <div x-show="apiKeyDiagnostics?.last_updated">
                        <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                        <p class="text-sm text-gray-600" x-text="new Date(apiKeyDiagnostics?.last_updated).toLocaleString()"></p>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-3">Actions</h3>
                <div class="space-y-3">
                    <button @click="testApiKeyEncryption()" 
                            :disabled="loading"
                            class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white rounded-lg transition-colors">
                        <span x-show="!loading">Test Encryption/Decryption</span>
                        <span x-show="loading" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Testing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Test Order -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Create Test Order
        </h2>
        
        <form @submit.prevent="createTestOrder()" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
                    <input type="text" x-model="testOrder.customer_name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Email</label>
                    <input type="email" x-model="testOrder.customer_email" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Phone</label>
                    <input type="tel" x-model="testOrder.customer_phone" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount (TND)</label>
                    <input type="number" x-model="testOrder.total" step="0.001" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
                <textarea x-model="testOrder.shipping_address" required rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" 
                        :disabled="loading"
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white rounded-lg font-medium transition-colors">
                    <span x-show="!loading">Create Test Order</span>
                    <span x-show="loading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Creating...
                    </span>
                </button>
            </div>
        </form>
    </div>

    <!-- All Orders List -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                All Orders
            </h2>
            <div class="flex space-x-2">
                <button @click="loadAllOrders()" 
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                    Refresh
                </button>
                <button @click="showFilters = !showFilters" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Filters
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div x-show="showFilters" class="mb-6 p-4 bg-gray-50 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" x-model="filters.search" 
                           placeholder="Order #, Name, Email..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select x-model="filters.status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmation Status</label>
                    <select x-model="filters.confirmation_status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Confirmation Statuses</option>
                        <option value="pending_confirmation">Pending Confirmation</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="printed">Printed</option>
                        <option value="pickup_requested">Pickup Requested</option>
                        <option value="in_delivery">In Delivery</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                    <div class="flex space-x-2">
                        <input type="date" x-model="filters.date_from" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <input type="date" x-model="filters.date_to" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button @click="applyFilters()" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Apply Filters
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confirmation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Delivery</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="order in allOrders" :key="order.id">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="order.order_number"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900" x-text="order.customer_first_name + ' ' + order.customer_last_name"></div>
                                <div class="text-sm text-gray-500" x-text="order.customer_email"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="order.customer_phone || 'N/A'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="parseFloat(order.total).toFixed(3) + ' TND'"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                      :class="{
                                          'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                          'bg-blue-100 text-blue-800': order.status === 'processing',
                                          'bg-purple-100 text-purple-800': order.status === 'shipped',
                                          'bg-green-100 text-green-800': order.status === 'delivered',
                                          'bg-red-100 text-red-800': order.status === 'cancelled'
                                      }"
                                      x-text="order.status.toUpperCase()"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                      :class="{
                                          'bg-yellow-100 text-yellow-800': order.confirmation_status === 'pending_confirmation',
                                          'bg-green-100 text-green-800': order.confirmation_status === 'confirmed',
                                          'bg-blue-100 text-blue-800': order.confirmation_status === 'printed',
                                          'bg-purple-100 text-purple-800': order.confirmation_status === 'pickup_requested',
                                          'bg-indigo-100 text-indigo-800': order.confirmation_status === 'in_delivery',
                                          'bg-gray-100 text-gray-800': order.confirmation_status === 'delivered',
                                          'bg-red-100 text-red-800': order.confirmation_status === 'cancelled'
                                      }"
                                      x-text="order.confirmation_status.replace('_', ' ').toUpperCase()"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div x-show="order.first_delivery_id" class="space-y-1">
                                    <div class="text-xs">
                                        <span class="font-medium">ID:</span> <span x-text="order.first_delivery_id"></span>
                                    </div>
                                    <div x-show="order.barcode" class="text-xs">
                                        <span class="font-medium">Barcode:</span> <span x-text="order.barcode"></span>
                                    </div>
                                    <div x-show="order.print_url" class="text-xs">
                                        <a :href="order.print_url" target="_blank" class="text-blue-600 hover:text-blue-800">Print Label</a>
                                    </div>
                                </div>
                                <div x-show="!order.first_delivery_id" class="text-xs text-gray-400">
                                    Not synced
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="new Date(order.created_at).toLocaleDateString()"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-wrap gap-1">
                                    <button x-show="order.confirmation_status === 'pending_confirmation'"
                                            @click="confirmTestOrder(order.id)" 
                                            :disabled="loading"
                                            class="px-2 py-1 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white text-xs rounded transition-colors">
                                        Confirm
                                    </button>
                                    <button @click="testOrderWorkflow(order.id)" 
                                            :disabled="loading"
                                            class="px-2 py-1 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white text-xs rounded transition-colors">
                                        Test Workflow
                                    </button>
                                    <button x-show="order.first_delivery_response"
                                            @click="printOrderResponse(order.id)" 
                                            class="px-2 py-1 bg-purple-600 hover:bg-purple-700 text-white text-xs rounded transition-colors">
                                        Print Response
                                    </button>
                                    <button @click="viewOrderDetails(order)" 
                                            class="px-2 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs rounded transition-colors">
                                        View Details
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="allOrders.length === 0">
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">No orders found</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div x-show="pagination && pagination.last_page > 1" class="mt-4 flex justify-center">
            <div class="flex space-x-2">
                <button @click="loadAllOrders(pagination.current_page - 1)" 
                        :disabled="pagination.current_page <= 1"
                        class="px-3 py-2 bg-gray-200 hover:bg-gray-300 disabled:bg-gray-100 disabled:text-gray-400 rounded-lg transition-colors">
                    Previous
                </button>
                <span class="px-3 py-2 text-sm text-gray-600">
                    Page <span x-text="pagination.current_page"></span> of <span x-text="pagination.last_page"></span>
                </span>
                <button @click="loadAllOrders(pagination.current_page + 1)" 
                        :disabled="pagination.current_page >= pagination.last_page"
                        class="px-3 py-2 bg-gray-200 hover:bg-gray-300 disabled:bg-gray-100 disabled:text-gray-400 rounded-lg transition-colors">
                    Next
                </button>
            </div>
        </div>
    </div>

    <!-- API Tests -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            First Delivery API Tests
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Test Individual APIs -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Individual API Tests</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Test Type</label>
                    <select x-model="apiTest.test_type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="auth">Authentication Test</option>
                        <option value="create_order">Create Order Test</option>
                        <option value="check_status">Check Status Test</option>
                        <option value="request_pickup">Request Pickup Test</option>
                        <option value="cancel_delivery">Cancel Delivery Test</option>
                    </select>
                </div>
                
                <div x-show="apiTest.test_type === 'create_order'">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Order</label>
                    <select x-model="apiTest.order_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select an order...</option>
                        <template x-for="order in testOrders" :key="order.id">
                            <option :value="order.id" x-text="order.order_number + ' - ' + order.customer_first_name"></option>
                        </template>
                    </select>
                </div>
                
                <div x-show="['check_status', 'request_pickup', 'cancel_delivery'].includes(apiTest.test_type)">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Barcode</label>
                    <input type="text" x-model="apiTest.barcode" 
                           placeholder="Enter barcode..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <button @click="runApiTest()" 
                        :disabled="!apiKey || loading || (apiTest.test_type === 'create_order' && !apiTest.order_id) || (['check_status', 'request_pickup', 'cancel_delivery'].includes(apiTest.test_type) && !apiTest.barcode)"
                        class="w-full px-4 py-3 bg-orange-600 hover:bg-orange-700 disabled:bg-gray-400 text-white rounded-lg font-medium transition-colors">
                    Run Test
                </button>
            </div>
            
            <!-- Test Results -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Test Results</h3>
                
                <div x-show="testResults.length === 0" class="text-center py-8 text-gray-500">
                    No tests run yet
                </div>
                
                <div class="space-y-4">
                    <template x-for="(result, index) in testResults" :key="index">
                        <div class="border border-gray-200 rounded-lg p-4"
                             :class="result.success ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <svg x-show="result.success" class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <svg x-show="!result.success" class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="font-medium" 
                                          :class="result.success ? 'text-green-800' : 'text-red-800'"
                                          x-text="result.test_type + ' Test'"></span>
                                </div>
                                <span class="text-sm text-gray-500" x-text="new Date(result.timestamp).toLocaleTimeString()"></span>
                            </div>
                            <p class="text-sm mb-2" 
                               :class="result.success ? 'text-green-700' : 'text-red-700'"
                               x-text="result.message"></p>
                            <div x-show="result.result" class="mt-2">
                                <details class="text-sm">
                                    <summary class="cursor-pointer text-gray-600 hover:text-gray-800">View Response</summary>
                                    <pre class="mt-2 p-2 bg-gray-100 rounded text-xs overflow-x-auto" x-text="JSON.stringify(result.result, null, 2)"></pre>
                                </details>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Operations Testing -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            Bulk Operations Testing
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-3">Bulk Create Orders</h3>
                <form @submit.prevent="runBulkCreateTest()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number of Orders (2-10)</label>
                        <input type="number" x-model="bulkTest.count" min="2" max="10" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">API Key (Optional)</label>
                        <input type="password" x-model="bulkTest.api_key"
                               placeholder="Leave empty to use stored API key"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <button type="submit" 
                            :disabled="loading"
                            class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white rounded-lg font-medium transition-colors">
                        <span x-show="!loading">Create Bulk Test Orders</span>
                        <span x-show="loading" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating...
                        </span>
                    </button>
                </form>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-3">Test Data Management</h3>
                <div class="space-y-3">
                    <button @click="exportTestData()" 
                            :disabled="loading"
                            class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white rounded-lg transition-colors">
                        <span x-show="!loading">Export Test Data (JSON)</span>
                        <span x-show="loading" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Exporting...
                        </span>
                    </button>
                    
                    <button @click="clearTestData()" 
                            :disabled="loading"
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white rounded-lg transition-colors">
                        <span x-show="!loading">Clear All Test Data</span>
                        <span x-show="loading" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Clearing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Bulk Test Results -->
        <div x-show="bulkTestResults" class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-3">Bulk Test Results</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-indigo-600" x-text="bulkTestResults?.orders_created || 0"></div>
                        <div class="text-sm text-gray-600">Orders Created</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600" x-text="bulkTestResults?.results?.filter(r => r.success).length || 0"></div>
                        <div class="text-sm text-gray-600">Successful Syncs</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600" x-text="bulkTestResults?.results?.filter(r => !r.success).length || 0"></div>
                        <div class="text-sm text-gray-600">Failed Syncs</div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <template x-for="result in bulkTestResults?.results || []" :key="result.order_id">
                        <div class="flex items-center justify-between p-2 rounded" 
                             :class="result.success ? 'bg-green-100' : 'bg-red-100'">
                            <span class="text-sm font-medium" x-text="result.order_number"></span>
                            <div class="flex items-center">
                                <div class="w-2 h-2 rounded-full mr-2" :class="result.success ? 'bg-green-500' : 'bg-red-500'"></div>
                                <span class="text-xs" :class="result.success ? 'text-green-700' : 'text-red-700'">
                                    <span x-show="result.success">Synced</span>
                                    <span x-show="!result.success" x-text="result.error || 'Failed'"></span>
                                </span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Performance Metrics
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600" x-text="performanceMetrics?.orders_today || 0"></div>
                <div class="text-sm text-blue-700">Orders Today</div>
            </div>
            <div class="p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600" x-text="performanceMetrics?.orders_synced_today || 0"></div>
                <div class="text-sm text-green-700">Synced Today</div>
            </div>
            <div class="p-4 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600" x-text="performanceMetrics?.pending_confirmation || 0"></div>
                <div class="text-sm text-yellow-700">Pending Confirmation</div>
            </div>
            <div class="p-4 bg-purple-50 rounded-lg">
                <div class="text-2xl font-bold text-purple-600" x-text="performanceMetrics?.api_success_rate || 0"></div>
                <div class="text-sm text-purple-700">API Success Rate %</div>
            </div>
        </div>
        
        <div class="mt-4 flex justify-end">
            <button @click="loadPerformanceMetrics()" 
                    :disabled="loading"
                    class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 disabled:bg-gray-400 text-white rounded-lg transition-colors">
                <span x-show="!loading">Refresh Metrics</span>
                <span x-show="loading" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Loading...
                </span>
            </button>
        </div>
    </div>

    <!-- Workflow Test Results -->
    <div x-show="workflowResults" class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            Workflow Test Results
        </h2>
        
        <div class="space-y-4">
            <template x-for="step in workflowResults.steps" :key="step">
                <div class="flex items-center p-3 rounded-lg"
                     :class="step.startsWith('✅') ? 'bg-green-50 text-green-800' : step.startsWith('❌') ? 'bg-red-50 text-red-800' : 'bg-yellow-50 text-yellow-800'">
                    <span x-text="step"></span>
                </div>
            </template>
        </div>
        
        <div x-show="workflowResults.results" class="mt-6">
            <details class="text-sm">
                <summary class="cursor-pointer text-gray-600 hover:text-gray-800 font-medium">View Detailed Results</summary>
                <pre class="mt-2 p-4 bg-gray-100 rounded text-xs overflow-x-auto" x-text="JSON.stringify(workflowResults.results, null, 2)"></pre>
            </details>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div x-show="showOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50" @click="showOrderModal = false">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Order Details</h3>
                    <button @click="showOrderModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div x-show="selectedOrder" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Order Information -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Order Information</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Order Number:</span>
                                    <span class="text-sm font-medium" x-text="selectedOrder?.order_number"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Customer:</span>
                                    <span class="text-sm font-medium" x-text="selectedOrder?.customer_first_name + ' ' + selectedOrder?.customer_last_name"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Email:</span>
                                    <span class="text-sm font-medium" x-text="selectedOrder?.customer_email"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Phone:</span>
                                    <span class="text-sm font-medium" x-text="selectedOrder?.customer_phone || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Total:</span>
                                    <span class="text-sm font-medium" x-text="parseFloat(selectedOrder?.total || 0).toFixed(3) + ' TND'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Status:</span>
                                    <span class="text-sm font-medium" x-text="selectedOrder?.status?.toUpperCase()"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Confirmation:</span>
                                    <span class="text-sm font-medium" x-text="selectedOrder?.confirmation_status?.replace('_', ' ').toUpperCase()"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Created:</span>
                                    <span class="text-sm font-medium" x-text="selectedOrder?.created_at ? new Date(selectedOrder.created_at).toLocaleString() : 'N/A'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- First Delivery Information -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">First Delivery Information</h4>
                            <div x-show="selectedOrder?.first_delivery_id" class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">FD ID:</span>
                                    <span class="text-sm font-medium" x-text="selectedOrder?.first_delivery_id"></span>
                                </div>
                                <div x-show="selectedOrder?.barcode" class="flex justify-between">
                                    <span class="text-sm text-gray-600">Barcode:</span>
                                    <span class="text-sm font-medium" x-text="selectedOrder?.barcode"></span>
                                </div>
                                <div x-show="selectedOrder?.print_url" class="flex justify-between">
                                    <span class="text-sm text-gray-600">Print URL:</span>
                                    <a :href="selectedOrder?.print_url" target="_blank" class="text-sm text-blue-600 hover:text-blue-800">Open</a>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">FD Status:</span>
                                    <span class="text-sm font-medium" x-text="selectedOrder?.first_delivery_status || 'N/A'"></span>
                                </div>
                            </div>
                            <div x-show="!selectedOrder?.first_delivery_id" class="text-sm text-gray-500">
                                Not synced with First Delivery
                            </div>
                        </div>
                    </div>

                    <!-- First Delivery Response -->
                    <div x-show="selectedOrder?.first_delivery_response" class="mt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">First Delivery API Response</h4>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <pre class="text-xs overflow-x-auto" x-text="JSON.stringify(selectedOrder?.first_delivery_response, null, 2)"></pre>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div x-show="selectedOrder?.order_items && selectedOrder.order_items.length > 0" class="mt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Order Items</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Product</th>
                                        <th class="px-4 py-2 text-left">Variant</th>
                                        <th class="px-4 py-2 text-left">Quantity</th>
                                        <th class="px-4 py-2 text-left">Unit Price</th>
                                        <th class="px-4 py-2 text-left">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="item in selectedOrder?.order_items || []" :key="item.id">
                                        <tr class="border-t">
                                            <td class="px-4 py-2" x-text="item.product_name"></td>
                                            <td class="px-4 py-2" x-text="item.variant_name || 'N/A'"></td>
                                            <td class="px-4 py-2" x-text="item.quantity"></td>
                                            <td class="px-4 py-2" x-text="parseFloat(item.unit_price).toFixed(3) + ' TND'"></td>
                                            <td class="px-4 py-2" x-text="parseFloat(item.total_price).toFixed(3) + ' TND'"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function testPage() {
    return {
        loading: false,
        apiKey: '',
        connectionResult: null,
        testOrder: {
            customer_name: 'Test Customer',
            customer_email: 'test@example.com',
            customer_phone: '12345678',
            shipping_address: 'Test Address, Test City',
            total: 50.000,
            shipping_cost: 5.000
        },
        testOrders: [],
        allOrders: [],
        pagination: null,
        showFilters: false,
        filters: {
            search: '',
            status: '',
            confirmation_status: '',
            date_from: '',
            date_to: ''
        },
        showOrderModal: false,
        selectedOrder: null,
        apiTest: {
            test_type: 'auth',
            order_id: '',
            barcode: ''
        },
        testResults: [],
        workflowResults: null,
        systemHealth: null,
        apiKeyDiagnostics: null,
        bulkTest: {
            count: 5,
            api_key: ''
        },
        bulkTestResults: null,
        performanceMetrics: null,

        async testConnection() {
            this.loading = true;
            try {
                const response = await fetch('/admin/tests/api-test', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        api_key: this.apiKey,
                        test_type: 'auth'
                    })
                });
                
                const result = await response.json();
                this.connectionResult = result;
            } catch (error) {
                this.connectionResult = {
                    success: false,
                    message: 'Connection test failed: ' + error.message
                };
            } finally {
                this.loading = false;
            }
        },

        async loadAllOrders(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    page: page,
                    ...this.filters
                });
                
                const response = await fetch(`/admin/tests/all-orders?${params}`);
                const result = await response.json();
                
                if (result.success) {
                    this.allOrders = result.orders;
                    this.pagination = result.pagination;
                }
            } catch (error) {
                console.error('Failed to load orders:', error);
                alert('Failed to load orders: ' + error.message);
            } finally {
                this.loading = false;
            }
        },

        applyFilters() {
            this.loadAllOrders(1);
        },

        viewOrderDetails(order) {
            this.selectedOrder = order;
            this.showOrderModal = true;
        },

        async printOrderResponse(orderId) {
            try {
                const response = await fetch('/admin/tests/print-response', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        order_id: orderId
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    // Open print window
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write(result.html);
                    printWindow.document.close();
                    printWindow.focus();
                    printWindow.print();
                } else {
                    alert('Failed to generate print response: ' + result.message);
                }
            } catch (error) {
                alert('Failed to print response: ' + error.message);
            }
        },

        async createTestOrder() {
            this.loading = true;
            try {
                const response = await fetch('/admin/tests/create-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.testOrder)
                });
                
                const result = await response.json();
                if (result.success) {
                    alert('Test order created successfully!');
                    this.loadTestOrders();
                    // Reset form
                    this.testOrder = {
                        customer_name: 'Test Customer',
                        customer_email: 'test@example.com',
                        customer_phone: '12345678',
                        shipping_address: 'Test Address, Test City',
                        total: 50.000,
                        shipping_cost: 5.000
                    };
                } else {
                    alert('Failed to create test order: ' + result.message);
                }
            } catch (error) {
                alert('Failed to create test order: ' + error.message);
            } finally {
                this.loading = false;
            }
        },

        async loadTestOrders() {
            try {
                const response = await fetch('/admin/tests/orders');
                const result = await response.json();
                if (result.success) {
                    this.testOrders = result.orders;
                }
            } catch (error) {
                console.error('Failed to load test orders:', error);
            }
        },

        async runApiTest() {
            if (!this.apiKey) return;
            
            this.loading = true;
            try {
                const response = await fetch('/admin/tests/api-test', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        api_key: this.apiKey,
                        test_type: this.apiTest.test_type,
                        order_id: this.apiTest.order_id,
                        barcode: this.apiTest.barcode
                    })
                });
                
                const result = await response.json();
                this.testResults.unshift({
                    ...result,
                    timestamp: new Date().toISOString()
                });
            } catch (error) {
                this.testResults.unshift({
                    success: false,
                    message: 'API test failed: ' + error.message,
                    test_type: this.apiTest.test_type,
                    timestamp: new Date().toISOString()
                });
            } finally {
                this.loading = false;
            }
        },

        async confirmTestOrder(orderId) {
            this.loading = true;
            try {
                const response = await fetch('/admin/tests/confirm-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        api_key: this.apiKey
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    alert('Order confirmed successfully!');
                    this.loadTestOrders(); // Refresh test orders
                    this.loadAllOrders(); // Refresh all orders
                } else {
                    alert('Failed to confirm order: ' + result.message);
                }
            } catch (error) {
                alert('Failed to confirm order: ' + error.message);
            } finally {
                this.loading = false;
            }
        },

        async testOrderWorkflow(orderId) {
            this.loading = true;
            try {
                const response = await fetch('/admin/tests/workflow', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        api_key: this.apiKey
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    this.workflowResults = result;
                    this.loadTestOrders(); // Refresh test orders
                    this.loadAllOrders(); // Refresh all orders
                } else {
                    alert('Workflow test failed: ' + result.message);
                }
            } catch (error) {
                alert('Workflow test failed: ' + error.message);
            } finally {
                this.loading = false;
            }
        },

        async checkSystemHealth() {
            this.loading = true;
            try {
                const response = await fetch('/admin/tests/system-health', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                if (result.success) {
                    this.systemHealth = result.health;
                } else {
                    console.error('Health check failed:', result.message);
                }
            } catch (error) {
                console.error('Health check failed:', error);
            } finally {
                this.loading = false;
            }
        },

        async testApiKeyEncryption() {
            this.loading = true;
            try {
                const response = await fetch('/admin/tests/api-key-diagnostics', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                if (result.success) {
                    this.apiKeyDiagnostics = result;
                } else {
                    console.error('API key diagnostics failed:', result.message);
                }
            } catch (error) {
                console.error('API key diagnostics failed:', error);
            } finally {
                this.loading = false;
            }
        },

        async runBulkCreateTest() {
            this.loading = true;
            try {
                const response = await fetch('/admin/tests/bulk-create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.bulkTest)
                });
                
                const result = await response.json();
                if (result.success) {
                    this.bulkTestResults = result;
                    this.loadAllOrders(); // Refresh orders list
                } else {
                    alert('Bulk test failed: ' + result.message);
                }
            } catch (error) {
                alert('Bulk test failed: ' + error.message);
            } finally {
                this.loading = false;
            }
        },

        async exportTestData() {
            this.loading = true;
            try {
                const response = await fetch('/admin/tests/export-data');
                const result = await response.json();
                
                if (result.success) {
                    // Download as JSON file
                    const dataStr = JSON.stringify(result.data, null, 2);
                    const dataBlob = new Blob([dataStr], {type: 'application/json'});
                    const url = URL.createObjectURL(dataBlob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = `test-data-${new Date().toISOString().split('T')[0]}.json`;
                    link.click();
                    URL.revokeObjectURL(url);
                } else {
                    alert('Export failed: ' + result.message);
                }
            } catch (error) {
                alert('Export failed: ' + error.message);
            } finally {
                this.loading = false;
            }
        },

        async clearTestData() {
            if (!confirm('Are you sure you want to clear all test data? This action cannot be undone.')) {
                return;
            }
            
            this.loading = true;
            try {
                const response = await fetch('/admin/tests/clear-test-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ confirm: true })
                });
                
                const result = await response.json();
                if (result.success) {
                    alert(`Test data cleared successfully. ${result.deleted_orders} orders deleted.`);
                    this.loadAllOrders(); // Refresh orders list
                    this.bulkTestResults = null;
                } else {
                    alert('Clear test data failed: ' + result.message);
                }
            } catch (error) {
                alert('Clear test data failed: ' + error.message);
            } finally {
                this.loading = false;
            }
        },

        async loadPerformanceMetrics() {
            this.loading = true;
            try {
                const response = await fetch('/admin/tests/performance-metrics');
                const result = await response.json();
                
                if (result.success) {
                    this.performanceMetrics = result.metrics;
                } else {
                    console.error('Failed to load performance metrics:', result.message);
                }
            } catch (error) {
                console.error('Failed to load performance metrics:', error);
            } finally {
                this.loading = false;
            }
        },

        init() {
            this.loadTestOrders();
            this.loadAllOrders();
            this.checkSystemHealth();
            this.testApiKeyEncryption();
            this.loadPerformanceMetrics();
        }
    }
}
</script>
@endsection
