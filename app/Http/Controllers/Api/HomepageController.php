<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomepageSectionResource;
use App\Models\PageSection;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;

class HomepageController extends Controller
{
    /**
     * Get combined homepage content (banners + sections)
     * GET /api/homepage
     */
    public function index(): JsonResponse
    {
        // Get active banners
        $banners = Banner::with(['translations' => function($query) {
            $query->with('language');
        }])
        ->where('is_active', true)
        ->where(function ($q) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', now());
        })
        ->where(function ($q) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now());
        })
        ->orderBy('sort_order')
        ->get();

        // Get active homepage sections
        $sections = PageSection::where('page', 'home')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'banners' => $this->transformBanners($banners),
                'sections' => $this->transformSections($sections)
            ]
        ]);
    }

    /**
     * Get only banners
     * GET /api/banners
     */
    public function banners(): JsonResponse
    {
        $banners = Banner::with(['translations' => function($query) {
            $query->with('language');
        }])
        ->where('is_active', true)
        ->where(function ($q) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', now());
        })
        ->where(function ($q) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now());
        })
        ->orderBy('sort_order')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $this->transformBanners($banners)
        ]);
    }

    /**
     * Get only homepage sections
     * GET /api/homepage-sections
     */
    public function sections(): JsonResponse
    {
        $sections = PageSection::where('page', 'home')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $this->transformSections($sections)
        ]);
    }

    /**
     * Get homepage section by type
     * GET /api/homepage/{type}
     */
    public function show(string $type): JsonResponse
    {
        $section = PageSection::where('page', 'home')
            ->where('type', $type)
            ->where('is_active', true)
            ->first();

        if (!$section) {
            return response()->json([
                'success' => false,
                'message' => 'Section not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->transformSection($section)
        ]);
    }

    /**
     * Transform banner data to match frontend spec
     */
    private function transformBanners($banners): array
    {
        return collect($banners)->map(function($banner) {
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
                'sort_order' => $banner->sort_order,
            ];
        })->toArray();
    }

    /**
     * Transform section data to match frontend spec
     */
    private function transformSections($sections): array
    {
        return collect($sections)->map(function($section) {
            return $this->transformSection($section);
        })->toArray();
    }

    /**
     * Transform single section
     */
    private function transformSection($section): array
    {
        return [
            'id' => $section->id,
            'name' => $section->name,
            'type' => $section->type,
            'title' => $section->getTranslation('title'),
            'content' => $section->getTranslation('content'),
            'settings' => $section->settings ?? [],
            'is_visible' => $section->is_active,
            'sort_order' => $section->sort_order,
        ];
    }
}
