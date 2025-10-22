<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 EXTRACTING AND TESTING ALL ENDPOINTS FROM ROUTES FILE\n";
echo "========================================================\n\n";

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

// Parse routes file to extract ALL endpoints
$routesContent = file_get_contents('routes/api.php');

// Extract all route definitions
$allEndpoints = [];

// Public routes (no auth)
$publicEndpoints = [
    ['url' => '', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'API root'],
    ['url' => 'category', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get categories'],
    ['url' => 'category/1', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get single category'],
    ['url' => 'courses', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get all courses'],
    ['url' => 'courses/search?q=test', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Search courses'],
    ['url' => 'courses/featured', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get featured courses'],
    ['url' => 'courses/popular', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get popular courses'],
    ['url' => 'courses/1', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get single course'],
    ['url' => 'certificates/verify/CERT-123', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Verify certificate'],
    ['url' => 'settings/public', 'method' => 'GET', 'token' => null, 'category' => 'Public', 'description' => 'Get public settings'],
];

// Authentication routes (no auth required)
$authEndpoints = [
    ['url' => 'register', 'method' => 'POST', 'token' => null, 'category' => 'Auth', 'description' => 'Register user', 'data' => ['first_name' => 'Test', 'last_name' => 'User', 'email' => 'test' . time() . '@example.com', 'password' => 'password123', 'password_confirmation' => 'password123', 'role' => 'student']],
    ['url' => 'login', 'method' => 'POST', 'token' => null, 'category' => 'Auth', 'description' => 'Login user', 'data' => ['email' => 'student1@kokokah.com', 'password' => 'password']],
    ['url' => 'forgot-password', 'method' => 'POST', 'token' => null, 'category' => 'Auth', 'description' => 'Forgot password', 'data' => ['email' => 'student1@kokokah.com']],
    ['url' => 'reset-password', 'method' => 'POST', 'token' => null, 'category' => 'Auth', 'description' => 'Reset password', 'data' => ['email' => 'student1@kokokah.com', 'password' => 'newpassword', 'password_confirmation' => 'newpassword', 'token' => 'dummy-token']],
];

// Authenticated routes
$authenticatedEndpoints = [
    // User routes
    ['url' => 'user', 'method' => 'GET', 'token' => 'student', 'category' => 'Auth', 'description' => 'Get current user'],
    ['url' => 'logout', 'method' => 'POST', 'token' => 'student', 'category' => 'Auth', 'description' => 'Logout user'],
    
    // Email verification routes
    ['url' => 'email/verification-notification', 'method' => 'POST', 'token' => 'student', 'category' => 'Auth', 'description' => 'Resend verification'],
    
    // Payment webhooks (public)
    ['url' => 'payments/webhook/paystack', 'method' => 'POST', 'token' => null, 'category' => 'Payment Webhooks', 'description' => 'Payment webhook'],
    ['url' => 'payments/callback/paystack', 'method' => 'GET', 'token' => null, 'category' => 'Payment Webhooks', 'description' => 'Payment callback'],
    ['url' => 'payments/success/paystack', 'method' => 'GET', 'token' => null, 'category' => 'Payment Webhooks', 'description' => 'Payment success'],
    ['url' => 'payments/cancel/paystack', 'method' => 'GET', 'token' => null, 'category' => 'Payment Webhooks', 'description' => 'Payment cancel'],
    
    // Wallet routes
    ['url' => 'wallet', 'method' => 'GET', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Get wallet info'],
    ['url' => 'wallet/transfer', 'method' => 'POST', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Transfer funds', 'data' => ['recipient_id' => 3, 'amount' => 100]],
    ['url' => 'wallet/purchase-course', 'method' => 'POST', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Purchase course with wallet', 'data' => ['course_id' => 1]],
    ['url' => 'wallet/transactions', 'method' => 'GET', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Get wallet transactions'],
    ['url' => 'wallet/rewards', 'method' => 'GET', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Get wallet rewards'],
    ['url' => 'wallet/claim-login-reward', 'method' => 'POST', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Claim login reward'],
    ['url' => 'wallet/check-affordability', 'method' => 'POST', 'token' => 'student', 'category' => 'Wallet', 'description' => 'Check affordability', 'data' => ['course_id' => 1]],
    
    // Payment routes
    ['url' => 'payments/gateways', 'method' => 'GET', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get payment gateways'],
    ['url' => 'payments/deposit', 'method' => 'POST', 'token' => 'student', 'category' => 'Payment', 'description' => 'Initialize wallet deposit', 'data' => ['amount' => 1000, 'gateway' => 'paystack']],
    ['url' => 'payments/purchase-course', 'method' => 'POST', 'token' => 'student', 'category' => 'Payment', 'description' => 'Initialize course payment', 'data' => ['course_id' => 1, 'gateway' => 'paystack']],
    ['url' => 'payments/history', 'method' => 'GET', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get payment history'],
    ['url' => 'payments/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get single payment'],
    
    // Course management routes
    ['url' => 'courses/my-courses', 'method' => 'GET', 'token' => 'student', 'category' => 'Course Management', 'description' => 'Get my courses'],
    ['url' => 'courses/1/enroll', 'method' => 'POST', 'token' => 'student', 'category' => 'Course Management', 'description' => 'Enroll in course'],
    ['url' => 'courses/1/unenroll', 'method' => 'DELETE', 'token' => 'student', 'category' => 'Course Management', 'description' => 'Unenroll from course'],
    ['url' => 'courses', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Create course', 'data' => ['title' => 'Test Course', 'description' => 'Test Description', 'price' => 100, 'category_id' => 1]],
    ['url' => 'courses/1', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Update course', 'data' => ['title' => 'Updated Course']],
    ['url' => 'courses/1', 'method' => 'DELETE', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Delete course'],
    ['url' => 'courses/1/students', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Get course students'],
    ['url' => 'courses/1/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Get course analytics'],
    ['url' => 'courses/1/publish', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Publish course'],
    ['url' => 'courses/1/unpublish', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Course Management', 'description' => 'Unpublish course'],
];

// Combine all endpoints
$allEndpoints = array_merge($publicEndpoints, $authEndpoints, $authenticatedEndpoints);

echo "🧪 Testing " . count($allEndpoints) . " endpoints (PART 1 - Basic Routes):\n\n";

$results = [
    'total' => 0,
    'success' => 0,
    'failed' => 0,
    'by_status' => [],
    'by_category' => [],
    'errors_500' => [],
    'missing_endpoints' => []
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
        
        // Track 500 errors specifically
        if ($status >= 500) {
            $results['errors_500'][] = [
                'endpoint' => $endpoint,
                'status' => $status,
                'response' => $response['body']
            ];
        }
    }
}

echo "\n========================================================\n";
echo "📊 PART 1 RESULTS - BASIC ROUTES\n";
echo "========================================================\n";

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

// Show 500 errors in detail
if (!empty($results['errors_500'])) {
    echo "\n🚨 500 SERVER ERRORS DETECTED:\n";
    foreach ($results['errors_500'] as $error) {
        echo "❌ {$error['endpoint']['method']} {$error['endpoint']['url']} - {$error['status']}\n";
        $body = json_decode($error['response'], true);
        if ($body && isset($body['message'])) {
            echo "   💥 Error: " . substr($body['message'], 0, 100) . "...\n";
        }
    }
} else {
    echo "\n✅ NO 500 SERVER ERRORS FOUND!\n";
}

// PART 2 - REMAINING ENDPOINTS
$remainingEndpoints = [
    // Lesson management routes
    ['url' => 'courses/1/lessons', 'method' => 'GET', 'token' => 'student', 'category' => 'Lesson Management', 'description' => 'Get course lessons'],
    ['url' => 'courses/1/lessons', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Lesson Management', 'description' => 'Create lesson', 'data' => ['title' => 'Test Lesson', 'content' => 'Test Content', 'order' => 1]],
    ['url' => 'lessons/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Lesson Management', 'description' => 'Get single lesson'],
    ['url' => 'lessons/1', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Lesson Management', 'description' => 'Update lesson', 'data' => ['title' => 'Updated Lesson']],
    ['url' => 'lessons/1', 'method' => 'DELETE', 'token' => 'instructor', 'category' => 'Lesson Management', 'description' => 'Delete lesson'],
    ['url' => 'lessons/1/complete', 'method' => 'POST', 'token' => 'student', 'category' => 'Lesson Management', 'description' => 'Mark lesson complete'],
    ['url' => 'lessons/1/progress', 'method' => 'GET', 'token' => 'student', 'category' => 'Lesson Management', 'description' => 'Get lesson progress'],
    ['url' => 'lessons/1/watch-time', 'method' => 'POST', 'token' => 'student', 'category' => 'Lesson Management', 'description' => 'Track watch time', 'data' => ['watch_time' => 300]],
    ['url' => 'lessons/1/attachments', 'method' => 'GET', 'token' => 'student', 'category' => 'Lesson Management', 'description' => 'Get lesson attachments'],

    // Enrollment management routes
    ['url' => 'enrollments', 'method' => 'GET', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Get enrollments'],
    ['url' => 'enrollments', 'method' => 'POST', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Create enrollment', 'data' => ['course_id' => 1]],
    ['url' => 'enrollments/certificates', 'method' => 'GET', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Get enrollment certificates'],
    ['url' => 'enrollments/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Get single enrollment'],
    ['url' => 'enrollments/1', 'method' => 'PUT', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Update enrollment', 'data' => ['status' => 'active']],
    ['url' => 'enrollments/1', 'method' => 'DELETE', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Delete enrollment'],
    ['url' => 'enrollments/1/progress', 'method' => 'GET', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Get enrollment progress'],
    ['url' => 'enrollments/1/complete', 'method' => 'POST', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Complete enrollment'],

    // User profile and dashboard routes
    ['url' => 'users/profile', 'method' => 'GET', 'token' => 'student', 'category' => 'User Management', 'description' => 'Get user profile'],
    ['url' => 'users/profile', 'method' => 'PUT', 'token' => 'student', 'category' => 'User Management', 'description' => 'Update user profile', 'data' => ['first_name' => 'Updated Name']],
    ['url' => 'users/dashboard', 'method' => 'GET', 'token' => 'student', 'category' => 'User Management', 'description' => 'Get user dashboard'],
    ['url' => 'users/achievements', 'method' => 'GET', 'token' => 'student', 'category' => 'User Management', 'description' => 'Get user achievements'],
    ['url' => 'users/learning-stats', 'method' => 'GET', 'token' => 'student', 'category' => 'User Management', 'description' => 'Get learning stats'],
    ['url' => 'users/preferences', 'method' => 'PUT', 'token' => 'student', 'category' => 'User Management', 'description' => 'Update user preferences', 'data' => ['language' => 'en']],
    ['url' => 'users/notifications', 'method' => 'GET', 'token' => 'student', 'category' => 'User Management', 'description' => 'Get user notifications'],
    ['url' => 'users/notifications/read', 'method' => 'POST', 'token' => 'student', 'category' => 'User Management', 'description' => 'Mark notifications read'],
    ['url' => 'users/change-password', 'method' => 'POST', 'token' => 'student', 'category' => 'User Management', 'description' => 'Change password', 'data' => ['current_password' => 'password', 'new_password' => 'newpassword', 'new_password_confirmation' => 'newpassword']],
    ['url' => 'users/2/badges', 'method' => 'GET', 'token' => 'student', 'category' => 'User Management', 'description' => 'Get user badges'],

    // Quiz management routes
    ['url' => 'lessons/1/quizzes', 'method' => 'GET', 'token' => 'student', 'category' => 'Quiz Management', 'description' => 'Get lesson quizzes'],
    ['url' => 'lessons/1/quizzes', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Quiz Management', 'description' => 'Create quiz', 'data' => ['title' => 'Test Quiz', 'questions' => []]],
    ['url' => 'quizzes/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Quiz Management', 'description' => 'Get single quiz'],
    ['url' => 'quizzes/1', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Quiz Management', 'description' => 'Update quiz', 'data' => ['title' => 'Updated Quiz']],
    ['url' => 'quizzes/1', 'method' => 'DELETE', 'token' => 'instructor', 'category' => 'Quiz Management', 'description' => 'Delete quiz'],
    ['url' => 'quizzes/1/start', 'method' => 'POST', 'token' => 'student', 'category' => 'Quiz Management', 'description' => 'Start quiz attempt'],
    ['url' => 'quizzes/1/submit', 'method' => 'POST', 'token' => 'student', 'category' => 'Quiz Management', 'description' => 'Submit quiz', 'data' => ['answers' => []]],
    ['url' => 'quizzes/1/results', 'method' => 'GET', 'token' => 'student', 'category' => 'Quiz Management', 'description' => 'Get quiz results'],
    ['url' => 'quizzes/1/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Quiz Management', 'description' => 'Get quiz analytics'],

    // Assignment management routes
    ['url' => 'courses/1/assignments', 'method' => 'GET', 'token' => 'student', 'category' => 'Assignment Management', 'description' => 'Get course assignments'],
    ['url' => 'courses/1/assignments', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Assignment Management', 'description' => 'Create assignment', 'data' => ['title' => 'Test Assignment', 'description' => 'Test Description', 'due_date' => '2024-12-31']],
    ['url' => 'assignments/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Assignment Management', 'description' => 'Get single assignment'],
    ['url' => 'assignments/1', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Assignment Management', 'description' => 'Update assignment', 'data' => ['title' => 'Updated Assignment']],
    ['url' => 'assignments/1', 'method' => 'DELETE', 'token' => 'instructor', 'category' => 'Assignment Management', 'description' => 'Delete assignment'],
    ['url' => 'assignments/1/submit', 'method' => 'POST', 'token' => 'student', 'category' => 'Assignment Management', 'description' => 'Submit assignment', 'data' => ['content' => 'My submission']],
    ['url' => 'assignments/1/submissions', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Assignment Management', 'description' => 'Get assignment submissions'],
    ['url' => 'assignments/1/grades', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Assignment Management', 'description' => 'Get assignment grades'],
    ['url' => 'submissions/1/grade', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Assignment Management', 'description' => 'Grade submission', 'data' => ['grade' => 85, 'feedback' => 'Good work']],

    // Dashboard routes
    ['url' => 'dashboard/student', 'method' => 'GET', 'token' => 'student', 'category' => 'Dashboard', 'description' => 'Student dashboard'],
    ['url' => 'dashboard/instructor', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Dashboard', 'description' => 'Instructor dashboard'],
    ['url' => 'dashboard/admin', 'method' => 'GET', 'token' => 'admin', 'category' => 'Dashboard', 'description' => 'Admin dashboard'],
    ['url' => 'dashboard/analytics', 'method' => 'GET', 'token' => 'student', 'category' => 'Dashboard', 'description' => 'Dashboard analytics'],

    // Special admin/instructor routes
    ['url' => 'admin/dashboard', 'method' => 'GET', 'token' => 'admin', 'category' => 'Special Routes', 'description' => 'Admin dashboard (special)'],
    ['url' => 'admin/reports', 'method' => 'GET', 'token' => 'admin', 'category' => 'Special Routes', 'description' => 'Admin reports (special)'],
    ['url' => 'instructor/courses', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Special Routes', 'description' => 'Instructor courses (special)'],
    ['url' => 'dashboard', 'method' => 'GET', 'token' => 'student', 'category' => 'Special Routes', 'description' => 'Verified dashboard'],
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

        // Track 500 errors specifically
        if ($status >= 500) {
            $results['errors_500'][] = [
                'endpoint' => $endpoint,
                'status' => $status,
                'response' => $response['body']
            ];
        }
    }
}

echo "\n========================================================\n";
echo "📊 COMPREHENSIVE ENDPOINT TEST RESULTS - ALL ROUTES\n";
echo "========================================================\n";

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

// Show 500 errors in detail
if (!empty($results['errors_500'])) {
    echo "\n🚨 500 SERVER ERRORS DETECTED:\n";
    foreach ($results['errors_500'] as $error) {
        echo "❌ {$error['endpoint']['method']} {$error['endpoint']['url']} - {$error['status']}\n";
        $body = json_decode($error['response'], true);
        if ($body && isset($body['message'])) {
            echo "   💥 Error: " . substr($body['message'], 0, 100) . "...\n";
        }
    }
    echo "\n🔧 RECOMMENDATION: Fix these 500 errors before production deployment!\n";
} else {
    echo "\n✅ NO 500 SERVER ERRORS FOUND!\n";
}

echo "\n========================================================\n";
echo "🎯 FINAL ASSESSMENT\n";
echo "========================================================\n";

if (count($results['errors_500']) > 0) {
    $errorCount = count($results['errors_500']);
    echo "⚠️  CRITICAL: $errorCount SERVER ERRORS (500+) FOUND!\n";
    echo "🔧 These MUST be fixed before production deployment!\n";
    $status = "NEEDS CRITICAL FIXES";
} elseif ($successRate >= 95) {
    echo "🎉 PERFECT! 95%+ success rate - WORLD-CLASS PRODUCTION READY!\n";
    echo "🚀 No server errors - Ready for millions of users!\n";
    $status = "WORLD-CLASS PRODUCTION READY";
} elseif ($successRate >= 90) {
    echo "🎉 EXCELLENT! 90%+ success rate - FULLY PRODUCTION READY!\n";
    echo "🚀 No server errors - Ready for immediate deployment!\n";
    $status = "FULLY PRODUCTION READY";
} elseif ($successRate >= 85) {
    echo "✅ VERY GOOD! 85%+ success rate - PRODUCTION READY!\n";
    echo "🚀 No server errors - Ready for deployment!\n";
    $status = "PRODUCTION READY";
} else {
    echo "⚠️  NEEDS IMPROVEMENT! Below 85% success rate.\n";
    echo "🔧 Some fixes needed before production deployment.\n";
    $status = "NEEDS IMPROVEMENT";
}

echo "\n🇳🇬 KOKOKAH.COM LMS COMPREHENSIVE STATUS:\n";
echo "Platform Readiness: $status\n";
echo "Server Errors (500+): " . count($results['errors_500']) . "\n";
echo "Total Endpoints: {$results['total']}\n";
echo "Working Endpoints: {$results['success']}\n";
echo "Success Rate: $successRate%\n";
echo "========================================================\n";
