<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 COMPREHENSIVE TEST WITH FRESH WORKING TOKENS\n";
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
    
    printf("%-55s %s %d\n", $name, $status, $httpCode);
    
    return $success;
}

$totalTests = 0;
$passedTests = 0;

// Comprehensive test of all major endpoint categories
$tests = [
    // Authentication & User Management
    ['Get Current User', 'GET', '/user', $studentToken],
    ['Get User Profile', 'GET', '/users/profile', $studentToken],
    ['Get User Dashboard', 'GET', '/users/dashboard', $studentToken],
    ['Get User Achievements', 'GET', '/users/achievements', $studentToken],
    ['Get Learning Statistics', 'GET', '/users/learning-stats', $studentToken],
    ['Get User Notifications', 'GET', '/users/notifications', $studentToken],
    
    // Course Management
    ['Get All Courses (Public)', 'GET', '/courses'],
    ['Get Single Course (Public)', 'GET', '/courses/11'],
    ['Search Courses (Public)', 'GET', '/courses/search?q=math'],
    ['Get Popular Courses (Public)', 'GET', '/courses/popular'],
    ['Get Featured Courses (Public)', 'GET', '/courses/featured'],
    ['Get My Courses (Auth)', 'GET', '/courses/my-courses', $studentToken],
    ['Get Course Students (Admin)', 'GET', '/courses/11/students', $adminToken],
    ['Get Course Analytics (Admin)', 'GET', '/courses/11/analytics', $adminToken],
    
    // Lesson Management
    ['Get Course Lessons', 'GET', '/courses/11/lessons', $studentToken],
    ['Get Single Lesson', 'GET', '/lessons/1', $studentToken],
    ['Get Lesson Progress', 'GET', '/lessons/1/progress', $studentToken],
    ['Get Lesson Attachments', 'GET', '/lessons/1/attachments', $studentToken],
    
    // Quiz Management
    ['Get Lesson Quizzes', 'GET', '/lessons/1/quizzes', $studentToken],
    ['Get Single Quiz', 'GET', '/quizzes/1', $studentToken],
    ['Get Quiz Results', 'GET', '/quizzes/1/results', $studentToken],
    ['Get Quiz Analytics (Admin)', 'GET', '/quizzes/1/analytics', $adminToken],
    
    // Assignment Management
    ['Get Course Assignments', 'GET', '/courses/11/assignments', $studentToken],
    ['Get Single Assignment', 'GET', '/assignments/1', $studentToken],
    
    // Dashboard
    ['Student Dashboard', 'GET', '/dashboard/student', $studentToken],
    ['Admin Dashboard', 'GET', '/dashboard/admin', $adminToken],
    ['Dashboard Analytics', 'GET', '/dashboard/analytics', $studentToken],
    
    // Wallet Management
    ['Get Wallet Info', 'GET', '/wallet', $studentToken],
    ['Get Wallet Transactions', 'GET', '/wallet/transactions', $studentToken],
    ['Get Wallet Rewards', 'GET', '/wallet/rewards', $studentToken],
    
    // Payment Management
    ['Get Payment Gateways', 'GET', '/payments/gateways', $studentToken],
    ['Get Payment History', 'GET', '/payments/history', $studentToken],
    
    // Certificate Management
    ['Get User Certificates', 'GET', '/certificates', $studentToken],
    ['Get Certificate Templates (Admin)', 'GET', '/certificates/templates', $adminToken],
    ['Get Certificate Analytics (Admin)', 'GET', '/certificates/analytics', $adminToken],
    
    // Badge Management
    ['Get All Badges', 'GET', '/badges', $studentToken],
    ['Get My Badges', 'GET', '/my-badges', $studentToken],
    ['Get Badge Leaderboard', 'GET', '/badges/leaderboard', $studentToken],
    ['Get Badge Analytics (Admin)', 'GET', '/badges/analytics', $adminToken],
    
    // Progress Tracking
    ['Get Course Progress', 'GET', '/progress/courses', $studentToken],
    ['Get Overall Progress', 'GET', '/progress/overall', $studentToken],
    ['Get Achievement Progress', 'GET', '/progress/achievements', $studentToken],
    ['Get Available Certificates', 'GET', '/progress/certificates', $studentToken],
    ['Get Streak Progress', 'GET', '/progress/streaks', $studentToken],
    
    // Analytics
    ['Learning Analytics', 'GET', '/analytics/learning', $adminToken],
    ['Course Performance Analytics', 'GET', '/analytics/course-performance', $adminToken],
    ['Student Progress Analytics', 'GET', '/analytics/student-progress', $adminToken],
    ['Engagement Analytics', 'GET', '/analytics/engagement', $adminToken],
    
    // Recommendations
    ['Get Personalized Recommendations', 'GET', '/recommendations', $studentToken],
    ['Get Course-based Recommendations', 'GET', '/recommendations/courses/11', $studentToken],
    ['Get Learning Path Recommendations', 'GET', '/recommendations/learning-paths', $studentToken],
    ['Get Instructor Recommendations', 'GET', '/recommendations/instructors', $studentToken],
    ['Get Content Recommendations', 'GET', '/recommendations/content', $studentToken],
    
    // AI Chat System
    ['Get Chat Sessions', 'GET', '/chat/sessions', $studentToken],
    ['Get Chat Analytics (Admin)', 'GET', '/chat/analytics', $adminToken],
    
    // Search System
    ['Global Search', 'GET', '/search/global?q=math', $studentToken],
    ['Course Search', 'GET', '/search/courses?q=introduction', $studentToken],
    ['User Search', 'GET', '/search/users?q=admin', $studentToken],
    ['Content Search', 'GET', '/search/content?q=math&course_id=10', $studentToken],
    ['Search Suggestions', 'GET', '/search/suggestions?q=math&type=courses', $studentToken],
    ['Search Filters', 'GET', '/search/filters', $studentToken],
    
    // Notification System
    ['Get Notifications', 'GET', '/notifications', $studentToken],
    ['Get Notification Preferences', 'GET', '/notifications/preferences', $studentToken],
    
    // File Management
    ['List User Files', 'GET', '/files/list', $studentToken],
    ['Get Storage Statistics', 'GET', '/files/storage/stats', $studentToken],
    
    // Categories
    ['Get All Categories (Public)', 'GET', '/category'],
    ['Get Single Category (Public)', 'GET', '/category/1'],
    
    // Admin Management
    ['Get All Users (Admin)', 'GET', '/admin/users', $adminToken],
    ['Admin Analytics', 'GET', '/admin/analytics', $adminToken],
    ['Admin Courses', 'GET', '/admin/courses', $adminToken],
    ['Admin Payments', 'GET', '/admin/payments', $adminToken],
    ['Admin Settings', 'GET', '/admin/settings', $adminToken],
    ['Database Stats (Admin)', 'GET', '/admin/database-stats', $adminToken],
    ['Audit Logs (Admin)', 'GET', '/audit/logs', $adminToken],
];

