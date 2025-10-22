<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 DEBUGGING THE ONE FAILED ENDPOINT\n";
echo "===================================\n\n";

// Load authentication tokens
$authTokens = [];
$tokenContent = file_get_contents('auth_tokens.txt');

if (preg_match('/ADMIN_TOKEN=(.+)/', $tokenContent, $adminMatch)) {
    $authTokens['admin'] = trim($adminMatch[1]);
}
if (preg_match('/STUDENT_TOKEN=(.+)/', $tokenContent, $studentMatch)) {
    $authTokens['student'] = trim($studentMatch[1]);
}
if (preg_match('/INSTRUCTOR_TOKEN=(.+)/', $tokenContent, $instructorMatch)) {
    $authTokens['instructor'] = trim($instructorMatch[1]);
}

function makeDetailedRequest($url, $method = 'GET', $token = null) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/api/$url",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_VERBOSE => false
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['status' => 0, 'body' => $error, 'error' => $error];
    }
    
    return ['status' => $httpCode, 'body' => $response, 'error' => null];
}

// The failed endpoint from our test
$failedEndpoint = 'assignments/1';
$token = $authTokens['student'];

echo "🧪 Testing the failed endpoint: assignments/1\n";
echo "Using student token: " . substr($token, 0, 20) . "...\n\n";

// Test the endpoint
$response = makeDetailedRequest($failedEndpoint, 'GET', $token);

echo "📊 DETAILED RESPONSE ANALYSIS:\n";
echo "Status Code: {$response['status']}\n";
echo "Response Body:\n";
echo $response['body'] . "\n\n";

// Parse the response
$body = json_decode($response['body'], true);
if ($body) {
    echo "📋 PARSED RESPONSE:\n";
    if (isset($body['message'])) {
        echo "Message: {$body['message']}\n";
    }
    if (isset($body['error'])) {
        echo "Error: {$body['error']}\n";
    }
    if (isset($body['errors'])) {
        echo "Validation Errors:\n";
        print_r($body['errors']);
    }
    echo "\n";
}

// Let's also test with different user roles to understand the permission issue
echo "🔐 TESTING WITH DIFFERENT USER ROLES:\n";
echo "=====================================\n";

$roles = ['student', 'instructor', 'admin'];
foreach ($roles as $role) {
    if (isset($authTokens[$role])) {
        echo "\nTesting as $role:\n";
        $roleResponse = makeDetailedRequest($failedEndpoint, 'GET', $authTokens[$role]);
        echo "Status: {$roleResponse['status']}\n";
        
        if ($roleResponse['status'] == 200) {
            echo "✅ SUCCESS - $role can access this endpoint\n";
        } else {
            $roleBody = json_decode($roleResponse['body'], true);
            if ($roleBody && isset($roleBody['message'])) {
                echo "❌ FAILED - {$roleBody['message']}\n";
            } else {
                echo "❌ FAILED - Status {$roleResponse['status']}\n";
            }
        }
    }
}

// Let's check if assignment ID 1 actually exists
echo "\n🔍 CHECKING IF ASSIGNMENT ID 1 EXISTS:\n";
echo "=====================================\n";

use App\Models\Assignment;
use App\Models\User;
use App\Models\Enrollment;

try {
    $assignment = Assignment::find(1);
    if ($assignment) {
        echo "✅ Assignment ID 1 exists:\n";
        echo "Title: {$assignment->title}\n";
        echo "Course ID: {$assignment->course_id}\n";
        echo "Created: {$assignment->created_at}\n";
        
        // Check if the student is enrolled in the course
        
        $student = User::where('role', 'student')->first();
        if ($student) {
            $enrollment = Enrollment::where('user_id', $student->id)
                                  ->where('course_id', $assignment->course_id)
                                  ->first();
            
            if ($enrollment) {
                echo "✅ Student is enrolled in course {$assignment->course_id}\n";
                echo "Enrollment status: {$enrollment->status}\n";
            } else {
                echo "❌ Student is NOT enrolled in course {$assignment->course_id}\n";
                echo "This explains the 403 Forbidden error!\n";
            }
        }
        
    } else {
        echo "❌ Assignment ID 1 does NOT exist in database\n";
        echo "This explains the error!\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking assignment: {$e->getMessage()}\n";
}

// Let's also check what assignments exist
echo "\n📋 CHECKING AVAILABLE ASSIGNMENTS:\n";
echo "=================================\n";

try {
    $assignments = Assignment::take(5)->get();
    if ($assignments->count() > 0) {
        echo "Available assignments:\n";
        foreach ($assignments as $assignment) {
            echo "ID: {$assignment->id}, Title: {$assignment->title}, Course: {$assignment->course_id}\n";
        }
    } else {
        echo "❌ No assignments found in database\n";
    }
} catch (Exception $e) {
    echo "❌ Error fetching assignments: {$e->getMessage()}\n";
}

echo "\n===================================\n";
echo "Debugging complete!\n";
echo "===================================\n";
