<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use App\Models\NotificationPreference;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification to a user
     */
    public function sendToUser(User $user, string $type, string $title, string $message, array $data = [])
    {
        // Create notification record
        $notification = Notification::createForUser($user->id, $type, $title, $message, $data);

        // Get user preferences
        $preferences = $user->notificationPreferences;
        if (!$preferences) {
            $preferences = $user->notificationPreferences()->create(NotificationPreference::getDefaultPreferences());
        }

        // Send via enabled channels
        if ($preferences->isNotificationEnabled($type, 'email')) {
            $this->sendEmail($user, $notification);
        }

        if ($preferences->isNotificationEnabled($type, 'push')) {
            $this->sendPushNotification($user, $notification);
        }

        if ($preferences->isNotificationEnabled($type, 'sms')) {
            $this->sendSMS($user, $notification);
        }

        return $notification;
    }

    /**
     * Send notification to multiple users
     */
    public function sendToUsers(array $userIds, string $type, string $title, string $message, array $data = [])
    {
        $notifications = [];
        
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $notifications[] = $this->sendToUser($user, $type, $title, $message, $data);
            }
        }

        return $notifications;
    }

    /**
     * Broadcast notification to all users with a specific role
     */
    public function broadcastToRole(string $role, string $type, string $title, string $message, array $data = [])
    {
        $users = User::where('role', $role)->get();
        $notifications = [];

        foreach ($users as $user) {
            $notifications[] = $this->sendToUser($user, $type, $title, $message, $data);
        }

        return $notifications;
    }

    /**
     * Broadcast notification to all users
     */
    public function broadcastToAll(string $type, string $title, string $message, array $data = [])
    {
        $users = User::all();
        $notifications = [];

        foreach ($users as $user) {
            $notifications[] = $this->sendToUser($user, $type, $title, $message, $data);
        }

        return $notifications;
    }

    /**
     * Send course-related notifications
     */
    public function sendCourseNotification($courseId, string $type, string $title, string $message, array $data = [])
    {
        $course = \App\Models\Course::find($courseId);
        if (!$course) return [];

        $enrolledUsers = $course->students;
        $notifications = [];

        foreach ($enrolledUsers as $user) {
            $notifications[] = $this->sendToUser($user, $type, $title, $message, array_merge($data, ['course_id' => $courseId]));
        }

        return $notifications;
    }

    /**
     * Send email notification
     */
    protected function sendEmail(User $user, Notification $notification)
    {
        try {
            // Here you would integrate with your email service
            // For now, we'll just log it
            Log::info("Email notification sent to {$user->email}: {$notification->title}");
            
            // Example with Laravel Mail:
            // Mail::to($user->email)->send(new NotificationMail($notification));
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send email notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send push notification
     */
    protected function sendPushNotification(User $user, Notification $notification)
    {
        try {
            // Here you would integrate with push notification service (FCM, Pusher, etc.)
            Log::info("Push notification sent to user {$user->id}: {$notification->title}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send push notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send SMS notification
     */
    protected function sendSMS(User $user, Notification $notification)
    {
        try {
            // Here you would integrate with SMS service (Twilio, etc.)
            if ($user->contact) {
                Log::info("SMS notification sent to {$user->contact}: {$notification->title}");
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send SMS notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create system notification templates
     */
    public function createSystemNotifications()
    {
        return [
            'course_enrollment' => [
                'title' => 'Course Enrollment Successful',
                'message' => 'You have successfully enrolled in {course_title}. Start learning now!',
                'type' => 'course_updates'
            ],
            'course_completion' => [
                'title' => 'Course Completed!',
                'message' => 'Congratulations! You have completed {course_title}. Your certificate is ready.',
                'type' => 'achievement_notifications'
            ],
            'assignment_due' => [
                'title' => 'Assignment Due Soon',
                'message' => 'Your assignment "{assignment_title}" is due in {days} days.',
                'type' => 'assignment_reminders'
            ],
            'quiz_available' => [
                'title' => 'New Quiz Available',
                'message' => 'A new quiz "{quiz_title}" is now available in {course_title}.',
                'type' => 'quiz_reminders'
            ],
            'payment_successful' => [
                'title' => 'Payment Successful',
                'message' => 'Your payment of {amount} has been processed successfully.',
                'type' => 'payment_notifications'
            ],
            'new_course_available' => [
                'title' => 'New Course Available',
                'message' => 'A new course "{course_title}" is now available in your area of interest.',
                'type' => 'new_courses'
            ]
        ];
    }

    /**
     * Send templated notification
     */
    public function sendTemplatedNotification(User $user, string $template, array $variables = [])
    {
        $templates = $this->createSystemNotifications();
        
        if (!isset($templates[$template])) {
            throw new \InvalidArgumentException("Notification template '{$template}' not found");
        }

        $templateData = $templates[$template];
        
        // Replace variables in title and message
        $title = $this->replaceVariables($templateData['title'], $variables);
        $message = $this->replaceVariables($templateData['message'], $variables);
        
        return $this->sendToUser($user, $templateData['type'], $title, $message, $variables);
    }

    /**
     * Replace variables in template strings
     */
    protected function replaceVariables(string $template, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        
        return $template;
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
                                  ->where('user_id', $userId)
                                  ->first();
        
        if ($notification) {
            $notification->markAsRead();
            return true;
        }
        
        return false;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
                          ->whereNull('read_at')
                          ->update(['read_at' => now()]);
    }

    /**
     * Get unread notification count for user
     */
    public function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
                          ->whereNull('read_at')
                          ->count();
    }

    /**
     * Clean up old notifications
     */
    public function cleanupOldNotifications(int $daysOld = 90): int
    {
        return Notification::where('created_at', '<', now()->subDays($daysOld))
                          ->delete();
    }
}
