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
    return view('login');
});

