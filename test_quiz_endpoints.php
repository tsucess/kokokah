<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING QUIZ ENDPOINTS SPECIFICALLY\n";
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

echo "📋 QUIZ ENDPOINTS (Detailed Testing):\n";

// Test each quiz endpoint individually
$quizTests = [
    ['Get Lesson Quizzes', 'GET', '/lessons/1/quizzes', $studentToken],
    ['Get Quiz 1', 'GET', '/quizzes/1', $studentToken],
];

$passed = 0;
$total = count($quizTests);

foreach ($quizTests as $test) {
    if (testEndpoint($test[0], $test[1], $test[2], $test[3])) {
        $passed++;
    }
}

echo "\n============================================================\n";
echo "📊 QUIZ ENDPOINTS TEST RESULTS\n";
echo "============================================================\n";
echo "Total Tests: $total\n";
echo "✅ Passed: $passed\n";
echo "❌ Failed: " . ($total - $passed) . "\n";
echo "📈 Success Rate: " . round(($passed / $total) * 100, 2) . "%\n";

if ($passed < $total) {
    echo "\n🔍 DEBUGGING QUIZ ISSUES...\n";
    
    // Check if quizzes exist
    try {
        $quizCount = \App\Models\Quiz::count();
        echo "Total quizzes in database: {$quizCount}\n";
        
        if ($quizCount > 0) {
            $quiz = \App\Models\Quiz::with(['lesson.course', 'questions'])->first();
            echo "✅ First quiz ID: {$quiz->id}\n";
            echo "✅ Quiz title: {$quiz->title}\n";
            echo "✅ Lesson ID: {$quiz->lesson_id}\n";
            echo "✅ Course ID: {$quiz->lesson->course_id}\n";
            echo "✅ Questions count: " . $quiz->questions->count() . "\n";
            
            // Check if student is enrolled in the course
            $user = \App\Models\User::where('role', 'student')->first();
            $enrollment = $quiz->lesson->course->enrollments()->where('user_id', $user->id)->first();
            if ($enrollment) {
                echo "✅ Student is enrolled in course\n";
            } else {
                echo "❌ Student is NOT enrolled in course\n";
                
                // Create enrollment for testing
                $quiz->lesson->course->enrollments()->create([
                    'user_id' => $user->id,
                    'status' => 'active',
                    'enrolled_at' => now()
                ]);
                echo "✅ Created enrollment for testing\n";
            }
        } else {
            echo "❌ No quizzes found in database\n";
        }
        
        $lessonCount = \App\Models\Lesson::count();
        echo "Total lessons in database: {$lessonCount}\n";
        
        if ($lessonCount > 0) {
            $lesson = \App\Models\Lesson::first();
            echo "✅ First lesson ID: {$lesson->id}\n";
            echo "✅ Lesson title: {$lesson->title}\n";
            
            $lessonQuizzes = $lesson->quizzes()->count();
            echo "✅ Quizzes in lesson 1: {$lessonQuizzes}\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Error checking quizzes: " . $e->getMessage() . "\n";
    }
}
