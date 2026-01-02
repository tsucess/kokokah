# Chat Schema - Quick Reference Guide

## 📋 Table Summary

| Table | Purpose | Rows | Soft Delete |
|-------|---------|------|-------------|
| `chat_rooms` | Chat room metadata | Few | ✅ Yes |
| `chat_room_users` | User membership | Many | ✅ Yes |
| `chat_messages` | Message content | Many | ✅ Yes |
| `message_reactions` | Emoji reactions | Many | ❌ No |

---

## 🗂️ Column Reference

### chat_rooms
```
id (PK)
├─ name: VARCHAR(255)
├─ description: TEXT
├─ type: ENUM('general', 'course')
├─ course_id: BIGINT FK (nullable)
├─ created_by: BIGINT FK
├─ background_image: VARCHAR(255)
├─ icon: VARCHAR(255)
├─ color: VARCHAR(7) = '#007bff'
├─ is_active: BOOLEAN = true
├─ is_archived: BOOLEAN = false
├─ member_count: INT = 0
├─ message_count: INT = 0
├─ last_message_at: TIMESTAMP
├─ created_at: TIMESTAMP
├─ updated_at: TIMESTAMP
└─ deleted_at: TIMESTAMP (soft delete)
```

### chat_room_users
```
id (PK)
├─ chat_room_id: BIGINT FK
├─ user_id: BIGINT FK
├─ role: ENUM('member', 'moderator', 'admin') = 'member'
├─ is_active: BOOLEAN = true
├─ is_muted: BOOLEAN = false
├─ is_pinned: BOOLEAN = false
├─ joined_at: TIMESTAMP = NOW()
├─ last_read_at: TIMESTAMP
├─ unread_count: INT = 0
├─ notification_level: ENUM('all', 'mentions', 'none') = 'all'
├─ created_at: TIMESTAMP
├─ updated_at: TIMESTAMP
└─ deleted_at: TIMESTAMP (soft delete)
```

### chat_messages
```
id (PK)
├─ chat_room_id: BIGINT FK
├─ user_id: BIGINT FK
├─ content: LONGTEXT
├─ type: ENUM('text', 'image', 'file', 'system') = 'text'
├─ reply_to_id: BIGINT FK (nullable)
├─ edited_content: LONGTEXT
├─ edited_at: TIMESTAMP
├─ reaction_count: INT = 0
├─ is_pinned: BOOLEAN = false
├─ is_deleted: BOOLEAN = false
├─ metadata: JSON
├─ created_at: TIMESTAMP
├─ updated_at: TIMESTAMP
└─ deleted_at: TIMESTAMP (soft delete)
```

### message_reactions
```
id (PK)
├─ chat_message_id: BIGINT FK
├─ user_id: BIGINT FK
├─ reaction: VARCHAR(255) [emoji]
├─ created_at: TIMESTAMP
└─ updated_at: TIMESTAMP
```

---

## 🔑 Key Indexes

### chat_rooms
- `type` - Filter by room type
- `course_id` - Find room by course
- `created_by` - Find rooms created by user
- `is_active` - Filter active rooms
- `created_at` - Sort by creation date
- `UNIQUE(course_id, type)` - One room per course

### chat_room_users
- `UNIQUE(chat_room_id, user_id)` - Prevent duplicates
- `user_id` - Find user's rooms
- `role` - Filter by role
- `is_active` - Filter active members
- `last_read_at` - Find unread messages
- `(chat_room_id, is_active)` - Active members
- `(user_id, is_active)` - User's active rooms

### chat_messages
- `chat_room_id` - Find messages in room
- `user_id` - Find user's messages
- `created_at` - Sort by date
- `(chat_room_id, created_at)` - Messages by room & date
- `(user_id, created_at)` - Messages by user & date
- `is_pinned` - Find pinned messages
- `reply_to_id` - Find message threads

### message_reactions
- `UNIQUE(chat_message_id, user_id, reaction)` - One per user
- `user_id` - Find user's reactions
- `reaction` - Find reactions by type
- `(chat_message_id, reaction)` - Reactions on message
- `created_at` - Sort by date

---

## 🔗 Foreign Keys

| Table | Column | References | On Delete |
|-------|--------|-----------|-----------|
| chat_rooms | course_id | courses.id | CASCADE |
| chat_rooms | created_by | users.id | CASCADE |
| chat_room_users | chat_room_id | chat_rooms.id | CASCADE |
| chat_room_users | user_id | users.id | CASCADE |
| chat_messages | chat_room_id | chat_rooms.id | CASCADE |
| chat_messages | user_id | users.id | CASCADE |
| chat_messages | reply_to_id | chat_messages.id | SET NULL |
| message_reactions | chat_message_id | chat_messages.id | CASCADE |
| message_reactions | user_id | users.id | CASCADE |

