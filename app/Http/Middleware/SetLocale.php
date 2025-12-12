<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use App\Models\Language;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority order: request parameter > user preference > session > browser > default
        // Support both 'lang' and 'hl' query parameters
        $locale = $request->get('hl') ?? $request->get('lang');
        
        // If no request parameter, check user preference (if authenticated)
        if (!$locale && auth()->check()) {
            $locale = auth()->user()->getPreferredLanguage();
        }
        
        // If still no locale, check session
        if (!$locale) {
            $locale = session('locale');
        }
        
        // If still no locale, check browser header
        if (!$locale) {
            $acceptLanguage = $request->header('Accept-Language');
            if ($acceptLanguage) {
                // Parse Accept-Language header (e.g., "en-US,en;q=0.9,fr;q=0.8")
                $languages = explode(',', $acceptLanguage);
                $primaryLang = trim(explode(';', $languages[0])[0]);
                $locale = substr($primaryLang, 0, 2); // Get first 2 chars (e.g., "en" from "en-US")
            } else {
                $locale = 'fr'; // Default to French
            }
        }
        
        // Validate locale exists and is active
        $language = Language::where('code', $locale)
            ->where('is_active', true)
            ->first();
        
        // If language not found or inactive, use default
        if (!$language) {
            $defaultLanguage = Language::where('is_default', true)
                ->where('is_active', true)
                ->first();
            $locale = $defaultLanguage ? $defaultLanguage->code : 'fr';
        }
        
        // Set the application locale
        App::setLocale($locale);
        
        // Store in session for future requests (only if not from user preference)
        if (!$request->get('lang') && !auth()->check()) {
            session(['locale' => $locale]);
        }
        
        return $next($request);
    }
}
