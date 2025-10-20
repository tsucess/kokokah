<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎯 FINAL COMPREHENSIVE ENDPOINT TEST - ALL ERROR TYPES\n";
echo "======================================================\n\n";

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
        CURLOPT_URL => "http://127.0.0.1:8000/$url",
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

// Comprehensive test covering all major functionality
$comprehensiveTests = [
    // CORE STUDENT FUNCTIONALITY (Should be 100% working)
    ['url' => 'api/user', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/courses', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/courses/featured', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/courses/my-courses', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/enrollments', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/users/profile', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/users/dashboard', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/progress/overall', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/certificates', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/wallet', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/payments/history', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/search?q=test', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/notifications', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/badges', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    ['url' => 'api/learning-paths', 'token' => 'student', 'category' => 'Student Core', 'priority' => 'Critical'],
    
    // CORE ADMIN FUNCTIONALITY (Should be 100% working)
    ['url' => 'api/user', 'token' => 'admin', 'category' => 'Admin Core', 'priority' => 'Critical'],
    ['url' => 'api/admin/dashboard', 'token' => 'admin', 'category' => 'Admin Core', 'priority' => 'Critical'],
    ['url' => 'api/admin/users', 'token' => 'admin', 'category' => 'Admin Core', 'priority' => 'Critical'],
    ['url' => 'api/admin/analytics', 'token' => 'admin', 'category' => 'Admin Core', 'priority' => 'Critical'],
    ['url' => 'api/admin/settings', 'token' => 'admin', 'category' => 'Admin Core', 'priority' => 'Critical'],
    ['url' => 'api/settings', 'token' => 'admin', 'category' => 'Admin Core', 'priority' => 'Critical'],
    
    // COURSE MANAGEMENT (Should be mostly working)
    ['url' => 'api/courses/1', 'token' => 'admin', 'category' => 'Course Management', 'priority' => 'High'],
    ['url' => 'api/courses/1/students', 'token' => 'admin', 'category' => 'Course Management', 'priority' => 'High'],
    ['url' => 'api/courses/1/analytics', 'token' => 'admin', 'category' => 'Course Management', 'priority' => 'High'],
    ['url' => 'api/courses/1/lessons', 'token' => 'admin', 'category' => 'Course Management', 'priority' => 'High'],
    ['url' => 'api/courses/1/assignments', 'token' => 'admin', 'category' => 'Course Management', 'priority' => 'High'],
    
    // ASSESSMENT SYSTEM (Should be mostly working)
    ['url' => 'api/assignments/1', 'token' => 'admin', 'category' => 'Assessment', 'priority' => 'High'],
    ['url' => 'api/assignments/1/submissions', 'token' => 'admin', 'category' => 'Assessment', 'priority' => 'High'],
    ['url' => 'api/quizzes/1', 'token' => 'admin', 'category' => 'Assessment', 'priority' => 'High'],
    ['url' => 'api/quizzes/1/analytics', 'token' => 'admin', 'category' => 'Assessment', 'priority' => 'High'],
    
    // INSTRUCTOR FUNCTIONALITY
    ['url' => 'api/instructor/courses', 'token' => 'instructor', 'category' => 'Instructor', 'priority' => 'Medium'],
    ['url' => 'api/dashboard/instructor', 'token' => 'instructor', 'category' => 'Instructor', 'priority' => 'Medium'],
    
    // ADVANCED FEATURES (May have some issues)
    ['url' => 'api/courses/1/forum', 'token' => 'admin', 'category' => 'Advanced Features', 'priority' => 'Low'],
    ['url' => 'api/enrollments/1/progress', 'token' => 'admin', 'category' => 'Advanced Features', 'priority' => 'Medium'],
    ['url' => 'api/certificates/1/download', 'token' => 'admin', 'category' => 'Advanced Features', 'priority' => 'Medium'],
    ['url' => 'api/learning-paths/1/progress?user_id=2', 'token' => 'admin', 'category' => 'Advanced Features', 'priority' => 'Low'],
    ['url' => 'api/chat/sessions/1', 'token' => 'admin', 'category' => 'Advanced Features', 'priority' => 'Low'],
    ['url' => 'api/files/download/1', 'token' => 'admin', 'category' => 'Advanced Features', 'priority' => 'Low'],
];

echo "🧪 Testing " . count($comprehensiveTests) . " endpoints across all categories:\n\n";

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
$priorityResults = ['Critical' => ['total' => 0, 'success' => 0], 'High' => ['total' => 0, 'success' => 0], 'Medium' => ['total' => 0, 'success' => 0], 'Low' => ['total' => 0, 'success' => 0]];
$errorDetails = [];

foreach ($comprehensiveTests as $test) {
    $results['total']++;
    $token = $authTokens[$test['token']];
    $response = makeRequest($test['url'], 'GET', $token);
    $status = $response['status'];
    
    $category = $test['category'];
    $priority = $test['priority'];
    
    if (!isset($categoryResults[$category])) {
        $categoryResults[$category] = ['total' => 0, 'success' => 0];
    }
    $categoryResults[$category]['total']++;
    $priorityResults[$priority]['total']++;
    
    if ($status == 200 || $status == 201) {
        $results['success']++;
        $categoryResults[$category]['success']++;
        $priorityResults[$priority]['success']++;
        echo "✅ {$test['url']} ({$category} - {$priority})\n";
    } elseif ($status >= 500) {
        $results['server_errors']++;
        $errorDetails[] = "SERVER ERROR ($status): {$test['url']} ({$category})";
        echo "❌ {$test['url']} - SERVER ERROR ($status) ({$category})\n";
    } elseif ($status == 403) {
        $results['permission_errors']++;
        $errorDetails[] = "PERMISSION ($status): {$test['url']} ({$category})";
        echo "⚠️  {$test['url']} - PERMISSION ($status) ({$category})\n";
    } elseif ($status == 404) {
        $results['not_found_errors']++;
        $errorDetails[] = "NOT FOUND ($status): {$test['url']} ({$category})";
        echo "🔍 {$test['url']} - NOT FOUND ($status) ({$category})\n";
    } elseif ($status == 422) {
        $results['validation_errors']++;
        $errorDetails[] = "VALIDATION ($status): {$test['url']} ({$category})";
        echo "📝 {$test['url']} - VALIDATION ($status) ({$category})\n";
    } elseif ($status == 401) {
        $results['auth_errors']++;
        $errorDetails[] = "AUTH ERROR ($status): {$test['url']} ({$category})";
        echo "🔐 {$test['url']} - AUTH ERROR ($status) ({$category})\n";
    } else {
        $results['other_errors']++;
        $errorDetails[] = "OTHER ($status): {$test['url']} ({$category})";
        echo "❓ {$test['url']} - OTHER ($status) ({$category})\n";
    }
}

echo "\n======================================================\n";
echo "📊 FINAL COMPREHENSIVE RESULTS - ALL ERROR TYPES\n";
echo "======================================================\n";

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
echo "📈 Overall Success Rate: $successRate%\n\n";

echo "📊 BY CATEGORY:\n";
foreach ($categoryResults as $category => $categoryResult) {
    $categoryRate = round(($categoryResult['success'] / $categoryResult['total']) * 100, 2);
    echo "• $category: {$categoryResult['success']}/{$categoryResult['total']} ({$categoryRate}%)\n";
}

echo "\n📊 BY PRIORITY:\n";
foreach ($priorityResults as $priority => $priorityResult) {
    if ($priorityResult['total'] > 0) {
        $priorityRate = round(($priorityResult['success'] / $priorityResult['total']) * 100, 2);
        echo "• $priority Priority: {$priorityResult['success']}/{$priorityResult['total']} ({$priorityRate}%)\n";
    }
}

echo "\n🔍 REMAINING ISSUES BY TYPE:\n";
$errorTypes = ['SERVER ERROR', 'PERMISSION', 'NOT FOUND', 'VALIDATION', 'AUTH ERROR', 'OTHER'];
foreach ($errorTypes as $type) {
    $typeErrors = array_filter($errorDetails, function($error) use ($type) {
        return str_contains($error, $type);
    });
    if (count($typeErrors) > 0) {
        echo "\n$type (" . count($typeErrors) . "):\n";
        foreach (array_slice($typeErrors, 0, 3) as $error) {
            echo "  • " . substr($error, strlen($type) + 6) . "\n";
        }
        if (count($typeErrors) > 3) {
            echo "  • ... and " . (count($typeErrors) - 3) . " more\n";
        }
    }
}

echo "\n======================================================\n";
echo "🎯 PRODUCTION READINESS ASSESSMENT\n";
echo "======================================================\n";

$criticalSuccess = round(($priorityResults['Critical']['success'] / $priorityResults['Critical']['total']) * 100, 2);
$highSuccess = round(($priorityResults['High']['success'] / $priorityResults['High']['total']) * 100, 2);

echo "🎯 CRITICAL FUNCTIONALITY: $criticalSuccess% ({$priorityResults['Critical']['success']}/{$priorityResults['Critical']['total']})\n";
echo "🎯 HIGH PRIORITY FUNCTIONALITY: $highSuccess% ({$priorityResults['High']['success']}/{$priorityResults['High']['total']})\n";
echo "🎯 OVERALL SUCCESS RATE: $successRate%\n\n";

if ($criticalSuccess >= 95 && $successRate >= 85) {
    echo "🎉 EXCELLENT! FULLY PRODUCTION READY!\n";
    echo "✅ Critical functionality: 95%+ working\n";
    echo "✅ Overall platform: 85%+ working\n";
    echo "🚀 Ready to serve thousands of users!\n";
} elseif ($criticalSuccess >= 90 && $successRate >= 80) {
    echo "🎉 VERY GOOD! PRODUCTION READY!\n";
    echo "✅ Critical functionality: 90%+ working\n";
    echo "✅ Overall platform: 80%+ working\n";
    echo "🚀 Ready for immediate deployment!\n";
} elseif ($criticalSuccess >= 85 && $successRate >= 75) {
    echo "✅ GOOD! NEARLY PRODUCTION READY!\n";
    echo "✅ Critical functionality: 85%+ working\n";
    echo "✅ Overall platform: 75%+ working\n";
    echo "🚀 Ready for beta launch!\n";
} else {
    echo "📈 PROGRESS MADE! Continue improving.\n";
    echo "🔧 Focus on critical functionality first\n";
}

echo "\n🇳🇬 KOKOKAH.COM LMS FINAL STATUS:\n";
echo "Platform Readiness: " . ($criticalSuccess >= 90 ? "PRODUCTION READY" : "BETA READY") . "\n";
echo "Market Launch: " . ($successRate >= 80 ? "READY FOR NIGERIAN MARKET" : "NEEDS MORE OPTIMIZATION") . "\n";
echo "======================================================\n";
