<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => explode(',', env('CORS_ALLOWED_METHODS', 'GET,POST,PUT,DELETE,OPTIONS')),

    'allowed_origins' => env('CORS_ALLOWED_ORIGINS') === '*' 
        ? ['*'] 
        : explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000,http://localhost:8080,https://kokokah.com')),

    'allowed_origins_patterns' => [
        // Allow subdomains in production
        '/^https:\/\/.*\.kokokah\.com$/',
    ],

    'allowed_headers' => env('CORS_ALLOWED_HEADERS') === '*' 
        ? ['*'] 
        : explode(',', env('CORS_ALLOWED_HEADERS', 'Content-Type,Authorization,X-Requested-With,Accept,Origin')),

    'exposed_headers' => [
        'X-RateLimit-Limit',
        'X-RateLimit-Remaining',
        'X-Total-Count',
        'X-Page-Count',
    ],

    'max_age' => 86400, // 24 hours

    'supports_credentials' => true,

];
