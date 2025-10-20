<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Validate cart before checkout
     * POST /api/v1/checkout/validate
     */
    public function validateCheckout(Request $request): JsonResponse
    {
        try {
            $sessionId = $this->getSessionId($request);
            
            $cartItems = Cart::with(['product', 'productVariant'])
                ->where('session_id', $sessionId)
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty'
                ], 400);
            }

            $errors = [];
            $validItems = [];

            foreach ($cartItems as $item) {
                $product = $item->product;
                $variant = $item->productVariant;

                // Check if product is active
                if (!$product->is_active) {
                    $errors[] = "Product '{$product->name}' is no longer available";
                    continue;
                }

                // Check if product is in stock
                if ($product->stock_status === 'out_of_stock') {
                    $errors[] = "Product '{$product->name}' is out of stock";
                    continue;
                }

                // Check variant availability if applicable
                if ($variant && !$variant->is_active) {
                    $errors[] = "Selected variant for '{$product->name}' is no longer available";
                    continue;
                }

                $validItems[] = [
                    'cart_item_id' => $item->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name' => $product->name,
                    'variant_name' => $variant ? "{$variant->color} - {$variant->size}" : null,
                    'quantity' => $item->quantity,
                    'unit_price' => $variant ? $variant->price : $product->price,
                    'total_price' => $item->quantity * ($variant ? $variant->price : $product->price),
                ];
            }

            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart validation failed',
                    'errors' => $errors,
                    'valid_items' => $validItems
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cart is valid for checkout',
                'data' => [
                    'valid_items' => $validItems,
                    'item_count' => $validItems->sum('quantity'),
                    'subtotal' => $validItems->sum('total_price')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to validate cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate totals with shipping
     * POST /api/v1/checkout/calculate
     */
    public function calculate(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'shipping_city' => 'required|string|max:100',
                'shipping_country' => 'nullable|string|max:100',
            ]);

            $sessionId = $this->getSessionId($request);
            
            $cartItems = Cart::with(['product', 'productVariant'])
                ->where('session_id', $sessionId)
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty'
                ], 400);
            }

            $subtotal = 0;
            $items = [];

            foreach ($cartItems as $item) {
                $product = $item->product;
                $variant = $item->productVariant;
                $unitPrice = $variant ? $variant->price : $product->price;
                $totalPrice = $item->quantity * $unitPrice;
                $subtotal += $totalPrice;

                $items[] = [
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name' => $product->name,
                    'variant_name' => $variant ? "{$variant->color} - {$variant->size}" : null,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ];
            }

            // Calculate shipping cost based on city/country
            $shippingCost = $this->calculateShippingCost($validated['shipping_city'], $validated['shipping_country'] ?? 'Morocco');
            $total = $subtotal + $shippingCost;

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $items,
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'total' => $total,
                    'item_count' => $cartItems->sum('quantity'),
                    'currency' => 'MAD'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate totals',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate shipping cost based on location
     */
    private function calculateShippingCost(string $city, string $country): float
    {
        // Simple shipping calculation logic
        // In a real app, this would be more complex
        
        if ($country !== 'Morocco') {
            return 150.00; // International shipping
        }

        // Morocco cities with different rates
        $majorCities = ['Casablanca', 'Rabat', 'Marrakech', 'Fez', 'Tangier', 'Agadir'];
        
        if (in_array(ucfirst(strtolower($city)), $majorCities)) {
            return 30.00; // Major cities
        }

        return 50.00; // Other cities
    }

    /**
     * Get session ID for cart (authenticated user or session)
     */
    private function getSessionId(Request $request): string
    {
        // If user is authenticated, use their ID as session ID
        if ($request->user()) {
            return 'user_' . $request->user()->id;
        }

        // Otherwise use session ID
        return $request->session()->getId();
    }
}



