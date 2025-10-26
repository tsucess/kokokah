<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Kokokah LMS Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options specific to the Kokokah
    | Learning Management System.
    |
    */

    'name' => env('BUSINESS_NAME', 'Kokokah Learning Management System'),
    'version' => '1.0.0',
    'description' => 'Premier online learning platform for Nigerian students',

    /*
    |--------------------------------------------------------------------------
    | Business Information
    |--------------------------------------------------------------------------
    */
    'business' => [
        'name' => env('BUSINESS_NAME', 'Kokokah Learning Management System'),
        'address' => env('BUSINESS_ADDRESS', 'Lagos, Nigeria'),
        'phone' => env('BUSINESS_PHONE', '+234-XXX-XXX-XXXX'),
        'email' => env('BUSINESS_EMAIL', 'support@kokokah.com'),
        'website' => env('BUSINESS_WEBSITE', 'https://kokokah.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    */
    'payments' => [
        'default_currency' => env('APP_CURRENCY', 'NGN'),
        'currency_symbol' => env('APP_CURRENCY_SYMBOL', '₦'),
        
        'gateways' => [
            'paystack' => [
                'enabled' => !empty(env('PAYSTACK_SECRET_KEY')),
                'public_key' => env('PAYSTACK_PUBLIC_KEY'),
                'secret_key' => env('PAYSTACK_SECRET_KEY'),
                'webhook_secret' => env('PAYSTACK_WEBHOOK_SECRET'),
            ],
            'flutterwave' => [
                'enabled' => !empty(env('FLUTTERWAVE_SECRET_KEY')),
                'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
                'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
                'webhook_secret' => env('FLUTTERWAVE_WEBHOOK_SECRET'),
            ],
            'stripe' => [
                'enabled' => !empty(env('STRIPE_SECRET')),
                'key' => env('STRIPE_KEY'),
                'secret' => env('STRIPE_SECRET'),
                'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            ],
            'paypal' => [
                'enabled' => !empty(env('PAYPAL_CLIENT_SECRET')),
                'client_id' => env('PAYPAL_CLIENT_ID'),
                'client_secret' => env('PAYPAL_CLIENT_SECRET'),
                'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
                'mode' => env('PAYPAL_MODE', 'sandbox'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Services Configuration
    |--------------------------------------------------------------------------
    */
    'ai' => [
        'openai' => [
            'enabled' => !empty(env('OPENAI_API_KEY')),
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
        ],
        'gemini' => [
            'enabled' => !empty(env('GEMINI_API_KEY')),
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-pro'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Toggles
    |--------------------------------------------------------------------------
    */
    'features' => [
        'ai_chat' => env('FEATURE_AI_CHAT', true),
        'recommendations' => env('FEATURE_RECOMMENDATIONS', true),
        'analytics' => env('FEATURE_ANALYTICS', true),
        'notifications' => env('FEATURE_NOTIFICATIONS', true),
        'forums' => env('FEATURE_FORUMS', true),
        'certificates' => env('FEATURE_CERTIFICATES', true),
        'badges' => env('FEATURE_BADGES', true),
        'wallet' => env('FEATURE_WALLET', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | System Limits
    |--------------------------------------------------------------------------
    */
    'limits' => [
        'max_file_upload_size' => env('MAX_FILE_UPLOAD_SIZE', 10240), // KB
        'max_video_upload_size' => env('MAX_VIDEO_UPLOAD_SIZE', 102400), // KB
        'max_course_duration' => env('MAX_COURSE_DURATION', 500), // hours
        'max_students_per_course' => env('MAX_STUDENTS_PER_COURSE', 1000),
        'max_lessons_per_course' => env('MAX_LESSONS_PER_COURSE', 100),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limiting' => [
        'api_per_minute' => env('RATE_LIMIT_PER_MINUTE', 60),
        'api_per_hour' => env('RATE_LIMIT_PER_HOUR', 1000),
        'login_attempts' => 5,
        'login_decay_minutes' => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */
    'security' => [
        'headers_enabled' => env('SECURITY_HEADERS_ENABLED', true),
        'cors_allowed_origins' => env('CORS_ALLOWED_ORIGINS', '*'),
        'cors_allowed_methods' => env('CORS_ALLOWED_METHODS', 'GET,POST,PUT,DELETE,OPTIONS'),
        'cors_allowed_headers' => env('CORS_ALLOWED_HEADERS', '*'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Legal URLs
    |--------------------------------------------------------------------------
    */
    'legal' => [
        'terms_of_service' => env('TERMS_OF_SERVICE_URL', 'https://kokokah.com/terms'),
        'privacy_policy' => env('PRIVACY_POLICY_URL', 'https://kokokah.com/privacy'),
        'refund_policy' => env('REFUND_POLICY_URL', 'https://kokokah.com/refund'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode
    |--------------------------------------------------------------------------
    */
    'maintenance' => [
        'enabled' => env('MAINTENANCE_MODE', false),
        'message' => env('MAINTENANCE_MESSAGE', 'We are currently performing scheduled maintenance. Please check back soon.'),
        'allowed_ips' => explode(',', env('MAINTENANCE_ALLOWED_IPS', '127.0.0.1')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Nigerian Localization
    |--------------------------------------------------------------------------
    */
    'localization' => [
        'timezone' => env('APP_TIMEZONE', 'Africa/Lagos'),
        'locale' => env('APP_LOCALE', 'en'),
        'currency' => env('APP_CURRENCY', 'NGN'),
        'currency_symbol' => env('APP_CURRENCY_SYMBOL', '₦'),
        'phone_country_code' => '+234',
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Login Configuration
    |--------------------------------------------------------------------------
    */
    'social' => [
        'google' => [
            'enabled' => !empty(env('GOOGLE_CLIENT_SECRET')),
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
        ],
        'facebook' => [
            'enabled' => !empty(env('FACEBOOK_CLIENT_SECRET')),
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'redirect_uri' => env('FACEBOOK_REDIRECT_URI'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Analytics Configuration
    |--------------------------------------------------------------------------
    */
    'analytics' => [
        'google_analytics_id' => env('GOOGLE_ANALYTICS_ID'),
        'mixpanel_token' => env('MIXPANEL_TOKEN'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Configuration
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'sms' => [
            'provider' => env('SMS_PROVIDER', 'twilio'),
            'twilio' => [
                'sid' => env('TWILIO_SID'),
                'token' => env('TWILIO_TOKEN'),
                'from' => env('TWILIO_FROM'),
            ],
        ],
        'push' => [
            'fcm_server_key' => env('FCM_SERVER_KEY'),
            'fcm_sender_id' => env('FCM_SENDER_ID'),
        ],
    ],
];
