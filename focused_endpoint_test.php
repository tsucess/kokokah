<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 FOCUSED ENDPOINT TEST WITH FRESH TOKENS\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);

$adminToken = trim($adminMatches[1]);
$studentToken = trim($studentMatches[1]);

echo "🔐 Using Fresh Authentication Tokens:\n";
echo "Admin Token: " . substr($adminToken, 0, 20) . "...\n";
echo "Student Token: " . substr($studentToken, 0, 20) . "...\n\n";

function testEndpoint($name, $method, $endpoint, $token = null, $data = null) {
    $url = 'http://localhost:8000/api' . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $success = in_array($httpCode, [200, 201, 204]);
    $status = $success ? '✅' : '❌';
    
    printf("%-50s %s %d\n", $name, $status, $httpCode);
    
    return $success;
}

$totalTests = 0;
$passedTests = 0;

// Test core endpoints systematically
$tests = [
    // Authentication
    ['Get Current User', 'GET', '/user', $studentToken],
    ['Logout', 'POST', '/logout', $studentToken],
    
    // Public Course Endpoints
    ['Get All Courses', 'GET', '/courses'],
    ['Get Single Course', 'GET', '/courses/11'],
    ['Search Courses', 'GET', '/courses/search?q=math'],
    ['Get Popular Courses', 'GET', '/courses/popular'],
    
    // Authenticated Course Endpoints
    ['Get My Courses', 'GET', '/courses/my-courses', $studentToken],
    ['Enroll in Course', 'POST', '/courses/11/enroll', $studentToken],
    
    // Lesson Endpoints
    ['Get Course Lessons', 'GET', '/courses/11/lessons', $studentToken],
    ['Get Single Lesson', 'GET', '/lessons/1', $studentToken],
    
    // User Management
    ['Get User Profile', 'GET', '/users/profile', $studentToken],
    ['Get User Dashboard', 'GET', '/users/dashboard', $studentToken],
    
    // Dashboard
    ['Student Dashboard', 'GET', '/dashboard/student', $studentToken],
    ['Admin Dashboard', 'GET', '/dashboard/admin', $adminToken],
    
    // Wallet
    ['Get Wallet Info', 'GET', '/wallet', $studentToken],
    ['Get Wallet Transactions', 'GET', '/wallet/transactions', $studentToken],
    
    // Payments
    ['Get Payment Gateways', 'GET', '/payments/gateways', $studentToken],
    ['Get Payment History', 'GET', '/payments/history', $studentToken],
    
    // Certificates
    ['Get User Certificates', 'GET', '/certificates', $studentToken],
    ['Get Certificate Templates', 'GET', '/certificates/templates', $adminToken],
    
    // Badges
    ['Get All Badges', 'GET', '/badges', $studentToken],
    ['Get My Badges', 'GET', '/my-badges', $studentToken],
    ['Get Badge Leaderboard', 'GET', '/badges/leaderboard', $studentToken],
    
    // Progress
    ['Get Course Progress', 'GET', '/progress/courses', $studentToken],
    ['Get Overall Progress', 'GET', '/progress/overall', $studentToken],
    
    // Notifications
    ['Get Notifications', 'GET', '/notifications', $studentToken],
    
    // Search
    ['Global Search', 'GET', '/search/global?q=math', $studentToken],
    
    // Categories
    ['Get All Categories', 'GET', '/category'],
    
    // Admin endpoints
    ['Get All Users (Admin)', 'GET', '/admin/users', $adminToken],
    ['Admin Analytics', 'GET', '/admin/analytics', $adminToken],
];

echo "🎯 TESTING " . count($tests) . " CORE ENDPOINTS:\n\n";

foreach ($tests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

$successRate = round(($passedTests / $totalTests) * 100, 2);

echo "\n============================================================\n";
echo "📊 FOCUSED ENDPOINT TEST RESULTS\n";
echo "============================================================\n";
echo "Total Tests: $totalTests\n";
echo "✅ Passed: $passedTests\n";
echo "❌ Failed: " . ($totalTests - $passedTests) . "\n";
echo "📈 Success Rate: $successRate%\n";
echo "============================================================\n";

if ($successRate >= 80) {
    echo "🎉 EXCELLENT! Most endpoints are working properly!\n";
} elseif ($successRate >= 60) {
    echo "👍 GOOD! Majority of endpoints are functional!\n";
} elseif ($successRate >= 40) {
    echo "⚠️  MODERATE! Some endpoints need attention!\n";
} else {
    echo "🚨 CRITICAL! Many endpoints require fixing!\n";
}

echo "\n🔍 Total routes in system: 269 (from php artisan route:list)\n";
echo "🎯 Core endpoints tested: $totalTests\n";
echo "📝 This represents the most critical user-facing functionality\n";
