<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class LocalizationService
{
    // Supported languages
    const SUPPORTED_LANGUAGES = ['en', 'fr', 'ar', 'yo', 'ha', 'ig'];

    // Supported currencies
    const SUPPORTED_CURRENCIES = ['NGN', 'USD', 'EUR', 'GBP', 'GHS', 'KES', 'ZAR'];

    // Currency symbols
    const CURRENCY_SYMBOLS = [
        'NGN' => '₦',
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'GHS' => 'GH₵',
        'KES' => 'KSh',
        'ZAR' => 'R'
    ];

    // Supported timezones
    const SUPPORTED_TIMEZONES = [
        'Africa/Lagos',
        'Africa/Johannesburg',
        'Africa/Cairo',
        'Africa/Nairobi',
        'UTC',
        'Europe/London',
        'Europe/Paris',
        'America/New_York'
    ];

    /**
     * Get user's preferred language
     */
    public function getUserLanguage($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return 'en';
        }

        return $user->language_preference ?? 'en';
    }

    /**
     * Set user's preferred language
     */
    public function setUserLanguage($userId, $languageCode)
    {
        if (!in_array($languageCode, self::SUPPORTED_LANGUAGES)) {
            return false;
        }

        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        $user->update(['language_preference' => $languageCode]);
        Cache::forget("user_language_{$userId}");

        return true;
    }

    /**
     * Get user's preferred currency
     */
    public function getUserCurrency($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return 'NGN';
        }

        return $user->currency_preference ?? 'NGN';
    }

    /**
     * Set user's preferred currency
     */
    public function setUserCurrency($userId, $currencyCode)
    {
        if (!in_array($currencyCode, self::SUPPORTED_CURRENCIES)) {
            return false;
        }

        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        $user->update(['currency_preference' => $currencyCode]);
        Cache::forget("user_currency_{$userId}");

        return true;
    }

    /**
     * Get user's preferred timezone
     */
    public function getUserTimezone($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return 'Africa/Lagos';
        }

        return $user->timezone_preference ?? 'Africa/Lagos';
    }

    /**
     * Set user's preferred timezone
     */
    public function setUserTimezone($userId, $timezone)
    {
        if (!in_array($timezone, self::SUPPORTED_TIMEZONES)) {
            return false;
        }

        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        $user->update(['timezone_preference' => $timezone]);
        Cache::forget("user_timezone_{$userId}");

        return true;
    }

    /**
     * Format currency
     */
    public function formatCurrency($amount, $currencyCode = 'NGN')
    {
        $symbol = self::CURRENCY_SYMBOLS[$currencyCode] ?? $currencyCode;
        return $symbol . number_format($amount, 2);
    }

    /**
     * Convert currency
     */
    public function convertCurrency($amount, $fromCurrency, $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        // Get exchange rates from cache or API
        $rates = Cache::remember('exchange_rates', 3600, function () {
            return $this->fetchExchangeRates();
        });

        if (!isset($rates[$fromCurrency]) || !isset($rates[$toCurrency])) {
            return $amount;
        }

        $baseAmount = $amount / $rates[$fromCurrency];
        return $baseAmount * $rates[$toCurrency];
    }

    /**
     * Fetch exchange rates
     */
    private function fetchExchangeRates()
    {
        // Default rates (in production, fetch from API like OpenExchangeRates)
        return [
            'NGN' => 1,
            'USD' => 0.0013,
            'EUR' => 0.0012,
            'GBP' => 0.0010,
            'GHS' => 0.0085,
            'KES' => 0.16,
            'ZAR' => 0.025
        ];
    }

    /**
     * Get all supported languages
     */
    public function getSupportedLanguages()
    {
        return self::SUPPORTED_LANGUAGES;
    }

    /**
     * Get all supported currencies
     */
    public function getSupportedCurrencies()
    {
        return self::SUPPORTED_CURRENCIES;
    }

    /**
     * Get all supported timezones
     */
    public function getSupportedTimezones()
    {
        return self::SUPPORTED_TIMEZONES;
    }

    /**
     * Get language name
     */
    public function getLanguageName($languageCode)
    {
        $names = [
            'en' => 'English',
            'fr' => 'Français',
            'ar' => 'العربية',
            'yo' => 'Yorùbá',
            'ha' => 'Hausa',
            'ig' => 'Igbo'
        ];

        return $names[$languageCode] ?? $languageCode;
    }

    /**
     * Get currency name
     */
    public function getCurrencyName($currencyCode)
    {
        $names = [
            'NGN' => 'Nigerian Naira',
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'GBP' => 'British Pound',
            'GHS' => 'Ghanaian Cedi',
            'KES' => 'Kenyan Shilling',
            'ZAR' => 'South African Rand'
        ];

        return $names[$currencyCode] ?? $currencyCode;
    }

    /**
     * Check if language is RTL
     */
    public function isRTL($languageCode)
    {
        return in_array($languageCode, ['ar']);
    }
}

