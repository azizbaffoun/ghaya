<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use App\Models\PageSectionTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageSectionController extends Controller
{
    /**
     * Toggle section active status
     */
    public function toggle($id)
    {
        try {
            $section = PageSection::findOrFail($id);
            $section->is_active = !$section->is_active;
            $section->save();

            return response()->json([
                'success' => true,
                'is_active' => $section->is_active,
                'message' => $section->is_active ? 'Section activated' : 'Section deactivated'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error toggling section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new section
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'title' => 'nullable|string|max:255',
                'is_visible' => 'boolean'
            ]);

            $section = PageSection::create([
                'page' => 'home',
                'type' => $request->type,
                'name' => $request->name,
                'is_active' => $request->boolean('is_visible', true),
                'sort_order' => PageSection::where('page', 'home')->max('sort_order') + 1,
                'settings' => json_encode([
                    'component' => $request->type,
                    'description' => 'Section created via admin'
                ])
            ]);

            // Create default translation if title provided
            if ($request->title) {
                $french = Language::where('code', 'fr')->first();
                if ($french) {
                    PageSectionTranslation::create([
                        'page_section_id' => $section->id,
                        'language_id' => $french->id,
                        'title' => $request->title
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Section created successfully',
                'section' => $section
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update section
     */
    public function update(Request $request, $id)
    {
        try {
            $section = PageSection::findOrFail($id);
            
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'title' => 'nullable|string|max:255',
                'is_visible' => 'sometimes|boolean'
            ]);

            $section->update([
                'name' => $request->input('name', $section->name),
                'is_active' => $request->boolean('is_visible', $section->is_active)
            ]);

            // Update translation if title provided
            if ($request->title) {
                $french = Language::where('code', 'fr')->first();
                if ($french) {
                    PageSectionTranslation::updateOrCreate(
                        [
                            'page_section_id' => $section->id,
                            'language_id' => $french->id
                        ],
                        ['title' => $request->title]
                    );
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Section updated successfully',
                'section' => $section
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete section
     */
    public function destroy($id)
    {
        try {
            $section = PageSection::findOrFail($id);
            $section->delete();

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
     * Reorder sections
     */
    public function reorder(Request $request)
    {
        try {
            $request->validate([
                'sections' => 'required|array',
                'sections.*.id' => 'required|integer',
                'sections.*.sort_order' => 'required|integer'
            ]);

            DB::transaction(function () use ($request) {
                foreach ($request->sections as $sectionData) {
                    PageSection::where('id', $sectionData['id'])
                        ->update(['sort_order' => $sectionData['sort_order']]);
                }
            });

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
}
