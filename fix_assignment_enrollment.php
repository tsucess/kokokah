<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING ASSIGNMENT ENROLLMENT ISSUES\n";
echo "============================================================\n\n";

use Illuminate\Support\Facades\DB;

try {
    // Get test student user
    $testStudent = DB::table('users')->where('email', 'test.student@kokokah.com')->first();
    
    if (!$testStudent) {
        echo "❌ Test student not found!\n";
        return;
    }
    
    echo "✅ Found test student: {$testStudent->email} (ID: {$testStudent->id})\n\n";
    
    // Check what courses have assignments
    $coursesWithAssignments = DB::table('assignments')
        ->join('courses', 'assignments.course_id', '=', 'courses.id')
        ->select('courses.id', 'courses.title', 'courses.status', DB::raw('COUNT(assignments.id) as assignment_count'))
        ->groupBy('courses.id', 'courses.title', 'courses.status')
        ->get();
    
    echo "📋 Courses with assignments:\n";
    foreach ($coursesWithAssignments as $course) {
        echo "   - Course ID {$course->id}: {$course->title} ({$course->assignment_count} assignments, status: {$course->status})\n";
    }
    echo "\n";
    
    // Enroll the test student in courses that have assignments
    foreach ($coursesWithAssignments as $course) {
        // Check if already enrolled
        $existingEnrollment = DB::table('enrollments')
            ->where('user_id', $testStudent->id)
            ->where('course_id', $course->id)
            ->first();
            
        if (!$existingEnrollment) {
            echo "📝 Enrolling test student in course: {$course->title}\n";
            
            DB::table('enrollments')->insert([
                'user_id' => $testStudent->id,
                'course_id' => $course->id,
                'status' => 'active',
                'progress' => 0,
                'enrolled_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            echo "✅ Enrolled successfully!\n";
        } else {
            echo "✅ Already enrolled in course: {$course->title}\n";
        }
    }
    
    echo "\n🧪 Testing assignment endpoints again...\n";
    
    // Get tokens from file
    $tokens = file_get_contents('auth_tokens.txt');
    preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
    $studentToken = trim($studentMatches[1]);
    
    // Test with a course that has assignments
    if ($coursesWithAssignments->count() > 0) {
        $testCourse = $coursesWithAssignments->first();
        
        echo "Testing GET /courses/{$testCourse->id}/assignments...\n";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/api/courses/{$testCourse->id}/assignments");
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
            $data = json_decode($response, true);
            $assignmentCount = count($data['data'] ?? []);
            echo "✅ Course assignments endpoint working! HTTP 200\n";
            echo "📊 Returned $assignmentCount assignments\n";
        } else {
            echo "❌ Course assignments endpoint failed! HTTP $httpCode\n";
            echo "Response: " . substr($response, 0, 200) . "...\n";
        }
    }
    
    // Test single assignment endpoint
    $assignment = DB::table('assignments')->first();
    if ($assignment) {
        echo "\nTesting GET /assignments/{$assignment->id}...\n";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/api/assignments/{$assignment->id}");
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
            echo "✅ Single assignment endpoint working! HTTP 200\n";
        } else {
            echo "❌ Single assignment endpoint failed! HTTP $httpCode\n";
            echo "Response: " . substr($response, 0, 300) . "...\n";
            
            // If it's still a 500 error, let's check the assignment data
            if ($httpCode === 500) {
                echo "\n🔍 Debugging assignment data...\n";
                $assignmentData = DB::table('assignments')
                    ->join('courses', 'assignments.course_id', '=', 'courses.id')
                    ->where('assignments.id', $assignment->id)
                    ->select('assignments.*', 'courses.title as course_title', 'courses.status as course_status')
                    ->first();
                    
                if ($assignmentData) {
                    echo "Assignment ID: {$assignmentData->id}\n";
                    echo "Course ID: {$assignmentData->course_id}\n";
                    echo "Course Title: {$assignmentData->course_title}\n";
                    echo "Course Status: {$assignmentData->course_status}\n";
                } else {
                    echo "❌ Assignment or course data not found!\n";
                }
            }
        }
    }
    
    echo "\n============================================================\n";
    echo "✅ ASSIGNMENT ENROLLMENT FIXES COMPLETED!\n";
    echo "============================================================\n";
    
} catch (\Exception $e) {
    echo "❌ Error fixing assignment enrollment: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
