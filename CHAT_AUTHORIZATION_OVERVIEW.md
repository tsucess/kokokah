# 🎯 Chat Authorization System - Visual Overview

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    HTTP Request                              │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              Route Middleware Stack                          │
├─────────────────────────────────────────────────────────────┤
│ 1. auth:sanctum                                             │
│    └─ Verify API token                                      │
│                                                              │
│ 2. ensure.user.authenticated.for.chat                       │
│    └─ Check user is authenticated & account is active       │
│                                                              │
│ 3. authorize.chat.room.access                               │
│    └─ Check user has access to chat room                    │
│                                                              │
│ 4. check.chat.room.mute.status (POST only)                  │
│    └─ Check user is not muted                               │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              Controller Method                               │
├─────────────────────────────────────────────────────────────┤
│ $this->authorize('action', $model)                          │
│    └─ Invoke Policy                                         │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              Policy Authorization                            │
├─────────────────────────────────────────────────────────────┤
│ ChatRoomPolicy / ChatMessagePolicy                          │
│    └─ Check specific permissions                            │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              Response                                        │
├─────────────────────────────────────────────────────────────┤
│ 200 OK - Authorized                                         │
│ 401 Unauthorized - Not authenticated                        │
│ 403 Forbidden - Not authorized                              │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔐 Authorization Layers

```
Layer 1: Authentication
├─ User is logged in
├─ API token is valid
└─ Session is active

Layer 2: Account Status
├─ User account is active
├─ User is not suspended
└─ User is not deleted

Layer 3: Room Access
├─ User is a member of room
├─ User is enrolled in course (for course rooms)
├─ User is instructor (for course rooms)
└─ User is admin

Layer 4: Mute Status
├─ User is not muted in room
├─ Admin bypass applies
└─ Only for message sending

Layer 5: Policy Authorization
├─ User has specific permission
├─ Message ownership checked
├─ Room creator rights verified
└─ Admin override applied
```

---

## 📊 Authorization Decision Tree

```
User Request
    │
    ├─ Is user authenticated?
    │  ├─ NO → 401 Unauthorized
    │  └─ YES ↓
    │
    ├─ Is user account active?
    │  ├─ NO → 403 Forbidden
    │  └─ YES ↓
    │
    ├─ Can user access room?
    │  ├─ NO → 403 Forbidden
    │  └─ YES ↓
    │
    ├─ Is this a POST request?
    │  ├─ YES → Is user muted?
    │  │        ├─ YES → 403 Forbidden
    │  │        └─ NO ↓
    │  └─ NO ↓
    │
    ├─ Does policy allow action?
    │  ├─ NO → 403 Forbidden
    │  └─ YES ↓
    │
    └─ 200 OK - Process Request
```

---

## 🎯 Policy Methods

### ChatRoomPolicy
```
view()          → Can user view room?
create()        → Can user create room?
update()        → Can user edit room?
delete()        → Can user delete room?
manageMember()  → Can user manage members?
archive()       → Can user archive room?
restore()       → Can user restore room?
forceDelete()   → Can user permanently delete?
```

### ChatMessagePolicy
```
viewAny()       → Can user view messages in room?
view()          → Can user view specific message?
create()        → Can user create message?
update()        → Can user edit message?
delete()        → Can user delete message?
restore()       → Can user restore message?
forceDelete()   → Can user permanently delete?
pin()           → Can user pin message?
react()         → Can user add reaction?
```

---

## 🛣️ Middleware Flow

```
Request
  │
  ├─ auth:sanctum
  │  └─ Verify token
  │
  ├─ ensure.user.authenticated.for.chat
  │  ├─ Check authenticated
  │  └─ Check account active
  │
  ├─ authorize.chat.room.access
  │  ├─ Check room membership
  │  ├─ Check course enrollment
  │  ├─ Check instructor status
  │  └─ Check admin status
  │
  ├─ check.chat.room.mute.status (POST only)
  │  ├─ Check mute status
  │  └─ Allow admin bypass
  │
  └─ Controller
     └─ Policy authorization
```

---

## 👥 User Roles & Permissions

### Room Creator
```
✅ View room
✅ Edit room
✅ Delete room
✅ Manage members
✅ Delete messages
✅ Pin messages
✅ Mute users
```

### Course Instructor
```
✅ View course room
✅ Edit course room
✅ Delete course room
✅ Manage members
✅ Delete messages
✅ Pin messages
✅ Mute users
```

