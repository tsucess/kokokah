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

        // Debug logging
        \Log::info('[SetLocale] Middleware executed', [
            'path' => $request->path(),
            'locale' => $locale,
            'auth_user_id' => Auth::id(),
            'user_language_preference' => Auth::user()?->language_preference,
        ]);

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

        // 1. Check if locale is passed in query parameter (highest priority)
        if ($request->has('locale') && in_array($request->query('locale'), $supportedLocales)) {
            return $request->query('locale');
        }

        // 2. Check if user is authenticated and has language preference (user preference takes priority)
        if (Auth::check()) {
            // Refresh the user to get the latest data from the database
            $user = Auth::user()->fresh();
            $userLocale = $user->language_preference ?? null;
            if ($userLocale && in_array($userLocale, $supportedLocales)) {
                return $userLocale;
            }
        }

        // 3. Check if locale is in session
        if (session()->has('locale') && in_array(session('locale'), $supportedLocales)) {
            return session('locale');
        }

        // 4. Check if locale is in cookie
        if ($request->hasCookie('locale') && in_array($request->cookie('locale'), $supportedLocales)) {
            return $request->cookie('locale');
        }

        // 5. Check if locale is passed in request header (lower priority than user preference)
        if ($request->hasHeader('Accept-Language')) {
            $locale = $this->parseAcceptLanguageHeader($request->header('Accept-Language'), $supportedLocales);
            if ($locale) {
                return $locale;
            }
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

