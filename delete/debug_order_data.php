<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;

echo "=== ORDER DATA DEBUG ===\n\n";

// Get some confirmed orders
$confirmedOrders = Order::where('confirmation_status', 'confirmed')
    ->with(['customer', 'orderItems.product'])
    ->orderBy('confirmed_at', 'desc')
    ->limit(5)
    ->get();

echo "CONFIRMED ORDERS:\n";
echo "================\n";
foreach ($confirmedOrders as $order) {
    echo "ID: {$order->id}\n";
    echo "Order Number: {$order->order_number}\n";
    echo "Barcode: " . ($order->barcode ?? 'NULL') . "\n";
    echo "Customer: {$order->customer_full_name}\n";
    echo "Status: {$order->confirmation_status}\n";
    echo "Confirmed At: " . ($order->confirmed_at ? $order->confirmed_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "---\n";
}

// Get some in-delivery orders
$inDeliveryOrders = Order::whereIn('confirmation_status', ['pickup_requested', 'in_delivery'])
    ->with(['customer', 'orderItems.product'])
    ->orderBy('pickup_requested_at', 'desc')
    ->limit(5)
    ->get();

echo "\nIN DELIVERY ORDERS:\n";
echo "==================\n";
foreach ($inDeliveryOrders as $order) {
    echo "ID: {$order->id}\n";
    echo "Order Number: {$order->order_number}\n";
    echo "Barcode: " . ($order->barcode ?? 'NULL') . "\n";
    echo "Customer: {$order->customer_full_name}\n";
    echo "Status: {$order->confirmation_status}\n";
    echo "Pickup Requested At: " . ($order->pickup_requested_at ? $order->pickup_requested_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "---\n";
}

echo "\n=== END DEBUG ===\n";

