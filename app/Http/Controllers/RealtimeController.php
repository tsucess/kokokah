<?php

namespace App\Http\Controllers;

use App\Services\RealtimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealtimeController extends Controller
{
    protected $realtimeService;

    public function __construct(RealtimeService $realtimeService)
    {
        $this->realtimeService = $realtimeService;
    }

    /**
     * Mark user as online
     */
    public function markUserOnline(Request $request)
    {
        try {
            $user = Auth::user();
            $courseId = $request->query('course_id');
            $lessonId = $request->query('lesson_id');

            $this->realtimeService->updateUserActivity($user->id, $courseId, $lessonId);
            $this->realtimeService->broadcastUserOnline($user, $courseId);

            return response()->json([
                'success' => true,
                'message' => 'User marked as online'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark user online: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark user as offline
     */
    public function markUserOffline()
    {
        try {
            $user = Auth::user();
            $this->realtimeService->broadcastUserOffline($user->id);

            return response()->json([
                'success' => true,
                'message' => 'User marked as offline'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark user offline: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get online users
     */
    public function getOnlineUsers()
    {
        try {
            $onlineUsers = $this->realtimeService->getOnlineUsers();

            return response()->json([
                'success' => true,
                'data' => $onlineUsers,
                'count' => count($onlineUsers)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch online users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get online users in course
     */
    public function getOnlineUsersInCourse($courseId)
    {
        try {
            $onlineUsers = $this->realtimeService->getOnlineUsersInCourse($courseId);

            return response()->json([
                'success' => true,
                'data' => $onlineUsers,
                'count' => count($onlineUsers)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch online users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get online count
     */
    public function getOnlineCount()
    {
        try {
            $count = $this->realtimeService->getOnlineCount();

            return response()->json([
                'success' => true,
                'data' => ['count' => $count]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch online count: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get online count in course
     */
    public function getOnlineCountInCourse($courseId)
    {
        try {
            $count = $this->realtimeService->getOnlineCountInCourse($courseId);

            return response()->json([
                'success' => true,
                'data' => ['count' => $count]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch online count: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send typing indicator
     */
    public function sendTypingIndicator(Request $request)
    {
        try {
            $validated = $request->validate([
                'chat_session_id' => 'required|integer',
                'is_typing' => 'required|boolean'
            ]);

            $user = Auth::user();

            $this->realtimeService->broadcastTypingIndicator(
                $user->id,
                $user->name,
                $validated['chat_session_id'],
                $validated['is_typing']
            );

            return response()->json([
                'success' => true,
                'message' => 'Typing indicator sent'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send typing indicator: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user activity status
     */
    public function getUserActivityStatus($userId)
    {
        try {
            $status = $this->realtimeService->getUserActivityStatus($userId);

            return response()->json([
                'success' => true,
                'data' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user activity: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current user activity status
     */
    public function getCurrentUserActivityStatus()
    {
        try {
            $user = Auth::user();
            $status = $this->realtimeService->getUserActivityStatus($user->id);

            return response()->json([
                'success' => true,
                'data' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user activity: ' . $e->getMessage()
            ], 500);
        }
    }
}

