# ChatMessageController - Implementation Guide

## 📦 What Was Created

### 1. **ChatMessageController** (`app/Http/Controllers/ChatMessageController.php`)
Main controller handling all message operations:
- `index()` - Fetch messages with pagination
- `store()` - Send new message
- `show()` - Get specific message
- `update()` - Edit message
- `destroy()` - Delete message

### 2. **MessageSent Event** (`app/Events/MessageSent.php`)
Broadcasting event for real-time updates:
- Broadcasts to private channel `chatroom.{id}`
- Sends message data to all room members
- Implements `ShouldBroadcast` interface

### 3. **ChatMessagePolicy** (`app/Policies/ChatMessagePolicy.php`)
Authorization rules:
- `view()` - Check room membership
- `create()` - Check membership and mute status
- `update()` - Check message ownership
- `delete()` - Check ownership or moderator status
- `react()` - Check room membership
- `pin()` - Check moderator status

### 4. **Request Classes**
- `StoreChatMessageRequest.php` - Validation for creating messages
- `UpdateChatMessageRequest.php` - Validation for updating messages

### 5. **ChatMessageResource** (`app/Http/Resources/ChatMessageResource.php`)
JSON resource for consistent API responses with:
- User information
- Reply context
- Reactions grouped by emoji
- Edit tracking

### 6. **Routes** (Updated `routes/api.php`)
```php
Route::prefix('chatrooms/{chatRoom}/messages')->group(function () {
    Route::get('/', [ChatMessageController::class, 'index']);
    Route::post('/', [ChatMessageController::class, 'store']);
    Route::get('/{message}', [ChatMessageController::class, 'show']);
    Route::put('/{message}', [ChatMessageController::class, 'update']);
    Route::delete('/{message}', [ChatMessageController::class, 'destroy']);
});
```

---

## 🔧 Setup Instructions

### Step 1: Verify Models Have Relationships

**ChatRoom Model** should have:
```php
public function messages(): HasMany
{
    return $this->hasMany(ChatMessage::class);
}

public function users(): BelongsToMany
{
    return $this->belongsToMany(User::class, 'chat_room_users')
                ->withPivot('role', 'is_active', 'is_muted', ...);
}
```

**ChatMessage Model** should have:
```php
public function chatRoom(): BelongsTo
{
    return $this->belongsTo(ChatRoom::class);
}

public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

public function replyTo(): BelongsTo
{
    return $this->belongsTo(ChatMessage::class, 'reply_to_id');
}

public function reactions(): HasMany
{
    return $this->hasMany(MessageReaction::class);
}
```

### Step 2: Configure Broadcasting

Update `config/broadcasting.php`:
```php
'default' => env('BROADCAST_DRIVER', 'pusher'),

'connections' => [
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
],
```

### Step 3: Register Policy (if using authorization)

In `app/Providers/AuthServiceProvider.php`:
```php
use App\Models\ChatMessage;
use App\Policies\ChatMessagePolicy;

protected $policies = [
    ChatMessage::class => ChatMessagePolicy::class,
];
```

### Step 4: Configure Queue (for broadcasting)

Update `.env`:
```
QUEUE_CONNECTION=database
BROADCAST_DRIVER=pusher
```

Run migrations:
```bash
php artisan queue:table
php artisan migrate
```

### Step 5: Test the Implementation

Run tests:
```bash
php artisan test tests/Feature/ChatMessageControllerTest.php
```

---

## 🚀 Usage Examples

### Frontend - Fetch Messages
```javascript
// Fetch messages with pagination
const response = await fetch(
    '/api/chatrooms/5/messages?per_page=50&page=1',
    {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    }
);
const data = await response.json();
console.log(data.data); // Array of messages
console.log(data.pagination); // Pagination info
```

### Frontend - Send Message
```javascript
const response = await fetch('/api/chatrooms/5/messages', {
    method: 'POST',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        content: 'Hello everyone!',
        type: 'text'
    })
});
const message = await response.json();
console.log(message.data); // New message
```

### Frontend - Real-time Updates
```javascript
// Listen for new messages
Echo.private(`chatroom.5`)
    .listen('message.sent', (event) => {
        console.log('New message:', event);
        // Update UI with new message
        addMessageToUI(event);
    });

// Listen for message edits
Echo.private(`chatroom.5`)
    .listen('message.updated', (event) => {
        console.log('Message updated:', event);
        updateMessageInUI(event);
    });

// Listen for message deletes
Echo.private(`chatroom.5`)
    .listen('message.deleted', (event) => {
        console.log('Message deleted:', event);
        removeMessageFromUI(event);
    });
```

### Frontend - Edit Message
```javascript
const response = await fetch('/api/chatrooms/5/messages/151', {
    method: 'PUT',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        content: 'Updated message'
    })
});
const updated = await response.json();
console.log(updated.data);
```

---

## 🔐 Authorization Flow

```
User Request
    ↓
Authentication Check (Bearer Token)
    ↓
Route Authorization (auth:sanctum)
    ↓
Controller Authorization:
    - isRoomMember() - Check if user is in chat room
    - isUserMuted() - Check if user is muted
    - Message ownership check (for edit/delete)
    ↓
Operation Executed
    ↓
Response Sent + Broadcast Event
```

---

## 📊 Database Queries

### Fetch Messages
```sql
SELECT * FROM chat_messages
WHERE chat_room_id = ? AND is_deleted = 0
ORDER BY created_at DESC
LIMIT 50 OFFSET 0;
```

### Send Message
```sql
INSERT INTO chat_messages (chat_room_id, user_id, content, type, created_at, updated_at)
VALUES (?, ?, ?, ?, NOW(), NOW());
```

### Update Message
```sql
UPDATE chat_messages
SET edited_content = ?, edited_at = NOW(), updated_at = NOW()
WHERE id = ? AND user_id = ?;
```

---

## 🎯 Performance Tips

1. **Use Pagination** - Always paginate large message lists
2. **Eager Load Relations** - Relations are eager-loaded in controller
3. **Index Database** - Ensure indexes on `chat_room_id`, `user_id`, `created_at`
4. **Cache Frequently Accessed** - Cache popular chat rooms
5. **Queue Broadcasting** - Use queue for broadcasting to prevent blocking

---

## 🧪 Testing

Run all tests:
```bash
php artisan test tests/Feature/ChatMessageControllerTest.php
```

Run specific test:
```bash
php artisan test tests/Feature/ChatMessageControllerTest.php --filter=test_send_message
```

---

## 🐛 Troubleshooting

### Messages not broadcasting?
- Check `BROADCAST_DRIVER` in `.env`
- Verify Pusher credentials
- Check browser console for errors

### Authorization errors?
- Verify user is member of chat room
- Check `chat_room_users` table
- Verify `is_active` is true

### Validation errors?
- Check request body format
- Verify content is not empty
- Check content length (max 5000)

---

## 📚 Related Files

- `app/Models/ChatMessage.php` - Message model
- `app/Models/ChatRoom.php` - Chat room model
- `app/Models/User.php` - User model
- `routes/api.php` - API routes
- `config/broadcasting.php` - Broadcasting config

---

## ✅ Checklist

- [ ] Models have correct relationships
- [ ] Broadcasting configured
- [ ] Policy registered (if using)
- [ ] Routes added to `routes/api.php`
- [ ] Tests passing
- [ ] Frontend integrated
- [ ] Real-time updates working
- [ ] Authorization working


