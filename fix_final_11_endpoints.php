<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Assignment;
use App\Models\ChatSession;
use App\Models\File;
use Illuminate\Support\Facades\Hash;

echo "🔧 FIXING FINAL 11 FAILING ENDPOINTS\n";
echo "====================================\n\n";

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

echo "1. Creating missing test data for failing endpoints...\n";

// Get existing users
$admin = User::find(1);
$student = User::find(2);
$course = Course::find(1);

// Create lesson with ID=1 if it doesn't exist
$lesson = Lesson::find(1);
if (!$lesson) {
    echo "   Creating lesson with ID=1...\n";
    $lesson = new Lesson();
    $lesson->id = 1;
    $lesson->course_id = $course->id;
    $lesson->title = 'Test Lesson 1';
    $lesson->content = 'Test lesson content for endpoint testing';
    $lesson->order = 1;
    $lesson->duration = 30;
    $lesson->is_published = true;
    $lesson->save();
    echo "   ✅ Lesson created with ID=1\n";
} else {
    echo "   ✅ Lesson with ID=1 already exists\n";
}

// Create assignment with ID=1 if it doesn't exist
$assignment = Assignment::find(1);
if (!$assignment) {
    echo "   Creating assignment with ID=1...\n";
    $assignment = new Assignment();
    $assignment->id = 1;
    $assignment->course_id = $course->id;
    $assignment->title = 'Test Assignment 1';
    $assignment->description = 'Test assignment for endpoint testing';
    $assignment->due_date = now()->addDays(7);
    $assignment->max_score = 100;
    $assignment->is_published = true;
    $assignment->save();
    echo "   ✅ Assignment created with ID=1\n";
} else {
    echo "   ✅ Assignment with ID=1 already exists\n";
}

// Create chat session with ID=1 if it doesn't exist
$chatSession = ChatSession::find(1);
if (!$chatSession) {
    echo "   Creating chat session with ID=1...\n";
    $chatSession = new ChatSession();
    $chatSession->id = 1;
    $chatSession->user_id = $student->id;
    $chatSession->instructor_id = $admin->id;
    $chatSession->course_id = $course->id;
    $chatSession->status = 'active';
    $chatSession->started_at = now();
    $chatSession->save();
    echo "   ✅ Chat session created with ID=1\n";
} else {
    echo "   ✅ Chat session with ID=1 already exists\n";
}

// Update the file record to have proper path
$file = File::find(1);
if ($file) {
    echo "   Updating file record with proper download path...\n";
    $file->file_path = 'uploads/test-file.pdf';
    $file->is_public = true;
    $file->save();
    echo "   ✅ File record updated\n";
}

echo "\n2. Testing the 11 failing endpoints with proper tokens and data...\n";

$failingEndpoints = [
    ['url' => 'api/courses/1/lessons', 'token' => 'admin', 'description' => 'Course lessons (admin access)'],
    ['url' => 'api/enrollments/1/progress', 'token' => 'student', 'description' => 'Enrollment progress'],
    ['url' => 'api/courses/1/assignments', 'token' => 'admin', 'description' => 'Course assignments (admin access)'],
    ['url' => 'api/assignments/1', 'token' => 'admin', 'description' => 'Assignment details (admin access)'],
    ['url' => 'api/assignments/1/submissions', 'token' => 'admin', 'description' => 'Assignment submissions (admin access)'],
    ['url' => 'api/courses/1/forum', 'token' => 'admin', 'description' => 'Course forum (admin access)'],
    ['url' => 'api/courses/1/forum/analytics', 'token' => 'admin', 'description' => 'Forum analytics (admin access)'],
    ['url' => 'api/certificates/1/download', 'token' => 'admin', 'description' => 'Certificate download (admin access)'],
    ['url' => 'api/learning-paths/1/progress?user_id=2', 'token' => 'student', 'description' => 'Learning path progress with user_id'],
    ['url' => 'api/chat/sessions/1', 'token' => 'admin', 'description' => 'Chat session (admin access)'],
    ['url' => 'api/files/download/1', 'token' => 'admin', 'description' => 'File download (admin access)'],
];

$results = [
    'total' => 0,
    'fixed' => 0,
    'still_failing' => 0
];

foreach ($failingEndpoints as $test) {
    $results['total']++;
    $token = $authTokens[$test['token']];
    $response = makeRequest($test['url'], 'GET', $token);
    $status = $response['status'];
    
    echo "Testing: {$test['description']}\n";
    echo "   URL: {$test['url']}\n";
    echo "   Token: {$test['token']}\n";
    
    if ($status == 200) {
        echo "   ✅ FIXED! Status: $status\n";
        $results['fixed']++;
    } else {
        echo "   ❌ Still failing: $status\n";
        $results['still_failing']++;
        
        // Show error details
        $body = json_decode($response['body'], true);
        if ($body && isset($body['message'])) {
            echo "   Error: " . substr($body['message'], 0, 100) . "...\n";
        }
    }
    echo "\n";
}

echo "====================================\n";
echo "📊 FINAL 11 ENDPOINTS FIX RESULTS\n";
echo "====================================\n";
echo "Total Tested: {$results['total']}\n";
echo "✅ Fixed: {$results['fixed']}\n";
echo "❌ Still Failing: {$results['still_failing']}\n";

$fixRate = round(($results['fixed'] / $results['total']) * 100, 2);
echo "📈 Fix Rate: $fixRate%\n\n";

if ($fixRate == 100) {
    echo "🎉 PERFECT! 100% fix rate - ALL ENDPOINTS WORKING!\n";
} elseif ($fixRate >= 90) {
    echo "🎉 EXCELLENT! 90%+ fix rate - Almost perfect!\n";
} elseif ($fixRate >= 80) {
    echo "✅ VERY GOOD! 80%+ fix rate - Great progress!\n";
} else {
    echo "⚠️  MORE WORK NEEDED! Additional investigation required.\n";
}

echo "\n🎯 NEXT: Investigate remaining issues if any\n";
echo "====================================\n";
