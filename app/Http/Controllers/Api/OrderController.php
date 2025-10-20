<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/orders
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['items']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by customer email
        if ($request->has('customer_email')) {
            $query->where('customer_email', $request->customer_email);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $orders = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => OrderResource::collection($orders->items()),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/orders
     */
    public function store(Request $request): JsonResponse
    {
        // Log the incoming request for debugging
        \Log::info('Order creation request received', [
            'request_data' => $request->all()
        ]);

        try {
            // Validate the request manually to catch validation errors
            $validated = $request->validate([
                'customer_email' => 'required|email|max:255',
                'customer_first_name' => 'required|string|max:255',
                'customer_last_name' => 'nullable|string|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'shipping_address' => 'required|string|max:500',
                'shipping_city' => 'nullable|string|max:100',
                'shipping_state' => 'nullable|string|max:100',
                'shipping_postal_code' => 'nullable|string|max:20',
                'shipping_country' => 'nullable|string|max:100',
                'subtotal' => 'required|numeric|min:0',
                'shipping_cost' => 'nullable|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'payment_method' => 'nullable|in:cash_on_delivery,bank_transfer',
                'notes' => 'nullable|string|max:1000',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
                'items.*.product_name' => 'required|string|max:255',
                'items.*.variant_name' => 'nullable|string|max:255',
                'items.*.quantity' => 'required|integer|min:1|max:99',
                'items.*.unit_price' => 'required|numeric|min:0',
                'items.*.total_price' => 'required|numeric|min:0',
            ]);

            \Log::info('Order validation passed', [
                'validated_data' => $validated
            ]);

            DB::beginTransaction();

            // Generate order number
            $orderNumber = $this->generateOrderNumber();

            // Get customer info (from authenticated user or request)
            $customer = $request->user();
            $customerId = $customer ? $customer->id : null;

            // Get delivery cost from First Delivery settings
            $deliverySettings = \App\Models\FirstDeliverySetting::first();
            $deliveryCost = $deliverySettings ? $deliverySettings->delivery_cost : 0;

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $customerId,
                'customer_email' => $validated['customer_email'],
                'customer_first_name' => $validated['customer_first_name'],
                'customer_last_name' => $validated['customer_last_name'] ?? '',
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'] ?? 'Tunis',
                'shipping_state' => $validated['shipping_state'] ?? null,
                'shipping_postal_code' => $validated['shipping_postal_code'] ?? null,
                'shipping_country' => $validated['shipping_country'] ?? 'Tunisia',
                'subtotal' => $validated['subtotal'],
                'shipping_cost' => $deliveryCost,
                'total' => $validated['subtotal'] + $deliveryCost,
                'status' => 'pending',
                'confirmation_status' => 'pending_confirmation',
                'payment_status' => 'pending',
                'payment_method' => $validated['payment_method'] ?? 'cash_on_delivery',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create order items
            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'variant_name' => $item['variant_name'] ?? null,
                    'product_sku' => 'SKU-' . $item['product_id'], // Generate SKU
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => new OrderResource($order->load('orderItems'))
            ], 201);

        } catch (ValidationException $e) {
            \Log::error('Order validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search orders for live search functionality
     * GET /api/orders/search
     */
    public function search(Request $request): JsonResponse
    {
        $query = Order::with(['customer', 'orderItems.product', 'orderItems.productVariant']);
        
        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('order_number', 'like', "%{$searchTerm}%")
                  ->orWhere('customer_first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('customer_last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('customer_email', 'like', "%{$searchTerm}%")
                  ->orWhere('customer_phone', 'like', "%{$searchTerm}%")
                  ->orWhere('shipping_address', 'like', "%{$searchTerm}%");
            });
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply confirmation status filter
        if ($request->filled('confirmation_status')) {
            $query->where('confirmation_status', $request->confirmation_status);
        }
        
        // Apply date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Get recent orders with customer details
        $orders = $query->orderBy('created_at', 'desc')->limit(20)->get();
        
        // Format the response for the dashboard
        $formattedOrders = $orders->map(function($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_first_name' => $order->customer_first_name,
                'customer_last_name' => $order->customer_last_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
                'shipping_address' => $order->shipping_address,
                'shipping_city' => $order->shipping_city,
                'shipping_state' => $order->shipping_state,
                'shipping_postal_code' => $order->shipping_postal_code,
                'shipping_country' => $order->shipping_country,
                'subtotal' => $order->subtotal,
                'shipping_cost' => $order->shipping_cost,
                'total' => $order->total,
                'status' => $order->status,
                'confirmation_status' => $order->confirmation_status,
                'created_at' => $order->created_at->format('d/m/Y H:i'),
                'order_items' => $order->orderItems->map(function($item) {
                    return [
                        'id' => $item->id,
                        'product_name' => $item->product_name,
                        'variant_name' => $item->variant_name,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->total_price
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedOrders,
            'count' => $orders->count()
        ]);
    }

    /**
     * Display the specified resource.
     * GET /api/orders/{id}
     */
    public function show(string $id): JsonResponse
    {
        $order = Order::with(['items'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new OrderResource($order)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/orders/{id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'sometimes|string|in:pending,processing,shipped,delivered,cancelled',
            'customer_name' => 'sometimes|string|max:255',
            'customer_email' => 'sometimes|email|max:255',
            'customer_phone' => 'sometimes|string|max:20',
            'shipping_address' => 'sometimes|string',
        ]);

        $order->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => new OrderResource($order->load('items'))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/orders/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $order = Order::findOrFail($id);
        
        // Only allow deletion of pending orders
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending orders can be deleted'
            ], 400);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ]);
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber(): string
    {
        $day = date('d');
        $month = date('m');
        $year = date('Y');
        
        // Get the count of orders created today
        $todayOrdersCount = Order::whereDate('created_at', today())->count();
        
        // The next order number for today
        $sequence = $todayOrdersCount + 1;
        
        return $sequence . '-' . $day . '-' . $month . '-' . $year;
    }
}
