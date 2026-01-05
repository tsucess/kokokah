# Chatroom System - Quick Start Guide

## 🚀 5-Minute Overview

The chatroom system has **two types of rooms**:

### 1️⃣ General Chatrooms
- **Who can access:** All authenticated users
- **How many:** 7 pre-seeded rooms
- **Examples:** "General", "Math Help", "Science Discussions"
- **Created by:** Admin (manual or seeder)

### 2️⃣ Course Chatrooms
- **Who can access:** Only enrolled students + instructor
- **How many:** One per course (auto-created)
- **Created by:** Automatic when course is created
- **Deleted by:** Automatic when course is deleted

---

## 🔄 Automatic Workflows

### When Course is Created
```
✅ ChatRoom automatically created
✅ Instructor automatically added (as admin)
✅ Enrolled students automatically added (as members)
```

### When Student Enrolls
```
✅ Student automatically added to course chatroom
✅ Student can immediately start chatting
```

### When Student Unenrolls
```
✅ Student automatically removed from chatroom
✅ Student can no longer access the room
```

---

## 📡 API Quick Reference

### List All Accessible Chatrooms
```bash
GET /api/chatrooms
```
Returns: General rooms + enrolled course rooms

### Get Room Details
```bash
GET /api/chatrooms/{roomId}
```
Returns: Room info + members

### Send a Message
```bash
POST /api/chatrooms/{roomId}/messages
Content-Type: application/json

{
  "content": "Hello everyone!",
  "type": "text"
}
```

### Fetch Messages
```bash
GET /api/chatrooms/{roomId}/messages?page=1&per_page=50
```
Returns: Paginated messages with user info

### Edit Message
```bash
PUT /api/chatrooms/{roomId}/messages/{messageId}
Content-Type: application/json

{
  "content": "Updated message"
}
```

### Delete Message
```bash
DELETE /api/chatrooms/{roomId}/messages/{messageId}
```

---

## 🔐 Access Control Rules

| User Type | General Rooms | Course Rooms |
|-----------|---------------|-------------|
| **Student** | ✅ View & Chat | ✅ Only enrolled |
| **Instructor** | ✅ View & Chat | ✅ Own courses |
| **Admin** | ✅ Full Access | ✅ All courses |

---

## 💻 Code Examples

### Get User's Chatrooms
```php
$user = Auth::user();
$rooms = $user->chatRooms()->get();
```

### Send Message
```php
$message = ChatMessage::create([
    'chat_room_id' => $roomId,
    'user_id' => auth()->id(),
    'content' => 'Hello!',
    'type' => 'text',
]);
```

### Get Room Messages
```php
$messages = ChatRoom::find($roomId)
    ->messages()
    ->with('user', 'reactions')
    ->paginate(50);
```

### Add User to Room
```php
$room->users()->attach($userId, [
    'role' => 'member',
    'joined_at' => now(),
]);
```

### Remove User from Room
```php
$room->users()->detach($userId);
```

---

## 📊 Database Tables

| Table | Purpose | Rows |
|-------|---------|------|
| `chat_rooms` | Room metadata | 1 per room |
| `chat_room_users` | User membership | 1 per user per room |
| `chat_messages` | Messages | 1 per message |
| `message_reactions` | Emoji reactions | 1 per reaction |

---

## 🎯 Common Tasks

### Create General Chatroom (Admin)
```php
ChatRoom::create([
    'name' => 'New Room',
    'type' => 'general',
    'created_by' => auth()->id(),
]);
```

### Check if User Can Access Room
```php
$canAccess = $room->users()
    ->where('user_id', $userId)
    ->where('is_active', true)
    ->exists();
```

### Get Unread Messages
```php
$unreadCount = $room->users()
    ->where('user_id', $userId)
    ->first()
    ->pivot->unread_count;
```

### Mute Room Notifications
```php
$room->users()->updateExistingPivot($userId, [
    'is_muted' => true,
]);
```

---

## 🚨 Troubleshooting

**Q: Student can't see course chatroom?**
A: Check enrollment status is 'active'

**Q: Messages not appearing?**
A: Verify user is room member, check is_deleted = false

**Q: Slow message loading?**
A: Check pagination, verify indexes exist

**Q: Unread count wrong?**
A: Clear cache, verify last_read_at is updating

---

## 📚 Full Documentation

- `CHATROOM_SYSTEM_EXECUTIVE_SUMMARY.md` - Complete overview
- `CHATROOM_TECHNICAL_REFERENCE.md` - Technical details
- `CHATROOM_IMPLEMENTATION_CHECKLIST.md` - Deployment guide

---

## ✅ Status

**System Status:** ✅ PRODUCTION READY

All requirements met:
- ✅ General chatroom for all users
- ✅ Automatic course chatroom creation
- ✅ Enrollment-based access control

Ready to deploy!

