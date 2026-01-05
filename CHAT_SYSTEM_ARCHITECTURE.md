# Laravel Group Chat System Architecture

## 📋 Overview
A WhatsApp-like group chat system for Kokokah LMS with real-time messaging, course-based chatrooms, and persistent storage.

---

## 🗄️ DATABASE SCHEMA

### 1. **Chatrooms Table**
```sql
CREATE TABLE chatrooms (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    description TEXT,
    type ENUM('general', 'course') DEFAULT 'general',
    course_id BIGINT UNSIGNED NULLABLE,
    background_image VARCHAR(255) NULLABLE,
    created_by BIGINT UNSIGNED NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
);
```

### 2. **Chatroom Members Table**
```sql
CREATE TABLE chatroom_members (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    chatroom_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    role ENUM('member', 'moderator', 'admin') DEFAULT 'member',
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_read_at TIMESTAMP NULLABLE,
    is_muted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY unique_member (chatroom_id, user_id),
    FOREIGN KEY (chatroom_id) REFERENCES chatrooms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 3. **Messages Table**
```sql
CREATE TABLE messages (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    chatroom_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    message_type ENUM('text', 'image', 'file', 'system') DEFAULT 'text',
    file_path VARCHAR(255) NULLABLE,
    is_edited BOOLEAN DEFAULT FALSE,
    edited_at TIMESTAMP NULLABLE,
    is_deleted BOOLEAN DEFAULT FALSE,
    deleted_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (chatroom_id) REFERENCES chatrooms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_chatroom_created (chatroom_id, created_at),
    INDEX idx_user_created (user_id, created_at)
);
```

### 4. **Message Reactions Table** (Optional)
```sql
CREATE TABLE message_reactions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    message_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    reaction VARCHAR(50) NOT NULL,
    created_at TIMESTAMP,
    UNIQUE KEY unique_reaction (message_id, user_id, reaction),
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

## 📦 ELOQUENT MODELS & RELATIONSHIPS

### Model Hierarchy
```
User
├── chatrooms (many-to-many through chatroom_members)
├── messages (one-to-many)
└── messageReactions (one-to-many)

Course
└── chatroom (one-to-one)

Chatroom
├── members (many-to-many through chatroom_members)
├── messages (one-to-many)
├── course (belongs-to)
└── creator (belongs-to User)

Message
├── chatroom (belongs-to)
├── user (belongs-to)
└── reactions (one-to-many)
```

---

## 🎯 KEY FEATURES

1. **General Chatroom** - Always available to all authenticated users
2. **Course Chatrooms** - Auto-created with courses, access restricted to enrolled students
3. **Real-time Updates** - Laravel Echo + WebSockets (Pusher/Soketi)
4. **Message Persistence** - All messages stored in database
5. **Background Images** - Per-chatroom customization
6. **Read Receipts** - Track last_read_at per member
7. **Message Editing/Deletion** - Soft deletes with timestamps
8. **Reactions** - Emoji reactions on messages
9. **Typing Indicators** - Real-time "user is typing" status
10. **Unread Counts** - Badge notifications

---

## 🔐 AUTHORIZATION RULES

| Action | General | Course | Admin |
|--------|---------|--------|-------|
| View Messages | All Users | Enrolled Only | All |
| Send Message | All Users | Enrolled Only | All |
| Edit Own Message | Yes | Yes | Yes |
| Delete Own Message | Yes | Yes | Yes |
| Delete Any Message | No | Moderator+ | Yes |
| Manage Members | No | Moderator+ | Yes |
| Mute Members | No | Moderator+ | Yes |
| Change Background | No | Moderator+ | Yes |

---

## 📡 REAL-TIME STRATEGY

### Option 1: Laravel Echo + Pusher (Recommended for Production)
- **Pros:** Reliable, scalable, managed service
- **Cons:** Paid service
- **Setup:** Configure in `config/broadcasting.php`

### Option 2: Laravel Echo + Soketi (Self-hosted)
- **Pros:** Free, open-source, full control
- **Cons:** Requires server management
- **Setup:** Docker container with Soketi

### Option 3: Polling (Fallback)
- **Pros:** No WebSocket setup needed
- **Cons:** Higher latency, more server load
- **Interval:** 2-3 second polls

---

## 🚀 IMPLEMENTATION PHASES

**Phase 1:** Database & Models (Week 1)
**Phase 2:** Controllers & Services (Week 2)
**Phase 3:** Blade Templates & UI (Week 3)
**Phase 4:** Real-time Broadcasting (Week 4)
**Phase 5:** Testing & Optimization (Week 5)


