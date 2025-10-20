<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Kokokah.com LMS API', 'status' => 'active']);
});

// Login route for authentication redirects (API-only app)
Route::get('/login', function () {
    return response()->json([
        'success' => false,
        'message' => 'This is an API-only application. Please use the API endpoints for authentication.',
        'endpoints' => [
            'login' => 'POST /api/login',
            'register' => 'POST /api/register'
        ]
    ], 401);
})->name('login');
