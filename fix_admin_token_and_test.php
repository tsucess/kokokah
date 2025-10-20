<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "🔧 FIXING ADMIN TOKEN AND TESTING ALL ERROR TYPES\n";
echo "=================================================\n\n";

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

echo "1. Testing current admin token...\n";

// Load current tokens
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

// Test current admin token
$response = makeRequest('api/user', 'GET', $authTokens['admin']);
if ($response['status'] == 401) {
    echo "   ❌ Current admin token is invalid. Regenerating...\n";
    
    // Find or create admin user
    $admin = User::where('email', 'admin@kokokah.com')->first();
    if (!$admin) {
        echo "   Creating new admin user...\n";
        $admin = new User();
        $admin->first_name = 'Admin';
        $admin->last_name = 'User';
        $admin->email = 'admin@kokokah.com';
        $admin->password = Hash::make('password123');
        $admin->role = 'admin';
        $admin->is_active = true;
        $admin->email_verified_at = now();
        $admin->save();
    }
    
    // Delete old tokens and create new one
    $admin->tokens()->delete();
    $newAdminToken = $admin->createToken('admin-token')->plainTextToken;
    
    // Update tokens file
    $authTokens['admin'] = $newAdminToken;
    $tokenContent = "ADMIN_TOKEN={$authTokens['admin']}\n";
    $tokenContent .= "STUDENT_TOKEN={$authTokens['student']}\n";
    $tokenContent .= "INSTRUCTOR_TOKEN={$authTokens['instructor']}\n";
    file_put_contents('auth_tokens.txt', $tokenContent);
    
    echo "   ✅ New admin token generated and saved\n";
} else {
    echo "   ✅ Current admin token is valid\n";
}

echo "\n2. Testing comprehensive endpoint coverage with proper tokens...\n\n";

// Comprehensive test with all error types
$comprehensiveTests = [
    // Core student functionality (should all work)
    ['url' => 'api/user', 'token' => 'student', 'category' => 'Student Core'],
    ['url' => 'api/courses', 'token' => 'student', 'category' => 'Student Core'],
    ['url' => 'api/enrollments', 'token' => 'student', 'category' => 'Student Core'],
    ['url' => 'api/users/profile', 'token' => 'student', 'category' => 'Student Core'],
    ['url' => 'api/certificates', 'token' => 'student', 'category' => 'Student Core'],
    ['url' => 'api/wallet', 'token' => 'student', 'category' => 'Student Core'],
    ['url' => 'api/payments/history', 'token' => 'student', 'category' => 'Student Core'],
    ['url' => 'api/search?q=test', 'token' => 'student', 'category' => 'Student Core'],
    
    // Admin functionality (should work with fixed token)
    ['url' => 'api/user', 'token' => 'admin', 'category' => 'Admin Core'],
    ['url' => 'api/admin/dashboard', 'token' => 'admin', 'category' => 'Admin Core'],
    ['url' => 'api/admin/users', 'token' => 'admin', 'category' => 'Admin Core'],
    ['url' => 'api/admin/analytics', 'token' => 'admin', 'category' => 'Admin Core'],
    ['url' => 'api/admin/settings', 'token' => 'admin', 'category' => 'Admin Core'],
    ['url' => 'api/settings', 'token' => 'admin', 'category' => 'Admin Core'],
    
    // Course management (admin)
    ['url' => 'api/courses/1', 'token' => 'admin', 'category' => 'Course Management'],
    ['url' => 'api/courses/1/students', 'token' => 'admin', 'category' => 'Course Management'],
    ['url' => 'api/courses/1/analytics', 'token' => 'admin', 'category' => 'Course Management'],
    ['url' => 'api/courses/1/lessons', 'token' => 'admin', 'category' => 'Course Management'],
    ['url' => 'api/courses/1/assignments', 'token' => 'admin', 'category' => 'Course Management'],
    ['url' => 'api/courses/1/forum', 'token' => 'admin', 'category' => 'Course Management'],
    
    // Assessment management (admin)
    ['url' => 'api/assignments/1', 'token' => 'admin', 'category' => 'Assessment'],
    ['url' => 'api/assignments/1/submissions', 'token' => 'admin', 'category' => 'Assessment'],
    ['url' => 'api/quizzes/1', 'token' => 'admin', 'category' => 'Assessment'],
    ['url' => 'api/quizzes/1/analytics', 'token' => 'admin', 'category' => 'Assessment'],
    
    // Previously failing endpoints
    ['url' => 'api/enrollments/1/progress', 'token' => 'admin', 'category' => 'Progress Tracking'],
    ['url' => 'api/certificates/1/download', 'token' => 'admin', 'category' => 'Certificates'],
    ['url' => 'api/learning-paths/1/progress?user_id=2', 'token' => 'admin', 'category' => 'Learning Paths'],
    ['url' => 'api/chat/sessions/1', 'token' => 'admin', 'category' => 'Communication'],
    ['url' => 'api/files/download/1', 'token' => 'admin', 'category' => 'File Management'],
    
    // Instructor functionality
    ['url' => 'api/instructor/courses', 'token' => 'instructor', 'category' => 'Instructor'],
    ['url' => 'api/dashboard/instructor', 'token' => 'instructor', 'category' => 'Instructor'],
];

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
$errorDetails = [];

