# Chatroom System - Technical Reference

## 📁 File Structure

```
app/
├── Models/
│   ├── ChatRoom.php              (187 lines)
│   ├── ChatMessage.php           (254 lines)
│   └── MessageReaction.php
├── Http/Controllers/
│   ├── ChatroomController.php    (251 lines)
│   └── ChatMessageController.php (353 lines)
├── Observers/
│   ├── CourseObserver.php        (120 lines)
│   └── EnrollmentObserver.php    (122 lines)
└── Events/
    └── MessageSent.php

database/
├── migrations/
│   ├── 2025_12_30_000001_create_chat_rooms_table.php
│   ├── 2025_12_30_000002_create_chat_room_users_table.php
│   ├── 2025_12_30_000003_create_chat_messages_table.php
│   └── 2025_12_30_000004_create_message_reactions_table.php
└── seeders/
    ├── ChatroomSeeder.php        (122 lines)
    └── ChatMessageSeeder.php

routes/
└── api.php (lines 481-510)
```

---

## 🔧 Model Relationships

### ChatRoom Model
```php
// Relationships
creator()      → BelongsTo User
course()       → BelongsTo Course
users()        → BelongsToMany User (pivot: chat_room_users)
messages()     → HasMany ChatMessage

// Scopes
generalRooms()      → where type = 'general'
courseRooms()       → where type = 'course'
active()            → where is_active = true
notArchived()       → where is_archived = false
withRecentActivity()→ where last_message_at >= now()-24h
forUser($userId)    → whereHas users with is_active = true
```

### ChatMessage Model
```php
// Relationships
chatRoom()     → BelongsTo ChatRoom
user()         → BelongsTo User
replyTo()      → BelongsTo ChatMessage (reply_to_id)
replies()      → HasMany ChatMessage (reply_to_id)
reactions()    → HasMany MessageReaction

// Scopes
inRoom($roomId)     → where chat_room_id = $roomId
fromUser($userId)   → where user_id = $userId
textMessages()      → where type = 'text'
imageMessages()     → where type = 'image'
fileMessages()      → where type = 'file'
systemMessages()    → where type = 'system'
pinned()            → where is_pinned = true
recent($hours)      → where created_at >= now()-$hours
edited()            → whereNotNull edited_at
withReplies()       → whereHas replies
```

---

## 🎯 Controller Methods

### ChatroomController
- `index()` - List accessible rooms (general + enrolled courses)
- `show($chatroom)` - Get room details with members
- `store()` - Create room (admin only)
- `update()` - Update room (admin only)
- `destroy()` - Delete room (admin only)

### ChatMessageController
- `index()` - Fetch messages with pagination
- `store()` - Send new message
- `show()` - Get specific message
- `update()` - Edit message
- `destroy()` - Delete message
- `updateLastRead()` - Track read status
- `isRoomMember()` - Check membership
- `isUserMuted()` - Check mute status

---

## 🔄 Observer Automation

### CourseObserver
**Triggers:** When course is created/updated/deleted

```php
created()   → Create chat room + add instructor + add enrolled students
updated()   → Update room name/description if course title changes
deleted()   → Soft delete chat room
restored()  → Restore chat room
forceDeleted() → Permanently delete chat room
```

### EnrollmentObserver
**Triggers:** When enrollment is created/updated/deleted

```php
created()   → Add user to course chat room (if active)
updated()   → Update user status in room based on enrollment status
deleted()   → Remove user from chat room
restored()  → Re-add user to chat room
forceDeleted() → Remove user from chat room
```

---

## 📊 Database Columns

### chat_rooms (15 columns)
```
id, name, description, type, course_id, created_by,
background_image, icon, color, is_active, is_archived,
member_count, message_count, last_message_at,
created_at, updated_at, deleted_at
```

### chat_room_users (13 columns)
```
id, chat_room_id, user_id, role, is_active, is_muted,
is_pinned, joined_at, last_read_at, unread_count,
notification_level, created_at, updated_at, deleted_at
```

### chat_messages (14 columns)
```
id, chat_room_id, user_id, content, type, reply_to_id,
edited_content, edited_at, reaction_count, is_pinned,
is_deleted, metadata, created_at, updated_at, deleted_at
```

### message_reactions (5 columns)
```
id, chat_message_id, user_id, reaction, created_at
```

---

## 🔐 Authorization

**Middleware Stack:**
1. `auth:sanctum` - User must be authenticated
2. `ensure.user.authenticated.for.chat` - Verify chat access
3. `authorize.chat.room.access` - Check room access
4. `check.chat.room.mute.status` - Verify not muted

**Access Rules:**
- General rooms: All authenticated users
- Course rooms: Instructor (admin) + enrolled students (members)
- Admin: Full access to all rooms

---

## 🚀 Seeding

**ChatroomSeeder creates 7 general rooms:**
1. General
2. Mathematics Help Corner
3. Science Discussions
4. English Literature & Writing
5. History & Social Studies
6. ICT & Programming Chat
7. Foreign Language Practice

Each room gets random 5-15 students added automatically.

---

## 📡 Real-time Events

**MessageSent Event** - Broadcasts when message created
- Triggers WebSocket update
- Notifies room members
- Updates unread counts

---

## ✅ Validation Rules

### Store Message
```
content: required|string|max:5000
type: in:text,image,file
file: nullable|file|max:10240
```

### Create Chatroom
```
name: required|string|max:255
description: nullable|string|max:1000
type: nullable|in:general,course,private
icon: nullable|string
color: nullable|string
```

