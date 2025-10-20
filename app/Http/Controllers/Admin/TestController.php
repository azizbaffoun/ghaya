<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Services\FirstDeliveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function index()
    {
        return view('admin.tests.index');
    }

    public function createTestOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'total' => 'required|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Split customer name
            $nameParts = explode(' ', trim($request->customer_name), 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

            // Generate order number
            $orderNumber = $this->generateOrderNumber();

            // Create test order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_first_name' => $firstName,
                'customer_last_name' => $lastName,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => 'Tunis',
                'shipping_state' => 'Tunis',
                'shipping_postal_code' => '1000',
                'shipping_country' => 'Tunisia',
                'total' => $request->total,
                'subtotal' => $request->total - ($request->shipping_cost ?? 0),
                'shipping_cost' => $request->shipping_cost ?? 0,
                'status' => 'pending',
                'confirmation_status' => 'pending_confirmation',
                'payment_status' => 'pending',
                'payment_method' => 'cash_on_delivery',
                'notes' => 'Test order created from admin test page',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Test order created successfully',
                'order' => $order->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create test order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create test order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testFirstDeliveryApi(Request $request)
    {
        $request->validate([
            'api_key' => 'nullable|string',
            'test_type' => 'required|in:auth,create_order,check_status,request_pickup,cancel_delivery',
            'order_id' => 'nullable|exists:orders,id',
            'barcode' => 'nullable|string'
        ]);

        try {
            // Use provided API key or get from database settings
            $apiKey = $request->api_key;
            if (!$apiKey) {
                $settings = \App\Models\FirstDeliverySetting::getSettings();
                $apiKey = $settings->first_delivery_key;
            }
            
            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'No API key provided or configured in settings'
                ], 400);
            }

            // Temporarily update the API key for testing
            $originalApiKey = config('services.first_delivery.api_key');
            config(['services.first_delivery.api_key' => $apiKey]);

            $service = app(FirstDeliveryService::class);
            $result = null;
            $message = '';

            switch ($request->test_type) {
                case 'auth':
                    // Test with status check instead of health endpoint
                    if (!$request->barcode) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Barcode is required for auth test (using status check)'
                        ], 400);
                    }
                    $result = $service->checkDeliveryStatus($request->barcode);
                    $message = 'Authentication test completed (via status check)';
                    break;

                case 'create_order':
                    if (!$request->order_id) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Order ID is required for create_order test'
                        ], 400);
                    }
                    $order = Order::findOrFail($request->order_id);
                    $result = $service->createOrder($order);
                    $message = 'Order creation test completed';
                    break;

                case 'check_status':
                    if (!$request->barcode) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Barcode is required for check_status test'
                        ], 400);
                    }
                    $result = $service->checkDeliveryStatus($request->barcode);
                    $message = 'Status check test completed';
                    break;

                case 'request_pickup':
                    if (!$request->barcode) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Barcode is required for request_pickup test'
                        ], 400);
                    }
                    $result = $service->requestPickup([$request->barcode]);
                    $message = 'Pickup request test completed';
                    break;

                case 'cancel_delivery':
                    if (!$request->barcode) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Barcode is required for cancel_delivery test'
                        ], 400);
                    }
                    $result = $service->cancelDelivery($request->barcode);
                    $message = 'Delivery cancellation test completed';
                    break;
            }

            // Restore original API key
            config(['services.first_delivery.api_key' => $originalApiKey]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'result' => $result,
                'test_type' => $request->test_type
            ]);

        } catch (\Exception $e) {
            // Restore original API key
            config(['services.first_delivery.api_key' => $originalApiKey]);

            Log::error('First Delivery API test failed', [
                'test_type' => $request->test_type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'API test failed: ' . $e->getMessage(),
                'test_type' => $request->test_type
            ], 500);
        }
    }

    public function testOrderWorkflow(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'api_key' => 'nullable|string'
        ]);

        try {
            $order = Order::findOrFail($request->order_id);
            $results = [];
            $steps = [];

            // Use provided API key or get from database settings
            $apiKey = $request->api_key;
            if (!$apiKey) {
                $settings = \App\Models\FirstDeliverySetting::getSettings();
                $apiKey = $settings->first_delivery_key;
            }
            
            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'No API key provided or configured in settings'
                ], 400);
            }

            // Temporarily update the API key
            $originalApiKey = config('services.first_delivery.api_key');
            config(['services.first_delivery.api_key' => $apiKey]);

            $service = app(FirstDeliveryService::class);

            // Step 1: Confirm Order
            $steps[] = 'Confirming order...';
            try {
                $confirmResult = $service->createOrder($order);
                if ($confirmResult) {
                    $order->update([
                        'confirmation_status' => 'confirmed',
                        'confirmed_at' => now(),
                        'barcode' => $confirmResult['result']['barCode'] ?? null,
                        'print_url' => $confirmResult['result']['link'] ?? null,
                        'first_delivery_response' => $confirmResult
                    ]);
                    $results['confirm'] = ['success' => true, 'data' => $confirmResult];
                    $steps[] = '✅ Order confirmed successfully';
                } else {
                    $results['confirm'] = ['success' => false, 'message' => 'Failed to confirm order'];
                    $steps[] = '❌ Order confirmation failed';
                }
            } catch (\Exception $e) {
                $results['confirm'] = ['success' => false, 'message' => $e->getMessage()];
                $steps[] = '❌ Order confirmation failed: ' . $e->getMessage();
            }

            // Step 2: Mark as Printed
            $steps[] = 'Marking as printed...';
            $order->update([
                'confirmation_status' => 'printed',
                'printed_at' => now()
            ]);
            $results['print'] = ['success' => true];
            $steps[] = '✅ Order marked as printed';

            // Step 3: Request Pickup (if barcode exists)
            if ($order->barcode) {
                $steps[] = 'Requesting pickup...';
                try {
                    $pickupResult = $service->requestPickup([$order->barcode]);
                    if ($pickupResult) {
                        $order->update([
                            'confirmation_status' => 'pickup_requested',
                            'pickup_requested_at' => now()
                        ]);
                        $results['pickup'] = ['success' => true, 'data' => $pickupResult];
                        $steps[] = '✅ Pickup requested successfully';
                    } else {
                        $results['pickup'] = ['success' => false, 'message' => 'Failed to request pickup'];
                        $steps[] = '❌ Pickup request failed';
                    }
                } catch (\Exception $e) {
                    $results['pickup'] = ['success' => false, 'message' => $e->getMessage()];
                    $steps[] = '❌ Pickup request failed: ' . $e->getMessage();
                }
            } else {
                $steps[] = '⚠️ No barcode available for pickup request';
            }

            // Step 4: Check Status (if barcode exists)
            if ($order->barcode) {
                $steps[] = 'Checking delivery status...';
                try {
                    $statusResult = $service->checkDeliveryStatus($order->barcode);
                    $results['status'] = ['success' => true, 'data' => $statusResult];
                    $steps[] = '✅ Status checked successfully';
                } catch (\Exception $e) {
                    $results['status'] = ['success' => false, 'message' => $e->getMessage()];
                    $steps[] = '❌ Status check failed: ' . $e->getMessage();
                }
            }

            // Restore original API key
            config(['services.first_delivery.api_key' => $originalApiKey]);

            return response()->json([
                'success' => true,
                'message' => 'Order workflow test completed',
                'results' => $results,
                'steps' => $steps,
                'order' => $order->fresh()
            ]);

        } catch (\Exception $e) {
            // Restore original API key
            config(['services.first_delivery.api_key' => $originalApiKey]);

            Log::error('Order workflow test failed', [
                'order_id' => $request->order_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Workflow test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirmTestOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'api_key' => 'nullable|string'
        ]);

        try {
            $order = Order::findOrFail($request->order_id);
            
            if ($order->confirmation_status !== 'pending_confirmation') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order is not in pending confirmation status.'
                ], 400);
            }

            // Use provided API key or get from database settings
            $apiKey = $request->api_key;
            if (!$apiKey) {
                $settings = \App\Models\FirstDeliverySetting::getSettings();
                $apiKey = $settings->first_delivery_key;
            }
            
            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'No API key provided or configured in settings'
                ], 400);
            }

            // Temporarily update the API key
            $originalApiKey = config('services.first_delivery.api_key');
            config(['services.first_delivery.api_key' => $apiKey]);

            // Set to processing
            $order->update([
                'status' => 'processing',
                'confirmation_status' => 'confirmed',
                'confirmed_at' => now()
            ]);

            $service = app(FirstDeliveryService::class);
            $result = $service->createOrder($order);

            if ($result && isset($result['result'])) {
                // API call successful
                $order->update([
                    'confirmation_status' => 'printed',
                    'barcode' => $result['result']['barCode'] ?? null,
                    'print_url' => $result['result']['link'] ?? null,
                    'first_delivery_id' => $result['result']['barCode'] ?? null,
                    'first_delivery_tracking_number' => $result['result']['barCode'] ?? null,
                    'first_delivery_status' => $this->mapFirstDeliveryStatus($result['result']['state'] ?? null),
                    'first_delivery_response' => $result
                ]);

                // Restore original API key
                config(['services.first_delivery.api_key' => $originalApiKey]);

                return response()->json([
                    'success' => true,
                    'message' => 'Order confirmed and sent to First Delivery',
                    'barcode' => $result['result']['barCode'] ?? null,
                    'print_url' => $result['result']['link'] ?? null,
                    'order' => $order->fresh(),
                    'first_delivery_response' => $result
                ]);
            } else {
                // API call failed
                $order->update([
                    'status' => 'failed',
                    'confirmation_status' => 'pending_confirmation'
                ]);

                // Restore original API key
                config(['services.first_delivery.api_key' => $originalApiKey]);

                return response()->json([
                    'success' => false,
                    'message' => 'Order confirmed but First Delivery API call failed'
                ], 500);
            }

        } catch (\Exception $e) {
            // Restore original API key
            config(['services.first_delivery.api_key' => $originalApiKey]);

            Log::error('Failed to confirm test order', [
                'order_id' => $request->order_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTestOrders()
    {
        $orders = Order::where('notes', 'like', '%Test order%')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    public function getAllOrders(Request $request)
    {
        $query = Order::with(['customer', 'orderItems.product']);
        
        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        
        // Filter by confirmation status
        if ($request->filled('confirmation_status')) {
            $query->byConfirmationStatus($request->confirmation_status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return response()->json([
            'success' => true,
            'orders' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'has_more' => $orders->hasMorePages()
            ]
        ]);
    }

    public function printOrderResponse(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        try {
            $order = Order::findOrFail($request->order_id);
            
            if (!$order->first_delivery_response) {
                return response()->json([
                    'success' => false,
                    'message' => 'No First Delivery response available for this order'
                ], 400);
            }

            // Generate printable HTML
            $html = $this->generatePrintableResponse($order);
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'order_number' => $order->order_number
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate print response: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generatePrintableResponse(Order $order)
    {
        $response = $order->first_delivery_response;
        $orderNumber = $order->order_number;
        $customerName = $order->customer_full_name;
        $timestamp = now()->format('Y-m-d H:i:s');
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <title>First Delivery Response - Order {$orderNumber}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
                .section { margin-bottom: 20px; }
                .section h3 { background: #f5f5f5; padding: 10px; margin: 0 0 10px 0; }
                .field { margin-bottom: 8px; }
                .field-label { font-weight: bold; display: inline-block; width: 150px; }
                .json-response { background: #f8f8f8; padding: 15px; border: 1px solid #ddd; border-radius: 5px; font-family: monospace; white-space: pre-wrap; }
                @media print { body { margin: 0; } .no-print { display: none; } }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>First Delivery API Response</h1>
                <p><strong>Order:</strong> {$orderNumber} | <strong>Customer:</strong> {$customerName} | <strong>Generated:</strong> {$timestamp}</p>
            </div>
            
            <div class='section'>
                <h3>Order Information</h3>
                <div class='field'><span class='field-label'>Order Number:</span> {$orderNumber}</div>
                <div class='field'><span class='field-label'>Customer:</span> {$customerName}</div>
                <div class='field'><span class='field-label'>Email:</span> {$order->customer_email}</div>
                <div class='field'><span class='field-label'>Phone:</span> {$order->customer_phone}</div>
                <div class='field'><span class='field-label'>Total:</span> " . number_format((float)$order->total, 3) . " TND</div>
                <div class='field'><span class='field-label'>Status:</span> " . ucfirst(str_replace('_', ' ', $order->confirmation_status)) . "</div>
            </div>
            
            <div class='section'>
                <h3>First Delivery Response</h3>
                <div class='json-response'>" . json_encode($response, JSON_PRETTY_PRINT) . "</div>
            </div>
            
            <div class='section no-print'>
                <button onclick='window.print()' style='padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 5px; cursor: pointer;'>Print This Page</button>
            </div>
        </body>
        </html>";
    }

    public function getSystemHealth()
    {
        try {
            $health = [
                'database' => $this->checkDatabaseConnection(),
                'first_delivery_settings' => $this->checkFirstDeliverySettings(),
                'api_key_encryption' => $this->checkApiKeyEncryption(),
                'laravel_config' => $this->checkLaravelConfig(),
                'last_api_call' => $this->getLastApiCallStatus(),
                'timestamp' => now()->toISOString()
            ];

            return response()->json([
                'success' => true,
                'health' => $health
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Health check failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testApiKeyEncryption()
    {
        try {
            $settings = \App\Models\FirstDeliverySetting::getSettings();
            $encryptedKey = $settings->first_delivery_key;
            
            // Test decryption
            $decryptedKey = \Illuminate\Support\Facades\Crypt::decryptString($encryptedKey);
            
            // Test re-encryption
            $reEncryptedKey = \Illuminate\Support\Facades\Crypt::encryptString($decryptedKey);
            
            // Verify UUID format
            $isValidUuid = preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $decryptedKey);
            
            return response()->json([
                'success' => true,
                'encrypted_key' => substr($encryptedKey, 0, 20) . '...',
                'decrypted_key' => substr($decryptedKey, 0, 8) . '...' . substr($decryptedKey, -4),
                'is_valid_uuid' => $isValidUuid,
                'encryption_works' => true,
                'last_updated' => $settings->updated_at->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'API key encryption test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPayloadPreview(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        try {
            $order = Order::with('orderItems')->findOrFail($request->order_id);
            $service = app(FirstDeliveryService::class);
            
            // Use reflection to access protected method
            $reflection = new \ReflectionClass($service);
            $method = $reflection->getMethod('buildOrderPayload');
            $method->setAccessible(true);
            $payload = $method->invoke($service, $order);

            return response()->json([
                'success' => true,
                'payload' => $payload,
                'order_info' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer' => $order->customer_first_name . ' ' . $order->customer_last_name,
                    'total' => $order->total
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate payload preview: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkCreateTest(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:2|max:10',
            'api_key' => 'nullable|string'
        ]);

        try {
            $orders = [];
            $results = [];
            $apiKey = $request->api_key;
            
            if (!$apiKey) {
                $settings = \App\Models\FirstDeliverySetting::getSettings();
                $apiKey = $settings->first_delivery_key;
            }

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'No API key provided or configured in settings'
                ], 400);
            }

            // Temporarily update the API key
            $originalApiKey = config('services.first_delivery.api_key');
            config(['services.first_delivery.api_key' => $apiKey]);

            $service = app(FirstDeliveryService::class);

            // Create test orders
            for ($i = 1; $i <= $request->count; $i++) {
                $order = Order::create([
                    'order_number' => 'BULK-TEST-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'customer_first_name' => 'Bulk',
                    'customer_last_name' => 'Test ' . $i,
                    'customer_email' => 'bulk' . $i . '@test.com',
                    'customer_phone' => '1234567' . $i,
                    'shipping_address' => 'Bulk Test Address ' . $i,
                    'shipping_city' => 'Tunis',
                    'shipping_state' => 'Tunis',
                    'shipping_postal_code' => '1000',
                    'shipping_country' => 'Tunisia',
                    'total' => 50.000 + ($i * 10),
                    'subtotal' => 45.000 + ($i * 10),
                    'shipping_cost' => 5.000,
                    'status' => 'pending',
                    'confirmation_status' => 'pending_confirmation',
                    'payment_status' => 'pending',
                    'payment_method' => 'cash_on_delivery',
                    'notes' => 'Bulk test order ' . $i,
                ]);

                $orders[] = $order;

                // Try to sync with First Delivery
                try {
                    $result = $service->createOrder($order);
                    $results[] = [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'success' => $result ? true : false,
                        'result' => $result
                    ];
                } catch (\Exception $e) {
                    $results[] = [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'success' => false,
                        'error' => $e->getMessage()
                    ];
                }
            }

            // Restore original API key
            config(['services.first_delivery.api_key' => $originalApiKey]);

            return response()->json([
                'success' => true,
                'message' => 'Bulk test completed',
                'orders_created' => count($orders),
                'results' => $results
            ]);

        } catch (\Exception $e) {
            // Restore original API key
            config(['services.first_delivery.api_key' => $originalApiKey]);

            return response()->json([
                'success' => false,
                'message' => 'Bulk test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clearTestData(Request $request)
    {
        $request->validate([
            'confirm' => 'required|boolean'
        ]);

        if (!$request->confirm) {
            return response()->json([
                'success' => false,
                'message' => 'Confirmation required to clear test data'
            ], 400);
        }

        try {
            $deletedCount = Order::where('notes', 'like', '%Test order%')
                ->orWhere('notes', 'like', '%Bulk test%')
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Test data cleared successfully',
                'deleted_orders' => $deletedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear test data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportTestData()
    {
        try {
            $orders = Order::where('notes', 'like', '%Test order%')
                ->orWhere('notes', 'like', '%Bulk test%')
                ->with('orderItems')
                ->orderBy('created_at', 'desc')
                ->get();

            $exportData = [
                'exported_at' => now()->toISOString(),
                'total_orders' => $orders->count(),
                'orders' => $orders->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer' => $order->customer_first_name . ' ' . $order->customer_last_name,
                        'email' => $order->customer_email,
                        'phone' => $order->customer_phone,
                        'total' => $order->total,
                        'status' => $order->status,
                        'confirmation_status' => $order->confirmation_status,
                        'barcode' => $order->barcode,
                        'print_url' => $order->print_url,
                        'first_delivery_response' => $order->first_delivery_response,
                        'created_at' => $order->created_at->toISOString(),
                        'order_items' => $order->orderItems->map(function ($item) {
                            return [
                                'product_name' => $item->product_name,
                                'quantity' => $item->quantity,
                                'unit_price' => $item->unit_price,
                                'total_price' => $item->total_price
                            ];
                        })
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'data' => $exportData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export test data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPerformanceMetrics()
    {
        try {
            $metrics = [
                'orders_today' => Order::whereDate('created_at', today())->count(),
                'orders_synced_today' => Order::whereDate('created_at', today())
                    ->whereNotNull('first_delivery_id')
                    ->count(),
                'pending_confirmation' => Order::where('confirmation_status', 'pending_confirmation')->count(),
                'confirmed_today' => Order::whereDate('confirmed_at', today())->count(),
                'with_barcode' => Order::whereNotNull('barcode')->count(),
                'with_print_url' => Order::whereNotNull('print_url')->count(),
                'last_sync' => Order::whereNotNull('first_delivery_id')
                    ->orderBy('updated_at', 'desc')
                    ->first()?->updated_at?->toISOString(),
                'api_success_rate' => $this->calculateApiSuccessRate(),
                'average_response_time' => $this->calculateAverageResponseTime()
            ];

            return response()->json([
                'success' => true,
                'metrics' => $metrics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get performance metrics: ' . $e->getMessage()
            ], 500);
        }
    }

    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'connected', 'message' => 'Database connection successful'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()];
        }
    }

    private function checkFirstDeliverySettings()
    {
        try {
            $settings = \App\Models\FirstDeliverySetting::getSettings();
            if (!$settings) {
                return ['status' => 'error', 'message' => 'No First Delivery settings found'];
            }
            
            $hasApiKey = !empty($settings->first_delivery_key);
            return [
                'status' => $hasApiKey ? 'ok' : 'warning',
                'message' => $hasApiKey ? 'Settings found with API key' : 'Settings found but no API key',
                'settings_id' => $settings->id,
                'has_api_key' => $hasApiKey
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Failed to check settings: ' . $e->getMessage()];
        }
    }

    private function checkApiKeyEncryption()
    {
        try {
            $settings = \App\Models\FirstDeliverySetting::getSettings();
            if (!$settings || !$settings->first_delivery_key) {
                return ['status' => 'warning', 'message' => 'No API key to test encryption'];
            }
            
            $decrypted = \Illuminate\Support\Facades\Crypt::decryptString($settings->first_delivery_key);
            return ['status' => 'ok', 'message' => 'API key encryption/decryption working'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'API key encryption test failed: ' . $e->getMessage()];
        }
    }

    private function checkLaravelConfig()
    {
        try {
            $config = [
                'app_env' => config('app.env'),
                'app_debug' => config('app.debug'),
                'first_delivery_url' => config('services.first_delivery.api_url'),
                'first_delivery_timeout' => config('services.first_delivery.timeout'),
                'encryption_key_set' => !empty(config('app.key'))
            ];
            
            return ['status' => 'ok', 'message' => 'Laravel configuration loaded', 'config' => $config];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Config check failed: ' . $e->getMessage()];
        }
    }

    private function getLastApiCallStatus()
    {
        try {
            $lastOrder = Order::whereNotNull('first_delivery_response')
                ->orderBy('updated_at', 'desc')
                ->first();
            
            if (!$lastOrder) {
                return ['status' => 'none', 'message' => 'No API calls recorded'];
            }
            
            $response = $lastOrder->first_delivery_response;
            $success = isset($response['isError']) ? !$response['isError'] : (isset($response['status']) && $response['status'] < 400);
            
            return [
                'status' => $success ? 'success' : 'error',
                'message' => $success ? 'Last API call successful' : 'Last API call failed',
                'order_number' => $lastOrder->order_number,
                'timestamp' => $lastOrder->updated_at->toISOString()
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Failed to check last API call: ' . $e->getMessage()];
        }
    }

    private function calculateApiSuccessRate()
    {
        try {
            $total = Order::whereNotNull('first_delivery_response')->count();
            if ($total === 0) return 0;
            
            $successful = Order::whereNotNull('first_delivery_response')
                ->whereRaw("JSON_EXTRACT(first_delivery_response, '$.isError') = false")
                ->count();
            
            return round(($successful / $total) * 100, 2);
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function calculateAverageResponseTime()
    {
        // This would need to be implemented with actual timing data
        // For now, return a placeholder
        return 'N/A';
    }

    private function generateOrderNumber(): string
    {
        $year = date('Y');
        $lastOrder = Order::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastOrder ? (int) substr($lastOrder->order_number, -4) + 1 : 1;
        
        return 'TEST-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Map First Delivery status to our status
     */
    private function mapFirstDeliveryStatus($fdStatus)
    {
        $statusMap = [
            0 => 'confirmed',
            1 => 'pickup_requested',
            2 => 'in_delivery',
            3 => 'in_delivery',
            4 => 'delivered',
            100 => 'cancelled',
            101 => 'cancelled',
        ];
        
        return $statusMap[$fdStatus] ?? 'confirmed';
    }
}
