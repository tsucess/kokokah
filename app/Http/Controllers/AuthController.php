<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|string|min:8|confirmed',
            'role'=>'nullable|in:student,instructor,admin'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>$request->role ?? 'student',
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['user'=>$user, 'token'=>$token], 201);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate(['email'=>'required|email|exists:users','password'=>'required']);

        if (!Auth::attempt($request->only('email','password'))) {
            return response()->json(['message'=>'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['user'=>$user, 'token'=>$token]);
    }

    // Current user
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // Logout (revoke current token)
    public function logout(Request $request)
    {
        // $request->user()->currentAccessToken()->delete(); // this delete just the current token
        $request->user()->tokens()->delete(); // this deletes all token for the logged in user_id

        return response()->json(['message'=>'Logged out']);
    }
}
