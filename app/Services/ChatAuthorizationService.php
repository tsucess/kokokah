<?php

namespace App\Services;

use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;

/**
 * Chat Authorization Service
 * 
 * Centralized service for all chat authorization logic.
 * Provides methods to check user permissions for various chat operations.
 */
class ChatAuthorizationService
{
    /**
     * Check if user can view a chat room.
     */
    public function canViewRoom(User $user, ChatRoom $chatRoom): bool
    {
        // Admin can view all rooms
        if ($user->role === 'admin') {
            return true;
        }

        // Check if user is a member of the room
        if ($chatRoom->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        // For course rooms, check if user is enrolled or instructor
        if ($chatRoom->type === 'course' && $chatRoom->course_id) {
            if ($chatRoom->course->instructor_id === $user->id) {
                return true;
            }

            if ($chatRoom->course->enrollments()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user can send messages in a chat room.
     */
    public function canSendMessage(User $user, ChatRoom $chatRoom): bool
    {
        // Must be able to view the room first
        if (!$this->canViewRoom($user, $chatRoom)) {
            return false;
        }

        // Check if user is muted
        if ($chatRoom->users()
            ->where('user_id', $user->id)
            ->where('is_muted', true)
            ->exists()) {
            return false;
        }

        // Check if room is archived
        if ($chatRoom->is_archived) {
            return false;
        }

        // Check if room is active
        if (!$chatRoom->is_active) {
            return false;
        }

        return true;
    }

    /**
     * Check if user can edit a message.
     */
    public function canEditMessage(User $user, ChatMessage $message): bool
    {
        // Admin can edit any message
        if ($user->role === 'admin') {
            return true;
        }

        // Only message owner can edit
        if ($message->user_id !== $user->id) {
            return false;
        }

        // Cannot edit deleted messages
        if ($message->is_deleted) {
            return false;
        }

        return true;
    }

    /**
     * Check if user can delete a message.
     */
    public function canDeleteMessage(User $user, ChatMessage $message): bool
    {
        // Superadmin and admin can delete any message
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return true;
        }

        // Message owner can delete their own message
        if ($message->user_id === $user->id) {
            return true;
        }

        // Room creator can delete messages in their room
        if ($message->room->created_by === $user->id) {
            return true;
        }

        // Course instructor can delete messages in their course room
        if ($message->room->type === 'course' && $message->room->course_id) {
            if ($message->room->course->instructor_id === $user->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user can manage members in a chat room.
     */
    public function canManageMembers(User $user, ChatRoom $chatRoom): bool
    {
        // Superadmin and admin can manage members in any room
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return true;
        }

        // Room creator can manage members
        if ($chatRoom->created_by === $user->id) {
            return true;
        }

        // Course instructor can manage members in course room
        if ($chatRoom->type === 'course' && $chatRoom->course_id) {
            if ($chatRoom->course->instructor_id === $user->id) {
                return true;
            }
        }

        return false;
    }
}

