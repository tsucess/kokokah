<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use App\Models\ChatRoom;

class CheckCoursesChatrooms extends Command
{
    protected $signature = 'check:course-chatrooms';
    protected $description = 'Check if courses have associated chatrooms';

    public function handle()
    {
        $this->info('Checking courses and their chatrooms...');
        
        $courses = Course::all();
        
        if ($courses->isEmpty()) {
            $this->warn('No courses found');
            return;
        }
        
        foreach ($courses as $course) {
            $chatRoom = ChatRoom::where('course_id', $course->id)->first();
            
            if ($chatRoom) {
                $this->line("✅ Course: {$course->title} → ChatRoom: {$chatRoom->name}");
            } else {
                $this->error("❌ Course: {$course->title} → NO CHATROOM");
            }
        }
        
        $this->info('Check complete!');
    }
}

