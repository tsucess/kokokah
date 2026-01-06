<?php

namespace App\Policies;

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatRoomPolicy
{
    /**
     * Determine if the user can view the chat room.
     * 
     * Rules:
     * - Admin can view all rooms
     * - User can view rooms they belong to
     * - For course rooms: user must be enrolled or instructor
     */
    public function view(User $user, ChatRoom $chatRoom): Response
    {
        // Superadmin and admin can view all rooms
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return Response::allow();
        }

        // Check if user is a member of the room
        if ($chatRoom->users()->where('user_id', $user->id)->exists()) {
            return Response::allow();
        }

        // For course rooms, check if user is enrolled or instructor
        if ($chatRoom->type === 'course' && $chatRoom->course_id) {
            // Check if user is the instructor
            if ($chatRoom->course->instructor_id === $user->id) {
                return Response::allow();
            }

            // Check if user is enrolled in the course
            if ($chatRoom->course->enrollments()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->exists()) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have access to this chat room.');
    }

    /**
     * Determine if the user can create a chat room.
     *
     * Rules:
     * - Only authenticated users can create rooms
     * - Superadmin, admin and instructors can create course rooms
     * - Instructors have same features as students
     */
    public function create(User $user): Response
    {
        // All authenticated users can create general rooms
        if (in_array($user->role, ['student', 'parent', 'instructor'])) {
            return Response::allow();
        }

        // Superadmin and admin can create any type of room
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to create chat rooms.');
    }

    /**
     * Determine if the user can update the chat room.
     *
     * Rules:
     * - Superadmin and admin can update any room
     * - Room creator can update their own room
     * - Course instructors can update course rooms
     */
    public function update(User $user, ChatRoom $chatRoom): Response
    {
        // Superadmin and admin can update any room
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return Response::allow();
        }

        // Room creator can update
        if ($chatRoom->created_by === $user->id) {
            return Response::allow();
        }

        // Course instructor can update course room
        if ($chatRoom->type === 'course' && $chatRoom->course_id) {
            if ($chatRoom->course->instructor_id === $user->id) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have permission to update this chat room.');
    }

    /**
     * Determine if the user can delete the chat room.
     *
     * Rules:
     * - Superadmin and admin can delete any room
     * - Room creator can delete their own room
     * - Course instructors can delete course rooms
     */
    public function delete(User $user, ChatRoom $chatRoom): Response
    {
        // Superadmin and admin can delete any room
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return Response::allow();
        }

        // Room creator can delete
        if ($chatRoom->created_by === $user->id) {
            return Response::allow();
        }

        // Course instructor can delete course room
        if ($chatRoom->type === 'course' && $chatRoom->course_id) {
            if ($chatRoom->course->instructor_id === $user->id) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have permission to delete this chat room.');
    }

    /**
     * Determine if the user can manage members in the chat room.
     *
     * Rules:
     * - Superadmin and admin can manage members in any room
     * - Room creator can manage members
     * - Course instructors can manage members in course rooms
     */
    public function manageMember(User $user, ChatRoom $chatRoom): Response
    {
        // Superadmin and admin can manage members in any room
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return Response::allow();
        }

        // Room creator can manage members
        if ($chatRoom->created_by === $user->id) {
            return Response::allow();
        }

        // Course instructor can manage members in course room
        if ($chatRoom->type === 'course' && $chatRoom->course_id) {
            if ($chatRoom->course->instructor_id === $user->id) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have permission to manage members in this chat room.');
    }

    /**
     * Determine if the user can archive the chat room.
     * 
     * Rules:
     * - Admin can archive any room
     * - Room creator can archive their own room
     * - Course instructors can archive course rooms
     */
    public function archive(User $user, ChatRoom $chatRoom): Response
    {
        return $this->update($user, $chatRoom);
    }

    /**
     * Determine if the user can restore the chat room.
     * 
     * Rules:
     * - Admin can restore any room
     * - Room creator can restore their own room
     * - Course instructors can restore course rooms
     */
    public function restore(User $user, ChatRoom $chatRoom): Response
    {
        return $this->update($user, $chatRoom);
    }

    /**
     * Determine if the user can force delete the chat room.
     *
     * Rules:
     * - Only superadmin and admin can force delete
     */
    public function forceDelete(User $user, ChatRoom $chatRoom): Response
    {
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to permanently delete this chat room.');
    }

    /**
     * Determine if the user can mute another user in the chat room.
     *
     * Rules:
     * - Superadmin and admin can mute any user
     * - Room creator can mute users
     * - Course instructors can mute users in their course room
     */
    public function muteUser(User $user, ChatRoom $chatRoom): Response
    {
        // Superadmin and admin can mute any user
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return Response::allow();
        }

        // Room creator can mute users
        if ($chatRoom->created_by === $user->id) {
            return Response::allow();
        }

        // Course instructor can mute users in course room
        if ($chatRoom->type === 'course' && $chatRoom->course_id) {
            if ($chatRoom->course->instructor_id === $user->id) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have permission to mute users in this chat room.');
    }

    /**
     * Determine if the user can remove another user from the chat room.
     *
     * Rules:
     * - Superadmin and admin can remove any user
     * - Room creator can remove users
     * - Course instructors can remove users from their course room
     */
    public function removeUser(User $user, ChatRoom $chatRoom): Response
    {
        // Superadmin and admin can remove any user
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return Response::allow();
        }

        // Room creator can remove users
        if ($chatRoom->created_by === $user->id) {
            return Response::allow();
        }

        // Course instructor can remove users from course room
        if ($chatRoom->type === 'course' && $chatRoom->course_id) {
            if ($chatRoom->course->instructor_id === $user->id) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have permission to remove users from this chat room.');
    }

    /**
     * Determine if the user can add members to the chat room.
     *
     * Rules:
     * - Superadmin and admin can add members to any room
     * - Room creator can add members
     * - Course instructors can add members to their course room
     */
    public function addMember(User $user, ChatRoom $chatRoom): Response
    {
        // Superadmin and admin can add members to any room
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return Response::allow();
        }

        // Room creator can add members
        if ($chatRoom->created_by === $user->id) {
            return Response::allow();
        }

        // Course instructor can add members to course room
        if ($chatRoom->type === 'course' && $chatRoom->course_id) {
            if ($chatRoom->course->instructor_id === $user->id) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have permission to add members to this chat room.');
    }
}

