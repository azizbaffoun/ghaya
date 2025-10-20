<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\FirstDeliveryService;

class SyncFirstDeliveryStatus extends Command
{
    protected $signature = 'orders:sync-delivery-status';
    protected $description = 'Sync order status with First Delivery API';

    public function handle()
    {
        // Get orders that are in processing or shipped status
        $orders = Order::whereIn('status', ['processing', 'shipped'])
                      ->whereNotNull('barcode')
                      ->where('status', '!=', 'delivered')
                      ->get();
        
        $service = new FirstDeliveryService();
        $updated = 0;
        
        foreach ($orders as $order) {
            try {
                $result = $service->checkDeliveryStatus($order->barcode);
                
                if ($result && isset($result['result']['state'])) {
                    $fdStatus = $result['result']['state'];
                    $this->updateOrderStatus($order, $fdStatus, $result);
                    $updated++;
                }
            } catch (\Exception $e) {
                $this->error("Failed to sync order {$order->order_number}: " . $e->getMessage());
            }
        }
        
        $this->info("Synced {$updated} orders successfully");
        return 0;
    }
    
    private function updateOrderStatus($order, $fdStatus, $apiResponse)
    {
        // Map First Delivery status to our statuses
        $statusMap = [
            // Waiting statuses
            0 => ['status' => 'processing', 'confirmation_status' => 'confirmed'],
            1 => ['status' => 'processing', 'confirmation_status' => 'pickup_requested'],
            
            // In transit
            2 => ['status' => 'shipped', 'confirmation_status' => 'in_delivery'],
            3 => ['status' => 'shipped', 'confirmation_status' => 'in_delivery'],
            
            // Delivered
            4 => ['status' => 'delivered', 'confirmation_status' => 'delivered'],
            
            // Failed/Cancelled
            100 => ['status' => 'cancelled', 'confirmation_status' => 'cancelled'],
            101 => ['status' => 'failed', 'confirmation_status' => 'cancelled'],
        ];
        
        $newStatus = $statusMap[$fdStatus] ?? null;
        
        if ($newStatus) {
            $order->update([
                'status' => $newStatus['status'],
                'confirmation_status' => $newStatus['confirmation_status'],
                'first_delivery_status' => $fdStatus,
                'first_delivery_response' => $apiResponse
            ]);
        }
    }
}

