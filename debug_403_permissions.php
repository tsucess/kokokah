<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 DEBUGGING 403 PERMISSION ERRORS\n";
echo "============================================================\n\n";

use App\Models\User;
use Illuminate\Support\Facades\DB;

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
$studentToken = trim($studentMatches[1]);
$adminToken = trim($adminMatches[1]);

echo "🔑 Checking user roles...\n";

// Check student user (ID 130)
$student = User::find(130);
if ($student) {
    echo "Student User (ID: 130):\n";
    echo "   Email: {$student->email}\n";
    echo "   Role: {$student->role}\n";
    echo "   Is Student: " . ($student->isStudent() ? 'Yes' : 'No') . "\n";
    echo "   Is Instructor: " . ($student->isInstructor() ? 'Yes' : 'No') . "\n";
    echo "   Is Admin: " . ($student->isAdmin() ? 'Yes' : 'No') . "\n\n";
}

// Check admin user (ID 22)
$admin = User::find(22);
if ($admin) {
    echo "Admin User (ID: 22):\n";
    echo "   Email: {$admin->email}\n";
    echo "   Role: {$admin->role}\n";
    echo "   Is Student: " . ($admin->isStudent() ? 'Yes' : 'No') . "\n";
    echo "   Is Instructor: " . ($admin->isInstructor() ? 'Yes' : 'No') . "\n";
    echo "   Is Admin: " . ($admin->isAdmin() ? 'Yes' : 'No') . "\n\n";
}

echo "🧪 Testing 403 endpoints with different tokens...\n\n";

$failing403Endpoints = [
    'Course 11 Lessons' => [
        'url' => '/courses/11/lessons',
        'description' => 'Get course lessons',
        'expected_role' => 'any authenticated'
    ],
    'Course 11 Assignments' => [
        'url' => '/courses/11/assignments',
        'description' => 'Get course assignments',
        'expected_role' => 'any authenticated'
    ],
    'Learning Analytics' => [
        'url' => '/analytics/learning',
        'description' => 'Learning analytics',
        'expected_role' => 'instructor,admin'
    ],
    'Course Performance Analytics' => [
        'url' => '/analytics/course-performance',
        'description' => 'Course performance analytics',
        'expected_role' => 'instructor,admin'
    ],
    'Student Progress Analytics' => [
        'url' => '/analytics/student-progress',
        'description' => 'Student progress analytics',
        'expected_role' => 'instructor,admin'
    ],
    'Engagement Analytics' => [
        'url' => '/analytics/engagement',
        'description' => 'Engagement analytics',
        'expected_role' => 'instructor,admin'
    ]
];

foreach ($failing403Endpoints as $name => $config) {
    echo "🔍 Testing $name: {$config['url']}\n";
    echo "   Expected role: {$config['expected_role']}\n";
    
    // Test with student token
    echo "   📝 Testing with STUDENT token...\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api' . $config['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $studentToken
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "      ✅ SUCCESS with student token! HTTP 200\n";
    } else {
        echo "      ❌ FAILED with student token! HTTP $httpCode\n";
        if ($httpCode === 403) {
            echo "      🔒 Permission denied - testing with admin token...\n";
            
            // Test with admin token
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api' . $config['url']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $adminToken
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200) {
                echo "      ✅ SUCCESS with admin token! HTTP 200\n";
                echo "      💡 Solution: Student needs instructor/admin role for this endpoint\n";
            } else {
                echo "      ❌ FAILED with admin token too! HTTP $httpCode\n";
                echo "      Response: " . substr($response, 0, 150) . "...\n";
            }
        }
    }
    echo "\n";
}

echo "============================================================\n";
echo "✅ 403 PERMISSION DEBUG COMPLETED!\n";
echo "============================================================\n";
