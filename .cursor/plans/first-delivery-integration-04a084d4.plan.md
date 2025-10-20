<!-- 04a084d4-de18-4c7a-b94f-86c26a139db0 01a10f20-ee18-48c7-99b0-ef3b01bf5a4b -->
# First Delivery API Integration Plan

## Overview

Integrate First Delivery API into the Laravel admin panel with a collapsible sidebar menu, settings management, and automatic order submission with full API operations support.

## Implementation Steps

### 1. Database Setup

**Migration: `create_first_delivery_settings_table.php`**

- Create `first_delivery_settings` table with fields:
  - `id`, `first_delivery_key`, `delivery_cost`, `return_cost`
  - `store_name`, `store_phone`, `store_address`, `store_city`
  - `vat_number`, `allow_open_package` (boolean)
  - `is_enabled` (boolean), `timestamps`

**Migration: `add_first_delivery_fields_to_orders_table.php`**

- Add columns to `orders` table:
  - `first_delivery_id` (nullable) - FD order ID
  - `first_delivery_tracking_number` (nullable)
  - `first_delivery_status` (nullable)
  - `first_delivery_response` (nullable, JSON) - store full API response

### 2. Model Creation

**File: `app/Models/FirstDeliverySetting.php`**

- Create model with fillable fields
- Add encrypted casting for `first_delivery_key`
- Static method `getSettings()` to retrieve active config

**Update: `app/Models/Order.php`**

- Add new fillable fields for First Delivery tracking
- Add casts for `first_delivery_response` as JSON
- Add method `syncToFirstDelivery()` for API submission

### 3. Service Layer

**File: `app/Services/FirstDeliveryService.php`**

- Create comprehensive service class with methods:
  - `createOrder($order)` - Submit single order to FD API
  - `bulkCreateOrders($orders)` - Bulk order creation
  - `checkOrderStatus($trackingNumber)` - Check order status
  - `filterOrders($filters)` - Filter orders with criteria
  - `cancelOrder($trackingNumber)` - Cancel delivery
  - `requestPickup($data)` - Request pickup
- Include proper error handling and logging
- Use Guzzle HTTP client for API requests

### 4. Controller Implementation

**File: `app/Http/Controllers/Admin/FirstDeliveryController.php`**

- `settings()` - Show settings page (GET)
- `saveSettings()` - Save API configuration (POST)
- `syncOrder($orderId)` - Manually sync specific order (POST)
- `bulkSync()` - Bulk sync multiple orders (POST)
- `checkStatus($orderId)` - Check delivery status (GET)
- `cancelDelivery($orderId)` - Cancel delivery (POST)
- `requestPickup()` - Request pickup (POST)

### 5. Routes Configuration

**File: `routes/web.php`**

Add within admin middleware group:

```php
Route::prefix('delivery')->name('delivery.')->group(function () {
    Route::get('/settings', [FirstDeliveryController::class, 'settings'])->name('settings');
    Route::post('/settings', [FirstDeliveryController::class, 'saveSettings'])->name('settings.save');
    Route::post('/sync/{order}', [FirstDeliveryController::class, 'syncOrder'])->name('sync');
    Route::post('/bulk-sync', [FirstDeliveryController::class, 'bulkSync'])->name('bulk-sync');
    Route::get('/status/{order}', [FirstDeliveryController::class, 'checkStatus'])->name('status');
    Route::post('/cancel/{order}', [FirstDeliveryController::class, 'cancelDelivery'])->name('cancel');
    Route::post('/pickup', [FirstDeliveryController::class, 'requestPickup'])->name('pickup');
});
```

### 6. Admin Layout Update

**File: `resources/views/admin/layout.blade.php`** (lines 160-174)

Add collapsible "Delivery" menu item before "Settings" divider:

