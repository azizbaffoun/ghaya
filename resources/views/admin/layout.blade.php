<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', __('admin.dashboard.title'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- RTL Support -->
    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    @endif

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Page-specific scripts -->
    @if(request()->is('admin/orders*'))
        @vite(['resources/js/order-management.js'])
    @endif
    
    @if(request()->is('admin/dashboard*') || request()->is('admin') || request()->is('admin/'))
        @vite(['resources/js/live-search.js'])
    @endif
    
    <!-- Alpine.js - Optimized -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    
    <style>
        @if(app()->getLocale() === 'ar')
            body { font-family: 'Cairo', sans-serif; }
        @endif
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Gradient backgrounds */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        /* Sidebar animations */
        .sidebar-item {
            transition: all 0.2s ease;
        }
        .sidebar-item:hover {
            background: linear-gradient(90deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false, language: '{{ app()->getLocale() }}' }">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 z-50 w-64 gradient-bg transform sidebar-transition shadow-2xl sidebar-mobile"
             :class="{
                 'left-0': language !== 'ar',
                 'right-0': language === 'ar',
                 'translate-x-0': sidebarOpen,
                 '-translate-x-full lg:translate-x-0': !sidebarOpen && language !== 'ar',
                 'translate-x-full lg:translate-x-0': !sidebarOpen && language === 'ar'
             }"
             x-show="sidebarOpen || window.innerWidth >= 1024">
            
            <div class="flex items-center justify-between h-20 bg-black bg-opacity-20 backdrop-blur-sm px-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h1 class="text-white mobile-text-xl text-xl font-bold">{{ config('app.name') }}</h1>
                </div>
                <!-- Mobile close button -->
                <button @click="sidebarOpen = false" 
                        class="lg:hidden mobile-min-h-12 p-2 rounded-lg text-white hover:bg-white hover:bg-opacity-20 transition-colors duration-200 touch-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <nav class="mt-8 px-4 space-y-2 overflow-y-auto flex-1 pb-8">
                <a href="{{ route('admin.dashboard') }}" 
                   data-smooth-nav
                   data-route="admin.dashboard"
                   class="sidebar-item group flex items-center mobile-p-6 px-4 py-3 mobile-text-base text-sm font-medium rounded-xl text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15 touch-target">
                    <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    </svg>
                    <span data-translate="admin.dashboard.title">{{ __('admin.dashboard.title') }}</span>
                </a>
                
                <a href="{{ route('admin.products.index') }}" 
                   data-smooth-nav
                   data-route="admin.products.index"
                   class="sidebar-item group flex items-center mobile-p-6 px-4 py-3 mobile-text-base text-sm font-medium rounded-xl text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15 touch-target">
                    <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span data-translate="admin.products.title">{{ __('admin.products.title') }}</span>
                </a>
                
                <a href="{{ route('admin.categories.index') }}" 
                   data-smooth-nav
                   data-route="admin.categories.index"
                   class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15 touch-target">
                    <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span data-translate="admin.categories.title">{{ __('admin.categories.title') }}</span>
                </a>
                
                <!-- Orders Menu -->
                <div x-data="ordersMenu()" class="space-y-1">
                    <button @click.stop="toggleOrders()" 
                            data-page-type="orders"
                            class="sidebar-item group flex items-center w-full px-4 py-3 text-sm font-medium rounded-xl text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15">
                    <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span data-translate="admin.orders.title">{{ __('admin.orders.title') }}</span>
                        <svg :class="language === 'ar' ? 'mr-auto' : 'ml-auto'" class="h-4 w-4 transition-transform duration-200" :class="ordersOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="ordersOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="pl-4 space-y-1">
                <a href="{{ route('admin.orders.index') }}" 
                   data-smooth-nav
                   data-route="admin.orders.index"
                   data-page-type="orders"
                   class="sidebar-item group flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15">
                            <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            {{ __('admin.navigation.all_orders') }}
                        </a>
                <a href="{{ route('admin.orders.new') }}" 
                   data-smooth-nav
                   data-route="admin.orders.new"
                   data-page-type="orders"
                   class="sidebar-item group flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15">
                            <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('admin.navigation.new_orders') }}
                        </a>
                <a href="{{ route('admin.orders.confirmed') }}" 
                   data-smooth-nav
                   data-route="admin.orders.confirmed"
                   data-page-type="orders"
                   class="sidebar-item group flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15">
                            <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('admin.navigation.confirmed') }}
                        </a>
                        <a href="{{ route('admin.orders.ready-pickup') }}" 
                           data-smooth-nav
                           data-route="admin.orders.ready-pickup"
                           data-page-type="orders"
                           class="sidebar-item group flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15">
                            <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            {{ __('admin.navigation.ready_pickup') }}
                        </a>
                        <a href="{{ route('admin.orders.in-delivery') }}" 
                           data-smooth-nav
                           data-route="admin.orders.in-delivery"
                           data-page-type="orders"
                           class="sidebar-item group flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15">
                            <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            {{ __('admin.navigation.in_delivery') }}
                        </a>
                    </div>
                </div>
                
                <a href="{{ route('admin.customers.index') }}" 
                   data-smooth-nav
                   data-route="admin.customers.index"
                   class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15 ">
                    <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    <span data-translate="admin.customers.title">{{ __('admin.customers.title') }}</span>
                </a>
                
                <!-- Divider for Website Management -->
                <div class="px-4 mt-6 mb-2">
                    <span class="text-xs font-semibold text-white text-opacity-60 uppercase tracking-wider">
                        {{ __('admin.navigation.website_management') }}
                    </span>
                </div>

                <!-- Website Management (Unified) -->
                <a href="{{ route('admin.website.index') }}" 
                   data-smooth-nav
                   data-route="admin.website.index"
                   class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15 ">
                    <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                    <span data-translate="admin.navigation.website_management">Website Management</span>
                </a>

                <!-- Page Builder -->
                <a href="{{ route('admin.page-builder.index') }}" 
                   data-smooth-nav
                   data-route="admin.page-builder.index"
                   class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15 ">
                    <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                    <span data-translate="admin.navigation.page_builder">{{ __('admin.navigation.page_builder') }}</span>
                </a>

                <!-- Banners -->
                <a href="{{ route('admin.banners.index') }}" 
                   data-smooth-nav
                   data-route="admin.banners.index"
                   class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15 ">
                    <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span data-translate="admin.navigation.banners">{{ __('admin.navigation.banners') }}</span>
                </a>

                <!-- Divider for Delivery -->
                <div class="px-4 mt-6 mb-2">
                    <span class="text-xs font-semibold text-white text-opacity-60 uppercase tracking-wider">
                        {{ __('admin.navigation.delivery') }}
                    </span>
                </div>

                <!-- Delivery Menu -->
                <div x-data="{ deliveryOpen: false }" class="space-y-1">
                    <button @click="deliveryOpen = !deliveryOpen" 
                            data-page-type="delivery"
                            class="sidebar-item group flex items-center w-full px-4 py-3 text-sm font-medium rounded-xl text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15 ">
                        <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        {{ __('admin.navigation.delivery_main') }}
                        <svg :class="language === 'ar' ? 'mr-auto' : 'ml-auto'" class="h-4 w-4 transition-transform duration-200" :class="deliveryOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="deliveryOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="pl-4 space-y-1">
                        <a href="{{ route('admin.delivery.settings') }}" 
                           data-smooth-nav
                           data-route="admin.delivery.settings"
                           data-page-type="delivery"
                           class="sidebar-item group flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15 ">
                            <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ __('admin.navigation.settings') }}
                        </a>
                    </div>
                </div>

                <!-- Divider for Settings -->
                <div class="px-4 mt-6 mb-2">
                    <span class="text-xs font-semibold text-white text-opacity-60 uppercase tracking-wider">
                        {{ __('admin.navigation.settings') }}
                    </span>
                </div>

                <!-- Admin Tests -->
                <a href="{{ route('admin.tests.index') }}" 
                   data-smooth-nav
                   data-route="admin.tests.index"
                   class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15 ">
                    <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>{{ __('admin.navigation.admin_tests') }}</span>
                </a>

                <!-- Languages -->
                <a href="{{ route('admin.languages.index') }}"
                   data-smooth-nav 
                   data-route="admin.languages.index"
                   class="sidebar-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-white bg-white bg-opacity-5 backdrop-blur-sm border border-white border-opacity-10 hover:bg-opacity-15 ">
                    <svg :class="language === 'ar' ? 'ml-3' : 'mr-3'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                    </svg>
                    <span data-translate="admin.languages.title">{{ __('admin.common.language') }}</span>
                </a>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col main-content"
             :class="{
                 'lg:ml-64': language !== 'ar',
                 'lg:mr-64': language === 'ar'
             }">
            <!-- Top navigation -->
            <header class="bg-white shadow-lg border-b border-gray-200 backdrop-blur-sm bg-opacity-95">
                <div class="flex items-center justify-between px-3 py-2 sm:px-6 sm:py-4 mobile-header-compact">
                    <div class="flex items-center">
                        <button @click.stop="sidebarOpen = !sidebarOpen" 
                                class="lg:hidden p-3 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-200 touch-target">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="ml-2 sm:ml-4">
                            <h2 class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mobile-title-responsive">
                                @yield('title', __('admin.dashboard.title'))
                            </h2>
                            <p class="text-sm text-gray-500 mt-1 mobile-subtitle-hidden sm:block">{{ __('admin.dashboard.subtitle') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <!-- Language Switcher -->
                        <div class="hidden sm:block">
                        @include('components.language-switcher')
                        </div>
                        
                        <!-- User menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click.stop="open = !open" 
                                    class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 touch-target">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                                    <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-2 sm:ml-3 text-left mobile-stack sm:block">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                                </div>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                                <div class="px-4 py-3 bg-gradient-to-r from-gray-50 to-blue-50 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ __('admin.navigation.profile') }}
                                </a>
                                <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ __('admin.navigation.settings') }}
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        {{ __('admin.navigation.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main id="main-content" class="flex-1 p-4 sm:p-8 bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 min-h-screen mobile-content-padding">
                <div class="max-w-full mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
         @click="sidebarOpen = false">
    </div>

    <script>
    // Alpine.js component for orders menu
    function ordersMenu() {
        return {
            ordersOpen: {{ request()->is('admin/orders*') ? 'true' : 'false' }},
            init() {
                // Only check localStorage after component is mounted
                this.$nextTick(() => {
                    const saved = localStorage.getItem('ordersMenuOpen');
                    if (saved !== null) {
                        this.ordersOpen = saved === 'true';
                    }
                });
            },
            toggleOrders() {
                this.ordersOpen = !this.ordersOpen;
                localStorage.setItem('ordersMenuOpen', this.ordersOpen);
            }
        };
    }
    </script>
    
    @stack('scripts')
    
    <!-- Fallback functions to prevent ReferenceError -->
    <script>
    // Fallback functions only defined if real ones aren't loaded
    if (typeof window.switchTab === 'undefined') {
        window.switchTab = function(tabName) {
            console.log('switchTab called but website-management.js not loaded');
        };
    }
    if (typeof window.openBannerModal === 'undefined') {
        window.openBannerModal = function() {
            console.log('openBannerModal called but website-management.js not loaded');
        };
    }
    if (typeof window.closeBannerModal === 'undefined') {
        window.closeBannerModal = function() {
            console.log('closeBannerModal called but website-management.js not loaded');
        };
    }
    if (typeof window.editBanner === 'undefined') {
        window.editBanner = function(id) {
            console.log('editBanner called but website-management.js not loaded');
        };
    }
    if (typeof window.deleteBanner === 'undefined') {
        window.deleteBanner = function(id) {
            console.log('deleteBanner called but website-management.js not loaded');
        };
    }
    if (typeof window.toggleBanner === 'undefined') {
        window.toggleBanner = function(id) {
            console.log('toggleBanner called but website-management.js not loaded');
        };
    }
    if (typeof window.openSectionModal === 'undefined') {
        window.openSectionModal = function() {
            console.log('openSectionModal called but website-management.js not loaded');
        };
    }
    if (typeof window.closeSectionModal === 'undefined') {
        window.closeSectionModal = function() {
            console.log('closeSectionModal called but website-management.js not loaded');
        };
    }
    if (typeof window.editSection === 'undefined') {
        window.editSection = function(id) {
            console.log('editSection called but website-management.js not loaded');
        };
    }
    if (typeof window.deleteSection === 'undefined') {
        window.deleteSection = function(id) {
            console.log('deleteSection called but website-management.js not loaded');
        };
    }
    if (typeof window.toggleSection === 'undefined') {
        window.toggleSection = function(id) {
            console.log('toggleSection called but website-management.js not loaded');
        };
    }
    // Product modal fallbacks - wait for real functions to load
    (function() {
        const storedProductId = { value: null };
        const fallbackFn = function(productId) {
            storedProductId.value = productId;
            let attempts = 0;
            const checkForRealFunction = setInterval(() => {
                attempts++;
                const currentFn = window.openProductModal;
                // Check if function was replaced (real one has 'isEditMode' in code)
                if (currentFn !== fallbackFn && currentFn && currentFn.toString().includes('isEditMode')) {
                    clearInterval(checkForRealFunction);
                    currentFn(storedProductId.value);
                } else if (attempts > 50) {
                    clearInterval(checkForRealFunction);
                    console.error('openProductModal: Script failed to load');
                }
            }, 100);
        };
        if (typeof window.openProductModal === 'undefined') {
            window.openProductModal = fallbackFn;
        }
    })();
    if (typeof window.closeProductModal === 'undefined') {
        window.closeProductModal = function() {
            console.log('closeProductModal called but product-modal.js not loaded');
        };
    }
    // openDeleteProductModal fallback - wait for real function to load
    if (typeof window.openDeleteProductModal === 'undefined') {
        (function() {
            const storedParams = { id: null, name: null };
            const fallbackFn = function(id, name) {
                storedParams.id = id;
                storedParams.name = name;
                let attempts = 0;
                const checkForRealFunction = setInterval(() => {
                    attempts++;
                    const currentFn = window.openDeleteProductModal;
                    // Check if function was replaced (real one has 'deleteProductId' in code)
                    if (currentFn !== fallbackFn && currentFn && currentFn.toString().includes('deleteProductId')) {
                        clearInterval(checkForRealFunction);
                        currentFn(storedParams.id, storedParams.name);
                    } else if (attempts > 50) {
                        clearInterval(checkForRealFunction);
                        console.error('openDeleteProductModal: Script failed to load');
                    }
                }, 100);
            };
            window.openDeleteProductModal = fallbackFn;
        })();
    }
    if (typeof window.closeDeleteProductModal === 'undefined') {
        window.closeDeleteProductModal = function() {
            console.log('closeDeleteProductModal called but product-modal.js not loaded');
        };
    }
    if (typeof window.openCategoryModal === 'undefined') {
        window.openCategoryModal = function() {
            console.log('openCategoryModal called but product-modal.js not loaded');
        };
    }
    </script>
</body>
</html>
