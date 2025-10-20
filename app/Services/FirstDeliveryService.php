<?php

namespace App\Services;

use App\Models\Order;
use App\Models\FirstDeliverySetting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class FirstDeliveryService
{
    protected $client;
    protected $settings;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.first_delivery.api_url', 'https://www.firstdeliverygroup.com/api/v2'),
            'timeout' => config('services.first_delivery.timeout', 30),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);
        
        $this->settings = FirstDeliverySetting::getSettings();
    }

    /**
     * Create a single order in First Delivery.
     */
    public function createOrder(Order $order)
    {
        try {
            $payload = $this->buildOrderPayload($order);
            
            // Use the same approach that works with curl
            $tempFile = tempnam(sys_get_temp_dir(), 'first_delivery_');
            file_put_contents($tempFile, json_encode($payload));
            
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $this->client->getConfig('base_uri') . '/create',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => file_get_contents($tempFile),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->settings->first_delivery_key,
                ],
                CURLOPT_TIMEOUT => 30,
            ]);
            
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);
            
            // Clean up temp file
            unlink($tempFile);
            
            if ($error) {
                throw new \Exception('CURL Error: ' . $error);
            }
            
            $responseData = json_decode($response, true);
            
            if ($httpCode !== 200 && $httpCode !== 201) {
                throw new \Exception('HTTP Error ' . $httpCode . ': ' . $response);
            }
            
            // Check if the response indicates success
            if (isset($responseData['status']) && $responseData['status'] == 201) {
                // Update order with First Delivery response
                $order->update([
                    'barcode' => $responseData['result']['barCode'] ?? null,
                    'print_url' => $responseData['result']['link'] ?? null,
                    'first_delivery_response' => $responseData,
                ]);

                Log::info('Order synced to First Delivery successfully', [
                    'order_id' => $order->id,
                    'barcode' => $responseData['result']['barCode'] ?? null
                ]);

                return $responseData;
            } else {
                Log::error('First Delivery API returned error', [
                    'order_id' => $order->id,
                    'response' => $responseData
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Failed to sync order to First Delivery', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Create multiple orders in bulk.
     */
    public function bulkCreateOrders($orders)
    {
        try {
            $payload = [];
            foreach ($orders as $order) {
                $payload[] = $this->buildOrderPayload($order);
            }

            $response = $this->client->post('/bulk-create', [
                'json' => $payload,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->settings->first_delivery_key,
                ]
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            Log::info('Bulk orders synced to First Delivery successfully', [
                'order_count' => count($orders),
                'response' => $responseData
            ]);

            return $responseData;

        } catch (RequestException $e) {
            Log::error('Failed to sync bulk orders to First Delivery', [
                'order_count' => count($orders),
                'error' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);

            return false;
        }
    }

    /**
     * Check order status by barcode.
     */
    public function checkOrderStatusByBarcode($barCode)
    {
        try {
            $response = $this->client->post('/etat', [
                'json' => ['barCode' => $barCode],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->settings->first_delivery_key,
                ]
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            Log::info('Order status checked in First Delivery', [
                'barCode' => $barCode,
                'response' => $responseData
            ]);

            return $responseData;

        } catch (RequestException $e) {
            Log::error('Failed to check order status in First Delivery', [
                'barCode' => $barCode,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Filter orders with criteria using POST request.
     */
    public function filterOrdersByPost($params = [])
    {
        try {
            $response = $this->client->post('/filter', [
                'json' => $params,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->settings->first_delivery_key,
                ]
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            Log::info('Orders filtered in First Delivery', [
                'params' => $params,
                'response' => $responseData
            ]);

            return $responseData;

        } catch (RequestException $e) {
            Log::error('Failed to filter orders in First Delivery', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Cancel orders by barcodes.
     */
    public function cancelDelivery($barCodes)
    {
        try {
            // Ensure barCodes is an array
            if (!is_array($barCodes)) {
                $barCodes = [$barCodes];
            }

            $response = $this->client->post('/cancel-orders', [
                'json' => ['barCodes' => $barCodes],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->settings->first_delivery_key,
                ]
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            Log::info('Orders cancelled in First Delivery', [
                'barCodes' => $barCodes,
                'response' => $responseData
            ]);

            return $responseData;

        } catch (RequestException $e) {
            Log::error('Failed to cancel orders in First Delivery', [
                'barCodes' => $barCodes,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Request pickup from First Delivery.
     */
    public function requestPickup($barCodes)
    {
        try {
            // Ensure barCodes is an array
            if (!is_array($barCodes)) {
                $barCodes = [$barCodes];
            }

            $response = $this->client->post('/pickup', [
                'json' => ['barCodes' => $barCodes],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->settings->first_delivery_key,
                ]
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            Log::info('Pickup requested from First Delivery', [
                'barCodes' => $barCodes,
                'response' => $responseData
            ]);

            return $responseData;

        } catch (RequestException $e) {
            Log::error('Failed to request pickup from First Delivery', [
                'barCodes' => $barCodes,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Request pickup print by pickup ID.
     */
    public function requestPickupPrint($pickupId)
    {
        try {
            $response = $this->client->post("/request-print/{$pickupId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->settings->first_delivery_key,
                ]
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            Log::info('Pickup print requested from First Delivery', [
                'pickupId' => $pickupId,
                'response' => $responseData
            ]);

            return $responseData;

        } catch (RequestException $e) {
            Log::error('Failed to request pickup print from First Delivery', [
                'pickupId' => $pickupId,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Check delivery status by barcode (alias for checkOrderStatusByBarcode).
     */
    public function checkDeliveryStatus($barcode)
    {
        return $this->checkOrderStatusByBarcode($barcode);
    }

    /**
     * Build order payload for First Delivery API.
     */
    protected function buildOrderPayload(Order $order)
    {
        // Handle orders with or without order items
        $productDesignation = 'Order #' . $order->order_number;
        $nombreArticle = 1; // Default to 1 if no order items
        $article = 'Product'; // Default product name
        
        if ($order->orderItems && $order->orderItems->count() > 0) {
            $productDesignation = $order->orderItems->map(function ($item) {
                return $item->product_name . ' (x' . $item->quantity . ')';
            })->implode(', ');
            $nombreArticle = $order->orderItems->sum('quantity');
            $article = $order->orderItems->pluck('product_name')->join(', ');
        }
        
        return [
            'Client' => [
                'nom' => trim($order->customer_first_name . ' ' . $order->customer_last_name),
                'gouvernerat' => $order->shipping_state ?? 'Tunis',
                'ville' => $order->shipping_city ?? 'Tunis',
                'adresse' => $order->shipping_address,
                'telephone' => $this->formatTunisianPhone($order->customer_phone),
                'telephone2' => ''
            ],
            'Produit' => [
                'prix' => (float) $order->total,
                'designation' => $productDesignation,
                'nombreArticle' => $nombreArticle,
                'commentaire' => $order->notes ?? '',
                'article' => $article,
                'nombreEchange' => 0
            ]
        ];
    }

    /**
     * Map First Delivery status codes to English.
     */
    protected function mapFirstDeliveryStatus($statusCode)
    {
        $statusMap = [
            0 => 'pending',           // En attente
            1 => 'in_progress',       // En cours
            2 => 'delivered',         // Livré
            3 => 'exchange',          // Échange
            5 => 'return_to_sender',  // Retour expéditeur
            6 => 'deleted',           // Supprimé
            8 => 'at_store',          // Au magasin
            20 => 'to_verify',        // À vérifier
            30 => 'return_received',  // Retour reçu
            31 => 'final_return',     // Retour définitif
            // 100-104: pickup phases
            // 201-204: return transport states
        ];
        
        return $statusMap[$statusCode] ?? 'unknown';
    }

    /**
     * Format phone number to Tunisian format (+216 XX XXX XXX).
     */
    protected function formatTunisianPhone($phone)
    {
        if (empty($phone)) {
            return $phone;
        }

        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Handle different input formats
        if (strlen($phone) == 8 && strpos($phone, '2') === 0) {
            // Format: 2X XXX XXX -> +216 2X XXX XXX
            return '+216 ' . substr($phone, 0, 2) . ' ' . substr($phone, 2, 3) . ' ' . substr($phone, 5, 3);
        } elseif (strlen($phone) == 9 && strpos($phone, '92') === 0) {
            // Format: 92X XXX XXX -> +216 92X XXX XXX
            return '+216 ' . substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6, 3);
        } elseif (strlen($phone) == 9 && strpos($phone, '9') === 0) {
            // Format: 9X XXX XXX -> +216 9X XXX XXX
            return '+216 ' . substr($phone, 0, 2) . ' ' . substr($phone, 2, 3) . ' ' . substr($phone, 5, 3);
        } elseif (strlen($phone) == 10 && strpos($phone, '216') === 0) {
            // Format: 216XXXXXXXX -> +216 XX XXX XXX
            $phone = substr($phone, 3);
            return '+216 ' . substr($phone, 0, 2) . ' ' . substr($phone, 2, 3) . ' ' . substr($phone, 5, 3);
        } elseif (strpos($phone, '+216') === 0) {
            // Already formatted with +216
            return $phone;
        }
        
        // Return as-is if no pattern matches
        return $phone;
    }
}