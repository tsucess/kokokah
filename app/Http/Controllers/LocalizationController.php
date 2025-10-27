<?php

namespace App\Http\Controllers;

use App\Services\LocalizationService;
use App\Models\ContentTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocalizationController extends Controller
{
    protected $localizationService;

    public function __construct(LocalizationService $localizationService)
    {
        $this->localizationService = $localizationService;
    }

    /**
     * Get user localization preferences
     */
    public function getPreferences()
    {
        try {
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'data' => [
                    'language' => $this->localizationService->getUserLanguage($user->id),
                    'currency' => $this->localizationService->getUserCurrency($user->id),
                    'timezone' => $this->localizationService->getUserTimezone($user->id)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch preferences: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user localization preferences
     */
    public function updatePreferences(Request $request)
    {
        try {
            $validated = $request->validate([
                'language' => 'nullable|string|in:' . implode(',', LocalizationService::SUPPORTED_LANGUAGES),
                'currency' => 'nullable|string|in:' . implode(',', LocalizationService::SUPPORTED_CURRENCIES),
                'timezone' => 'nullable|string|in:' . implode(',', LocalizationService::SUPPORTED_TIMEZONES)
            ]);

            $user = Auth::user();

            if (isset($validated['language'])) {
                $this->localizationService->setUserLanguage($user->id, $validated['language']);
            }

            if (isset($validated['currency'])) {
                $this->localizationService->setUserCurrency($user->id, $validated['currency']);
            }

            if (isset($validated['timezone'])) {
                $this->localizationService->setUserTimezone($user->id, $validated['timezone']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Preferences updated successfully',
                'data' => [
                    'language' => $this->localizationService->getUserLanguage($user->id),
                    'currency' => $this->localizationService->getUserCurrency($user->id),
                    'timezone' => $this->localizationService->getUserTimezone($user->id)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update preferences: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get supported languages
     */
    public function getSupportedLanguages()
    {
        try {
            $languages = [];
            foreach ($this->localizationService->getSupportedLanguages() as $code) {
                $languages[] = [
                    'code' => $code,
                    'name' => $this->localizationService->getLanguageName($code),
                    'is_rtl' => $this->localizationService->isRTL($code)
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $languages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch languages: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get supported currencies
     */
    public function getSupportedCurrencies()
    {
        try {
            $currencies = [];
            foreach ($this->localizationService->getSupportedCurrencies() as $code) {
                $currencies[] = [
                    'code' => $code,
                    'name' => $this->localizationService->getCurrencyName($code),
                    'symbol' => LocalizationService::CURRENCY_SYMBOLS[$code] ?? $code
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $currencies
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch currencies: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get supported timezones
     */
    public function getSupportedTimezones()
    {
        try {
            $timezones = $this->localizationService->getSupportedTimezones();

            return response()->json([
                'success' => true,
                'data' => $timezones
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch timezones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Convert currency
     */
    public function convertCurrency(Request $request)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:0',
                'from_currency' => 'required|string|in:' . implode(',', LocalizationService::SUPPORTED_CURRENCIES),
                'to_currency' => 'required|string|in:' . implode(',', LocalizationService::SUPPORTED_CURRENCIES)
            ]);

            $convertedAmount = $this->localizationService->convertCurrency(
                $validated['amount'],
                $validated['from_currency'],
                $validated['to_currency']
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'original_amount' => $validated['amount'],
                    'original_currency' => $validated['from_currency'],
                    'converted_amount' => round($convertedAmount, 2),
                    'converted_currency' => $validated['to_currency'],
                    'formatted' => $this->localizationService->formatCurrency($convertedAmount, $validated['to_currency'])
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to convert currency: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Translate content
     */
    public function translateContent(Request $request)
    {
        try {
            $validated = $request->validate([
                'model_type' => 'required|string',
                'model_id' => 'required|integer',
                'language' => 'required|string|in:' . implode(',', LocalizationService::SUPPORTED_LANGUAGES),
                'translations' => 'required|array'
            ]);

            // Get the model class
            $modelClass = 'App\\Models\\' . $validated['model_type'];
            if (!class_exists($modelClass)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid model type'
                ], 400);
            }

            $model = $modelClass::find($validated['model_id']);
            if (!$model) {
                return response()->json([
                    'success' => false,
                    'message' => 'Model not found'
                ], 404);
            }

            ContentTranslation::setTranslations($model, $validated['language'], $validated['translations']);

            return response()->json([
                'success' => true,
                'message' => 'Content translated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to translate content: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get translations for content
     */
    public function getTranslations(Request $request)
    {
        try {
            $validated = $request->validate([
                'model_type' => 'required|string',
                'model_id' => 'required|integer',
                'language' => 'required|string|in:' . implode(',', LocalizationService::SUPPORTED_LANGUAGES)
            ]);

            $modelClass = 'App\\Models\\' . $validated['model_type'];
            if (!class_exists($modelClass)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid model type'
                ], 400);
            }

            $model = $modelClass::find($validated['model_id']);
            if (!$model) {
                return response()->json([
                    'success' => false,
                    'message' => 'Model not found'
                ], 404);
            }

            $translations = ContentTranslation::getTranslations($model, $validated['language']);

            return response()->json([
                'success' => true,
                'data' => $translations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch translations: ' . $e->getMessage()
            ], 500);
        }
    }
}

