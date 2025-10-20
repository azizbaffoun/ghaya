<?php

require_once 'vendor/autoload.php';

use App\Models\Order;
use App\Models\FirstDeliverySetting;
use App\Services\FirstDeliveryService;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ORDER CONFIRMATION DEBUG ===\n\n";

// Check First Delivery settings
echo "1. First Delivery Settings:\n";
$settings = FirstDeliverySetting::getSettings();
echo "   - Enabled: " . ($settings->is_enabled ? 'Yes' : 'No') . "\n";
echo "   - API Key: " . ($settings->first_delivery_key ? 'Set' : 'Not Set') . "\n";
echo "   - Store Name: " . ($settings->store_name ?? 'Not Set') . "\n\n";

// Get recent confirmed orders
echo "2. Recent Confirmed Orders:\n";
$confirmedOrders = Order::where('confirmation_status', 'confirmed')
    ->orderBy('confirmed_at', 'desc')
    ->limit(5)
    ->get();

foreach ($confirmedOrders as $order) {
    echo "   - Order #{$order->order_number} (ID: {$order->id})\n";
    echo "     Status: {$order->status}\n";
    echo "     Confirmation: {$order->confirmation_status}\n";
    echo "     First Delivery ID: " . ($order->first_delivery_id ?? 'None') . "\n";
    echo "     Print URL: " . ($order->print_url ?? 'None') . "\n";
    echo "     Barcode: " . ($order->barcode ?? 'None') . "\n";
    echo "     First Delivery Response: " . ($order->first_delivery_response ? 'Yes' : 'No') . "\n";
    echo "     Confirmed At: " . ($order->confirmed_at ?? 'Not set') . "\n\n";
}

// Test First Delivery API connection
echo "3. Testing First Delivery API:\n";
if ($settings->first_delivery_key) {
    try {
        $service = new FirstDeliveryService();
        echo "   - Service initialized successfully\n";
        
        // Try to get a test order
        $testOrder = Order::where('confirmation_status', 'confirmed')
            ->whereNull('first_delivery_id')
            ->first();
            
        if ($testOrder) {
            echo "   - Found test order: #{$testOrder->order_number}\n";
            echo "   - Attempting to sync to First Delivery...\n";
            
            $result = $service->createOrder($testOrder);
            
            if ($result) {
                echo "   - SUCCESS: Order synced to First Delivery\n";
                echo "   - Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
            } else {
                echo "   - FAILED: Could not sync order to First Delivery\n";
            }
        } else {
            echo "   - No suitable test order found\n";
        }
    } catch (Exception $e) {
        echo "   - ERROR: " . $e->getMessage() . "\n";
    }
} else {
    echo "   - No API key configured\n";
}

echo "\n=== DEBUG COMPLETE ===\n";

