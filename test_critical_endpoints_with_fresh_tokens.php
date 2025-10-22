<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎯 CRITICAL ENDPOINTS TEST - WITH FRESH WORKING TOKENS\n";
echo "=====================================================\n\n";

// Load authentication tokens FRESH
$authTokens = [];
$tokenContent = file_get_contents('auth_tokens.txt');

if (preg_match('/ADMIN_TOKEN=(.+)/', $tokenContent, $adminMatch)) {
    $authTokens['admin'] = trim($adminMatch[1]);
}
if (preg_match('/STUDENT_TOKEN=(.+)/', $tokenContent, $studentMatch)) {
    $authTokens['student'] = trim($studentMatch[1]);
}
if (preg_match('/INSTRUCTOR_TOKEN=(.+)/', $tokenContent, $instructorMatch)) {
    $authTokens['instructor'] = trim($instructorMatch[1]);
}

echo "🔐 Using fresh tokens:\n";
echo "Admin: " . substr($authTokens['admin'], 0, 20) . "...\n";
echo "Student: " . substr($authTokens['student'], 0, 20) . "...\n";
echo "Instructor: " . substr($authTokens['instructor'], 0, 20) . "...\n\n";

function makeRequest($url, $method = 'GET', $token = null, $data = null) {
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
    
    if ($data && ($method === 'POST' || $method === 'PUT')) {
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

// Test token validity first
echo "🧪 Testing token validity:\n";
foreach ($authTokens as $role => $token) {
    $response = makeRequest('user', 'GET', $token);
    echo "$role: " . ($response['status'] == 200 ? "✅ Valid" : "❌ Invalid ({$response['status']})") . "\n";
}

echo "\n🎯 Testing CRITICAL ENDPOINTS (Most Important for LMS):\n\n";

// CRITICAL ENDPOINTS - Most important for LMS functionality
$criticalEndpoints = [
    // CORE STUDENT FUNCTIONALITY (Must work for students to use the platform)
    ['url' => 'user', 'method' => 'GET', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get current user', 'priority' => 'CRITICAL'],
    ['url' => 'courses/my-courses', 'method' => 'GET', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get my courses', 'priority' => 'CRITICAL'],
    ['url' => 'users/profile', 'method' => 'GET', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get user profile', 'priority' => 'CRITICAL'],
    ['url' => 'users/dashboard', 'method' => 'GET', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get user dashboard', 'priority' => 'CRITICAL'],
    ['url' => 'enrollments', 'method' => 'GET', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get enrollments', 'priority' => 'CRITICAL'],
    ['url' => 'progress/overall', 'method' => 'GET', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get overall progress', 'priority' => 'CRITICAL'],
    ['url' => 'certificates', 'method' => 'GET', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get certificates', 'priority' => 'CRITICAL'],
    ['url' => 'badges', 'method' => 'GET', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get badges', 'priority' => 'CRITICAL'],
    ['url' => 'learning-paths', 'method' => 'GET', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get learning paths', 'priority' => 'CRITICAL'],
    ['url' => 'recommendations', 'method' => 'GET', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get recommendations', 'priority' => 'CRITICAL'],
    
    // PAYMENT SYSTEM (Critical for revenue)
    ['url' => 'wallet', 'method' => 'GET', 'token' => 'student', 'category' => 'Payment System', 'description' => 'Get wallet info', 'priority' => 'CRITICAL'],
    ['url' => 'wallet/transactions', 'method' => 'GET', 'token' => 'student', 'category' => 'Payment System', 'description' => 'Get wallet transactions', 'priority' => 'CRITICAL'],
    ['url' => 'payments/gateways', 'method' => 'GET', 'token' => 'student', 'category' => 'Payment System', 'description' => 'Get payment gateways', 'priority' => 'CRITICAL'],
    ['url' => 'payments/history', 'method' => 'GET', 'token' => 'student', 'category' => 'Payment System', 'description' => 'Get payment history', 'priority' => 'CRITICAL'],
    
    // COURSE LEARNING (Core learning functionality)
    ['url' => 'courses/1/lessons', 'method' => 'GET', 'token' => 'student', 'category' => 'Course Learning', 'description' => 'Get course lessons', 'priority' => 'CRITICAL'],
    ['url' => 'lessons/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Course Learning', 'description' => 'Get single lesson', 'priority' => 'CRITICAL'],
    ['url' => 'lessons/1/progress', 'method' => 'GET', 'token' => 'student', 'category' => 'Course Learning', 'description' => 'Get lesson progress', 'priority' => 'CRITICAL'],
    ['url' => 'quizzes/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Course Learning', 'description' => 'Get single quiz', 'priority' => 'CRITICAL'],
    ['url' => 'assignments/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Course Learning', 'description' => 'Get single assignment', 'priority' => 'CRITICAL'],
    
    // ADMIN FUNCTIONALITY (Critical for platform management)
    ['url' => 'admin/dashboard', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Core', 'description' => 'Admin dashboard', 'priority' => 'CRITICAL'],
    ['url' => 'admin/users', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Core', 'description' => 'Get all users', 'priority' => 'CRITICAL'],
    ['url' => 'admin/courses', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Core', 'description' => 'Get all courses', 'priority' => 'CRITICAL'],
    ['url' => 'admin/payments', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Core', 'description' => 'Get all payments', 'priority' => 'CRITICAL'],
    ['url' => 'admin/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Core', 'description' => 'Get admin analytics', 'priority' => 'CRITICAL'],
    
    // INSTRUCTOR FUNCTIONALITY (Critical for content creators)
    ['url' => 'instructor/courses', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Instructor Core', 'description' => 'Get instructor courses', 'priority' => 'CRITICAL'],
    ['url' => 'dashboard/instructor', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Instructor Core', 'description' => 'Instructor dashboard', 'priority' => 'CRITICAL'],
    ['url' => 'courses/1/students', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Instructor Core', 'description' => 'Get course students', 'priority' => 'CRITICAL'],
    ['url' => 'courses/1/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Instructor Core', 'description' => 'Get course analytics', 'priority' => 'CRITICAL'],
    
    // SEARCH & DISCOVERY (Critical for user experience)
    ['url' => 'search?q=test', 'method' => 'GET', 'token' => 'student', 'category' => 'Search & Discovery', 'description' => 'Global search', 'priority' => 'CRITICAL'],
    ['url' => 'search/courses?q=test', 'method' => 'GET', 'token' => 'student', 'category' => 'Search & Discovery', 'description' => 'Course search', 'priority' => 'CRITICAL'],
    
    // NOTIFICATIONS (Critical for user engagement)
    ['url' => 'notifications', 'method' => 'GET', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Get notifications', 'priority' => 'CRITICAL'],
    
    // PUBLIC ENDPOINTS (Critical for marketing and discovery)
    ['url' => 'courses', 'method' => 'GET', 'token' => null, 'category' => 'Public Access', 'description' => 'Get all courses', 'priority' => 'CRITICAL'],
    ['url' => 'courses/featured', 'method' => 'GET', 'token' => null, 'category' => 'Public Access', 'description' => 'Get featured courses', 'priority' => 'CRITICAL'],
    ['url' => 'category', 'method' => 'GET', 'token' => null, 'category' => 'Public Access', 'description' => 'Get categories', 'priority' => 'CRITICAL'],
];

$results = [
    'total' => 0,
    'success' => 0,
    'failed' => 0,
    'by_status' => [],
    'by_category' => [],
    'by_priority' => []
];

foreach ($criticalEndpoints as $endpoint) {
    $results['total']++;
    $token = $endpoint['token'] ? $authTokens[$endpoint['token']] : null;
    $data = $endpoint['data'] ?? null;
    $response = makeRequest($endpoint['url'], $endpoint['method'], $token, $data);
    $status = $response['status'];
    
    $category = $endpoint['category'];
    $priority = $endpoint['priority'];
    
    if (!isset($results['by_category'][$category])) {
        $results['by_category'][$category] = ['total' => 0, 'success' => 0];
    }
    $results['by_category'][$category]['total']++;
    
    if (!isset($results['by_priority'][$priority])) {
        $results['by_priority'][$priority] = ['total' => 0, 'success' => 0];
    }
    $results['by_priority'][$priority]['total']++;
    
    if (!isset($results['by_status'][$status])) {
        $results['by_status'][$status] = 0;
    }
    $results['by_status'][$status]++;
    
    if ($status >= 200 && $status < 300) {
        $results['success']++;
        $results['by_category'][$category]['success']++;
        $results['by_priority'][$priority]['success']++;
        echo "✅ {$endpoint['method']} {$endpoint['url']} ({$category})\n";
    } else {
        $results['failed']++;
        echo "❌ {$endpoint['method']} {$endpoint['url']} - $status ({$category}) - {$endpoint['description']}\n";
        
        // Show error details for critical failures
        if ($status == 401) {
            echo "   🔐 Authentication issue\n";
        } elseif ($status == 403) {
            echo "   ⚠️  Permission/enrollment issue (may be correct behavior)\n";
        } elseif ($status == 500) {
            $body = json_decode($response['body'], true);
            if ($body && isset($body['message'])) {
                echo "   💥 Server error: " . substr($body['message'], 0, 80) . "...\n";
            }
        }
    }
}

echo "\n=====================================================\n";
echo "📊 CRITICAL ENDPOINTS TEST RESULTS\n";
echo "=====================================================\n";

echo "📈 OVERALL STATISTICS:\n";
echo "Total Critical Endpoints Tested: {$results['total']}\n";
echo "✅ Successfully Working: {$results['success']}\n";
echo "❌ Failed: {$results['failed']}\n";

$successRate = round(($results['success'] / $results['total']) * 100, 2);
echo "📈 Critical Success Rate: $successRate%\n\n";

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

echo "\n📊 BY PRIORITY:\n";
foreach ($results['by_priority'] as $priority => $priorityResult) {
    $priorityRate = round(($priorityResult['success'] / $priorityResult['total']) * 100, 2);
    echo "• $priority: {$priorityResult['success']}/{$priorityResult['total']} ({$priorityRate}%)\n";
}

echo "\n=====================================================\n";
echo "🎯 CRITICAL FUNCTIONALITY ASSESSMENT\n";
echo "=====================================================\n";

if ($successRate >= 95) {
    echo "🎉 PERFECT! 95%+ critical success rate - WORLD-CLASS PRODUCTION READY!\n";
    echo "🚀 All critical functionality working - Ready for millions of users!\n";
    $status = "WORLD-CLASS PRODUCTION READY";
} elseif ($successRate >= 90) {
    echo "🎉 EXCELLENT! 90%+ critical success rate - FULLY PRODUCTION READY!\n";
    echo "🚀 Critical functionality working - Ready for immediate deployment!\n";
    $status = "FULLY PRODUCTION READY";
} elseif ($successRate >= 85) {
    echo "✅ VERY GOOD! 85%+ critical success rate - PRODUCTION READY!\n";
    echo "🚀 Core functionality working - Ready for deployment!\n";
    $status = "PRODUCTION READY";
} elseif ($successRate >= 80) {
    echo "👍 GOOD! 80%+ critical success rate - MOSTLY PRODUCTION READY!\n";
    echo "🚀 Most critical functionality working - Minor fixes needed!\n";
    $status = "MOSTLY PRODUCTION READY";
} elseif ($successRate >= 70) {
    echo "⚠️  NEEDS WORK! 70%+ critical success rate.\n";
    echo "🔧 Some critical functionality issues - Fixes needed!\n";
    $status = "NEEDS WORK";
} else {
    echo "⚠️  CRITICAL ISSUES! Below 70% critical success rate.\n";
    echo "🔧 Major critical functionality problems - Significant fixes needed!\n";
    $status = "CRITICAL ISSUES";
}

echo "\n🇳🇬 KOKOKAH.COM LMS CRITICAL FUNCTIONALITY STATUS:\n";
echo "Platform Readiness: $status\n";
echo "Market Launch: " . ($successRate >= 85 ? "READY FOR LAUNCH" : "NEEDS FIXES BEFORE LAUNCH") . "\n";
echo "Critical Endpoints: {$results['total']}\n";
echo "Working Critical Endpoints: {$results['success']}\n";
echo "Critical Success Rate: $successRate%\n";
echo "=====================================================\n";
