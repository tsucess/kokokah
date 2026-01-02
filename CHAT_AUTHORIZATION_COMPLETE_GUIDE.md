# 🔐 Chat Authorization - Complete Implementation Guide

## ✅ Status: FULLY IMPLEMENTED

Your Kokokah.com chat system has **comprehensive authorization** using Laravel Policies, Middleware, and a dedicated Authorization Service.

---

## 🎯 Authorization Rules

### 1. **Chat Room Access**

| User Type | General Rooms | Course Rooms | Archived Rooms |
|-----------|---------------|--------------|----------------|
| **Admin** | ✅ All | ✅ All | ✅ All |
| **Instructor** | ✅ Member | ✅ Own Course | ❌ No |
| **Student** | ✅ Member | ✅ Enrolled | ❌ No |
| **Non-Member** | ❌ No | ❌ No | ❌ No |

### 2. **Message Operations**

| Operation | Owner | Room Creator | Instructor | Admin |
|-----------|-------|--------------|-----------|-------|
| **View** | ✅ | ✅ | ✅ | ✅ |
| **Send** | ✅ | ✅ | ✅ | ✅ |
| **Edit Own** | ✅ | ✅ | ✅ | ✅ |
| **Edit Others** | ❌ | ❌ | ❌ | ✅ |
| **Delete Own** | ✅ | ✅ | ✅ | ✅ |
| **Delete Others** | ❌ | ✅ | ✅ | ✅ |
| **Pin** | ❌ | ✅ | ✅ | ✅ |
| **React** | ✅ | ✅ | ✅ | ✅ |

### 3. **Room Management**

| Operation | Creator | Instructor | Admin |
|-----------|---------|-----------|-------|
| **Update** | ✅ | ✅ | ✅ |
| **Delete** | ✅ | ✅ | ✅ |
| **Archive** | ✅ | ✅ | ✅ |
| **Manage Members** | ✅ | ✅ | ✅ |
| **Mute Users** | ✅ | ✅ | ✅ |
| **Force Delete** | ❌ | ❌ | ✅ |

---

## 🏗️ Implementation Components

### 1. **Policies** (Laravel Policies)

#### ChatRoomPolicy
**File:** `app/Policies/ChatRoomPolicy.php`

**Methods:**
- `view()` - Can user view this room?
- `create()` - Can user create rooms?
- `update()` - Can user update this room?
- `delete()` - Can user delete this room?
- `manageMember()` - Can user manage members?
- `archive()` - Can user archive this room?
- `restore()` - Can user restore this room?
- `forceDelete()` - Can user permanently delete?

#### ChatMessagePolicy
**File:** `app/Policies/ChatMessagePolicy.php`

**Methods:**
- `viewAny()` - Can user view messages in room?
- `view()` - Can user view this message?
- `create()` - Can user send messages?
- `update()` - Can user edit this message?
- `delete()` - Can user delete this message?
- `restore()` - Can user restore this message?
- `forceDelete()` - Can user permanently delete?
- `react()` - Can user react to this message?
- `pin()` - Can user pin this message?

### 2. **Middleware**

#### EnsureUserAuthenticatedForChat
**File:** `app/Http/Middleware/EnsureUserAuthenticatedForChat.php`

**Checks:**
- User is authenticated
- User account is active
- User is not banned
- (Optional) Email is verified

#### AuthorizeChatRoomAccess
**File:** `app/Http/Middleware/AuthorizeChatRoomAccess.php`

**Checks:**
- Chat room exists
- User can access the room
- Room is not archived
- Room is active

### 3. **Authorization Service**

**File:** `app/Services/ChatAuthorizationService.php`

**Methods:**
- `canAccessRoom()` - Can user access room?
- `canSendMessage()` - Can user send message?
- `canEditMessage()` - Can user edit message?
- `canDeleteMessage()` - Can user delete message?
- `canManageMembers()` - Can user manage members?
- `canMuteUser()` - Can user mute another user?
- `canRemoveUser()` - Can user remove another user?

---

## 🔌 Integration Points

### Routes with Authorization

```php
// Chat room messages routes
Route::prefix('chatrooms/{chatRoom}/messages')
    ->middleware([
        'auth:sanctum',
        'ensure.user.authenticated.for.chat',
        'authorize.chat.room.access',
    ])
    ->group(function () {
        Route::get('/', [ChatMessageController::class, 'index']);
        Route::post('/', [ChatMessageController::class, 'store']);
        Route::put('/{message}', [ChatMessageController::class, 'update']);
        Route::delete('/{message}', [ChatMessageController::class, 'destroy']);
    });
```

### Controller Usage

```php
// In ChatMessageController
public function store(Request $request, ChatRoom $chatRoom)
{
    // Authorize using policy
    $this->authorize('create', [ChatMessage::class, $chatRoom]);
    
    // Create message
    $message = ChatMessage::create([...]);
    
    // Broadcast event
    broadcast(new MessageSent($message, $chatRoom))->toOthers();
}

public function update(Request $request, ChatRoom $chatRoom, ChatMessage $message)
{
    // Authorize using policy
    $this->authorize('update', $message);
    
    // Update message
    $message->update([...]);
}
```

### Service Usage

