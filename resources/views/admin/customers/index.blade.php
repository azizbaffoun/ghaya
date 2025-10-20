@extends('admin.layout')

@section('title', __('admin.customers.title'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-xl sm:text-3xl font-bold text-gray-900 mobile-title-responsive">{{ __('admin.customers.title') }}</h1>
            <p class="mt-2 text-gray-600 mobile-subtitle-hidden sm:block">{{ __('admin.customers.subtitle', [], 'Manage customer information and orders') }}</p>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.customers.customers_list') }}</h3>
        </div>
        
        <!-- Desktop Table -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.customers.customer') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.customers.email') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.customers.phone') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.customers.orders') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.customers.last_order') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.common.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Sample Customer Row -->
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">JD</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">John Doe</div>
                                    <div class="text-sm text-gray-500">Casablanca, Morocco</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">john@example.com</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">+212 6XX XXX XXX</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">5</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dec 15, 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                    {{ __('admin.common.view') }}
                                </button>
                                <button class="text-green-600 hover:text-green-900 transition-colors duration-200">
                                    {{ __('admin.common.edit') }}
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden p-4 space-y-4">
            <!-- Sample Customer Card -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <div class="flex items-start space-x-4">
                    <!-- Customer Avatar -->
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-white">JD</span>
                        </div>
                    </div>
                    
                    <!-- Customer Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-900">John Doe</h4>
                            <span class="text-sm text-gray-500">5 orders</span>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-2">Casablanca, Morocco</p>
                        
                        <div class="space-y-1 mb-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Email:</span>
                                <a href="mailto:john@example.com" class="text-sm text-blue-600">john@example.com</a>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Phone:</span>
                                <a href="tel:+2126XXXXXXX" class="text-sm text-blue-600">+212 6XX XXX XXX</a>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Last Order:</span>
                                <span class="text-sm text-gray-900">Dec 15, 2024</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <button class="flex-1 bg-blue-600 text-white text-center py-2 px-3 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                {{ __('admin.common.view') }}
                            </button>
                            <button class="flex-1 bg-green-100 text-green-700 text-center py-2 px-3 rounded-lg text-sm font-medium hover:bg-green-200 transition-colors">
                                {{ __('admin.common.edit') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No customers found</h3>
                <p class="mt-1 text-sm text-gray-500">Start by adding your first customer.</p>
            </div>
        </div>
    </div>
</div>
@endsection
