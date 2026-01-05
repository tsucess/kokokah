<?php

namespace App\Listeners;

use App\Events\CourseCreated;
use App\Models\ChatRoom;

class CreateCourseChatRoom
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * 
     * This listener creates a chat room whenever a course is created.
     * It also attaches the instructor and all enrolled students to the room.
     */
    public function handle(CourseCreated $event): void
    {
        $course = $event->course;

        // Create a chat room for this course
        $chatRoom = ChatRoom::create([
            'name' => $course->title . ' Discussion',
            'description' => 'Discussion room for ' . $course->title,
            'type' => 'course',
            'course_id' => $course->id,
            'created_by' => $course->instructor_id,
            'background_image' => 'images/default-course-chat-bg.jpg',
            'color' => '#007bff',
            'is_active' => true,
        ]);

        // Attach the instructor as admin
        if ($course->instructor_id) {
            $chatRoom->users()->attach($course->instructor_id, [
                'role' => 'admin',
                'is_active' => true,
                'joined_at' => now(),
            ]);
        }

        // Attach all enrolled students as members
        $enrolledStudents = $course->enrollments()
            ->where('status', 'active')
            ->pluck('user_id')
            ->toArray();

        if (!empty($enrolledStudents)) {
            $attachData = [];
            foreach ($enrolledStudents as $studentId) {
                $attachData[$studentId] = [
                    'role' => 'member',
                    'is_active' => true,
                    'joined_at' => now(),
                ];
            }
            $chatRoom->users()->attach($attachData);
        }
    }
}

