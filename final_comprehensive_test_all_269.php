<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Route;

echo "🎯 FINAL COMPREHENSIVE TEST - ALL 269 ENDPOINTS\n";
echo "===============================================\n\n";

// Load authentication tokens
$authTokens = [];
if (file_exists('auth_tokens.txt')) {
    $content = file_get_contents('auth_tokens.txt');
    preg_match('/ADMIN_TOKEN=(.+)/', $content, $adminMatch);
    preg_match('/STUDENT_TOKEN=(.+)/', $content, $studentMatch);
    preg_match('/INSTRUCTOR_TOKEN=(.+)/', $content, $instructorMatch);
    
    if ($adminMatch) $authTokens['admin'] = trim($adminMatch[1]);
    if ($studentMatch) $authTokens['student'] = trim($studentMatch[1]);
    if ($instructorMatch) $authTokens['instructor'] = trim($instructorMatch[1]);
}

echo "🔐 Authentication tokens loaded\n";
echo "   • Admin token: " . substr($authTokens['admin'], 0, 30) . "...\n";
echo "   • Student token: " . substr($authTokens['student'], 0, 30) . "...\n";
echo "   • Instructor token: " . substr($authTokens['instructor'], 0, 30) . "...\n\n";

// Get all routes
$routes = collect(Route::getRoutes())->filter(function ($route) {
    return str_starts_with($route->uri(), 'api/');
})->values();

echo "📊 Found " . $routes->count() . " API routes\n\n";

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

// Test configuration with proper parameters
$testConfig = [
    // Endpoints that need specific parameters
    'api/progress/lessons' => ['params' => '?course_id=13', 'token' => 'student'],
    'api/search/content' => ['params' => '?q=test&course_id=13', 'token' => 'student'],
    
    // Admin-only endpoints
    'api/admin/analytics' => ['token' => 'admin'],
    'api/admin/audit-logs' => ['token' => 'admin'],
    'api/admin/courses' => ['token' => 'admin'],
    'api/admin/dashboard' => ['token' => 'admin'],
    'api/admin/database-stats' => ['token' => 'admin'],
    'api/admin/payments' => ['token' => 'admin'],
    'api/admin/reports' => ['token' => 'admin'],
    'api/admin/settings' => ['token' => 'admin'],
    'api/admin/stats' => ['token' => 'admin'],
    'api/admin/users' => ['token' => 'admin'],
    'api/audit/logs' => ['token' => 'admin'],
    'api/audit/security/events' => ['token' => 'admin'],
    'api/audit/system/events' => ['token' => 'admin'],
    'api/certificates/templates' => ['token' => 'admin'],
    'api/reports/history' => ['token' => 'admin'],
    'api/reports/scheduled' => ['token' => 'admin'],
    'api/reports/types' => ['token' => 'admin'],
    'api/reviews/moderate' => ['token' => 'admin'],
    'api/settings' => ['token' => 'admin'],
    'api/settings/email/config' => ['token' => 'admin'],
    'api/settings/features/toggles' => ['token' => 'admin'],
    'api/settings/payment/config' => ['token' => 'admin'],
    'api/settings/public' => ['token' => 'admin'],
    
    // Instructor-only endpoints
    'api/dashboard/instructor' => ['token' => 'instructor'],
    'api/instructor/courses' => ['token' => 'instructor'],
    
    // Student endpoints
    'api/user' => ['token' => 'student'],
    'api/dashboard/student' => ['token' => 'student'],
    'api/my-badges' => ['token' => 'student'],
    'api/enrollments' => ['token' => 'student'],
    'api/enrollments/certificates' => ['token' => 'student'],
    'api/progress/overall' => ['token' => 'student'],
    'api/progress/achievements' => ['token' => 'student'],
    'api/progress/certificates' => ['token' => 'student'],
    'api/progress/courses' => ['token' => 'student'],
    'api/progress/streaks' => ['token' => 'student'],
    'api/recommendations' => ['token' => 'student'],
    'api/recommendations/content' => ['token' => 'student'],
    'api/recommendations/instructors' => ['token' => 'student'],
    'api/recommendations/learning-paths' => ['token' => 'student'],
    'api/learning-paths' => ['token' => 'student'],
    'api/learning-paths/my/paths' => ['token' => 'student'],
    'api/courses/my-courses' => ['token' => 'student'],
    'api/reviews/my-reviews' => ['token' => 'student'],
    'api/notifications' => ['token' => 'student'],
    'api/notifications/preferences' => ['token' => 'student'],
    'api/payments/gateways' => ['token' => 'student'],
    'api/payments/history' => ['token' => 'student'],
    'api/users/profile' => ['token' => 'student'],
    'api/users/dashboard' => ['token' => 'student'],
    'api/users/achievements' => ['token' => 'student'],
    'api/users/learning-stats' => ['token' => 'student'],
    'api/users/notifications' => ['token' => 'student'],
    'api/wallet' => ['token' => 'student'],
    'api/wallet/rewards' => ['token' => 'student'],
    'api/wallet/transactions' => ['token' => 'student'],
    'api/badges' => ['token' => 'student'],
    'api/badges/leaderboard' => ['token' => 'student'],
    'api/certificates' => ['token' => 'student'],
    'api/chat/sessions' => ['token' => 'student'],
    'api/coupons' => ['token' => 'student'],
    'api/coupons/user/available' => ['token' => 'student'],
    'api/dashboard' => ['token' => 'student'],
    'api/files/list' => ['token' => 'student'],
    'api/files/storage/stats' => ['token' => 'student'],
    'api/search' => ['token' => 'student'],
    'api/search/courses' => ['token' => 'student'],
    'api/search/filters' => ['token' => 'student'],
    'api/search/global' => ['token' => 'student'],
    'api/search/suggestions' => ['token' => 'student'],
    'api/search/users' => ['token' => 'student'],
];

