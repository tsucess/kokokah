<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎯 COMPREHENSIVE ENDPOINT TEST - ALL 200+ ENDPOINTS\n";
echo "===================================================\n\n";

// Create fresh student token before testing
echo "🔧 Creating fresh student token...\n";
use App\Models\User;
use App\Models\Badge;
use App\Models\Coupon;

// Get the test student user (test.student@kokokah.com)
$student = User::where('email', 'test.student@kokokah.com')->first() ?? User::find(130);
if ($student) {
    $student->tokens()->delete();
    $tokenObject = $student->createToken('student-token');
    $newToken = $tokenObject->plainTextToken;

    echo "DEBUG: Token created for user {$student->id}: $newToken\n";

    // Verify token is in database
    $tokenParts = explode('|', $newToken);
    $tokenId = $tokenParts[0];
    $tokenRecord = \DB::table('personal_access_tokens')->where('id', $tokenId)->first();
    if ($tokenRecord) {
        echo "DEBUG: Token verified in database (ID: $tokenId, User: {$tokenRecord->tokenable_id})\n";
    } else {
        echo "DEBUG: Token NOT in database (ID: $tokenId)\n";
    }

    // Update auth_tokens.txt
    $tokenContent = file_get_contents('auth_tokens.txt');
    $newTokenContent = preg_replace(
        '/STUDENT_TOKEN=.+/',
        'STUDENT_TOKEN=' . $newToken,
        $tokenContent
    );
    file_put_contents('auth_tokens.txt', $newTokenContent);
    echo "✅ Fresh student token created\n\n";
}

// Get actual IDs for test data
echo "🔧 Getting actual test data IDs...\n";
use App\Models\ForumPost;
use App\Models\Enrollment;

// Get student user
$studentUser = User::where('email', 'test.student@kokokah.com')->first();
$studentUserId = $studentUser?->id ?? 130;

$badgeId = Badge::first()?->id ?? 2;
$couponId = Coupon::first()?->id ?? 9;
$forumTopicId = \DB::table('forum_topics')->first()?->id ?? 1;
$forumPostId = ForumPost::first()?->id ?? 1;

// Get enrollment ID for student user
$enrollmentId = Enrollment::where('user_id', $studentUserId)->first()?->id ?? 7;

// Get or create notification for the student user
$notification = \DB::table('notifications')->where('notifiable_id', $studentUserId)->first();
if (!$notification) {
    // Create a notification for the student user
    $notificationId = \Illuminate\Support\Str::uuid();
    \DB::table('notifications')->insert([
        'id' => $notificationId,
        'type' => 'App\\Notifications\\TestNotification',
        'notifiable_type' => 'App\\Models\\User',
        'notifiable_id' => $studentUserId,
        'data' => json_encode(['title' => 'Test Notification', 'message' => 'This is a test notification']),
        'read_at' => null,
        'created_at' => now(),
        'updated_at' => now()
    ]);
} else {
    $notificationId = $notification->id;
}

echo "Using Badge ID: $badgeId, Coupon ID: $couponId, Forum Topic ID: $forumTopicId, Forum Post ID: $forumPostId, Enrollment ID: $enrollmentId, Notification ID: $notificationId\n";
echo "DEBUG: enrollmentId = $enrollmentId (type: " . gettype($enrollmentId) . ")\n\n";

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

// Verify all tokens are working first
echo "🧪 VERIFYING ALL TOKENS:\n";
echo "========================\n";

foreach ($authTokens as $role => $token) {
    $response = makeRequest('user', 'GET', $token);
    if ($response['status'] == 200) {
        echo "✅ $role token: WORKING\n";
    } else {
        echo "❌ $role token: FAILED (Status: {$response['status']})\n";
        exit(1);
    }
}

echo "\n🎯 ALL TOKENS VERIFIED - STARTING COMPREHENSIVE TEST\n\n";

// Debug: Check if student token is still in database
$studentTokenParts = explode('|', $authTokens['student']);
$studentTokenId = $studentTokenParts[0];
$tokenRecord = \DB::table('personal_access_tokens')->where('id', $studentTokenId)->first();
if ($tokenRecord) {
    echo "DEBUG: Student token still in database (ID: $studentTokenId, User: {$tokenRecord->tokenable_id})\n\n";
} else {
    echo "DEBUG: Student token NOT in database (ID: $studentTokenId)\n\n";
}

