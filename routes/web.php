<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Kokokah.com LMS API', 'status' => 'active']);
});
