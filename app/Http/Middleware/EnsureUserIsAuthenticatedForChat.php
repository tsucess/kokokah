<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthenticatedForChat
{
    /**
     * Handle an incoming request.
     * 
     * Ensures that only authenticated users can access chat endpoints.
     * Returns 401 Unauthorized if user is not authenticated.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please log in to access chat.',
                'error' => 'unauthenticated'
            ], 401);
        }

        // Check if user account is active
        $user = auth()->user();
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact support.',
                'error' => 'account_inactive'
            ], 403);
        }

        return $next($request);
    }
}

