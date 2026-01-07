<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Policies\ChatRoomPolicy;
use App\Policies\ChatMessagePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ChatRoom::class => ChatRoomPolicy::class,
        ChatMessage::class => ChatMessagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define custom gates for chat authorization
        
        /**
         * Gate: can-access-chat-room
         * Check if user can access a specific chat room
         */
        Gate::define('access-chat-room', function ($user, ChatRoom $chatRoom) {
            // Superadmin and admin can access all rooms
            if (in_array($user->role, ['superadmin', 'admin'])) {
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
        });

        /**
         * Gate: can-send-message
         * Check if user can send a message in a chat room
         */
        Gate::define('send-message', function ($user, ChatRoom $chatRoom) {
            // Admin can send messages in all rooms
            if (in_array($user->role, ['superadmin', 'admin'])) {
                return true;
            }

            // General chatrooms are accessible to all authenticated users
            if ($chatRoom->type === 'general') {
                // Check if room is active and not archived
                if (!$chatRoom->is_active || $chatRoom->is_archived) {
                    return false;
                }

                // Check if user is muted
                $isMuted = $chatRoom->users()
                    ->where('user_id', $user->id)
                    ->where('is_muted', true)
                    ->exists();

                if ($isMuted) {
                    return false;
                }

                return true;
            }

            // For other room types, check if user can access the room
            // Check if user is a member of the room
            if ($chatRoom->users()->where('user_id', $user->id)->exists()) {
                // Check if user is muted
                $isMuted = $chatRoom->users()
                    ->where('user_id', $user->id)
                    ->where('is_muted', true)
                    ->exists();

                if ($isMuted) {
                    return false;
                }

                // Room must be active and not archived
                if (!$chatRoom->is_active || $chatRoom->is_archived) {
                    return false;
                }

                return true;
            }

            // For course rooms, check if user is enrolled or instructor
            if ($chatRoom->type === 'course' && $chatRoom->course_id) {
                // Check if user is the instructor
                if ($chatRoom->course->instructor_id === $user->id) {
                    // Check if user is muted
                    $isMuted = $chatRoom->users()
                        ->where('user_id', $user->id)
                        ->where('is_muted', true)
                        ->exists();

                    if ($isMuted) {
                        return false;
                    }

                    // Room must be active and not archived
                    if (!$chatRoom->is_active || $chatRoom->is_archived) {
                        return false;
                    }

                    return true;
                }

                // Check if user is enrolled in the course
                if ($chatRoom->course->enrollments()
                    ->where('user_id', $user->id)
                    ->where('status', 'active')
                    ->exists()) {
                    // Check if user is muted
                    $isMuted = $chatRoom->users()
                        ->where('user_id', $user->id)
                        ->where('is_muted', true)
                        ->exists();

                    if ($isMuted) {
                        return false;
                    }

                    // Room must be active and not archived
                    if (!$chatRoom->is_active || $chatRoom->is_archived) {
                        return false;
                    }

                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: can-manage-chat-room
         * Check if user can manage a chat room (edit, delete, manage members)
         */
        Gate::define('manage-chat-room', function ($user, ChatRoom $chatRoom) {
            // Superadmin and admin can manage all rooms
            if (in_array($user->role, ['superadmin', 'admin'])) {
                return true;
            }

            // Room creator can manage
            if ($chatRoom->created_by === $user->id) {
                return true;
            }

            // Course instructor can manage course room
            if ($chatRoom->type === 'course' && $chatRoom->course_id) {
                if ($chatRoom->course->instructor_id === $user->id) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: can-moderate-chat-room
         * Check if user can moderate a chat room (delete messages, mute users)
         */
        Gate::define('moderate-chat-room', function ($user, ChatRoom $chatRoom) {
            // Superadmin and admin can moderate all rooms
            if (in_array($user->role, ['superadmin', 'admin'])) {
                return true;
            }

            // Room creator can moderate
            if ($chatRoom->created_by === $user->id) {
                return true;
            }

            // Course instructor can moderate course room
            if ($chatRoom->type === 'course' && $chatRoom->course_id) {
                if ($chatRoom->course->instructor_id === $user->id) {
                    return true;
                }
            }

            // Check if user is a moderator in the room
            if ($chatRoom->users()
                ->where('user_id', $user->id)
                ->where('role', 'moderator')
                ->exists()) {
                return true;
            }

            return false;
        });
    }
}

