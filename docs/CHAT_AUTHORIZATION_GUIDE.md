# 🔐 Chat System Authorization Guide

Complete guide to the authorization system for the real-time chat system.

---

## 📋 Overview

The chat system uses **Laravel Policies** and **Middleware** to enforce authorization rules:

- ✅ **Policies** - Define what users can do (view, create, update, delete)
- ✅ **Middleware** - Enforce authentication and authorization at route level
- ✅ **Gates** - Custom authorization logic for complex scenarios

---

## 🎯 Authorization Rules

### Chat Room Access

| User Type | General Rooms | Course Rooms | Admin |
|-----------|---------------|--------------|-------|
| **View** | Members only | Enrolled + Instructor | All |
| **Create** | All users | Instructors only | All |
| **Edit** | Creator only | Creator + Instructor | All |
| **Delete** | Creator only | Creator + Instructor | All |
| **Manage Members** | Creator only | Creator + Instructor | All |

### Message Operations

| Operation | Owner | Room Creator | Instructor | Admin |
|-----------|-------|--------------|------------|-------|
| **View** | ✅ | ✅ | ✅ | ✅ |
| **Create** | ✅ | ✅ | ✅ | ✅ |
| **Edit** | ✅ | ❌ | ❌ | ✅ |
| **Delete** | ✅ | ✅ | ✅ | ✅ |
| **Pin** | ❌ | ✅ | ✅ | ✅ |
| **React** | ✅ | ✅ | ✅ | ✅ |

---

## 🔐 Policies

### ChatRoomPolicy

**File:** `app/Policies/ChatRoomPolicy.php`

**Methods:**
- `view()` - Can user view the room?
- `create()` - Can user create a room?
- `update()` - Can user edit the room?
- `delete()` - Can user delete the room?
- `manageMember()` - Can user manage members?
- `archive()` - Can user archive the room?
- `restore()` - Can user restore the room?
- `forceDelete()` - Can user permanently delete?

**Usage:**
```php
// In controller
$this->authorize('view', $chatRoom);
$this->authorize('update', $chatRoom);
$this->authorize('delete', $chatRoom);

// In blade
@can('view', $chatRoom)
    <!-- Show room -->
@endcan

// In gate
if (Gate::allows('manage-chat-room', $chatRoom)) {
    // User can manage
}
```

### ChatMessagePolicy

**File:** `app/Policies/ChatMessagePolicy.php`

**Methods:**
- `viewAny()` - Can user view messages in room?
- `view()` - Can user view specific message?
- `create()` - Can user create message?
- `update()` - Can user edit message?
- `delete()` - Can user delete message?
- `restore()` - Can user restore message?
- `forceDelete()` - Can user permanently delete?
- `pin()` - Can user pin message?
- `react()` - Can user add reaction?

**Usage:**
```php
// In controller
$this->authorize('create', [ChatMessage::class, $chatRoom]);
$this->authorize('update', $message);
$this->authorize('delete', $message);
$this->authorize('pin', $message);
$this->authorize('react', $message);
```

---

## 🛣️ Middleware

### EnsureUserIsAuthenticatedForChat

**File:** `app/Http/Middleware/EnsureUserIsAuthenticatedForChat.php`

**Purpose:** Ensures user is authenticated and account is active

**Applied to:** All chat routes

**Checks:**
- User is logged in
- User account is active

**Response:** 401 Unauthorized or 403 Forbidden

### AuthorizeChatRoomAccess

**File:** `app/Http/Middleware/AuthorizeChatRoomAccess.php`

**Purpose:** Ensures user has access to the chat room

**Applied to:** Chat room message routes

**Checks:**
- User is a member of the room OR
- User is admin OR
- For course rooms: user is enrolled or instructor

**Response:** 403 Forbidden

### CheckChatRoomMuteStatus

**File:** `app/Http/Middleware/CheckChatRoomMuteStatus.php`

**Purpose:** Prevents muted users from sending messages

**Applied to:** POST message routes only

**Checks:**
- User is not muted in the room
- Admin users bypass this check

**Response:** 403 Forbidden

---

## 🚪 Gates

**File:** `app/Providers/AuthServiceProvider.php`

### access-chat-room

Check if user can access a chat room.

```php
Gate::allows('access-chat-room', $chatRoom)
Gate::denies('access-chat-room', $chatRoom)
```

### send-message

Check if user can send a message in a room.

```php
Gate::allows('send-message', $chatRoom)
```

### manage-chat-room

Check if user can manage a room (edit, delete, manage members).

```php
Gate::allows('manage-chat-room', $chatRoom)
```

### moderate-chat-room

Check if user can moderate a room (delete messages, mute users).

```php
Gate::allows('moderate-chat-room', $chatRoom)
```

---

## 📝 Routes with Authorization

### Chat Room Messages

