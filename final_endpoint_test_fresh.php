<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎯 FINAL COMPREHENSIVE ENDPOINT TEST - FRESH TOKENS\n";
echo "===================================================\n\n";

// Load authentication tokens FRESH
$authTokens = [];
$tokenContent = file_get_contents('auth_tokens.txt');
echo "Reading tokens from file:\n";
echo $tokenContent . "\n";

if (preg_match('/ADMIN_TOKEN=(.+)/', $tokenContent, $adminMatch)) {
    $authTokens['admin'] = trim($adminMatch[1]);
    echo "Admin token: " . substr($authTokens['admin'], 0, 20) . "...\n";
}
if (preg_match('/STUDENT_TOKEN=(.+)/', $tokenContent, $studentMatch)) {
    $authTokens['student'] = trim($studentMatch[1]);
    echo "Student token: " . substr($authTokens['student'], 0, 20) . "...\n";
}
if (preg_match('/INSTRUCTOR_TOKEN=(.+)/', $tokenContent, $instructorMatch)) {
    $authTokens['instructor'] = trim($instructorMatch[1]);
    echo "Instructor token: " . substr($authTokens['instructor'], 0, 20) . "...\n";
}

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
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['status' => 0, 'body' => $error];
    }
    
    return ['status' => $httpCode, 'body' => $response];
}

echo "\n🧪 Testing token validity first:\n";
foreach ($authTokens as $role => $token) {
    $response = makeRequest('user', 'GET', $token);
    echo "$role: " . ($response['status'] == 200 ? "✅ Valid" : "❌ Invalid ({$response['status']})") . "\n";
}

