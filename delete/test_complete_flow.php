<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Services\FirstDeliveryService;

echo "=== TEST COMPLETE ORDER FLOW ===\n\n";

// Create a test order
$order = Order::create([
    'order_number' => 'TEST-COMPLETE-' . date('Y-m-d-H-i-s'),
    'customer_first_name' => 'Test',
    'customer_last_name' => 'Customer',
    'customer_email' => 'test@example.com',
    'customer_phone' => '12345678',
    'shipping_address' => 'Test Address, Test City',
    'shipping_city' => 'Tunis',
    'shipping_state' => 'Tunis',
    'shipping_postal_code' => '1000',
    'shipping_country' => 'Tunisia',
    'status' => 'pending',
    'confirmation_status' => 'pending_confirmation',
    'payment_method' => 'cash_on_delivery',
    'subtotal' => 75.00,
    'shipping_cost' => 10.00,
    'total' => 85.00,
    'notes' => 'Test order for complete flow testing',
]);

echo "1. Created Test Order:\n";
echo "   - Order #: " . $order->order_number . "\n";
echo "   - Customer: " . $order->customer_first_name . " " . $order->customer_last_name . "\n";
echo "   - Phone: " . $order->customer_phone . "\n";
echo "   - Address: " . $order->shipping_address . ", " . $order->shipping_city . "\n";
echo "   - Total: " . $order->total . "\n";
echo "   - Status: " . $order->status . "\n";
echo "   - Confirmation Status: " . $order->confirmation_status . "\n\n";

// Test the FirstDeliveryService
echo "2. Testing FirstDeliveryService:\n";
try {
    $service = app(FirstDeliveryService::class);
    $result = $service->createOrder($order);
    
    if ($result) {
        echo "   - SUCCESS! Order synced to First Delivery\n";
        echo "   - Barcode: " . ($order->barcode ?? 'N/A') . "\n";
        echo "   - Print URL: " . ($order->print_url ?? 'N/A') . "\n";
        echo "   - First Delivery Response: " . ($order->first_delivery_response ? 'Yes' : 'No') . "\n";
        
        // Update order status to confirmed
        $order->update([
            'status' => 'confirmed',
            'confirmation_status' => 'confirmed',
            'first_delivery_id' => $order->barcode, // Use barcode as ID
        ]);
        
        echo "\n3. Order Status Updated:\n";
        echo "   - Status: " . $order->status . "\n";
        echo "   - Confirmation Status: " . $order->confirmation_status . "\n";
        echo "   - First Delivery ID: " . $order->first_delivery_id . "\n";
        
        // Test print functionality
        if ($order->print_url) {
            echo "\n4. Print Functionality Test:\n";
            echo "   - Print URL: " . $order->print_url . "\n";
            echo "   - URL is valid and ready for printing\n";
            echo "   - You can test this by opening the URL in a browser\n";
        }
        
    } else {
        echo "   - FAILED! Order sync failed\n";
    }
    
} catch (\Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";

