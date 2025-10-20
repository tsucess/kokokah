<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "🔍 CHECKING DATABASE STRUCTURE\n";
echo "==============================\n\n";

// Check coupons table structure
echo "📋 COUPONS TABLE COLUMNS:\n";
try {
    $couponsColumns = Schema::getColumnListing('coupons');
    foreach ($couponsColumns as $column) {
        echo "  • $column\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking coupons table: " . $e->getMessage() . "\n";
}

echo "\n📋 COUPON_USAGES TABLE COLUMNS:\n";
try {
    $couponUsagesColumns = Schema::getColumnListing('coupon_usages');
    foreach ($couponUsagesColumns as $column) {
        echo "  • $column\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking coupon_usages table: " . $e->getMessage() . "\n";
}

echo "\n📋 LEARNING_PATHS TABLE COLUMNS:\n";
try {
    $learningPathsColumns = Schema::getColumnListing('learning_paths');
    foreach ($learningPathsColumns as $column) {
        echo "  • $column\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking learning_paths table: " . $e->getMessage() . "\n";
}

echo "\n🔍 CHECKING FOR MISSING COLUMNS:\n";
echo "================================\n";

// Check what columns are missing
$missingColumns = [];

// Check coupons table
if (!in_array('created_by', $couponsColumns)) {
    $missingColumns[] = "coupons.created_by";
}
if (!in_array('status', $couponsColumns)) {
    $missingColumns[] = "coupons.status";
}
if (!in_array('user_limit', $couponsColumns)) {
    $missingColumns[] = "coupons.user_limit";
}

// Check coupon_usages table
if (!in_array('used_at', $couponUsagesColumns)) {
    $missingColumns[] = "coupon_usages.used_at";
}

// Check learning_paths table
if (!in_array('status', $learningPathsColumns)) {
    $missingColumns[] = "learning_paths.status";
}

if (empty($missingColumns)) {
    echo "✅ All required columns exist!\n";
} else {
    echo "❌ Missing columns:\n";
    foreach ($missingColumns as $column) {
        echo "  • $column\n";
    }
}

echo "\n==============================\n";
