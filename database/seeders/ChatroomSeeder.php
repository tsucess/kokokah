<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatRoom;
use App\Models\User;

class ChatroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->command->info('No admin user found. Skipping chatroom seeding.');
            return;
        }

        // Create general chatrooms
        $chatrooms = [
            [
                'name' => 'General',
                'description' => 'General discussion for all users',
                'type' => 'general',
                'icon' => 'bi-hash',
                'color' => '#004A53',
                'created_by' => $admin->id,
                'is_active' => true,
            ],
            [
                'name' => 'Mathematics Help Corner',
                'description' => 'Get help with mathematics problems',
                'type' => 'general',
                'icon' => 'bi-calculator',
                'color' => '#114243',
                'created_by' => $admin->id,
                'is_active' => true,
            ],
            [
                'name' => 'Science Discussions',
                'description' => 'Discuss science topics and experiments',
                'type' => 'general',
                'icon' => 'bi-flask',
                'color' => '#114243',
                'created_by' => $admin->id,
                'is_active' => true,
            ],
            [
                'name' => 'English Literature & Writing',
                'description' => 'Share and discuss literature and writing',
                'type' => 'general',
                'icon' => 'bi-book',
                'color' => '#114243',
                'created_by' => $admin->id,
                'is_active' => true,
            ],
            [
                'name' => 'History & Social Studies',
                'description' => 'Explore history and social studies topics',
                'type' => 'general',
                'icon' => 'bi-globe',
                'color' => '#114243',
                'created_by' => $admin->id,
                'is_active' => true,
            ],
            [
                'name' => 'ICT & Programming Chat',
                'description' => 'Discuss programming and ICT topics',
                'type' => 'general',
                'icon' => 'bi-code-slash',
                'color' => '#114243',
                'created_by' => $admin->id,
                'is_active' => true,
            ],
            [
                'name' => 'Foreign Language Practice',
                'description' => 'Practice foreign languages with peers',
                'type' => 'general',
                'icon' => 'bi-translate',
                'color' => '#114243',
                'created_by' => $admin->id,
                'is_active' => true,
            ],
        ];

        foreach ($chatrooms as $chatroom) {
            $room = ChatRoom::create($chatroom);
            
            // Add admin as member
            $room->users()->attach($admin->id, [
                'role' => 'admin',
                'joined_at' => now(),
                'is_active' => true,
            ]);

            // Add some students to the chatroom
            $students = User::where('role', 'student')
                ->inRandomOrder()
                ->limit(rand(5, 15))
                ->get();

            foreach ($students as $student) {
                $room->users()->attach($student->id, [
                    'role' => 'member',
                    'joined_at' => now(),
                    'is_active' => true,
                ]);
            }

            $this->command->info("Created chatroom: {$room->name}");
        }

        $this->command->info('✅ Chatrooms seeded successfully!');
    }
}

