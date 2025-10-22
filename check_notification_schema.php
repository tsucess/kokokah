<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Notification table columns:\n";
$columns = Schema::getColumnListing('notifications');
foreach ($columns as $column) {
    echo "  - $column\n";
}

echo "\nFirst notification:\n";
$notification = DB::table('notifications')->first();
if ($notification) {
    echo json_encode($notification, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "No notifications found\n";
}

