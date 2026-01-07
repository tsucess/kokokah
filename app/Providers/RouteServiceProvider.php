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
        // Use implicit route model binding for ChatRoom
        Route::model('chatRoom', ChatRoom::class);

        // Use implicit route model binding for ChatMessage
        Route::model('message', ChatMessage::class);
    }
}
