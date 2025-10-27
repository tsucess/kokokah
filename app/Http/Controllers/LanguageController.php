<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Services\LocalizationService;

class LanguageController extends Controller
{
    protected $localizationService;

    public function __construct(LocalizationService $localizationService)
    {
        $this->localizationService = $localizationService;
    }

    /**
     * Get current application locale
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentLocale(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'locale' => App::getLocale(),
                'locale_name' => $this->localizationService->getLanguageName(App::getLocale()),
                'supported_locales' => $this->getSupportedLocalesData(),
            ]
        ]);
    }

    /**
     * Get all supported languages
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSupportedLanguages()
    {
        return response()->json([
            'success' => true,
            'data' => $this->getSupportedLocalesData()
        ]);
    }

    /**
     * Set application locale (for guests)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setLocale(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|string|in:' . implode(',', LocalizationService::SUPPORTED_LANGUAGES)
        ]);

        $locale = $validated['locale'];

        // Set locale in session
        session(['locale' => $locale]);

        // Set locale in cookie (expires in 1 year)
        $response = response()->json([
            'success' => true,
            'message' => 'Language changed successfully',
            'data' => [
                'locale' => $locale,
                'locale_name' => $this->localizationService->getLanguageName($locale),
            ]
        ]);

        return $response->cookie('locale', $locale, 60 * 24 * 365);
    }

    /**
     * Set user's preferred language (authenticated users)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUserLanguage(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|string|in:' . implode(',', LocalizationService::SUPPORTED_LANGUAGES)
        ]);

        $user = Auth::user();
        $locale = $validated['locale'];

        // Update user's language preference
        $this->localizationService->setUserLanguage($user->id, $locale);

        // Set locale in session
        session(['locale' => $locale]);

        return response()->json([
            'success' => true,
            'message' => 'Language preference updated successfully',
            'data' => [
                'locale' => $locale,
                'locale_name' => $this->localizationService->getLanguageName($locale),
            ]
        ]);
    }

    /**
     * Get user's language preference (authenticated users)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserLanguage()
    {
        $user = Auth::user();
        $locale = $user->language_preference ?? config('app.locale', 'en');

        return response()->json([
            'success' => true,
            'data' => [
                'locale' => $locale,
                'locale_name' => $this->localizationService->getLanguageName($locale),
            ]
        ]);
    }

    /**
     * Get translated messages for current locale
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTranslations()
    {
        $locale = App::getLocale();
        $messages = trans('messages');

        return response()->json([
            'success' => true,
            'data' => [
                'locale' => $locale,
                'messages' => $messages
            ]
        ]);
    }

    /**
     * Get translated messages for specific locale
     *
     * @param  string  $locale
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTranslationsByLocale($locale)
    {
        if (!in_array($locale, LocalizationService::SUPPORTED_LANGUAGES)) {
            return response()->json([
                'success' => false,
                'message' => 'Unsupported locale'
            ], 400);
        }

        $messages = trans('messages', [], $locale);

        return response()->json([
            'success' => true,
            'data' => [
                'locale' => $locale,
                'messages' => $messages
            ]
        ]);
    }

    /**
     * Get language info
     *
     * @param  string  $locale
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLanguageInfo($locale)
    {
        if (!in_array($locale, LocalizationService::SUPPORTED_LANGUAGES)) {
            return response()->json([
                'success' => false,
                'message' => 'Unsupported locale'
            ], 400);
        }

        $languageInfo = [
            'en' => [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'direction' => 'ltr',
                'country' => 'United States'
            ],
            'fr' => [
                'code' => 'fr',
                'name' => 'French',
                'native_name' => 'Français',
                'direction' => 'ltr',
                'country' => 'France'
            ],
            'ar' => [
                'code' => 'ar',
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'direction' => 'rtl',
                'country' => 'Saudi Arabia'
            ],
            'yo' => [
                'code' => 'yo',
                'name' => 'Yoruba',
                'native_name' => 'Yorùbá',
                'direction' => 'ltr',
                'country' => 'Nigeria'
            ],
            'ha' => [
                'code' => 'ha',
                'name' => 'Hausa',
                'native_name' => 'Hausa',
                'direction' => 'ltr',
                'country' => 'Nigeria'
            ],
            'ig' => [
                'code' => 'ig',
                'name' => 'Igbo',
                'native_name' => 'Igbo',
                'direction' => 'ltr',
                'country' => 'Nigeria'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $languageInfo[$locale]
        ]);
    }

    /**
     * Get all language info
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllLanguageInfo()
    {
        $languageInfo = [
            'en' => [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'direction' => 'ltr',
                'country' => 'United States'
            ],
            'fr' => [
                'code' => 'fr',
                'name' => 'French',
                'native_name' => 'Français',
                'direction' => 'ltr',
                'country' => 'France'
            ],
            'ar' => [
                'code' => 'ar',
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'direction' => 'rtl',
                'country' => 'Saudi Arabia'
            ],
            'yo' => [
                'code' => 'yo',
                'name' => 'Yoruba',
                'native_name' => 'Yorùbá',
                'direction' => 'ltr',
                'country' => 'Nigeria'
            ],
            'ha' => [
                'code' => 'ha',
                'name' => 'Hausa',
                'native_name' => 'Hausa',
                'direction' => 'ltr',
                'country' => 'Nigeria'
            ],
            'ig' => [
                'code' => 'ig',
                'name' => 'Igbo',
                'native_name' => 'Igbo',
                'direction' => 'ltr',
                'country' => 'Nigeria'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => array_values($languageInfo)
        ]);
    }

    /**
     * Helper method to get supported locales data
     *
     * @return array
     */
    private function getSupportedLocalesData(): array
    {
        $locales = [];
        foreach (LocalizationService::SUPPORTED_LANGUAGES as $locale) {
            $locales[] = [
                'code' => $locale,
                'name' => $this->localizationService->getLanguageName($locale)
            ];
        }
        return $locales;
    }
}

