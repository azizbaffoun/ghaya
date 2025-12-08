<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes - React App
Route::get('/', function () {
    return file_get_contents(public_path('index.html'));
});

// Global language switching route
Route::post('/language/switch', [\App\Http\Controllers\Admin\LanguageController::class, 'switchLanguage'])->name('language.switch');

// Dashboard redirect based on user role
Route::get('/dashboard', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        return redirect('/user-dashboard');
    }
    return redirect('/login');
})->middleware('auth');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', \App\Http\Controllers\Admin\ProductsController::class);
    
    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoriesController::class);
    
    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\OrdersController::class, 'allOrders'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\OrdersController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\OrdersController::class, 'store'])->name('store');
        Route::get('/search', [\App\Http\Controllers\Api\OrderController::class, 'search'])->name('search');
        Route::get('/new', [\App\Http\Controllers\Admin\OrdersController::class, 'newOrders'])->name('new');
        Route::get('/confirmed', [\App\Http\Controllers\Admin\OrdersController::class, 'confirmedOrders'])->name('confirmed');
        Route::get('/ready-pickup', [\App\Http\Controllers\Admin\OrdersController::class, 'readyForPickup'])->name('ready-pickup');
        Route::get('/in-delivery', [\App\Http\Controllers\Admin\OrdersController::class, 'inDelivery'])->name('in-delivery');
        
        Route::post('/bulk-confirm', [\App\Http\Controllers\Admin\OrdersController::class, 'bulkConfirm'])->name('bulk-confirm');
        Route::post('/bulk-cancel', [\App\Http\Controllers\Admin\OrdersController::class, 'bulkCancel'])->name('bulk-cancel');
        Route::post('/bulk-print', [\App\Http\Controllers\Admin\OrdersController::class, 'bulkPrint'])->name('bulk-print');
        Route::post('/bulk-pickup', [\App\Http\Controllers\Admin\OrdersController::class, 'bulkPickup'])->name('bulk-pickup');
        
        Route::get('/{order}/edit', [\App\Http\Controllers\Admin\OrdersController::class, 'edit'])->name('edit');
        Route::put('/{order}', [\App\Http\Controllers\Admin\OrdersController::class, 'update'])->name('update');
        Route::delete('/{order}', [\App\Http\Controllers\Admin\OrdersController::class, 'destroy'])->name('destroy');
        Route::patch('/{order}/notes', [\App\Http\Controllers\Admin\OrdersController::class, 'updateOrderNotes'])->name('update-notes');
        Route::post('/{order}/reminder', [\App\Http\Controllers\Admin\OrdersController::class, 'setReminder'])->name('set-reminder');
        Route::get('/{order}/details', [\App\Http\Controllers\Admin\OrdersController::class, 'show'])->name('show');
        Route::patch('/{order}/status', [\App\Http\Controllers\Admin\OrdersController::class, 'updateStatus'])->name('update-status');
        Route::post('/{order}/quick-confirm', [\App\Http\Controllers\Admin\OrdersController::class, 'quickConfirm'])->name('quick-confirm');
        Route::post('/{order}/quick-cancel', [\App\Http\Controllers\Admin\OrdersController::class, 'quickCancel'])->name('quick-cancel');
        Route::post('/{order}/quick-pickup', [\App\Http\Controllers\Admin\OrdersController::class, 'quickPickup'])->name('quick-pickup');
        Route::post('/{order}/mark-delivered', [\App\Http\Controllers\Admin\OrdersController::class, 'markAsDelivered'])->name('mark-delivered');
        Route::post('/{order}/quick-print', [\App\Http\Controllers\Admin\OrdersController::class, 'quickPrint'])->name('quick-print');
        Route::post('/{order}/check-delivery-status', [\App\Http\Controllers\Admin\OrdersController::class, 'checkDeliveryStatus'])->name('check-delivery-status');
    });
    
    // Customers
    Route::resource('customers', \App\Http\Controllers\Admin\CustomersController::class);
    
    // Languages
    Route::resource('languages', \App\Http\Controllers\Admin\LanguageController::class);
    Route::patch('languages/{language}/toggle', [\App\Http\Controllers\Admin\LanguageController::class, 'toggle'])->name('languages.toggle');
    Route::post('languages/{language}/set-default', [\App\Http\Controllers\Admin\LanguageController::class, 'setDefault'])->name('languages.set-default');
    Route::post('languages/switch', [\App\Http\Controllers\Admin\LanguageController::class, 'switchLanguage'])->name('languages.switch');
    
    // Banners
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);
    Route::patch('banners/{banner}/toggle', [\App\Http\Controllers\Admin\BannerController::class, 'toggle'])->name('banners.toggle');
    
    // Tests
    Route::get('tests', [\App\Http\Controllers\Admin\TestController::class, 'index'])->name('tests.index');
    Route::post('tests/create-order', [\App\Http\Controllers\Admin\TestController::class, 'createTestOrder'])->name('tests.create-order');
    Route::post('tests/api-test', [\App\Http\Controllers\Admin\TestController::class, 'testFirstDeliveryApi'])->name('tests.api-test');
    Route::post('tests/workflow', [\App\Http\Controllers\Admin\TestController::class, 'testOrderWorkflow'])->name('tests.workflow');
    Route::post('tests/confirm-order', [\App\Http\Controllers\Admin\TestController::class, 'confirmTestOrder'])->name('tests.confirm-order');
    Route::get('tests/orders', [\App\Http\Controllers\Admin\TestController::class, 'getTestOrders'])->name('tests.orders');
    Route::get('tests/all-orders', [\App\Http\Controllers\Admin\TestController::class, 'getAllOrders'])->name('tests.all-orders');
    Route::post('tests/print-response', [\App\Http\Controllers\Admin\TestController::class, 'printOrderResponse'])->name('tests.print-response');
    
    // Enhanced test routes
    Route::post('tests/system-health', [\App\Http\Controllers\Admin\TestController::class, 'getSystemHealth'])->name('tests.system-health');
    Route::post('tests/api-key-diagnostics', [\App\Http\Controllers\Admin\TestController::class, 'testApiKeyEncryption'])->name('tests.api-key-diagnostics');
    Route::post('tests/payload-preview', [\App\Http\Controllers\Admin\TestController::class, 'getPayloadPreview'])->name('tests.payload-preview');
    Route::post('tests/bulk-create', [\App\Http\Controllers\Admin\TestController::class, 'bulkCreateTest'])->name('tests.bulk-create');
    Route::post('tests/clear-test-data', [\App\Http\Controllers\Admin\TestController::class, 'clearTestData'])->name('tests.clear-test-data');
    Route::get('tests/export-data', [\App\Http\Controllers\Admin\TestController::class, 'exportTestData'])->name('tests.export-data');
    Route::get('tests/performance-metrics', [\App\Http\Controllers\Admin\TestController::class, 'getPerformanceMetrics'])->name('tests.performance-metrics');
    
    // Website Management
    Route::get('website', [\App\Http\Controllers\Admin\WebsiteController::class, 'index'])->name('website.index');
    
    // Page Section Management
    Route::post('sections/{id}/toggle', [\App\Http\Controllers\Admin\PageSectionController::class, 'toggle']);
    Route::put('sections/{id}', [\App\Http\Controllers\Admin\PageSectionController::class, 'update']);
    Route::post('sections', [\App\Http\Controllers\Admin\PageSectionController::class, 'store']);
    Route::delete('sections/{id}', [\App\Http\Controllers\Admin\PageSectionController::class, 'destroy']);
    Route::post('sections/reorder', [\App\Http\Controllers\Admin\PageSectionController::class, 'reorder']);
    
    // Banner Management
    Route::post('banners/{id}/toggle', [\App\Http\Controllers\Admin\BannerController::class, 'toggle']);
    Route::put('banners/{id}', [\App\Http\Controllers\Admin\BannerController::class, 'update']);
    Route::post('banners', [\App\Http\Controllers\Admin\BannerController::class, 'store']);
    Route::delete('banners/{id}', [\App\Http\Controllers\Admin\BannerController::class, 'destroy']);
    
    // Page Builder
    Route::get('page-builder', [\App\Http\Controllers\Admin\PageBuilderController::class, 'index'])->name('page-builder.index');
    Route::post('page-builder', [\App\Http\Controllers\Admin\PageBuilderController::class, 'store'])->name('page-builder.store');
    Route::put('page-builder/{pageBuilder}', [\App\Http\Controllers\Admin\PageBuilderController::class, 'update'])->name('page-builder.update');
    Route::delete('page-builder/{pageBuilder}', [\App\Http\Controllers\Admin\PageBuilderController::class, 'destroy'])->name('page-builder.destroy');
    Route::patch('page-builder/{pageBuilder}/toggle', [\App\Http\Controllers\Admin\PageBuilderController::class, 'toggle'])->name('page-builder.toggle');
    Route::post('page-builder/reorder', [\App\Http\Controllers\Admin\PageBuilderController::class, 'reorder'])->name('page-builder.reorder');
    Route::get('page-builder/categories', [\App\Http\Controllers\Admin\PageBuilderController::class, 'getCategories'])->name('page-builder.categories');
    Route::get('page-builder/products', [\App\Http\Controllers\Admin\PageBuilderController::class, 'getProducts'])->name('page-builder.products');
    Route::get('page-builder/banners', [\App\Http\Controllers\Admin\PageBuilderController::class, 'getBanners'])->name('page-builder.banners');
    
    // First Delivery
    Route::prefix('delivery')->name('delivery.')->group(function () {
        Route::get('/settings', [\App\Http\Controllers\Admin\FirstDeliveryController::class, 'settings'])->name('settings');
        Route::post('/settings', [\App\Http\Controllers\Admin\FirstDeliveryController::class, 'saveSettings'])->name('settings.save');
        Route::post('/sync/{order}', [\App\Http\Controllers\Admin\FirstDeliveryController::class, 'syncOrder'])->name('sync');
        Route::post('/bulk-sync', [\App\Http\Controllers\Admin\FirstDeliveryController::class, 'bulkSync'])->name('bulk-sync');
        Route::get('/status/{order}', [\App\Http\Controllers\Admin\FirstDeliveryController::class, 'checkStatus'])->name('status');
        Route::post('/cancel/{order}', [\App\Http\Controllers\Admin\FirstDeliveryController::class, 'cancelDelivery'])->name('cancel');
        Route::post('/pickup', [\App\Http\Controllers\Admin\FirstDeliveryController::class, 'requestPickup'])->name('pickup');
    });
    
    // Admin Tests
    Route::prefix('tests')->name('tests.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TestController::class, 'index'])->name('index');
        Route::post('/create-order', [\App\Http\Controllers\Admin\TestController::class, 'createTestOrder'])->name('create-order');
        Route::post('/confirm-order', [\App\Http\Controllers\Admin\TestController::class, 'confirmTestOrder'])->name('confirm-order');
        Route::post('/api-test', [\App\Http\Controllers\Admin\TestController::class, 'testFirstDeliveryApi'])->name('api-test');
        Route::post('/workflow', [\App\Http\Controllers\Admin\TestController::class, 'testOrderWorkflow'])->name('workflow');
        Route::get('/orders', [\App\Http\Controllers\Admin\TestController::class, 'getTestOrders'])->name('orders');
    });
});

// User dashboard (existing)
Route::get('/user-dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('user.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Catch-all route for React Router - handles client-side navigation
// MUST be last to avoid conflicting with Laravel routes
Route::get('/{any}', function () {
    return file_get_contents(public_path('index.html'));
})->where('any', '^(?!admin|api|login|register|dashboard|profile|language|storage|build|assets|favicon|robots|placeholder).*$');
