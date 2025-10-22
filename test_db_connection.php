<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 TESTING DATABASE CONNECTION\n";
echo "==============================\n\n";

// Get database info
$dbConnection = config('database.default');
$dbHost = config('database.connections.' . $dbConnection . '.host');
$dbName = config('database.connections.' . $dbConnection . '.database');

echo "Database Connection: $dbConnection\n";
echo "Database Host: $dbHost\n";
echo "Database Name: $dbName\n\n";

// Test connection
try {
    $result = \DB::select('SELECT 1');
    echo "✅ Database connection successful!\n\n";
} catch (\Exception $e) {
    echo "❌ Database connection failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Check token table
$tokenCount = \DB::table('personal_access_tokens')->count();
echo "Total tokens in database: $tokenCount\n\n";

// Check user 2
$user2 = \DB::table('users')->where('id', 2)->first();
if ($user2) {
    echo "✅ User ID 2 found: {$user2->first_name} {$user2->last_name}\n";
} else {
    echo "❌ User ID 2 not found!\n";
}

echo "\n========================================\n";
