<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    /**
     * Get product variants
     * GET /api/v1/products/{id}/variants
     */
    public function index(string $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $variants = $product->variants()->get();

            return response()->json([
                'success' => true,
                'data' => $variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'product_id' => $variant->product_id,
                        'color' => $variant->color,
                        'size' => $variant->size,
                        'sku' => $variant->sku,
                        'stock_quantity' => $variant->stock_quantity,
                        'price' => $variant->price,
                        'is_active' => $variant->is_active,
                        'created_at' => $variant->created_at?->format('Y-m-d H:i:s'),
                        'updated_at' => $variant->updated_at?->format('Y-m-d H:i:s'),
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get product variants',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create variant
     * POST /api/v1/admin/products/{id}/variants
     */
    public function store(Request $request, string $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'color' => 'required|string|max:50',
                'size' => 'required|string|max:20',
                'sku' => 'required|string|unique:product_variants,sku',
                'stock_quantity' => 'required|integer|min:0',
                'price' => 'required|numeric|min:0',
                'is_active' => 'boolean',
            ]);

            $variant = $product->variants()->create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Product variant created successfully',
                'data' => [
                    'id' => $variant->id,
                    'product_id' => $variant->product_id,
                    'color' => $variant->color,
                    'size' => $variant->size,
                    'sku' => $variant->sku,
                    'stock_quantity' => $variant->stock_quantity,
                    'price' => $variant->price,
                    'is_active' => $variant->is_active,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product variant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update variant
     * PUT /api/v1/admin/products/{id}/variants/{variantId}
     */
    public function update(Request $request, string $id, string $variantId): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $variant = $product->variants()->findOrFail($variantId);

            $validated = $request->validate([
                'color' => 'sometimes|string|max:50',
                'size' => 'sometimes|string|max:20',
                'sku' => 'sometimes|string|unique:product_variants,sku,' . $variant->id,
                'stock_quantity' => 'sometimes|integer|min:0',
                'price' => 'sometimes|numeric|min:0',
                'is_active' => 'boolean',
            ]);

            $variant->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Product variant updated successfully',
                'data' => [
                    'id' => $variant->id,
                    'product_id' => $variant->product_id,
                    'color' => $variant->color,
                    'size' => $variant->size,
                    'sku' => $variant->sku,
                    'stock_quantity' => $variant->stock_quantity,
                    'price' => $variant->price,
                    'is_active' => $variant->is_active,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product variant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete variant
     * DELETE /api/v1/admin/products/{id}/variants/{variantId}
     */
    public function destroy(string $id, string $variantId): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $variant = $product->variants()->findOrFail($variantId);

            $variant->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product variant deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product variant',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}



