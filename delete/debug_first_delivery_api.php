<?php

require_once 'vendor/autoload.php';

use App\Models\Order;
use App\Models\FirstDeliverySetting;
use App\Services\FirstDeliveryService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FIRST DELIVERY API DEBUG ===\n\n";

// Get settings
$settings = FirstDeliverySetting::getSettings();
echo "1. Settings:\n";
echo "   - API Key: " . ($settings->first_delivery_key ? 'Set' : 'Not Set') . "\n";
echo "   - API URL: " . config('services.first_delivery.api_url') . "\n\n";

// Get a test order
$order = Order::where('confirmation_status', 'confirmed')
    ->whereNull('first_delivery_id')
    ->first();

if (!$order) {
    echo "No test order found\n";
    exit;
}

echo "2. Test Order:\n";
echo "   - Order #: {$order->order_number}\n";
echo "   - Customer: {$order->customer_first_name} {$order->customer_last_name}\n";
echo "   - Phone: {$order->customer_phone}\n";
echo "   - Address: {$order->shipping_address}\n";
echo "   - Total: {$order->total}\n\n";

// Build payload manually
$service = new FirstDeliveryService();
$payload = $service->buildOrderPayload($order);

echo "3. Payload:\n";
echo json_encode($payload, JSON_PRETTY_PRINT) . "\n\n";

// Test API call manually
echo "4. Testing API Call:\n";

try {
    $client = new Client([
        'base_uri' => config('services.first_delivery.api_url', 'https://www.firstdeliverygroup.com/api/v2'),
        'timeout' => 30,
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]
    ]);

    $response = $client->post('/create', [
        'json' => $payload,
        'headers' => [
            'Authorization' => 'Bearer ' . $settings->first_delivery_key,
        ]
    ]);

    $responseData = json_decode($response->getBody()->getContents(), true);
    
    echo "   - SUCCESS!\n";
    echo "   - Status Code: " . $response->getStatusCode() . "\n";
    echo "   - Response: " . json_encode($responseData, JSON_PRETTY_PRINT) . "\n";

} catch (RequestException $e) {
    echo "   - FAILED!\n";
    echo "   - Error: " . $e->getMessage() . "\n";
    
    if ($e->hasResponse()) {
        $response = $e->getResponse();
        echo "   - Status Code: " . $response->getStatusCode() . "\n";
        echo "   - Response Body: " . $response->getBody()->getContents() . "\n";
    }
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n";