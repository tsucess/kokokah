<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing terms with proper order values
        DB::table('terms')->where('name', 'like', '%First%')->update(['order' => 1]);
        DB::table('terms')->where('name', 'like', '%Second%')->update(['order' => 2]);
        DB::table('terms')->where('name', 'like', '%Third%')->update(['order' => 3]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset order values
        DB::table('terms')->update(['order' => 0]);
    }
};
