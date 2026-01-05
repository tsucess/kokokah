# 🎯 Chatroom Implementation - Laravel 12 & Course-Based Access

## ✅ Overview

Updated chatroom implementation for **Laravel 12** with proper course-based access control:

- **General Chatroom**: Available to ALL authenticated users
- **Course Chatrooms**: Only available to users enrolled in that course
- **Admin Access**: Admins can access all chatrooms

---

## 🔧 Key Changes for Laravel 12

### 1. Middleware Registration (bootstrap/app.php)

**Laravel 12 uses the new middleware registration system:**

```php
$middleware->alias([
    'ensure.user.authenticated.for.chat' => \App\Http\Middleware\EnsureUserIsAuthenticatedForChat::class,
    'authorize.chat.room.access' => \App\Http\Middleware\AuthorizeChatRoomAccess::class,
    'check.chat.room.mute.status' => \App\Http\Middleware\CheckChatRoomMuteStatus::class,
]);
```

**Note:** No longer using `app/Http/Kernel.php` - all middleware is registered in `bootstrap/app.php`

---

## 📋 Access Control Logic

### General Chatrooms
- **Type:** `general`
- **Access:** All authenticated users
- **Middleware Check:** Returns `true` for all users

### Course Chatrooms
- **Type:** `course`
- **Access:** Only enrolled students + instructor
- **Middleware Check:**
  1. Is user the course instructor? → Allow
  2. Is user enrolled with `status = 'active'`? → Allow
  3. Otherwise → Deny (403)

### Admin Access
- **Role:** `admin`
- **Access:** All chatrooms (bypasses all checks)

---

## 🔄 Updated Components

### 1. ChatroomController::index()
**File:** `app/Http/Controllers/ChatroomController.php`

Returns only chatrooms user has access to:
```php
// Get General chatroom (available to all users)
$generalChatrooms = ChatRoom::where('type', 'general')->get();

// Get course-specific chatrooms for courses user is enrolled in
$enrolledCourseIds = $user->enrolledCourses()
    ->pluck('courses.id')
    ->toArray();

$courseChatrooms = ChatRoom::where('type', 'course')
    ->whereIn('course_id', $enrolledCourseIds)
    ->get();
```

### 2. AuthorizeChatRoomAccess Middleware
**File:** `app/Http/Middleware/AuthorizeChatRoomAccess.php`

Enforces access rules:
- General rooms: All authenticated users
- Course rooms: Enrolled students + instructors
- Admin: All rooms

### 3. ChatMessageSeeder
**File:** `database/seeders/ChatMessageSeeder.php`

Creates messages respecting access rules:
- General chatroom: Messages from all students
- Course chatrooms: Messages only from enrolled students

---

## 🚀 How It Works

### User Sees Chatrooms
1. User logs in
2. Frontend calls `GET /api/chatrooms`
3. Controller queries:
   - All general chatrooms
   - Course chatrooms where user is enrolled
4. Returns filtered list

### User Accesses Messages
1. User clicks chatroom
2. Frontend calls `GET /api/chatrooms/{id}/messages`
3. Middleware checks:
   - Is it general? → Allow
   - Is it course? → Check enrollment
   - Is user admin? → Allow
4. Controller returns messages

---

## 📊 Database Relationships

```
User
├── enrolledCourses() → Course (many-to-many via enrollments)
├── chatRooms() → ChatRoom (many-to-many via chat_room_users)
└── chatMessages() → ChatMessage

Course
├── enrollments() → Enrollment
├── students() → User (many-to-many)
└── chatRoom() → ChatRoom (one-to-one)

ChatRoom
├── type: 'general' | 'course'
├── course_id: nullable (only for course rooms)
├── users() → User (many-to-many)
└── messages() → ChatMessage
```

---

## ✅ Testing

### Test General Chatroom Access
```bash
# Login as any user
# Should see General chatroom
GET /api/chatrooms
```

### Test Course Chatroom Access
```bash
# Login as enrolled student
# Should see course chatroom
GET /api/chatrooms

# Login as non-enrolled user
# Should NOT see course chatroom
```

### Test Message Access
```bash
# Enrolled user can fetch messages
GET /api/chatrooms/{courseRoomId}/messages → 200

# Non-enrolled user cannot
GET /api/chatrooms/{courseRoomId}/messages → 403
```

---

## 🎉 Status

✅ Laravel 12 compatible  
✅ Course-based access control  
✅ General chatroom for all users  
✅ Proper middleware registration  
✅ Enrollment-based filtering  
✅ Admin bypass access  

Ready for production!