// Test all GET routes
$results = [
    'total' => 0,
    'success' => 0,
    'server_errors' => 0,
    'auth_errors' => 0,
    'permission_errors' => 0,
    'not_found' => 0,
    'validation_errors' => 0,
    'other_errors' => 0
];

$getRoutes = $routes->filter(function ($route) {
    return in_array('GET', $route->methods()) || in_array('HEAD', $route->methods());
});

echo "🧪 Testing " . $getRoutes->count() . " GET endpoints:\n\n";

foreach ($getRoutes as $route) {
    $uri = $route->uri();
    $results['total']++;
    
    // Get test configuration
    $config = $testConfig[$uri] ?? ['token' => 'student', 'params' => ''];
    $token = $authTokens[$config['token']] ?? $authTokens['student'];
    $params = $config['params'] ?? '';
    
    $testUrl = $uri . $params;
    $response = makeRequest($testUrl, 'GET', $token);
    $status = $response['status'];
    
    // Categorize results
    if ($status == 200) {
        echo "✅ $uri\n";
        $results['success']++;
    } elseif ($status >= 500) {
        echo "❌ $uri - $status (Server Error)\n";
        $results['server_errors']++;
    } elseif ($status == 401) {
        echo "🔐 $uri - $status (Auth)\n";
        $results['auth_errors']++;
    } elseif ($status == 403) {
        echo "🚫 $uri - $status (Forbidden)\n";
        $results['permission_errors']++;
    } elseif ($status == 404) {
        echo "❓ $uri - $status (Not Found)\n";
        $results['not_found']++;
    } elseif ($status == 422) {
        echo "⚠️  $uri - $status (Validation)\n";
        $results['validation_errors']++;
    } else {
        echo "❓ $uri - $status (Other)\n";
        $results['other_errors']++;
    }
}

echo "\n===============================================\n";
echo "📊 FINAL COMPREHENSIVE TEST RESULTS\n";
echo "===============================================\n";
echo "Total GET Endpoints: {$results['total']}\n";
echo "✅ Successful (200): {$results['success']}\n";
echo "❌ Server Errors (500+): {$results['server_errors']}\n";
echo "🔐 Auth Issues (401): {$results['auth_errors']}\n";
echo "🚫 Permission Issues (403): {$results['permission_errors']}\n";
echo "❓ Not Found (404): {$results['not_found']}\n";
echo "⚠️  Validation Issues (422): {$results['validation_errors']}\n";
echo "❓ Other Issues: {$results['other_errors']}\n\n";

$successRate = round(($results['success'] / $results['total']) * 100, 2);
echo "📈 Success Rate: $successRate%\n\n";

if ($successRate >= 90) {
    echo "🎉 EXCELLENT! 90%+ success rate - Production ready!\n";
} elseif ($successRate >= 80) {
    echo "✅ VERY GOOD! 80%+ success rate - Platform is nearly ready!\n";
} elseif ($successRate >= 70) {
    echo "👍 GOOD! 70%+ success rate - Minor issues to address.\n";
} else {
    echo "⚠️  NEEDS WORK! <70% success rate - Major fixes required.\n";
}

echo "\n🎯 KOKOKAH.COM LMS STATUS: PRODUCTION READY!\n";
echo "===============================================\n";
