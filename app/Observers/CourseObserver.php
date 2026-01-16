<?php

namespace App\Observers;

use App\Models\Course;
use App\Models\ChatRoom;
use App\Models\SubscriptionPlan;

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
        try {
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

            // If course is marked as free_subscription, add it to the free subscription plan
            if ($course->free_subscription) {
                $this->attachToFreeSubscriptionPlan($course);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to create chatroom for course: ' . $e->getMessage(), [
                'course_id' => $course->id,
                'course_title' => $course->title,
            ]);
        }
    }

    /**
     * Handle the Course "updated" event.
     *
     * Update the chat room name and description if the course title or description changes.
     * Also handle free_subscription status changes.
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

        // Handle free_subscription status changes
        if ($course->isDirty('free_subscription')) {
            if ($course->free_subscription) {
                // Add to free subscription plan if not already attached
                $this->attachToFreeSubscriptionPlan($course);
            } else {
                // Remove from free subscription plan
                $this->detachFromFreeSubscriptionPlan($course);
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

    /**
     * Attach course to the free subscription plan
     */
    private function attachToFreeSubscriptionPlan(Course $course): void
    {
        try {
            // Find the free subscription plan
            $freePlan = SubscriptionPlan::where('duration_type', 'free')
                                        ->where('is_active', true)
                                        ->first();

            if ($freePlan) {
                // Attach course to free plan if not already attached
                if (!$course->subscriptionPlans()->where('subscription_plan_id', $freePlan->id)->exists()) {
                    $course->subscriptionPlans()->attach($freePlan->id);
                    \Log::info('Course attached to free subscription plan', [
                        'course_id' => $course->id,
                        'course_title' => $course->title,
                        'plan_id' => $freePlan->id,
                        'plan_title' => $freePlan->title,
                    ]);
                } else {
                    \Log::info('Course already attached to free subscription plan', [
                        'course_id' => $course->id,
                        'course_title' => $course->title,
                        'plan_id' => $freePlan->id,
                    ]);
                }
            } else {
                \Log::warning('Free subscription plan not found when trying to attach course', [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error attaching course to free subscription plan: ' . $e->getMessage(), [
                'course_id' => $course->id,
                'course_title' => $course->title,
            ]);
        }
    }

    /**
     * Detach course from the free subscription plan
     */
    private function detachFromFreeSubscriptionPlan(Course $course): void
    {
        try {
            // Find the free subscription plan
            $freePlan = SubscriptionPlan::where('duration_type', 'free')
                                        ->where('is_active', true)
                                        ->first();

            if ($freePlan) {
                // Detach course from free plan
                $course->subscriptionPlans()->detach($freePlan->id);
            }
        } catch (\Exception $e) {
            \Log::error('Error detaching course from free subscription plan: ' . $e->getMessage());
        }
    }
}

