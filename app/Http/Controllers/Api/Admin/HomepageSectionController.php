<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomepageSectionResource;
use App\Http\Requests\StoreHomepageSectionRequest;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HomepageSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/admin/homepage-sections
     */
    public function index(Request $request): JsonResponse
    {
        $query = PageSection::where('page', 'home');

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

        $sections = $query->get();

        return response()->json([
            'success' => true,
            'data' => $this->transformSections($sections)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/admin/homepage-sections
     */
    public function store(StoreHomepageSectionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['page'] = 'home'; // Force homepage
        $section = PageSection::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Homepage section created successfully',
            'data' => $this->transformSection($section)
        ], 201);
    }

    /**
     * Display the specified resource.
     * GET /api/admin/homepage-sections/{id}
     */
    public function show(string $id): JsonResponse
    {
        $section = PageSection::where('page', 'home')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $this->transformSection($section)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/admin/homepage-sections/{id}
     */
    public function update(StoreHomepageSectionRequest $request, string $id): JsonResponse
    {
        $section = PageSection::where('page', 'home')->findOrFail($id);
        $section->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Homepage section updated successfully',
            'data' => $this->transformSection($section)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/admin/homepage-sections/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $section = PageSection::where('page', 'home')->findOrFail($id);
        $section->delete();

        return response()->json([
            'success' => true,
            'message' => 'Homepage section deleted successfully'
        ]);
    }

    /**
     * Reorder homepage sections
     * PUT /api/admin/homepage-sections/reorder
     */
    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'section_ids' => 'required|array',
            'section_ids.*' => 'integer|exists:page_sections,id'
        ]);

        foreach ($validated['section_ids'] as $index => $sectionId) {
            PageSection::where('id', $sectionId)
                ->where('page', 'home')
                ->update(['sort_order' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sections reordered successfully'
        ]);
    }

    /**
     * Transform section data to match frontend spec
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
            'is_visible' => $section->is_active, // Map is_active to is_visible
            'sort_order' => $section->sort_order, // Map order to sort_order
            'created_at' => $section->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $section->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Transform multiple sections
     */
    private function transformSections($sections): array
    {
        return collect($sections)->map(function($section) {
            return $this->transformSection($section);
        })->toArray();
    }
}
