<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 COMPREHENSIVE TEST OF ALL 200+ ENDPOINTS\n";
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
    
    echo sprintf("%-50s %s %d\n", $name, $status, $httpCode);
    
    return $success;
}

$totalTests = 0;
$passedTests = 0;

echo "🎯 TESTING ALL ENDPOINTS FROM API DOCUMENTATION:\n\n";

// Authentication Endpoints (6 endpoints)
echo "🔐 AUTHENTICATION ENDPOINTS:\n";
$authTests = [
    ['Register', 'POST', '/register', null, ['first_name' => 'Test', 'last_name' => 'User', 'email' => 'test@test.com', 'password' => 'password']],
    ['Login', 'POST', '/login', null, ['email' => 'admin@kokokah.com', 'password' => 'password']],
    ['Get Current User', 'GET', '/user', $studentToken],
    ['Logout', 'POST', '/logout', $studentToken],
    ['Forgot Password', 'POST', '/forgot-password', null, ['email' => 'admin@kokokah.com']],
    ['Reset Password', 'POST', '/reset-password', null, ['token' => 'test', 'email' => 'admin@kokokah.com', 'password' => 'newpassword']],
];

foreach ($authTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

echo "\n📚 COURSE MANAGEMENT ENDPOINTS:\n";
$courseTests = [
    ['Get All Courses', 'GET', '/courses'],
    ['Get Single Course', 'GET', '/courses/11'],
    ['Create Course', 'POST', '/courses', $adminToken, ['title' => 'Test Course', 'description' => 'Test']],
    ['Update Course', 'PUT', '/courses/11', $adminToken, ['title' => 'Updated Course']],
    ['Delete Course', 'DELETE', '/courses/999', $adminToken],
    ['Enroll in Course', 'POST', '/courses/11/enroll', $studentToken],
    ['Unenroll from Course', 'DELETE', '/courses/11/unenroll', $studentToken],
    ['Get My Courses', 'GET', '/courses/my-courses', $studentToken],
    ['Search Courses', 'GET', '/courses/search?q=math'],
    ['Get Featured Courses', 'GET', '/courses/featured'],
    ['Get Popular Courses', 'GET', '/courses/popular'],
    ['Get Course Students', 'GET', '/courses/11/students', $adminToken],
    ['Get Course Analytics', 'GET', '/courses/11/analytics', $adminToken],
    ['Publish Course', 'POST', '/courses/11/publish', $adminToken],
    ['Unpublish Course', 'POST', '/courses/11/unpublish', $adminToken],
];

foreach ($courseTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

echo "\n📖 LESSON MANAGEMENT ENDPOINTS:\n";
$lessonTests = [
    ['Get Course Lessons', 'GET', '/courses/11/lessons', $studentToken],
    ['Create Lesson', 'POST', '/courses/11/lessons', $adminToken, ['title' => 'Test Lesson', 'content' => 'Test']],
    ['Get Single Lesson', 'GET', '/lessons/1', $studentToken],
    ['Update Lesson', 'PUT', '/lessons/1', $adminToken, ['title' => 'Updated Lesson']],
    ['Delete Lesson', 'DELETE', '/lessons/999', $adminToken],
    ['Mark Lesson Complete', 'POST', '/lessons/1/complete', $studentToken],
    ['Get Lesson Progress', 'GET', '/lessons/1/progress', $studentToken],
    ['Track Watch Time', 'POST', '/lessons/1/watch-time', $studentToken, ['watch_time' => 300]],
    ['Get Lesson Attachments', 'GET', '/lessons/1/attachments', $studentToken],
];

foreach ($lessonTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

echo "\n📝 QUIZ MANAGEMENT ENDPOINTS:\n";
$quizTests = [
    ['Get Lesson Quizzes', 'GET', '/lessons/1/quizzes', $studentToken],
    ['Create Quiz', 'POST', '/lessons/1/quizzes', $adminToken, ['title' => 'Test Quiz', 'description' => 'Test']],
    ['Get Single Quiz', 'GET', '/quizzes/1', $studentToken],
    ['Update Quiz', 'PUT', '/quizzes/1', $adminToken, ['title' => 'Updated Quiz']],
    ['Delete Quiz', 'DELETE', '/quizzes/999', $adminToken],
    ['Start Quiz Attempt', 'POST', '/quizzes/1/start', $studentToken],
    ['Submit Quiz', 'POST', '/quizzes/1/submit', $studentToken, ['answers' => []]],
    ['Get Quiz Results', 'GET', '/quizzes/1/results', $studentToken],
    ['Get Quiz Analytics', 'GET', '/quizzes/1/analytics', $adminToken],
];

foreach ($quizTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

echo "\n📋 ASSIGNMENT MANAGEMENT ENDPOINTS:\n";
$assignmentTests = [
    ['Get Course Assignments', 'GET', '/courses/11/assignments', $studentToken],
    ['Create Assignment', 'POST', '/courses/11/assignments', $adminToken, ['title' => 'Test Assignment', 'instructions' => 'Test']],
    ['Get Single Assignment', 'GET', '/assignments/1', $studentToken],
    ['Update Assignment', 'PUT', '/assignments/1', $adminToken, ['title' => 'Updated Assignment']],
    ['Delete Assignment', 'DELETE', '/assignments/999', $adminToken],
    ['Submit Assignment', 'POST', '/assignments/1/submit', $studentToken, ['file_url' => 'test.pdf']],
    ['Get Assignment Submissions', 'GET', '/assignments/1/submissions', $adminToken],
    ['Get Assignment Grades', 'GET', '/assignments/1/grades', $adminToken],
    ['Grade Submission', 'PUT', '/submissions/1/grade', $adminToken, ['grade' => 85, 'feedback' => 'Good work']],
];

foreach ($assignmentTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

echo "\n👥 USER MANAGEMENT ENDPOINTS:\n";
$userTests = [
    ['Get All Users (Admin)', 'GET', '/admin/users', $adminToken],
    ['Search Users', 'GET', '/search/users?q=test', $studentToken],
    ['Get User Profile', 'GET', '/users/profile', $studentToken],
    ['Update User Profile', 'PUT', '/users/profile', $studentToken, ['first_name' => 'Updated']],
    ['Get User Dashboard', 'GET', '/users/dashboard', $studentToken],
    ['Get User Achievements', 'GET', '/users/achievements', $studentToken],
    ['Get Learning Statistics', 'GET', '/users/learning-stats', $studentToken],
    ['Update User Preferences', 'PUT', '/users/preferences', $studentToken, ['theme' => 'dark']],
    ['Get User Notifications', 'GET', '/users/notifications', $studentToken],
    ['Mark Notifications Read', 'POST', '/users/notifications/read', $studentToken],
    ['Change Password', 'POST', '/users/change-password', $studentToken, ['current_password' => 'old', 'new_password' => 'new']],
    ['Ban User', 'POST', '/admin/users/21/ban', $adminToken],
    ['Unban User', 'POST', '/admin/users/21/unban', $adminToken],
    ['Get User Activity', 'GET', '/audit/users/21/activity', $adminToken],
    ['Get User Badges', 'GET', '/users/21/badges', $studentToken],
];

foreach ($userTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

echo "\n📊 DASHBOARD ENDPOINTS:\n";
$dashboardTests = [
    ['Student Dashboard', 'GET', '/dashboard/student', $studentToken],
    ['Instructor Dashboard', 'GET', '/dashboard/instructor', $adminToken],
    ['Admin Dashboard', 'GET', '/dashboard/admin', $adminToken],
    ['Dashboard Analytics', 'GET', '/dashboard/analytics', $studentToken],
];

foreach ($dashboardTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

echo "\n💰 PAYMENT MANAGEMENT ENDPOINTS:\n";
$paymentTests = [
    ['Get Payment Gateways', 'GET', '/payments/gateways', $studentToken],
    ['Initialize Wallet Deposit', 'POST', '/payments/deposit', $studentToken, ['amount' => 1000]],
    ['Initialize Course Payment', 'POST', '/payments/purchase-course', $studentToken, ['course_id' => 11, 'amount' => 5000]],
    ['Get Payment History', 'GET', '/payments/history', $studentToken],
    ['Get Single Payment', 'GET', '/payments/1', $studentToken],
    ['Payment Webhook', 'POST', '/payments/webhook/paystack'],
    ['Payment Callback', 'GET', '/payments/callback/paystack'],
    ['Payment Success', 'GET', '/payments/success/paystack'],
    ['Payment Cancel', 'GET', '/payments/cancel/paystack'],
];

foreach ($paymentTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

echo "\n💳 WALLET MANAGEMENT ENDPOINTS:\n";
$walletTests = [
    ['Get Wallet Info', 'GET', '/wallet', $studentToken],
    ['Transfer Funds', 'POST', '/wallet/transfer', $studentToken, ['recipient_id' => 22, 'amount' => 100]],
    ['Purchase Course with Wallet', 'POST', '/wallet/purchase-course', $studentToken, ['course_id' => 11]],
    ['Get Wallet Transactions', 'GET', '/wallet/transactions', $studentToken],
    ['Get Wallet Rewards', 'GET', '/wallet/rewards', $studentToken],
    ['Claim Login Reward', 'POST', '/wallet/claim-login-reward', $studentToken],
    ['Check Affordability', 'POST', '/wallet/check-affordability', $studentToken, ['amount' => 1000]],
];

foreach ($walletTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Continue with more endpoint categories...
echo "\n============================================================\n";
echo "📊 PARTIAL TEST RESULTS (First 8 Categories)\n";
echo "============================================================\n";
echo "Total Tests: $totalTests\n";
echo "✅ Passed: $passedTests\n";
echo "❌ Failed: " . ($totalTests - $passedTests) . "\n";
echo "📈 Success Rate: " . round(($passedTests / $totalTests) * 100, 2) . "%\n";
echo "============================================================\n";
echo "🔄 This is a partial test. Creating additional test files for remaining categories...\n";
