<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\ChatRoom;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the default "General" chatroom for all users if it doesn't exist.
     */
    public function up(): void
    {
        // Check if General chatroom already exists
        $generalExists = ChatRoom::where('name', 'General')
            ->where('type', 'general')
            ->exists();

        if (!$generalExists) {
            // Get an admin user to set as creator, or use null if no admin exists yet
            $admin = User::where('role', 'admin')->first();
            $createdBy = $admin ? $admin->id : null;

            ChatRoom::create([
                'name' => 'General',
                'description' => 'General discussion for all users',
                'type' => 'general',
                'icon' => 'bi-hash',
                'color' => '#004A53',
                'created_by' => $createdBy,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Soft delete the General chatroom
        ChatRoom::where('name', 'General')
            ->where('type', 'general')
            ->delete();
    }
};
