# Chat System Eloquent Models - Index

Complete index and navigation guide for chat system models.

---

## 📍 START HERE

This index helps you navigate all chat system model documentation and code.

---

## 🎯 Quick Navigation

### For Quick Overview
→ **CHAT_MODELS_DELIVERY.md** (5 min)
- Summary of what was created
- Statistics and metrics
- Quick start examples

### For Detailed Usage
→ **CHAT_MODELS_USAGE_GUIDE.md** (20 min)
- Comprehensive examples
- All relationships explained
- All scopes explained
- Common operations

### For Quick Reference
→ **CHAT_MODELS_QUICK_REFERENCE.md** (5 min)
- Fast lookup
- Code snippets
- Common queries
- Fillable fields

### For Complete Details
→ **CHAT_MODELS_SUMMARY.md** (15 min)
- Detailed statistics
- Feature overview
- Relationship diagrams
- Next steps

---

## 📦 Models Created

### 1. ChatRoom
**File:** `app/Models/ChatRoom.php`

**What it does:**
- Represents a chat room (general or course-specific)
- Manages room metadata and settings
- Tracks members and messages
- Supports background images and styling

**Key Features:**
- ✅ General and course-specific rooms
- ✅ User membership with roles
- ✅ Background image with URL accessor
- ✅ Active/archived status
- ✅ Soft deletes

**Quick Example:**
```php
$room = ChatRoom::create([
    'name' => 'General Discussion',
    'type' => 'general',
    'created_by' => auth()->id(),
]);
```

**Learn More:** CHAT_MODELS_USAGE_GUIDE.md → ChatRoom Model

---

### 2. ChatMessage
**File:** `app/Models/ChatMessage.php`

**What it does:**
- Represents a message in a chat room
- Supports multiple message types
- Enables message threading (replies)
- Tracks edits and reactions

**Key Features:**
- ✅ Multiple message types (text, image, file, system)
- ✅ Message threading (reply to specific message)
- ✅ Edit tracking
- ✅ Pin important messages
- ✅ Soft deletes

**Quick Example:**
```php
$message = ChatMessage::create([
    'chat_room_id' => $roomId,
    'user_id' => auth()->id(),
    'content' => 'Hello everyone!',
    'type' => 'text',
]);
```

**Learn More:** CHAT_MODELS_USAGE_GUIDE.md → ChatMessage Model

---

### 3. MessageReaction
**File:** `app/Models/MessageReaction.php`

**What it does:**
- Represents an emoji reaction on a message
- Tracks who reacted with what
- Prevents duplicate reactions

**Key Features:**
- ✅ Emoji reactions
- ✅ Track who reacted
- ✅ Prevent duplicates
- ✅ Grouped queries

**Quick Example:**
```php
MessageReaction::create([
    'chat_message_id' => $messageId,
    'user_id' => auth()->id(),
    'reaction' => '👍',
]);
```

**Learn More:** CHAT_MODELS_USAGE_GUIDE.md → MessageReaction Model

---

## 🔗 Models Updated

### User Model
**File:** `app/Models/User.php`

**Added Relationships:**
- `chatRooms()` - All rooms user is in
- `createdChatRooms()` - Rooms user created
- `chatMessages()` - All messages sent
- `messageReactions()` - All reactions added

**Quick Example:**
```php
$rooms = auth()->user()->chatRooms()->get();
```

---

### Course Model
**File:** `app/Models/Course.php`

**Added Relationship:**
- `chatRoom()` - Associated ChatRoom

**Quick Example:**
```php
$room = $course->chatRoom;
```

---

## 📊 Model Statistics

| Model | Fillable | Relationships | Scopes | Accessors |
|-------|----------|---------------|--------|-----------|
| ChatRoom | 12 | 4 | 6 | 3 |
| ChatMessage | 11 | 5 | 8 | 5 |
| MessageReaction | 3 | 2 | 4 | 2 |
| **Total** | **26** | **11** | **18** | **10** |

---

## 🚀 Common Tasks

