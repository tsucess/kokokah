# 🚀 Chat Authorization - Quick Reference

## 📋 Quick Start

### Check Room Access
```php
// In controller
$this->authorize('view', $chatRoom);

// Using gate
Gate::allows('access-chat-room', $chatRoom)
```

### Check Message Permissions
```php
// Can edit?
$this->authorize('update', $message);

// Can delete?
$this->authorize('delete', $message);

// Can pin?
$this->authorize('pin', $message);
```

### Check Room Management
```php
// Can manage room?
Gate::allows('manage-chat-room', $chatRoom)

// Can moderate room?
Gate::allows('moderate-chat-room', $chatRoom)
```

---

## 🔐 Authorization Rules

### Room Access
- **Members** - Can view and send messages
- **Room Creator** - Can edit, delete, manage members
- **Course Instructor** - Can access and manage course rooms
- **Admin** - Can access all rooms

### Message Operations
- **Owner** - Can edit and delete own messages
- **Room Creator** - Can delete any message
- **Instructor** - Can delete messages in course rooms
- **Admin** - Can edit and delete any message

### Muting
- **Muted users** - Cannot send messages
- **Room Creator** - Can mute/unmute members
- **Instructor** - Can mute/unmute in course rooms
- **Admin** - Can mute/unmute anyone

---

## 📁 Files Overview

| File | Purpose |
|------|---------|
| `app/Policies/ChatRoomPolicy.php` | Room authorization |
| `app/Policies/ChatMessagePolicy.php` | Message authorization |
| `app/Http/Middleware/EnsureUserIsAuthenticatedForChat.php` | Auth check |
| `app/Http/Middleware/AuthorizeChatRoomAccess.php` | Room access check |
| `app/Http/Middleware/CheckChatRoomMuteStatus.php` | Mute check |
| `app/Providers/AuthServiceProvider.php` | Policy registration & gates |
| `docs/CHAT_AUTHORIZATION_GUIDE.md` | Full documentation |

---

## 🧪 Testing

```bash
# Run authorization tests
php artisan test tests/Feature/ChatAuthorizationTest.php

# Run specific test
php artisan test tests/Feature/ChatAuthorizationTest.php --filter=test_user_can_view_room_they_belong_to

# Run with coverage
php artisan test --coverage
```

---

## 🛡️ Security Checklist

- [x] Authentication required for all chat endpoints
- [x] Room access control enforced
- [x] Course enrollment verified
- [x] Instructor access allowed
- [x] Admin override available
- [x] Mute enforcement active
- [x] Message ownership checked
- [x] Account status verified
- [x] Soft deletes supported

---

## 🚨 Common Issues

### User Cannot Access Room
1. Check if user is a member: `$room->users()->where('user_id', $user->id)->exists()`
2. Check if user is enrolled (course rooms): `$course->enrollments()->where('user_id', $user->id)->exists()`
3. Check if user is instructor: `$course->instructor_id === $user->id`
4. Check if user is admin: `$user->role === 'admin'`

### User Cannot Send Message
1. Check if user is muted: `$room->users()->where('user_id', $user->id)->where('is_muted', true)->exists()`
2. Check if room is active: `$room->is_active === true`
3. Check if room is archived: `$room->is_archived === false`
4. Check if user can access room (see above)

### User Cannot Edit Message
1. Check if user is message owner: `$message->user_id === $user->id`
2. Check if user is admin: `$user->role === 'admin'`
3. Check if message is deleted: `$message->is_deleted === false`

### User Cannot Delete Message
1. Check if user is message owner: `$message->user_id === $user->id`
2. Check if user is room creator: `$message->chatRoom->created_by === $user->id`
3. Check if user is instructor (course rooms): `$message->chatRoom->course->instructor_id === $user->id`
4. Check if user is admin: `$user->role === 'admin'`

---

## 📊 Authorization Matrix

### Room Creator
- ✅ View room
- ✅ Edit room
- ✅ Delete room
- ✅ Manage members
- ✅ Delete messages
- ✅ Pin messages
- ✅ Mute users

### Course Instructor
- ✅ View course room
- ✅ Edit course room
- ✅ Delete course room
- ✅ Manage members
- ✅ Delete messages
- ✅ Pin messages
- ✅ Mute users

### Enrolled Student
- ✅ View course room
- ✅ Send messages
- ✅ Edit own messages
- ✅ Delete own messages
- ✅ React to messages
- ❌ Edit room
- ❌ Delete room
- ❌ Manage members

### Admin
- ✅ View all rooms
- ✅ Edit all rooms
- ✅ Delete all rooms
- ✅ Manage all members
- ✅ Delete all messages
- ✅ Pin all messages
- ✅ Mute all users

---

## 🔗 Related Documentation

- **Full Guide:** `docs/CHAT_AUTHORIZATION_GUIDE.md`
- **Implementation Status:** `CHAT_AUTHORIZATION_IMPLEMENTATION_COMPLETE.md`
- **Tests:** `tests/Feature/ChatAuthorizationTest.php`

---

## 💡 Tips

1. **Always use policies** - Don't check authorization manually
2. **Use middleware** - Apply middleware to route groups
3. **Use gates for complex logic** - Gates are better for complex scenarios
4. **Check in blade** - Use @can/@cannot in templates
5. **Fail securely** - Default to deny, explicitly allow
6. **Log authorization failures** - Track unauthorized access attempts
7. **Test authorization** - Write tests for all authorization rules

---

## 🎯 Next Steps

1. Run tests: `php artisan test tests/Feature/ChatAuthorizationTest.php`
2. Review documentation: `docs/CHAT_AUTHORIZATION_GUIDE.md`
3. Test in your application
4. Deploy to production

---

**Status:** ✅ **READY FOR PRODUCTION!** 🚀


