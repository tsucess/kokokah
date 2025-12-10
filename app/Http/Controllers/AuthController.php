<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\VerificationCode;
use App\Notifications\VerificationCodeNotification;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|in:student,instructor,admin'
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'student',
        ]);

        // after $user = User::create([...]);
        $user->sendEmailVerificationNotification();

        $token = $user->createToken('api_token')->plainTextToken;
        

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        // Validate required fields only (not existence)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt authentication
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    // Current user
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }

    // Logout (revoke current token)
    public function logout(Request $request)
    {
        // $request->user()->currentAccessToken()->delete(); // this delete just the current token
        $request->user()->tokens()->delete(); // this deletes all token for the logged in user_id

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Send verification code to user's email
     */
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already verified'
                ], 400);
            }

            // Create verification code
            $verificationCode = VerificationCode::createForUser($user, 'email', 15);

            // Send notification
            $user->notify(new VerificationCodeNotification($verificationCode, 'email'));

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to your email',
                'data' => [
                    'expires_in_minutes' => 15,
                    'code_length' => 6
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify email using verification code
     */
    public function verifyEmailWithCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'code' => 'required|string|size:6'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already verified'
                ], 400);
            }

            // Verify the code
            $verification = VerificationCode::verify($user->id, $request->code, 'email');

            if (!$verification) {
                // Increment attempts for any active code
                $activeCode = VerificationCode::forUser($user->id)
                    ->byType('email')
                    ->active()
                    ->first();

                if ($activeCode) {
                    $activeCode->incrementAttempts();

                    if ($activeCode->hasExceededAttempts()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Too many failed attempts. Please request a new code.'
                        ], 429);
                    }
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired verification code'
                ], 400);
            }

            // Mark email as verified
            $user->markEmailAsVerified();

            // Mark code as used
            $verification->markAsUsed();

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully',
                'data' => [
                    'user' => $user,
                    'verified_at' => $user->email_verified_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resend verification code
     */
    public function resendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already verified'
                ], 400);
            }

            // Create new verification code
            $verificationCode = VerificationCode::createForUser($user, 'email', 15);

            // Send notification
            $user->notify(new VerificationCodeNotification($verificationCode, 'email'));

            return response()->json([
                'success' => true,
                'message' => 'New verification code sent to your email',
                'data' => [
                    'expires_in_minutes' => 15
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend verification code: ' . $e->getMessage()
            ], 500);
        }
    }
}
