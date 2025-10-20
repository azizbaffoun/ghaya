<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;

echo "=== ORDERS WITH BARCODES DEBUG ===\n\n";

// Get orders that have barcodes
$ordersWithBarcodes = Order::whereNotNull('barcode')
    ->with(['customer', 'orderItems.product'])
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

echo "ORDERS WITH BARCODES:\n";
echo "====================\n";
foreach ($ordersWithBarcodes as $order) {
    echo "ID: {$order->id}\n";
    echo "Order Number: {$order->order_number}\n";
    echo "Barcode: {$order->barcode}\n";
    echo "Customer: {$order->customer_full_name}\n";
    echo "Status: {$order->confirmation_status}\n";
    echo "First Delivery ID: " . ($order->first_delivery_id ?? 'NULL') . "\n";
    echo "Print URL: " . ($order->print_url ?? 'NULL') . "\n";
    echo "---\n";
}

// Check if any orders have barcode in order_number field
$suspiciousOrders = Order::where('order_number', 'like', '%683375045049%')
    ->orWhere('order_number', 'like', '%683375045050%')
    ->orWhere('order_number', 'like', '%683375045051%')
    ->orWhere('order_number', 'like', '%683375045052%')
    ->orWhere('order_number', 'like', '%683375045053%')
    ->orWhere('order_number', 'like', '%683375045054%')
    ->orWhere('order_number', 'like', '%683375045055%')
    ->orWhere('order_number', 'like', '%683375045056%')
    ->orWhere('order_number', 'like', '%683375045057%')
    ->orWhere('order_number', 'like', '%683375045058%')
    ->orWhere('order_number', 'like', '%683375045059%')
    ->orWhere('order_number', 'like', '%683375045060%')
    ->get();

echo "\nSUSPICIOUS ORDERS (barcode-like order numbers):\n";
echo "==============================================\n";
foreach ($suspiciousOrders as $order) {
    echo "ID: {$order->id}\n";
    echo "Order Number: {$order->order_number}\n";
    echo "Barcode: " . ($order->barcode ?? 'NULL') . "\n";
    echo "Customer: {$order->customer_full_name}\n";
    echo "Status: {$order->confirmation_status}\n";
    echo "---\n";
}

echo "\n=== END DEBUG ===\n";