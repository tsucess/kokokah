<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 DEBUGGING MY COURSES ENDPOINT\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$studentToken = trim($studentMatches[1]);

// Test the endpoint with detailed debugging
$url = 'http://localhost:8000/api/courses/my-courses';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $studentToken,
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$info = curl_getinfo($ch);
curl_close($ch);

echo "URL: $url\n";
echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

// Also check if user has enrollments
use App\Models\User;
use App\Models\Enrollment;

// Find the student user
$user = User::find(21); // Student user ID from tokens
if ($user) {
    echo "User found: {$user->first_name} {$user->last_name}\n";
    
    $enrollments = Enrollment::where('user_id', $user->id)->get();
    echo "Total enrollments: " . $enrollments->count() . "\n";
    
    foreach ($enrollments as $enrollment) {
        echo "- Enrollment ID: {$enrollment->id}, Course ID: {$enrollment->course_id}, Status: {$enrollment->status}\n";
        if ($enrollment->course) {
            echo "  Course: {$enrollment->course->title}\n";
        } else {
            echo "  Course: NULL (course not found)\n";
        }
    }
} else {
    echo "User not found!\n";
}

echo "\n============================================================\n";
