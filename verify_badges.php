<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Badge;

echo "=== BADGE VERIFICATION ===\n\n";

// Total badges
$total = Badge::count();
echo "Total Badges: $total\n\n";

// Badges by category
echo "Badges by Category:\n";
$byCategory = Badge::selectRaw('category, COUNT(*) as count')->groupBy('category')->get();
foreach ($byCategory as $item) {
    echo "  - {$item->category}: {$item->count} badges\n";
}

echo "\n";

// Badges by type
echo "Badges by Type:\n";
$byType = Badge::selectRaw('type, COUNT(*) as count')->groupBy('type')->get();
foreach ($byType as $item) {
    echo "  - {$item->type}: {$item->count} badges\n";
}

echo "\n";

// Total points available
$totalPoints = Badge::sum('points');
echo "Total Points Available: $totalPoints\n\n";

// Sample badges
echo "Sample Badges:\n";
Badge::limit(5)->get()->each(function ($badge) {
    echo "  - {$badge->name} ({$badge->points} pts) - {$badge->category}/{$badge->type}\n";
});

echo "\n✅ Badge population complete!\n";

