# Real-time Chat - Broadcasting Events Guide

## 📡 Broadcasting Events

### 1. MessageSent Event

**File:** `app/Events/MessageSent.php` (Already created)

**Triggered When:** A new message is sent to a chat room

**Channel:** `private-chatroom.{id}`

**Event Name:** `message.sent`

**Data Broadcast:**
```json
{
    "id": 1,
    "chat_room_id": 5,
    "user_id": 10,
    "user": {
        "id": 10,
        "first_name": "John",
        "last_name": "Doe",
        "profile_photo": "https://..."
    },
    "content": "Hello everyone!",
    "type": "text",
    "reply_to_id": null,
    "edited_content": null,
    "edited_at": null,
    "is_deleted": false,
    "is_pinned": false,
    "reaction_count": 0,
    "created_at": "2025-12-31T10:30:00Z",
    "updated_at": "2025-12-31T10:30:00Z"
}
```

**JavaScript Listener:**
```javascript
Echo.private(`chatroom.5`)
    .listen('message.sent', (event) => {
        console.log('New message:', event);
        addMessageToUI(event);
    });
```

**Or using RealtimeChat module:**
```javascript
const chat = new RealtimeChat(5);
chat.onMessageSent((event) => {
    addMessageToUI(event.data);
});
```

---

### 2. MessageUpdated Event

**File:** `app/Events/MessageUpdated.php` (Create if needed)

**Triggered When:** A message is edited

**Channel:** `private-chatroom.{id}`

**Event Name:** `message.updated`

**Data Broadcast:**
```json
{
    "id": 1,
    "chat_room_id": 5,
    "user_id": 10,
    "content": "Original content",
    "edited_content": "Updated content",
    "edited_at": "2025-12-31T10:35:00Z",
    "is_edited": true,
    "updated_at": "2025-12-31T10:35:00Z"
}
```

**JavaScript Listener:**
```javascript
Echo.private(`chatroom.5`)
    .listen('message.updated', (event) => {
        console.log('Message updated:', event);
        updateMessageInUI(event);
    });
```

---

### 3. MessageDeleted Event

**File:** `app/Events/MessageDeleted.php` (Create if needed)

**Triggered When:** A message is deleted

**Channel:** `private-chatroom.{id}`

**Event Name:** `message.deleted`

**Data Broadcast:**
```json
{
    "id": 1,
    "chat_room_id": 5,
    "user_id": 10,
    "is_deleted": true,
    "deleted_at": "2025-12-31T10:40:00Z"
}
```

**JavaScript Listener:**
```javascript
Echo.private(`chatroom.5`)
    .listen('message.deleted', (event) => {
        console.log('Message deleted:', event);
        removeMessageFromUI(event.id);
    });
```

---

### 4. UserTyping Event

**File:** `app/Events/UserTyping.php` (Create if needed)

**Triggered When:** A user is typing a message

**Channel:** `chatroom.{id}` (Public channel)

**Event Name:** `user.typing`

**Data Broadcast:**
```json
{
    "user_id": 10,
    "user_name": "John Doe",
    "chat_room_id": 5,
    "timestamp": "2025-12-31T10:45:00Z"
}
```

**JavaScript Listener:**
```javascript
Echo.channel(`chatroom.5`)
    .listen('user.typing', (event) => {
        console.log('User typing:', event.user_name);
        showTypingIndicator(event.user_name);
    });
```

---

### 5. ReactionAdded Event

**File:** `app/Events/ReactionAdded.php` (Create if needed)

**Triggered When:** A user adds a reaction to a message

**Channel:** `private-chatroom.{id}`

**Event Name:** `reaction.added`

**Data Broadcast:**
```json
{
    "message_id": 1,
    "user_id": 10,
    "emoji": "👍",
    "reaction_count": 2,
    "users": [10, 11]
}
```

**JavaScript Listener:**
```javascript
Echo.private(`chatroom.5`)
    .listen('reaction.added', (event) => {
        console.log('Reaction added:', event);
        addReactionToUI(event);
    });
```

