<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "🔧 REGENERATING STUDENT TOKEN FOR USER ID 2\n";
echo "============================================\n\n";

// Find user ID 2 specifically
$student = User::find(2);

if (!$student) {
    echo "❌ User ID 2 not found!\n";
    exit(1);
}

echo "👤 Found student: {$student->first_name} {$student->last_name} (ID: {$student->id})\n";
echo "📧 Email: {$student->email}\n\n";

// Delete all existing tokens for this student
$deletedCount = $student->tokens()->delete();
echo "🗑️  Deleted $deletedCount existing tokens\n\n";

// Create new token
echo "Creating new token...\n";
$tokenObject = $student->createToken('student-token');
$newToken = $tokenObject->plainTextToken;

echo "🔑 New student token generated: $newToken\n\n";

// Extract token ID
$tokenParts = explode('|', $newToken);
$tokenId = $tokenParts[0];

// Verify token is in database
echo "Verifying token in database...\n";
$tokenRecord = \DB::table('personal_access_tokens')
    ->where('id', $tokenId)
    ->where('tokenable_id', 2)
    ->first();

if ($tokenRecord) {
    echo "✅ Token verified in database!\n";
    echo "   Token ID: {$tokenRecord->id}\n";
    echo "   User ID: {$tokenRecord->tokenable_id}\n";
    echo "   Name: {$tokenRecord->name}\n";
    echo "   Created: {$tokenRecord->created_at}\n\n";
} else {
    echo "❌ Token NOT in database after creation!\n\n";
    exit(1);
}

// Update auth_tokens.txt file
$tokenContent = file_get_contents('auth_tokens.txt');

// Replace the student token
$newTokenContent = preg_replace(
    '/STUDENT_TOKEN=.+/',
    'STUDENT_TOKEN=' . $newToken,
    $tokenContent
);

file_put_contents('auth_tokens.txt', $newTokenContent);

echo "📝 Updated auth_tokens.txt with new student token\n\n";

// Verify the file was updated correctly
$updatedContent = file_get_contents('auth_tokens.txt');
echo "📄 Updated auth_tokens.txt content:\n";
echo $updatedContent . "\n";

echo "✅ STUDENT TOKEN REGENERATION COMPLETE!\n";
echo "🎯 Ready to test all endpoints again!\n";
