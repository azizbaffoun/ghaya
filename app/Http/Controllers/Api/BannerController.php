<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Get active banners
     * GET /api/v1/banners
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Banner::with(['translations'])
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                });

            // Filter by type
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            // Filter by position
            if ($request->has('position')) {
                $query->where('position', $request->position);
            }

            // Sort by sort_order
            $query->orderBy('sort_order');

            $banners = $query->get();

            return response()->json([
                'success' => true,
                'data' => BannerResource::collection($banners)
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
     * Get single banner
     * GET /api/v1/banners/{id}
     */
    public function show(string $id): JsonResponse
    {
        try {
            $banner = Banner::with(['translations'])
                ->where('is_active', true)
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new BannerResource($banner)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}