---

### 6. ReactionRemoved Event

**File:** `app/Events/ReactionRemoved.php` (Create if needed)

**Triggered When:** A user removes a reaction from a message

**Channel:** `private-chatroom.{id}`

**Event Name:** `reaction.removed`

**Data Broadcast:**
```json
{
    "message_id": 1,
    "user_id": 10,
    "emoji": "👍",
    "reaction_count": 1,
    "users": [11]
}
```

**JavaScript Listener:**
```javascript
Echo.private(`chatroom.5`)
    .listen('reaction.removed', (event) => {
        console.log('Reaction removed:', event);
        removeReactionFromUI(event);
    });
```

---

## 🔐 Channel Authorization

### Private Channels
Only authenticated users who are members of the chat room can access:

```php
// In routes/channels.php
Broadcast::channel('private-chatroom.{chatRoomId}', function ($user, $chatRoomId) {
    return $user->chatRooms()->where('chat_room_id', $chatRoomId)->exists();
});
```

### Public Channels
All users can access:

```php
// In routes/channels.php
Broadcast::channel('chatroom.{chatRoomId}', function ($user, $chatRoomId) {
    return true;
});
```

---

## 📝 Creating Custom Events

### Example: MessageSent Event

```php
<?php

namespace App\Events;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $chatRoom;

    public function __construct(ChatMessage $message, ChatRoom $chatRoom)
    {
        $this->message = $message;
        $this->chatRoom = $chatRoom;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chatroom.' . $this->chatRoom->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'chat_room_id' => $this->message->chat_room_id,
            'user_id' => $this->message->user_id,
            'user' => [
                'id' => $this->message->user->id,
                'first_name' => $this->message->user->first_name,
                'last_name' => $this->message->user->last_name,
                'profile_photo' => $this->message->user->profile_photo,
            ],
            'content' => $this->message->content,
            'type' => $this->message->type,
            'created_at' => $this->message->created_at,
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
```

---

## 🔄 Broadcasting Flow

```
User sends message
    ↓
API endpoint receives request
    ↓
Message saved to database
    ↓
MessageSent event dispatched
    ↓
Event queued for broadcasting
    ↓
Queue worker processes event
    ↓
Event broadcast to WebSocket server
    ↓
WebSocket server sends to all connected clients
    ↓
JavaScript listener receives event
    ↓
UI updated in real-time
```

---

## 📊 Event Frequency

| Event | Frequency | Channel |
|-------|-----------|---------|
| MessageSent | Per message | Private |
| MessageUpdated | Per edit | Private |
| MessageDeleted | Per delete | Private |
| UserTyping | Per keystroke | Public |
| ReactionAdded | Per reaction | Private |
| ReactionRemoved | Per removal | Private |

---

## 🎯 Best Practices

1. **Use Private Channels** for sensitive data (messages, reactions)
2. **Use Public Channels** for non-sensitive data (typing indicators)
3. **Throttle Typing Events** to avoid excessive broadcasting
4. **Serialize Data** properly before broadcasting
5. **Handle Errors** gracefully in JavaScript listeners
6. **Test Broadcasting** in development before production

---

## 🐛 Debugging

### Enable Debug Mode
```javascript
const chat = new RealtimeChat(chatRoomId, { debug: true });
```

### Check Broadcasting
```bash
# In Laravel Tinker
php artisan tinker
> broadcast(new App\Events\MessageSent($message, $chatRoom));
```

### Monitor WebSocket
Open browser DevTools → Network → WS tab to see WebSocket messages.

---

## 📚 Related Documentation

- [Laravel Broadcasting](https://laravel.com/docs/broadcasting)
- [Laravel Events](https://laravel.com/docs/events)
- [Pusher Documentation](https://pusher.com/docs)
- [Laravel Echo](https://laravel.com/docs/broadcasting#client-side-installation)


