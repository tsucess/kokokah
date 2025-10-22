<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎯 COMPREHENSIVE TEST - ALL 200+ ENDPOINTS IN PROJECT\n";
echo "====================================================\n\n";

// Load authentication tokens
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

echo "🔐 Using tokens:\n";
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

// ALL ENDPOINTS FROM THE PROJECT - COMPREHENSIVE LIST
$allEndpoints = [
    // PUBLIC ENDPOINTS (No authentication required)
    ['url' => '', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'API root'],
    ['url' => 'category', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get all categories'],
    ['url' => 'category/1', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get single category'],
    ['url' => 'courses', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get all courses'],
    ['url' => 'courses/search?q=test', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Search courses'],
    ['url' => 'courses/featured', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get featured courses'],
    ['url' => 'courses/popular', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get popular courses'],
    ['url' => 'courses/1', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get single course'],
    ['url' => 'certificates/verify/CERT-123', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Verify certificate'],
    ['url' => 'settings/public', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get public settings'],
    
    // AUTHENTICATION ENDPOINTS
    ['url' => 'register', 'method' => 'POST', 'token' => null, 'category' => 'Auth', 'description' => 'Register user', 'data' => ['first_name' => 'Test', 'last_name' => 'User', 'email' => 'test@example.com', 'password' => 'password123', 'password_confirmation' => 'password123']],
    ['url' => 'login', 'method' => 'POST', 'token' => null, 'category' => 'Auth', 'description' => 'Login user', 'data' => ['email' => 'student1@kokokah.com', 'password' => 'password123']],
    ['url' => 'forgot-password', 'method' => 'POST', 'token' => null, 'category' => 'Auth', 'description' => 'Forgot password', 'data' => ['email' => 'student1@kokokah.com']],
    ['url' => 'reset-password', 'method' => 'POST', 'token' => null, 'category' => 'Auth', 'description' => 'Reset password', 'data' => ['email' => 'student1@kokokah.com', 'password' => 'newpassword', 'password_confirmation' => 'newpassword', 'token' => 'dummy-token']],
    ['url' => 'user', 'method' => 'GET', 'token' => 'student', 'category' => 'Auth', 'description' => 'Get current user'],
    ['url' => 'logout', 'method' => 'POST', 'token' => 'student', 'category' => 'Auth', 'description' => 'Logout user'],
    
    // PAYMENT WEBHOOKS (Public)
    ['url' => 'payments/webhook/paystack', 'method' => 'POST', 'token' => null, 'category' => 'Payment Webhooks', 'description' => 'Payment webhook'],
    ['url' => 'payments/callback/paystack', 'method' => 'GET', 'token' => null, 'category' => 'Payment Webhooks', 'description' => 'Payment callback'],
    ['url' => 'payments/success/paystack', 'method' => 'GET', 'token' => null, 'category' => 'Payment Webhooks', 'description' => 'Payment success'],
    ['url' => 'payments/cancel/paystack', 'method' => 'GET', 'token' => null, 'category' => 'Payment Webhooks', 'description' => 'Payment cancel'],
    
    // WALLET MANAGEMENT
    ['url' => 'wallet', 'method' => 'GET', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Get wallet info'],
    ['url' => 'wallet/transfer', 'method' => 'POST', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Transfer funds'],
    ['url' => 'wallet/purchase-course', 'method' => 'POST', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Purchase course with wallet'],
    ['url' => 'wallet/transactions', 'method' => 'GET', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Get wallet transactions'],
    ['url' => 'wallet/rewards', 'method' => 'GET', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Get wallet rewards'],
    ['url' => 'wallet/claim-login-reward', 'method' => 'POST', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Claim login reward'],
    ['url' => 'wallet/check-affordability', 'method' => 'POST', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Check affordability'],
    
    // PAYMENT MANAGEMENT
    ['url' => 'payments/gateways', 'method' => 'GET', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get payment gateways'],
    ['url' => 'payments/deposit', 'method' => 'POST', 'token' => 'student', 'category' => 'Payment', 'description' => 'Initialize wallet deposit'],
    ['url' => 'payments/purchase-course', 'method' => 'POST', 'token' => 'student', 'category' => 'Payment', 'description' => 'Initialize course payment'],
    ['url' => 'payments/history', 'method' => 'GET', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get payment history'],
    ['url' => 'payments/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get single payment'],
    
    // COURSE MANAGEMENT (Student accessible)
    ['url' => 'courses/my-courses', 'method' => 'GET', 'token' => 'student', 'category' => 'Course Management', 'description' => 'Get my courses'],
    ['url' => 'courses/1/enroll', 'method' => 'POST', 'token' => 'student', 'category' => 'Course Management', 'description' => 'Enroll in course'],
    ['url' => 'courses/1/unenroll', 'method' => 'DELETE', 'token' => 'student', 'category' => 'Course Management', 'description' => 'Unenroll from course'],
    
    // COURSE MANAGEMENT (Instructor/Admin)
    ['url' => 'courses', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Create course'],
    ['url' => 'courses/1', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Update course'],
    ['url' => 'courses/1', 'method' => 'DELETE', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Delete course'],
    ['url' => 'courses/1/students', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Get course students'],
    ['url' => 'courses/1/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Get course analytics'],
    ['url' => 'courses/1/publish', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Publish course'],
    ['url' => 'courses/1/unpublish', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Unpublish course'],
    
    // LESSON MANAGEMENT
    ['url' => 'courses/1/lessons', 'method' => 'GET', 'token' => 'student', 'category' => 'Lesson Management', 'description' => 'Get course lessons'],
    ['url' => 'courses/1/lessons', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Lesson Management', 'description' => 'Create lesson'],
    ['url' => 'lessons/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Lesson Management', 'description' => 'Get single lesson'],
    ['url' => 'lessons/1', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Lesson Management', 'description' => 'Update lesson'],
    ['url' => 'lessons/1', 'method' => 'DELETE', 'token' => 'instructor', 'category' => 'Lesson Management', 'description' => 'Delete lesson'],
    ['url' => 'lessons/1/complete', 'method' => 'POST', 'token' => 'student', 'category' => 'Lesson Management', 'description' => 'Mark lesson complete'],
    ['url' => 'lessons/1/progress', 'method' => 'GET', 'token' => 'student', 'category' => 'Lesson Management', 'description' => 'Get lesson progress'],
    ['url' => 'lessons/1/watch-time', 'method' => 'POST', 'token' => 'student', 'category' => 'Lesson Management', 'description' => 'Track watch time'],
    ['url' => 'lessons/1/attachments', 'method' => 'GET', 'token' => 'student', 'category' => 'Lesson Management', 'description' => 'Get lesson attachments'],
    
    // ENROLLMENT MANAGEMENT
    ['url' => 'enrollments', 'method' => 'GET', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Get enrollments'],
    ['url' => 'enrollments', 'method' => 'POST', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Create enrollment'],
    ['url' => 'enrollments/certificates', 'method' => 'GET', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Get enrollment certificates'],
    ['url' => 'enrollments/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Get single enrollment'],
    ['url' => 'enrollments/1', 'method' => 'PUT', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Update enrollment'],
    ['url' => 'enrollments/1', 'method' => 'DELETE', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Delete enrollment'],
    ['url' => 'enrollments/1/progress', 'method' => 'GET', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Get enrollment progress'],
    ['url' => 'enrollments/1/complete', 'method' => 'POST', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Complete enrollment'],
    
    // USER MANAGEMENT
    ['url' => 'users/profile', 'method' => 'GET', 'token' => 'student', 'category' => 'User Management', 'description' => 'Get user profile'],
    ['url' => 'users/profile', 'method' => 'PUT', 'token' => 'student', 'category' => 'User Management', 'description' => 'Update user profile'],
    ['url' => 'users/dashboard', 'method' => 'GET', 'token' => 'student', 'category' => 'User Management', 'description' => 'Get user dashboard'],
    ['url' => 'users/achievements', 'method' => 'GET', 'token' => 'student', 'category' => 'User Management', 'description' => 'Get user achievements'],
    ['url' => 'users/learning-stats', 'method' => 'GET', 'token' => 'student', 'category' => 'User Management', 'description' => 'Get learning stats'],
    ['url' => 'users/preferences', 'method' => 'PUT', 'token' => 'student', 'category' => 'User Management', 'description' => 'Update user preferences'],
    ['url' => 'users/notifications', 'method' => 'GET', 'token' => 'student', 'category' => 'User Management', 'description' => 'Get user notifications'],
    ['url' => 'users/notifications/read', 'method' => 'POST', 'token' => 'student', 'category' => 'User Management', 'description' => 'Mark notifications read'],
    ['url' => 'users/change-password', 'method' => 'POST', 'token' => 'student', 'category' => 'User Management', 'description' => 'Change password'],
    ['url' => 'users/2/badges', 'method' => 'GET', 'token' => 'student', 'category' => 'User Management', 'description' => 'Get user badges'],
    
    // QUIZ MANAGEMENT
    ['url' => 'lessons/1/quizzes', 'method' => 'GET', 'token' => 'student', 'category' => 'Quiz Management', 'description' => 'Get lesson quizzes'],
    ['url' => 'lessons/1/quizzes', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Quiz Management', 'description' => 'Create quiz'],
    ['url' => 'quizzes/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Quiz Management', 'description' => 'Get single quiz'],
    ['url' => 'quizzes/1', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Quiz Management', 'description' => 'Update quiz'],
    ['url' => 'quizzes/1', 'method' => 'DELETE', 'token' => 'instructor', 'category' => 'Quiz Management', 'description' => 'Delete quiz'],
    ['url' => 'quizzes/1/start', 'method' => 'POST', 'token' => 'student', 'category' => 'Quiz Management', 'description' => 'Start quiz attempt'],
    ['url' => 'quizzes/1/submit', 'method' => 'POST', 'token' => 'student', 'category' => 'Quiz Management', 'description' => 'Submit quiz'],
    ['url' => 'quizzes/1/results', 'method' => 'GET', 'token' => 'student', 'category' => 'Quiz Management', 'description' => 'Get quiz results'],
    ['url' => 'quizzes/1/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Quiz Management', 'description' => 'Get quiz analytics'],
    
    // ASSIGNMENT MANAGEMENT
    ['url' => 'courses/1/assignments', 'method' => 'GET', 'token' => 'student', 'category' => 'Assignment Management', 'description' => 'Get course assignments'],
    ['url' => 'courses/1/assignments', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Assignment Management', 'description' => 'Create assignment'],
    ['url' => 'assignments/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Assignment Management', 'description' => 'Get single assignment'],
    ['url' => 'assignments/1', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Assignment Management', 'description' => 'Update assignment'],
    ['url' => 'assignments/1', 'method' => 'DELETE', 'token' => 'instructor', 'category' => 'Assignment Management', 'description' => 'Delete assignment'],
    ['url' => 'assignments/1/submit', 'method' => 'POST', 'token' => 'student', 'category' => 'Assignment Management', 'description' => 'Submit assignment'],
    ['url' => 'assignments/1/submissions', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Assignment Management', 'description' => 'Get assignment submissions'],
    ['url' => 'assignments/1/grades', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Assignment Management', 'description' => 'Get assignment grades'],
    ['url' => 'submissions/1/grade', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Assignment Management', 'description' => 'Grade submission'],
    
    // DASHBOARD ENDPOINTS
    ['url' => 'dashboard/student', 'method' => 'GET', 'token' => 'student', 'category' => 'Dashboard', 'description' => 'Student dashboard'],
    ['url' => 'dashboard/instructor', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Dashboard', 'description' => 'Instructor dashboard'],
    ['url' => 'dashboard/admin', 'method' => 'GET', 'token' => 'admin', 'category' => 'Dashboard', 'description' => 'Admin dashboard'],
    ['url' => 'dashboard/analytics', 'method' => 'GET', 'token' => 'student', 'category' => 'Dashboard', 'description' => 'Dashboard analytics'],
];

echo "🧪 Testing " . count($allEndpoints) . " endpoints across all categories:\n\n";

$results = [
    'total' => 0,
    'success' => 0,
    'failed' => 0,
    'by_status' => [],
    'by_category' => []
];

foreach ($allEndpoints as $endpoint) {
    $results['total']++;
    $token = $endpoint['token'] ? $authTokens[$endpoint['token']] : null;
    $data = $endpoint['data'] ?? null;
    $response = makeRequest($endpoint['url'], $endpoint['method'], $token, $data);
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
    
    if ($status >= 200 && $status < 300) {
        $results['success']++;
        $results['by_category'][$category]['success']++;
        echo "✅ {$endpoint['method']} {$endpoint['url']} ({$category})\n";
    } else {
        $results['failed']++;
        echo "❌ {$endpoint['method']} {$endpoint['url']} - $status ({$category}) - {$endpoint['description']}\n";
    }
}

echo "\n====================================================\n";
echo "📊 COMPREHENSIVE ENDPOINT TEST RESULTS (PART 1)\n";
echo "====================================================\n";

echo "📈 OVERALL STATISTICS:\n";
echo "Total Endpoints Tested: {$results['total']}\n";
echo "✅ Successfully Working: {$results['success']}\n";
echo "❌ Failed: {$results['failed']}\n";

$successRate = round(($results['success'] / $results['total']) * 100, 2);
echo "📈 Success Rate: $successRate%\n\n";

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

// CONTINUE WITH REMAINING ENDPOINTS
$remainingEndpoints = [
    // REVIEW MANAGEMENT
    ['url' => 'courses/1/reviews', 'method' => 'GET', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Get course reviews'],
    ['url' => 'courses/1/reviews', 'method' => 'POST', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Create course review'],
    ['url' => 'courses/1/reviews/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Review Management', 'description' => 'Get review analytics'],
    ['url' => 'reviews/moderate', 'method' => 'GET', 'token' => 'admin', 'category' => 'Review Management', 'description' => 'Moderate reviews'],
    ['url' => 'reviews/my-reviews', 'method' => 'GET', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Get my reviews'],
    ['url' => 'reviews/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Get single review'],
    ['url' => 'reviews/1', 'method' => 'PUT', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Update review'],
    ['url' => 'reviews/1', 'method' => 'DELETE', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Delete review'],
    ['url' => 'reviews/1/helpful', 'method' => 'POST', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Mark review helpful'],
    ['url' => 'reviews/1/approve', 'method' => 'POST', 'token' => 'admin', 'category' => 'Review Management', 'description' => 'Approve review'],
    ['url' => 'reviews/1/reject', 'method' => 'POST', 'token' => 'admin', 'category' => 'Review Management', 'description' => 'Reject review'],

    // FORUM MANAGEMENT
    ['url' => 'courses/1/forum', 'method' => 'GET', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Get course forum'],
    ['url' => 'courses/1/forum', 'method' => 'POST', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Create forum topic'],
    ['url' => 'courses/1/forum/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Forum Management', 'description' => 'Get forum analytics'],
    ['url' => 'forum/topics/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Get forum topic'],
    ['url' => 'forum/topics/1', 'method' => 'PUT', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Update forum topic'],
    ['url' => 'forum/topics/1', 'method' => 'DELETE', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Delete forum topic'],
    ['url' => 'forum/topics/1/subscribe', 'method' => 'POST', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Subscribe to topic'],
    ['url' => 'forum/topics/1/unsubscribe', 'method' => 'DELETE', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Unsubscribe from topic'],
    ['url' => 'forum/topics/1/posts', 'method' => 'POST', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Create forum post'],
    ['url' => 'forum/posts/1', 'method' => 'PUT', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Update forum post'],
    ['url' => 'forum/posts/1', 'method' => 'DELETE', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Delete forum post'],
    ['url' => 'forum/posts/1/like', 'method' => 'POST', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Like forum post'],
    ['url' => 'forum/posts/1/solution', 'method' => 'POST', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Mark as solution'],

    // CERTIFICATE MANAGEMENT
    ['url' => 'certificates', 'method' => 'GET', 'token' => 'student', 'category' => 'Certificate Management', 'description' => 'Get user certificates'],
    ['url' => 'certificates/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Certificate Management', 'description' => 'Get certificate analytics'],
    ['url' => 'certificates/templates', 'method' => 'GET', 'token' => 'student', 'category' => 'Certificate Management', 'description' => 'Get certificate templates'],
    ['url' => 'certificates/generate', 'method' => 'POST', 'token' => 'student', 'category' => 'Certificate Management', 'description' => 'Generate certificate'],
    ['url' => 'certificates/bulk-generate', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Certificate Management', 'description' => 'Bulk generate certificates'],
    ['url' => 'certificates/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Certificate Management', 'description' => 'Get single certificate'],
    ['url' => 'certificates/1/download', 'method' => 'GET', 'token' => 'student', 'category' => 'Certificate Management', 'description' => 'Download certificate'],
    ['url' => 'certificates/1/revoke', 'method' => 'POST', 'token' => 'admin', 'category' => 'Certificate Management', 'description' => 'Revoke certificate'],

    // BADGE MANAGEMENT
    ['url' => 'badges', 'method' => 'GET', 'token' => 'student', 'category' => 'Badge Management', 'description' => 'Get all badges'],
    ['url' => 'badges/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Get badge analytics'],
    ['url' => 'badges/leaderboard', 'method' => 'GET', 'token' => 'student', 'category' => 'Badge Management', 'description' => 'Get badge leaderboard'],
    ['url' => 'badges', 'method' => 'POST', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Create badge'],
    ['url' => 'badges/award', 'method' => 'POST', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Award badge to user'],
    ['url' => 'badges/check-automatic/2', 'method' => 'POST', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Check automatic badges'],
    ['url' => 'badges/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Badge Management', 'description' => 'Get single badge'],
    ['url' => 'badges/1', 'method' => 'PUT', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Update badge'],
    ['url' => 'badges/1', 'method' => 'DELETE', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Delete badge'],
    ['url' => 'badges/user-badges/1/revoke', 'method' => 'POST', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Revoke user badge'],
    ['url' => 'my-badges', 'method' => 'GET', 'token' => 'student', 'category' => 'Badge Management', 'description' => 'Get my badges'],

    // PROGRESS TRACKING
    ['url' => 'progress/courses', 'method' => 'GET', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Get course progress'],
    ['url' => 'progress/lessons', 'method' => 'GET', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Get lesson progress'],
    ['url' => 'progress/overall', 'method' => 'GET', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Get overall progress'],
    ['url' => 'progress/update', 'method' => 'POST', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Update progress'],
    ['url' => 'progress/certificates', 'method' => 'GET', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Get available certificates'],
    ['url' => 'progress/generate-cert', 'method' => 'POST', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Generate certificate'],
    ['url' => 'progress/achievements', 'method' => 'GET', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Get achievement progress'],
    ['url' => 'progress/streaks', 'method' => 'GET', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Get streak progress'],

    // GRADING MANAGEMENT
    ['url' => 'grading/gradebook/1', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Get gradebook'],
    ['url' => 'grading/courses/1', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Get course grades'],
    ['url' => 'grading/students/2', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Get student grades'],
    ['url' => 'grading/bulk-grade', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Bulk grade'],
    ['url' => 'grading/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Get grading analytics'],
    ['url' => 'grading/export', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Export grades'],
    ['url' => 'grading/grade-history/2/1', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Get grade history'],
    ['url' => 'grading/weights/1', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Update grade weights'],
    ['url' => 'grading/comments', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Add grading comments'],
    ['url' => 'grading/reports/1', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Get grading reports'],
];

// Test remaining endpoints
foreach ($remainingEndpoints as $endpoint) {
    $results['total']++;
    $token = $endpoint['token'] ? $authTokens[$endpoint['token']] : null;
    $data = $endpoint['data'] ?? null;
    $response = makeRequest($endpoint['url'], $endpoint['method'], $token, $data);
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

    if ($status >= 200 && $status < 300) {
        $results['success']++;
        $results['by_category'][$category]['success']++;
        echo "✅ {$endpoint['method']} {$endpoint['url']} ({$category})\n";
    } else {
        $results['failed']++;
        echo "❌ {$endpoint['method']} {$endpoint['url']} - $status ({$category}) - {$endpoint['description']}\n";
    }
}

// FINAL BATCH OF ENDPOINTS
$finalEndpoints = [
    // ADMIN MANAGEMENT
    ['url' => 'admin/dashboard', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Admin dashboard'],
    ['url' => 'admin/users', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get all users'],
    ['url' => 'admin/courses', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get all courses'],
    ['url' => 'admin/payments', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get all payments'],
    ['url' => 'admin/reports', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get admin reports'],
    ['url' => 'admin/settings', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get admin settings'],
    ['url' => 'admin/stats', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get database stats'],
    ['url' => 'admin/users/2/ban', 'method' => 'POST', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Ban user'],
    ['url' => 'admin/users/2/unban', 'method' => 'POST', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Unban user'],
    ['url' => 'admin/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Admin analytics'],
    ['url' => 'admin/bulk-actions', 'method' => 'POST', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Bulk actions'],
    ['url' => 'admin/audit-logs', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get audit logs'],
    ['url' => 'admin/maintenance', 'method' => 'POST', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Toggle maintenance mode'],
    ['url' => 'admin/clear-cache', 'method' => 'POST', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Clear system cache'],
    ['url' => 'admin/database-stats', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get database statistics'],

    // ANALYTICS
    ['url' => 'analytics/learning', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Learning analytics'],
    ['url' => 'analytics/course-performance', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Course performance'],
    ['url' => 'analytics/student-progress', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Student progress'],
    ['url' => 'analytics/revenue', 'method' => 'GET', 'token' => 'admin', 'category' => 'Analytics', 'description' => 'Revenue analytics'],
    ['url' => 'analytics/engagement', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Engagement analytics'],
    ['url' => 'analytics/comparative', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Comparative analytics'],
    ['url' => 'analytics/export', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Export analytics'],
    ['url' => 'analytics/real-time', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Real-time analytics'],
    ['url' => 'analytics/predictive', 'method' => 'GET', 'token' => 'admin', 'category' => 'Analytics', 'description' => 'Predictive analytics'],

    // LEARNING PATHS
    ['url' => 'learning-paths', 'method' => 'GET', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Get learning paths'],
    ['url' => 'learning-paths', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Learning Paths', 'description' => 'Create learning path'],
    ['url' => 'learning-paths/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Get single learning path'],
    ['url' => 'learning-paths/1', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Learning Paths', 'description' => 'Update learning path'],
    ['url' => 'learning-paths/1', 'method' => 'DELETE', 'token' => 'instructor', 'category' => 'Learning Paths', 'description' => 'Delete learning path'],
    ['url' => 'learning-paths/1/enroll', 'method' => 'POST', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Enroll in learning path'],
    ['url' => 'learning-paths/1/unenroll', 'method' => 'DELETE', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Unenroll from learning path'],
    ['url' => 'learning-paths/my/paths', 'method' => 'GET', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Get my learning paths'],
    ['url' => 'learning-paths/1/progress?user_id=2', 'method' => 'GET', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Get learning path progress'],
    ['url' => 'learning-paths/1/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Learning Paths', 'description' => 'Get learning path analytics'],
    ['url' => 'learning-paths/1/publish', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Learning Paths', 'description' => 'Publish learning path'],
    ['url' => 'learning-paths/1/unpublish', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Learning Paths', 'description' => 'Unpublish learning path'],

    // AI CHAT
    ['url' => 'chat/start', 'method' => 'POST', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'Start chat session'],
    ['url' => 'chat/sessions/1/message', 'method' => 'POST', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'Send message'],
    ['url' => 'chat/sessions/1', 'method' => 'GET', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'Get session history'],
    ['url' => 'chat/sessions', 'method' => 'GET', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'Get user sessions'],
    ['url' => 'chat/sessions/1/end', 'method' => 'POST', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'End session'],
    ['url' => 'chat/sessions/1/rate', 'method' => 'POST', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'Rate session'],
    ['url' => 'chat/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'AI Chat', 'description' => 'Chat analytics'],
    ['url' => 'chat/suggestions', 'method' => 'POST', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'Get suggested responses'],

    // RECOMMENDATIONS
    ['url' => 'recommendations', 'method' => 'GET', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Get personalized recommendations'],
    ['url' => 'recommendations/courses/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Course-based recommendations'],
    ['url' => 'recommendations/learning-paths', 'method' => 'GET', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Learning path recommendations'],
    ['url' => 'recommendations/instructors', 'method' => 'GET', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Instructor recommendations'],
    ['url' => 'recommendations/content', 'method' => 'GET', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Content recommendations'],
    ['url' => 'recommendations/preferences', 'method' => 'PUT', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Update recommendation preferences'],
    ['url' => 'recommendations/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'Recommendations', 'description' => 'Recommendation analytics'],

    // COUPONS
    ['url' => 'coupons', 'method' => 'GET', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Get all coupons'],
    ['url' => 'coupons', 'method' => 'POST', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Create coupon'],
    ['url' => 'coupons/1', 'method' => 'GET', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Get single coupon'],
    ['url' => 'coupons/1', 'method' => 'PUT', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Update coupon'],
    ['url' => 'coupons/1', 'method' => 'DELETE', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Delete coupon'],
    ['url' => 'coupons/validate', 'method' => 'POST', 'token' => 'student', 'category' => 'Coupons', 'description' => 'Validate coupon'],
    ['url' => 'coupons/apply', 'method' => 'POST', 'token' => 'student', 'category' => 'Coupons', 'description' => 'Apply coupon'],
    ['url' => 'coupons/user/available', 'method' => 'GET', 'token' => 'student', 'category' => 'Coupons', 'description' => 'Get available coupons'],
    ['url' => 'coupons/admin/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Coupon analytics'],
    ['url' => 'coupons/bulk-action', 'method' => 'POST', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Bulk coupon action'],

    // REPORTS
    ['url' => 'reports/types', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Reports', 'description' => 'Get report types'],
    ['url' => 'reports/financial', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Reports', 'description' => 'Generate financial report'],
    ['url' => 'reports/academic', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Reports', 'description' => 'Generate academic report'],
    ['url' => 'reports/user', 'method' => 'POST', 'token' => 'admin', 'category' => 'Reports', 'description' => 'Generate user report'],
    ['url' => 'reports/content', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Reports', 'description' => 'Generate content report'],
    ['url' => 'reports/scheduled', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Reports', 'description' => 'Get scheduled reports'],
    ['url' => 'reports/schedule', 'method' => 'POST', 'token' => 'admin', 'category' => 'Reports', 'description' => 'Schedule report'],
    ['url' => 'reports/history', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Reports', 'description' => 'Get report history'],

    // SETTINGS
    ['url' => 'settings', 'method' => 'GET', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get all settings'],
    ['url' => 'settings/app_name', 'method' => 'GET', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get single setting'],
    ['url' => 'settings/app_name', 'method' => 'PUT', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Update single setting'],
    ['url' => 'settings', 'method' => 'PUT', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Update bulk settings'],
    ['url' => 'settings/reset', 'method' => 'POST', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Reset settings'],
    ['url' => 'settings/email/config', 'method' => 'GET', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get email settings'],
    ['url' => 'settings/payment/config', 'method' => 'GET', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get payment settings'],
    ['url' => 'settings/features/toggles', 'method' => 'GET', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get feature toggles'],

    // AUDIT
    ['url' => 'audit/logs', 'method' => 'GET', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get audit logs'],
    ['url' => 'audit/logs/1', 'method' => 'GET', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get single audit log'],
    ['url' => 'audit/users/2/activity', 'method' => 'GET', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get user activity'],
    ['url' => 'audit/system/events', 'method' => 'GET', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get system events'],
    ['url' => 'audit/security/events', 'method' => 'GET', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get security events'],
    ['url' => 'audit/export', 'method' => 'POST', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Export audit logs'],

    // NOTIFICATIONS
    ['url' => 'notifications', 'method' => 'GET', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Get notifications'],
    ['url' => 'notifications/1/read', 'method' => 'PUT', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Mark as read'],
    ['url' => 'notifications/read-all', 'method' => 'PUT', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Mark all as read'],
    ['url' => 'notifications/1', 'method' => 'DELETE', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Delete notification'],
    ['url' => 'notifications/preferences', 'method' => 'GET', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Get notification preferences'],
    ['url' => 'notifications/preferences', 'method' => 'PUT', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Update notification preferences'],
    ['url' => 'notifications/send', 'method' => 'POST', 'token' => 'admin', 'category' => 'Notifications', 'description' => 'Send notification'],
    ['url' => 'notifications/broadcast', 'method' => 'POST', 'token' => 'admin', 'category' => 'Notifications', 'description' => 'Broadcast notification'],
    ['url' => 'notifications/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'Notifications', 'description' => 'Notification analytics'],

    // SEARCH
    ['url' => 'search?q=test', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'General search'],
    ['url' => 'search/global?q=test', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'Global search'],
    ['url' => 'search/courses?q=test', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'Course search'],
    ['url' => 'search/users?q=test', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'User search'],
    ['url' => 'search/content?q=test', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'Content search'],
    ['url' => 'search/suggestions?q=test', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'Search suggestions'],
    ['url' => 'search/filters', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'Get search filters'],

    // FILE MANAGEMENT
    ['url' => 'files/upload', 'method' => 'POST', 'token' => 'student', 'category' => 'File Management', 'description' => 'Upload file'],
    ['url' => 'files/download/1', 'method' => 'GET', 'token' => 'student', 'category' => 'File Management', 'description' => 'Download file'],
    ['url' => 'files/1', 'method' => 'DELETE', 'token' => 'student', 'category' => 'File Management', 'description' => 'Delete file'],
    ['url' => 'files/list', 'method' => 'GET', 'token' => 'student', 'category' => 'File Management', 'description' => 'List files'],
    ['url' => 'files/preview/1', 'method' => 'GET', 'token' => 'student', 'category' => 'File Management', 'description' => 'Preview file'],
    ['url' => 'files/1/share', 'method' => 'POST', 'token' => 'student', 'category' => 'File Management', 'description' => 'Share file'],
    ['url' => 'files/organize', 'method' => 'POST', 'token' => 'student', 'category' => 'File Management', 'description' => 'Organize files'],
    ['url' => 'files/storage/stats', 'method' => 'GET', 'token' => 'student', 'category' => 'File Management', 'description' => 'Get storage stats'],

    // CATEGORY MANAGEMENT (Admin)
    ['url' => 'category', 'method' => 'POST', 'token' => 'admin', 'category' => 'Category Management', 'description' => 'Create category'],
    ['url' => 'category/1', 'method' => 'PUT', 'token' => 'admin', 'category' => 'Category Management', 'description' => 'Update category'],
    ['url' => 'category/1', 'method' => 'DELETE', 'token' => 'admin', 'category' => 'Category Management', 'description' => 'Delete category'],
];

// Test final endpoints
foreach ($finalEndpoints as $endpoint) {
    $results['total']++;
    $token = $endpoint['token'] ? $authTokens[$endpoint['token']] : null;
    $data = $endpoint['data'] ?? null;
    $response = makeRequest($endpoint['url'], $endpoint['method'], $token, $data);
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

    if ($status >= 200 && $status < 300) {
        $results['success']++;
        $results['by_category'][$category]['success']++;
        echo "✅ {$endpoint['method']} {$endpoint['url']} ({$category})\n";
    } else {
        $results['failed']++;
        echo "❌ {$endpoint['method']} {$endpoint['url']} - $status ({$category}) - {$endpoint['description']}\n";
    }
}

echo "\n====================================================\n";
echo "📊 FINAL COMPREHENSIVE ENDPOINT TEST RESULTS\n";
echo "====================================================\n";

echo "📈 OVERALL STATISTICS:\n";
echo "Total Endpoints Tested: {$results['total']}\n";
echo "✅ Successfully Working: {$results['success']}\n";
echo "❌ Failed: {$results['failed']}\n";

$successRate = round(($results['success'] / $results['total']) * 100, 2);
echo "📈 Overall Success Rate: $successRate%\n\n";

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

echo "\n====================================================\n";
echo "🎯 FINAL ASSESSMENT\n";
echo "====================================================\n";

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
} elseif ($successRate >= 70) {
    echo "⚠️  NEEDS SOME WORK! 70%+ success rate.\n";
    echo "🔧 Some fixes needed before production deployment.\n";
    $status = "NEEDS SOME WORK";
} else {
    echo "⚠️  NEEDS SIGNIFICANT IMPROVEMENT! Below 70% success rate.\n";
    echo "🔧 Major fixes needed before production deployment.\n";
    $status = "NEEDS SIGNIFICANT IMPROVEMENT";
}

echo "\n🇳🇬 KOKOKAH.COM LMS COMPREHENSIVE STATUS:\n";
echo "Platform Readiness: $status\n";
echo "Market Launch: " . ($successRate >= 85 ? "READY FOR GLOBAL MARKETS" : "NEEDS ADDITIONAL WORK") . "\n";
echo "Total Endpoints: {$results['total']}\n";
echo "Working Endpoints: {$results['success']}\n";
echo "Success Rate: $successRate%\n";
echo "====================================================\n";
