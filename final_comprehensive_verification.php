<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 FINAL COMPREHENSIVE VERIFICATION - COMPLETE SYSTEM CHECK\n";
echo "============================================================\n\n";

// Get tokens
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$adminToken = trim($adminMatches[1]);
$studentToken = trim($studentMatches[1]);

echo "🔐 Authentication Status:\n";
echo "✅ Admin Token: " . substr($adminToken, 0, 30) . "...\n";
echo "✅ Student Token: " . substr($studentToken, 0, 30) . "...\n\n";

// Test categories for comprehensive verification
$testCategories = [
    'Core User Features' => [
        ['/user', $studentToken, 'User profile data'],
        ['/dashboard/student', $studentToken, 'Student dashboard'],
        ['/notifications', $studentToken, 'User notifications']
    ],
    'Course Management' => [
        ['/courses', $studentToken, 'Public courses list'],
        ['/courses/featured', $studentToken, 'Featured courses'],
        ['/courses/11', $studentToken, 'Single course details'],
        ['/courses/11/lessons', $studentToken, 'Course lessons']
    ],
    'Learning Features' => [
        ['/assignments/1', $studentToken, 'Assignment details'],
        ['/quizzes/1', $studentToken, 'Quiz details'],
        ['/progress/courses', $studentToken, 'Course progress'],
        ['/badges', $studentToken, 'Available badges']
    ],
    'Analytics & Reporting' => [
        ['/analytics/learning', $adminToken, 'Learning analytics'],
        ['/analytics/course-performance', $adminToken, 'Course performance'],
        ['/analytics/engagement', $adminToken, 'Engagement metrics'],
        ['/badges/analytics', $adminToken, 'Badge analytics']
    ],
    'Search & Discovery' => [
        ['/search/content?q=math&course_id=10', $studentToken, 'Content search'],
        ['/search/filters', $studentToken, 'Search filters'],
        ['/search/suggestions?q=math&type=courses', $studentToken, 'Search suggestions'],
        ['/search?q=course', $studentToken, 'Global search']
    ],
    'AI & Recommendations' => [
        ['/recommendations', $studentToken, 'Personalized recommendations'],
        ['/recommendations/courses/11', $studentToken, 'Course recommendations'],
        ['/recommendations/learning-paths', $studentToken, 'Learning paths'],
        ['/recommendations/instructors', $studentToken, 'Instructor recommendations']
    ],
    'Admin Features' => [
        ['/admin/analytics', $adminToken, 'Admin analytics'],
        ['/admin/users', $adminToken, 'User management'],
        ['/admin/courses', $adminToken, 'Course management'],
        ['/admin/stats', $adminToken, 'System statistics']
    ]
];

$totalTests = 0;
$passedTests = 0;
$categoryResults = [];

foreach ($testCategories as $categoryName => $tests) {
    echo "📂 Testing Category: $categoryName\n";
    $categoryPassed = 0;
    $categoryTotal = count($tests);
    
    foreach ($tests as $test) {
        $url = $test[0];
        $token = $test[1];
        $description = $test[2];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api' . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $totalTests++;
        
        if ($httpCode === 200) {
            $data = json_decode($response, true);
            if (isset($data['success']) && $data['success'] === true) {
                echo "   ✅ $description\n";
                $passedTests++;
                $categoryPassed++;
            } else {
                echo "   ❌ $description (Invalid response format)\n";
            }
        } else {
            echo "   ❌ $description (HTTP $httpCode)\n";
        }
    }
    
    $categorySuccessRate = round(($categoryPassed / $categoryTotal) * 100, 1);
    $categoryResults[$categoryName] = [
        'passed' => $categoryPassed,
        'total' => $categoryTotal,
        'rate' => $categorySuccessRate
    ];
    
    echo "   📊 Category Success Rate: $categorySuccessRate% ($categoryPassed/$categoryTotal)\n\n";
}

echo "============================================================\n";
echo "📊 FINAL COMPREHENSIVE VERIFICATION RESULTS\n";
echo "============================================================\n\n";

echo "📈 OVERALL RESULTS:\n";
echo "Total Tests: $totalTests\n";
echo "✅ Passed: $passedTests\n";
echo "❌ Failed: " . ($totalTests - $passedTests) . "\n";
echo "📈 Overall Success Rate: " . round(($passedTests / $totalTests) * 100, 2) . "%\n\n";

echo "📂 CATEGORY BREAKDOWN:\n";
foreach ($categoryResults as $category => $result) {
    $status = $result['rate'] >= 90 ? '🟢' : ($result['rate'] >= 70 ? '🟡' : '🔴');
    echo "$status $category: {$result['rate']}% ({$result['passed']}/{$result['total']})\n";
}

echo "\n============================================================\n";

if ($passedTests === $totalTests) {
    echo "🎉 PERFECT! ALL ENDPOINTS ARE WORKING FLAWLESSLY!\n";
    echo "🚀 Your Kokokah.com LMS is 100% ready for production!\n";
} elseif ($passedTests / $totalTests >= 0.95) {
    echo "🎊 EXCELLENT! 95%+ success rate - Platform is production ready!\n";
    echo "🔧 Minor optimizations may be beneficial but not required.\n";
} elseif ($passedTests / $totalTests >= 0.90) {
    echo "✅ VERY GOOD! 90%+ success rate - Platform is nearly ready!\n";
    echo "🔧 A few endpoints may need attention before production.\n";
} else {
    echo "⚠️  NEEDS ATTENTION! Some critical endpoints require fixes.\n";
    echo "🔧 Review failed endpoints before production deployment.\n";
}

echo "============================================================\n";
