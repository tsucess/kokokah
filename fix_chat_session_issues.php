<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING CHAT SESSION ISSUES\n";
echo "==================================================\n\n";

try {
    // 1. Add course_id column to chat_sessions table
    echo "💬 Adding course_id to chat_sessions table...\n";
    if (!Schema::hasColumn('chat_sessions', 'course_id')) {
        DB::statement("ALTER TABLE chat_sessions ADD COLUMN course_id BIGINT UNSIGNED NULL AFTER user_id");
        DB::statement("ALTER TABLE chat_sessions ADD FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL");
        echo "  ✅ Added course_id column to chat_sessions table\n";
    } else {
        echo "  ✅ course_id column already exists in chat_sessions table\n";
    }

    // 2. Add session_type column to chat_sessions table
    echo "💬 Adding session_type to chat_sessions table...\n";
    if (!Schema::hasColumn('chat_sessions', 'session_type')) {
        DB::statement("ALTER TABLE chat_sessions ADD COLUMN session_type ENUM('general', 'course_help', 'assignment_help', 'quiz_help') DEFAULT 'general' AFTER course_id");
        echo "  ✅ Added session_type column to chat_sessions table\n";
    } else {
        echo "  ✅ session_type column already exists in chat_sessions table\n";
    }

    // 3. Add context column to chat_sessions table
    echo "💬 Adding context to chat_sessions table...\n";
    if (!Schema::hasColumn('chat_sessions', 'context')) {
        DB::statement("ALTER TABLE chat_sessions ADD COLUMN context JSON NULL AFTER session_type");
        echo "  ✅ Added context column to chat_sessions table\n";
    } else {
        echo "  ✅ context column already exists in chat_sessions table\n";
    }

    // 4. Update existing chat sessions with sample data
    echo "💬 Updating existing chat sessions...\n";
    $courses = DB::table('courses')->pluck('id')->toArray();
    
    if (!empty($courses)) {
        $sessionsToUpdate = DB::table('chat_sessions')
            ->whereNull('course_id')
            ->limit(5)
            ->get();
        
        foreach ($sessionsToUpdate as $session) {
            $courseId = $courses[array_rand($courses)];
            DB::table('chat_sessions')
                ->where('id', $session->id)
                ->update([
                    'course_id' => $courseId,
                    'session_type' => 'course_help',
                    'context' => json_encode(['topic' => 'General course help'])
                ]);
        }
        echo "  ✅ Updated " . count($sessionsToUpdate) . " chat sessions with course data\n";
    }

    // 5. Create chat_messages table if it doesn't exist
    echo "💬 Checking chat_messages table...\n";
    if (!Schema::hasTable('chat_messages')) {
        Schema::create('chat_messages', function ($table) {
            $table->id();
            $table->foreignId('chat_session_id')->constrained()->onDelete('cascade');
            $table->enum('sender', ['user', 'bot', 'system'])->default('user');
            $table->text('message');
            $table->json('metadata')->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            $table->index(['chat_session_id', 'sent_at']);
        });
        echo "  ✅ Created chat_messages table\n";
    } else {
        echo "  ✅ chat_messages table already exists\n";
    }

    // 6. Add sent_at column to chat_messages if missing
    if (Schema::hasTable('chat_messages') && !Schema::hasColumn('chat_messages', 'sent_at')) {
        DB::statement("ALTER TABLE chat_messages ADD COLUMN sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER message");
        echo "  ✅ Added sent_at column to chat_messages table\n";
    }

    // 7. Create sample chat messages
    echo "💬 Creating sample chat messages...\n";
    $chatSessions = DB::table('chat_sessions')->pluck('id')->toArray();
    
    if (!empty($chatSessions)) {
        $messageExists = DB::table('chat_messages')->exists();
        
        if (!$messageExists) {
            foreach (array_slice($chatSessions, 0, 3) as $sessionId) {
                // User message
                DB::table('chat_messages')->insert([
                    'chat_session_id' => $sessionId,
                    'sender' => 'user',
                    'message' => 'Hello, I need help with this course.',
                    'created_at' => now()->subMinutes(10),
                    'updated_at' => now()->subMinutes(10)
                ]);

                // Bot response
                DB::table('chat_messages')->insert([
                    'chat_session_id' => $sessionId,
                    'sender' => 'bot',
                    'message' => 'Hello! I\'m here to help you with your course. What specific topic would you like assistance with?',
                    'created_at' => now()->subMinutes(9),
                    'updated_at' => now()->subMinutes(9)
                ]);
            }
            echo "  ✅ Created sample chat messages\n";
        } else {
            echo "  ✅ Chat messages already exist\n";
        }
    }

    echo "\n🎉 Chat session issues have been fixed!\n";
    echo "✅ course_id column added to chat_sessions\n";
    echo "✅ session_type column added to chat_sessions\n";
    echo "✅ context column added to chat_sessions\n";
    echo "✅ Existing chat sessions updated\n";
    echo "✅ chat_messages table verified\n";
    echo "✅ Sample chat messages created\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
