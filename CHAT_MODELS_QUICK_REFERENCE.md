# Chat Models - Quick Reference

Fast lookup for ChatRoom, ChatMessage, and MessageReaction models.

---

## 📦 ChatRoom Model

### Create
```php
ChatRoom::create([
    'name' => 'Room Name',
    'type' => 'general',  // or 'course'
    'created_by' => auth()->id(),
]);
```

### Query
```php
ChatRoom::generalRooms()->get();
ChatRoom::courseRooms()->get();
ChatRoom::active()->get();
ChatRoom::forUser(auth()->id())->get();
ChatRoom::with('users', 'messages')->find(1);
```

### Relationships
```php
$room->creator;      // User who created
$room->course;       // Associated Course
$room->users;        // All users in room
$room->messages;     // All messages
```

### Accessors
```php
$room->background_image_url;  // Full URL
$room->is_general;             // Boolean
$room->is_course;              // Boolean
```

### Add User
```php
$room->users()->attach($userId, ['role' => 'member']);
```

---

## 💬 ChatMessage Model

### Create
```php
ChatMessage::create([
    'chat_room_id' => $roomId,
    'user_id' => auth()->id(),
    'content' => 'Message text',
    'type' => 'text',  // text, image, file, system
]);
```

### Reply
```php
ChatMessage::create([
    'chat_room_id' => $roomId,
    'user_id' => auth()->id(),
    'content' => 'Reply text',
    'reply_to_id' => $originalMessageId,
]);
```

### Query
```php
ChatMessage::inRoom($roomId)->get();
ChatMessage::fromUser($userId)->get();
ChatMessage::textMessages()->get();
ChatMessage::pinned()->get();
ChatMessage::recent(24)->get();
ChatMessage::with('user', 'reactions')->latest()->paginate(50);
```

### Edit
```php
$message->update([
    'edited_content' => 'New content',
    'edited_at' => now(),
]);
```

### Pin
```php
$message->update(['is_pinned' => true]);
```

### Delete
```php
$message->delete();      // Soft delete
$message->restore();     // Restore
$message->forceDelete(); // Permanent
```

### Relationships
```php
$message->chatRoom;   // ChatRoom
$message->user;       // User who sent
$message->replyTo;    // Original message (if reply)
$message->replies;    // Messages replying to this
$message->reactions;  // MessageReaction collection
```

### Accessors
```php
$message->is_edited;   // Boolean
$message->is_text;     // Boolean
$message->is_image;    // Boolean
$message->is_file;     // Boolean
$message->is_system;   // Boolean
```

### Methods
```php
$message->getDisplayContent();   // Edited or original
$message->getFormattedTime();    // "14:30"
$message->getFormattedDateTime();// "Dec 30, 2025 14:30"
```

---

## 😊 MessageReaction Model

### Create
```php
MessageReaction::create([
    'chat_message_id' => $messageId,
    'user_id' => auth()->id(),
    'reaction' => '👍',
]);
```

### Remove
```php
MessageReaction::where('chat_message_id', $messageId)
                ->where('user_id', auth()->id())
                ->where('reaction', '👍')
                ->delete();
```

### Query
```php
MessageReaction::forMessage($messageId)->get();
MessageReaction::fromUser($userId)->get();
MessageReaction::withReaction('👍')->get();
MessageReaction::forMessage($messageId)
                ->groupedByReaction()
                ->get();
```

### Relationships
```php
$reaction->message;  // ChatMessage
$reaction->user;     // User who reacted
```

### Accessors
```php
$reaction->emoji;           // '👍'
$reaction->reaction_name;   // 'Like'
```

---

## 🔗 User Model (Updated)

### Chat Relationships
```php
auth()->user()->chatRooms();        // All rooms user is in
auth()->user()->createdChatRooms(); // Rooms user created
auth()->user()->chatMessages();     // All messages sent
auth()->user()->messageReactions(); // All reactions added
```

---

## 🔗 Course Model (Updated)

### Chat Relationship
```php
$course->chatRoom;  // Associated ChatRoom (if exists)
```

---

## 📊 Common Queries

### Get user's rooms with unread count
```php
$rooms = auth()->user()
    ->chatRooms()
    ->with('messages')
    ->get()
    ->map(function ($room) {
        return [
            'room' => $room,
            'unread' => $room->users()
                ->where('user_id', auth()->id())
                ->first()
                ->pivot
                ->unread_count,
        ];
    });
```

### Get recent messages with reactions
```php
$messages = ChatMessage::inRoom($roomId)
    ->with('user', 'reactions.user')
    ->latest()
    ->paginate(50);
```

### Get reaction summary for message
```php
$reactions = ChatMessage::find($messageId)
    ->reactions()
    ->groupBy('reaction')
    ->selectRaw('reaction, COUNT(*) as count')
    ->get();
```

### Get all messages from user in room
```php
$messages = ChatMessage::inRoom($roomId)
    ->fromUser(auth()->id())
    ->latest()
    ->get();
```

### Get pinned messages in room
```php
$pinned = ChatMessage::inRoom($roomId)
    ->pinned()
    ->with('user')
    ->get();
```

### Get threaded messages
```php
$threaded = ChatMessage::inRoom($roomId)
    ->withReplies()
    ->with('replies.user')
    ->get();
```

---

## 🎯 Fillable Fields

### ChatRoom
```
name, description, type, course_id, created_by,
background_image, icon, color, is_active, is_archived,
member_count, message_count, last_message_at
```

### ChatMessage
```
chat_room_id, user_id, content, type, reply_to_id,
edited_content, edited_at, reaction_count, is_pinned,
is_deleted, metadata
```

### MessageReaction
```
chat_message_id, user_id, reaction
```

---

## 🔐 Soft Deletes

Models with soft deletes:
- ChatRoom
- ChatMessage

Models without soft deletes:
- MessageReaction

---

## 📝 Casts

### ChatRoom
```
is_active => boolean
is_archived => boolean
last_message_at => datetime
```

### ChatMessage
```
is_pinned => boolean
is_deleted => boolean
edited_at => datetime
metadata => array
```

---

## 🚀 Quick Start

```php
// Create a room
$room = ChatRoom::create([
    'name' => 'General',
    'type' => 'general',
    'created_by' => auth()->id(),
]);

// Add user
$room->users()->attach(auth()->id(), ['role' => 'member']);

// Send message
$message = ChatMessage::create([
    'chat_room_id' => $room->id,
    'user_id' => auth()->id(),
    'content' => 'Hello!',
]);

// Add reaction
MessageReaction::create([
    'chat_message_id' => $message->id,
    'user_id' => auth()->id(),
    'reaction' => '👍',
]);

// Get messages
$messages = $room->messages()->with('user', 'reactions')->latest()->paginate(50);
```

---

*Quick reference for Chat Eloquent models*


