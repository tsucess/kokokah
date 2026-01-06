<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ChatRoom;

class CheckChatRoomMuteStatus
{
    /**
     * Handle an incoming request.
     *
     * Checks if user is muted in the chat room.
     * Only applies to POST requests (sending messages).
     * Admin users are never muted.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for POST requests (sending messages)
        if ($request->method() !== 'POST') {
            return $next($request);
        }

        // Admin and superadmin users are never muted
        if (in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            return $next($request);
        }

        // Get the chat room parameter from route
        $chatRoomParam = $request->route('chatRoom');

        if (!$chatRoomParam) {
            return $next($request);
        }

        // Handle both string ID and ChatRoom model object
        if ($chatRoomParam instanceof ChatRoom) {
            $chatRoom = $chatRoomParam;
        } else {
            // Fetch the ChatRoom model if we got an ID
            $chatRoom = ChatRoom::find($chatRoomParam);
        }

        if (!$chatRoom) {
            return $next($request);
        }

        // Check if user is muted in the room
        $isMuted = $chatRoom->users()
            ->where('user_id', auth()->id())
            ->where('is_muted', true)
            ->exists();

        if ($isMuted) {
            return response()->json([
                'success' => false,
                'message' => 'You are muted in this chat room and cannot send messages.',
                'error' => 'user_muted'
            ], 403);
        }

        return $next($request);
    }
}

