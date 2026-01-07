<?php

namespace App\Policies;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatMessagePolicy
{
    /**
     * Determine whether the user can view any chat messages in a room.
     *
     * Rules:
     * - User must have access to the chat room
     * - Admin can view all messages
     *
     * @param User $user
     * @param ChatRoom $chatRoom
     * @return Response
     */
    public function viewAny(User $user, ChatRoom $chatRoom): Response
    {
        // Admin can view all messages
        if ($user->role === 'admin') {
            return Response::allow();
        }

        // Check if user can view the room
        if (!$this->canAccessRoom($user, $chatRoom)) {
            return Response::deny('You do not have access to this chat room.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can view the chat message.
     *
     * @param User $user
     * @param ChatMessage $message
     * @return Response
     */
    public function view(User $user, ChatMessage $message): Response
    {
        // Admin can view all messages
        if ($user->role === 'admin') {
            return Response::allow();
        }

        // Check if user can access the room
        if (!$this->canAccessRoom($user, $message->chatRoom)) {
            return Response::deny('You do not have access to this chat room.');
        }

        // If message is deleted, only owner can view
        if ($message->is_deleted && $message->user_id !== $user->id) {
            return Response::deny('This message has been deleted.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create chat messages.
     *
     * @param User $user
     * @param ChatRoom $chatRoom
     * @return Response
     */
    public function create(User $user, ChatRoom $chatRoom): Response
    {
        // Check if user can access the room
        if (!$this->canAccessRoom($user, $chatRoom)) {
            return Response::deny('You do not have access to this chat room.');
        }

        // User must not be muted
        $isMuted = $chatRoom->users()
            ->where('user_id', $user->id)
            ->where('is_muted', true)
            ->exists();

        if ($isMuted) {
            return Response::deny('You are muted in this chat room.');
        }

        // Check if room is archived
        if ($chatRoom->is_archived) {
            return Response::deny('This chat room is archived and cannot receive new messages.');
        }

        // Check if room is active
        if (!$chatRoom->is_active) {
            return Response::deny('This chat room is inactive.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the chat message.
     *
     * @param User $user
     * @param ChatMessage $message
     * @return Response
     */
    public function update(User $user, ChatMessage $message): Response
    {
        // Admin can update any message
        if ($user->role === 'admin') {
            return Response::allow();
        }

        // Only message owner can update
        if ($message->user_id !== $user->id) {
            return Response::deny('You can only edit your own messages.');
        }

        // Cannot edit deleted messages
        if ($message->is_deleted) {
            return Response::deny('You cannot edit a deleted message.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the chat message.
     *
     * @param User $user
     * @param ChatMessage $message
     * @return Response
     */
    public function delete(User $user, ChatMessage $message): Response
    {
        // Admin can delete any message
        if ($user->role === 'admin') {
            return Response::allow();
        }

        // Message owner can delete their own message
        if ($message->user_id === $user->id) {
            return Response::allow();
        }

        // Room creator can delete messages in their room
        if ($message->chatRoom->created_by === $user->id) {
            return Response::allow();
        }

        // Course instructor can delete messages in their course room
        if ($message->chatRoom->type === 'course' && $message->chatRoom->course_id) {
            if ($message->chatRoom->course->instructor_id === $user->id) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have permission to delete this message.');
    }

    /**
     * Determine whether the user can restore the chat message.
     *
     * @param User $user
     * @param ChatMessage $message
     * @return Response
     */
    public function restore(User $user, ChatMessage $message): Response
    {
        // Admin can restore any message
        if ($user->role === 'admin') {
            return Response::allow();
        }

        // Message owner can restore their own message
        if ($message->user_id === $user->id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to restore this message.');
    }

    /**
     * Determine whether the user can permanently delete the chat message.
     *
     * @param User $user
     * @param ChatMessage $message
     * @return Response
     */
    public function forceDelete(User $user, ChatMessage $message): Response
    {
        // Only admin can permanently delete
        if ($user->role === 'admin') {
            return Response::allow();
        }

        return Response::deny('You do not have permission to permanently delete this message.');
    }

    /**
     * Determine whether the user can add reactions to a message.
     *
     * @param User $user
     * @param ChatMessage $message
     * @return Response
     */
    public function react(User $user, ChatMessage $message): Response
    {
        // Check if user can access the room
        if (!$this->canAccessRoom($user, $message->chatRoom)) {
            return Response::deny('You do not have access to this chat room.');
        }

        // Check if user is muted in the room
        $isMuted = $message->chatRoom->users()
            ->where('user_id', $user->id)
            ->where('is_muted', true)
            ->exists();

        if ($isMuted) {
            return Response::deny('You are muted in this chat room.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can pin a message.
     *
     * @param User $user
     * @param ChatMessage $message
     * @return Response
     */
    public function pin(User $user, ChatMessage $message): Response
    {
        // Admin can pin any message
        if ($user->role === 'admin') {
            return Response::allow();
        }

        // Room creator can pin messages
        if ($message->chatRoom->created_by === $user->id) {
            return Response::allow();
        }

        // Course instructor can pin messages in their course room
        if ($message->chatRoom->type === 'course' && $message->chatRoom->course_id) {
            if ($message->chatRoom->course->instructor_id === $user->id) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have permission to pin messages in this room.');
    }

    /**
     * Determine whether the user can unpin a message.
     *
     * @param User $user
     * @param ChatMessage $message
     * @return Response
     */
    public function unpin(User $user, ChatMessage $message): Response
    {
        // Same rules as pin
        return $this->pin($user, $message);
    }

    /**
     * Determine whether the user can view deleted messages.
     *
     * @param User $user
     * @param ChatMessage $message
     * @return Response
     */
    public function viewDeleted(User $user, ChatMessage $message): Response
    {
        // Admin can view all deleted messages
        if ($user->role === 'admin') {
            return Response::allow();
        }

        // Message owner can view their own deleted message
        if ($message->user_id === $user->id) {
            return Response::allow();
        }

        // Room creator can view deleted messages in their room
        if ($message->chatRoom->created_by === $user->id) {
            return Response::allow();
        }

        // Course instructor can view deleted messages in their course room
        if ($message->chatRoom->type === 'course' && $message->chatRoom->course_id) {
            if ($message->chatRoom->course->instructor_id === $user->id) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have permission to view this deleted message.');
    }

    /**
     * Helper method to check if user can access a chat room.
     *
     * Rules:
     * - Admin can access all rooms
     * - General chatrooms are accessible to all authenticated users
     * - User must be a member of the room
     * - For course rooms: user must be enrolled or instructor
     *
     * @param User $user
     * @param ChatRoom $chatRoom
     * @return bool
     */
    private function canAccessRoom(User $user, ChatRoom $chatRoom): bool
    {
        // Admin can access all rooms
        if ($user->role === 'admin') {
            return true;
        }

        // General chatrooms are accessible to all authenticated users
        if ($chatRoom->type === 'general') {
            return true;
        }

        // Check if user is a member of the room
        if ($chatRoom->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        // For course rooms, check if user is enrolled or instructor
        if ($chatRoom->type === 'course' && $chatRoom->course_id) {
            // Check if user is the instructor
            if ($chatRoom->course->instructor_id === $user->id) {
                return true;
            }

            // Check if user is enrolled in the course
            if ($chatRoom->course->enrollments()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->exists()) {
                return true;
            }
        }

        return false;
    }
}

