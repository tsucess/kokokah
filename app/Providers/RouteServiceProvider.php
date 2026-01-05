<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\ChatRoom;
use App\Models\ChatMessage;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Define route model bindings using bind() for implicit binding
        Route::bind('chatRoom', function ($value) {
            return ChatRoom::findOrFail($value);
        });

        Route::bind('message', function ($value) {
            return ChatMessage::findOrFail($value);
        });
    }
}
