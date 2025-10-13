<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and add security headers
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!config('kokokah.security.headers_enabled', true)) {
            return $response;
        }

        // Prevent XSS attacks
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');

        // Enforce HTTPS
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Content Security Policy
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
            "font-src 'self' https://fonts.gstatic.com",
            "img-src 'self' data: https:",
            "media-src 'self' https:",
            "connect-src 'self' https:",
            "frame-src 'self' https://www.youtube.com https://player.vimeo.com",
        ];
        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy (formerly Feature Policy)
        $permissions = [
            'camera=()',
            'microphone=()',
            'geolocation=()',
            'payment=(self)',
            'usb=()',
        ];
        $response->headers->set('Permissions-Policy', implode(', ', $permissions));

        return $response;
    }
}
