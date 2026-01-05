<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ChatRoom;

class AuthorizeChatRoomAccess
{
    /**
     * Handle an incoming request.
     *
     * Ensures that user has access to the requested chat room.
     * Access rules:
     * - General chatrooms: All authenticated users
     * - Course chatrooms: Only users enrolled in the course or instructors
     * - Admin: Can access all rooms
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the chat room parameter from route
        $chatRoomParam = $request->route('chatRoom');

        if (!$chatRoomParam) {
            return response()->json([
                'success' => false,
                'message' => 'Chat room not found.',
                'error' => 'not_found'
            ], 404);
        }

        // Handle both string ID and ChatRoom model object
        if ($chatRoomParam instanceof ChatRoom) {
            $chatRoom = $chatRoomParam;
        } else {
            // Fetch the ChatRoom model if we got an ID
            $chatRoom = ChatRoom::find($chatRoomParam);
        }

        if (!$chatRoom) {
            return response()->json([
                'success' => false,
                'message' => 'Chat room not found.',
                'error' => 'not_found'
            ], 404);
        }

        $user = auth()->user();

        // Admin can access all rooms
        if ($user->role === 'admin') {
            return $next($request);
        }

        // General chatrooms are accessible to all authenticated users
        if ($chatRoom->type === 'general') {
            return $next($request);
        }

        // For course-specific chatrooms, check enrollment
        if ($chatRoom->type === 'course' && $chatRoom->course_id) {
            // Check if user is the instructor
            if ($chatRoom->course && $chatRoom->course->instructor_id === $user->id) {
                return $next($request);
            }

            // Check if user is enrolled in the course with active status
            if ($chatRoom->course && $chatRoom->course->enrollments()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->exists()) {
                return $next($request);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'You do not have access to this chat room.',
            'error' => 'unauthorized'
        ], 403);
    }
}

