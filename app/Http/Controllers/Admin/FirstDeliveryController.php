<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FirstDeliverySetting;
use App\Models\Order;
use App\Services\FirstDeliveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FirstDeliveryController extends Controller
{
    protected $firstDeliveryService;

    public function __construct(FirstDeliveryService $firstDeliveryService)
    {
        $this->firstDeliveryService = $firstDeliveryService;
    }

    /**
     * Show the First Delivery settings page.
     */
    public function settings()
    {
        $settings = FirstDeliverySetting::getSettings();
        
        return view('admin.delivery.settings', compact('settings'));
    }

    /**
     * Save First Delivery settings.
     */
    public function saveSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_delivery_key' => 'required|string',
            'delivery_cost' => 'required|numeric|min:0',
            'return_cost' => 'required|numeric|min:0',
            'store_name' => 'required|string|max:255',
            'store_phone' => ['required', 'string', 'max:20', 'regex:/^(\+216|216)?[0-9]{8,9}$/'],
            'store_address' => 'required|string',
            'store_city' => 'required|string|max:100',
            'vat_number' => 'nullable|string|max:50',
            'allow_open_package' => 'boolean',
            'is_enabled' => 'boolean',
        ], [
            'store_phone.regex' => 'Please enter a valid Tunisian phone number (e.g., +216 XX XXX XXX or 2X XXX XXX)',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $settings = FirstDeliverySetting::first() ?? new FirstDeliverySetting();
        $settings->fill($request->all());
        $settings->save();

        return redirect()->back()
            ->with('success', 'First Delivery settings saved successfully!');
    }

    /**
     * Sync a specific order to First Delivery.
     */
    public function syncOrder(Order $order)
    {
        if ($order->isSyncedToFirstDelivery()) {
            return redirect()->back()
                ->with('warning', 'Order is already synced to First Delivery.');
        }

        $result = $this->firstDeliveryService->createOrder($order);

        if ($result) {
            return redirect()->back()
                ->with('success', 'Order synced to First Delivery successfully!');
        }

        return redirect()->back()
            ->with('error', 'Failed to sync order to First Delivery. Please check the logs.');
    }

    /**
     * Bulk sync multiple orders.
     */
    public function bulkSync(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        
        if (empty($orderIds)) {
            return redirect()->back()
                ->with('error', 'No orders selected for sync.');
        }

        $orders = Order::whereIn('id', $orderIds)->get();
        $results = $this->firstDeliveryService->bulkCreateOrders($orders);

        $successCount = collect($results)->where('result', '!=', false)->count();
        $totalCount = count($results);

        return redirect()->back()
            ->with('success', "Successfully synced {$successCount} out of {$totalCount} orders to First Delivery.");
    }

    /**
     * Check order status in First Delivery.
     */
    public function checkStatus(Order $order)
    {
        if (!$order->isSyncedToFirstDelivery()) {
            return redirect()->back()
                ->with('error', 'Order is not synced to First Delivery.');
        }

        $result = $this->firstDeliveryService->checkOrderStatusByTracking($order->first_delivery_tracking_number);

        if ($result) {
            // Update order status
            $order->update([
                'first_delivery_status' => $result['status'] ?? $order->first_delivery_status,
                'first_delivery_response' => array_merge($order->first_delivery_response ?? [], $result)
            ]);

            return redirect()->back()
                ->with('success', 'Order status updated successfully!');
        }

        return redirect()->back()
            ->with('error', 'Failed to check order status. Please try again.');
    }

    /**
     * Cancel delivery in First Delivery.
     */
    public function cancelDelivery(Order $order)
    {
        if (!$order->isSyncedToFirstDelivery()) {
            return redirect()->back()
                ->with('error', 'Order is not synced to First Delivery.');
        }

        $result = $this->firstDeliveryService->cancelOrder($order->first_delivery_tracking_number);

        if ($result) {
            $order->update([
                'first_delivery_status' => 'cancelled',
                'first_delivery_response' => array_merge($order->first_delivery_response ?? [], $result)
            ]);

            return redirect()->back()
                ->with('success', 'Delivery cancelled successfully!');
        }

        return redirect()->back()
            ->with('error', 'Failed to cancel delivery. Please try again.');
    }

    /**
     * Request pickup from First Delivery.
     */
    public function requestPickup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pickup_date' => 'required|date|after:today',
            'pickup_time' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'notes' => $request->notes,
            'store' => [
                'name' => FirstDeliverySetting::getSettings()->store_name,
                'phone' => FirstDeliverySetting::getSettings()->store_phone,
                'address' => FirstDeliverySetting::getSettings()->store_address,
                'city' => FirstDeliverySetting::getSettings()->store_city,
            ]
        ];

        $result = $this->firstDeliveryService->requestPickup($data);

        if ($result) {
            return redirect()->back()
                ->with('success', 'Pickup requested successfully!');
        }

        return redirect()->back()
            ->with('error', 'Failed to request pickup. Please try again.');
    }
}
