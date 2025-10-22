<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for middleware configuration

    /**
     * Get user notifications
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'type' => 'nullable|in:course,assignment,quiz,system,payment,social',
                'status' => 'nullable|in:read,unread',
                'per_page' => 'nullable|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = Notification::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc');

            if ($request->type) {
                $query->where('type', $request->type);
            }

            if ($request->status === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($request->status === 'unread') {
                $query->whereNull('read_at');
            }

            $notifications = $query->paginate($request->get('per_page', 20));

            // Add summary
            $summary = [
                'total_notifications' => $notifications->total(),
                'unread_count' => Notification::where('user_id', $user->id)
                                              ->whereNull('read_at')
                                              ->count(),
                'types_breakdown' => $this->getNotificationTypesBreakdown($user->id)
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'notifications' => $notifications,
                    'summary' => $summary
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        try {
            $user = Auth::user();
            // Query by notifiable_id (polymorphic notifications) or user_id (custom notifications)
            $notification = Notification::where(function($q) use ($user) {
                $q->where('notifiable_id', $user->id)
                  ->orWhere('user_id', $user->id);
            })->findOrFail($id);

            if (!$notification->read_at) {
                $notification->update(['read_at' => now()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
                'data' => $notification
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();

            $updatedCount = Notification::where('user_id', $user->id)
                                      ->whereNull('read_at')
                                      ->update(['read_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => "{$updatedCount} notifications marked as read"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete notification
     */
    public function delete($id)
    {
        try {
            $user = Auth::user();
            // Query by notifiable_id (polymorphic notifications) or user_id (custom notifications)
            $notification = Notification::where(function($q) use ($user) {
                $q->where('notifiable_id', $user->id)
                  ->orWhere('user_id', $user->id);
            })->findOrFail($id);

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get notification preferences
     */
    public function getPreferences()
    {
        try {
            $user = Auth::user();

            $preferences = NotificationPreference::where('user_id', $user->id)->first();

            if (!$preferences) {
                // Create default preferences
                $preferences = NotificationPreference::create([
                    'user_id' => $user->id,
                    'email_notifications' => true,
                    'push_notifications' => true,
                    'sms_notifications' => false,
                    'course_updates' => true,
                    'assignment_reminders' => true,
                    'quiz_reminders' => true,
                    'payment_notifications' => true,
                    'marketing_emails' => false,
                    'system_notifications' => true,
                    'social_notifications' => true
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $preferences
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notification preferences: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update notification preferences
     */
    public function updatePreferences(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'email_notifications' => 'nullable|boolean',
                'push_notifications' => 'nullable|boolean',
                'sms_notifications' => 'nullable|boolean',
                'course_updates' => 'nullable|boolean',
                'assignment_reminders' => 'nullable|boolean',
                'quiz_reminders' => 'nullable|boolean',
                'payment_notifications' => 'nullable|boolean',
                'marketing_emails' => 'nullable|boolean',
                'system_notifications' => 'nullable|boolean',
                'social_notifications' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $preferences = NotificationPreference::updateOrCreate(
                ['user_id' => $user->id],
                $request->only([
                    'email_notifications', 'push_notifications', 'sms_notifications',
                    'course_updates', 'assignment_reminders', 'quiz_reminders',
                    'payment_notifications', 'marketing_emails', 'system_notifications',
                    'social_notifications'
                ])
            );

            return response()->json([
                'success' => true,
                'message' => 'Notification preferences updated successfully',
                'data' => $preferences
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update notification preferences: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification (admin only)
     */
    public function sendNotification(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'recipient_id' => 'required|exists:users,id',
                'type' => 'required|in:course,assignment,quiz,system,payment,social',
                'title' => 'required|string|max:255',
                'message' => 'required|string|max:1000',
                'action_url' => 'nullable|url',
                'priority' => 'nullable|in:low,normal,high,urgent'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $notification = Notification::create([
                'user_id' => $request->recipient_id,
                'type' => $request->type,
                'title' => $request->title,
                'message' => $request->message,
                'action_url' => $request->action_url,
                'priority' => $request->priority ?? 'normal',
                'sent_by' => $user->id
            ]);

            // Send actual notification (email, push, etc.)
            $this->dispatchNotification($notification);

            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully',
                'data' => $notification
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Broadcast notification to multiple users (admin only)
     */
    public function broadcastNotification(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'recipient_type' => 'required|in:all,role,course,specific',
                'recipients' => 'required_if:recipient_type,specific|array',
                'recipients.*' => 'exists:users,id',
                'role' => 'required_if:recipient_type,role|in:student,instructor,admin',
                'course_id' => 'required_if:recipient_type,course|exists:courses,id',
                'type' => 'required|in:course,assignment,quiz,system,payment,social',
                'title' => 'required|string|max:255',
                'message' => 'required|string|max:1000',
                'action_url' => 'nullable|url',
                'priority' => 'nullable|in:low,normal,high,urgent'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get recipient list
            $recipients = $this->getRecipientList($request);

            $sentCount = 0;
            foreach ($recipients as $recipientId) {
                try {
                    $notification = Notification::create([
                        'user_id' => $recipientId,
                        'type' => $request->type,
                        'title' => $request->title,
                        'message' => $request->message,
                        'action_url' => $request->action_url,
                        'priority' => $request->priority ?? 'normal',
                        'sent_by' => $user->id
                    ]);

                    $this->dispatchNotification($notification);
                    $sentCount++;
                } catch (\Exception $e) {
                    // Log error but continue with other recipients
                    \Log::error('Failed to send notification to user ' . $recipientId . ': ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Notification broadcast completed. Sent to {$sentCount} users.",
                'data' => [
                    'sent_count' => $sentCount,
                    'total_recipients' => count($recipients)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to broadcast notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get notification analytics (admin only)
     */
    public function getAnalytics(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $period = $request->get('period', 30); // days

            $analytics = [
                'overview' => [
                    'total_notifications' => Notification::count(),
                    'notifications_sent_today' => Notification::whereDate('created_at', today())->count(),
                    'average_read_rate' => $this->calculateReadRate(),
                    'most_active_type' => $this->getMostActiveNotificationType()
                ],
                'engagement' => [
                    'read_rate_by_type' => $this->getReadRateByType(),
                    'response_times' => $this->getAverageResponseTimes(),
                    'user_engagement' => $this->getUserEngagementMetrics()
                ],
                'trends' => [
                    'daily_notifications' => $this->getDailyNotificationTrends($period),
                    'type_distribution' => $this->getTypeDistribution($period),
                    'peak_hours' => $this->getPeakNotificationHours()
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notification analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods
     */
    private function getNotificationTypesBreakdown($userId)
    {
        return Notification::where('user_id', $userId)
                          ->select('type', DB::raw('count(*) as count'))
                          ->groupBy('type')
                          ->get()
                          ->pluck('count', 'type');
    }

    private function getRecipientList($request)
    {
        switch ($request->recipient_type) {
            case 'all':
                return User::pluck('id')->toArray();
            case 'role':
                return User::where('role', $request->role)->pluck('id')->toArray();
            case 'course':
                return User::whereHas('enrollments', function($query) use ($request) {
                    $query->where('course_id', $request->course_id);
                })->pluck('id')->toArray();
            case 'specific':
                return $request->recipients;
            default:
                return [];
        }
    }

    private function dispatchNotification($notification)
    {
        // Mock implementation - would dispatch actual notifications
        // This would integrate with email services, push notification services, etc.
        \Log::info('Notification dispatched', [
            'notification_id' => $notification->id,
            'user_id' => $notification->user_id,
            'type' => $notification->type
        ]);
    }

    private function calculateReadRate()
    {
        $total = Notification::count();
        if ($total === 0) return 0;

        $read = Notification::whereNotNull('read_at')->count();
        return round(($read / $total) * 100, 2);
    }

    private function getMostActiveNotificationType()
    {
        return Notification::select('type', DB::raw('count(*) as count'))
                          ->groupBy('type')
                          ->orderBy('count', 'desc')
                          ->first();
    }

    private function getReadRateByType()
    {
        return Notification::select('type')
                          ->selectRaw('COUNT(*) as total')
                          ->selectRaw('COUNT(read_at) as read_count')
                          ->selectRaw('ROUND((COUNT(read_at) / COUNT(*)) * 100, 2) as read_rate')
                          ->groupBy('type')
                          ->get();
    }

    private function getAverageResponseTimes()
    {
        // Mock implementation - would calculate actual response times
        return [
            'course' => '2.5 hours',
            'assignment' => '4.2 hours',
            'quiz' => '1.8 hours',
            'system' => '6.1 hours'
        ];
    }

    private function getUserEngagementMetrics()
    {
        return [
            'highly_engaged' => User::whereHas('notifications', function($query) {
                $query->whereNotNull('read_at');
            })->count(),
            'moderately_engaged' => rand(50, 100),
            'low_engagement' => rand(20, 50)
        ];
    }

    private function getDailyNotificationTrends($period)
    {
        $data = [];
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'count' => Notification::whereDate('created_at', $date)->count()
            ];
        }
        return $data;
    }

    private function getTypeDistribution($period)
    {
        return Notification::where('created_at', '>=', now()->subDays($period))
                          ->select('type', DB::raw('count(*) as count'))
                          ->groupBy('type')
                          ->get()
                          ->pluck('count', 'type');
    }

    private function getPeakNotificationHours()
    {
        return Notification::selectRaw('HOUR(created_at) as hour, count(*) as count')
                          ->groupBy('hour')
                          ->orderBy('count', 'desc')
                          ->limit(3)
                          ->get();
    }
}
