<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

echo "🔍 DEBUGGING AUTHENTICATION ISSUES\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);

$adminToken = trim($adminMatches[1]);
$studentToken = trim($studentMatches[1]);

echo "Current tokens:\n";
echo "Admin Token: $adminToken\n";
echo "Student Token: $studentToken\n\n";

// Check tokens in database
echo "🔍 Checking tokens in database:\n\n";

// Parse token IDs
$adminTokenId = explode('|', $adminToken)[0];
$studentTokenId = explode('|', $studentToken)[0];

echo "Admin Token ID: $adminTokenId\n";
echo "Student Token ID: $studentTokenId\n\n";

// Check if tokens exist in database
$adminTokenRecord = PersonalAccessToken::find($adminTokenId);
$studentTokenRecord = PersonalAccessToken::find($studentTokenId);

if ($adminTokenRecord) {
    echo "✅ Admin token found in database:\n";
    echo "  - Token ID: {$adminTokenRecord->id}\n";
    echo "  - User ID: {$adminTokenRecord->tokenable_id}\n";
    echo "  - Name: {$adminTokenRecord->name}\n";
    echo "  - Created: {$adminTokenRecord->created_at}\n";
    echo "  - Last Used: {$adminTokenRecord->last_used_at}\n\n";
} else {
    echo "❌ Admin token NOT found in database\n\n";
}

if ($studentTokenRecord) {
    echo "✅ Student token found in database:\n";
    echo "  - Token ID: {$studentTokenRecord->id}\n";
    echo "  - User ID: {$studentTokenRecord->tokenable_id}\n";
    echo "  - Name: {$studentTokenRecord->name}\n";
    echo "  - Created: {$studentTokenRecord->created_at}\n";
    echo "  - Last Used: {$studentTokenRecord->last_used_at}\n\n";
} else {
    echo "❌ Student token NOT found in database\n\n";
}

// Test direct API call with detailed debugging
echo "🧪 Testing API calls with detailed debugging:\n\n";

function debugApiCall($name, $token) {
    echo "Testing $name:\n";
    echo "Token: $token\n";
    
    $url = 'http://localhost:8000/api/user';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "HTTP Code: $httpCode\n";
    if ($error) {
        echo "cURL Error: $error\n";
    }
    echo "Response: " . substr($response, 0, 200) . "...\n";
    echo "---\n\n";
    
    return $httpCode === 200;
}

debugApiCall("Admin Token", $adminToken);
debugApiCall("Student Token", $studentToken);

// Check if Laravel Sanctum is properly configured
echo "🔧 Checking Laravel Sanctum configuration:\n\n";

// Check if sanctum middleware is working
$sanctumMiddleware = app('router')->getMiddleware();
if (isset($sanctumMiddleware['auth:sanctum'])) {
    echo "✅ Sanctum middleware is registered\n";
} else {
    echo "❌ Sanctum middleware is NOT registered\n";
}

// Check if API routes have auth middleware
echo "\n🔍 Checking route middleware for /api/user:\n";
$routes = app('router')->getRoutes();
foreach ($routes as $route) {
    if ($route->uri() === 'api/user' && in_array('GET', $route->methods())) {
        $middleware = $route->middleware();
        echo "Route: {$route->uri()}\n";
        echo "Middleware: " . implode(', ', $middleware) . "\n";
        break;
    }
}

echo "\n============================================================\n";
