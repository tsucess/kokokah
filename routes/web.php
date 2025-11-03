<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/koodies', function () {
    return view('koodies');
});

Route::get('/stem', function () {
    return view('stem');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/forgotpassword', function () {
    return view('auth.forgotpassword');
});

Route::get('/resetpassword', function () {
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

Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/market', function () {
    return view('market');
});

Route::get('/profiles', function () {
    return view('admin.profile');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/application', function () {
    return view('application');
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

Route::get('/enroll', function () {
    return view('users.enroll');
});

Route::get('/termsubject', function () {
    return view('users.termsubject');
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

Route::get('/subjectmedia', function () {
    return view('admin.subjectmedia');
});

Route::get('/curriculum', function () {
    return view('admin.curriculum');
});

Route::get('/createsubject', function () {
    return view('admin.createsubject');
});

Route::get('/userkoodies', function () {
    return view('users.userkoodies');
});

Route::get('/userkoodiesaudio', function () {
    return view('users.userkoodiesaudio');
});
