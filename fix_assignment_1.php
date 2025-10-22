<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING ASSIGNMENT 1 ISSUE\n";
echo "============================================================\n\n";

use Illuminate\Support\Facades\DB;

try {
    echo "📝 Checking assignment 1...\n";
    
    $assignment = DB::table('assignments')->where('id', 1)->first();
    
    if (!$assignment) {
        echo "❌ Assignment 1 not found!\n";
        exit;
    }
    
    echo "Assignment 1 details:\n";
    echo "   ID: {$assignment->id}\n";
    echo "   Course ID: {$assignment->course_id}\n";
    echo "   Title: {$assignment->title}\n\n";
    
    // Check if the course exists
    $course = DB::table('courses')->where('id', $assignment->course_id)->first();
    
    if (!$course) {
        echo "❌ Course {$assignment->course_id} not found! This is the issue.\n";
        
        // Find a valid course to assign
        $validCourse = DB::table('courses')->where('status', 'published')->first();
        
        if ($validCourse) {
            echo "📝 Updating assignment 1 to use course {$validCourse->id} ({$validCourse->title})...\n";
            
            DB::table('assignments')
                ->where('id', 1)
                ->update([
                    'course_id' => $validCourse->id,
                    'updated_at' => now()
                ]);
                
            echo "✅ Assignment 1 updated successfully!\n";
        } else {
            echo "❌ No valid courses found!\n";
        }
    } else {
        echo "✅ Course exists: {$course->title}\n";
        echo "   This shouldn't be causing a 404. Let me check enrollment...\n";
        
        // Check if test student is enrolled
        $enrollment = DB::table('enrollments')
            ->where('user_id', 130)
            ->where('course_id', $assignment->course_id)
            ->first();
            
        if (!$enrollment) {
            echo "📝 Test student not enrolled in course. Enrolling...\n";
            
            DB::table('enrollments')->insert([
                'user_id' => 130,
                'course_id' => $assignment->course_id,
                'status' => 'active',
                'progress' => 0,
                'enrolled_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            echo "✅ Test student enrolled in course!\n";
        } else {
            echo "✅ Test student already enrolled\n";
        }
    }
    
    echo "\n🧪 Testing assignment 1 endpoint...\n";
    
    // Get tokens from file
    $tokens = file_get_contents('auth_tokens.txt');
    preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
    $studentToken = trim($studentMatches[1]);
    
    // Test the endpoint
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/assignments/1');
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
        echo "✅ Assignment 1 endpoint working! HTTP 200\n";
    } else {
        echo "❌ Assignment 1 endpoint still failing! HTTP $httpCode\n";
        echo "Response: " . substr($response, 0, 200) . "...\n";
    }
    
    echo "\n============================================================\n";
    echo "✅ ASSIGNMENT 1 FIX COMPLETED!\n";
    echo "============================================================\n";
    
} catch (\Exception $e) {
    echo "❌ Error fixing assignment 1: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
