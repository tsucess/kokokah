# Chat System - Quick Reference Card

## 📋 FILE STRUCTURE

```
app/
├── Models/
│   ├── Chatroom.php (NEW)
│   ├── Message.php (NEW)
│   ├── MessageReaction.php (NEW)
│   └── User.php (UPDATE)
├── Http/
│   ├── Controllers/
│   │   ├── ChatroomController.php (NEW)
│   │   ├── MessageController.php (NEW)
│   │   └── TypingIndicatorController.php (NEW)
│   └── Requests/
│       ├── StoreChatroomRequest.php (NEW)
│       └── StoreMessageRequest.php (NEW)
├── Services/
│   ├── ChatroomService.php (NEW)
│   └── MessageService.php (NEW)
├── Policies/
│   ├── ChatroomPolicy.php (NEW)
│   └── MessagePolicy.php (NEW)
└── Events/
    ├── MessageSent.php (NEW)
    ├── MessageEdited.php (NEW)
    ├── MessageDeleted.php (NEW)
    ├── ReactionAdded.php (NEW)
    ├── ReactionRemoved.php (NEW)
    └── UserTyping.php (NEW)

database/
├── migrations/
│   ├── 2025_01_01_000001_create_chatrooms_table.php (NEW)
│   ├── 2025_01_01_000002_create_chatroom_members_table.php (NEW)
│   ├── 2025_01_01_000003_create_messages_table.php (NEW)
│   └── 2025_01_01_000004_create_message_reactions_table.php (NEW)
└── seeders/
    └── ChatroomSeeder.php (NEW)

resources/
└── views/
    └── chatrooms/
        ├── index.blade.php (NEW)
        └── show.blade.php (NEW)

routes/
├── api.php (UPDATE)
└── web.php (UPDATE)
```

---

## 🔑 KEY MODELS & RELATIONSHIPS

```php
// User relationships
User::chatrooms()          // belongsToMany
User::messages()           // hasMany
User::messageReactions()   // hasMany

// Course relationships
Course::chatroom()         // hasOne

// Chatroom relationships
Chatroom::course()         // belongsTo
Chatroom::creator()        // belongsTo User
Chatroom::members()        // belongsToMany
Chatroom::messages()       // hasMany
Chatroom::latestMessage()  // hasOne

// Message relationships
Message::chatroom()        // belongsTo
Message::user()            // belongsTo
Message::reactions()       // hasMany
```

---

## 🛣️ API ENDPOINTS

```
GET    /api/chatrooms                    # List all chatrooms
POST   /api/chatrooms                    # Create chatroom (admin)
GET    /api/chatrooms/{id}               # Get chatroom with messages
PUT    /api/chatrooms/{id}               # Update chatroom
DELETE /api/chatrooms/{id}               # Delete chatroom

POST   /api/chatrooms/{id}/members       # Add member
DELETE /api/chatrooms/{id}/members/{uid} # Remove member

POST   /api/chatrooms/{id}/messages      # Send message
PUT    /api/messages/{id}                # Edit message
DELETE /api/messages/{id}                # Delete message

POST   /api/messages/{id}/reactions      # Add reaction
DELETE /api/messages/{id}/reactions/{r}  # Remove reaction

POST   /api/chatrooms/{id}/typing        # Typing indicator
```

---

## 🎯 AUTHORIZATION RULES

```php
// ChatroomPolicy
view()          // General: all | Course: enrolled | Custom: member
create()        // admin, instructor
update()        // creator, admin
delete()        // creator, admin
manageMember()  // moderator+, admin
sendMessage()   // Same as view()

// MessagePolicy
view()          // Same as chatroom
update()        // Own message, admin
delete()        // Own message, moderator+, admin
```

---

## 📡 BROADCASTING CHANNELS

```javascript
// Subscribe to chatroom
Echo.channel('chatroom.{id}')
    .listen('MessageSent', (e) => {})
    .listen('MessageEdited', (e) => {})
    .listen('MessageDeleted', (e) => {})
    .listen('ReactionAdded', (e) => {})
    .listen('ReactionRemoved', (e) => {})
    .listen('UserTyping', (e) => {});
```

---

## 🗄️ DATABASE TABLES

| Table | Columns | Purpose |
|-------|---------|---------|
| chatrooms | id, name, slug, type, course_id, background_image, created_by, is_active | Chat rooms |
| chatroom_members | id, chatroom_id, user_id, role, joined_at, last_read_at, is_muted | Membership |
| messages | id, chatroom_id, user_id, content, message_type, file_path, is_edited, is_deleted | Messages |
| message_reactions | id, message_id, user_id, reaction | Reactions |

