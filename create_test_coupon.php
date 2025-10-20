<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Coupon;

echo "Creating test coupon...\n\n";

$coupon = Coupon::create([
    'code' => 'TEST10',
    'name' => 'Test Coupon 10% Off',
    'description' => 'Test coupon for 10% discount',
    'type' => 'percentage',
    'value' => 10,
    'usage_limit' => 100,
    'used_count' => 0,
    'starts_at' => now(),
    'expires_at' => now()->addMonths(1),
    'is_active' => true
]);

echo "✅ Coupon created with ID: {$coupon->id}\n";
echo "   Code: {$coupon->code}\n";
echo "   Discount: {$coupon->discount_value}%\n";

