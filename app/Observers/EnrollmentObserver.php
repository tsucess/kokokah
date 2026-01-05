<?php

namespace App\Observers;

use App\Models\Enrollment;

class EnrollmentObserver
{
    /**
     * Handle the Enrollment "created" event.
     * 
     * When a student enrolls in a course, automatically add them to the course chat room.
     */
    public function created(Enrollment $enrollment): void
    {
        // Only add if enrollment is active
        if ($enrollment->status === 'active') {
            $chatRoom = $enrollment->course->chatRoom;

            if ($chatRoom) {
                // Check if user is already in the room
                if (!$chatRoom->users()->where('user_id', $enrollment->user_id)->exists()) {
                    $chatRoom->users()->attach($enrollment->user_id, [
                        'role' => 'member',
                        'is_active' => true,
                        'joined_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Handle the Enrollment "updated" event.
     * 
     * When enrollment status changes, update the user's status in the chat room.
     */
    public function updated(Enrollment $enrollment): void
    {
        $chatRoom = $enrollment->course->chatRoom;

        if (!$chatRoom) {
            return;
        }

        // If enrollment became active, add to chat room
        if ($enrollment->isDirty('status') && $enrollment->status === 'active') {
            if (!$chatRoom->users()->where('user_id', $enrollment->user_id)->exists()) {
                $chatRoom->users()->attach($enrollment->user_id, [
                    'role' => 'member',
                    'is_active' => true,
                    'joined_at' => now(),
                ]);
            } else {
                // If already exists, just update to active
                $chatRoom->users()->updateExistingPivot($enrollment->user_id, [
                    'is_active' => true,
                ]);
            }
        }

        // If enrollment became inactive, deactivate in chat room
        if ($enrollment->isDirty('status') && $enrollment->status !== 'active') {
            $chatRoom->users()->updateExistingPivot($enrollment->user_id, [
                'is_active' => false,
            ]);
        }
    }

    /**
     * Handle the Enrollment "deleted" event.
     * 
     * When an enrollment is deleted, remove the user from the chat room.
     */
    public function deleted(Enrollment $enrollment): void
    {
        $chatRoom = $enrollment->course->chatRoom;

        if ($chatRoom) {
            $chatRoom->users()->detach($enrollment->user_id);
        }
    }

    /**
     * Handle the Enrollment "restored" event.
     * 
     * When an enrollment is restored, re-add the user to the chat room.
     */
    public function restored(Enrollment $enrollment): void
    {
        // Only restore if enrollment is active
        if ($enrollment->status === 'active') {
            $chatRoom = $enrollment->course->chatRoom;

            if ($chatRoom) {
                if (!$chatRoom->users()->where('user_id', $enrollment->user_id)->exists()) {
                    $chatRoom->users()->attach($enrollment->user_id, [
                        'role' => 'member',
                        'is_active' => true,
                        'joined_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Handle the Enrollment "force deleted" event.
     * 
     * When an enrollment is permanently deleted, remove the user from the chat room.
     */
    public function forceDeleted(Enrollment $enrollment): void
    {
        $chatRoom = $enrollment->course->chatRoom;

        if ($chatRoom) {
            $chatRoom->users()->detach($enrollment->user_id);
        }
    }
}