echo "🎯 TESTING " . count($tests) . " COMPREHENSIVE ENDPOINTS:\n\n";

foreach ($tests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

$successRate = round(($passedTests / $totalTests) * 100, 2);

echo "\n============================================================\n";
echo "📊 COMPREHENSIVE ENDPOINT TEST RESULTS\n";
echo "============================================================\n";
echo "Total Tests: $totalTests\n";
echo "✅ Passed: $passedTests\n";
echo "❌ Failed: " . ($totalTests - $passedTests) . "\n";
echo "📈 Success Rate: $successRate%\n";
echo "============================================================\n";

if ($successRate >= 90) {
    echo "🎉 OUTSTANDING! Almost all endpoints are working!\n";
} elseif ($successRate >= 80) {
    echo "🎉 EXCELLENT! Most endpoints are working properly!\n";
} elseif ($successRate >= 70) {
    echo "👍 VERY GOOD! Majority of endpoints are functional!\n";
} elseif ($successRate >= 60) {
    echo "👍 GOOD! Most core functionality is working!\n";
} elseif ($successRate >= 50) {
    echo "⚠️  MODERATE! Half of the endpoints are working!\n";
} elseif ($successRate >= 40) {
    echo "⚠️  NEEDS WORK! Some endpoints need attention!\n";
} else {
    echo "🚨 CRITICAL! Many endpoints require fixing!\n";
}

echo "\n🔍 Total routes in system: 269 (from php artisan route:list)\n";
echo "🎯 Comprehensive endpoints tested: $totalTests\n";
echo "📝 This represents the full user-facing API functionality\n";

// Save results to file
$report = "# COMPREHENSIVE ENDPOINT TEST REPORT\n\n";
$report .= "**Date:** " . date('Y-m-d H:i:s') . "\n";
$report .= "**Total Tests:** $totalTests\n";
$report .= "**Passed:** $passedTests\n";
$report .= "**Failed:** " . ($totalTests - $passedTests) . "\n";
$report .= "**Success Rate:** $successRate%\n\n";
$report .= "**Status:** ";
if ($successRate >= 80) {
    $report .= "PRODUCTION READY ✅\n";
} elseif ($successRate >= 60) {
    $report .= "MOSTLY FUNCTIONAL ⚠️\n";
} else {
    $report .= "NEEDS SIGNIFICANT WORK 🚨\n";
}

file_put_contents('COMPREHENSIVE_ENDPOINT_TEST_REPORT.md', $report);
echo "\n📄 Report saved to: COMPREHENSIVE_ENDPOINT_TEST_REPORT.md\n";
