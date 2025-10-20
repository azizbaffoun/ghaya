<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\PageSection;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsiteController extends Controller
{
    /**
     * Display the unified website management interface
     */
    public function index(Request $request)
    {
        $locale = $request->get('lang', 'fr');
        app()->setLocale($locale);
        
        // Get banners with translations
        $banners = Banner::with(['translations' => function($query) use ($locale) {
            $query->whereHas('language', function($q) use ($locale) {
                $q->where('code', $locale);
            });
        }])
        ->orderBy('sort_order')
        ->get();
        
        // Get homepage sections with translations
        $sections = PageSection::where('page', 'home')
            ->with(['translations' => function($query) use ($locale) {
                $query->whereHas('language', function($q) use ($locale) {
                    $q->where('code', $locale);
                });
            }])
            ->orderBy('sort_order')
            ->get();
        
        $languages = Language::active()->ordered()->get();
        
        return view('admin.website.index', compact('banners', 'sections', 'languages', 'locale'));
    }
}
