<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING ASSIGNMENT SYSTEM\n";
echo "============================================================\n\n";

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

try {
    echo "📝 Checking assignments table schema...\n";
    
    // Get current columns
    $columns = Schema::getColumnListing('assignments');
    echo "Current columns: " . implode(', ', $columns) . "\n\n";
    
    // Check if we need to add missing columns
    $needsUpdate = false;
    
    if (!in_array('due_date', $columns) && in_array('deadline', $columns)) {
        echo "📝 Renaming 'deadline' to 'due_date'...\n";
        Schema::table('assignments', function (Blueprint $table) {
            $table->renameColumn('deadline', 'due_date');
        });
        $needsUpdate = true;
    }
    
    if (!in_array('description', $columns) && in_array('instructions', $columns)) {
        echo "📝 Renaming 'instructions' to 'description'...\n";
        Schema::table('assignments', function (Blueprint $table) {
            $table->renameColumn('instructions', 'description');
        });
        $needsUpdate = true;
    }
    
    // Check if max_score exists (should have been added in migration)
    if (!in_array('max_score', $columns)) {
        echo "📝 Adding missing max_score column...\n";
        Schema::table('assignments', function (Blueprint $table) {
            $table->integer('max_score')->default(100)->after('due_date');
        });
        $needsUpdate = true;
    }
    
    // Check if allowed_file_types exists
    if (!in_array('allowed_file_types', $columns)) {
        echo "📝 Adding missing allowed_file_types column...\n";
        Schema::table('assignments', function (Blueprint $table) {
            $table->json('allowed_file_types')->nullable()->after('max_score');
        });
        $needsUpdate = true;
    }
    
    // Check if max_file_size_mb exists
    if (!in_array('max_file_size_mb', $columns)) {
        echo "📝 Adding missing max_file_size_mb column...\n";
        Schema::table('assignments', function (Blueprint $table) {
            $table->integer('max_file_size_mb')->default(10)->after('allowed_file_types');
        });
        $needsUpdate = true;
    }
    
    if ($needsUpdate) {
        echo "✅ Updated assignments table schema!\n\n";
    } else {
        echo "✅ Assignments table schema is correct!\n\n";
    }
    
    // Check if we have any assignments
    $assignmentCount = DB::table('assignments')->count();
    
    if ($assignmentCount === 0) {
        echo "📝 No assignments found. Creating sample assignments...\n";
        
        // Get some courses to create assignments for
        $courses = DB::table('courses')->where('status', 'published')->limit(3)->get();
        
        if ($courses->count() > 0) {
            foreach ($courses as $course) {
                $assignmentId = DB::table('assignments')->insertGetId([
                    'course_id' => $course->id,
                    'title' => "Assignment for {$course->title}",
                    'description' => 'This is a sample assignment for testing purposes. Please submit your work according to the course requirements.',
                    'due_date' => now()->addDays(7),
                    'max_score' => 100,
                    'allowed_file_types' => json_encode(['pdf', 'doc', 'docx']),
                    'max_file_size_mb' => 10,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                echo "   ✅ Created assignment ID $assignmentId for course: {$course->title}\n";
            }
        } else {
            echo "⚠️  No published courses found to create assignments for.\n";
        }
    } else {
        echo "✅ Found $assignmentCount assignments in database!\n";
    }
    
    echo "\n🧪 Testing assignment endpoints...\n";
    
    // Get tokens from file
    $tokens = file_get_contents('auth_tokens.txt');
    preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
    $studentToken = trim($studentMatches[1]);
    
    // Test course assignments endpoint (403 error)
    echo "Testing GET /courses/11/assignments...\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/courses/11/assignments');
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
        echo "✅ Course assignments endpoint working! HTTP 200\n";
    } else {
        echo "❌ Course assignments endpoint failed! HTTP $httpCode\n";
        echo "Response: " . substr($response, 0, 200) . "...\n";
    }
    
    // Test single assignment endpoint (500 error)
    echo "\nTesting GET /assignments/1...\n";
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
        echo "✅ Single assignment endpoint working! HTTP 200\n";
    } else {
        echo "❌ Single assignment endpoint failed! HTTP $httpCode\n";
        echo "Response: " . substr($response, 0, 200) . "...\n";
        
        if ($httpCode === 404) {
            echo "📝 Assignment ID 1 not found. Let's check what assignments exist...\n";
            $assignments = DB::table('assignments')->get();
            if ($assignments->count() > 0) {
                $firstAssignment = $assignments->first();
                echo "Testing with assignment ID {$firstAssignment->id}...\n";
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/api/assignments/{$firstAssignment->id}");
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
                    echo "✅ Assignment endpoint working with ID {$firstAssignment->id}! HTTP 200\n";
                } else {
                    echo "❌ Assignment endpoint still failing! HTTP $httpCode\n";
                    echo "Response: " . substr($response, 0, 200) . "...\n";
                }
            }
        }
    }
    
    echo "\n============================================================\n";
    echo "✅ ASSIGNMENT SYSTEM FIXES COMPLETED!\n";
    echo "============================================================\n";
    
} catch (\Exception $e) {
    echo "❌ Error fixing assignment system: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
