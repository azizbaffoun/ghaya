<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use App\Models\PageSectionTranslation;
use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageBuilderController extends Controller
{
    /**
     * Display the page builder interface
     */
    public function index(Request $request)
    {
        $locale = $request->get('lang', 'fr');
        app()->setLocale($locale);
        
        $page = $request->get('page', 'home');
        $sections = PageSection::where('page', $page)
            ->with(['translations' => function($query) use ($locale) {
                $query->whereHas('language', function($q) use ($locale) {
                    $q->where('code', $locale);
                });
            }])
            ->orderBy('sort_order')
            ->get();

        $languages = Language::active()->get();
        $categories = Category::active()->with('translations')->get();
        $products = Product::with('translations')->get();
        $banners = Banner::active()->with('translations')->get();

        return view('admin.page-builder.index', compact('sections', 'languages', 'categories', 'products', 'banners', 'page'));
    }

    /**
     * Store a newly created section
     */
    public function store(Request $request)
    {
        $request->validate([
            'page' => 'required|string',
            'type' => 'required|string',
            'name' => 'required|string',
            'settings' => 'nullable|array',
            'data' => 'nullable|array',
            'translations' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // Create the section
            $section = PageSection::create([
                'page' => $request->page,
                'type' => $request->type,
                'name' => $request->name,
                'is_active' => $request->get('is_active', true),
                'sort_order' => PageSection::where('page', $request->page)->max('sort_order') + 1,
                'settings' => $request->settings ?? [],
                'data' => $request->data ?? [],
            ]);

            // Create translations
            foreach ($request->translations as $languageCode => $translationData) {
                $language = Language::where('code', $languageCode)->first();
                if ($language) {
                    PageSectionTranslation::create([
                        'page_section_id' => $section->id,
                        'language_id' => $language->id,
                        'title' => $translationData['title'] ?? null,
                        'subtitle' => $translationData['subtitle'] ?? null,
                        'content' => $translationData['content'] ?? null,
                        'button_text' => $translationData['button_text'] ?? null,
                        'button_url' => $translationData['button_url'] ?? null,
                        'image' => $translationData['image'] ?? null,
                        'additional_data' => $translationData['additional_data'] ?? [],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Section created successfully',
                'section' => $section->load('translations')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified section
     */
    public function update(Request $request, PageSection $pageBuilder)
    {
        $request->validate([
            'name' => 'required|string',
            'settings' => 'nullable|array',
            'data' => 'nullable|array',
            'translations' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // Update the section
            $pageBuilder->update([
                'name' => $request->name,
                'is_active' => $request->get('is_active', $pageBuilder->is_active),
                'settings' => $request->settings ?? $pageBuilder->settings,
                'data' => $request->data ?? $pageBuilder->data,
            ]);

            // Update translations
            foreach ($request->translations as $languageCode => $translationData) {
                $language = Language::where('code', $languageCode)->first();
                if ($language) {
                    $translation = PageSectionTranslation::where('page_section_id', $pageBuilder->id)
                        ->where('language_id', $language->id)
                        ->first();

                    if ($translation) {
                        $translation->update([
                            'title' => $translationData['title'] ?? $translation->title,
                            'subtitle' => $translationData['subtitle'] ?? $translation->subtitle,
                            'content' => $translationData['content'] ?? $translation->content,
                            'button_text' => $translationData['button_text'] ?? $translation->button_text,
                            'button_url' => $translationData['button_url'] ?? $translation->button_url,
                            'image' => $translationData['image'] ?? $translation->image,
                            'additional_data' => $translationData['additional_data'] ?? $translation->additional_data,
                        ]);
                    } else {
                        PageSectionTranslation::create([
                            'page_section_id' => $pageBuilder->id,
                            'language_id' => $language->id,
                            'title' => $translationData['title'] ?? null,
                            'subtitle' => $translationData['subtitle'] ?? null,
                            'content' => $translationData['content'] ?? null,
                            'button_text' => $translationData['button_text'] ?? null,
                            'button_url' => $translationData['button_url'] ?? null,
                            'image' => $translationData['image'] ?? null,
                            'additional_data' => $translationData['additional_data'] ?? [],
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Section updated successfully',
                'section' => $pageBuilder->load('translations')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified section
     */
    public function destroy(PageSection $pageBuilder)
    {
        try {
            $pageBuilder->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Section deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle section active status
     */
    public function toggle(PageSection $pageBuilder)
    {
        try {
            $pageBuilder->update(['is_active' => !$pageBuilder->is_active]);
            
            return response()->json([
                'success' => true,
                'message' => 'Section status updated successfully',
                'is_active' => $pageBuilder->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating section status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder sections
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:page_sections,id',
            'sections.*.sort_order' => 'required|integer|min:0',
        ]);

        try {
            foreach ($request->sections as $sectionData) {
                PageSection::where('id', $sectionData['id'])
                    ->update(['sort_order' => $sectionData['sort_order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sections reordered successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reordering sections: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available categories for auto-display
     */
    public function getCategories()
    {
        $categories = Category::active()
            ->with(['translations' => function($query) {
                $query->whereHas('language', function($q) {
                    $q->where('code', app()->getLocale());
                });
            }])
            ->get();

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    /**
     * Get available products for auto-display
     */
    public function getProducts()
    {
        $products = Product::with(['translations' => function($query) {
                $query->whereHas('language', function($q) {
                    $q->where('code', app()->getLocale());
                });
            }])
            ->get();

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    /**
     * Get available banners
     */
    public function getBanners()
    {
        $banners = Banner::active()
            ->with(['translations' => function($query) {
                $query->whereHas('language', function($q) {
                    $q->where('code', app()->getLocale());
                });
            }])
            ->get();

        return response()->json([
            'success' => true,
            'banners' => $banners
        ]);
    }
}
