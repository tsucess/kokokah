<?php

namespace App\Observers;

use App\Models\Course;
use App\Models\ChatRoom;

class CourseObserver
{
    /**
     * Handle the Course "created" event.
     * 
     * This observer automatically creates a chat room whenever a new course is created.
     * It also attaches the instructor and all enrolled students to the room.
     */
    public function created(Course $course): void
    {
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

    /**
     * Handle the Course "updated" event.
     * 
     * Update the chat room name and description if the course title or description changes.
     */
    public function updated(Course $course): void
    {
        // Only update if title or description changed
        if ($course->isDirty('title') || $course->isDirty('description')) {
            $chatRoom = $course->chatRoom;

            if ($chatRoom) {
                $chatRoom->update([
                    'name' => $course->title . ' Discussion',
                    'description' => 'Discussion room for ' . $course->title,
                ]);
            }
        }
    }

    /**
     * Handle the Course "deleted" event.
     * 
     * Soft delete the associated chat room when a course is deleted.
     */
    public function deleted(Course $course): void
    {
        $chatRoom = $course->chatRoom;

        if ($chatRoom) {
            $chatRoom->delete();  // Soft delete
        }
    }

    /**
     * Handle the Course "restored" event.
     * 
     * Restore the chat room when a course is restored from soft delete.
     */
    public function restored(Course $course): void
    {
        $chatRoom = $course->chatRoom;

        if ($chatRoom && $chatRoom->trashed()) {
            $chatRoom->restore();
        }
    }

    /**
     * Handle the Course "force deleted" event.
     * 
     * Permanently delete the chat room when a course is force deleted.
     */
    public function forceDeleted(Course $course): void
    {
        $chatRoom = $course->chatRoom;

        if ($chatRoom) {
            $chatRoom->forceDelete();  // Permanent delete
        }
    }
}

