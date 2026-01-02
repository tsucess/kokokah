# Chat System - Database Schema Documentation

## 📋 Overview

Complete database schema for a Laravel group chat system with support for:
- General chatrooms (all users)
- Course-specific chatrooms (auto-created)
- Real-time messaging
- Message reactions
- Read receipts
- Role-based access control

## 🗄️ Tables

### 1. chat_rooms
**Purpose:** Store chat room information

```sql
CREATE TABLE chat_rooms (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('general', 'course') DEFAULT 'general',
    course_id BIGINT NULLABLE,
    created_by BIGINT NOT NULL,
    background_image VARCHAR(255),
    icon VARCHAR(255),
    color VARCHAR(7) DEFAULT '#007bff',
    is_active BOOLEAN DEFAULT true,
    is_archived BOOLEAN DEFAULT false,
    member_count INT DEFAULT 0,
    message_count INT DEFAULT 0,
    last_message_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
```

**Columns:**
- `id` - Primary key
- `name` - Room name (e.g., "General Chat", "Course Name Chat")
- `description` - Optional room description
- `type` - 'general' for all users, 'course' for course-specific
- `course_id` - Foreign key to courses (NULL for general rooms)
- `created_by` - User who created the room
- `background_image` - URL to background image
- `icon` - Room icon/emoji
- `color` - Hex color for UI
- `is_active` - Whether room is active
- `is_archived` - Whether room is archived
- `member_count` - Denormalized count for performance
- `message_count` - Denormalized count for performance
- `last_message_at` - Timestamp of last message
- `deleted_at` - Soft delete timestamp

**Indexes:**
- `type` - Filter by room type
- `course_id` - Find room by course
- `created_by` - Find rooms created by user
- `is_active` - Filter active rooms
- `created_at` - Sort by creation date
- `UNIQUE(course_id, type)` - One chat room per course

**Relationships:**
- `belongsTo(Course)` - The course (if type='course')
- `belongsTo(User, 'created_by')` - Creator
- `hasMany(ChatMessage)` - Messages in room
- `belongsToMany(User, 'chat_room_users')` - Members

---

### 2. chat_room_users
**Purpose:** Pivot table for user membership in chat rooms

```sql
CREATE TABLE chat_room_users (
    id BIGINT PRIMARY KEY,
    chat_room_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    role ENUM('member', 'moderator', 'admin') DEFAULT 'member',
    is_active BOOLEAN DEFAULT true,
    is_muted BOOLEAN DEFAULT false,
    is_pinned BOOLEAN DEFAULT false,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_read_at TIMESTAMP,
    unread_count INT DEFAULT 0,
    notification_level ENUM('all', 'mentions', 'none') DEFAULT 'all',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
```

**Columns:**
- `id` - Primary key
- `chat_room_id` - Foreign key to chat_rooms
- `user_id` - Foreign key to users
- `role` - User's role in room (member/moderator/admin)
- `is_active` - Whether membership is active
- `is_muted` - Whether user muted notifications
- `is_pinned` - Whether room is pinned for user
- `joined_at` - When user joined
- `last_read_at` - Last time user read messages
- `unread_count` - Number of unread messages
- `notification_level` - Notification preference
- `deleted_at` - Soft delete timestamp

**Indexes:**
- `UNIQUE(chat_room_id, user_id)` - Prevent duplicate memberships
- `user_id` - Find user's rooms
- `role` - Filter by role
- `is_active` - Filter active members
- `last_read_at` - Find unread messages
- `(chat_room_id, is_active)` - Active members in room
- `(user_id, is_active)` - User's active rooms

**Relationships:**
- `belongsTo(ChatRoom)` - The room
- `belongsTo(User)` - The user

---

### 3. chat_messages
**Purpose:** Store individual messages

```sql
CREATE TABLE chat_messages (
    id BIGINT PRIMARY KEY,
    chat_room_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    content LONGTEXT NOT NULL,
    type ENUM('text', 'image', 'file', 'system') DEFAULT 'text',
    reply_to_id BIGINT NULLABLE,
    edited_content LONGTEXT,
    edited_at TIMESTAMP,
    reaction_count INT DEFAULT 0,
    is_pinned BOOLEAN DEFAULT false,
    is_deleted BOOLEAN DEFAULT false,
    metadata JSON,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
```

**Columns:**
- `id` - Primary key
- `chat_room_id` - Foreign key to chat_rooms
- `user_id` - Foreign key to users (sender)
- `content` - Message text
- `type` - Message type (text/image/file/system)
- `reply_to_id` - Foreign key to parent message (for threading)
- `edited_content` - Edited message content
- `edited_at` - When message was edited
- `reaction_count` - Denormalized reaction count
- `is_pinned` - Whether message is pinned
- `is_deleted` - Whether message is deleted
- `metadata` - JSON for file info, etc.
- `deleted_at` - Soft delete timestamp

**Indexes:**
- `chat_room_id` - Find messages in room
- `user_id` - Find user's messages
- `created_at` - Sort by date
- `(chat_room_id, created_at)` - Messages in room by date
- `(user_id, created_at)` - User's messages by date
- `is_pinned` - Find pinned messages
- `reply_to_id` - Find message threads

**Relationships:**
- `belongsTo(ChatRoom)` - The room
- `belongsTo(User)` - The sender
- `belongsTo(ChatMessage, 'reply_to_id')` - Parent message
- `hasMany(ChatMessage, 'reply_to_id')` - Replies
- `hasMany(MessageReaction)` - Reactions