---

## 🔧 COMMON OPERATIONS

```php
// Create chatroom
$chatroom = Chatroom::create([
    'name' => 'General',
    'type' => 'general',
    'created_by' => auth()->id()
]);

// Add member
$chatroom->addMember($user, 'member');

// Send message
$message = $chatroom->messages()->create([
    'user_id' => auth()->id(),
    'content' => 'Hello!'
]);

// Add reaction
$message->addReaction(auth()->user(), '👍');

// Get unread count
$chatroom->getUnreadCount(auth()->user());

// Check membership
$chatroom->isMember($user);

// Update last read
$member->pivot->updateLastRead();
```

---

## 🚀 SETUP COMMANDS

```bash
# 1. Create all files
php artisan make:model Chatroom -m
php artisan make:model Message -m
php artisan make:model MessageReaction -m
php artisan make:controller ChatroomController --resource
php artisan make:controller MessageController --resource
php artisan make:policy ChatroomPolicy --model=Chatroom
php artisan make:policy MessagePolicy --model=Message
php artisan make:event MessageSent
php artisan make:event MessageEdited
php artisan make:event MessageDeleted
php artisan make:event ReactionAdded
php artisan make:event ReactionRemoved
php artisan make:event UserTyping

# 2. Run migrations
php artisan migrate

# 3. Seed general chatroom
php artisan db:seed --class=ChatroomSeeder

# 4. Install broadcasting packages
npm install pusher-js laravel-echo

# 5. Build frontend
npm run build
```

---

## 🧪 TESTING CHECKLIST

- [ ] User can view general chatroom
- [ ] User can send message to general chatroom
- [ ] User can edit own message
- [ ] User can delete own message
- [ ] User can add reaction to message
- [ ] User can remove reaction
- [ ] Enrolled user can access course chatroom
- [ ] Non-enrolled user cannot access course chatroom
- [ ] Moderator can delete any message
- [ ] Admin can manage members
- [ ] Messages appear in real-time
- [ ] Typing indicator works
- [ ] Read receipts update
- [ ] Unread count displays correctly

---

## 🐛 DEBUGGING TIPS

```bash
# Check if event is dispatched
php artisan tinker
broadcast(new App\Events\MessageSent($message));

# Check database
SELECT * FROM chatrooms;
SELECT * FROM messages WHERE chatroom_id = 1;
SELECT * FROM chatroom_members WHERE chatroom_id = 1;

# Check authorization
$user->can('view', $chatroom);
$user->can('sendMessage', $chatroom);

# Check relationships
$chatroom->members()->count();
$message->reactions()->count();

# Check broadcasting
BROADCAST_DRIVER=log  # Log to storage/logs
```

---

## 📚 DOCUMENTATION MAP

| File | Purpose | Lines |
|------|---------|-------|
| CHAT_SYSTEM_ARCHITECTURE.md | Design & schema | 150 |
| CHAT_MODELS_IMPLEMENTATION.md | Models & relationships | 150 |
| CHAT_CONTROLLERS_SERVICES.md | Controllers & services | 150 |
| CHAT_AUTHORIZATION_EVENTS.md | Policies & events | 150 |
| CHAT_ROUTES_REALTIME.md | Routes & broadcasting | 150 |
| CHAT_MIGRATIONS_FRONTEND.md | Migrations & views | 150 |
| CHAT_IMPLEMENTATION_CHECKLIST.md | Implementation plan | 150 |
| CHAT_INTEGRATION_GUIDE.md | Integration with LMS | 150 |
| CHAT_SYSTEM_SUMMARY.md | Executive summary | 150 |
| CHAT_QUICK_REFERENCE.md | This file | 150 |

**Total: 1,500 lines of documentation + code examples**

---

## 💡 PRO TIPS

1. **Use eager loading** to prevent N+1 queries
   ```php
   Chatroom::with('members', 'latestMessage')->get();
   ```

2. **Index frequently queried columns**
   ```php
   $table->index(['chatroom_id', 'created_at']);
   ```

3. **Use soft deletes** for message deletion
   ```php
   $message->softDelete();  // Not permanently deleted
   ```

4. **Cache member lists** for performance
   ```php
   Cache::remember("chatroom.{$id}.members", 3600, fn() => ...);
   ```

5. **Implement rate limiting** on message sending
   ```php
   RateLimiter::attempt('send-message:' . auth()->id(), 10, fn() => ...);
   ```


