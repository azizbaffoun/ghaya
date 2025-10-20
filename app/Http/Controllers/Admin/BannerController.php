<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Toggle banner active status
     */
    public function toggle($id)
    {
        try {
            $banner = Banner::findOrFail($id);
            $banner->is_active = !$banner->is_active;
            $banner->save();

            return response()->json([
                'success' => true,
                'is_active' => $banner->is_active,
                'message' => $banner->is_active ? 'Banner activated' : 'Banner deactivated'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error toggling banner: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new banner
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'subtitle' => 'nullable|string|max:255',
                'type' => 'required|string|in:hero,promotional,category',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'cta_text' => 'nullable|string|max:255',
                'cta_link' => 'nullable|url|max:255',
                'is_active' => 'boolean'
            ]);

            $banner = Banner::create([
                'type' => $request->type,
                'position' => 'top',
                'is_active' => $request->boolean('is_active', true),
                'sort_order' => Banner::max('sort_order') + 1,
                'settings' => json_encode([
                    'description' => 'Banner created via admin'
                ])
            ]);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('banners', 'public');
            }

            // Create default translation
            $french = Language::where('code', 'fr')->first();
            if ($french) {
                BannerTranslation::create([
                    'banner_id' => $banner->id,
                    'language_id' => $french->id,
                    'title' => $request->title,
                    'subtitle' => $request->subtitle,
                    'button_text' => $request->cta_text,
                    'button_url' => $request->cta_link,
                    'image' => $imagePath
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Banner created successfully',
                'banner' => $banner
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating banner: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update banner
     */
    public function update(Request $request, $id)
    {
        try {
            $banner = Banner::findOrFail($id);
            
            $request->validate([
                'title' => 'sometimes|string|max:255',
                'subtitle' => 'nullable|string|max:255',
                'cta_text' => 'nullable|string|max:255',
                'cta_link' => 'nullable|url|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'sometimes|boolean'
            ]);

            $banner->update([
                'is_active' => $request->boolean('is_active', $banner->is_active)
            ]);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                // Delete old image
                if ($banner->getTranslation('image')) {
                    Storage::disk('public')->delete($banner->getTranslation('image'));
                }
                $imagePath = $request->file('image')->store('banners', 'public');
            }

            // Update translation
            $french = Language::where('code', 'fr')->first();
            if ($french) {
                $translationData = [
                    'banner_id' => $banner->id,
                    'language_id' => $french->id,
                    'title' => $request->input('title', $banner->getTranslation('title')),
                    'subtitle' => $request->input('subtitle', $banner->getTranslation('subtitle')),
                    'button_text' => $request->input('cta_text', $banner->getTranslation('button_text')),
                    'button_url' => $request->input('cta_link', $banner->getTranslation('button_url'))
                ];

                if ($imagePath) {
                    $translationData['image'] = $imagePath;
                }

                BannerTranslation::updateOrCreate(
                    [
                        'banner_id' => $banner->id,
                        'language_id' => $french->id
                    ],
                    $translationData
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Banner updated successfully',
                'banner' => $banner
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating banner: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete banner
     */
    public function destroy($id)
    {
        try {
            $banner = Banner::findOrFail($id);
            
            // Delete associated image
            if ($banner->getTranslation('image')) {
                Storage::disk('public')->delete($banner->getTranslation('image'));
            }
            
            $banner->delete();

            return response()->json([
                'success' => true,
                'message' => 'Banner deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting banner: ' . $e->getMessage()
            ], 500);
        }
    }
}