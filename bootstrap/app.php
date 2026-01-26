<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases for Laravel 12
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'rate.limit' => \App\Http\Middleware\RateLimitMiddleware::class,
            'security.headers' => \App\Http\Middleware\SecurityHeadersMiddleware::class,
            'ensure.user.authenticated.for.chat' => \App\Http\Middleware\EnsureUserIsAuthenticatedForChat::class,
            'authorize.chat.room.access' => \App\Http\Middleware\AuthorizeChatRoomAccess::class,
            'check.chat.room.mute.status' => \App\Http\Middleware\CheckChatRoomMuteStatus::class,
        ]);

        // Apply global middleware
        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);

        // Apply rate limiting to API routes
        $middleware->group('api', [
            'rate.limit:api,300', // 300 requests per minute (increased for testing)
        ]);

        // Apply SetLocale middleware to web routes (after auth)
        $middleware->appendToGroup('web', \App\Http\Middleware\SetLocale::class);

        // Configure authentication redirect for unauthenticated users
        $middleware->redirectGuestsTo('/login');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle API exceptions with consistent JSON responses
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return \App\Exceptions\ApiExceptionHandler::handle($e, $request);
            }
        });

        // Report exceptions to external services in production
        $exceptions->report(function (Throwable $e) {
            if (app()->environment('production')) {
                // Log to external services like Sentry, Bugsnag, etc.
                if (config('logging.channels.sentry')) {
                    app('sentry')->captureException($e);
                }
            }
        });
    })->create();