### Enrolled Student
```
✅ View course room
✅ Send messages
✅ Edit own messages
✅ Delete own messages
✅ React to messages
❌ Edit room
❌ Delete room
❌ Manage members
```

### Admin
```
✅ View all rooms
✅ Edit all rooms
✅ Delete all rooms
✅ Manage all members
✅ Delete all messages
✅ Pin all messages
✅ Mute all users
```

---

## 📁 File Structure

```
app/
├── Policies/
│   ├── ChatRoomPolicy.php
│   └── ChatMessagePolicy.php
│
├── Http/
│   ├── Middleware/
│   │   ├── EnsureUserIsAuthenticatedForChat.php
│   │   ├── AuthorizeChatRoomAccess.php
│   │   └── CheckChatRoomMuteStatus.php
│   │
│   ├── Controllers/
│   │   └── ChatMessageController.php
│   │
│   └── Kernel.php
│
└── Providers/
    └── AuthServiceProvider.php

routes/
└── api.php

tests/
└── Feature/
    └── ChatAuthorizationTest.php

docs/
└── CHAT_AUTHORIZATION_GUIDE.md
```

---

## 🔄 Request Flow Example

### Sending a Message

```
1. POST /api/chatrooms/1/messages
   Content: { "content": "Hello!" }

2. auth:sanctum
   ✓ Token valid

3. ensure.user.authenticated.for.chat
   ✓ User authenticated
   ✓ Account active

4. authorize.chat.room.access
   ✓ User is member of room

5. check.chat.room.mute.status
   ✓ User not muted

6. ChatMessageController::store()
   $this->authorize('create', [ChatMessage::class, $chatRoom])

7. ChatMessagePolicy::create()
   ✓ User can access room
   ✓ Room is active
   ✓ Room not archived

8. Create message
   ✓ Message created
   ✓ Event broadcast
   ✓ 201 Created response
```

---

## 🧪 Test Coverage

```
ChatAuthorizationTest
├── Unauthenticated Access
│   └─ test_unauthenticated_user_cannot_access_chat
│
├── Room Access Control
│   ├─ test_user_can_view_room_they_belong_to
│   ├─ test_user_cannot_view_room_they_dont_belong_to
│   ├─ test_enrolled_student_can_view_course_room
│   ├─ test_non_enrolled_student_cannot_view_course_room
│   ├─ test_instructor_can_view_course_room
│   └─ test_admin_can_view_any_room
│
├── Message Operations
│   ├─ test_user_can_send_message_in_room_they_belong_to
│   ├─ test_user_cannot_send_message_in_room_they_dont_belong_to
│   ├─ test_user_can_edit_their_own_message
│   ├─ test_user_cannot_edit_others_messages
│   ├─ test_admin_can_edit_any_message
│   ├─ test_user_can_delete_their_own_message
│   ├─ test_user_cannot_delete_others_messages
│   ├─ test_room_creator_can_delete_messages
│   └─ test_instructor_can_delete_messages_in_course_room
│
├── Muting
│   └─ test_muted_user_cannot_send_message
│
└── Account Status
    └─ test_inactive_user_cannot_access_chat
```

---

## 📚 Documentation Files

```
docs/
└── CHAT_AUTHORIZATION_GUIDE.md
    ├── Overview
    ├── Authorization Rules
    ├── Policies
    ├── Middleware
    ├── Gates
    ├── Routes
    ├── Usage Examples
    ├── Authorization Flow
    ├── Security Features
    ├── Testing
    ├── Authorization Matrix
    └── Best Practices

CHAT_AUTHORIZATION_QUICK_REFERENCE.md
├── Quick Start
├── Authorization Rules
├── Files Overview
├── Testing Commands
├── Common Issues
├── Authorization Matrix
└── Tips

CHAT_AUTHORIZATION_SUMMARY.md
├── Implementation Overview
├── Authorization Rules
├── Security Features
├── Files List
├── Testing Instructions
└── Usage Examples

CHAT_AUTHORIZATION_CHECKLIST.md
├── Implementation Status
├── Testing Status
├── Documentation Status
├── Authorization Rules
├── Security Features
├── Code Quality
├── Files Status
└── Deployment Readiness
```

---

## ✅ Implementation Status

- [x] Policies implemented
- [x] Middleware implemented
- [x] Authorization provider created
- [x] Routes protected
- [x] Controller updated
- [x] Kernel configured
- [x] Tests created
- [x] Documentation created
- [x] No errors or warnings
- [x] Production ready

---

**Status:** ✅ **COMPLETE & PRODUCTION READY!** 🚀


