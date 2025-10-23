<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::get('/temp', function () {
//     return view('layouts.template');
// });

Route::get('/', function () {
    // return response()->json(['message' => 'Kokokah.com LMS API', 'status' => 'active']);
    return view('index');
});

// Login route for authentication redirects (API-only app)
// Route::get('/login', function () {
//     return response()->json([
//         'success' => false,
//         'message' => 'This is an API-only application. Please use the API endpoints for authentication.',
//         'endpoints' => [
//             'login' => 'POST /api/login',
//             'register' => 'POST /api/register'
//         ]
//     ], 401);
// })->name('login');

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

Route::get('/koodies', function () {
    return view('koodies');
});

Route::get('/stem', function () {
    return view('stem');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/signup2', function () {
    return view('signup2');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/market', function () {
    return view('market');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/application', function () {
    return view('application');
});

Route::get('/adsignup', function () {
    return view('admin.adminsignup');
});

Route::get('/adlogin', function () {
    return view('admin.adminlogin');
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

Route::get('/enroll', function () {
    return view('users.enroll');
});

Route::get('/termsubject', function () {
    return view('admin.termsubject');
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

Route::get('/chatroom', function () {
    return view('admin.chatroom');
});

Route::get('/announcement', function () {
    return view('admin.announcement');
});

Route::get('/feedback', function () {
    return view('admin.feedback');
});


Route::get('/users', function () {
    return view('admin.users');
});

Route::get('/aduser', function () {
    return view('admin.aduser');
});

Route::get('/activity', function () {
    return view('users.useractivity');
});

Route::get('/allcourses', function () {
    return view('admin.allcourses');
});


Route::get('/coursemedia', function () {
    return view('admin.coursemedia');
});

Route::get('/createcourse', function () {
    return view('admin.createcourse');
});

Route::get('/userkoodies', function () {
    return view('users.userkoodies');
});

Route::get('/userkoodiesaudio', function () {
    return view('users.userkoodiesaudio');
});
