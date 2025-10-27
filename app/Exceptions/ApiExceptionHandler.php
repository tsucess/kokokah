<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class ApiExceptionHandler
{
    /**
     * Handle API exceptions and return consistent JSON responses
     */
    public static function handle(Throwable $e, Request $request): JsonResponse
    {
        // Authentication exceptions
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
                'error' => 'Unauthenticated',
                'code' => 'AUTH_REQUIRED'
            ], 401);
        }

        // Authorization exceptions
        if ($e instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied',
                'error' => 'Unauthorized',
                'code' => 'ACCESS_DENIED'
            ], 403);
        }

        // Validation exceptions
        if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'code' => 'VALIDATION_ERROR'
            ], 422);
        }

        // Model not found exceptions
        if ($e instanceof ModelNotFoundException) {
            $model = class_basename($e->getModel());
            return response()->json([
                'success' => false,
                'message' => "{$model} not found",
                'error' => 'Resource not found',
                'code' => 'RESOURCE_NOT_FOUND'
            ], 404);
        }

        // HTTP exceptions
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Endpoint not found',
                'error' => 'Not Found',
                'code' => 'ENDPOINT_NOT_FOUND'
            ], 404);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Method not allowed',
                'error' => 'Method Not Allowed',
                'code' => 'METHOD_NOT_ALLOWED'
            ], 405);
        }

        if ($e instanceof TooManyRequestsHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests',
                'error' => 'Rate limit exceeded',
                'code' => 'RATE_LIMIT_EXCEEDED'
            ], 429);
        }

        // Generic HTTP exceptions
        if ($e instanceof HttpException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'HTTP error occurred',
                'error' => 'HTTP Error',
                'code' => 'HTTP_ERROR'
            ], $e->getStatusCode());
        }

        // Database exceptions
        if (str_contains($e->getMessage(), 'SQLSTATE')) {
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred',
                'error' => app()->environment('production') ? 'Internal Server Error' : $e->getMessage(),
                'code' => 'DATABASE_ERROR'
            ], 500);
        }

        // Payment gateway exceptions
        if (str_contains(get_class($e), 'Payment') || str_contains($e->getMessage(), 'payment')) {
            return response()->json([
                'success' => false,
                'message' => 'Payment processing error',
                'error' => app()->environment('production') ? 'Payment Error' : $e->getMessage(),
                'code' => 'PAYMENT_ERROR'
            ], 400);
        }

        // File upload exceptions
        if (str_contains($e->getMessage(), 'file') || str_contains($e->getMessage(), 'upload')) {
            return response()->json([
                'success' => false,
                'message' => 'File upload error',
                'error' => app()->environment('production') ? 'Upload Error' : $e->getMessage(),
                'code' => 'UPLOAD_ERROR'
            ], 400);
        }

        // Generic server errors
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred',
            'error' => app()->environment('production') ? 'Internal Server Error' : $e->getMessage(),
            'code' => 'INTERNAL_ERROR',
            'trace' => app()->environment('production') ? null : $e->getTraceAsString()
        ], 500);
    }

    /**
     * Log exception details for debugging
     */
    public static function logException(Throwable $e, Request $request): void
    {
        $context = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'input' => $request->except(['password', 'password_confirmation']),
            'exception' => [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]
        ];

        logger()->error('API Exception: ' . $e->getMessage(), $context);
    }
}
