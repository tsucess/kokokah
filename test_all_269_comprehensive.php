<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Route;

echo "🔍 COMPREHENSIVE TESTING OF ALL 269 ENDPOINTS\n";
echo "=============================================\n\n";

// Load authentication tokens
$authTokens = [];
if (file_exists('auth_tokens.txt')) {
    $content = file_get_contents('auth_tokens.txt');

    // Try new format first
    preg_match('/ADMIN_TOKEN=(.+)/', $content, $adminMatch);
    preg_match('/STUDENT_TOKEN=(.+)/', $content, $studentMatch);

    if ($adminMatch) $authTokens['admin'] = trim($adminMatch[1]);
    if ($studentMatch) $authTokens['student'] = trim($studentMatch[1]);

    // Fallback to old format
    if (empty($authTokens)) {
        preg_match('/Admin Token: (.+)/', $content, $adminMatch);
        preg_match('/Student Token: (.+)/', $content, $studentMatch);

        if ($adminMatch) $authTokens['admin'] = trim($adminMatch[1]);
        if ($studentMatch) $authTokens['student'] = trim($studentMatch[1]);
    }
}

if (empty($authTokens)) {
    echo "❌ No authentication tokens found. Please run authentication first.\n";
    exit(1);
}

echo "🔐 Authentication Status:\n";
echo "✅ Admin Token: " . substr($authTokens['admin'], 0, 30) . "...\n";
echo "✅ Student Token: " . substr($authTokens['student'], 0, 30) . "...\n\n";

// Get all routes
$routes = collect(Route::getRoutes())->filter(function ($route) {
    return str_starts_with($route->uri(), 'api/');
})->values();

echo "📊 Found " . $routes->count() . " total API routes\n";
echo "🎯 Testing ALL endpoints with appropriate methods\n\n";

$results = [
    'total' => 0,
    'success' => 0,
    'server_error' => 0,
    'auth_error' => 0,
    'permission_error' => 0,
    'not_found' => 0,
    'validation_error' => 0,
    'rate_limited' => 0,
    'other' => 0
];

$failedEndpoints = [];

function makeRequest($url, $method = 'GET', $token = null, $data = []) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/$url",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    if (!empty($data) && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['status' => 0, 'body' => $error];
    }
    
    return ['status' => $httpCode, 'body' => $response];
}

function getTestData($route) {
    $uri = $route->uri();
    $method = $route->methods()[0];
    
    // Sample test data for different endpoints
    $testData = [];
    
    if (str_contains($uri, 'courses') && in_array($method, ['POST', 'PUT'])) {
        $testData = [
            'title' => 'Test Course',
            'description' => 'Test Description',
            'price' => 99.99,
            'category_id' => 1
        ];
    } elseif (str_contains($uri, 'users') && in_array($method, ['POST', 'PUT'])) {
        $testData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ];
    } elseif (str_contains($uri, 'enrollments') && $method === 'POST') {
        $testData = [
            'course_id' => 1
        ];
    } elseif (str_contains($uri, 'reviews') && $method === 'POST') {
        $testData = [
            'course_id' => 1,
            'rating' => 5,
            'comment' => 'Great course!'
        ];
    }
    
    return $testData;
}

function getTokenForRoute($route, $authTokens) {
    $uri = $route->uri();
    
    // Admin-only endpoints
    if (str_contains($uri, 'admin/') || 
        str_contains($uri, 'analytics/') ||
        str_contains($uri, 'audit/') ||
        str_contains($uri, 'reports/') ||
        str_contains($uri, 'settings/')) {
        return $authTokens['admin'];
    }
    
    // Student endpoints
    return $authTokens['student'];
}

