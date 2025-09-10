<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/', function() {
    return 'API';
});



Route::apiResource('category', CategoryController::class);











Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/forgot-password', [PasswordResetController::class,'sendResetLinkEmail']);
Route::post('/reset-password', [PasswordResetController::class,'reset']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class,'user']);
    Route::post('/logout', [AuthController::class,'logout']);
    // protected routes here...
});

Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin/dashboard', function () {
    return response()->json(['ok' => true]);
});


// Admin-only route
Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin/reports', function () {
    return "Admin Reports";
});

// Instructor-only route
Route::middleware(['auth:sanctum', 'role:instructor'])->get('/instructor/courses', function () {
    return "Instructor Courses";
});





