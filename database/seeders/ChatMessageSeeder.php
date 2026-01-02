<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Enrollment;

class ChatMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates sample messages for chatrooms:
     * - General chatroom: All users
     * - Course chatrooms: Only enrolled students
     */
    public function run(): void
    {
        $sampleMessages = [
            'Hello everyone! 👋',
            'How is everyone doing today?',
            'Great discussion so far!',
            'Does anyone have questions?',
            'Thanks for sharing!',
            'I agree with that point',
            'Can someone explain this further?',
            'That makes sense now',
            'Looking forward to the next session',
            'This is really helpful',
            'I have a question about this topic',
            'Great explanation!',
            'Thanks for the clarification',
            'Anyone else struggling with this?',
            'I found a helpful resource',
            'Let me share my thoughts',
            'This is interesting',
            'I learned something new today',
            'Keep up the good work!',
            'See you next time!',
        ];

        // Process General chatrooms
        $generalChatrooms = ChatRoom::where('type', 'general')->get();
        foreach ($generalChatrooms as $chatroom) {
            $this->createMessagesForChatroom($chatroom, $sampleMessages);
        }

        // Process Course-specific chatrooms
        $courseChatrooms = ChatRoom::where('type', 'course')->get();
        foreach ($courseChatrooms as $chatroom) {
            if (!$chatroom->course_id) {
                continue;
            }

            // Get enrolled students for this course
            $enrolledStudents = Enrollment::where('course_id', $chatroom->course_id)
                ->where('status', 'active')
                ->pluck('user_id')
                ->toArray();

            if (empty($enrolledStudents)) {
                $this->command->warn("No enrolled students for course: {$chatroom->course_id}");
                continue;
            }

            // Create messages from enrolled students
            $messageCount = rand(5, 15);
            for ($i = 0; $i < $messageCount; $i++) {
                $randomUser = $enrolledStudents[array_rand($enrolledStudents)];
                $randomMessage = $sampleMessages[array_rand($sampleMessages)];

                ChatMessage::create([
                    'chat_room_id' => $chatroom->id,
                    'user_id' => $randomUser,
                    'content' => $randomMessage,
                    'type' => 'text',
                    'is_deleted' => false,
                    'created_at' => now()->subMinutes(rand(1, 1440)),
                ]);
            }

            $this->command->info("✅ Created messages for course chatroom: {$chatroom->name}");
        }

        $this->command->info('✅ Chat messages seeded successfully!');
    }

    /**
     * Create messages for a general chatroom from all users
     */
    private function createMessagesForChatroom(ChatRoom $chatroom, array $sampleMessages): void
    {
        $users = User::where('role', 'student')->pluck('id')->toArray();

        if (empty($users)) {
            $this->command->warn("No students found for general chatroom: {$chatroom->name}");
            return;
        }

        $messageCount = rand(5, 15);
        for ($i = 0; $i < $messageCount; $i++) {
            $randomUser = $users[array_rand($users)];
            $randomMessage = $sampleMessages[array_rand($sampleMessages)];

            ChatMessage::create([
                'chat_room_id' => $chatroom->id,
                'user_id' => $randomUser,
                'content' => $randomMessage,
                'type' => 'text',
                'is_deleted' => false,
                'created_at' => now()->subMinutes(rand(1, 1440)),
            ]);
        }

        $this->command->info("✅ Created messages for general chatroom: {$chatroom->name}");
    }
}

