<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Get cart items
     * GET /api/v1/cart
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $sessionId = $this->getSessionId($request);
            
            $cartItems = Cart::with(['product.images', 'productVariant'])
                ->where('session_id', $sessionId)
                ->get();

            $total = $cartItems->sum(function ($item) {
                return $item->quantity * ($item->productVariant?->price ?? $item->product->price);
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => CartResource::collection($cartItems),
                    'total' => $total,
                    'item_count' => $cartItems->sum('quantity'),
                    'unique_items' => $cartItems->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get cart items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add item to cart
     * POST /api/v1/cart/add
     */
    public function add(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'product_variant_id' => 'nullable|exists:product_variants,id',
                'quantity' => 'required|integer|min:1|max:99',
            ]);

            $sessionId = $this->getSessionId($request);
            $product = Product::findOrFail($validated['product_id']);

            // Check if product is active
            if (!$product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is not available'
                ], 400);
            }

            // Check if variant exists and belongs to product
            if ($validated['product_variant_id']) {
                $variant = ProductVariant::where('id', $validated['product_variant_id'])
                    ->where('product_id', $product->id)
                    ->first();

                if (!$variant) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product variant not found'
                    ], 400);
                }
            }

            // Check if item already exists in cart
            $existingItem = Cart::where('session_id', $sessionId)
                ->where('product_id', $validated['product_id'])
                ->where('product_variant_id', $validated['product_variant_id'])
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $validated['quantity']
                ]);
                $cartItem = $existingItem;
            } else {
                $cartItem = Cart::create([
                    'session_id' => $sessionId,
                    'product_id' => $validated['product_id'],
                    'product_variant_id' => $validated['product_variant_id'],
                    'quantity' => $validated['quantity'],
                ]);
            }

            $cartItem->load(['product.images', 'productVariant']);

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart',
                'data' => new CartResource($cartItem)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     * PUT /api/v1/cart/update/{id}
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:99',
            ]);

            $sessionId = $this->getSessionId($request);
            
            $cartItem = Cart::where('session_id', $sessionId)
                ->where('id', $id)
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found'
                ], 404);
            }

            $cartItem->update(['quantity' => $validated['quantity']]);
            $cartItem->load(['product.images', 'productVariant']);

            return response()->json([
                'success' => true,
                'message' => 'Cart item updated',
                'data' => new CartResource($cartItem)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     * DELETE /api/v1/cart/remove/{id}
     */
    public function remove(Request $request, $id): JsonResponse
    {
        try {
            $sessionId = $this->getSessionId($request);
            
            $cartItem = Cart::where('session_id', $sessionId)
                ->where('id', $id)
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found'
                ], 404);
            }

            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear entire cart
     * DELETE /api/v1/cart/clear
     */
    public function clear(Request $request): JsonResponse
    {
        try {
            $sessionId = $this->getSessionId($request);
            
            Cart::where('session_id', $sessionId)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cart items count
     * GET /api/v1/cart/count
     */
    public function count(Request $request): JsonResponse
    {
        try {
            $sessionId = $this->getSessionId($request);
            
            $itemCount = Cart::where('session_id', $sessionId)->sum('quantity');
            $uniqueItems = Cart::where('session_id', $sessionId)->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'item_count' => $itemCount,
                    'unique_items' => $uniqueItems
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get cart count',
                'error' => $e->getMessage()
            ], 500);
        }
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



