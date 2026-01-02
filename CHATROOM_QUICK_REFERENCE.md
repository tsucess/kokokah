# 🚀 Chatroom Quick Reference

## Installation & Setup

### 1. Verify Middleware Registration
```bash
# Check bootstrap/app.php has middleware aliases
grep -n "ensure.user.authenticated.for.chat" bootstrap/app.php
```

### 2. Run Seeder
```bash
php artisan db:seed --class=ChatMessageSeeder
```

### 3. Test API
```bash
# Get chatrooms
curl -X GET http://localhost:8000/api/chatrooms \
  -H "Authorization: Bearer YOUR_TOKEN"

# Get messages
curl -X GET http://localhost:8000/api/chatrooms/1/messages \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Access Rules Quick Lookup

| Scenario | Access | Reason |
|----------|--------|--------|
| User views General chatroom | ✅ YES | Available to all |
| User views enrolled course chatroom | ✅ YES | User is enrolled |
| User views non-enrolled course chatroom | ❌ NO | Not enrolled |
| Instructor views course chatroom | ✅ YES | Course owner |
| Admin views any chatroom | ✅ YES | Admin bypass |
| Muted user sends message | ❌ NO | User is muted |

---

## Code Snippets

### Get User's Accessible Chatrooms
```php
$user = Auth::user();

// General chatrooms
$general = ChatRoom::where('type', 'general')->get();

// Course chatrooms
$courses = $user->enrolledCourses()
    ->pluck('courses.id')
    ->toArray();

$courseRooms = ChatRoom::where('type', 'course')
    ->whereIn('course_id', $courses)
    ->get();

$all = $general->concat($courseRooms);
```

### Check User Access to Chatroom
```php
$user = Auth::user();
$chatRoom = ChatRoom::find($id);

// Admin always has access
if ($user->role === 'admin') {
    return true;
}

// General rooms accessible to all
if ($chatRoom->type === 'general') {
    return true;
}

// Course rooms: check enrollment
if ($chatRoom->type === 'course') {
    return $chatRoom->course->enrollments()
        ->where('user_id', $user->id)
        ->where('status', 'active')
        ->exists();
}

return false;
```

### Send Message
```php
$message = ChatMessage::create([
    'chat_room_id' => $roomId,
    'user_id' => auth()->id(),
    'content' => 'Hello!',
    'type' => 'text',
]);

// Broadcast event
broadcast(new MessageSent($message, $chatRoom))->toOthers();
```

---

## Troubleshooting

### Issue: 500 Error on Message Load
**Solution:**
1. Check middleware in bootstrap/app.php
2. Verify AuthorizeChatRoomAccess exists
3. Check Laravel logs

### Issue: User Can't See Course Chatroom
**Solution:**
1. Verify enrollment exists
2. Check enrollment status = 'active'
3. Verify course_id on chatroom

### Issue: Messages Not Displaying
**Solution:**
1. Check messages exist in database
2. Verify user has access
3. Check API response in Network tab

---

## Database Queries

### Find All Messages in Chatroom
```sql
SELECT * FROM chat_messages 
WHERE chat_room_id = 1 
AND is_deleted = 0 
ORDER BY created_at DESC;
```

### Find User's Enrolled Courses
```sql
SELECT c.* FROM courses c
JOIN enrollments e ON c.id = e.course_id
WHERE e.user_id = 1 
AND e.status = 'active';
```

### Find Course Chatroom
```sql
SELECT * FROM chat_rooms 
WHERE type = 'course' 
AND course_id = 1;
```

---

## Files to Know

| File | Purpose |
|------|---------|
| bootstrap/app.php | Middleware registration |
| ChatroomController.php | List chatrooms |
| ChatMessageController.php | Manage messages |
| AuthorizeChatRoomAccess.php | Access control |
| ChatRoom.php | Model |
| ChatMessage.php | Model |

---

## Common Tasks

### Add User to Chatroom
```php
$chatRoom->users()->attach($userId, [
    'role' => 'member',
    'joined_at' => now()
]);
```

### Remove User from Chatroom
```php
$chatRoom->users()->detach($userId);
```

### Mute User
```php
$chatRoom->users()
    ->where('user_id', $userId)
    ->update(['is_muted' => true]);
```

### Get Unread Count
```php
$unreadCount = $chatRoom->users()
    ->where('user_id', $userId)
    ->first()
    ->pivot
    ->unread_count;
```

---

## Status: ✅ READY TO USE

All features implemented and tested!