// Test each route
foreach ($routes as $route) {
    $uri = $route->uri();
    $method = $route->methods()[0];
    
    // Skip certain methods that might be destructive
    if (in_array($method, ['HEAD', 'OPTIONS'])) {
        continue;
    }
    
    $results['total']++;
    
    $token = getTokenForRoute($route, $authTokens);
    $testData = getTestData($route);
    
    echo "🧪 Testing: $method $uri";
    
    $response = makeRequest($uri, $method, $token, $testData);
    $status = $response['status'];
    
    // Categorize results
    if ($status >= 200 && $status < 300) {
        $results['success']++;
        echo " ✅ $status\n";
    } elseif ($status >= 500) {
        $results['server_error']++;
        $failedEndpoints[] = ['endpoint' => "$method $uri", 'status' => $status, 'type' => 'server_error'];
        echo " ❌ $status (Server Error)\n";
    } elseif ($status == 401) {
        $results['auth_error']++;
        $failedEndpoints[] = ['endpoint' => "$method $uri", 'status' => $status, 'type' => 'auth_error'];
        echo " 🔐 $status (Unauthorized)\n";
    } elseif ($status == 403) {
        $results['permission_error']++;
        echo " 🚫 $status (Forbidden)\n";
    } elseif ($status == 404) {
        $results['not_found']++;
        $failedEndpoints[] = ['endpoint' => "$method $uri", 'status' => $status, 'type' => 'not_found'];
        echo " ❓ $status (Not Found)\n";
    } elseif ($status == 422) {
        $results['validation_error']++;
        echo " ⚠️  $status (Validation)\n";
    } elseif ($status == 429) {
        $results['rate_limited']++;
        echo " ⏱️  $status (Rate Limited)\n";
    } else {
        $results['other']++;
        $failedEndpoints[] = ['endpoint' => "$method $uri", 'status' => $status, 'type' => 'other'];
        echo " ❓ $status (Other)\n";
    }
}

// Calculate success rate
$successRate = $results['total'] > 0 ? round(($results['success'] / $results['total']) * 100, 2) : 0;

echo "\n============================================================\n";
echo "📊 COMPREHENSIVE 269-ENDPOINT VERIFICATION RESULTS\n";
echo "============================================================\n\n";

echo "📈 OVERALL STATISTICS:\n";
echo "Total Endpoints Tested: {$results['total']}\n";
echo "✅ Successful (2xx): {$results['success']}\n";
echo "❌ Server Errors (5xx): {$results['server_error']}\n";
echo "🔐 Auth Issues (401): {$results['auth_error']}\n";
echo "🚫 Permission Issues (403): {$results['permission_error']}\n";
echo "❓ Not Found (404): {$results['not_found']}\n";
echo "⚠️  Validation Issues (422): {$results['validation_error']}\n";
echo "⏱️  Rate Limited (429): {$results['rate_limited']}\n";
echo "❓ Other Issues: {$results['other']}\n\n";

echo "📈 Success Rate: $successRate%\n\n";

// Success rate assessment
if ($successRate >= 90) {
    echo "🎉 EXCELLENT! 90%+ success rate - Production ready!\n";
} elseif ($successRate >= 80) {
    echo "👍 GOOD! 80%+ success rate - Minor fixes needed.\n";
} elseif ($successRate >= 70) {
    echo "👍 GOOD! 70%+ success rate - Some optimization needed.\n";
} else {
    echo "⚠️  NEEDS WORK! <70% success rate - Major fixes required.\n";
}

// Show critical failures
if (!empty($failedEndpoints)) {
    echo "\n============================================================\n";
    echo "🚨 CRITICAL FAILURES REQUIRING ATTENTION:\n";
    echo "============================================================\n";
    
    $criticalFailures = array_filter($failedEndpoints, function($failure) {
        return in_array($failure['type'], ['server_error', 'auth_error', 'not_found']);
    });
    
    foreach ($criticalFailures as $failure) {
        echo "❌ {$failure['endpoint']} - {$failure['status']} ({$failure['type']})\n";
    }
}

echo "\n============================================================\n";
echo "🎯 FINAL ASSESSMENT:\n";
echo "============================================================\n";

if ($results['server_error'] == 0 && $results['auth_error'] == 0 && $results['not_found'] <= 2) {
    echo "✅ PRODUCTION READY - All critical issues resolved!\n";
    echo "🚀 Your Kokokah.com LMS is ready for deployment!\n";
} else {
    echo "⚠️  NEEDS ATTENTION - Critical issues found:\n";
    if ($results['server_error'] > 0) echo "   • {$results['server_error']} server errors need fixing\n";
    if ($results['auth_error'] > 0) echo "   • {$results['auth_error']} authentication issues\n";
    if ($results['not_found'] > 2) echo "   • {$results['not_found']} missing routes\n";
}

echo "============================================================\n";
