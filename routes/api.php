<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\HomepageController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Admin\HomepageSectionController;
use App\Http\Controllers\Api\Admin\HomepageAssetController;
use App\Http\Controllers\Api\Admin\ProductImageController;
use App\Http\Controllers\Api\Admin\ProductVariantController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API Routes (no authentication required)
Route::prefix('v1')->group(function () {
    
    // Authentication Routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [PasswordController::class, 'forgotPassword']);
        Route::post('/reset-password', [PasswordController::class, 'resetPassword']);
    });
    
    // Products API
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/new', [ProductController::class, 'new']);
    Route::get('/products/related/{id}', [ProductController::class, 'related']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/{id}/variants', [ProductVariantController::class, 'index']);
    
    // Categories API
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/categories/{id}/products', [CategoryController::class, 'getProducts']);
    
    // Homepage API
    Route::get('/homepage', [HomepageController::class, 'index']);
    Route::get('/homepage/{type}', [HomepageController::class, 'show']);
    Route::get('/banners', [HomepageController::class, 'banners']);
    Route::get('/homepage-sections', [HomepageController::class, 'sections']);
    
    // Languages API
    Route::get('/languages', [LanguageController::class, 'index']);
    Route::get('/languages/default', [LanguageController::class, 'default']);
    
    // Cart API (session-based)
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/update/{id}', [CartController::class, 'update']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);
    Route::get('/cart/count', [CartController::class, 'count']);
    
    // Checkout API
    Route::post('/checkout/validate', [CheckoutController::class, 'validateCheckout']);
    Route::post('/checkout/calculate', [CheckoutController::class, 'calculate']);
    
    // Orders API
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show']);
    
    // Delivery settings
    Route::get('/delivery/settings', function () {
        $settings = \App\Models\FirstDeliverySetting::first();
        return response()->json([
            'delivery_cost' => $settings ? $settings->delivery_cost : 0,
            'is_enabled' => $settings ? $settings->is_enabled : false,
        ]);
    });
    
    // Homepage API
    Route::get('/homepage', [HomepageController::class, 'index']);
    Route::get('/homepage/sections/{type}', [HomepageController::class, 'getSectionByType']);
    
    // Search API
    Route::get('/search', [\App\Http\Controllers\Api\SearchController::class, 'search']);
    Route::get('/search/suggestions', [\App\Http\Controllers\Api\SearchController::class, 'suggestions']);
    Route::get('/products/filters', [\App\Http\Controllers\Api\SearchController::class, 'getProductFilters']);
    
});

// Customer API Routes (customer authentication required)
Route::prefix('v1')->middleware(['auth:sanctum', 'customer'])->group(function () {
    
    // Customer Authentication
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/change-password', [PasswordController::class, 'changePassword']);
    
    // Customer Profile
    Route::get('/customer/profile', [CustomerController::class, 'profile']);
    Route::put('/customer/profile', [CustomerController::class, 'updateProfile']);
    Route::get('/customer/orders', [CustomerController::class, 'orders']);
    Route::get('/customer/addresses', [CustomerController::class, 'addresses']);
    Route::post('/customer/addresses', [CustomerController::class, 'addAddress']);
    
    // Customer Avatar
    Route::post('/customer/avatar', [ProfileController::class, 'uploadAvatar']);
    Route::delete('/customer/avatar', [ProfileController::class, 'removeAvatar']);
    
    // Customer Cart (authenticated)
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/update/{id}', [CartController::class, 'update']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);
    Route::get('/cart/count', [CartController::class, 'count']);
    
});

// Admin API Routes (admin authentication required)
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    
    // Products Management
    Route::apiResource('products', ProductController::class);
    Route::post('/products/{id}/toggle-featured', [ProductController::class, 'toggleFeatured']);
    Route::post('/products/{id}/images', [ProductImageController::class, 'store']);
    Route::post('/products/{id}/images/bulk', [ProductImageController::class, 'bulkUpload']);
    Route::delete('/products/{id}/images/{imageId}', [ProductImageController::class, 'destroy']);
    Route::put('/products/{id}/images/{imageId}/primary', [ProductImageController::class, 'setPrimary']);
    
    // Product Variants Management
    Route::post('/products/{id}/variants', [ProductVariantController::class, 'store']);
    Route::put('/products/{id}/variants/{variantId}', [ProductVariantController::class, 'update']);
    Route::delete('/products/{id}/variants/{variantId}', [ProductVariantController::class, 'destroy']);
    
    // Categories Management
    Route::apiResource('categories', CategoryController::class);
    Route::post('/categories/{id}/image', [CategoryController::class, 'uploadImage']);
    
    // Customers Management
    Route::apiResource('customers', \App\Http\Controllers\Admin\CustomersController::class);
    Route::get('/customers/{id}/orders', [CustomerController::class, 'orders']);
    
    // Orders Management
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/search', [OrderController::class, 'search']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    
    // Banners Management
    Route::apiResource('banners', \App\Http\Controllers\Api\Admin\BannerController::class);
    Route::post('banners/{id}/upload-image', [\App\Http\Controllers\Api\Admin\BannerController::class, 'uploadImage']);
    
    // Languages Management
    Route::apiResource('languages', \App\Http\Controllers\Admin\LanguageController::class);
    Route::patch('/languages/{id}/toggle', [\App\Http\Controllers\Admin\LanguageController::class, 'toggle']);
    Route::post('/languages/{id}/set-default', [\App\Http\Controllers\Admin\LanguageController::class, 'setDefault']);
    
    // Homepage Sections Management
    Route::apiResource('homepage-sections', \App\Http\Controllers\Api\Admin\HomepageSectionController::class);
    Route::put('homepage-sections/reorder', [\App\Http\Controllers\Api\Admin\HomepageSectionController::class, 'reorder']);
    
    // Homepage Assets Management
    Route::apiResource('homepage-assets', HomepageAssetController::class);
    Route::post('/homepage-assets/bulk-upload', [HomepageAssetController::class, 'bulkUpload']);
    
    // Dashboard Statistics
    Route::get('/dashboard/stats', [\App\Http\Controllers\Api\Admin\DashboardController::class, 'stats']);
    Route::get('/dashboard/recent-orders', [\App\Http\Controllers\Api\Admin\DashboardController::class, 'recentOrders']);
    Route::get('/dashboard/top-products', [\App\Http\Controllers\Api\Admin\DashboardController::class, 'topProducts']);
    Route::get('/dashboard/revenue', [\App\Http\Controllers\Api\Admin\DashboardController::class, 'revenue']);
    
});

// Legacy route for user authentication (keeping for compatibility)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