---

### 4. message_reactions
**Purpose:** Store emoji reactions on messages

```sql
CREATE TABLE message_reactions (
    id BIGINT PRIMARY KEY,
    chat_message_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    reaction VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Columns:**
- `id` - Primary key
- `chat_message_id` - Foreign key to chat_messages
- `user_id` - Foreign key to users
- `reaction` - Emoji or reaction type (e.g., '👍', '❤️')
- `created_at` - When reaction was added
- `updated_at` - When reaction was updated

**Indexes:**
- `UNIQUE(chat_message_id, user_id, reaction)` - One reaction per user per message
- `user_id` - Find user's reactions
- `reaction` - Find reactions by type
- `(chat_message_id, reaction)` - Reactions on message
- `created_at` - Sort by date

**Relationships:**
- `belongsTo(ChatMessage)` - The message
- `belongsTo(User)` - The user who reacted

---

## 📊 Entity Relationship Diagram

```
USERS (1) ──────────────────────────── (Many) CHAT_ROOMS
  │                                         │
  │ (Many)                                  │ (Many)
  │                                         │
  └─ CHAT_ROOM_USERS ◄──────────────────┘
     ├─ role (member/moderator/admin)
     ├─ joined_at
     ├─ last_read_at
     └─ is_muted

COURSES (1) ──────────────────────── (1) CHAT_ROOMS
  │
  └─ type = 'course'

CHAT_ROOMS (1) ──────────────────────── (Many) CHAT_MESSAGES
  │                                         │
  │                                         │ (Many)
  │                                         │
  │                                    MESSAGE_REACTIONS
  │                                         │
  │                                         └─ reaction (emoji)
  │
  └─ USERS (1) ◄─────────────────────────┘
     └─ created_by
```

---

## 🔑 Key Features

### 1. Soft Deletes
All tables support soft deletes for data recovery:
- `chat_rooms` - Archive rooms without losing data
- `chat_room_users` - Remove members without losing history
- `chat_messages` - Delete messages without losing history
- `message_reactions` - Delete reactions

### 2. Denormalization for Performance
- `chat_rooms.member_count` - Quick member count
- `chat_rooms.message_count` - Quick message count
- `chat_rooms.last_message_at` - Quick last activity
- `chat_room_users.unread_count` - Quick unread count
- `chat_messages.reaction_count` - Quick reaction count

### 3. Comprehensive Indexing
- Foreign key indexes for joins
- Composite indexes for common queries
- Unique constraints to prevent duplicates
- Indexes on frequently filtered columns

### 4. Role-Based Access
- `chat_room_users.role` - member/moderator/admin
- Enables authorization policies
- Supports different permissions per role

### 5. Read Tracking
- `chat_room_users.last_read_at` - Track read status
- `chat_room_users.unread_count` - Quick unread count
- Enables read receipts and notifications

### 6. Message Threading
- `chat_messages.reply_to_id` - Reply to specific message
- Enables message threads/conversations
- Self-referencing foreign key

---

## 🚀 Migration Files

All migrations are in `database/migrations/`:

1. `2025_12_30_000001_create_chat_rooms_table.php`
2. `2025_12_30_000002_create_chat_room_users_table.php`
3. `2025_12_30_000003_create_chat_messages_table.php`
4. `2025_12_30_000004_create_message_reactions_table.php`

### Run Migrations

```bash
php artisan migrate
```

### Rollback Migrations

```bash
php artisan migrate:rollback
```

---

## 📈 Performance Considerations

### Query Optimization
- Use eager loading to prevent N+1 queries
- Composite indexes on frequently joined columns
- Denormalized counts for quick aggregations

### Pagination
- Always paginate message lists
- Use `created_at` for cursor-based pagination
- Limit to 50 messages per page

### Caching
- Cache member lists per room
- Cache room metadata
- Invalidate on updates

### Archiving
- Archive old messages (> 1 year)
- Move to separate table if needed
- Keep recent messages in main table

---

## 🔐 Security Considerations

### Authorization
- Check `chat_room_users.role` for permissions
- Verify user is member before accessing room
- Validate message ownership before editing

### Data Validation
- Validate message content length
- Validate file uploads
- Sanitize user input

### Soft Deletes
- Use `withoutTrashed()` in queries
- Restore deleted data if needed
- Permanently delete after retention period

---

## 📝 Usage Examples

### Create a Chat Room
```php
$room = ChatRoom::create([
    'name' => 'General Chat',
    'type' => 'general',
    'created_by' => auth()->id(),
]);
```

### Add User to Room
```php
$room->users()->attach($user->id, [
    'role' => 'member',
    'joined_at' => now(),
]);
```

### Send Message
```php
$message = ChatMessage::create([
    'chat_room_id' => $room->id,
    'user_id' => auth()->id(),
    'content' => 'Hello!',
]);
```

### Add Reaction
```php
MessageReaction::create([
    'chat_message_id' => $message->id,
    'user_id' => auth()->id(),
    'reaction' => '👍',
]);
```

---

## ✅ Checklist

- [x] Create chat_rooms table
- [x] Create chat_room_users pivot table
- [x] Create chat_messages table
- [x] Create message_reactions table
- [x] Add proper foreign keys
- [x] Add comprehensive indexes
- [x] Add soft delete support
- [x] Add denormalized counts
- [x] Add role-based access
- [x] Add read tracking
- [x] Add message threading
- [x] Document schema


