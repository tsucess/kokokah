<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Checking notification table columns...\n";
$columns = Schema::getColumnListing('notifications');
echo "Columns: " . implode(', ', $columns) . "\n";