### Create a chat room
```php
$room = ChatRoom::create([
    'name' => 'Room Name',
    'type' => 'general',
    'created_by' => auth()->id(),
]);
```
→ See: CHAT_MODELS_USAGE_GUIDE.md → ChatRoom → Common Operations

### Send a message
```php
$message = ChatMessage::create([
    'chat_room_id' => $roomId,
    'user_id' => auth()->id(),
    'content' => 'Message text',
]);
```
→ See: CHAT_MODELS_USAGE_GUIDE.md → ChatMessage → Common Operations

### Reply to a message
```php
$reply = ChatMessage::create([
    'chat_room_id' => $roomId,
    'user_id' => auth()->id(),
    'content' => 'Reply text',
    'reply_to_id' => $messageId,
]);
```
→ See: CHAT_MODELS_USAGE_GUIDE.md → ChatMessage → Reply

### Add reaction
```php
MessageReaction::create([
    'chat_message_id' => $messageId,
    'user_id' => auth()->id(),
    'reaction' => '👍',
]);
```
→ See: CHAT_MODELS_USAGE_GUIDE.md → MessageReaction → Common Operations

### Get user's rooms
```php
$rooms = auth()->user()->chatRooms()->get();
```
→ See: CHAT_MODELS_QUICK_REFERENCE.md → User Model

### Get recent messages
```php
$messages = ChatMessage::inRoom($roomId)
    ->with('user', 'reactions')
    ->latest()
    ->paginate(50);
```
→ See: CHAT_MODELS_QUICK_REFERENCE.md → Common Queries

---

## 📚 Documentation Files

| File | Purpose | Read Time |
|------|---------|-----------|
| **CHAT_MODELS_DELIVERY.md** | Summary & overview | 5 min |
| **CHAT_MODELS_USAGE_GUIDE.md** | Comprehensive guide | 20 min |
| **CHAT_MODELS_QUICK_REFERENCE.md** | Quick lookup | 5 min |
| **CHAT_MODELS_SUMMARY.md** | Detailed info | 15 min |
| **CHAT_MODELS_INDEX.md** | This file | 5 min |

---

## 🔄 Next Steps

1. **Read** CHAT_MODELS_DELIVERY.md (overview)
2. **Study** CHAT_MODELS_USAGE_GUIDE.md (detailed)
3. **Reference** CHAT_MODELS_QUICK_REFERENCE.md (lookup)
4. **Create Controllers** (next phase)
5. **Create Routes** (next phase)
6. **Create Views** (next phase)

---

## 🎯 By Role

### For Developers
→ Start with: CHAT_MODELS_USAGE_GUIDE.md
→ Reference: CHAT_MODELS_QUICK_REFERENCE.md

### For Architects
→ Start with: CHAT_MODELS_SUMMARY.md
→ Reference: CHAT_MODELS_DELIVERY.md

### For Quick Lookup
→ Use: CHAT_MODELS_QUICK_REFERENCE.md

### For Learning
→ Start with: CHAT_MODELS_DELIVERY.md
→ Then: CHAT_MODELS_USAGE_GUIDE.md

---

## 📁 File Locations

```
app/Models/
├── ChatRoom.php              ✅ Created
├── ChatMessage.php           ✅ Updated
├── MessageReaction.php       ✅ Created
├── User.php                  ✅ Updated
└── Course.php                ✅ Updated

Documentation/
├── CHAT_MODELS_INDEX.md              (this file)
├── CHAT_MODELS_DELIVERY.md           (summary)
├── CHAT_MODELS_USAGE_GUIDE.md        (comprehensive)
├── CHAT_MODELS_QUICK_REFERENCE.md    (quick lookup)
└── CHAT_MODELS_SUMMARY.md            (detailed)
```

---

## ✅ Verification

All models have been:
- ✅ Created with proper structure
- ✅ Tested for syntax errors
- ✅ Configured with relationships
- ✅ Documented with examples
- ✅ Ready for use in controllers

---

## 🎉 You're Ready!

All Eloquent models for the chat system are complete and documented.

**Next:** Read CHAT_MODELS_DELIVERY.md

---

*Complete Eloquent models for Laravel group chat system*
*Production-ready with comprehensive documentation*
*Ready for controller and route implementation*


