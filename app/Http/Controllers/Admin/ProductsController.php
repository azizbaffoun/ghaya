<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
            $allFiles = $request->allFiles();
            $fileDetails = [];
            $imageFiles = [];
            
            // Check for images in various formats
            if ($request->hasFile('images')) {
                $imageFiles = $request->file('images');
            } elseif (isset($allFiles['images']) && is_array($allFiles['images'])) {
                $imageFiles = $allFiles['images'];
            } else {
                // Check for images[0], images[1], etc.
                foreach ($allFiles as $key => $file) {
                    if (preg_match('/^images\[(\d+)\]$/', $key, $matches)) {
                        $imageFiles[$matches[1]] = $file;
                    }
                }
            }
            
            foreach ($imageFiles as $key => $file) {
                if ($file && $file->isValid()) {
                    $fileDetails[$key] = [
                        'name' => $file->getClientOriginalName(),
                        'mime' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'is_valid' => $file->isValid(),
                        'extension' => $file->getClientOriginalExtension(),
                    ];
                }
            }
            
            \Log::info('Product Store Request:', [
                'has_images' => $request->hasFile('images'),
                'images_count' => count($imageFiles),
                'file_details' => $fileDetails,
                'all_files_keys' => array_keys($allFiles),
                'raw_input' => array_keys($request->all()),
                'request_data' => $request->except(['images', 'color_images'])
            ]);

            // Validate non-file fields first (images handled separately)
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
                'images' => 'sometimes|nullable',
                'images.*' => 'sometimes|nullable',
            ]);

            // Manually validate image files if present
            if (!empty($imageFiles)) {
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 10240; // 10MB in KB
                
                foreach ($imageFiles as $key => $file) {
                    if (!$file || !$file->isValid()) {
                        throw ValidationException::withMessages([
                            "images.{$key}" => ['The uploaded file is not valid.']
                        ]);
                    }
                    
                    $mimeType = $file->getMimeType();
                    if (!in_array($mimeType, $allowedMimes)) {
                        throw ValidationException::withMessages([
                            "images.{$key}" => ['The file must be a jpeg, png, jpg, gif, or webp image.']
                        ]);
                    }
                    
                    if ($file->getSize() > ($maxSize * 1024)) {
                        throw ValidationException::withMessages([
                            "images.{$key}" => ['The file must be less than 10MB.']
                        ]);
                    }
                }
            }

            // Handle colors - only save if provided and not empty
            // Also filter out default black color if it's the only color (likely accidental)
            $colors = [];
            \Log::info('Product colors received (CREATE):', [
                'has_colors' => $request->has('colors'),
                'colors_raw' => $request->input('colors'),
                'colors_type' => gettype($request->input('colors')),
                'all_request_keys' => array_keys($request->except(['images', 'color_images']))
            ]);
            
            if ($request->has('colors') && is_array($request->colors) && !empty($request->colors)) {
                $colors = array_filter($request->colors, function($color) {
                    return !empty($color) && trim($color) !== '';
                });
                // If only black (#000000 or #000) was sent and nothing else, treat as empty
                if (count($colors) === 1 && in_array(strtolower($colors[0]), ['#000000', '#000', '000000', '000', 'black'])) {
                    \Log::warning('Only black color detected, treating as empty', ['color' => $colors[0]]);
                    $colors = [];
                }
            }
            
            \Log::info('Product colors after processing (CREATE):', ['colors' => $colors]);

            // Create product
            \Log::info('Creating product with colors:', [
                'colors_to_save' => $colors,
                'colors_count' => count($colors),
                'colors_type' => gettype($colors)
            ]);
            
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'sku' => $request->sku,
                'category_id' => $request->category_id,
                'description' => $request->description ?: 'No description provided',
                'price' => $request->price,
                'compare_price' => $request->compare_price,
                'stock_status' => $request->stock_status,
                'is_active' => $request->boolean('is_active'),
                'sizes' => $this->generateSizes($request->size_from, $request->size_to),
                'colors' => $colors
            ]);
            
            \Log::info('Product created, verifying colors saved:', [
                'product_id' => $product->id,
                'saved_colors' => $product->colors,
                'saved_colors_type' => gettype($product->colors)
            ]);

            // Handle general image uploads
            if (!empty($imageFiles)) {
                // Sort by key to maintain order
                ksort($imageFiles);
                $this->uploadProductImages($product, array_values($imageFiles), null);
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

        } catch (ValidationException $e) {
            $errors = $e->errors();
            \Log::error('Validation error creating product:', [
                'errors' => $errors,
                'all_errors' => json_encode($errors, JSON_PRETTY_PRINT),
                'request_data' => $request->except(['images', 'color_images']),
                'has_images' => $request->hasFile('images'),
                'all_files' => array_keys($request->allFiles())
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors,
                'debug' => [
                    'has_images' => $request->hasFile('images'),
                    'all_files_keys' => array_keys($request->allFiles())
                ]
            ], 422);
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
        
        // Format images for frontend
        $formattedImages = $product->images->map(function($image) {
            return [
                'id' => $image->id,
                'url' => asset('storage/' . $image->image_path),
                'path' => $image->image_path,
                'is_primary' => $image->is_primary ?? false,
                'color' => $image->color,
                'alt_text' => $image->alt_text
            ];
        });
        
        // Group images by color for easier handling
        $colorImages = [];
        foreach ($formattedImages as $image) {
            $color = $image['color'] ?? 'general';
            if (!isset($colorImages[$color])) {
                $colorImages[$color] = [];
            }
            $colorImages[$color][] = $image;
        }
        
        return response()->json([
            'success' => true,
            'product' => array_merge($product->toArray(), [
                'images' => $formattedImages,
                'color_images' => $colorImages
            ]),
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
                'images' => 'nullable|array',
                'images.*' => 'file|mimes:jpeg,jpg,png,gif,webp|max:10240',
                'color_images' => 'nullable|array',
                'color_images.*' => 'nullable|array',
                'color_images.*.*' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:10240'
            ], [
                'images.*.file' => 'Each file must be a valid file',
                'images.*.mimes' => 'Images must be jpeg, png, jpg, gif, or webp format',
                'images.*.max' => 'Each image must be less than 10MB',
            ]);

            // Handle colors - only save if provided and not empty
            // Also filter out default black color if it's the only color (likely accidental)
            $colors = [];
            if ($request->has('colors') && is_array($request->colors) && !empty($request->colors)) {
                $colors = array_filter($request->colors, function($color) {
                    return !empty($color) && trim($color) !== '';
                });
                // If only black (#000000 or #000) was sent and nothing else, treat as empty
                if (count($colors) === 1 && in_array(strtolower($colors[0]), ['#000000', '#000', '000000', '000', 'black'])) {
                    $colors = [];
                }
            }

            // Update product
            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'sku' => $request->sku,
                'category_id' => $request->category_id,
                'description' => $request->description ?: 'No description provided',
                'price' => $request->price,
                'compare_price' => $request->compare_price,
                'stock_status' => $request->stock_status,
                'is_active' => $request->boolean('is_active'),
                'sizes' => $this->generateSizes($request->size_from, $request->size_to),
                'colors' => $colors
            ]);

            // Handle image deletions
            if ($request->has('delete_images') && is_array($request->delete_images)) {
                foreach ($request->delete_images as $imageId) {
                    $image = $product->images()->find($imageId);
                    if ($image) {
                        // Delete file from storage
                        if (\Storage::disk('public')->exists($image->image_path)) {
                            \Storage::disk('public')->delete($image->image_path);
                        }
                        // Delete from database
                        $image->delete();
                    }
                }
            }

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
     * Generate size array from range (increments by 2)
     */
    private function generateSizes($from, $to)
    {
        $sizes = [];
        for ($i = $from; $i <= $to; $i += 2) {
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
