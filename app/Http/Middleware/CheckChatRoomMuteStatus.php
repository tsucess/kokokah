<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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

        // Admin users are never muted
        if (auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Get the chat room from route parameter
        $chatRoom = $request->route('chatRoom');

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

