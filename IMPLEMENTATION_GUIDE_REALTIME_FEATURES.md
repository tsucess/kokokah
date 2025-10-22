# Real-time Features with WebSocket - Implementation Guide

## ⚡ Current State Analysis

### What's Already Implemented
- ✅ Notification system (polling-based)
- ✅ Chat system (request/response)
- ✅ Activity logging
- ✅ Notification preferences

### What's Missing
- ❌ WebSocket server (Laravel Reverb)
- ❌ Real-time notifications (push)
- ❌ Live chat system
- ❌ Presence indicators (online/offline)
- ❌ Real-time activity feeds
- ❌ Live class support
- ❌ Real-time collaboration
- ❌ Typing indicators

---

## 🎯 Implementation Plan

### Phase 1: Install & Configure Laravel Reverb

**Installation:**
```bash
composer require laravel/reverb
php artisan reverb:install
```

**Configuration in `config/reverb.php`:**
- Set up WebSocket server (default: localhost:8080)
- Configure for production (use Pusher or self-hosted)
- Set up authentication

### Phase 2: Create Broadcasting Events

**New Events to Create:**
1. `NotificationSent` - Real-time notification
2. `ChatMessageSent` - Live chat message
3. `UserOnline` - User came online
4. `UserOffline` - User went offline
5. `TypingIndicator` - User is typing
6. `ActivityUpdated` - Real-time activity
7. `LessonProgressUpdated` - Student progress
8. `QuizAnswerSubmitted` - Quiz submission

**Example Event Structure:**
```php
class NotificationSent implements ShouldBroadcast
{
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->notification->user_id);
    }
    
    public function broadcastAs()
    {
        return 'notification.sent';
    }
}
```

### Phase 3: Update Controllers for Broadcasting

**Update NotificationController:**
- Broadcast notifications instead of just storing
- Use `broadcast()` helper
- Send to appropriate channels

**Update ChatController:**
- Broadcast chat messages in real-time
- Add typing indicators
- Track online status

### Phase 4: Create Real-time Services

**Create `app/Services/RealtimeService.php`:**
- `notifyUser()` - Send real-time notification
- `broadcastToRole()` - Broadcast to role
- `broadcastToClass()` - Broadcast to class
- `updatePresence()` - Update online status
- `sendTypingIndicator()` - Show typing status

### Phase 5: Add Presence Channels

**Presence Tracking:**
```php
// In routes/channels.php
Broadcast::channel('course.{courseId}', function ($user, $courseId) {
    return $user->enrollments()->where('course_id', $courseId)->exists();
});

Broadcast::presence('course.{courseId}.users', function ($user, $courseId) {
    return ['id' => $user->id, 'name' => $user->name];
});
```

### Phase 6: Frontend Integration (JavaScript)

**Client-side setup:**
```javascript
// Listen for notifications
Echo.private('user.' + userId)
    .listen('NotificationSent', (e) => {
        console.log('New notification:', e.notification);
    });

// Listen for chat messages
Echo.private('chat.' + chatId)
    .listen('ChatMessageSent', (e) => {
        console.log('New message:', e.message);
    });

// Track presence
Echo.presence('course.' + courseId + '.users')
    .here((users) => console.log('Online users:', users))
    .joining((user) => console.log('User joined:', user))
    .leaving((user) => console.log('User left:', user));
```

### Phase 7: Add New API Endpoints

**New Endpoints:**
```
GET  /api/realtime/status
POST /api/realtime/presence/online
POST /api/realtime/presence/offline
POST /api/chat/messages (with broadcasting)
GET  /api/notifications/realtime
POST /api/notifications/broadcast
```

### Phase 8: Implement Live Features

**Live Class Support:**
- Broadcast lesson content
- Real-time Q&A
- Live polls
- Screen sharing (via third-party)

**Real-time Collaboration:**
- Shared whiteboard
- Collaborative documents
- Live code editor

---

## 🔧 Technology Stack

- **WebSocket Server:** Laravel Reverb (recommended)
- **Alternative:** Pusher (managed service)
- **Client Library:** Laravel Echo
- **Frontend:** Vue.js or React with Echo

---

## 📊 Channel Structure

```
Private Channels:
- user.{userId} - Personal notifications
- chat.{chatId} - Chat conversations
- course.{courseId}.instructor - Instructor channel

Presence Channels:
- course.{courseId}.users - Active users in course
- class.{classId}.participants - Live class participants
```

---

## 🚀 Implementation Priority

1. **High Priority:** Real-time notifications (biggest impact)
2. **High Priority:** Live chat (user engagement)
3. **Medium Priority:** Presence indicators (UX)
4. **Medium Priority:** Activity feeds (engagement)
5. **Low Priority:** Live class (advanced feature)

---

## 📝 Estimated Timeline

- **Phase 1-2:** 1 week (Setup + Events)
- **Phase 3-4:** 1 week (Controllers + Services)
- **Phase 5-6:** 1 week (Presence + Frontend)
- **Phase 7-8:** 1 week (API + Features)
- **Total:** 4 weeks for complete implementation

---

## 💡 Deployment Considerations

**Development:**
- Use Laravel Reverb locally
- Run on port 8080

**Production:**
- Use Pusher (managed, reliable)
- Or self-host Reverb on separate server
- Use Redis for scaling
- Set up SSL/TLS for WSS

---

## 🔐 Security

- Authenticate WebSocket connections
- Validate channel access
- Rate limit broadcasts
- Encrypt sensitive data
- Log all broadcasts

