<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Services\LocalizationService;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $this->getLocale($request);
        
        // Set the application locale
        App::setLocale($locale);
        
        // Store locale in request for later use
        $request->attributes->set('locale', $locale);
        
        return $next($request);
    }

    /**
     * Determine the locale for the request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    private function getLocale(Request $request): string
    {
        $supportedLocales = LocalizationService::SUPPORTED_LANGUAGES;
        $defaultLocale = config('app.locale', 'en');

        // 1. Check if locale is passed in query parameter
        if ($request->has('locale') && in_array($request->query('locale'), $supportedLocales)) {
            return $request->query('locale');
        }

        // 2. Check if locale is passed in request header
        if ($request->hasHeader('Accept-Language')) {
            $locale = $this->parseAcceptLanguageHeader($request->header('Accept-Language'), $supportedLocales);
            if ($locale) {
                return $locale;
            }
        }

        // 3. Check if user is authenticated and has language preference
        if (Auth::check()) {
            $userLocale = Auth::user()->language_preference ?? null;
            if ($userLocale && in_array($userLocale, $supportedLocales)) {
                return $userLocale;
            }
        }

        // 4. Check if locale is in session
        if (session()->has('locale') && in_array(session('locale'), $supportedLocales)) {
            return session('locale');
        }

        // 5. Check if locale is in cookie
        if ($request->hasCookie('locale') && in_array($request->cookie('locale'), $supportedLocales)) {
            return $request->cookie('locale');
        }

        // 6. Fall back to default locale
        return $defaultLocale;
    }

    /**
     * Parse Accept-Language header and return best matching locale
     *
     * @param  string  $acceptLanguage
     * @param  array   $supportedLocales
     * @return string|null
     */
    private function parseAcceptLanguageHeader(string $acceptLanguage, array $supportedLocales): ?string
    {
        // Parse the Accept-Language header
        $languages = [];
        foreach (explode(',', $acceptLanguage) as $lang) {
            $parts = explode(';', $lang);
            $locale = trim($parts[0]);
            $quality = isset($parts[1]) ? (float)str_replace('q=', '', trim($parts[1])) : 1.0;
            
            // Extract language code (e.g., 'en' from 'en-US')
            $langCode = explode('-', $locale)[0];
            
            if (in_array($langCode, $supportedLocales)) {
                $languages[$langCode] = $quality;
            }
        }

        // Sort by quality (highest first)
        arsort($languages);

        // Return the best matching locale
        return !empty($languages) ? key($languages) : null;
    }
}

