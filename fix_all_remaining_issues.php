<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use App\Models\Certificate;
use Illuminate\Support\Facades\Hash;

echo "🔧 FIXING ALL REMAINING ENDPOINT ISSUES\n";
echo "=======================================\n\n";

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

echo "🔐 Authentication tokens loaded\n";

// Step 1: Create test data to support endpoints
echo "📊 Creating test data for endpoints...\n";

// Get users first
$admin = User::where('email', 'admin@kokokah.com')->first();
$student = User::where('email', 'student@kokokah.com')->first();
$instructor = User::where('email', 'instructor@kokokah.com')->first();

// Get or create a category
$category = Category::firstOrCreate(
    ['title' => 'Test Category'],
    [
        'title' => 'Test Category',
        'description' => 'Test category for endpoint testing',
        'user_id' => $admin->id
    ]
);

// Create a test course
$course = Course::firstOrCreate(
    ['title' => 'Test Course for Endpoints'],
    [
        'description' => 'Test course for endpoint validation',
        'price' => 99.99,
        'category_id' => $category->id,
        'instructor_id' => $instructor->id,
        'is_published' => true,
        'level' => 'beginner',
        'duration' => 120
    ]
);

// Create enrollment for student
$enrollment = Enrollment::firstOrCreate(
    [
        'user_id' => $student->id,
        'course_id' => $course->id
    ],
    [
        'enrolled_at' => now(),
        'status' => 'active'
    ]
);

// Create a certificate for testing
$certificate = Certificate::firstOrCreate(
    [
        'user_id' => $student->id,
        'course_id' => $course->id
    ],
    [
        'user_id' => $student->id,
        'course_id' => $course->id,
        'certificate_number' => 'CERT-TEST-' . strtoupper(uniqid()),
        'certificate_url' => 'https://example.com/certificates/test.pdf',
        'issued_at' => now()
    ]
);

echo "✅ Test data created successfully\n";
echo "   • Category ID: {$category->id}\n";
echo "   • Course ID: {$course->id}\n";
echo "   • Enrollment ID: {$enrollment->id}\n";
echo "   • Certificate ID: {$certificate->id}\n\n";

// Step 2: Test the previously failing endpoints with proper parameters
function makeRequest($url, $method = 'GET', $token = null, $data = []) {
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
    
    if (!empty($data) && in_array($method, ['POST', 'PUT', 'PATCH'])) {
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

echo "🧪 Testing previously failing endpoints with proper parameters:\n\n";

$tests = [
    [
        'name' => 'Enrollments Certificates',
        'url' => 'api/enrollments/certificates',
        'token' => $authTokens['student'],
        'expected' => 200
    ],
    [
        'name' => 'Progress Lessons with course_id',
        'url' => "api/progress/lessons?course_id={$course->id}",
        'token' => $authTokens['student'],
        'expected' => 200
    ],
    [
        'name' => 'Search Content with parameters',
        'url' => "api/search/content?q=test&course_id={$course->id}",
        'token' => $authTokens['student'],
        'expected' => 200
    ],
    [
        'name' => 'Dashboard Instructor with instructor token',
        'url' => 'api/dashboard/instructor',
        'token' => $authTokens['instructor'],
        'expected' => 200
    ],
    [
        'name' => 'Instructor Courses with instructor token',
        'url' => 'api/instructor/courses',
        'token' => $authTokens['instructor'],
        'expected' => 200
    ]
];

$results = ['success' => 0, 'failed' => 0, 'total' => count($tests)];

foreach ($tests as $test) {
    $response = makeRequest($test['url'], 'GET', $test['token']);
    $status = $response['status'];
    
    if ($status == $test['expected']) {
        echo "✅ {$test['name']} - $status\n";
        $results['success']++;
    } else {
        echo "❌ {$test['name']} - $status (expected {$test['expected']})\n";
        $results['failed']++;
        
        // Show error details
        if ($status >= 400) {
            $body = json_decode($response['body'], true);
            if (isset($body['message'])) {
                echo "   Error: {$body['message']}\n";
            }
        }
    }
}

echo "\n=======================================\n";
echo "📊 FIX RESULTS\n";
echo "=======================================\n";
echo "Total Tests: {$results['total']}\n";
echo "✅ Fixed: {$results['success']}\n";
echo "❌ Still Failing: {$results['failed']}\n";
echo "📈 Fix Rate: " . round(($results['success'] / $results['total']) * 100, 2) . "%\n\n";

if ($results['failed'] == 0) {
    echo "🎉 ALL ISSUES FIXED! Your platform is ready!\n";
} else {
    echo "⚠️  Some issues remain. Check the errors above.\n";
}

echo "\n🎯 Test data created for comprehensive endpoint testing:\n";
echo "   • Use course_id={$course->id} for course-specific endpoints\n";
echo "   • Use enrollment_id={$enrollment->id} for enrollment endpoints\n";
echo "   • Use certificate_id={$certificate->id} for certificate endpoints\n";
echo "=======================================\n";
