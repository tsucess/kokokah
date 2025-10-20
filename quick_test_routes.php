<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Route;

echo "🔍 CHECKING ROUTE CONFIGURATION\n";
echo "================================\n\n";

// Get all routes
$routes = Route::getRoutes();

echo "Total routes: " . count($routes) . "\n\n";

// Check for wallet routes
echo "Wallet routes:\n";
$walletCount = 0;
foreach ($routes as $route) {
    if (strpos($route->uri, 'wallet') !== false) {
        echo "• {$route->methods[0]} {$route->uri}\n";
        $walletCount++;
    }
}
echo "Total wallet routes: $walletCount\n\n";

// Check for user route
echo "User route:\n";
foreach ($routes as $route) {
    if ($route->uri === 'user' || $route->uri === 'api/user') {
        echo "• {$route->methods[0]} {$route->uri}\n";
    }
}

echo "\n";

// Load token
$tokenContent = file_get_contents('auth_tokens.txt');
if (preg_match('/STUDENT_TOKEN=(.+)/', $tokenContent, $match)) {
    $token = trim($match[1]);
    echo "🔐 Testing with student token: " . substr($token, 0, 20) . "...\n\n";
    
    // Test wallet endpoint
    echo "Testing wallet endpoint:\n";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/api/wallet",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Status: $httpCode\n";
    echo "Response: " . substr($response, 0, 200) . "...\n";
}

echo "\n========================================\n";
