<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "🔍 DEBUGGING STUDENT TOKEN AUTHENTICATION ISSUE\n";
echo "===============================================\n\n";

// Get the student token from file
$tokenContent = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokenContent, $studentMatch);
$studentToken = trim($studentMatch[1]);

echo "Student token from file: $studentToken\n\n";

// Test the token with a simple API call
function testTokenDirectly($token) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ];
    
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/api/user",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_VERBOSE => false
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return ['status' => $httpCode, 'body' => $response];
}

echo "🧪 Testing student token directly:\n";
$result = testTokenDirectly($studentToken);
echo "Status: {$result['status']}\n";
echo "Response: {$result['body']}\n\n";

if ($result['status'] != 200) {
    echo "❌ Token is not working! Let's regenerate it...\n\n";
    
    // Find the student user
    $student = User::where('role', 'student')->first();
    if ($student) {
        echo "Found student: {$student->email} (ID: {$student->id})\n";
        
        // Delete all old tokens
        $student->tokens()->delete();
        echo "✅ Deleted old tokens\n";
        
        // Create new token
        $newToken = $student->createToken('student-token')->plainTextToken;
        echo "✅ Generated new token: $newToken\n";
        
        // Test new token immediately
        echo "\n🧪 Testing new token:\n";
        $newResult = testTokenDirectly($newToken);
        echo "Status: {$newResult['status']}\n";
        echo "Response: {$newResult['body']}\n";
        
        if ($newResult['status'] == 200) {
            echo "✅ New token works! Updating file...\n";
            
            // Update the auth_tokens.txt file
            $authContent = file_get_contents('auth_tokens.txt');
            $authContent = preg_replace('/STUDENT_TOKEN=.+/', "STUDENT_TOKEN=$newToken", $authContent);
            file_put_contents('auth_tokens.txt', $authContent);
            
            echo "✅ Updated auth_tokens.txt with working token\n";
            echo "New student token: $newToken\n";
        } else {
            echo "❌ New token also doesn't work! There might be a deeper issue.\n";
        }
    } else {
        echo "❌ No student user found in database!\n";
    }
} else {
    echo "✅ Student token is working correctly!\n";
}

echo "\n===============================================\n";
echo "Token debugging complete!\n";
echo "===============================================\n";
