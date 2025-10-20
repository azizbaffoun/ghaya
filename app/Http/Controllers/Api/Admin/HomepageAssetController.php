<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageAsset;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomepageAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/admin/homepage-assets
     */
    public function index(Request $request): JsonResponse
    {
        $query = HomepageAsset::query();

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $assets = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $assets->items(),
            'pagination' => [
                'current_page' => $assets->currentPage(),
                'last_page' => $assets->lastPage(),
                'per_page' => $assets->perPage(),
                'total' => $assets->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/admin/homepage-assets
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,svg,mp4,webm,pdf|max:10240', // 10MB max
            'type' => 'required|string|in:image,video,icon,file',
            'name' => 'required|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
        ]);

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($validated['name']) . '_' . time() . '.' . $extension;
            
            // Store file in organized directory structure
            $directory = 'homepage-assets/' . $validated['type'] . '/' . date('Y/m');
            $path = $file->storeAs($directory, $filename, 'public');

            // Get file metadata
            $metadata = [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'extension' => $extension,
            ];

            // Add image dimensions if it's an image
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg'])) {
                $imageInfo = getimagesize($file->getPathname());
                if ($imageInfo) {
                    $metadata['dimensions'] = [
                        'width' => $imageInfo[0],
                        'height' => $imageInfo[1],
                    ];
                }
            }

            $asset = HomepageAsset::create([
                'type' => $validated['type'],
                'name' => $validated['name'],
                'path' => $path,
                'alt_text' => $validated['alt_text'],
                'category' => $validated['category'],
                'metadata' => $metadata,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asset uploaded successfully',
                'data' => $asset
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload asset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/admin/homepage-assets/{id}
     */
    public function show(string $id): JsonResponse
    {
        $asset = HomepageAsset::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $asset
        ]);
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/admin/homepage-assets/{id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $asset = HomepageAsset::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
        ]);

        $asset->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Asset updated successfully',
            'data' => $asset
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/admin/homepage-assets/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $asset = HomepageAsset::findOrFail($id);
        
        // Delete file from storage
        if (Storage::disk('public')->exists($asset->path)) {
            Storage::disk('public')->delete($asset->path);
        }

        $asset->delete();

        return response()->json([
            'success' => true,
            'message' => 'Asset deleted successfully'
        ]);
    }
}