// ALL ENDPOINTS - COMPREHENSIVE LIST
$allEndpoints = [
    // Public routes (no auth)
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
    
    // Authentication routes (no auth required)
    ['url' => 'register', 'method' => 'POST', 'token' => null, 'category' => 'Auth', 'description' => 'Register user', 'data' => ['first_name' => 'Test', 'last_name' => 'User', 'email' => 'test' . time() . '@example.com', 'password' => 'password123', 'password_confirmation' => 'password123', 'role' => 'student']],
    ['url' => 'login', 'method' => 'POST', 'token' => null, 'category' => 'Auth', 'description' => 'Login user', 'data' => ['email' => 'student1@kokokah.com', 'password' => 'password']],
    ['url' => 'forgot-password', 'method' => 'POST', 'token' => null, 'category' => 'Auth', 'description' => 'Forgot password', 'data' => ['email' => 'student1@kokokah.com']],
    ['url' => 'reset-password', 'method' => 'POST', 'token' => null, 'category' => 'Auth', 'description' => 'Reset password', 'data' => ['email' => 'student1@kokokah.com', 'password' => 'newpassword', 'password_confirmation' => 'newpassword', 'token' => 'dummy-token']],
    
    // Authenticated routes
    ['url' => 'user', 'method' => 'GET', 'token' => 'student', 'category' => 'Auth', 'description' => 'Get current user'],
    // NOTE: logout endpoint is tested LAST because it deletes all tokens
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
    ['url' => 'enrollments/{enrollmentId}', 'method' => 'GET', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Get single enrollment'],
    ['url' => 'enrollments/{enrollmentId}', 'method' => 'PUT', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Update enrollment', 'data' => ['status' => 'active']],
    ['url' => 'enrollments/{enrollmentId}', 'method' => 'DELETE', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Delete enrollment'],
    ['url' => 'enrollments/{enrollmentId}/progress', 'method' => 'GET', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Get enrollment progress'],
    ['url' => 'enrollments/{enrollmentId}/complete', 'method' => 'POST', 'token' => 'student', 'category' => 'Enrollment', 'description' => 'Complete enrollment'],
];

$results = [
    'total' => 0,
    'success' => 0,
    'failed' => 0,
    'by_status' => [],
    'by_category' => [],
    'errors_500' => [],
    'missing_endpoints' => []
];

echo "🧪 Testing " . count($allEndpoints) . " endpoints (PART 1):\n\n";

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
echo "📊 PART 1 RESULTS\n";
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
    echo "\n✅ NO 500 SERVER ERRORS FOUND IN PART 1!\n";
}

// PART 2 - REMAINING ENDPOINTS
$remainingEndpoints = [
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
    ['url' => 'my-badges', 'method' => 'GET', 'token' => 'student', 'category' => 'User Management', 'description' => 'Get my badges'],

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

    // Review management routes
    ['url' => 'courses/1/reviews', 'method' => 'GET', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Get course reviews'],
    ['url' => 'courses/1/reviews', 'method' => 'POST', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Create review', 'data' => ['rating' => 5, 'comment' => 'Great course!']],
    ['url' => 'courses/1/reviews/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Review Management', 'description' => 'Get review analytics'],
    ['url' => 'reviews/moderate', 'method' => 'GET', 'token' => 'admin', 'category' => 'Review Management', 'description' => 'Moderate reviews'],
    ['url' => 'reviews/my-reviews', 'method' => 'GET', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Get my reviews'],
    ['url' => 'reviews/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Get single review'],
    ['url' => 'reviews/1', 'method' => 'PUT', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Update review', 'data' => ['rating' => 4]],
    ['url' => 'reviews/1', 'method' => 'DELETE', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Delete review'],
    ['url' => 'reviews/1/helpful', 'method' => 'POST', 'token' => 'student', 'category' => 'Review Management', 'description' => 'Mark review helpful'],
    ['url' => 'reviews/1/approve', 'method' => 'POST', 'token' => 'admin', 'category' => 'Review Management', 'description' => 'Approve review'],
    ['url' => 'reviews/1/reject', 'method' => 'POST', 'token' => 'admin', 'category' => 'Review Management', 'description' => 'Reject review'],

    // Forum management routes
    ['url' => 'courses/1/forum', 'method' => 'GET', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Get course forum'],
    ['url' => 'courses/1/forum', 'method' => 'POST', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Create forum topic', 'data' => ['title' => 'Test Topic', 'content' => 'Test Content']],
    ['url' => 'courses/1/forum/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Forum Management', 'description' => 'Get forum analytics'],
    ['url' => "forum/topics/$forumTopicId", 'method' => 'GET', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Get forum topic'],
    ['url' => "forum/topics/$forumTopicId", 'method' => 'PUT', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Update forum topic', 'data' => ['title' => 'Updated Topic']],
    ['url' => "forum/topics/$forumTopicId", 'method' => 'DELETE', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Delete forum topic'],
    ['url' => "forum/topics/$forumTopicId/subscribe", 'method' => 'POST', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Subscribe to topic'],
    ['url' => "forum/topics/$forumTopicId/unsubscribe", 'method' => 'DELETE', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Unsubscribe from topic'],
    ['url' => "forum/topics/$forumTopicId/posts", 'method' => 'POST', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Create forum post', 'data' => ['content' => 'Test post']],
    ['url' => "forum/posts/$forumPostId", 'method' => 'PUT', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Update forum post', 'data' => ['content' => 'Updated post']],
    ['url' => "forum/posts/$forumPostId", 'method' => 'DELETE', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Delete forum post'],
    ['url' => "forum/posts/$forumPostId/like", 'method' => 'POST', 'token' => 'student', 'category' => 'Forum Management', 'description' => 'Like forum post'],
    ['url' => "forum/posts/$forumPostId/solution", 'method' => 'POST', 'token' => 'instructor', 'category' => 'Forum Management', 'description' => 'Mark as solution'],

    // Certificate management routes
    ['url' => 'certificates', 'method' => 'GET', 'token' => 'student', 'category' => 'Certificate Management', 'description' => 'Get certificates'],
    ['url' => 'certificates/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'Certificate Management', 'description' => 'Get certificate analytics'],
    ['url' => 'certificates/templates', 'method' => 'GET', 'token' => 'admin', 'category' => 'Certificate Management', 'description' => 'Get certificate templates'],
    ['url' => 'certificates/generate', 'method' => 'POST', 'token' => 'admin', 'category' => 'Certificate Management', 'description' => 'Generate certificate', 'data' => ['user_id' => 2, 'course_id' => 1]],
    ['url' => 'certificates/bulk-generate', 'method' => 'POST', 'token' => 'admin', 'category' => 'Certificate Management', 'description' => 'Bulk generate certificates', 'data' => ['course_id' => 1]],
    ['url' => 'certificates/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Certificate Management', 'description' => 'Get single certificate'],
    ['url' => 'certificates/1/download', 'method' => 'GET', 'token' => 'student', 'category' => 'Certificate Management', 'description' => 'Download certificate'],
    ['url' => 'certificates/1/revoke', 'method' => 'POST', 'token' => 'admin', 'category' => 'Certificate Management', 'description' => 'Revoke certificate'],

    // Badge management routes
    ['url' => 'badges', 'method' => 'GET', 'token' => 'student', 'category' => 'Badge Management', 'description' => 'Get badges'],
    ['url' => 'badges/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Get badge analytics'],
    ['url' => 'badges/leaderboard', 'method' => 'GET', 'token' => 'student', 'category' => 'Badge Management', 'description' => 'Get badge leaderboard'],
    ['url' => 'badges', 'method' => 'POST', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Create badge', 'data' => ['name' => 'Test Badge', 'description' => 'Test Description']],
    ['url' => 'badges/award', 'method' => 'POST', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Award badge', 'data' => ['user_id' => 2, 'badge_id' => $badgeId]],
    ['url' => 'badges/check-automatic/2', 'method' => 'POST', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Check automatic badges'],
    ['url' => "badges/$badgeId", 'method' => 'GET', 'token' => 'student', 'category' => 'Badge Management', 'description' => 'Get single badge'],
    ['url' => "badges/$badgeId", 'method' => 'PUT', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Update badge', 'data' => ['name' => 'Updated Badge']],
    ['url' => "badges/$badgeId", 'method' => 'DELETE', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Delete badge'],
    ['url' => "badges/user-badges/$badgeId/revoke", 'method' => 'POST', 'token' => 'admin', 'category' => 'Badge Management', 'description' => 'Revoke user badge'],

    // Progress tracking routes
    ['url' => 'progress/courses', 'method' => 'GET', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Get course progress'],
    ['url' => 'progress/lessons', 'method' => 'GET', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Get lesson progress'],
    ['url' => 'progress/overall', 'method' => 'GET', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Get overall progress'],
    ['url' => 'progress/update', 'method' => 'POST', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Update progress', 'data' => ['lesson_id' => 1, 'progress' => 50]],
    ['url' => 'progress/certificates', 'method' => 'GET', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Get available certificates'],
    ['url' => 'progress/generate-cert', 'method' => 'POST', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Generate certificate', 'data' => ['course_id' => 1]],
    ['url' => 'progress/achievements', 'method' => 'GET', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Get achievement progress'],
    ['url' => 'progress/streaks', 'method' => 'GET', 'token' => 'student', 'category' => 'Progress Tracking', 'description' => 'Get streak progress'],
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

// PART 3 - FINAL BATCH OF ENDPOINTS
$finalEndpoints = [
    // Grading management routes
    ['url' => 'grading/gradebook/1', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Get gradebook'],
    ['url' => 'grading/courses/1', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Get course grades'],
    ['url' => 'grading/students/2', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Get student grades'],
    ['url' => 'grading/bulk-grade', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Bulk grade', 'data' => ['grades' => []]],
    ['url' => 'grading/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Get grading analytics'],
    ['url' => 'grading/export', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Export grades', 'data' => ['course_id' => 1]],
    ['url' => 'grading/grade-history/2/1', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Get grade history'],
    ['url' => 'grading/weights/1', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Update grade weights', 'data' => ['weights' => []]],
    ['url' => 'grading/comments', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Add grading comments', 'data' => ['student_id' => 2, 'comment' => 'Good work']],
    ['url' => 'grading/reports/1', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Grading Management', 'description' => 'Get grading reports'],

    // Admin management routes
    ['url' => 'admin/dashboard', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Admin dashboard'],
    ['url' => 'admin/users', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get all users'],
    ['url' => 'admin/courses', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get all courses'],
    ['url' => 'admin/payments', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get all payments'],
    ['url' => 'admin/reports', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get admin reports'],
    ['url' => 'admin/settings', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get admin settings'],
    ['url' => 'admin/stats', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get database stats'],
    ['url' => 'admin/users/2/ban', 'method' => 'POST', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Ban user'],
    ['url' => 'admin/users/2/unban', 'method' => 'POST', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Unban user'],
    ['url' => 'admin/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get admin analytics'],
    ['url' => 'admin/bulk-actions', 'method' => 'POST', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Bulk actions', 'data' => ['action' => 'test']],
    ['url' => 'admin/audit-logs', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get audit logs'],
    ['url' => 'admin/maintenance', 'method' => 'POST', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Maintenance mode', 'data' => ['enabled' => false]],
    ['url' => 'admin/clear-cache', 'method' => 'POST', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Clear cache'],
    ['url' => 'admin/database-stats', 'method' => 'GET', 'token' => 'admin', 'category' => 'Admin Management', 'description' => 'Get database stats'],

    // Analytics routes
    ['url' => 'analytics/learning', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Get learning analytics'],
    ['url' => 'analytics/course-performance', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Get course performance'],
    ['url' => 'analytics/student-progress', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Get student progress'],
    ['url' => 'analytics/revenue', 'method' => 'GET', 'token' => 'admin', 'category' => 'Analytics', 'description' => 'Get revenue analytics'],
    ['url' => 'analytics/engagement', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Get engagement analytics'],
    ['url' => 'analytics/comparative', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Get comparative analytics', 'data' => ['courses' => [1, 2]]],
    ['url' => 'analytics/export', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Export analytics', 'data' => ['type' => 'course']],
    ['url' => 'analytics/real-time', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Analytics', 'description' => 'Get real-time analytics'],
    ['url' => 'analytics/predictive', 'method' => 'GET', 'token' => 'admin', 'category' => 'Analytics', 'description' => 'Get predictive analytics'],

    // Learning paths routes
    ['url' => 'learning-paths', 'method' => 'GET', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Get learning paths'],
    ['url' => 'learning-paths', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Learning Paths', 'description' => 'Create learning path', 'data' => ['title' => 'Test Path', 'description' => 'Test Description']],
    ['url' => 'learning-paths/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Get single learning path'],
    ['url' => 'learning-paths/1', 'method' => 'PUT', 'token' => 'instructor', 'category' => 'Learning Paths', 'description' => 'Update learning path', 'data' => ['title' => 'Updated Path']],
    ['url' => 'learning-paths/1', 'method' => 'DELETE', 'token' => 'instructor', 'category' => 'Learning Paths', 'description' => 'Delete learning path'],
    ['url' => 'learning-paths/1/enroll', 'method' => 'POST', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Enroll in learning path'],
    ['url' => 'learning-paths/1/unenroll', 'method' => 'DELETE', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Unenroll from learning path'],
    ['url' => 'learning-paths/my/paths', 'method' => 'GET', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Get my learning paths'],
    ['url' => 'learning-paths/1/progress', 'method' => 'GET', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Get path progress'],
    ['url' => 'learning-paths/1/analytics', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Learning Paths', 'description' => 'Get path analytics'],
    ['url' => 'learning-paths/1/publish', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Learning Paths', 'description' => 'Publish learning path'],
    ['url' => 'learning-paths/1/unpublish', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Learning Paths', 'description' => 'Unpublish learning path'],

    // AI Chat routes
    ['url' => 'chat/start', 'method' => 'POST', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'Start chat session', 'data' => ['context' => 'course']],
    ['url' => 'chat/sessions/1/message', 'method' => 'POST', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'Send message', 'data' => ['message' => 'Hello']],
    ['url' => 'chat/sessions/1', 'method' => 'GET', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'Get session history'],
    ['url' => 'chat/sessions', 'method' => 'GET', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'Get user sessions'],
    ['url' => 'chat/sessions/1/end', 'method' => 'POST', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'End chat session'],
    ['url' => 'chat/sessions/1/rate', 'method' => 'POST', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'Rate chat session', 'data' => ['rating' => 5]],
    ['url' => 'chat/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'AI Chat', 'description' => 'Get chat analytics'],
    ['url' => 'chat/suggestions', 'method' => 'POST', 'token' => 'student', 'category' => 'AI Chat', 'description' => 'Get suggested responses', 'data' => ['context' => 'course']],

    // Recommendation routes
    ['url' => 'recommendations', 'method' => 'GET', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Get recommendations'],
    ['url' => 'recommendations/courses/1', 'method' => 'GET', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Get course-based recommendations'],
    ['url' => 'recommendations/learning-paths', 'method' => 'GET', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Get learning path recommendations'],
    ['url' => 'recommendations/instructors', 'method' => 'GET', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Get instructor recommendations'],
    ['url' => 'recommendations/content', 'method' => 'GET', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Get content recommendations'],
    ['url' => 'recommendations/preferences', 'method' => 'PUT', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Update recommendation preferences', 'data' => ['categories' => [1, 2]]],
    ['url' => 'recommendations/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'Recommendations', 'description' => 'Get recommendation analytics'],

    // Coupon management routes
    ['url' => 'coupons', 'method' => 'GET', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Get coupons'],
    ['url' => 'coupons', 'method' => 'POST', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Create coupon', 'data' => ['code' => 'TEST10', 'discount' => 10]],
    ['url' => "coupons/$couponId", 'method' => 'GET', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Get single coupon'],
    ['url' => "coupons/$couponId", 'method' => 'PUT', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Update coupon', 'data' => ['discount' => 15]],
    ['url' => "coupons/$couponId", 'method' => 'DELETE', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Delete coupon'],
    ['url' => 'coupons/validate', 'method' => 'POST', 'token' => 'student', 'category' => 'Coupons', 'description' => 'Validate coupon', 'data' => ['code' => 'TEST10']],
    ['url' => 'coupons/apply', 'method' => 'POST', 'token' => 'student', 'category' => 'Coupons', 'description' => 'Apply coupon', 'data' => ['code' => 'TEST10', 'course_id' => 1]],
    ['url' => 'coupons/user/available', 'method' => 'GET', 'token' => 'student', 'category' => 'Coupons', 'description' => 'Get user coupons'],
    ['url' => 'coupons/admin/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Get coupon analytics'],
    ['url' => 'coupons/bulk-action', 'method' => 'POST', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Bulk coupon action', 'data' => ['action' => 'activate', 'coupon_ids' => [1]]],

    // Report generation routes
    ['url' => 'reports/types', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Reports', 'description' => 'Get report types'],
    ['url' => 'reports/financial', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Reports', 'description' => 'Generate financial report', 'data' => ['period' => 'monthly']],
    ['url' => 'reports/academic', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Reports', 'description' => 'Generate academic report', 'data' => ['course_id' => 1]],
    ['url' => 'reports/user', 'method' => 'POST', 'token' => 'admin', 'category' => 'Reports', 'description' => 'Generate user report', 'data' => ['user_type' => 'student']],
    ['url' => 'reports/content', 'method' => 'POST', 'token' => 'instructor', 'category' => 'Reports', 'description' => 'Generate content report', 'data' => ['content_type' => 'course']],
    ['url' => 'reports/scheduled', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Reports', 'description' => 'Get scheduled reports'],
    ['url' => 'reports/schedule', 'method' => 'POST', 'token' => 'admin', 'category' => 'Reports', 'description' => 'Schedule report', 'data' => ['type' => 'financial', 'frequency' => 'weekly']],
    ['url' => 'reports/history', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Reports', 'description' => 'Get report history'],

    // System settings routes
    ['url' => 'settings', 'method' => 'GET', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get all settings'],
    ['url' => 'settings/app_name', 'method' => 'GET', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get single setting'],
    ['url' => 'settings/app_name', 'method' => 'PUT', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Update single setting', 'data' => ['value' => 'Kokokah LMS']],
    ['url' => 'settings', 'method' => 'PUT', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Update bulk settings', 'data' => ['app_name' => 'Kokokah LMS']],
    ['url' => 'settings/reset', 'method' => 'POST', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Reset settings'],
    ['url' => 'settings/email/config', 'method' => 'GET', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get email settings'],
    ['url' => 'settings/payment/config', 'method' => 'GET', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get payment settings'],
    ['url' => 'settings/features/toggles', 'method' => 'GET', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get feature toggles'],

    // Audit and security routes
    ['url' => 'audit/logs', 'method' => 'GET', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get audit logs'],
    ['url' => 'audit/logs/1', 'method' => 'GET', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get single audit log'],
    ['url' => 'audit/users/2/activity', 'method' => 'GET', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get user activity'],
    ['url' => 'audit/system/events', 'method' => 'GET', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get system events'],
    ['url' => 'audit/security/events', 'method' => 'GET', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get security events'],
    ['url' => 'audit/export', 'method' => 'POST', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Export audit logs', 'data' => ['format' => 'csv']],

    // Notification routes
    ['url' => 'notifications', 'method' => 'GET', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Get notifications'],
    ['url' => "notifications/$notificationId/read", 'method' => 'PUT', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Mark notification as read'],
    ['url' => 'notifications/read-all', 'method' => 'PUT', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Mark all notifications as read'],
    ['url' => "notifications/$notificationId", 'method' => 'DELETE', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Delete notification'],
    ['url' => 'notifications/preferences', 'method' => 'GET', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Get notification preferences'],
    ['url' => 'notifications/preferences', 'method' => 'PUT', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Update notification preferences', 'data' => ['email_enabled' => true]],
    ['url' => 'notifications/send', 'method' => 'POST', 'token' => 'admin', 'category' => 'Notifications', 'description' => 'Send notification', 'data' => ['user_id' => 2, 'message' => 'Test notification']],
    ['url' => 'notifications/broadcast', 'method' => 'POST', 'token' => 'admin', 'category' => 'Notifications', 'description' => 'Broadcast notification', 'data' => ['message' => 'System maintenance']],
    ['url' => 'notifications/analytics', 'method' => 'GET', 'token' => 'admin', 'category' => 'Notifications', 'description' => 'Get notification analytics'],

    // Search routes
    ['url' => 'search', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'Global search', 'data' => ['q' => 'test']],
    ['url' => 'search/global', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'Global search', 'data' => ['q' => 'test']],
    ['url' => 'search/courses', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'Course search', 'data' => ['q' => 'test']],
    ['url' => 'search/users', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'User search', 'data' => ['q' => 'test']],
    ['url' => 'search/content', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'Content search', 'data' => ['q' => 'test']],
    ['url' => 'search/suggestions', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'Get search suggestions', 'data' => ['q' => 'te']],
    ['url' => 'search/filters', 'method' => 'GET', 'token' => 'student', 'category' => 'Search', 'description' => 'Get search filters'],

    // File management routes
    ['url' => 'files/upload', 'method' => 'POST', 'token' => 'student', 'category' => 'File Management', 'description' => 'Upload file', 'data' => ['file' => 'test.txt']],
    ['url' => 'files/download/1', 'method' => 'GET', 'token' => 'student', 'category' => 'File Management', 'description' => 'Download file'],
    ['url' => 'files/1', 'method' => 'DELETE', 'token' => 'student', 'category' => 'File Management', 'description' => 'Delete file'],
    ['url' => 'files/list', 'method' => 'GET', 'token' => 'student', 'category' => 'File Management', 'description' => 'List files'],
    ['url' => 'files/preview/1', 'method' => 'GET', 'token' => 'student', 'category' => 'File Management', 'description' => 'Preview file'],
    ['url' => 'files/1/share', 'method' => 'POST', 'token' => 'student', 'category' => 'File Management', 'description' => 'Share file', 'data' => ['user_id' => 3]],
    ['url' => 'files/organize', 'method' => 'POST', 'token' => 'student', 'category' => 'File Management', 'description' => 'Organize files', 'data' => ['folder' => 'documents']],
    ['url' => 'files/storage/stats', 'method' => 'GET', 'token' => 'student', 'category' => 'File Management', 'description' => 'Get storage stats'],

    // Special admin/instructor routes
    ['url' => 'admin/dashboard', 'method' => 'GET', 'token' => 'admin', 'category' => 'Special Routes', 'description' => 'Admin dashboard (special)'],
    ['url' => 'admin/reports', 'method' => 'GET', 'token' => 'admin', 'category' => 'Special Routes', 'description' => 'Admin reports (special)'],
    ['url' => 'instructor/courses', 'method' => 'GET', 'token' => 'instructor', 'category' => 'Special Routes', 'description' => 'Instructor courses (special)'],
    ['url' => 'dashboard', 'method' => 'GET', 'token' => 'student', 'category' => 'Special Routes', 'description' => 'Verified dashboard'],

    // Logout endpoint (tested LAST because it deletes all tokens)
    ['url' => 'logout', 'method' => 'POST', 'token' => 'student', 'category' => 'Auth', 'description' => 'Logout user'],
];

// Test final endpoints
foreach ($finalEndpoints as $endpoint) {
    $results['total']++;
    $token = $endpoint['token'] ? $authTokens[$endpoint['token']] : null;
    $data = $endpoint['data'] ?? null;

    // Replace placeholders in URL
    $url = $endpoint['url'];
    if (strpos($url, '{enrollmentId}') !== false) {
        echo "DEBUG: Before replacement: $url, enrollmentId=$enrollmentId\n";
    }
    $url = str_replace('{enrollmentId}', $enrollmentId, $url);
    $url = str_replace('{badgeId}', $badgeId, $url);
    $url = str_replace('{couponId}', $couponId, $url);
    $url = str_replace('{forumTopicId}', $forumTopicId, $url);
    $url = str_replace('{forumPostId}', $forumPostId, $url);
    $url = str_replace('{notificationId}', $notificationId, $url);
    if (strpos($endpoint['url'], '{enrollmentId}') !== false) {
        echo "DEBUG: After replacement: $url\n";
    }

    $response = makeRequest($url, $endpoint['method'], $token, $data);
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
        echo "✅ {$endpoint['method']} $url ({$category})\n";
    } else {
        $results['failed']++;
        echo "❌ {$endpoint['method']} $url - $status ({$category}) - {$endpoint['description']}\n";

        // Track 500 errors specifically
        if ($status >= 500) {
            $results['errors_500'][] = [
                'endpoint' => $endpoint,
                'status' => $status,
                'response' => $response['body'],
                'url' => $url
            ];
        }
    }
}

echo "\n========================================================\n";
echo "🎯 COMPREHENSIVE ENDPOINT TEST RESULTS - ALL ENDPOINTS\n";
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
        $displayUrl = $error['url'] ?? $error['endpoint']['url'];
        echo "❌ {$error['endpoint']['method']} $displayUrl - {$error['status']}\n";
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
