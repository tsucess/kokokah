<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('index');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/lms', function () {
    return view('lms');
});

Route::get('/sms', function () {
    return view('sms');
});

Route::get('/pricing', function () {
    return view('pricing');
});

Route::get('/template', function () {
    return view('layouts.template');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/kokoplay', function () {
    return view('kokoplay');
});

Route::get('/stem', function () {
    return view('stem');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/forgotpassword', function () {
    return view('auth.forgotpassword');
});

Route::get('/reset-password', function () {
    return view('auth.resetpassword');
});

Route::get('/verify', function () {
    // return view('auth.verifypassword');
    return view('auth.verifypassword');
});

Route::get('/stemregister', function () {
    return view('auth.stemregister');
});

Route::get('/stemregister2', function () {
    return view('auth.stemregister2');
});

// Dashboard route - Check if user is logged in via localStorage token
// The frontend will handle the redirect if no token is found
Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/market', function () {
    return view('market');
});

Route::get('/test-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user(),
        'guard' => auth()->getDefaultDriver()
    ]);
});


// Profile routes - simple views that load profile data via API
Route::get('/adminprofile', function () {
    return view('admin.profile');
});

Route::get('/userprofile', function () {
    return view('users.profile');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/application', function () {
    return view('application');
});

Route::get('/categories', function () {
    return view('admin.categories');
});

Route::get('/report', function () {
    return view('admin.report');
});

// Admin-only routes for curriculum management
// Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/curriculum-categories', function () {
        return view('admin.curriculum-categories');
    });

    Route::get('/subject-categories', function () {
        return view('admin.subject-categories');
    });

    Route::get('/levels', function () {
        return view('admin.levels');
    });

    Route::get('/terms', function () {
        return view('admin.terms');
    });
// });

// Feedback route (Admin and Superadmin only)
Route::get('/feedback', function () {
    return view('admin.feedback');
});

Route::get('/instructor', function () {
    return view('admin.instructor');
});

Route::get('/student', function () {
    return view('admin.student');
});

Route::get('/subscription', function () {
    return view('admin.subscription');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/becometutor', function () {
    return view('becometutor');
});

// Route::get('/dashboard', function () {
//     return view('admin.dashboardtemp');
// });


Route::get('/usersdashboard', function () {
    return view('users.usersdashboard');
});

Route::get('/userclass', function () {
    return view('users.userclass');
});

Route::get('/usersubject', function () {
    return view('users.usersubject');
});

Route::get('/userresult', function () {
    return view('users.result');
});

Route::get('/userenroll', function () {
    return view('users.enroll');
});

Route::get('/userannouncement', function () {
    return view('users.userannouncement');
});

Route::get('/termsubject', function () {
    return view('users.termsubject');
});

Route::get('/userkudikah', function () {
    return view('users.kudikah');
});

Route::get('/userfeedback', function () {
    return view('users.userfeedback');
});

Route::get('/userkoodies', function () {
    return view('users.userkoodies');
});

Route::get('/userleaderboard', function () {
    return view('users.leaderboard');
});

// Route::middleware('auth')->group(function () {
    Route::get('/chatroom', function () {
        // Create a token for the user if they don't have one
        // $user = auth()->user();
        // if ($user && !$user->tokens()->exists()) {
        //     $token = $user->createToken('web_token')->plainTextToken;
        //     // Pass the token to the view
        //     return view('chat.chatroom', ['token' => $token]);
        // }
        return view('chat.chatroom');
    });
// });

Route::get('/userlessondetails', function () {
    return view('users.subjectdetails');
});

Route::get('/usersubscriptionhistory', function () {
    return view('users.subscriptionhistory');
});

Route::get('/subjectselect', function () {
    return view('admin.subjectselected');
});

Route::get('/subjectchart', function () {
    return view('admin.subjectchart');
});

Route::get('/wallet', function () {
    return view('admin.wallet');
});

Route::get('/announcement', function () {
    return view('admin.announcement');
});

Route::get('/users', function () {
    return view('admin.users');
});

Route::get('/adduser', function () {
    return view('admin.createuser');
});
Route::get('/edituser', function () {
    return view('admin.edituser');
});

Route::get('/useractivity', function () {
    return view('admin.useractivity');
});

Route::get('/subjects', function () {
    return view('admin.allsubjects');
});

Route::get('/categories', function () {
    return view('admin.categories');
});

Route::get('/students', function () {
    return view('admin.students');
});

Route::get('/instructors', function () {
    return view('admin.instructors');
});

// Rating routes
Route::get('/rating', function () {
    return view('admin.rating');
});

Route::get('/rating-details', function () {
    return view('admin.ratingdetails');
});

// Legacy route - redirect to new rating details
Route::get('/ratingdetails', function () {
    return redirect('/rating');
});

Route::get('/transactions', function () {
    return view('admin.transactions');
});

Route::get('/subjectmedia', function () {
    return view('admin.subjectmedia');
});

Route::get('/curriculum', function () {
    return view('admin.curriculum');
});

Route::get('/createsubject', function () {
    return view('admin.createsubject');
});

Route::get('/editsubject/{id?}', function ($id = null) {
    // Get ID from URL parameter or query string
    $courseId = $id ?? request()->query('id');
    return view('admin.editsubject', ['courseId' => $courseId]);
});

Route::get('/publish', function () {
    return view('admin.publish');
});

Route::get('/createannouncement', function () {
    return view('admin.createannouncement');
});

Route::get('/announcement/{id}/edit', function ($id) {
    return view('admin.editannouncement', ['announcementId' => $id]);
});

Route::get('/userkoodies', function () {
    return view('users.userkoodies');
});

Route::get('/userkoodiesaudio', function () {
    return view('users.userkoodiesaudio');
});
