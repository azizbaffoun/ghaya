<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        // Set language from request
        $locale = $request->get('lang', 'fr');
        App::setLocale($locale);
        
        $query = Category::withCount('products')
            ->withTranslation($locale);
        
        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $categories = $query->ordered()->get();
            
        return view('admin.categories.index', compact('categories', 'locale'));
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
            \Log::info('Category Store Request:', [
                'has_image' => $request->hasFile('image'),
                'all_files' => $request->allFiles(),
                'request_data' => $request->except(['image'])
            ]);

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'parent_id' => 'nullable|exists:categories,id',
                'sort_order' => 'integer|min:0',
                'is_active' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
            ]);

            // Create category
            $category = Category::create([
                'name' => $request->name,
                'slug' => \Str::slug($request->name),
                'description' => $request->description,
                'parent_id' => $request->parent_id,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->boolean('is_active'),
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('categories', 'public');
                $category->update(['image' => $path]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'category' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating category: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function show(Category $category)
    {
        $category->load(['products']);
        return view('admin.categories.show', compact('category'));
    }
    
    public function edit(Category $category)
    {
        $categories = Category::active()->ordered()->withTranslation(app()->getLocale())->get();
        
        return response()->json([
            'success' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'parent_id' => $category->parent_id,
                'sort_order' => $category->sort_order,
                'is_active' => $category->is_active,
                'image' => $category->image,
                'image_url' => $category->image ? asset('storage/' . $category->image) : null
            ],
            'categories' => $categories
        ]);
    }
    
    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'parent_id' => 'nullable|exists:categories,id',
                'sort_order' => 'integer|min:0',
                'is_active' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
            ]);

            // Update category
            $category->update([
                'name' => $request->name,
                'slug' => \Str::slug($request->name),
                'description' => $request->description,
                'parent_id' => $request->parent_id,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->boolean('is_active'),
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($category->image && \Storage::disk('public')->exists($category->image)) {
                    \Storage::disk('public')->delete($category->image);
                }
                
                $path = $request->file('image')->store('categories', 'public');
                $category->update(['image' => $path]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'category' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating category: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy(Category $category)
    {
        try {
            // Check if category has products
            if ($category->products()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with products. Please move or delete products first.'
                ], 400);
            }

            // Check if category has children
            if ($category->children()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with subcategories. Please delete subcategories first.'
                ], 400);
            }

            // Delete associated image
            if ($category->image && \Storage::disk('public')->exists($category->image)) {
                \Storage::disk('public')->delete($category->image);
            }
            
            // Delete category
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category: ' . $e->getMessage()
            ], 500);
        }
    }
}
