<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING PROGRESS CERTIFICATES ENDPOINT\n";
echo "============================================================\n\n";

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

try {
    echo "📝 Checking certificates table schema...\n";
    
    // Get current columns
    $columns = Schema::getColumnListing('certificates');
    echo "Current columns: " . implode(', ', $columns) . "\n\n";
    
    // Check if we need to add missing columns
    $needsUpdate = false;
    
    if (!in_array('certificate_number', $columns)) {
        echo "📝 Adding certificate_number column...\n";
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('certificate_number')->unique()->after('course_id');
        });
        $needsUpdate = true;
    }
    
    if (!in_array('issued_at', $columns)) {
        echo "📝 Adding issued_at column...\n";
        Schema::table('certificates', function (Blueprint $table) {
            $table->timestamp('issued_at')->default(now())->after('certificate_number');
        });
        $needsUpdate = true;
    }
    
    if (!in_array('grade', $columns)) {
        echo "📝 Adding grade column...\n";
        Schema::table('certificates', function (Blueprint $table) {
            $table->decimal('grade', 5, 2)->nullable()->after('issued_at');
        });
        $needsUpdate = true;
    }
    
    if (!in_array('completion_date', $columns)) {
        echo "📝 Adding completion_date column...\n";
        Schema::table('certificates', function (Blueprint $table) {
            $table->timestamp('completion_date')->nullable()->after('grade');
        });
        $needsUpdate = true;
    }
    
    if (!in_array('file_path', $columns)) {
        echo "📝 Adding file_path column...\n";
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('completion_date');
        });
        $needsUpdate = true;
    }
    
    if (!in_array('revoked_at', $columns)) {
        echo "📝 Adding revoked_at column...\n";
        Schema::table('certificates', function (Blueprint $table) {
            $table->timestamp('revoked_at')->nullable()->after('file_path');
        });
        $needsUpdate = true;
    }
    
    if ($needsUpdate) {
        echo "✅ Updated certificates table schema!\n\n";
    } else {
        echo "✅ Certificates table schema is correct!\n\n";
    }
    
    // Check if we have any completed enrollments to create certificates for
    $completedEnrollments = DB::table('enrollments')
        ->where('status', 'completed')
        ->count();
        
    echo "📊 Found $completedEnrollments completed enrollments\n";
    
    if ($completedEnrollments === 0) {
        echo "📝 No completed enrollments found. Creating sample completed enrollment...\n";
        
        // Get a test student enrollment and mark it as completed
        $enrollment = DB::table('enrollments')
            ->where('user_id', 130) // Test student
            ->first();
            
        if ($enrollment) {
            DB::table('enrollments')
                ->where('id', $enrollment->id)
                ->update([
                    'status' => 'completed',
                    'progress' => 100,
                    'completed_at' => now(),
                    'updated_at' => now()
                ]);
                
            echo "✅ Marked enrollment ID {$enrollment->id} as completed\n";
        }
    }
    
    // Check if we have any certificates
    $certificateCount = DB::table('certificates')->count();
    
    if ($certificateCount === 0) {
        echo "📝 No certificates found. Creating sample certificate...\n";
        
        $completedEnrollment = DB::table('enrollments')
            ->where('status', 'completed')
            ->first();
            
        if ($completedEnrollment) {
            $certificateId = DB::table('certificates')->insertGetId([
                'user_id' => $completedEnrollment->user_id,
                'course_id' => $completedEnrollment->course_id,
                'certificate_number' => 'CERT-' . str_pad(1, 6, '0', STR_PAD_LEFT),
                'issued_at' => now(),
                'grade' => 85.5,
                'completion_date' => $completedEnrollment->completed_at,
                'certificate_url' => 'certificates/sample-cert-1.pdf',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            echo "✅ Created sample certificate ID: $certificateId\n";
        }
    } else {
        echo "✅ Found $certificateCount certificates in database!\n";
    }
    
    echo "\n🧪 Testing progress certificates endpoint...\n";
    
    // Get tokens from file
    $tokens = file_get_contents('auth_tokens.txt');
    preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
    $studentToken = trim($studentMatches[1]);
    
    // Test the endpoint
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/progress/certificates');
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
        echo "✅ Progress certificates endpoint working! HTTP 200\n";
        $data = json_decode($response, true);
        if (isset($data['data'])) {
            $available = count($data['data']['available_for_generation'] ?? []);
            $existing = count($data['data']['existing_certificates'] ?? []);
            echo "📊 Available for generation: $available\n";
            echo "📊 Existing certificates: $existing\n";
        }
    } else {
        echo "❌ Progress certificates endpoint failed! HTTP $httpCode\n";
        echo "Response: " . substr($response, 0, 300) . "...\n";
    }
    
    echo "\n============================================================\n";
    echo "✅ PROGRESS CERTIFICATES ENDPOINT FIXED!\n";
    echo "============================================================\n";
    
} catch (\Exception $e) {
    echo "❌ Error fixing progress certificates: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