---

## 📊 Relationships

### User
```php
$user->chatRooms()           // Many-to-many
$user->chatRoomUsers()       // One-to-many
$user->chatMessages()        // One-to-many
$user->messageReactions()    // One-to-many
$user->createdChatRooms()    // One-to-many
```

### Course
```php
$course->chatRoom()          // One-to-one
```

### ChatRoom
```php
$room->users()               // Many-to-many
$room->members()             // One-to-many (pivot)
$room->messages()            // One-to-many
$room->creator()             // Belongs-to
$room->course()              // Belongs-to (nullable)
```

### ChatMessage
```php
$message->room()             // Belongs-to
$message->user()             // Belongs-to
$message->reactions()        // One-to-many
$message->replies()          // One-to-many
$message->parentMessage()    // Belongs-to (nullable)
```

### MessageReaction
```php
$reaction->message()         // Belongs-to
$reaction->user()            // Belongs-to
```

---

## 🚀 Common Queries

### Get user's chat rooms
```php
$rooms = $user->chatRooms()->where('is_active', true)->get();
```

### Get room members
```php
$members = $room->users()->where('is_active', true)->get();
```

### Get room messages
```php
$messages = $room->messages()
    ->where('is_deleted', false)
    ->orderBy('created_at', 'desc')
    ->paginate(50);
```

### Get unread messages
```php
$unread = $user->chatRoomUsers()
    ->where('unread_count', '>', 0)
    ->get();
```

### Get message reactions
```php
$reactions = $message->reactions()
    ->groupBy('reaction')
    ->selectRaw('reaction, COUNT(*) as count')
    ->get();
```

### Get user's reactions
```php
$reactions = MessageReaction::where('user_id', $user->id)
    ->where('created_at', '>', now()->subDays(7))
    ->get();
```

---

## 🔐 Authorization Checks

### Can view room?
```php
$user->chatRoomUsers()
    ->where('chat_room_id', $room->id)
    ->where('is_active', true)
    ->exists();
```

### Can send message?
```php
// Same as view + not muted
$user->chatRoomUsers()
    ->where('chat_room_id', $room->id)
    ->where('is_active', true)
    ->where('is_muted', false)
    ->exists();
```

### Can edit message?
```php
// Own message or moderator+
$message->user_id === $user->id ||
$user->chatRoomUsers()
    ->where('chat_room_id', $message->chat_room_id)
    ->whereIn('role', ['moderator', 'admin'])
    ->exists();
```

### Can delete message?
```php
// Own message or moderator+
$message->user_id === $user->id ||
$user->chatRoomUsers()
    ->where('chat_room_id', $message->chat_room_id)
    ->whereIn('role', ['moderator', 'admin'])
    ->exists();
```

---

## 📈 Performance Tips

1. **Eager Load Relationships**
   ```php
   $rooms = ChatRoom::with('users', 'messages')->get();
   ```

2. **Use Pagination**
   ```php
   $messages = $room->messages()->paginate(50);
   ```

3. **Cache Member Lists**
   ```php
   Cache::remember("room.{$room->id}.members", 3600, fn() => 
       $room->users()->get()
   );
   ```

4. **Use Denormalized Counts**
   ```php
   // Instead of: $room->messages()->count()
   // Use: $room->message_count
   ```

5. **Index Frequently Queried Columns**
   - Already done in migrations!

---

## ✅ Migration Checklist

- [x] Create chat_rooms table
- [x] Create chat_room_users table
- [x] Create chat_messages table
- [x] Create message_reactions table
- [x] Add foreign keys
- [x] Add indexes
- [x] Add soft deletes
- [x] Add denormalized counts
- [x] Add unique constraints
- [x] Add default values

---

## 🔄 Data Flow

```
User joins room
  ↓
INSERT into chat_room_users
  ↓
UPDATE chat_rooms.member_count++
  ↓
User sends message
  ↓
INSERT into chat_messages
  ↓
UPDATE chat_rooms.message_count++
  ↓
UPDATE chat_rooms.last_message_at
  ↓
UPDATE chat_room_users.last_read_at
  ↓
UPDATE chat_room_users.unread_count = 0
  ↓
Broadcast MessageSent event
```

---

## 📞 Need Help?

- **Schema Questions** → See CHAT_DATABASE_SCHEMA.md
- **Migration Issues** → Check database/migrations/
- **Query Help** → See Common Queries section above
- **Performance** → See Performance Tips section above


