<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;

echo "=== TESTING ORDER DISPLAY ===\n\n";

// Get a confirmed order with barcode
$order = Order::where('confirmation_status', 'confirmed')
    ->whereNotNull('barcode')
    ->first();

if ($order) {
    echo "Order ID: {$order->id}\n";
    echo "Order Number: {$order->order_number}\n";
    echo "Barcode: {$order->barcode}\n";
    echo "Status: {$order->confirmation_status}\n";
    echo "Customer: {$order->customer_full_name}\n";
    echo "\n";
    
    // Test what the view would display
    echo "=== VIEW TEMPLATE TEST ===\n";
    echo "Order Number Column: #{$order->order_number}\n";
    echo "Barcode Column: {$order->barcode}\n";
    echo "\n";
    
    // Check if there's any issue with the data
    echo "=== DATA INTEGRITY CHECK ===\n";
    echo "Order Number Length: " . strlen($order->order_number) . "\n";
    echo "Barcode Length: " . strlen($order->barcode) . "\n";
    echo "Order Number Type: " . gettype($order->order_number) . "\n";
    echo "Barcode Type: " . gettype($order->barcode) . "\n";
    echo "\n";
    
    // Check if order_number contains barcode
    if (strpos($order->order_number, $order->barcode) !== false) {
        echo "WARNING: Order number contains barcode!\n";
    } else {
        echo "OK: Order number does not contain barcode\n";
    }
    
    // Check if barcode contains order_number
    if (strpos($order->barcode, $order->order_number) !== false) {
        echo "WARNING: Barcode contains order number!\n";
    } else {
        echo "OK: Barcode does not contain order number\n";
    }
} else {
    echo "No confirmed orders with barcodes found\n";
}

echo "\n=== END TEST ===\n";
