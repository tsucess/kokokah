<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to ensure user is authenticated for chat operations.
 * 
 * This middleware:
 * - Checks if user is authenticated
 * - Verifies email is verified (optional)
 * - Checks if user account is active
 * - Prevents banned users from accessing chat
 */
class EnsureUserAuthenticatedForChat
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user is authenticated
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please log in to access chat.'
            ], 401);
        }

        // Check if user account is active
        if ($user->status === 'inactive' || $user->is_banned) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive or banned. You cannot access chat.'
            ], 403);
        }

        // Optional: Check if email is verified
        // Uncomment if you want to require email verification for chat
        // if (!$user->hasVerifiedEmail()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Please verify your email to access chat.'
        //     ], 403);
        // }

        return $next($request);
    }
}

