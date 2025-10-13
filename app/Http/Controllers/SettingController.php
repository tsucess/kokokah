<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:admin')->except(['getPublicSettings']);
    }

    /**
     * Get all system settings
     */
    public function index(Request $request)
    {
        try {
            $category = $request->get('category');
            $search = $request->get('search');

            $query = Setting::query();

            if ($category) {
                $query->where('category', $category);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('key', 'like', "%{$search}%")
                      ->orWhere('display_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $settings = $query->orderBy('category')
                            ->orderBy('order')
                            ->orderBy('key')
                            ->get()
                            ->groupBy('category');

            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific setting
     */
    public function show($key)
    {
        try {
            $setting = Setting::where('key', $key)->first();

            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $setting
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch setting: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update specific setting
     */
    public function update(Request $request, $key)
    {
        try {
            $setting = Setting::where('key', $key)->first();

            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'value' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate value based on setting type
            $validationResult = $this->validateSettingValue($setting, $request->value);
            if (!$validationResult['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validationResult['message']
                ], 400);
            }

            $setting->update([
                'value' => $request->value,
                'updated_by' => Auth::id()
            ]);

            // Clear cache for this setting
            Cache::forget("setting.{$key}");
            Cache::forget('settings.all');

            // Log setting change
            $this->logSettingChange($setting, $request->value);

            return response()->json([
                'success' => true,
                'message' => 'Setting updated successfully',
                'data' => $setting
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update setting: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update multiple settings at once
     */
    public function updateBulk(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'settings' => 'required|array',
                'settings.*.key' => 'required|string',
                'settings.*.value' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $results = [
                'updated' => 0,
                'errors' => []
            ];

            foreach ($request->settings as $settingData) {
                try {
                    $setting = Setting::where('key', $settingData['key'])->first();

                    if (!$setting) {
                        $results['errors'][] = [
                            'key' => $settingData['key'],
                            'error' => 'Setting not found'
                        ];
                        continue;
                    }

                    // Validate value
                    $validationResult = $this->validateSettingValue($setting, $settingData['value']);
                    if (!$validationResult['valid']) {
                        $results['errors'][] = [
                            'key' => $settingData['key'],
                            'error' => $validationResult['message']
                        ];
                        continue;
                    }

                    $setting->update([
                        'value' => $settingData['value'],
                        'updated_by' => Auth::id()
                    ]);

                    // Clear cache
                    Cache::forget("setting.{$settingData['key']}");

                    $results['updated']++;
                } catch (\Exception $e) {
                    $results['errors'][] = [
                        'key' => $settingData['key'],
                        'error' => $e->getMessage()
                    ];
                }
            }

            // Clear all settings cache
            Cache::forget('settings.all');

            return response()->json([
                'success' => true,
                'message' => "Bulk update completed. {$results['updated']} settings updated.",
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset settings to default values
     */
    public function reset(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'nullable|string',
                'keys' => 'nullable|array',
                'keys.*' => 'string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = Setting::query();

            if ($request->category) {
                $query->where('category', $request->category);
            }

            if ($request->keys) {
                $query->whereIn('key', $request->keys);
            }

            $settings = $query->get();

            $resetCount = 0;
            foreach ($settings as $setting) {
                if ($setting->default_value !== null) {
                    $setting->update([
                        'value' => $setting->default_value,
                        'updated_by' => Auth::id()
                    ]);
                    Cache::forget("setting.{$setting->key}");
                    $resetCount++;
                }
            }

            Cache::forget('settings.all');

            return response()->json([
                'success' => true,
                'message' => "{$resetCount} settings reset to default values"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get email settings
     */
    public function getEmailSettings()
    {
        try {
            $emailSettings = Setting::where('category', 'email')->get();

            return response()->json([
                'success' => true,
                'data' => $emailSettings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch email settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment settings
     */
    public function getPaymentSettings()
    {
        try {
            $paymentSettings = Setting::where('category', 'payment')->get();

            return response()->json([
                'success' => true,
                'data' => $paymentSettings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch payment settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get feature toggles
     */
    public function getFeatureToggles()
    {
        try {
            $features = Setting::where('category', 'features')->get();

            return response()->json([
                'success' => true,
                'data' => $features
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch feature toggles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get public settings (no authentication required)
     */
    public function getPublicSettings()
    {
        try {
            $publicSettings = Setting::where('is_public', true)->get();

            return response()->json([
                'success' => true,
                'data' => $publicSettings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch public settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods
     */
    private function validateSettingValue($setting, $value)
    {
        switch ($setting->type) {
            case 'boolean':
                if (!is_bool($value) && !in_array($value, ['true', 'false', '1', '0', 1, 0])) {
                    return ['valid' => false, 'message' => 'Value must be a boolean'];
                }
                break;

            case 'integer':
                if (!is_numeric($value) || !is_int((int)$value)) {
                    return ['valid' => false, 'message' => 'Value must be an integer'];
                }
                break;

            case 'decimal':
                if (!is_numeric($value)) {
                    return ['valid' => false, 'message' => 'Value must be a number'];
                }
                break;

            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return ['valid' => false, 'message' => 'Value must be a valid email address'];
                }
                break;

            case 'url':
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    return ['valid' => false, 'message' => 'Value must be a valid URL'];
                }
                break;

            case 'json':
                json_decode($value);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return ['valid' => false, 'message' => 'Value must be valid JSON'];
                }
                break;

            case 'select':
                if ($setting->options && !in_array($value, json_decode($setting->options, true))) {
                    return ['valid' => false, 'message' => 'Value must be one of the allowed options'];
                }
                break;
        }

        // Check min/max constraints
        if ($setting->min_value !== null && is_numeric($value) && $value < $setting->min_value) {
            return ['valid' => false, 'message' => "Value must be at least {$setting->min_value}"];
        }

        if ($setting->max_value !== null && is_numeric($value) && $value > $setting->max_value) {
            return ['valid' => false, 'message' => "Value must not exceed {$setting->max_value}"];
        }

        return ['valid' => true];
    }

    private function logSettingChange($setting, $newValue)
    {
        // Mock implementation - would log to audit table
        \Log::info('Setting changed', [
            'key' => $setting->key,
            'old_value' => $setting->value,
            'new_value' => $newValue,
            'user_id' => Auth::id(),
            'timestamp' => now()
        ]);
    }
}
