<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 TESTING WITH PROPER TOKEN ASSIGNMENT\n";
echo "=======================================\n\n";

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
        CURLOPT_URL => "http://127.0.0.1:8000/$url",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
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

// Define comprehensive test endpoints with proper token assignments
$testEndpoints = [
    // Student-accessible endpoints
    ['url' => 'api/user', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/courses', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/courses/featured', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/courses/my-courses', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/enrollments', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/users/profile', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/users/dashboard', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/progress/overall', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/certificates', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/wallet', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/payments/history', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/notifications', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/badges', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/learning-paths', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    ['url' => 'api/search?q=test', 'token' => 'student', 'method' => 'GET', 'category' => 'Student'],
    
    // Admin-only endpoints
    ['url' => 'api/admin/dashboard', 'token' => 'admin', 'method' => 'GET', 'category' => 'Admin'],
    ['url' => 'api/admin/users', 'token' => 'admin', 'method' => 'GET', 'category' => 'Admin'],
    ['url' => 'api/admin/analytics', 'token' => 'admin', 'method' => 'GET', 'category' => 'Admin'],
    ['url' => 'api/admin/settings', 'token' => 'admin', 'method' => 'GET', 'category' => 'Admin'],
    ['url' => 'api/admin/courses', 'token' => 'admin', 'method' => 'GET', 'category' => 'Admin'],
    ['url' => 'api/admin/enrollments', 'token' => 'admin', 'method' => 'GET', 'category' => 'Admin'],
    ['url' => 'api/admin/payments', 'token' => 'admin', 'method' => 'GET', 'category' => 'Admin'],
    ['url' => 'api/admin/reports', 'token' => 'admin', 'method' => 'GET', 'category' => 'Admin'],
    ['url' => 'api/admin/audit-logs', 'token' => 'admin', 'method' => 'GET', 'category' => 'Admin'],
    ['url' => 'api/settings', 'token' => 'admin', 'method' => 'GET', 'category' => 'Admin'],
    
    // Instructor endpoints
    ['url' => 'api/instructor/dashboard', 'token' => 'instructor', 'method' => 'GET', 'category' => 'Instructor'],
    ['url' => 'api/instructor/courses', 'token' => 'instructor', 'method' => 'GET', 'category' => 'Instructor'],
    ['url' => 'api/instructor/students', 'token' => 'instructor', 'method' => 'GET', 'category' => 'Instructor'],
    ['url' => 'api/instructor/analytics', 'token' => 'instructor', 'method' => 'GET', 'category' => 'Instructor'],
    
    // Course management (admin)
    ['url' => 'api/courses/1', 'token' => 'admin', 'method' => 'GET', 'category' => 'Course Management'],
    ['url' => 'api/courses/1/students', 'token' => 'admin', 'method' => 'GET', 'category' => 'Course Management'],
    ['url' => 'api/courses/1/analytics', 'token' => 'admin', 'method' => 'GET', 'category' => 'Course Management'],
    ['url' => 'api/courses/1/lessons', 'token' => 'admin', 'method' => 'GET', 'category' => 'Course Management'],
    ['url' => 'api/courses/1/assignments', 'token' => 'admin', 'method' => 'GET', 'category' => 'Course Management'],
    ['url' => 'api/courses/1/quizzes', 'token' => 'admin', 'method' => 'GET', 'category' => 'Course Management'],
    ['url' => 'api/courses/1/forum', 'token' => 'admin', 'method' => 'GET', 'category' => 'Course Management'],
    
    // Assignment and quiz management (admin)
    ['url' => 'api/assignments/1', 'token' => 'admin', 'method' => 'GET', 'category' => 'Assessment'],
    ['url' => 'api/assignments/1/submissions', 'token' => 'admin', 'method' => 'GET', 'category' => 'Assessment'],
    ['url' => 'api/quizzes/1', 'token' => 'admin', 'method' => 'GET', 'category' => 'Assessment'],
    ['url' => 'api/quizzes/1/analytics', 'token' => 'admin', 'method' => 'GET', 'category' => 'Assessment'],
    
    // File and certificate management
    ['url' => 'api/files', 'token' => 'admin', 'method' => 'GET', 'category' => 'File Management'],
    ['url' => 'api/certificates/1', 'token' => 'admin', 'method' => 'GET', 'category' => 'Certificates'],
    ['url' => 'api/certificates/1/download', 'token' => 'admin', 'method' => 'GET', 'category' => 'Certificates'],
    
    // Chat and communication
    ['url' => 'api/chat/sessions', 'token' => 'admin', 'method' => 'GET', 'category' => 'Communication'],
    ['url' => 'api/chat/sessions/1', 'token' => 'admin', 'method' => 'GET', 'category' => 'Communication'],
    
    // Previously failing endpoints
    ['url' => 'api/enrollments/1/progress', 'token' => 'admin', 'method' => 'GET', 'category' => 'Progress Tracking'],
    ['url' => 'api/learning-paths/1/progress?user_id=2', 'token' => 'admin', 'method' => 'GET', 'category' => 'Learning Paths'],
];

echo "🧪 Testing " . count($testEndpoints) . " endpoints with proper token assignments:\n\n";

$results = [
    'total' => 0,
    'success' => 0,
    'server_errors' => 0,
    'permission_errors' => 0,
    'not_found_errors' => 0,
    'validation_errors' => 0,
    'auth_errors' => 0,
    'other_errors' => 0
];

$categoryResults = [];

foreach ($testEndpoints as $test) {
    $results['total']++;
    $token = $authTokens[$test['token']];
    $response = makeRequest($test['url'], $test['method'], $token);
    $status = $response['status'];
    
    $category = $test['category'];
    if (!isset($categoryResults[$category])) {
        $categoryResults[$category] = ['total' => 0, 'success' => 0];
    }
    $categoryResults[$category]['total']++;
    
    if ($status == 200 || $status == 201) {
        $results['success']++;
        $categoryResults[$category]['success']++;
        echo "✅ {$test['url']} ({$category})\n";
    } elseif ($status >= 500) {
        $results['server_errors']++;
        echo "❌ {$test['url']} - SERVER ERROR ($status)\n";
    } elseif ($status == 403) {
        $results['permission_errors']++;
        echo "⚠️  {$test['url']} - PERMISSION ($status)\n";
    } elseif ($status == 404) {
        $results['not_found_errors']++;
        echo "🔍 {$test['url']} - NOT FOUND ($status)\n";
    } elseif ($status == 422) {
        $results['validation_errors']++;
        echo "📝 {$test['url']} - VALIDATION ($status)\n";
    } elseif ($status == 401) {
        $results['auth_errors']++;
        echo "🔐 {$test['url']} - AUTH ERROR ($status)\n";
    } else {
        $results['other_errors']++;
        echo "❓ {$test['url']} - OTHER ($status)\n";
    }
}

echo "\n=======================================\n";
echo "📊 PROPER TOKEN TEST RESULTS\n";
echo "=======================================\n";

echo "📈 OVERALL STATISTICS:\n";
echo "Total Endpoints Tested: {$results['total']}\n";
echo "✅ Success (200/201): {$results['success']}\n";
echo "❌ Server Errors (500+): {$results['server_errors']}\n";
echo "⚠️  Permission Errors (403): {$results['permission_errors']}\n";
echo "🔍 Not Found (404): {$results['not_found_errors']}\n";
echo "📝 Validation Errors (422): {$results['validation_errors']}\n";
echo "🔐 Auth Errors (401): {$results['auth_errors']}\n";
echo "❓ Other Errors: {$results['other_errors']}\n\n";

$successRate = round(($results['success'] / $results['total']) * 100, 2);
echo "📈 Success Rate: $successRate%\n\n";

echo "📊 BY CATEGORY:\n";
foreach ($categoryResults as $category => $categoryResult) {
    $categoryRate = round(($categoryResult['success'] / $categoryResult['total']) * 100, 2);
    echo "• $category: {$categoryResult['success']}/{$categoryResult['total']} ({$categoryRate}%)\n";
}

echo "\n=======================================\n";
echo "🎯 ASSESSMENT\n";
echo "=======================================\n";

if ($successRate >= 90) {
    echo "🎉 EXCELLENT! 90%+ success rate - PRODUCTION READY!\n";
} elseif ($successRate >= 80) {
    echo "✅ VERY GOOD! 80%+ success rate - NEARLY PRODUCTION READY!\n";
} elseif ($successRate >= 70) {
    echo "👍 GOOD! 70%+ success rate - CORE FUNCTIONALITY READY!\n";
} else {
    echo "📈 NEEDS IMPROVEMENT! Continue fixing remaining issues.\n";
}

echo "\n🚀 Platform Status: " . ($successRate >= 80 ? "READY FOR DEPLOYMENT" : "NEEDS MORE WORK") . "\n";
echo "=======================================\n";
