<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$token = '81|ZAx6zmLxIofwzKmE45gVmRq9YtLcgGWFozH2J5szb49a8843';
$hashed = hash('sha256', $token);

echo "Token: $token\n";
echo "Hashed: $hashed\n\n";

$pt = DB::table('personal_access_tokens')->where('token', $hashed)->first();
if ($pt) {
    echo "Token found!\n";
    echo "User ID: {$pt->tokenable_id}\n";
    echo "Name: {$pt->name}\n";
} else {
    echo "Token NOT found in database\n";
    
    // Check all tokens
    $allTokens = DB::table('personal_access_tokens')->get();
    echo "\nAll tokens in database:\n";
    foreach ($allTokens as $t) {
        echo "  - User {$t->tokenable_id}: {$t->name}\n";
    }
}

