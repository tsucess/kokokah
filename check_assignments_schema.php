<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "🔍 CHECKING ASSIGNMENTS TABLE SCHEMA\n";
echo "============================================================\n\n";

try {
    // Get column information
    echo "📋 Assignments table columns:\n";
    $columns = Schema::getColumnListing('assignments');
    foreach ($columns as $column) {
        echo "   - $column\n";
    }
    
    echo "\n📊 Sample assignment data:\n";
    $assignments = DB::table('assignments')->limit(3)->get();
    foreach ($assignments as $assignment) {
        echo "   Assignment ID {$assignment->id}:\n";
        foreach ((array)$assignment as $key => $value) {
            echo "     $key: $value\n";
        }
        echo "\n";
    }
    
    echo "📈 Total assignments: " . DB::table('assignments')->count() . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n============================================================\n";
echo "✅ SCHEMA CHECK COMPLETED!\n";
echo "============================================================\n";
