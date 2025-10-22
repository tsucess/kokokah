<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING PROGRESS ENDPOINTS SPECIFICALLY\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);

$adminToken = trim($adminMatches[1]);
$studentToken = trim($studentMatches[1]);

function testEndpoint($name, $method, $endpoint, $token, $data = null) {
    $url = 'http://localhost:8000/api' . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($httpCode === 200) {
        echo "Testing $name... ✅ $httpCode\n";
        if (isset($result['data'])) {
            if (is_array($result['data'])) {
                echo "  Data count: " . count($result['data']) . "\n";
            } else {
                echo "  Data keys: " . implode(', ', array_keys($result['data'])) . "\n";
            }
        }
    } else {
        echo "Testing $name... ❌ $httpCode";
        if (isset($result['message'])) {
            echo " - " . $result['message'];
        }
        echo "\n";
        
        // Show detailed error for debugging
        if ($httpCode === 500) {
            echo "  Full response: " . substr($response, 0, 300) . "...\n";
        }
    }
    
    return $httpCode === 200;
}

echo "📊 PROGRESS ENDPOINTS (Detailed Testing):\n";

// Test each progress endpoint individually
$progressTests = [
    ['Get Course Progress', 'GET', '/progress/courses', $studentToken],
    ['Get Overall Progress', 'GET', '/progress/overall', $studentToken],
    ['Get Achievement Progress', 'GET', '/progress/achievements', $studentToken],
];

$passed = 0;
$total = count($progressTests);

foreach ($progressTests as $test) {
    if (testEndpoint($test[0], $test[1], $test[2], $test[3])) {
        $passed++;
    }
}

echo "\n============================================================\n";
echo "📊 PROGRESS ENDPOINTS TEST RESULTS\n";
echo "============================================================\n";
echo "Total Tests: $total\n";
echo "✅ Passed: $passed\n";
echo "❌ Failed: " . ($total - $passed) . "\n";
echo "📈 Success Rate: " . round(($passed / $total) * 100, 2) . "%\n";

if ($passed < $total) {
    echo "\n🔍 DEBUGGING PROGRESS ISSUES...\n";
    
    // Check if progress-related data exists
    try {
        $enrollmentCount = \App\Models\Enrollment::count();
        echo "Enrollments in database: {$enrollmentCount}\n";
        
        $lessonCompletionCount = \App\Models\LessonCompletion::count();
        echo "Lesson completions in database: {$lessonCompletionCount}\n";
        
        $quizAttemptCount = \App\Models\QuizAttempt::count();
        echo "Quiz attempts in database: {$quizAttemptCount}\n";
        
        $badgeCount = \App\Models\Badge::count();
        echo "Badges in database: {$badgeCount}\n";
        
        $userBadgeCount = \App\Models\UserBadge::count();
        echo "User badges in database: {$userBadgeCount}\n";
        
        // Check if student has enrollments
        $student = \App\Models\User::where('role', 'student')->first();
        if ($student) {
            $studentEnrollments = $student->enrollments()->count();
            echo "Student enrollments: {$studentEnrollments}\n";
            
            if ($studentEnrollments == 0) {
                echo "❌ Student has no enrollments, creating test enrollment...\n";
                
                $course = \App\Models\Course::first();
                if ($course) {
                    $course->enrollments()->create([
                        'user_id' => $student->id,
                        'status' => 'active',
                        'enrolled_at' => now(),
                        'progress' => 25.5
                    ]);
                    echo "✅ Created test enrollment\n";
                }
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Error checking progress data: " . $e->getMessage() . "\n";
    }
}
