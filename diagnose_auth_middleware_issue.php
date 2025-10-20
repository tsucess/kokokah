<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Route;

echo "🔍 DIAGNOSING AUTHENTICATION MIDDLEWARE ISSUE\n";
echo "=============================================\n\n";

// Load authentication tokens
$authTokens = [];
$tokenContent = file_get_contents('auth_tokens.txt');

if (preg_match('/STUDENT_TOKEN=(.+)/', $tokenContent, $studentMatch)) {
    $authTokens['student'] = trim($studentMatch[1]);
}

$studentToken = $authTokens['student'];
echo "🔐 Using student token: $studentToken\n\n";

// Test 1: Check if token is valid in database
echo "TEST 1: Verify token in database\n";
echo "================================\n";

$tokenParts = explode('|', $studentToken);
$tokenId = $tokenParts[0];
$tokenHash = $tokenParts[1];

$tokenRecord = \DB::table('personal_access_tokens')
    ->where('id', $tokenId)
    ->first();

if ($tokenRecord) {
    echo "✅ Token found in database\n";
    echo "Token ID: $tokenId\n";
    echo "User ID: {$tokenRecord->tokenable_id}\n";
    
    $user = User::find($tokenRecord->tokenable_id);
    if ($user) {
        echo "✅ User found: {$user->first_name} {$user->last_name} ({$user->role})\n";
    }
} else {
    echo "❌ Token NOT found in database\n";
}

echo "\n";

// Test 2: Test direct API calls with different endpoints
echo "TEST 2: Testing different endpoints with same token\n";
echo "===================================================\n\n";

function makeRequest($url, $method = 'GET', $token = null) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/api/$url",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return ['status' => $httpCode, 'body' => $response];
}

$testEndpoints = [
    ['url' => 'user', 'description' => 'Get current user (WORKS)'],
    ['url' => 'wallet', 'description' => 'Get wallet (FAILS)'],
    ['url' => 'payments/gateways', 'description' => 'Get payment gateways (FAILS)'],
    ['url' => 'courses/my-courses', 'description' => 'Get my courses (FAILS)'],
    ['url' => 'enrollments', 'description' => 'Get enrollments (FAILS)'],
    ['url' => 'users/profile', 'description' => 'Get user profile (FAILS)'],
    ['url' => 'dashboard/student', 'description' => 'Student dashboard (FAILS)'],
];

foreach ($testEndpoints as $endpoint) {
    $response = makeRequest($endpoint['url'], 'GET', $studentToken);
    $status = $response['status'];
    
    if ($status == 200) {
        echo "✅ {$endpoint['description']} - Status: $status\n";
    } else {
        echo "❌ {$endpoint['description']} - Status: $status\n";
        
        // Try to parse error message
        $body = json_decode($response['body'], true);
        if ($body && isset($body['message'])) {
            echo "   Error: {$body['message']}\n";
        }
    }
}

echo "\n";

// Test 3: Check Laravel routes
echo "TEST 3: Checking Laravel route configuration\n";
echo "============================================\n\n";

// Get all routes
$routes = Route::getRoutes();

echo "Total routes registered: " . count($routes) . "\n\n";

// Find wallet route
echo "Looking for wallet routes:\n";
foreach ($routes as $route) {
    if (strpos($route->uri, 'wallet') !== false) {
        echo "✅ Found: {$route->methods[0]} /api/{$route->uri}\n";
        echo "   Middleware: " . implode(', ', $route->middleware()) . "\n";
    }
}

echo "\nLooking for user route:\n";
foreach ($routes as $route) {
    if ($route->uri === 'user') {
        echo "✅ Found: {$route->methods[0]} /api/{$route->uri}\n";
        echo "   Middleware: " . implode(', ', $route->middleware()) . "\n";
    }
}

echo "\n";

// Test 4: Check if auth:sanctum middleware is working
echo "TEST 4: Testing auth:sanctum middleware directly\n";
echo "================================================\n\n";

// Create a test request with the token
$request = \Illuminate\Http\Request::create('/api/wallet', 'GET');
$request->headers->set('Authorization', 'Bearer ' . $studentToken);
$request->headers->set('Accept', 'application/json');

// Try to authenticate
$user = $request->user('sanctum');

if ($user) {
    echo "✅ Middleware authenticated user: {$user->first_name} {$user->last_name}\n";
} else {
    echo "❌ Middleware failed to authenticate user\n";
    echo "   Request user: " . ($request->user() ? 'Found' : 'Not found') . "\n";
}

echo "\n========================================\n";
echo "🎯 DIAGNOSIS COMPLETE\n";
echo "========================================\n";
