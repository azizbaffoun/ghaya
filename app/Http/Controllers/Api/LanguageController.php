<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Get all active languages
     * GET /api/v1/languages
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $languages = Language::where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => LanguageResource::collection($languages)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get languages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get default language
     * GET /api/v1/languages/default
     */
    public function default(): JsonResponse
    {
        try {
            $language = Language::where('is_active', true)
                ->where('is_default', true)
                ->first();

            if (!$language) {
                // Fallback to first active language
                $language = Language::where('is_active', true)->first();
            }

            if (!$language) {
                return response()->json([
                    'success' => false,
                    'message' => 'No languages available'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new LanguageResource($language)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get default language',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}



