<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Coupon;

$coupons = Coupon::pluck('id')->toArray();
echo "Coupon IDs: " . implode(', ', $coupons) . "\n";
echo "First Coupon ID: " . (Coupon::first()?->id ?? 'NONE') . "\n";

