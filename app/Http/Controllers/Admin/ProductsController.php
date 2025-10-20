<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        // Set language from request
        $locale = $request->get('lang', 'fr');
        App::setLocale($locale);
        
        $query = Product::with(['category', 'images', 'primaryImage', 'variants'])
            ->withTranslation($locale);
        
        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        if ($request->filled('stock_status')) {
            $query->byStockStatus($request->stock_status);
        }
        
        $products = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get categories for filter dropdown
        $categories = Category::active()->ordered()->withTranslation($locale)->get();
            
        return view('admin.products.index', compact('products', 'categories', 'locale'));
    }
    
    public function create()
    {
        $categories = Category::active()->ordered()->withTranslation(app()->getLocale())->get();
        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
    
    public function store(Request $request)
    {
        try {
            // Debug logging
            \Log::info('Product Store Request:', [
                'has_images' => $request->hasFile('images'),
                'images_count' => $request->hasFile('images') ? count($request->file('images')) : 0,
                'all_files' => $request->allFiles(),
                'request_data' => $request->except(['images'])
            ]);

            $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|unique:products,sku',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string|max:1000',
                'price' => 'required|numeric|min:0',
                'compare_price' => 'nullable|numeric|min:0',
                'stock_status' => 'required|in:in_stock,out_of_stock,pre_order',
                'is_active' => 'boolean',
                'colors' => 'nullable|array',
                'size_from' => 'required|integer|min:1|max:100',
                'size_to' => 'required|integer|min:1|max:100|gte:size_from',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'color_images' => 'nullable|array',
                'color_images.*' => 'nullable|array',
                'color_images.*.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
            ]);

            // Create product
            $product = Product::create([
                'name' => $request->name,
                'slug' => \Str::slug($request->name),
                'sku' => $request->sku,
                'category_id' => $request->category_id,
                'description' => $request->description ?: 'No description provided',
                'price' => $request->price,
                'compare_price' => $request->compare_price,
                'stock_status' => $request->stock_status,
                'is_active' => $request->boolean('is_active'),
                'sizes' => $this->generateSizes($request->size_from, $request->size_to),
                'colors' => $request->colors ?? []
            ]);

            // Handle general image uploads
            if ($request->hasFile('images')) {
                $this->uploadProductImages($product, $request->file('images'), null);
            }

            // Handle color-specific image uploads
            if ($request->has('color_images')) {
                foreach ($request->file('color_images') as $color => $colorImages) {
                    if (is_array($colorImages)) {
                        $this->uploadProductImages($product, $colorImages, $color);
                    }
                }
            }

            // Generate variants
            $this->generateProductVariants($product, $request->colors ?? [], $this->generateSizes($request->size_from, $request->size_to));

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'product' => $product
            ]);

        } catch (\Exception $e) {
            \Log::error('Error creating product: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->except(['images', 'color_images']),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ], 500);
        }
    }
    
    public function show(Product $product)
    {
        $product->load(['category', 'images', 'variants']);
        return view('admin.products.show', compact('product'));
    }
    
    public function edit(Product $product)
    {
        $product->load(['category', 'images', 'variants']);
        $categories = Category::active()->ordered()->withTranslation(app()->getLocale())->get();
        
        return response()->json([
            'success' => true,
            'product' => $product,
            'categories' => $categories
        ]);
    }
    
    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|unique:products,sku,' . $product->id,
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'compare_price' => 'nullable|numeric|min:0',
                'stock_status' => 'required|in:in_stock,out_of_stock,pre_order',
                'is_active' => 'boolean',
                'colors' => 'nullable|array',
                'size_from' => 'required|integer|min:1|max:100',
                'size_to' => 'required|integer|min:1|max:100|gte:size_from',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'color_images' => 'nullable|array',
                'color_images.*' => 'nullable|array',
                'color_images.*.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
            ]);

            // Update product
            $product->update([
                'name' => $request->name,
                'slug' => \Str::slug($request->name),
                'sku' => $request->sku,
                'category_id' => $request->category_id,
                'description' => $request->description ?: 'No description provided',
                'price' => $request->price,
                'compare_price' => $request->compare_price,
                'stock_status' => $request->stock_status,
                'is_active' => $request->boolean('is_active'),
                'sizes' => $this->generateSizes($request->size_from, $request->size_to),
                'colors' => $request->colors ?? []
            ]);

            // Handle general image uploads
            if ($request->hasFile('images')) {
                $this->uploadProductImages($product, $request->file('images'), null);
            }

            // Handle color-specific image uploads
            if ($request->has('color_images')) {
                foreach ($request->file('color_images') as $color => $colorImages) {
                    if (is_array($colorImages)) {
                        $this->uploadProductImages($product, $colorImages, $color);
                    }
                }
            }

            // Update variants
            $product->variants()->delete();
            $this->generateProductVariants($product, $request->colors ?? [], $this->generateSizes($request->size_from, $request->size_to));

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'product' => $product
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy(Product $product)
    {
        try {
            // Delete associated images
            foreach ($product->images as $image) {
                if (\Storage::disk('public')->exists($image->image_path)) {
                    \Storage::disk('public')->delete($image->image_path);
                }
            }
            
            // Delete product (variants will be deleted by cascade)
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate size array from range
     */
    private function generateSizes($from, $to)
    {
        $sizes = [];
        for ($i = $from; $i <= $to; $i++) {
            $sizes[] = (string)$i;
        }
        return $sizes;
    }

    /**
     * Upload product images
     */
    private function uploadProductImages($product, $images, $color = null)
    {
        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');
            
            // Check if this should be the primary image
            $isPrimary = false;
            if ($color === null) {
                // For general images, make the first one primary if no primary exists
                if ($index === 0) {
                    $existingPrimary = $product->images()->where('is_primary', true)->exists();
                    $isPrimary = !$existingPrimary;
                }
            }
            
            $product->images()->create([
                'image_path' => $path,
                'alt_text' => $product->name . ' image ' . ($index + 1),
                'is_primary' => $isPrimary,
                'color' => $color // Associate image with color if provided
            ]);
        }
    }

    /**
     * Generate product variants from colors and sizes
     */
    private function generateProductVariants($product, $colors, $sizes)
    {
        if (empty($colors)) {
            $colors = ['default'];
        }

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                $product->variants()->create([
                    'color' => $color,
                    'size' => $size,
                    'sku' => $product->sku . '-' . str_replace('#', '', $color) . '-' . $size,
                    'stock_quantity' => 0,
                    'price' => $product->price,
                    'is_active' => true
                ]);
            }
        }
    }
}
