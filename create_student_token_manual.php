<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 CREATING STUDENT TOKEN MANUALLY\n";
echo "===================================\n\n";

// Generate a random token
$tokenString = bin2hex(random_bytes(40));
$tokenHash = hash('sha256', $tokenString);

echo "Generated token string: $tokenString\n";
echo "Generated token hash: $tokenHash\n\n";

// Insert into database
$inserted = \DB::table('personal_access_tokens')->insert([
    'tokenable_type' => 'App\\Models\\User',
    'tokenable_id' => 2,
    'name' => 'student-token',
    'token' => $tokenHash,
    'abilities' => '["*"]',
    'last_used_at' => null,
    'expires_at' => null,
    'created_at' => now(),
    'updated_at' => now(),
]);

if ($inserted) {
    echo "✅ Token inserted into database!\n\n";
    
    // Get the token ID
    $tokenRecord = \DB::table('personal_access_tokens')
        ->where('tokenable_id', 2)
        ->where('name', 'student-token')
        ->orderBy('id', 'desc')
        ->first();
    
    if ($tokenRecord) {
        $fullToken = $tokenRecord->id . '|' . $tokenString;
        echo "Token ID: {$tokenRecord->id}\n";
        echo "Full token: $fullToken\n\n";
        
        // Update auth_tokens.txt
        $tokenContent = file_get_contents('auth_tokens.txt');
        $newTokenContent = preg_replace(
            '/STUDENT_TOKEN=.+/',
            'STUDENT_TOKEN=' . $fullToken,
            $tokenContent
        );
        
        file_put_contents('auth_tokens.txt', $newTokenContent);
        echo "✅ Updated auth_tokens.txt\n\n";
        
        // Verify
        $verified = \DB::table('personal_access_tokens')
            ->where('id', $tokenRecord->id)
            ->where('tokenable_id', 2)
            ->first();
        
        if ($verified) {
            echo "✅ Token verified in database!\n";
        }
    }
} else {
    echo "❌ Failed to insert token!\n";
}

echo "\n========================================\n";
