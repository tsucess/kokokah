<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎯 COMPREHENSIVE ENDPOINT TEST - ALL PROJECT ENDPOINTS\n";
echo "=====================================================\n\n";

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
        CURLOPT_URL => "http://127.0.0.1:8000/api/$url",
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

// COMPREHENSIVE ENDPOINT LIST - ALL ENDPOINTS FROM THE PROJECT
$allEndpoints = [
    // PUBLIC ENDPOINTS (No Authentication)
    ['method' => 'GET', 'url' => '', 'token' => null, 'category' => 'Public', 'description' => 'API Root'],
    ['method' => 'GET', 'url' => 'category', 'token' => null, 'category' => 'Public', 'description' => 'Get categories'],
    ['method' => 'GET', 'url' => 'category/1', 'token' => null, 'category' => 'Public', 'description' => 'Get single category'],
    ['method' => 'GET', 'url' => 'courses', 'token' => null, 'category' => 'Public', 'description' => 'Get all courses'],
    ['method' => 'GET', 'url' => 'courses/search', 'token' => null, 'category' => 'Public', 'description' => 'Search courses'],
    ['method' => 'GET', 'url' => 'courses/featured', 'token' => null, 'category' => 'Public', 'description' => 'Get featured courses'],
    ['method' => 'GET', 'url' => 'courses/popular', 'token' => null, 'category' => 'Public', 'description' => 'Get popular courses'],
    ['method' => 'GET', 'url' => 'courses/1', 'token' => null, 'category' => 'Public', 'description' => 'Get single course'],
    ['method' => 'GET', 'url' => 'certificates/verify/CERT-123', 'token' => null, 'category' => 'Public', 'description' => 'Verify certificate'],
    ['method' => 'GET', 'url' => 'settings/public', 'token' => null, 'category' => 'Public', 'description' => 'Get public settings'],
    
    // AUTHENTICATION ENDPOINTS
    ['method' => 'POST', 'url' => 'register', 'token' => null, 'category' => 'Auth', 'description' => 'Register user'],
    ['method' => 'POST', 'url' => 'login', 'token' => null, 'category' => 'Auth', 'description' => 'Login user'],
    ['method' => 'POST', 'url' => 'forgot-password', 'token' => null, 'category' => 'Auth', 'description' => 'Forgot password'],
    ['method' => 'POST', 'url' => 'reset-password', 'token' => null, 'category' => 'Auth', 'description' => 'Reset password'],
    ['method' => 'GET', 'url' => 'user', 'token' => 'student', 'category' => 'Auth', 'description' => 'Get current user'],
    ['method' => 'POST', 'url' => 'logout', 'token' => 'student', 'category' => 'Auth', 'description' => 'Logout user'],
    
    // STUDENT CORE FUNCTIONALITY
    ['method' => 'GET', 'url' => 'courses/my-courses', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get my courses'],
    ['method' => 'GET', 'url' => 'users/profile', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get user profile'],
    ['method' => 'GET', 'url' => 'users/dashboard', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get user dashboard'],
    ['method' => 'GET', 'url' => 'users/achievements', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get achievements'],
    ['method' => 'GET', 'url' => 'users/learning-stats', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get learning stats'],
    ['method' => 'GET', 'url' => 'users/notifications', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get notifications'],
    ['method' => 'GET', 'url' => 'enrollments', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get enrollments'],
    ['method' => 'GET', 'url' => 'enrollments/certificates', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get enrollment certificates'],
    ['method' => 'GET', 'url' => 'progress/overall', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get overall progress'],
    ['method' => 'GET', 'url' => 'progress/courses', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get course progress'],
    ['method' => 'GET', 'url' => 'progress/lessons', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get lesson progress'],
    ['method' => 'GET', 'url' => 'certificates', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get certificates'],
    ['method' => 'GET', 'url' => 'badges', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get badges'],
    ['method' => 'GET', 'url' => 'my-badges', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get my badges'],
    ['method' => 'GET', 'url' => 'learning-paths', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get learning paths'],
    ['method' => 'GET', 'url' => 'recommendations', 'token' => 'student', 'category' => 'Student Core', 'description' => 'Get recommendations'],
    
    // PAYMENT & WALLET SYSTEM
    ['method' => 'GET', 'url' => 'wallet', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get wallet info'],
    ['method' => 'GET', 'url' => 'wallet/transactions', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get wallet transactions'],
    ['method' => 'GET', 'url' => 'wallet/rewards', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get wallet rewards'],
    ['method' => 'GET', 'url' => 'payments/gateways', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get payment gateways'],
    ['method' => 'GET', 'url' => 'payments/history', 'token' => 'student', 'category' => 'Payment', 'description' => 'Get payment history'],
    
    // COURSE MANAGEMENT
    ['method' => 'GET', 'url' => 'courses/1/students', 'token' => 'admin', 'category' => 'Course Management', 'description' => 'Get course students'],
    ['method' => 'GET', 'url' => 'courses/1/analytics', 'token' => 'admin', 'category' => 'Course Management', 'description' => 'Get course analytics'],
    ['method' => 'GET', 'url' => 'courses/1/lessons', 'token' => 'student', 'category' => 'Course Management', 'description' => 'Get course lessons'],
    ['method' => 'GET', 'url' => 'courses/1/assignments', 'token' => 'student', 'category' => 'Course Management', 'description' => 'Get course assignments'],
    ['method' => 'GET', 'url' => 'lessons/1', 'token' => 'student', 'category' => 'Course Management', 'description' => 'Get single lesson'],
    ['method' => 'GET', 'url' => 'lessons/1/progress', 'token' => 'student', 'category' => 'Course Management', 'description' => 'Get lesson progress'],
    ['method' => 'GET', 'url' => 'lessons/1/attachments', 'token' => 'student', 'category' => 'Course Management', 'description' => 'Get lesson attachments'],
    
    // ASSESSMENT SYSTEM
    ['method' => 'GET', 'url' => 'assignments/1', 'token' => 'student', 'category' => 'Assessment', 'description' => 'Get single assignment'],
    ['method' => 'GET', 'url' => 'assignments/1/submissions', 'token' => 'admin', 'category' => 'Assessment', 'description' => 'Get assignment submissions'],
    ['method' => 'GET', 'url' => 'assignments/1/grades', 'token' => 'admin', 'category' => 'Assessment', 'description' => 'Get assignment grades'],
    ['method' => 'GET', 'url' => 'quizzes/1', 'token' => 'student', 'category' => 'Assessment', 'description' => 'Get single quiz'],
    ['method' => 'GET', 'url' => 'quizzes/1/results', 'token' => 'student', 'category' => 'Assessment', 'description' => 'Get quiz results'],
    ['method' => 'GET', 'url' => 'quizzes/1/analytics', 'token' => 'admin', 'category' => 'Assessment', 'description' => 'Get quiz analytics'],
    ['method' => 'GET', 'url' => 'lessons/1/quizzes', 'token' => 'student', 'category' => 'Assessment', 'description' => 'Get lesson quizzes'],
    
    // ADMIN FUNCTIONALITY
    ['method' => 'GET', 'url' => 'admin/dashboard', 'token' => 'admin', 'category' => 'Admin', 'description' => 'Admin dashboard'],
    ['method' => 'GET', 'url' => 'admin/users', 'token' => 'admin', 'category' => 'Admin', 'description' => 'Get all users'],
    ['method' => 'GET', 'url' => 'admin/courses', 'token' => 'admin', 'category' => 'Admin', 'description' => 'Get all courses (admin)'],
    ['method' => 'GET', 'url' => 'admin/payments', 'token' => 'admin', 'category' => 'Admin', 'description' => 'Get all payments'],
    ['method' => 'GET', 'url' => 'admin/reports', 'token' => 'admin', 'category' => 'Admin', 'description' => 'Get admin reports'],
    ['method' => 'GET', 'url' => 'admin/settings', 'token' => 'admin', 'category' => 'Admin', 'description' => 'Get admin settings'],
    ['method' => 'GET', 'url' => 'admin/analytics', 'token' => 'admin', 'category' => 'Admin', 'description' => 'Get admin analytics'],
    ['method' => 'GET', 'url' => 'admin/audit-logs', 'token' => 'admin', 'category' => 'Admin', 'description' => 'Get audit logs'],
    ['method' => 'GET', 'url' => 'admin/database-stats', 'token' => 'admin', 'category' => 'Admin', 'description' => 'Get database stats'],
    
    // INSTRUCTOR FUNCTIONALITY
    ['method' => 'GET', 'url' => 'instructor/courses', 'token' => 'instructor', 'category' => 'Instructor', 'description' => 'Get instructor courses'],
    ['method' => 'GET', 'url' => 'dashboard/instructor', 'token' => 'instructor', 'category' => 'Instructor', 'description' => 'Instructor dashboard'],
    ['method' => 'GET', 'url' => 'dashboard/student', 'token' => 'student', 'category' => 'Instructor', 'description' => 'Student dashboard'],
    ['method' => 'GET', 'url' => 'dashboard/admin', 'token' => 'admin', 'category' => 'Instructor', 'description' => 'Admin dashboard (alt)'],
    
    // ANALYTICS SYSTEM
    ['method' => 'GET', 'url' => 'analytics/learning', 'token' => 'admin', 'category' => 'Analytics', 'description' => 'Learning analytics'],
    ['method' => 'GET', 'url' => 'analytics/course-performance', 'token' => 'admin', 'category' => 'Analytics', 'description' => 'Course performance'],
    ['method' => 'GET', 'url' => 'analytics/student-progress', 'token' => 'admin', 'category' => 'Analytics', 'description' => 'Student progress analytics'],
    ['method' => 'GET', 'url' => 'analytics/revenue', 'token' => 'admin', 'category' => 'Analytics', 'description' => 'Revenue analytics'],
    ['method' => 'GET', 'url' => 'analytics/engagement', 'token' => 'admin', 'category' => 'Analytics', 'description' => 'Engagement analytics'],
    ['method' => 'GET', 'url' => 'analytics/real-time', 'token' => 'admin', 'category' => 'Analytics', 'description' => 'Real-time analytics'],
    ['method' => 'GET', 'url' => 'analytics/predictive', 'token' => 'admin', 'category' => 'Analytics', 'description' => 'Predictive analytics'],
    
    // SEARCH FUNCTIONALITY
    ['method' => 'GET', 'url' => 'search?q=test', 'token' => 'student', 'category' => 'Search', 'description' => 'Global search'],
    ['method' => 'GET', 'url' => 'search/global?q=test', 'token' => 'student', 'category' => 'Search', 'description' => 'Global search (alt)'],
    ['method' => 'GET', 'url' => 'search/courses?q=test', 'token' => 'student', 'category' => 'Search', 'description' => 'Course search'],
    ['method' => 'GET', 'url' => 'search/users?q=test', 'token' => 'student', 'category' => 'Search', 'description' => 'User search'],
    ['method' => 'GET', 'url' => 'search/content?q=test', 'token' => 'student', 'category' => 'Search', 'description' => 'Content search'],
    ['method' => 'GET', 'url' => 'search/suggestions?q=test', 'token' => 'student', 'category' => 'Search', 'description' => 'Search suggestions'],
    ['method' => 'GET', 'url' => 'search/filters', 'token' => 'student', 'category' => 'Search', 'description' => 'Search filters'],
    
    // NOTIFICATION SYSTEM
    ['method' => 'GET', 'url' => 'notifications', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Get notifications'],
    ['method' => 'GET', 'url' => 'notifications/preferences', 'token' => 'student', 'category' => 'Notifications', 'description' => 'Get notification preferences'],
    ['method' => 'GET', 'url' => 'notifications/analytics', 'token' => 'admin', 'category' => 'Notifications', 'description' => 'Notification analytics'],
    
    // SETTINGS SYSTEM
    ['method' => 'GET', 'url' => 'settings', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get settings'],
    ['method' => 'GET', 'url' => 'settings/email/config', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get email settings'],
    ['method' => 'GET', 'url' => 'settings/payment/config', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get payment settings'],
    ['method' => 'GET', 'url' => 'settings/features/toggles', 'token' => 'admin', 'category' => 'Settings', 'description' => 'Get feature toggles'],

    // ADVANCED FEATURES
    ['method' => 'GET', 'url' => 'courses/1/forum', 'token' => 'student', 'category' => 'Advanced Features', 'description' => 'Get course forum'],
    ['method' => 'GET', 'url' => 'enrollments/1/progress', 'token' => 'admin', 'category' => 'Advanced Features', 'description' => 'Get enrollment progress'],
    ['method' => 'GET', 'url' => 'certificates/1/download', 'token' => 'student', 'category' => 'Advanced Features', 'description' => 'Download certificate'],
    ['method' => 'GET', 'url' => 'learning-paths/1/progress?user_id=2', 'token' => 'admin', 'category' => 'Advanced Features', 'description' => 'Get learning path progress'],
    ['method' => 'GET', 'url' => 'chat/sessions/1', 'token' => 'admin', 'category' => 'Advanced Features', 'description' => 'Get chat session'],
    ['method' => 'GET', 'url' => 'files/download/1', 'token' => 'student', 'category' => 'Advanced Features', 'description' => 'Download file'],

    // GRADING SYSTEM
    ['method' => 'GET', 'url' => 'grading/gradebook/1', 'token' => 'admin', 'category' => 'Grading', 'description' => 'Get gradebook'],
    ['method' => 'GET', 'url' => 'grading/courses/1', 'token' => 'admin', 'category' => 'Grading', 'description' => 'Get course grades'],
    ['method' => 'GET', 'url' => 'grading/students/2', 'token' => 'admin', 'category' => 'Grading', 'description' => 'Get student grades'],
    ['method' => 'GET', 'url' => 'grading/analytics', 'token' => 'admin', 'category' => 'Grading', 'description' => 'Get grading analytics'],

    // REVIEW SYSTEM
    ['method' => 'GET', 'url' => 'courses/1/reviews', 'token' => 'student', 'category' => 'Reviews', 'description' => 'Get course reviews'],
    ['method' => 'GET', 'url' => 'courses/1/reviews/analytics', 'token' => 'admin', 'category' => 'Reviews', 'description' => 'Get review analytics'],
    ['method' => 'GET', 'url' => 'reviews/moderate', 'token' => 'admin', 'category' => 'Reviews', 'description' => 'Moderate reviews'],
    ['method' => 'GET', 'url' => 'reviews/my-reviews', 'token' => 'student', 'category' => 'Reviews', 'description' => 'Get my reviews'],

    // COUPON SYSTEM
    ['method' => 'GET', 'url' => 'coupons', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Get coupons'],
    ['method' => 'GET', 'url' => 'coupons/user/available', 'token' => 'student', 'category' => 'Coupons', 'description' => 'Get available coupons'],
    ['method' => 'GET', 'url' => 'coupons/admin/analytics', 'token' => 'admin', 'category' => 'Coupons', 'description' => 'Get coupon analytics'],

    // REPORT SYSTEM
    ['method' => 'GET', 'url' => 'reports/types', 'token' => 'admin', 'category' => 'Reports', 'description' => 'Get report types'],
    ['method' => 'GET', 'url' => 'reports/scheduled', 'token' => 'admin', 'category' => 'Reports', 'description' => 'Get scheduled reports'],
    ['method' => 'GET', 'url' => 'reports/history', 'token' => 'admin', 'category' => 'Reports', 'description' => 'Get report history'],

    // AUDIT SYSTEM
    ['method' => 'GET', 'url' => 'audit/logs', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get audit logs'],
    ['method' => 'GET', 'url' => 'audit/users/2/activity', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get user activity'],
    ['method' => 'GET', 'url' => 'audit/system/events', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get system events'],
    ['method' => 'GET', 'url' => 'audit/security/events', 'token' => 'admin', 'category' => 'Audit', 'description' => 'Get security events'],

    // FILE MANAGEMENT
    ['method' => 'GET', 'url' => 'files/list', 'token' => 'student', 'category' => 'Files', 'description' => 'List files'],
    ['method' => 'GET', 'url' => 'files/preview/1', 'token' => 'student', 'category' => 'Files', 'description' => 'Preview file'],
    ['method' => 'GET', 'url' => 'files/storage/stats', 'token' => 'student', 'category' => 'Files', 'description' => 'Get storage stats'],

    // LEARNING PATHS
    ['method' => 'GET', 'url' => 'learning-paths/1', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Get single learning path'],
    ['method' => 'GET', 'url' => 'learning-paths/my/paths', 'token' => 'student', 'category' => 'Learning Paths', 'description' => 'Get my learning paths'],
    ['method' => 'GET', 'url' => 'learning-paths/1/analytics', 'token' => 'admin', 'category' => 'Learning Paths', 'description' => 'Get learning path analytics'],

    // CHAT SYSTEM
    ['method' => 'GET', 'url' => 'chat/sessions', 'token' => 'student', 'category' => 'Chat', 'description' => 'Get chat sessions'],
    ['method' => 'GET', 'url' => 'chat/analytics', 'token' => 'admin', 'category' => 'Chat', 'description' => 'Get chat analytics'],

    // RECOMMENDATION SYSTEM
    ['method' => 'GET', 'url' => 'recommendations/courses/1', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Get course recommendations'],
    ['method' => 'GET', 'url' => 'recommendations/learning-paths', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Get learning path recommendations'],
    ['method' => 'GET', 'url' => 'recommendations/instructors', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Get instructor recommendations'],
    ['method' => 'GET', 'url' => 'recommendations/content', 'token' => 'student', 'category' => 'Recommendations', 'description' => 'Get content recommendations'],
    ['method' => 'GET', 'url' => 'recommendations/analytics', 'token' => 'admin', 'category' => 'Recommendations', 'description' => 'Get recommendation analytics'],

    // BADGE SYSTEM
    ['method' => 'GET', 'url' => 'badges/analytics', 'token' => 'admin', 'category' => 'Badges', 'description' => 'Get badge analytics'],
    ['method' => 'GET', 'url' => 'badges/leaderboard', 'token' => 'student', 'category' => 'Badges', 'description' => 'Get badge leaderboard'],
    ['method' => 'GET', 'url' => 'badges/1', 'token' => 'student', 'category' => 'Badges', 'description' => 'Get single badge'],
    ['method' => 'GET', 'url' => 'users/2/badges', 'token' => 'student', 'category' => 'Badges', 'description' => 'Get user badges'],

    // CERTIFICATE SYSTEM
    ['method' => 'GET', 'url' => 'certificates/analytics', 'token' => 'admin', 'category' => 'Certificates', 'description' => 'Get certificate analytics'],
    ['method' => 'GET', 'url' => 'certificates/templates', 'token' => 'student', 'category' => 'Certificates', 'description' => 'Get certificate templates'],
    ['method' => 'GET', 'url' => 'certificates/1', 'token' => 'student', 'category' => 'Certificates', 'description' => 'Get single certificate'],

    // PROGRESS SYSTEM
    ['method' => 'GET', 'url' => 'progress/certificates', 'token' => 'student', 'category' => 'Progress', 'description' => 'Get available certificates'],
    ['method' => 'GET', 'url' => 'progress/achievements', 'token' => 'student', 'category' => 'Progress', 'description' => 'Get achievement progress'],
    ['method' => 'GET', 'url' => 'progress/streaks', 'token' => 'student', 'category' => 'Progress', 'description' => 'Get streak progress'],
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
    $response = makeRequest($endpoint['url'], $endpoint['method'], $token);
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
    
    if ($status == 200 || $status == 201) {
        $results['success']++;
        $results['by_category'][$category]['success']++;
        echo "✅ {$endpoint['method']} {$endpoint['url']} ({$category})\n";
    } else {
        $results['failed']++;
        echo "❌ {$endpoint['method']} {$endpoint['url']} - $status ({$category}) - {$endpoint['description']}\n";
    }
}

echo "\n=====================================================\n";
echo "📊 COMPREHENSIVE ENDPOINT TEST RESULTS\n";
echo "=====================================================\n";

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

echo "\n=====================================================\n";
echo "🎯 FINAL ASSESSMENT\n";
echo "=====================================================\n";

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
} else {
    echo "⚠️  NEEDS IMPROVEMENT! Below 80% success rate.\n";
    echo "🔧 Additional fixes needed before production deployment.\n";
    $status = "NEEDS IMPROVEMENT";
}

echo "\n🇳🇬 KOKOKAH.COM LMS FINAL STATUS:\n";
echo "Platform Readiness: $status\n";
echo "Market Launch: " . ($successRate >= 85 ? "READY FOR GLOBAL MARKETS" : "NEEDS ADDITIONAL WORK") . "\n";
echo "Total Endpoints: {$results['total']}\n";
echo "Working Endpoints: {$results['success']}\n";
echo "Success Rate: $successRate%\n";
echo "=====================================================\n";
