# Chat System - Routes & Real-time Configuration

## 1. API Routes (routes/api.php)

```php
Route::middleware('auth:sanctum')->group(function () {
    // Chatroom Routes
    Route::apiResource('chatrooms', ChatroomController::class);
    
    Route::post('chatrooms/{chatroom}/members', [ChatroomController::class, 'addMember']);
    Route::delete('chatrooms/{chatroom}/members/{user}', [ChatroomController::class, 'removeMember']);
    
    // Message Routes
    Route::post('chatrooms/{chatroom}/messages', [MessageController::class, 'store']);
    Route::put('messages/{message}', [MessageController::class, 'update']);
    Route::delete('messages/{message}', [MessageController::class, 'destroy']);
    
    // Reaction Routes
    Route::post('messages/{message}/reactions', [MessageController::class, 'addReaction']);
    Route::delete('messages/{message}/reactions/{reaction}', [MessageController::class, 'removeReaction']);
    
    // Typing Indicator
    Route::post('chatrooms/{chatroom}/typing', [TypingIndicatorController::class, 'store']);
});
```

## 2. Web Routes (routes/web.php)

```php
Route::middleware('auth')->group(function () {
    // Chatroom Views
    Route::get('/chatrooms', [ChatroomController::class, 'index'])->name('chatrooms.index');
    Route::get('/chatrooms/{chatroom}', [ChatroomController::class, 'show'])->name('chatrooms.show');
    Route::get('/chatrooms/{chatroom}/edit', [ChatroomController::class, 'edit'])->name('chatrooms.edit');
    Route::post('/chatrooms', [ChatroomController::class, 'store'])->name('chatrooms.store');
    Route::put('/chatrooms/{chatroom}', [ChatroomController::class, 'update'])->name('chatrooms.update');
});
```

## 3. Broadcasting Configuration (config/broadcasting.php)

### Option A: Pusher (Production)
```php
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'useTLS' => true,
    ],
],
```

### Option B: Soketi (Self-hosted)
```php
'soketi' => [
    'driver' => 'pusher',
    'key' => env('SOKETI_APP_KEY', 'app-key'),
    'secret' => env('SOKETI_APP_SECRET', 'app-secret'),
    'app_id' => env('SOKETI_APP_ID', '1'),
    'options' => [
        'host' => env('SOKETI_HOST', 'localhost'),
        'port' => env('SOKETI_PORT', 6001),
        'scheme' => env('SOKETI_SCHEME', 'http'),
        'curl_options' => [
            CURLOPT_SSL_VERIFYPEER => false,
        ],
    ],
],
```

## 4. Environment Variables (.env)

```env
# Broadcasting
BROADCAST_DRIVER=pusher
QUEUE_CONNECTION=redis

# Pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1

# Or Soketi
SOKETI_HOST=localhost
SOKETI_PORT=6001
SOKETI_APP_KEY=app-key
SOKETI_APP_SECRET=app-secret
SOKETI_APP_ID=1
```

## 5. Laravel Echo Setup (resources/js/echo.js)

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
});

// Or for Soketi:
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_SOKETI_APP_KEY,
//     wsHost: import.meta.env.VITE_SOKETI_HOST,
//     wsPort: import.meta.env.VITE_SOKETI_PORT,
//     wssPort: import.meta.env.VITE_SOKETI_PORT,
//     forceTLS: false,
//     encrypted: false,
//     disableStats: true,
// });
```

## 6. Vite Configuration (vite.config.js)

```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/echo.js',
            ],
            refresh: true,
        }),
    ],
});
```

## 7. Polling Fallback (JavaScript)

```javascript
// For browsers without WebSocket support
class ChatPoller {
    constructor(chatroomId, interval = 3000) {
        this.chatroomId = chatroomId;
        this.interval = interval;
        this.lastMessageId = 0;
    }

    start() {
        setInterval(() => this.poll(), this.interval);
    }

    async poll() {
        try {
            const response = await fetch(
                `/api/chatrooms/${this.chatroomId}/messages?since=${this.lastMessageId}`
            );
            const data = await response.json();
            
            if (data.data.length > 0) {
                this.onMessagesReceived(data.data);
                this.lastMessageId = data.data[data.data.length - 1].id;
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }

    onMessagesReceived(messages) {
        // Dispatch custom event
        window.dispatchEvent(
            new CustomEvent('messages-received', { detail: messages })
        );
    }
}

// Usage
const poller = new ChatPoller(chatroomId);
poller.start();
```

## 8. Typing Indicator Event

```php
<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserTyping implements ShouldBroadcast
{
    public function __construct(
        public $chatroomId,
        public $userId,
        public $userName
    ) {}

    public function broadcastOn()
    {
        return new Channel('chatroom.' . $this->chatroomId);
    }

    public function broadcastAs()
    {
        return 'user.typing';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->userId,
            'user_name' => $this->userName,
        ];
    }
}
```

## 9. Typing Indicator Controller

```php
<?php
namespace App\Http\Controllers;

use App\Events\UserTyping;
use App\Models\Chatroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypingIndicatorController extends Controller
{
    public function store(Request $request, Chatroom $chatroom)
    {
        $user = Auth::user();
        
        broadcast(new UserTyping(
            $chatroom->id,
            $user->id,
            $user->first_name . ' ' . $user->last_name
        ))->toOthers();

        return response()->json(['success' => true]);
    }
}
```


