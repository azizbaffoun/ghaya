<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Global search (products, categories)
     * GET /api/v1/search
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $type = $request->get('type', 'all'); // all, products, categories
            $limit = $request->get('limit', 20);

            if (empty($query)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'products' => [],
                        'categories' => [],
                        'total' => 0
                    ]
                ]);
            }

            $results = [
                'products' => [],
                'categories' => [],
                'total' => 0
            ];

            // Search products
            if ($type === 'all' || $type === 'products') {
                $products = Product::with(['category', 'images'])
                    ->where('is_active', true)
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%")
                          ->orWhere('sku', 'like', "%{$query}%");
                    })
                    ->limit($limit)
                    ->get();

                $results['products'] = ProductResource::collection($products);
            }

            // Search categories
            if ($type === 'all' || $type === 'categories') {
                $categories = Category::with(['products'])
                    ->where('is_active', true)
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->limit($limit)
                    ->get();

                $results['categories'] = CategoryResource::collection($categories);
            }

            $results['total'] = count($results['products']) + count($results['categories']);

            return response()->json([
                'success' => true,
                'data' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search autocomplete suggestions
     * GET /api/v1/search/suggestions
     */
    public function suggestions(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $limit = $request->get('limit', 10);

            if (strlen($query) < 2) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            $suggestions = [];

            // Get product suggestions
            $products = Product::where('is_active', true)
                ->where('name', 'like', "%{$query}%")
                ->select('name', 'sku')
                ->limit($limit)
                ->get();

            foreach ($products as $product) {
                $suggestions[] = [
                    'type' => 'product',
                    'text' => $product->name,
                    'value' => $product->sku,
                    'url' => '/products/' . $product->id
                ];
            }

            // Get category suggestions
            $categories = Category::where('is_active', true)
                ->where('name', 'like', "%{$query}%")
                ->select('name', 'slug')
                ->limit($limit)
                ->get();

            foreach ($categories as $category) {
                $suggestions[] = [
                    'type' => 'category',
                    'text' => $category->name,
                    'value' => $category->slug,
                    'url' => '/categories/' . $category->id
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $suggestions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get suggestions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available filters for products
     * GET /api/v1/products/filters
     */
    public function getProductFilters(Request $request): JsonResponse
    {
        try {
            $filters = [
                'categories' => Category::where('is_active', true)
                    ->withCount('products')
                    ->having('products_count', '>', 0)
                    ->orderBy('name')
                    ->get(['id', 'name', 'slug', 'products_count']),
                
                'price_ranges' => [
                    ['min' => 0, 'max' => 50, 'label' => 'Under $50'],
                    ['min' => 50, 'max' => 100, 'label' => '$50 - $100'],
                    ['min' => 100, 'max' => 200, 'label' => '$100 - $200'],
                    ['min' => 200, 'max' => 500, 'label' => '$200 - $500'],
                    ['min' => 500, 'max' => null, 'label' => 'Over $500'],
                ],
                
                'stock_status' => [
                    ['value' => 'in_stock', 'label' => 'In Stock'],
                    ['value' => 'out_of_stock', 'label' => 'Out of Stock'],
                    ['value' => 'pre_order', 'label' => 'Pre-order'],
                ],
                
                'sort_options' => [
                    ['value' => 'name', 'label' => 'Name A-Z'],
                    ['value' => 'name_desc', 'label' => 'Name Z-A'],
                    ['value' => 'price', 'label' => 'Price Low to High'],
                    ['value' => 'price_desc', 'label' => 'Price High to Low'],
                    ['value' => 'created_at', 'label' => 'Newest First'],
                    ['value' => 'created_at_desc', 'label' => 'Oldest First'],
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $filters
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}



