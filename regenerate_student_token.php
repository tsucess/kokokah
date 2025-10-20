<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "🔄 REGENERATING STUDENT TOKEN\n";
echo "============================\n\n";

// Find student user
$student = User::where('role', 'student')->first();

if (!$student) {
    echo "❌ No student user found!\n";
    exit(1);
}

echo "Found student: {$student->email} (ID: {$student->id})\n";

// Delete old tokens
$student->tokens()->delete();
echo "✅ Deleted old tokens\n";

// Create new token
$newToken = $student->createToken('student-token')->plainTextToken;
echo "✅ Generated new token: " . substr($newToken, 0, 20) . "...\n";

// Update auth_tokens.txt file
$authContent = file_get_contents('auth_tokens.txt');

// Replace student token
$authContent = preg_replace('/STUDENT_TOKEN=.+/', "STUDENT_TOKEN=$newToken", $authContent);

file_put_contents('auth_tokens.txt', $authContent);
echo "✅ Updated auth_tokens.txt\n";

echo "\n============================\n";
echo "Student token regenerated successfully!\n";
echo "New token: $newToken\n";
echo "============================\n";