```php
// In any controller or service
use App\Services\ChatAuthorizationService;

$authService = new ChatAuthorizationService();

// Check if user can access room
if ($authService->canAccessRoom($user, $chatRoom)) {
    // Allow access
}

// Check if user can send message
if ($authService->canSendMessage($user, $chatRoom)) {
    // Allow message
}

// Check if user can manage members
if ($authService->canManageMembers($user, $chatRoom)) {
    // Allow management
}
```

---

## 📋 Authorization Checks in Controllers

### ChatMessageController

```php
// Fetch messages
public function index(Request $request, ChatRoom $chatRoom)
{
    $this->authorize('viewAny', [ChatMessage::class, $chatRoom]);
    // ...
}

// Send message
public function store(Request $request, ChatRoom $chatRoom)
{
    $this->authorize('create', [ChatMessage::class, $chatRoom]);
    // ...
}

// Update message
public function update(Request $request, ChatRoom $chatRoom, ChatMessage $message)
{
    $this->authorize('update', $message);
    // ...
}

// Delete message
public function destroy(ChatRoom $chatRoom, ChatMessage $message)
{
    $this->authorize('delete', $message);
    // ...
}
```

### ChatController

```php
// View room
public function show(ChatRoom $chatRoom)
{
    $this->authorize('view', $chatRoom);
    // ...
}

// Update room
public function update(Request $request, ChatRoom $chatRoom)
{
    $this->authorize('update', $chatRoom);
    // ...
}

// Delete room
public function destroy(ChatRoom $chatRoom)
{
    $this->authorize('delete', $chatRoom);
    // ...
}

// Manage members
public function manageMember(Request $request, ChatRoom $chatRoom)
{
    $this->authorize('manageMember', $chatRoom);
    // ...
}
```

---

## 🧪 Testing Authorization

### Test User Access

```php
// Test admin can access all rooms
$admin = User::factory()->create(['role' => 'admin']);
$this->actingAs($admin)
    ->getJson("/api/chatrooms/{$room->id}/messages")
    ->assertStatus(200);

// Test non-member cannot access
$user = User::factory()->create();
$this->actingAs($user)
    ->getJson("/api/chatrooms/{$room->id}/messages")
    ->assertStatus(403);

// Test enrolled student can access course room
$student = User::factory()->create();
$course->enrollments()->create(['user_id' => $student->id, 'status' => 'active']);
$this->actingAs($student)
    ->getJson("/api/chatrooms/{$courseRoom->id}/messages")
    ->assertStatus(200);
```

### Test Message Operations

```php
// Test user can edit own message
$message = ChatMessage::factory()->create(['user_id' => $user->id]);
$this->actingAs($user)
    ->putJson("/api/chatrooms/{$room->id}/messages/{$message->id}", [
        'content' => 'Updated'
    ])
    ->assertStatus(200);

// Test user cannot edit others message
$otherUser = User::factory()->create();
$this->actingAs($otherUser)
    ->putJson("/api/chatrooms/{$room->id}/messages/{$message->id}", [
        'content' => 'Hacked'
    ])
    ->assertStatus(403);
```

---

## 🔒 Security Features

✅ **Authentication Required** - All chat operations require authentication  
✅ **Role-Based Access** - Different permissions for admin, instructor, student  
✅ **Room Membership** - Users can only access rooms they belong to  
✅ **Course Enrollment** - Course rooms restricted to enrolled users  
✅ **User Muting** - Muted users cannot send messages  
✅ **Account Status** - Inactive/banned users cannot access chat  
✅ **Archived Rooms** - Non-admin users cannot access archived rooms  
✅ **Message Ownership** - Users can only edit/delete their own messages  

---

## 📊 Authorization Matrix

### Admin
- ✅ Access all rooms
- ✅ View all messages
- ✅ Edit any message
- ✅ Delete any message
- ✅ Manage all rooms
- ✅ Manage all members
- ✅ Force delete rooms/messages

### Instructor (Course Rooms)
- ✅ Access own course rooms
- ✅ View all messages in course
- ✅ Edit own messages
- ✅ Delete any message in course
- ✅ Update course room
- ✅ Manage course room members
- ✅ Mute users in course room

### Student
- ✅ Access rooms they belong to
- ✅ Access enrolled course rooms
- ✅ View messages in accessible rooms
- ✅ Send messages (if not muted)
- ✅ Edit own messages
- ✅ Delete own messages
- ✅ React to messages

### Non-Member
- ❌ Cannot access any room
- ❌ Cannot view messages
- ❌ Cannot send messages

---

## 🚀 Best Practices

1. **Always Use Policies** - Use `$this->authorize()` in controllers
2. **Use Middleware** - Apply middleware to route groups
3. **Check in Service** - Use service for complex authorization logic
4. **Log Authorization Failures** - Track unauthorized access attempts
5. **Test Authorization** - Write tests for all authorization rules
6. **Document Rules** - Keep authorization rules documented
7. **Review Regularly** - Review authorization rules periodically

---

## 📞 Support

For questions about authorization:
1. Check the Policy files
2. Check the Middleware files
3. Check the Authorization Service
4. Review the tests in `tests/Feature/`

---

**Status:** ✅ **FULLY IMPLEMENTED & SECURE!** 🔐

Your chat system has comprehensive authorization protecting user data and preventing unauthorized access.