```blade
<div x-data="{ deliveryOpen: false }" class="space-y-1">
    <button @click="deliveryOpen = !deliveryOpen" class="sidebar-item w-full...">
        <!-- Truck icon + "Delivery" text + chevron -->
    </button>
    <div x-show="deliveryOpen" class="pl-4 space-y-1">
        <a href="{{ route('admin.delivery.settings') }}" class="sidebar-item...">
            Settings
        </a>
    </div>
</div>
```

### 7. Settings View

**File: `resources/views/admin/delivery/settings.blade.php`**

- Create form matching screenshot design with:
  - Toggle for "Enable First Delivery"
  - API Key input field
  - Delivery Cost & Return Cost inputs
  - Store details: Name, Phone, Address, City (dropdown)
  - VAT Number input
  - "Allow to open package" toggle
  - Save button with gradient styling
- Include success/error flash messages
- Use Alpine.js for toggle interactions

### 8. Order Auto-Sync Integration

**Update: `app/Http/Controllers/Admin/OrdersController.php`**

- In `updateStatus()` method:
  - When order status changes to "confirmed" or "processing"
  - Check if First Delivery is enabled
  - Automatically call `FirstDeliveryService::createOrder()`
  - Store response in order record
  - Show success/error notification

### 9. Orders Index Enhancement

**File: `resources/views/admin/orders/index.blade.php`**

Add action buttons for each order:

- "Sync to FD" button (if not synced)
- "Check Status" button (if synced)
- "Cancel Delivery" button (if synced and not delivered)
- Display First Delivery tracking number if available
- Show delivery status badge

### 10. API Action Buttons Panel

Create section in orders page with:

- **Bulk Sync** - Sync multiple selected orders
- **Check Status** - Batch status check
- **Request Pickup** - Form to request pickup with date/time selection
- Use modals/dropdowns for better UX

### 11. Configuration & Environment

**File: `.env`**

Add configuration keys:

```
FIRST_DELIVERY_API_URL=https://api.firstdelivery.com
FIRST_DELIVERY_TIMEOUT=30
```

**File: `config/services.php`**

```php
'first_delivery' => [
    'api_url' => env('FIRST_DELIVERY_API_URL'),
    'timeout' => env('FIRST_DELIVERY_TIMEOUT', 30),
],
```

## Key Files to Create/Modify

- **New:** `database/migrations/*_create_first_delivery_settings_table.php`
- **New:** `database/migrations/*_add_first_delivery_fields_to_orders_table.php`
- **New:** `app/Models/FirstDeliverySetting.php`
- **New:** `app/Services/FirstDeliveryService.php`
- **New:** `app/Http/Controllers/Admin/FirstDeliveryController.php`
- **New:** `resources/views/admin/delivery/settings.blade.php`
- **Modify:** `routes/web.php`
- **Modify:** `resources/views/admin/layout.blade.php`
- **Modify:** `app/Models/Order.php`
- **Modify:** `app/Http/Controllers/Admin/OrdersController.php`
- **Modify:** `resources/views/admin/orders/index.blade.php`
- **Modify:** `config/services.php`

## Technical Notes

- Use Guzzle HTTP client (already in Laravel)
- Store API responses as JSON for debugging
- Add comprehensive error logging
- Implement queue jobs for bulk operations (optional enhancement)
- Add validation for all form inputs
- Ensure proper CSRF protection on all POST routes

### To-dos

- [ ] Create database migrations for first_delivery_settings table and add First Delivery tracking fields to orders table
- [ ] Create FirstDeliverySetting model and update Order model with First Delivery fields
- [ ] Build FirstDeliveryService with all API methods (create, bulk-create, check, filter, cancel, pickup)
- [ ] Create FirstDeliveryController and add delivery routes to web.php
- [ ] Update admin layout sidebar with collapsible Delivery menu
- [ ] Create delivery settings view with form matching the screenshot design
- [ ] Implement automatic order sync to First Delivery when order is confirmed
- [ ] Add First Delivery action buttons and status display to orders index page
- [ ] Add First Delivery configuration to services.php and .env.example