<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING USER_BADGES TABLE\n";
echo "============================================================\n\n";

try {
    // Add revoked_at column to user_badges table
    echo "🏅 Adding revoked_at column to user_badges table...\n";
    if (!Schema::hasColumn('user_badges', 'revoked_at')) {
        Schema::table('user_badges', function (Blueprint $table) {
            $table->timestamp('revoked_at')->nullable()->after('earned_at');
        });
        echo "✅ Added revoked_at column to user_badges table\n";
    } else {
        echo "✅ revoked_at column already exists in user_badges table\n";
    }
    
    echo "\n🎉 User badges table fix completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
