<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 COMPREHENSIVE TEST PART 2 - REMAINING ENDPOINTS\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);

$adminToken = trim($adminMatches[1]);
$studentToken = trim($studentMatches[1]);

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

echo "🎯 TESTING REMAINING ENDPOINT CATEGORIES:\n\n";

// Certificates Endpoints (10 endpoints)
echo "🎖️ CERTIFICATE ENDPOINTS:\n";
$certificateTests = [
    ['Get User Certificates', 'GET', '/certificates', $studentToken],
    ['Get Certificate Analytics', 'GET', '/certificates/analytics', $adminToken],
    ['Get Certificate Templates', 'GET', '/certificates/templates', $adminToken],
    ['Generate Certificate', 'POST', '/certificates/generate', $studentToken, ['course_id' => 11]],
    ['Bulk Generate Certificates', 'POST', '/certificates/bulk-generate', $adminToken, ['course_id' => 11]],
    ['Get Single Certificate', 'GET', '/certificates/1', $studentToken],
    ['Download Certificate', 'GET', '/certificates/1/download', $studentToken],
    ['Revoke Certificate', 'POST', '/certificates/1/revoke', $adminToken],
    ['Verify Certificate (Public)', 'GET', '/certificates/verify/CERT123'],
];

foreach ($certificateTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Badges & Achievements Endpoints (13 endpoints)
echo "\n🏆 BADGES & ACHIEVEMENTS ENDPOINTS:\n";
$badgeTests = [
    ['Get All Badges', 'GET', '/badges', $studentToken],
    ['Get Badge Analytics', 'GET', '/badges/analytics', $adminToken],
    ['Get Badge Leaderboard', 'GET', '/badges/leaderboard', $studentToken],
    ['Create Badge', 'POST', '/badges', $adminToken, ['name' => 'Test Badge', 'description' => 'Test', 'points' => 10]],
    ['Award Badge to User', 'POST', '/badges/award', $adminToken, ['user_id' => 21, 'badge_id' => 1]],
    ['Check Automatic Badges', 'POST', '/badges/check-automatic/21', $adminToken],
    ['Get Single Badge', 'GET', '/badges/1', $studentToken],
    ['Update Badge', 'PUT', '/badges/1', $adminToken, ['name' => 'Updated Badge']],
    ['Delete Badge', 'DELETE', '/badges/999', $adminToken],
    ['Revoke User Badge', 'POST', '/badges/user-badges/1/revoke', $adminToken],
    ['Get User Badges', 'GET', '/users/21/badges', $studentToken],
    ['Get My Badges', 'GET', '/my-badges', $studentToken],
];

foreach ($badgeTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Progress Tracking Endpoints (8 endpoints)
echo "\n📈 PROGRESS TRACKING ENDPOINTS:\n";
$progressTests = [
    ['Get Course Progress', 'GET', '/progress/courses', $studentToken],
    ['Get Lesson Progress', 'GET', '/progress/lessons', $studentToken],
    ['Get Overall Progress', 'GET', '/progress/overall', $studentToken],
    ['Update Progress', 'POST', '/progress/update', $studentToken, ['lesson_id' => 1, 'progress' => 75]],
    ['Get Available Certificates', 'GET', '/progress/certificates', $studentToken],
    ['Generate Certificate', 'POST', '/progress/generate-cert', $studentToken, ['course_id' => 11]],
    ['Get Achievement Progress', 'GET', '/progress/achievements', $studentToken],
    ['Get Streak Progress', 'GET', '/progress/streaks', $studentToken],
];

foreach ($progressTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Analytics Endpoints (9 endpoints)
echo "\n📊 ANALYTICS ENDPOINTS:\n";
$analyticsTests = [
    ['Learning Analytics', 'GET', '/analytics/learning', $adminToken],
    ['Course Performance', 'GET', '/analytics/course-performance', $adminToken],
    ['Student Progress', 'GET', '/analytics/student-progress', $adminToken],
    ['Revenue Analytics', 'GET', '/analytics/revenue', $adminToken],
    ['Engagement Analytics', 'GET', '/analytics/engagement', $adminToken],
    ['Comparative Analytics', 'POST', '/analytics/comparative', $adminToken, ['courses' => [11, 12]]],
    ['Export Analytics', 'POST', '/analytics/export', $adminToken, ['type' => 'pdf']],
    ['Real-time Analytics', 'GET', '/analytics/real-time', $adminToken],
    ['Predictive Analytics', 'GET', '/analytics/predictive', $adminToken],
];

foreach ($analyticsTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Recommendations Endpoints (7 endpoints)
echo "\n🎯 RECOMMENDATION ENDPOINTS:\n";
$recommendationTests = [
    ['Get Personalized Recommendations', 'GET', '/recommendations', $studentToken],
    ['Course-based Recommendations', 'GET', '/recommendations/courses/11', $studentToken],
    ['Learning Path Recommendations', 'GET', '/recommendations/learning-paths', $studentToken],
    ['Instructor Recommendations', 'GET', '/recommendations/instructors', $studentToken],
    ['Content Recommendations', 'GET', '/recommendations/content', $studentToken],
    ['Update Recommendation Preferences', 'PUT', '/recommendations/preferences', $studentToken, ['categories' => ['math', 'science']]],
    ['Recommendation Analytics', 'GET', '/recommendations/analytics', $adminToken],
];

foreach ($recommendationTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// AI Chat Endpoints (8 endpoints)
echo "\n💬 AI CHAT ENDPOINTS:\n";
$chatTests = [
    ['Start Chat Session', 'POST', '/chat/start', $studentToken, ['course_id' => 11]],
    ['Send Message', 'POST', '/chat/sessions/1/message', $studentToken, ['message' => 'Hello']],
    ['Get Session History', 'GET', '/chat/sessions/1', $studentToken],
    ['Get User Sessions', 'GET', '/chat/sessions', $studentToken],
    ['End Session', 'POST', '/chat/sessions/1/end', $studentToken],
    ['Rate Session', 'POST', '/chat/sessions/1/rate', $studentToken, ['rating' => 5]],
    ['Chat Analytics', 'GET', '/chat/analytics', $adminToken],
    ['Get Suggested Responses', 'POST', '/chat/suggestions', $studentToken, ['message' => 'Help me']],
];

foreach ($chatTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Search Endpoints (6 endpoints)
echo "\n🔍 SEARCH ENDPOINTS:\n";
$searchTests = [
    ['Global Search', 'GET', '/search/global?q=math', $studentToken],
    ['Course Search', 'GET', '/search/courses?q=introduction', $studentToken],
    ['User Search', 'GET', '/search/users?q=admin', $studentToken],
    ['Content Search', 'GET', '/search/content?q=lesson', $studentToken],
    ['Search Suggestions', 'GET', '/search/suggestions?q=ma', $studentToken],
    ['Get Search Filters', 'GET', '/search/filters', $studentToken],
];

foreach ($searchTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Notifications Endpoints (9 endpoints)
echo "\n🔔 NOTIFICATION ENDPOINTS:\n";
$notificationTests = [
    ['Get Notifications', 'GET', '/notifications', $studentToken],
    ['Mark as Read', 'PUT', '/notifications/1/read', $studentToken],
    ['Mark All as Read', 'PUT', '/notifications/read-all', $studentToken],
    ['Delete Notification', 'DELETE', '/notifications/1', $studentToken],
    ['Get Notification Preferences', 'GET', '/notifications/preferences', $studentToken],
    ['Update Notification Preferences', 'PUT', '/notifications/preferences', $studentToken, ['email_notifications' => true]],
    ['Send Notification', 'POST', '/notifications/send', $adminToken, ['user_id' => 21, 'message' => 'Test']],
    ['Broadcast Notification', 'POST', '/notifications/broadcast', $adminToken, ['message' => 'Broadcast test']],
    ['Notification Analytics', 'GET', '/notifications/analytics', $adminToken],
];

foreach ($notificationTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// File Management Endpoints (8 endpoints)
echo "\n📁 FILE MANAGEMENT ENDPOINTS:\n";
$fileTests = [
    ['Upload File', 'POST', '/files/upload', $studentToken, ['file' => 'test.pdf']],
    ['Download File', 'GET', '/files/download/1', $studentToken],
    ['Delete File', 'DELETE', '/files/1', $studentToken],
    ['List User Files', 'GET', '/files/list', $studentToken],
    ['Preview File', 'GET', '/files/preview/1', $studentToken],
    ['Share File', 'POST', '/files/1/share', $studentToken, ['user_id' => 22]],
    ['Organize Files', 'POST', '/files/organize', $studentToken, ['folder' => 'documents']],
    ['Get Storage Statistics', 'GET', '/files/storage/stats', $studentToken],
];

foreach ($fileTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Admin Management Endpoints (15 endpoints)
echo "\n⚙️ ADMIN MANAGEMENT ENDPOINTS:\n";
$adminTests = [
    ['Admin Dashboard', 'GET', '/admin/dashboard', $adminToken],
    ['Get All Users', 'GET', '/admin/users', $adminToken],
    ['Get All Courses', 'GET', '/admin/courses', $adminToken],
    ['Get All Payments', 'GET', '/admin/payments', $adminToken],
    ['Get Admin Reports', 'GET', '/admin/reports', $adminToken],
    ['Get Admin Settings', 'GET', '/admin/settings', $adminToken],
    ['Ban User', 'POST', '/admin/users/21/ban', $adminToken],
    ['Unban User', 'POST', '/admin/users/21/unban', $adminToken],
    ['Admin Analytics', 'GET', '/admin/analytics', $adminToken],
    ['Bulk Actions', 'POST', '/admin/bulk-actions', $adminToken, ['action' => 'activate', 'user_ids' => [21]]],
    ['Get Audit Logs', 'GET', '/admin/audit-logs', $adminToken],
    ['Toggle Maintenance Mode', 'POST', '/admin/maintenance', $adminToken, ['enabled' => false]],
    ['Clear System Cache', 'POST', '/admin/clear-cache', $adminToken],
    ['Get Database Statistics', 'GET', '/admin/database-stats', $adminToken],
];

foreach ($adminTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

// Categories Endpoints (5 endpoints)
echo "\n📋 CATEGORY ENDPOINTS:\n";
$categoryTests = [
    ['Get All Categories', 'GET', '/category'],
    ['Create Category', 'POST', '/category', $adminToken, ['name' => 'Test Category', 'description' => 'Test']],
    ['Get Single Category', 'GET', '/category/1'],
    ['Update Category', 'PUT', '/category/1', $adminToken, ['name' => 'Updated Category']],
    ['Delete Category', 'DELETE', '/category/999', $adminToken],
];

foreach ($categoryTests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

echo "\n============================================================\n";
echo "📊 PART 2 TEST RESULTS\n";
echo "============================================================\n";
echo "Total Tests: $totalTests\n";
echo "✅ Passed: $passedTests\n";
echo "❌ Failed: " . ($totalTests - $passedTests) . "\n";
echo "📈 Success Rate: " . round(($passedTests / $totalTests) * 100, 2) . "%\n";
echo "============================================================\n";
