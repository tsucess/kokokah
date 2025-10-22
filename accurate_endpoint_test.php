<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 ACCURATE TEST OF ALL IMPLEMENTED ENDPOINTS\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);

$adminToken = trim($adminMatches[1]);
$studentToken = trim($studentMatches[1]);

echo "🔐 Using Authentication Tokens:\n";
echo "Admin Token: " . substr($adminToken, 0, 20) . "...\n";
echo "Student Token: " . substr($studentToken, 0, 20) . "...\n\n";

function testEndpoint($name, $method, $endpoint, $token = null, $data = null, $expectedCodes = [200, 201, 204]) {
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
    
    $success = in_array($httpCode, $expectedCodes);
    $status = $success ? '✅' : '❌';
    
    echo sprintf("%-60s %s %d\n", $name, $status, $httpCode);
    
    return $success;
}

$totalTests = 0;
$passedTests = 0;

echo "🎯 TESTING CORE IMPLEMENTED ENDPOINTS:\n\n";

// Authentication Endpoints (Public)
echo "🔐 AUTHENTICATION ENDPOINTS:\n";
$authTests = [
    ['Get Current User', 'GET', '/user', $studentToken],
    ['Logout', 'POST', '/logout', $studentToken],
];

foreach ($authTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Course Management (Mix of public and authenticated)
echo "\n📚 COURSE MANAGEMENT ENDPOINTS:\n";
$courseTests = [
    ['Get All Courses (Public)', 'GET', '/courses'],
    ['Get Single Course (Public)', 'GET', '/courses/11'],
    ['Search Courses (Public)', 'GET', '/courses/search?q=math'],
    ['Get Featured Courses (Public)', 'GET', '/courses/featured'],
    ['Get Popular Courses (Public)', 'GET', '/courses/popular'],
    ['Get My Courses (Auth)', 'GET', '/courses/my-courses', $studentToken],
    ['Get Course Students (Admin)', 'GET', '/courses/11/students', $adminToken],
    ['Get Course Analytics (Admin)', 'GET', '/courses/11/analytics', $adminToken],
    ['Enroll in Course (Auth)', 'POST', '/courses/11/enroll', $studentToken],
];

foreach ($courseTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Lesson Management (Authenticated)
echo "\n📖 LESSON MANAGEMENT ENDPOINTS:\n";
$lessonTests = [
    ['Get Course Lessons', 'GET', '/courses/11/lessons', $studentToken],
    ['Get Single Lesson', 'GET', '/lessons/1', $studentToken],
    ['Get Lesson Progress', 'GET', '/lessons/1/progress', $studentToken],
    ['Get Lesson Attachments', 'GET', '/lessons/1/attachments', $studentToken],
    ['Mark Lesson Complete', 'POST', '/lessons/1/complete', $studentToken],
];

foreach ($lessonTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Quiz Management (Authenticated)
echo "\n📝 QUIZ MANAGEMENT ENDPOINTS:\n";
$quizTests = [
    ['Get Lesson Quizzes', 'GET', '/lessons/1/quizzes', $studentToken],
    ['Get Single Quiz', 'GET', '/quizzes/1', $studentToken],
    ['Get Quiz Results', 'GET', '/quizzes/1/results', $studentToken],
    ['Get Quiz Analytics (Admin)', 'GET', '/quizzes/1/analytics', $adminToken],
];

foreach ($quizTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// User Management (Authenticated)
echo "\n👥 USER MANAGEMENT ENDPOINTS:\n";
$userTests = [
    ['Get User Profile', 'GET', '/users/profile', $studentToken],
    ['Get User Dashboard', 'GET', '/users/dashboard', $studentToken],
    ['Get User Achievements', 'GET', '/users/achievements', $studentToken],
    ['Get Learning Statistics', 'GET', '/users/learning-stats', $studentToken],
    ['Get User Notifications', 'GET', '/users/notifications', $studentToken],
    ['Get All Users (Admin)', 'GET', '/admin/users', $adminToken],
];

foreach ($userTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Dashboard Endpoints (Authenticated)
echo "\n📊 DASHBOARD ENDPOINTS:\n";
$dashboardTests = [
    ['Student Dashboard', 'GET', '/dashboard/student', $studentToken],
    ['Admin Dashboard', 'GET', '/dashboard/admin', $adminToken],
    ['Dashboard Analytics', 'GET', '/dashboard/analytics', $studentToken],
];

foreach ($dashboardTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Payment Management (Authenticated)
echo "\n💰 PAYMENT MANAGEMENT ENDPOINTS:\n";
$paymentTests = [
    ['Get Payment Gateways', 'GET', '/payments/gateways', $studentToken],
    ['Get Payment History', 'GET', '/payments/history', $studentToken],
];

foreach ($paymentTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Wallet Management (Authenticated)
echo "\n💳 WALLET MANAGEMENT ENDPOINTS:\n";
$walletTests = [
    ['Get Wallet Info', 'GET', '/wallet', $studentToken],
    ['Get Wallet Transactions', 'GET', '/wallet/transactions', $studentToken],
    ['Get Wallet Rewards', 'GET', '/wallet/rewards', $studentToken],
];

foreach ($walletTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Certificate Management (Authenticated)
echo "\n🎖️ CERTIFICATE ENDPOINTS:\n";
$certificateTests = [
    ['Get User Certificates', 'GET', '/certificates', $studentToken],
    ['Get Certificate Templates (Admin)', 'GET', '/certificates/templates', $adminToken],
    ['Get Certificate Analytics (Admin)', 'GET', '/certificates/analytics', $adminToken],
];

foreach ($certificateTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Badge Management (Authenticated)
echo "\n🏆 BADGE ENDPOINTS:\n";
$badgeTests = [
    ['Get All Badges', 'GET', '/badges', $studentToken],
    ['Get My Badges', 'GET', '/my-badges', $studentToken],
    ['Get Badge Leaderboard', 'GET', '/badges/leaderboard', $studentToken],
    ['Get Badge Analytics (Admin)', 'GET', '/badges/analytics', $adminToken],
];

foreach ($badgeTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Progress Tracking (Authenticated)
echo "\n📈 PROGRESS TRACKING ENDPOINTS:\n";
$progressTests = [
    ['Get Course Progress', 'GET', '/progress/courses', $studentToken],
    ['Get Overall Progress', 'GET', '/progress/overall', $studentToken],
    ['Get Achievement Progress', 'GET', '/progress/achievements', $studentToken],
];

foreach ($progressTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Recommendation System (Authenticated)
echo "\n🎯 RECOMMENDATION ENDPOINTS:\n";
$recommendationTests = [
    ['Get Personalized Recommendations', 'GET', '/recommendations', $studentToken],
    ['Get Course-based Recommendations', 'GET', '/recommendations/courses/11', $studentToken],
];

foreach ($recommendationTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// AI Chat System (Authenticated)
echo "\n💬 AI CHAT ENDPOINTS:\n";
$chatTests = [
    ['Get Chat Sessions', 'GET', '/chat/sessions', $studentToken],
    ['Get Chat Analytics (Admin)', 'GET', '/chat/analytics', $adminToken],
];

foreach ($chatTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Search System (Authenticated)
echo "\n🔍 SEARCH ENDPOINTS:\n";
$searchTests = [
    ['Global Search', 'GET', '/search/global?q=math', $studentToken],
    ['Course Search', 'GET', '/search/courses?q=introduction', $studentToken],
    ['User Search', 'GET', '/search/users?q=admin', $studentToken],
];

foreach ($searchTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Notification System (Authenticated)
echo "\n🔔 NOTIFICATION ENDPOINTS:\n";
$notificationTests = [
    ['Get Notifications', 'GET', '/notifications', $studentToken],
    ['Get Notification Preferences', 'GET', '/notifications/preferences', $studentToken],
];

foreach ($notificationTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// File Management (Authenticated)
echo "\n📁 FILE MANAGEMENT ENDPOINTS:\n";
$fileTests = [
    ['List User Files', 'GET', '/files/list', $studentToken],
    ['Get Storage Statistics', 'GET', '/files/storage/stats', $studentToken],
];

foreach ($fileTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Categories (Public)
echo "\n📋 CATEGORY ENDPOINTS:\n";
$categoryTests = [
    ['Get All Categories (Public)', 'GET', '/category'],
    ['Get Single Category (Public)', 'GET', '/category/1'],
];

foreach ($categoryTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

echo "\n============================================================\n";
echo "📊 ACCURATE ENDPOINT TEST RESULTS\n";
echo "============================================================\n";
echo "Total Tests: $totalTests\n";
echo "✅ Passed: $passedTests\n";
echo "❌ Failed: " . ($totalTests - $passedTests) . "\n";
echo "📈 Success Rate: " . round(($passedTests / $totalTests) * 100, 2) . "%\n";
echo "============================================================\n";
echo "📝 Note: This test focuses on core implemented endpoints with proper authentication\n";
echo "🔍 Total routes in system: 269 (from php artisan route:list)\n";
echo "🎯 Core endpoints tested: $totalTests\n";
