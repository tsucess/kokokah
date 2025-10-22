<?php

namespace App\Services;

use App\Events\NotificationSent;
use App\Events\ChatMessageSent;
use App\Events\UserOnline;
use App\Events\TypingIndicator;
use App\Models\Notification;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class RealtimeService
{
    /**
     * Broadcast notification to user
     */
    public function broadcastNotification($userId, Notification $notification)
    {
        broadcast(new NotificationSent($notification, $userId))->toOthers();
        return true;
    }

    /**
     * Broadcast chat message
     */
    public function broadcastChatMessage($chatSessionId, ChatMessage $message)
    {
        broadcast(new ChatMessageSent($message, $chatSessionId))->toOthers();
        return true;
    }

    /**
     * Broadcast user online status
     */
    public function broadcastUserOnline($user, $courseId = null)
    {
        broadcast(new UserOnline($user, $courseId))->toOthers();
        $this->setUserOnline($user->id);
        return true;
    }

    /**
     * Broadcast user offline status
     */
    public function broadcastUserOffline($userId)
    {
        $this->setUserOffline($userId);
        return true;
    }

    /**
     * Broadcast typing indicator
     */
    public function broadcastTypingIndicator($userId, $userName, $chatSessionId, $isTyping = true)
    {
        broadcast(new TypingIndicator($userId, $userName, $chatSessionId, $isTyping))->toOthers();
        return true;
    }

    /**
     * Set user as online
     */
    public function setUserOnline($userId)
    {
        Cache::put("user_online_{$userId}", true, now()->addHours(24));
    }

    /**
     * Set user as offline
     */
    public function setUserOffline($userId)
    {
        Cache::forget("user_online_{$userId}");
    }

    /**
     * Check if user is online
     */
    public function isUserOnline($userId)
    {
        return Cache::has("user_online_{$userId}");
    }

    /**
     * Get online users
     */
    public function getOnlineUsers()
    {
        $users = User::all();
        $onlineUsers = [];

        foreach ($users as $user) {
            if ($this->isUserOnline($user->id)) {
                $onlineUsers[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar_url
                ];
            }
        }

        return $onlineUsers;
    }

    /**
     * Get online users in course
     */
    public function getOnlineUsersInCourse($courseId)
    {
        $cacheKey = "course_{$courseId}_online_users";
        return Cache::get($cacheKey, []);
    }

    /**
     * Add user to course online list
     */
    public function addUserToCourseOnline($courseId, $userId)
    {
        $cacheKey = "course_{$courseId}_online_users";
        $onlineUsers = Cache::get($cacheKey, []);

        if (!in_array($userId, $onlineUsers)) {
            $onlineUsers[] = $userId;
            Cache::put($cacheKey, $onlineUsers, now()->addHours(24));
        }
    }

    /**
     * Remove user from course online list
     */
    public function removeUserFromCourseOnline($courseId, $userId)
    {
        $cacheKey = "course_{$courseId}_online_users";
        $onlineUsers = Cache::get($cacheKey, []);

        $onlineUsers = array_filter($onlineUsers, function ($id) use ($userId) {
            return $id !== $userId;
        });

        Cache::put($cacheKey, array_values($onlineUsers), now()->addHours(24));
    }

    /**
     * Get online count
     */
    public function getOnlineCount()
    {
        return count($this->getOnlineUsers());
    }

    /**
     * Get online count in course
     */
    public function getOnlineCountInCourse($courseId)
    {
        return count($this->getOnlineUsersInCourse($courseId));
    }

    /**
     * Broadcast course update
     */
    public function broadcastCourseUpdate($courseId, $data)
    {
        // This would be implemented with a custom event
        // For now, we'll use a generic approach
        return true;
    }

    /**
     * Broadcast lesson update
     */
    public function broadcastLessonUpdate($lessonId, $data)
    {
        // This would be implemented with a custom event
        return true;
    }

    /**
     * Broadcast quiz update
     */
    public function broadcastQuizUpdate($quizId, $data)
    {
        // This would be implemented with a custom event
        return true;
    }

    /**
     * Get user activity status
     */
    public function getUserActivityStatus($userId)
    {
        return [
            'user_id' => $userId,
            'is_online' => $this->isUserOnline($userId),
            'last_seen' => Cache::get("user_last_seen_{$userId}"),
            'current_course' => Cache::get("user_current_course_{$userId}"),
            'current_lesson' => Cache::get("user_current_lesson_{$userId}")
        ];
    }

    /**
     * Update user activity
     */
    public function updateUserActivity($userId, $courseId = null, $lessonId = null)
    {
        $this->setUserOnline($userId);
        Cache::put("user_last_seen_{$userId}", now(), now()->addHours(24));

        if ($courseId) {
            Cache::put("user_current_course_{$userId}", $courseId, now()->addHours(24));
            $this->addUserToCourseOnline($courseId, $userId);
        }

        if ($lessonId) {
            Cache::put("user_current_lesson_{$userId}", $lessonId, now()->addHours(24));
        }
    }
}

