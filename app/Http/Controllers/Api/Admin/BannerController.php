<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Models\Banner;
use App\Models\BannerTranslation;
use App\Models\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of banners
     * GET /api/admin/banners
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Banner::with(['translations' => function($query) {
                $query->with('language');
            }]);

            // Filter by type
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            // Filter by active status
            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            // Sort
            $sortBy = $request->get('sort_by', 'sort_order');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            $banners = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $this->transformBanners($banners->items()),
                'pagination' => [
                    'current_page' => $banners->currentPage(),
                    'last_page' => $banners->lastPage(),
                    'per_page' => $banners->perPage(),
                    'total' => $banners->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get banners',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created banner
     * POST /api/admin/banners
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'subtitle' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'cta_text' => 'nullable|string|max:100',
                'cta_link' => 'nullable|string|max:500',
                'type' => 'required|in:hero,promotional,category',
                'is_active' => 'boolean',
                'sort_order' => 'integer|min:0'
            ]);

            DB::beginTransaction();

            // Create banner
            $banner = Banner::create([
                'type' => $validated['type'],
                'is_active' => $validated['is_active'] ?? true,
                'sort_order' => $validated['sort_order'] ?? 0,
                'settings' => []
            ]);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('banners', 'public');
            }

            // Create translation for default language (French)
            $defaultLanguage = Language::where('code', 'fr')->first();
            if ($defaultLanguage) {
                BannerTranslation::create([
                    'banner_id' => $banner->id,
                    'language_id' => $defaultLanguage->id,
                    'title' => $validated['title'],
                    'subtitle' => $validated['subtitle'],
                    'button_text' => $validated['cta_text'],
                    'button_url' => $validated['cta_link'],
                    'image' => $imagePath,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Banner created successfully',
                'data' => $this->transformBanner($banner->fresh(['translations.language']))
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified banner
     * GET /api/admin/banners/{id}
     */
    public function show(string $id): JsonResponse
    {
        try {
            $banner = Banner::with(['translations.language'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $this->transformBanner($banner)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified banner
     * PUT /api/admin/banners/{id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $banner = Banner::findOrFail($id);

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'subtitle' => 'nullable|string|max:255',
                'cta_text' => 'nullable|string|max:100',
                'cta_link' => 'nullable|string|max:500',
                'type' => 'sometimes|in:hero,promotional,category',
                'is_active' => 'boolean',
                'sort_order' => 'integer|min:0'
            ]);

            DB::beginTransaction();

            // Update banner
            $banner->update(array_filter($validated, function($key) {
                return !in_array($key, ['title', 'subtitle', 'cta_text', 'cta_link']);
            }, ARRAY_FILTER_USE_KEY));

            // Update translation
            $defaultLanguage = Language::where('code', 'fr')->first();
            if ($defaultLanguage) {
                $translation = $banner->translations()->where('language_id', $defaultLanguage->id)->first();
                
                if ($translation) {
                    $translation->update([
                        'title' => $validated['title'] ?? $translation->title,
                        'subtitle' => $validated['subtitle'] ?? $translation->subtitle,
                        'button_text' => $validated['cta_text'] ?? $translation->button_text,
                        'button_url' => $validated['cta_link'] ?? $translation->button_url,
                    ]);
                } else {
                    BannerTranslation::create([
                        'banner_id' => $banner->id,
                        'language_id' => $defaultLanguage->id,
                        'title' => $validated['title'],
                        'subtitle' => $validated['subtitle'],
                        'button_text' => $validated['cta_text'],
                        'button_url' => $validated['cta_link'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Banner updated successfully',
                'data' => $this->transformBanner($banner->fresh(['translations.language']))
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified banner
     * DELETE /api/admin/banners/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $banner = Banner::findOrFail($id);

            DB::beginTransaction();

            // Delete associated translations
            $banner->translations()->delete();

            // Delete banner
            $banner->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Banner deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload image for banner
     * POST /api/admin/banners/{id}/upload-image
     */
    public function uploadImage(Request $request, string $id): JsonResponse
    {
        try {
            $banner = Banner::findOrFail($id);

            $validated = $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240'
            ]);

            // Delete old image if exists
            $defaultLanguage = Language::where('code', 'fr')->first();
            if ($defaultLanguage) {
                $translation = $banner->translations()->where('language_id', $defaultLanguage->id)->first();
                if ($translation && $translation->image) {
                    Storage::disk('public')->delete($translation->image);
                }
            }

            // Store new image
            $imagePath = $request->file('image')->store('banners', 'public');

            // Update translation with new image path
            if ($defaultLanguage) {
                $translation = $banner->translations()->where('language_id', $defaultLanguage->id)->first();
                if ($translation) {
                    $translation->update(['image' => $imagePath]);
                } else {
                    BannerTranslation::create([
                        'banner_id' => $banner->id,
                        'language_id' => $defaultLanguage->id,
                        'image' => $imagePath,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'image_url' => asset('storage/' . $imagePath)
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
     * Transform banner data to match frontend spec
     */
    private function transformBanner($banner): array
    {
        $translation = $banner->translations->first();
        
        return [
            'id' => $banner->id,
            'title' => $translation?->title,
            'subtitle' => $translation?->subtitle,
            'image' => $translation?->image ? asset('storage/' . $translation->image) : null,
            'video' => $translation?->video ? asset('storage/' . $translation->video) : null,
            'video_url' => $translation?->video_url,
            'cta_text' => $translation?->button_text,
            'cta_link' => $translation?->button_url,
            'type' => $banner->type,
            'is_active' => $banner->is_active,
            'sort_order' => $banner->sort_order,
            'created_at' => $banner->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $banner->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Transform multiple banners
     */
    private function transformBanners($banners): array
    {
        return collect($banners)->map(function($banner) {
            return $this->transformBanner($banner);
        })->toArray();
    }
}
