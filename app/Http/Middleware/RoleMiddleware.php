<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// class RoleMiddleware
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
//      */
//     public function handle($request, Closure $next, $role)
//     {
//         if (!$request->user() || $request->user()->role !== $role) {
//             return response()->json(['message' => 'Forbidden'], 403);
//         }
//         return $next($request);
//     }
// }



// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Check if the user has any of the required roles
        if (!in_array($user->role, $roles)) {
            return response()->json(['message' => 'Forbidden. Required role: ' . implode(' or ', $roles)], 403);
        }

        return $next($request);
    }
}
