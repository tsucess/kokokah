<?php
/**
 * Test script to find problematic badge criteria
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Badge;

try {
    echo "Searching for badges with 'transfer' in criteria...\n\n";
    
    $badges = Badge::where('criteria', 'like', '%transfer%')->get();
    
    if ($badges->isEmpty()) {
        echo "No badges found with 'transfer' in criteria\n";
    } else {
        foreach ($badges as $badge) {
            echo "Badge: {$badge->name}\n";
            echo "Criteria: {$badge->criteria}\n";
            echo "---\n";
        }
    }
    
    echo "\nSearching for badges with 'sender_id' in criteria...\n\n";
    
    $badges = Badge::where('criteria', 'like', '%sender_id%')->get();
    
    if ($badges->isEmpty()) {
        echo "No badges found with 'sender_id' in criteria\n";
    } else {
        foreach ($badges as $badge) {
            echo "Badge: {$badge->name}\n";
            echo "Criteria: {$badge->criteria}\n";
            echo "---\n";
        }
    }
    
    echo "\nAll badge criteria:\n\n";
    Badge::all()->each(function($b) {
        echo "{$b->name}: {$b->criteria}\n";
    });
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