```php
Route::prefix('chatrooms/{chatRoom}/messages')
    ->middleware([
        'auth:sanctum',
        'ensure.user.authenticated.for.chat',
        'authorize.chat.room.access',
    ])
    ->group(function () {
        Route::get('/', [ChatMessageController::class, 'index']);
        Route::post('/', [ChatMessageController::class, 'store'])
            ->middleware('check.chat.room.mute.status');
        Route::get('/{message}', [ChatMessageController::class, 'show']);
        Route::put('/{message}', [ChatMessageController::class, 'update']);
        Route::delete('/{message}', [ChatMessageController::class, 'destroy']);
    });
```

---

## 💻 Usage Examples

### Check Room Access

```php
// In controller
if ($this->authorize('view', $chatRoom)) {
    // User can view room
}

// Using gate
if (Gate::allows('access-chat-room', $chatRoom)) {
    // User can access room
}

// In blade
@can('view', $chatRoom)
    <div>Room content</div>
@endcan
```

### Check Message Permissions

```php
// Can user edit message?
$this->authorize('update', $message);

// Can user delete message?
$this->authorize('delete', $message);

// Can user pin message?
$this->authorize('pin', $message);

// Can user react to message?
$this->authorize('react', $message);
```

### Check Room Management

```php
// Can user manage room?
if (Gate::allows('manage-chat-room', $chatRoom)) {
    // Show edit/delete buttons
}

// Can user moderate room?
if (Gate::allows('moderate-chat-room', $chatRoom)) {
    // Show moderation tools
}
```

---

## 🔍 Authorization Flow

### Viewing Messages

```
1. User requests GET /api/chatrooms/{id}/messages
2. Middleware: EnsureUserIsAuthenticatedForChat
   - Check: User is authenticated
   - Check: User account is active
3. Middleware: AuthorizeChatRoomAccess
   - Check: User is member OR admin OR enrolled/instructor
4. Policy: ChatMessagePolicy::viewAny()
   - Check: User can view messages in room
5. Controller: ChatMessageController::index()
   - Return messages
```

### Sending Message

```
1. User requests POST /api/chatrooms/{id}/messages
2. Middleware: EnsureUserIsAuthenticatedForChat
   - Check: User is authenticated
   - Check: User account is active
3. Middleware: AuthorizeChatRoomAccess
   - Check: User is member OR admin OR enrolled/instructor
4. Middleware: CheckChatRoomMuteStatus
   - Check: User is not muted
5. Policy: ChatMessagePolicy::create()
   - Check: User can create message
   - Check: Room is active and not archived
6. Controller: ChatMessageController::store()
   - Create and broadcast message
```

### Editing Message

```
1. User requests PUT /api/chatrooms/{id}/messages/{id}
2. Middleware: EnsureUserIsAuthenticatedForChat
3. Middleware: AuthorizeChatRoomAccess
4. Policy: ChatMessagePolicy::update()
   - Check: User is message owner OR admin
   - Check: Message is not deleted
5. Controller: ChatMessageController::update()
   - Update and broadcast message
```

---

## 🛡️ Security Features

✅ **Authentication Required** - All chat endpoints require login  
✅ **Room Access Control** - Users can only access rooms they belong to  
✅ **Course Enrollment Check** - Course rooms restricted to enrolled users  
✅ **Instructor Access** - Instructors can access course rooms  
✅ **Admin Override** - Admins can access all rooms  
✅ **Mute Enforcement** - Muted users cannot send messages  
✅ **Message Ownership** - Users can only edit/delete their own messages  
✅ **Room Creator Rights** - Room creators can manage their rooms  
✅ **Soft Deletes** - Deleted messages can be restored  

---

## 🧪 Testing Authorization

### Test Room Access

```php
// User can view room they belong to
$this->assertTrue(Gate::allows('access-chat-room', $chatRoom));

// User cannot view room they don't belong to
$this->assertFalse(Gate::allows('access-chat-room', $otherRoom));

// Admin can view any room
$this->assertTrue(Gate::allows('access-chat-room', $chatRoom, $admin));
```

### Test Message Permissions

```php
// User can edit their own message
$this->assertTrue(Gate::allows('update', $message, $user));

// User cannot edit others' messages
$this->assertFalse(Gate::allows('update', $message, $otherUser));

// Admin can edit any message
$this->assertTrue(Gate::allows('update', $message, $admin));
```

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

## 🚀 Best Practices

1. **Always use policies** - Don't check authorization manually
2. **Use middleware** - Apply middleware to route groups
3. **Use gates for complex logic** - Gates are better for complex scenarios
4. **Check in blade** - Use @can/@cannot in templates
5. **Fail securely** - Default to deny, explicitly allow
6. **Log authorization failures** - Track unauthorized access attempts
7. **Test authorization** - Write tests for all authorization rules

---

## 📞 Support

For questions about authorization, see:
- `app/Policies/ChatRoomPolicy.php` - Room authorization
- `app/Policies/ChatMessagePolicy.php` - Message authorization
- `app/Providers/AuthServiceProvider.php` - Gates and policy registration
- `app/Http/Middleware/` - Middleware implementations


