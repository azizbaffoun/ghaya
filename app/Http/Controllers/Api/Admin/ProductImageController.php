<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * Upload single product image
     * POST /api/v1/admin/products/{id}/images
     */
    public function store(Request $request, string $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
                'alt_text' => 'nullable|string|max:255',
                'color' => 'nullable|string|max:50',
                'is_primary' => 'boolean',
            ]);

            // Upload image
            $path = $request->file('image')->store('products', 'public');

            // If this is set as primary, unset other primary images
            if ($validated['is_primary'] ?? false) {
                ProductImage::where('product_id', $product->id)
                    ->update(['is_primary' => false]);
            }

            // Create image record
            $image = ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'alt_text' => $validated['alt_text'] ?? $product->name . ' image',
                'color' => $validated['color'],
                'is_primary' => $validated['is_primary'] ?? false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'id' => $image->id,
                    'image_path' => $path,
                    'url' => asset('storage/' . $path),
                    'alt_text' => $image->alt_text,
                    'color' => $image->color,
                    'is_primary' => $image->is_primary,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk upload images
     * POST /api/v1/admin/products/{id}/images/bulk
     */
    public function bulkUpload(Request $request, string $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'images' => 'required|array|min:1|max:10',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
                'colors' => 'nullable|array',
                'colors.*' => 'string|max:50',
            ]);

            $uploadedImages = [];

            foreach ($validated['images'] as $index => $image) {
                $path = $image->store('products', 'public');
                $color = $validated['colors'][$index] ?? null;

                $imageRecord = ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'alt_text' => $product->name . ' image ' . ($index + 1),
                    'color' => $color,
                    'is_primary' => $index === 0, // First image is primary
                ]);

                $uploadedImages[] = [
                    'id' => $imageRecord->id,
                    'image_path' => $path,
                    'url' => asset('storage/' . $path),
                    'alt_text' => $imageRecord->alt_text,
                    'color' => $imageRecord->color,
                    'is_primary' => $imageRecord->is_primary,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Images uploaded successfully',
                'data' => $uploadedImages
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload images',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete image
     * DELETE /api/v1/admin/products/{id}/images/{imageId}
     */
    public function destroy(string $id, string $imageId): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $image = ProductImage::where('product_id', $product->id)
                ->where('id', $imageId)
                ->firstOrFail();

            // Delete file from storage
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set image as primary
     * PUT /api/v1/admin/products/{id}/images/{imageId}/primary
     */
    public function setPrimary(string $id, string $imageId): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $image = ProductImage::where('product_id', $product->id)
                ->where('id', $imageId)
                ->firstOrFail();

            // Unset other primary images
            ProductImage::where('product_id', $product->id)
                ->update(['is_primary' => false]);

            // Set this as primary
            $image->update(['is_primary' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Primary image updated successfully',
                'data' => [
                    'id' => $image->id,
                    'is_primary' => true,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set primary image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}



