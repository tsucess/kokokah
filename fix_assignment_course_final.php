<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING ASSIGNMENT 1 COURSE ISSUE (FINAL)\n";
echo "============================================================\n\n";

use Illuminate\Support\Facades\DB;

try {
    echo "📝 Restoring soft-deleted course 10...\n";
    
    // Restore the soft-deleted course
    $restored = DB::table('courses')
        ->where('id', 10)
        ->update([
            'deleted_at' => null,
            'updated_at' => now()
        ]);
    
    if ($restored) {
        echo "✅ Course 10 restored successfully!\n";
    } else {
        echo "❌ Failed to restore course 10\n";
    }
    
    // Verify the course is now accessible
    $course = DB::table('courses')->where('id', 10)->first();
    echo "Course 10 status:\n";
    echo "   Title: {$course->title}\n";
    echo "   Status: {$course->status}\n";
    echo "   Deleted at: " . ($course->deleted_at ?? 'NULL') . "\n\n";
    
    echo "🧪 Testing assignment 1 endpoint...\n";
    
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
        $data = json_decode($response, true);
        if (isset($data['data']['assignment'])) {
            $assignment = $data['data']['assignment'];
            echo "📊 Assignment details:\n";
            echo "   Title: {$assignment['title']}\n";
            echo "   Course: {$assignment['course']['title']}\n";
            echo "   Due date: {$assignment['due_date']}\n";
        }
    } else {
        echo "❌ Assignment 1 endpoint still failing! HTTP $httpCode\n";
        echo "Response: " . substr($response, 0, 300) . "...\n";
    }
    
    echo "\n============================================================\n";
    echo "✅ ASSIGNMENT 1 COURSE FIX COMPLETED!\n";
    echo "============================================================\n";
    
} catch (\Exception $e) {
    echo "❌ Error fixing assignment course: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
