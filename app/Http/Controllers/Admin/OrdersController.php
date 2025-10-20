<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function allOrders(Request $request)
    {
        // Set language from request
        $locale = $request->get('lang', 'fr');
        App::setLocale($locale);
        
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
            
        return view('admin.orders.all-orders', compact('orders', 'locale'));
    }
    
    public function newOrders(Request $request)
    {
        $locale = $request->get('lang', 'fr');
        App::setLocale($locale);
        
        $orders = Order::with(['customer', 'orderItems.product'])
            ->where('confirmation_status', 'pending_confirmation')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get aggregated product quantities for new orders
        $productSummary = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.confirmation_status', 'pending_confirmation')
            ->select(
                'order_items.product_name',
                'order_items.variant_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('order_items.product_name', 'order_items.variant_name')
            ->orderBy('total_quantity', 'desc')
            ->get();
            
        return view('admin.orders.new', compact('orders', 'productSummary', 'locale'));
    }
    
    public function confirmedOrders(Request $request)
    {
        $locale = $request->get('lang', 'fr');
        App::setLocale($locale);
        
        $orders = Order::with(['customer', 'orderItems.product'])
            ->where('confirmation_status', 'confirmed')
            ->orderBy('confirmed_at', 'desc')
            ->paginate(20);
        
        // Get aggregated product quantities for confirmed orders
        $productSummary = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.confirmation_status', 'confirmed')
            ->select(
                'order_items.product_name',
                'order_items.variant_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('order_items.product_name', 'order_items.variant_name')
            ->orderBy('total_quantity', 'desc')
            ->get();
            
        return view('admin.orders.confirmed', compact('orders', 'productSummary', 'locale'));
    }
    
    public function readyForPickup(Request $request)
    {
        $locale = $request->get('lang', 'fr');
        App::setLocale($locale);
        
        $orders = Order::with(['customer', 'orderItems.product', 'orderItems.productVariant'])
            ->whereIn('confirmation_status', ['printed', 'pickup_requested'])
            ->orderBy('confirmed_at', 'desc')
            ->paginate(20);
            
        return view('admin.orders.ready-pickup', compact('orders', 'locale'));
    }
    
    public function inDelivery(Request $request)
    {
        $locale = $request->get('lang', 'fr');
        App::setLocale($locale);
        
        $orders = Order::with(['customer', 'orderItems.product'])
            ->whereIn('confirmation_status', ['pickup_requested', 'in_delivery'])
            ->orderBy('pickup_requested_at', 'desc')
            ->paginate(20);
            
        return view('admin.orders.in-delivery', compact('orders', 'locale'));
    }
    
    public function show(Order $order)
    {
        $order->load(['customer', 'orderItems.product', 'orderItems.productVariant']);
        return view('admin.orders.show', compact('order'));
    }
    
    public function bulkConfirm(Request $request)
    {
        // Handle both array and JSON string formats
        $orderIds = $request->order_ids ?? $request->input('order_ids', []);
        if (is_string($orderIds)) {
            $orderIds = json_decode($orderIds, true);
        }
        
        // If order_ids is still empty, try order_ids[] (for individual form submissions)
        if (empty($orderIds)) {
            $orderIds = $request->input('order_ids', []);
        }
        
        $request->merge(['order_ids' => $orderIds]);
        
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);
        
        $orders = Order::whereIn('id', $orderIds)
            ->pendingConfirmation()
            ->get();
        
        $service = app(\App\Services\FirstDeliveryService::class);
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($orders as $order) {
            try {
                // Try to sync with First Delivery service
                $result = $service->createOrder($order);
                
                if ($result && isset($result['result'])) {
                    // API call successful - update order with First Delivery response
                    $order->update([
                        'status' => 'processing',
                        'confirmation_status' => 'confirmed',
                        'confirmed_at' => now(),
                        'barcode' => $result['result']['barCode'] ?? null,
                        'print_url' => $result['result']['link'] ?? null,
                        'first_delivery_id' => $result['result']['barCode'] ?? null,
                        'first_delivery_tracking_number' => $result['result']['barCode'] ?? null,
                        'first_delivery_status' => $this->mapFirstDeliveryStatus($result['result']['state'] ?? null),
                        'first_delivery_response' => $result
                    ]);
                    $successCount++;
                } else {
                    // API call failed - set status to failed
                    $order->update([
                        'status' => 'failed',
                        'confirmation_status' => 'pending_confirmation',
                        'confirmed_at' => now(),
                        'first_delivery_response' => $result
                    ]);
                    $errorCount++;
                }
            } catch (\Exception $e) {
                \Log::error('Failed to confirm order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
                
                // Exception occurred - set status to failed
                $order->update([
                    'status' => 'failed',
                    'confirmation_status' => 'pending_confirmation',
                    'confirmed_at' => now(),
                    'first_delivery_response' => ['error' => $e->getMessage()]
                ]);
                $errorCount++;
            }
        }
        
        $message = "Bulk confirmation completed. {$successCount} orders confirmed successfully.";
        if ($errorCount > 0) {
            $message .= " {$errorCount} orders failed to confirm.";
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public function bulkCancel(Request $request)
    {
        // Handle both array and JSON string formats
        $orderIds = $request->order_ids ?? $request->input('order_ids', []);
        if (is_string($orderIds)) {
            $orderIds = json_decode($orderIds, true);
        }
        
        // If order_ids is still empty, try order_ids[] (for individual form submissions)
        if (empty($orderIds)) {
            $orderIds = $request->input('order_ids', []);
        }
        
        $request->merge(['order_ids' => $orderIds]);
        
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);
        
        Order::whereIn('id', $orderIds)
            ->update([
                'confirmation_status' => 'cancelled',
                'status' => 'cancelled'
            ]);
        
        return redirect()->back()->with('success', 'Selected orders have been cancelled.');
    }
    
    public function bulkPrint(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);
        
        $orders = Order::whereIn('id', $request->order_ids)
            ->confirmed()
            ->get();
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($orders as $order) {
            try {
                // Check if order has print_url and barcode from First Delivery
                if ($order->print_url && $order->barcode) {
                    $order->update([
                        'confirmation_status' => 'printed',
                        'printed_at' => now()
                    ]);
                    $successCount++;
                } else {
                    // If no print_url, still mark as printed but log warning
                    $order->update([
                        'confirmation_status' => 'printed',
                        'printed_at' => now()
                    ]);
                    \Log::warning('Order marked as printed without print_url', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number
                    ]);
                    $successCount++;
                }
            } catch (\Exception $e) {
                \Log::error('Failed to mark order as printed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
                $errorCount++;
            }
        }
        
        $message = "Bulk print completed. {$successCount} orders marked as printed.";
        if ($errorCount > 0) {
            $message .= " {$errorCount} orders failed to update.";
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public function bulkPickup(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);
        
        $orders = Order::whereIn('id', $request->order_ids)
            ->whereIn('confirmation_status', ['printed', 'pickup_requested'])
            ->whereNotNull('barcode')
            ->get();
        
        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'No valid orders found for pickup request. Orders must be printed and have barcodes.');
        }
        
        $barCodes = $orders->pluck('barcode')->toArray();
        $service = app(\App\Services\FirstDeliveryService::class);
        $successCount = 0;
        $errorCount = 0;
        
        try {
            $result = $service->requestPickup($barCodes);
            
            if ($result) {
                // Update all orders to pickup_requested status
                Order::whereIn('id', $orders->pluck('id'))
                    ->update([
                        'confirmation_status' => 'pickup_requested',
                        'pickup_requested_at' => now()
                    ]);
                
                $successCount = $orders->count();
                
                return redirect()->back()->with('success', "Pickup request sent successfully for {$successCount} orders.");
            } else {
                return redirect()->back()->with('error', 'Failed to request pickup from delivery service. Please try again.');
            }
        } catch (\Exception $e) {
            \Log::error('Failed to request pickup', [
                'order_ids' => $request->order_ids,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Failed to request pickup: ' . $e->getMessage());
        }
    }
    
    public function updateOrderNotes(Request $request, Order $order)
    {
        $request->validate([
            'staff_notes' => 'nullable|string|max:1000'
        ]);
        
        $order->update(['staff_notes' => $request->staff_notes]);
        
        return redirect()->back()->with('success', 'Order notes updated successfully.');
    }
    
    public function setReminder(Request $request, Order $order)
    {
        $request->validate([
            'reminder_at' => 'required|date|after:now'
        ]);
        
        $order->update(['reminder_at' => $request->reminder_at]);
        
        return redirect()->back()->with('success', 'Reminder set successfully.');
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);
        
        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Auto-sync to First Delivery when order is confirmed or processing
        if (in_array($request->status, ['processing']) && 
            !$order->isSyncedToFirstDelivery() && 
            \App\Models\FirstDeliverySetting::isEnabled()) {
            
            try {
                $result = $order->syncToFirstDelivery();
                if ($result) {
                    return redirect()->back()->with('success', 'Order status updated and synced to First Delivery successfully.');
                } else {
                    return redirect()->back()->with('warning', 'Order status updated, but failed to sync to First Delivery. Please check logs.');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to auto-sync order to First Delivery', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
                return redirect()->back()->with('warning', 'Order status updated, but failed to sync to First Delivery. Please try manual sync.');
            }
        }
        
        return redirect()->back()
            ->with('success', 'Order status updated successfully');
    }

    public function quickConfirm(Order $order)
    {
        if (!$order->canBeConfirmed()) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be confirmed in its current status.'
            ], 400);
        }

        try {
            // Change status from pending to processing
            $order->update([
                'status' => 'processing',
                'confirmation_status' => 'confirmed',
                'confirmed_at' => now()
            ]);
            
            // Try to send to First Delivery API
            $firstDeliveryService = app(\App\Services\FirstDeliveryService::class);
            $result = $firstDeliveryService->createOrder($order);
            
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
                
                return response()->json([
                    'success' => true,
                    'message' => 'Order confirmed and sent to First Delivery',
                    'barcode' => $result['result']['barCode'] ?? null,
                    'print_url' => $result['result']['link'] ?? null,
                    'order' => $order->fresh()
                ]);
            } else {
                // API call failed
                $order->update([
                    'status' => 'failed',
                    'confirmation_status' => 'pending_confirmation'
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Order confirmed but First Delivery API call failed'
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to confirm order', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            // Exception occurred
            $order->update([
                'status' => 'failed',
                'confirmation_status' => 'pending_confirmation'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function quickCancel(Order $order)
    {
        if (!$order->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled in its current status.'
            ], 400);
        }

        $order->update([
            'status' => 'cancelled',
            'confirmation_status' => 'cancelled'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully.',
            'order' => $order->fresh()
        ]);
    }

    public function quickPickup(Order $order)
    {
        if (!in_array($order->confirmation_status, ['printed', 'pickup_requested'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order must be printed before requesting pickup.'
            ], 400);
        }

        if (!$order->barcode) {
            return response()->json([
                'success' => false,
                'message' => 'Order does not have a barcode for pickup request.'
            ], 400);
        }

        try {
            $service = app(\App\Services\FirstDeliveryService::class);
            $result = $service->requestPickup([$order->barcode]);
            
            if ($result) {
                $order->update([
                    'confirmation_status' => 'pickup_requested',
                    'pickup_requested_at' => now()
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Pickup request sent successfully.',
                    'order' => $order->fresh()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send pickup request to delivery service.'
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to request pickup for order', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to request pickup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAsDelivered(Order $order)
    {
        if (!in_array($order->confirmation_status, ['pickup_requested', 'in_delivery'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order must be in pickup or delivery status to mark as delivered.'
            ], 400);
        }

        $order->update([
            'confirmation_status' => 'delivered',
            'status' => 'delivered',
            'delivered_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as delivered successfully.',
            'order' => $order->fresh()
        ]);
    }

    public function quickPrint(Order $order)
    {
        if ($order->confirmation_status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Order must be confirmed before marking as printed.'
            ], 400);
        }

        try {
            // Check if order has print_url and barcode from First Delivery
            if ($order->print_url && $order->barcode) {
                $order->update([
                    'confirmation_status' => 'printed',
                    'printed_at' => now()
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Order marked as printed successfully.',
                    'order' => $order->fresh()
                ]);
            } else {
                // If no print_url, still mark as printed but log warning
                $order->update([
                    'confirmation_status' => 'printed',
                    'printed_at' => now()
                ]);
                
                \Log::warning('Order marked as printed without print_url', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Order marked as printed (no print URL available).',
                    'order' => $order->fresh()
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to mark order as printed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark order as printed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        return view('admin.orders.create');
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_country' => 'nullable|string|max:100',
            'total' => 'required|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'status' => 'sometimes|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'sometimes|in:pending,paid,failed,refunded',
            'payment_method' => 'sometimes|in:cash_on_delivery,credit_card,bank_transfer',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Split customer name into first and last name
        $nameParts = explode(' ', trim($request->customer_name), 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

        try {
            DB::beginTransaction();

            // Generate order number
            $orderNumber = $this->generateOrderNumber();

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_first_name' => $firstName,
                'customer_last_name' => $lastName,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_country' => $request->shipping_country ?? 'Tunisia',
                'total' => $request->total,
                'subtotal' => $request->total - ($request->shipping_cost ?? 0),
                'shipping_cost' => $request->shipping_cost ?? 0,
                'status' => $request->status ?? 'pending',
                'confirmation_status' => 'pending_confirmation', // This is crucial for orders to appear on /admin/orders/new
                'payment_status' => $request->payment_status ?? 'pending',
                'payment_method' => $request->payment_method ?? 'cash_on_delivery',
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $order->load(['customer', 'orderItems.product', 'orderItems.productVariant']);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'total' => 'required|numeric|min:0',
            'status' => 'sometimes|in:pending,processing,shipped,delivered,cancelled',
            'confirmation_status' => 'sometimes|in:pending_confirmation,confirmed,printed,pickup_requested,in_delivery,delivered,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Split customer name into first and last name
        $nameParts = explode(' ', trim($request->customer_name), 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

        try {
            $order->update([
                'customer_first_name' => $firstName,
                'customer_last_name' => $lastName,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'total' => $request->total,
                'status' => $request->status,
                'confirmation_status' => $request->confirmation_status,
                'notes' => $request->notes,
            ]);

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        try {
            // Only allow deletion of pending orders
            if ($order->status !== 'pending' && $order->confirmation_status !== 'pending_confirmation') {
                return redirect()->back()
                    ->with('error', 'Only pending orders can be deleted.');
            }

            $order->delete();

            return redirect()->route('admin.orders.index')
                ->with('success', 'Order deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    /**
     * Check delivery status manually
     */
    public function checkDeliveryStatus(Order $order)
    {
        if (!$order->barcode) {
            return response()->json([
                'success' => false,
                'message' => 'Order has no barcode'
            ], 400);
        }
        
        try {
            $service = app(\App\Services\FirstDeliveryService::class);
            $result = $service->checkDeliveryStatus($order->barcode);
            
            if ($result && isset($result['result']['state'])) {
                // Update status based on First Delivery response
                $this->updateOrderStatusFromAPI($order, $result['result']['state'], $result);
                
                return response()->json([
                    'success' => true,
                    'status' => $order->status,
                    'confirmation_status' => $order->confirmation_status,
                    'fd_status' => $result['result']['state']
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get status from First Delivery'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status from First Delivery API response
     */
    private function updateOrderStatusFromAPI($order, $fdStatus, $apiResponse)
    {
        // Map First Delivery status to our statuses
        $statusMap = [
            'pending' => ['status' => 'processing', 'confirmation_status' => 'confirmed'],
            'picked_up' => ['status' => 'processing', 'confirmation_status' => 'pickup_requested'],
            'in_transit' => ['status' => 'shipped', 'confirmation_status' => 'in_delivery'],
            'delivered' => ['status' => 'delivered', 'confirmation_status' => 'delivered'],
            'cancelled' => ['status' => 'cancelled', 'confirmation_status' => 'cancelled'],
            'failed' => ['status' => 'cancelled', 'confirmation_status' => 'cancelled'],
        ];
        
        $newStatus = $statusMap[$fdStatus] ?? null;
        
        if ($newStatus) {
            $order->update([
                'status' => $newStatus['status'],
                'confirmation_status' => $newStatus['confirmation_status'],
                'first_delivery_status' => $fdStatus,
                'first_delivery_response' => $apiResponse,
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Map First Delivery status to our status
     */
    private function mapFirstDeliveryStatus($fdStatus)
    {
        $statusMap = [
            'pending' => 'confirmed',
            'picked_up' => 'pickup_requested',
            'in_transit' => 'in_delivery',
            'delivered' => 'delivered',
            'cancelled' => 'cancelled',
            'failed' => 'cancelled',
        ];
        
        return $statusMap[$fdStatus] ?? 'confirmed';
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
