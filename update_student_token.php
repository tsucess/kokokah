<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$student = User::where('email', 'test.student@kokokah.com')->first();
$token = $student->createToken('api-token')->plainTextToken;

echo "New token: $token\n";

// Update auth_tokens.txt
$content = file_get_contents('auth_tokens.txt');
$content = preg_replace('/STUDENT_TOKEN=.+/', "STUDENT_TOKEN=$token", $content);
file_put_contents('auth_tokens.txt', $content);

echo "Updated auth_tokens.txt\n";

