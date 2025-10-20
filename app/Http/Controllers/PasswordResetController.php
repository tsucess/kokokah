<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    // Send reset link
    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate(['email'=>'required|email']);

            $status = Password::sendResetLink($request->only('email'));

            return $status === Password::RESET_LINK_SENT
                ? response()->json(['success' => true, 'message'=>'Password reset link sent to your email'])
                : response()->json(['success' => false, 'message'=>'Unable to send reset link'], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send password reset link: ' . $e->getMessage()
            ], 500);
        }
    }

    // Reset password
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message'=>__('passwords.reset')])
            : response()->json(['message'=>__($status)], 422);
    }
}