foreach ($comprehensiveTests as $test) {
    $results['total']++;
    $token = $authTokens[$test['token']];
    $response = makeRequest($test['url'], 'GET', $token);
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
        $errorDetails[] = "SERVER ERROR: {$test['url']} - $status";
        echo "❌ {$test['url']} - SERVER ERROR ($status)\n";
    } elseif ($status == 403) {
        $results['permission_errors']++;
        $errorDetails[] = "PERMISSION: {$test['url']} - $status";
        echo "⚠️  {$test['url']} - PERMISSION ($status)\n";
    } elseif ($status == 404) {
        $results['not_found_errors']++;
        $errorDetails[] = "NOT FOUND: {$test['url']} - $status";
        echo "🔍 {$test['url']} - NOT FOUND ($status)\n";
    } elseif ($status == 422) {
        $results['validation_errors']++;
        $errorDetails[] = "VALIDATION: {$test['url']} - $status";
        echo "📝 {$test['url']} - VALIDATION ($status)\n";
    } elseif ($status == 401) {
        $results['auth_errors']++;
        $errorDetails[] = "AUTH ERROR: {$test['url']} - $status";
        echo "🔐 {$test['url']} - AUTH ERROR ($status)\n";
    } else {
        $results['other_errors']++;
        $errorDetails[] = "OTHER: {$test['url']} - $status";
        echo "❓ {$test['url']} - OTHER ($status)\n";
    }
}

echo "\n=================================================\n";
echo "📊 COMPREHENSIVE ERROR TYPE ANALYSIS\n";
echo "=================================================\n";

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

echo "\n🔍 REMAINING ISSUES TO FIX:\n";
foreach ($errorDetails as $error) {
    echo "  • $error\n";
}

echo "\n=================================================\n";
echo "🎯 FINAL ASSESSMENT\n";
echo "=================================================\n";

if ($successRate >= 90) {
    echo "🎉 EXCELLENT! 90%+ success rate - PRODUCTION READY!\n";
} elseif ($successRate >= 80) {
    echo "✅ VERY GOOD! 80%+ success rate - NEARLY PRODUCTION READY!\n";
} elseif ($successRate >= 70) {
    echo "👍 GOOD! 70%+ success rate - CORE FUNCTIONALITY READY!\n";
} else {
    echo "📈 PROGRESS MADE! Continue fixing remaining issues.\n";
}

echo "\n🚀 Platform Status: " . ($successRate >= 75 ? "READY FOR BETA LAUNCH" : "NEEDS MORE FIXES") . "\n";
echo "=================================================\n";
