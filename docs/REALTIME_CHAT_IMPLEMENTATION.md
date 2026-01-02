# Real-time Chat - Complete Implementation Guide

## 📁 Files Created

### Configuration
- `config/broadcasting.php` - Broadcasting configuration
- `resources/js/echo.js` - Laravel Echo setup

### Frontend
- `resources/js/modules/realtime-chat.js` - Real-time chat module
- `resources/views/chat/realtime-chat.blade.php` - Chat interface

### Documentation
- `docs/REALTIME_CHAT_ENV_SETUP.md` - Environment setup
- `docs/REALTIME_CHAT_IMPLEMENTATION.md` - This file

---

## 🚀 Quick Start (5 minutes)

### 1. Install Dependencies
```bash
npm install pusher-js laravel-echo
```

### 2. Configure Broadcasting
Update `.env`:
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_id
PUSHER_APP_KEY=your_key
PUSHER_APP_SECRET=your_secret
PUSHER_APP_CLUSTER=mt1
VITE_PUSHER_APP_KEY=your_key
VITE_PUSHER_APP_CLUSTER=mt1
```

### 3. Build Frontend
```bash
npm run build
```

### 4. Start Queue Worker
```bash
php artisan queue:work
```

### 5. Access Chat
Navigate to `/chat/rooms/{id}` in your browser.

---

## 🔌 API Integration

### Send Message
```javascript
const response = await fetch(`/api/chatrooms/${chatRoomId}/messages`, {
    method: 'POST',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        content: 'Hello!',
        type: 'text'
    })
});
```

### Fetch Messages
```javascript
const response = await fetch(`/api/chatrooms/${chatRoomId}/messages`, {
    headers: {
        'Authorization': `Bearer ${token}`,
    }
});
const data = await response.json();
```

---

## 🔄 Real-time Events

### MessageSent
Triggered when a new message is sent.

**Event Data:**
```json
{
    "id": 1,
    "chat_room_id": 5,
    "user_id": 10,
    "user": {
        "id": 10,
        "first_name": "John",
        "last_name": "Doe"
    },
    "content": "Hello!",
    "type": "text",
    "created_at": "2025-12-31T10:30:00Z"
}
```

**Listen:**
```javascript
chat.onMessageSent((event) => {
    console.log('New message:', event.data);
    addMessageToUI(event.data);
});
```

### MessageUpdated
Triggered when a message is edited.

**Listen:**
```javascript
chat.onMessageUpdated((event) => {
    console.log('Message updated:', event.data);
    updateMessageInUI(event.data);
});
```

### MessageDeleted
Triggered when a message is deleted.

**Listen:**
```javascript
chat.onMessageDeleted((event) => {
    console.log('Message deleted:', event.data);
    removeMessageFromUI(event.data.id);
});
```

### UserTyping
Triggered when a user is typing.

**Listen:**
```javascript
chat.onUserTyping((event) => {
    console.log('User typing:', event.user_name);
    showTypingIndicator(event.user_name);
});
```

### ReactionAdded
Triggered when a reaction is added to a message.

**Listen:**
```javascript
chat.onReactionAdded((event) => {
    console.log('Reaction added:', event.data);
    addReactionToUI(event.data);
});
```

---

## 📝 Usage Examples

### Initialize Real-time Chat
```javascript
import RealtimeChat from '/resources/js/modules/realtime-chat.js';

const chat = new RealtimeChat(chatRoomId, { debug: true });
```

### Listen for Multiple Events
```javascript
chat
    .onMessageSent((event) => {
        addMessageToUI(event.data);
    })
    .onMessageUpdated((event) => {
        updateMessageInUI(event.data);
    })
    .onMessageDeleted((event) => {
        removeMessageFromUI(event.data.id);
    })
    .onUserTyping((event) => {
        showTypingIndicator(event.user_name);
    });
```

### Broadcast Typing Indicator
```javascript
document.getElementById('messageInput').addEventListener('input', () => {
    chat.broadcastTyping(userId, userName);
});
```

### Disconnect from Channel
```javascript
chat.disconnect();
```

---

## 🔐 Channel Authorization

### Private Channel
Only authenticated users can access:
```javascript
window.Echo.private(`chatroom.${id}`)
    .listen('MessageSent', (event) => {
        // Handle event
    });
```

### Public Channel
All users can access:
```javascript
window.Echo.channel(`chatroom.${id}`)
    .listen('UserTyping', (event) => {
        // Handle event
    });
```

---

## 🎯 Broadcasting Configuration

### Pusher (Production)
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

### Soketi (Self-hosted)
```php
'soketi' => [
    'driver' => 'pusher',
    'key' => env('SOKETI_APP_KEY'),
    'secret' => env('SOKETI_APP_SECRET'),
    'app_id' => env('SOKETI_APP_ID'),
    'options' => [
        'host' => env('SOKETI_HOST'),
        'port' => env('SOKETI_PORT'),
        'scheme' => env('SOKETI_SCHEME'),
    ],
],
```

---

## 📊 Database Schema

### chat_messages table
```sql
CREATE TABLE chat_messages (
    id BIGINT PRIMARY KEY,
    chat_room_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    content LONGTEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'text',
    reply_to_id BIGINT NULL,
    edited_content LONGTEXT NULL,
    edited_at TIMESTAMP NULL,
    is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (chat_room_id) REFERENCES chat_rooms(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

## 🧪 Testing Real-time Chat

### Test Message Sending
1. Open chat in two browser windows
2. Send message in first window
3. Verify message appears instantly in second window

### Test Typing Indicator
1. Start typing in message input
2. Verify typing indicator appears in other windows

### Test Message Editing
1. Edit a message
2. Verify edit appears instantly in all windows

### Test Message Deletion
1. Delete a message
2. Verify deletion appears instantly in all windows

---

## 🐛 Troubleshooting

### WebSocket Connection Failed
```javascript
// Check connection status
console.log(window.Echo);

// Enable debug mode
const chat = new RealtimeChat(chatRoomId, { debug: true });
```

### Messages Not Appearing
1. Check queue is running: `php artisan queue:work`
2. Check browser console for errors
3. Verify broadcasting credentials
4. Check network tab for WebSocket connection

### CORS Errors
1. Update `config/cors.php`
2. Add your domain to allowed origins
3. Restart server

---

## 📚 Related Files

- `app/Events/MessageSent.php` - Broadcasting event
- `app/Http/Controllers/ChatMessageController.php` - API controller
- `routes/api.php` - API routes
- `config/broadcasting.php` - Broadcasting config

---

## ✅ Checklist

- [ ] Dependencies installed
- [ ] Broadcasting configured
- [ ] Frontend built
- [ ] Queue worker running
- [ ] Chat interface accessible
- [ ] Messages sending successfully
- [ ] Real-time updates working
- [ ] Typing indicator working
- [ ] Edit/delete working

---

## 🚀 Next Steps

1. Test real-time chat functionality
2. Integrate with your frontend
3. Add additional features (reactions, file uploads, etc.)
4. Deploy to production
5. Monitor WebSocket connections