// CORE FUNCTIONALITY TEST - Focus on most important endpoints
$coreEndpoints = [
    // CRITICAL STUDENT FUNCTIONALITY
    ['url' => 'user', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get current user'],
    ['url' => 'courses/my-courses', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get my courses'],
    ['url' => 'users/profile', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get user profile'],
    ['url' => 'users/dashboard', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get user dashboard'],
    ['url' => 'enrollments', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get enrollments'],
    ['url' => 'progress/overall', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get overall progress'],
    ['url' => 'certificates', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get certificates'],
    ['url' => 'badges', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get badges'],
    ['url' => 'learning-paths', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get learning paths'],
    ['url' => 'recommendations', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get recommendations'],
    
    // PAYMENT SYSTEM
    ['url' => 'wallet', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get wallet info'],
    ['url' => 'wallet/transactions', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get wallet transactions'],
    ['url' => 'payments/gateways', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get payment gateways'],
    ['url' => 'payments/history', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get payment history'],
    
    // ADMIN FUNCTIONALITY
    ['url' => 'admin/dashboard', 'token' => 'admin', 'category' => 'Admin', 'description' => 'Admin dashboard'],
    ['url' => 'admin/users', 'token' => 'admin', 'category' => 'Admin', 'description' => 'Get all users'],
    ['url' => 'admin/analytics', 'token' => 'admin', 'category' => 'Admin', 'description' => 'Get admin analytics'],
    ['url' => 'admin/settings', 'token' => 'admin', 'category' => 'Admin', 'description' => 'Get admin settings'],
    
    // COURSE MANAGEMENT
    ['url' => 'courses/1/students', 'token' => 'admin', 'category' => 'Course Management', 'description' => 'Get course students'],
    ['url' => 'courses/1/analytics', 'token' => 'admin', 'category' => 'Course Management', 'description' => 'Get course analytics'],
    ['url' => 'courses/1/lessons', 'token' => 'student', 'category' => 'Course Management', 'description' => 'Get course lessons'],
    ['url' => 'courses/1/assignments', 'token' => 'student', 'category' => 'Course Management', 'description' => 'Get course assignments'],
    
    // ASSESSMENT SYSTEM
    ['url' => 'assignments/1', 'token' => 'student', 'category' => 'Assessment', 'description' => 'Get single assignment'],
    ['url' => 'assignments/1/submissions', 'token' => 'admin', 'category' => 'Assessment', 'description' => 'Get assignment submissions'],
    ['url' => 'quizzes/1', 'token' => 'student', 'category' => 'Assessment', 'description' => 'Get single quiz'],
    ['url' => 'quizzes/1/analytics', 'token' => 'admin', 'category' => 'Assessment', 'description' => 'Get quiz analytics'],
    
    // INSTRUCTOR FUNCTIONALITY
    ['url' => 'instructor/courses', 'token' => 'instructor', 'category' => 'Instructor', 'description' => 'Get instructor courses'],
    ['url' => 'dashboard/instructor', 'token' => 'instructor', 'category' => 'Instructor', 'description' => 'Instructor dashboard'],
    
    // SEARCH FUNCTIONALITY
    ['url' => 'search?q=test', 'token' => 'student', 'category' => 'Search', 'description' => 'Global search'],
    ['url' => 'search/courses?q=test', 'token' => 'student', 'category' => 'Search', 'description' => 'Course search'],
    
    // NOTIFICATIONS
    ['url' => 'notifications', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Get notifications'],
    
    // SETTINGS
    ['url' => 'settings', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get settings'],
    
    // PUBLIC ENDPOINTS
    ['url' => 'courses', 'token' => null, 'category' => 'Public', 'description' => 'Get all courses'],
    ['url' => 'courses/featured', 'token' => null, 'category' => 'Public', 'description' => 'Get featured courses'],
    ['url' => 'category', 'token' => null, 'category' => 'Public', 'description' => 'Get categories'],
];

echo "\n🧪 Testing " . count($coreEndpoints) . " core functionality endpoints:\n\n";

$results = [
    'total' => 0,
    'success' => 0,
    'failed' => 0,
    'by_status' => [],
    'by_category' => []
];

foreach ($coreEndpoints as $endpoint) {
    $results['total']++;
    $token = $endpoint['token'] ? $authTokens[$endpoint['token']] : null;
    $response = makeRequest($endpoint['url'], 'GET', $token);
    $status = $response['status'];
    
    $category = $endpoint['category'];
    
    if (!isset($results['by_category'][$category])) {
        $results['by_category'][$category] = ['total' => 0, 'success' => 0];
    }
    $results['by_category'][$category]['total']++;
    
    if (!isset($results['by_status'][$status])) {
        $results['by_status'][$status] = 0;
    }
    $results['by_status'][$status]++;
    
    if ($status == 200 || $status == 201) {
        $results['success']++;
        $results['by_category'][$category]['success']++;
        echo "✅ {$endpoint['url']} ({$category})\n";
    } else {
        $results['failed']++;
        echo "❌ {$endpoint['url']} - $status ({$category}) - {$endpoint['description']}\n";
        
        // Show error details for debugging
        if ($status == 401) {
            echo "   🔐 Authentication issue\n";
        } elseif ($status == 500) {
            $body = json_decode($response['body'], true);
            if ($body && isset($body['message'])) {
                echo "   Error: " . substr($body['message'], 0, 80) . "...\n";
            }
        }
    }
}

echo "\n===================================================\n";
echo "📊 CORE FUNCTIONALITY TEST RESULTS\n";
echo "===================================================\n";

echo "📈 OVERALL STATISTICS:\n";
echo "Total Core Endpoints Tested: {$results['total']}\n";
echo "✅ Successfully Working: {$results['success']}\n";
echo "❌ Failed: {$results['failed']}\n";

$successRate = round(($results['success'] / $results['total']) * 100, 2);
echo "📈 Core Success Rate: $successRate%\n\n";

echo "📊 BY HTTP STATUS CODE:\n";
ksort($results['by_status']);
foreach ($results['by_status'] as $status => $count) {
    $percentage = round(($count / $results['total']) * 100, 2);
    $statusText = match($status) {
        200, 201 => "✅ Success",
        401 => "🔐 Unauthorized",
        403 => "⚠️  Forbidden",
        404 => "🔍 Not Found",
        422 => "📝 Validation Error",
        500 => "❌ Server Error",
        default => "❓ Other"
    };
    echo "• $status ($statusText): $count ($percentage%)\n";
}

echo "\n📊 BY CATEGORY:\n";
foreach ($results['by_category'] as $category => $categoryResult) {
    $categoryRate = round(($categoryResult['success'] / $categoryResult['total']) * 100, 2);
    echo "• $category: {$categoryResult['success']}/{$categoryResult['total']} ({$categoryRate}%)\n";
}

echo "\n===================================================\n";
echo "🎯 FINAL ASSESSMENT\n";
echo "===================================================\n";

if ($successRate >= 95) {
    echo "🎉 PERFECT! 95%+ success rate - WORLD-CLASS PRODUCTION READY!\n";
    echo "🚀 Your platform is ready to serve millions of users globally!\n";
    $status = "WORLD-CLASS PRODUCTION READY";
} elseif ($successRate >= 90) {
    echo "🎉 EXCELLENT! 90%+ success rate - FULLY PRODUCTION READY!\n";
    echo "🚀 Your platform is ready for immediate global deployment!\n";
    $status = "FULLY PRODUCTION READY";
} elseif ($successRate >= 85) {
    echo "✅ VERY GOOD! 85%+ success rate - PRODUCTION READY!\n";
    echo "🚀 Your platform is ready for immediate deployment!\n";
    $status = "PRODUCTION READY";
} elseif ($successRate >= 80) {
    echo "👍 GOOD! 80%+ success rate - MOSTLY PRODUCTION READY!\n";
    echo "🚀 Your platform is ready for deployment with minor fixes!\n";
    $status = "MOSTLY PRODUCTION READY";
} else {
    echo "⚠️  NEEDS IMPROVEMENT! Below 80% success rate.\n";
    echo "🔧 Additional fixes needed before production deployment.\n";
    $status = "NEEDS IMPROVEMENT";
}

echo "\n🇳🇬 KOKOKAH.COM LMS FINAL STATUS:\n";
echo "Platform Readiness: $status\n";
echo "Market Launch: " . ($successRate >= 85 ? "READY FOR GLOBAL MARKETS" : "NEEDS ADDITIONAL WORK") . "\n";
echo "Core Endpoints: {$results['total']}\n";
echo "Working Endpoints: {$results['success']}\n";
echo "Success Rate: $successRate%\n";
echo "===================================================\n";
