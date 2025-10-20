<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Coupon;
use Illuminate\Support\Facades\DB;

echo "Coupon count: " . Coupon::count() . "\n";
echo "All coupons:\n";
$coupons = Coupon::all();
foreach ($coupons as $c) {
    echo "  - ID: {$c->id}, Code: {$c->code}, Name: {$c->name}\n";
}

// Check database directly
echo "\nDirect database query:\n";
$dbCoupons = DB::table('coupons')->get();
echo "Database coupon count: " . count($dbCoupons) . "\n";
foreach ($dbCoupons as $c) {
    echo "  - ID: {$c->id}, Code: {$c->code}, Name: {$c->name}\n";
}

