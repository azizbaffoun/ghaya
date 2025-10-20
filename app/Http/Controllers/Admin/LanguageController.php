<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LanguageController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->get('lang', 'fr');
        app()->setLocale($locale);
        
        $languages = Language::orderBy('sort_order')->get();
        
        return view('admin.languages.index', compact('languages', 'locale'));
    }
    
    public function create()
    {
        return response()->json([
            'success' => true,
            'message' => 'Language creation form data'
        ]);
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:2|unique:languages,code',
                'name' => 'required|string|max:255',
                'native_name' => 'required|string|max:255',
                'flag' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean',
                'is_default' => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);

            // If this is set as default, unset other defaults
            if ($validated['is_default'] ?? false) {
                Language::where('is_default', true)->update(['is_default' => false]);
            }

            $language = Language::create($validated);

            // Handle flag upload
            if ($request->hasFile('flag')) {
                $path = $request->file('flag')->store('languages/flags', 'public');
                $language->update(['flag' => $path]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Language created successfully',
                'language' => $language
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating language: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function edit(Language $language)
    {
        return response()->json([
            'success' => true,
            'language' => $language
        ]);
    }
    
    public function update(Request $request, Language $language)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:2|unique:languages,code,' . $language->id,
                'name' => 'required|string|max:255',
                'native_name' => 'required|string|max:255',
                'flag' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean',
                'is_default' => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);

            // If this is set as default, unset other defaults
            if ($validated['is_default'] ?? false) {
                Language::where('is_default', true)->update(['is_default' => false]);
            }

            // Handle flag upload
            if ($request->hasFile('flag')) {
                // Delete old flag if exists
                if ($language->flag) {
                    Storage::disk('public')->delete($language->flag);
                }
                $path = $request->file('flag')->store('languages/flags', 'public');
                $validated['flag'] = $path;
            }

            $language->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Language updated successfully',
                'language' => $language
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating language: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy(Language $language)
    {
        try {
            // Don't allow deletion of default language
            if ($language->is_default) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete default language'
                ], 400);
            }

            // Delete flag file if exists
            if ($language->flag) {
                Storage::disk('public')->delete($language->flag);
            }

            $language->delete();

            return response()->json([
                'success' => true,
                'message' => 'Language deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting language: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function toggleStatus(Language $language)
    {
        try {
            $language->update(['is_active' => !$language->is_active]);
            
            return response()->json([
                'success' => true,
                'message' => 'Language status updated successfully',
                'is_active' => $language->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating language status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function setDefault(Language $language)
    {
        try {
            // Unset all other defaults
            Language::where('is_default', true)->update(['is_default' => false]);
            
            // Set this as default
            $language->update(['is_default' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'Default language updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error setting default language: ' . $e->getMessage()
            ], 500);
        }
    }
}