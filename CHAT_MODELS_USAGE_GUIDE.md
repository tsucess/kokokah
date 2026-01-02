# Chat System Models - Usage Guide

Complete guide for using the ChatRoom, ChatMessage, and MessageReaction Eloquent models.

---

## 📋 Models Created

1. **ChatRoom** - `app/Models/ChatRoom.php`
2. **ChatMessage** - `app/Models/ChatMessage.php` (updated)
3. **MessageReaction** - `app/Models/MessageReaction.php`

---

## 🎯 ChatRoom Model

### Fillable Fields
```php
protected $fillable = [
    'name',
    'description',
    'type',                // 'general' or 'course'
    'course_id',
    'created_by',
    'background_image',
    'icon',
    'color',
    'is_active',
    'is_archived',
    'member_count',
    'message_count',
    'last_message_at',
];
```

### Relationships

#### Get the creator
```php
$room = ChatRoom::find(1);
$creator = $room->creator;  // User who created the room
```

#### Get the associated course
```php
$room = ChatRoom::find(1);
$course = $room->course;  // Course (if type='course')
```

#### Get all users in the room
```php
$room = ChatRoom::find(1);
$users = $room->users;  // Collection of User models with pivot data

// Access pivot data
foreach ($room->users as $user) {
    echo $user->pivot->role;  // 'member', 'moderator', 'admin'
    echo $user->pivot->is_muted;
    echo $user->pivot->unread_count;
}
```

#### Get all messages
```php
$room = ChatRoom::find(1);
$messages = $room->messages;  // Collection of ChatMessage models
$recentMessages = $room->messages()->latest()->paginate(50);
```

### Scopes

#### Get general rooms
```php
$generalRooms = ChatRoom::generalRooms()->get();
```

#### Get course-specific rooms
```php
$courseRooms = ChatRoom::courseRooms()->get();
```

#### Get active rooms
```php
$activeRooms = ChatRoom::active()->get();
```

#### Get non-archived rooms
```php
$activeRooms = ChatRoom::notArchived()->get();
```

#### Get rooms with recent activity
```php
$recentRooms = ChatRoom::withRecentActivity(24)->get();  // Last 24 hours
```

#### Get rooms for a specific user
```php
$userRooms = ChatRoom::forUser(auth()->id())->get();
```

#### Combine scopes
```php
$rooms = ChatRoom::generalRooms()
                  ->active()
                  ->notArchived()
                  ->withRecentActivity(48)
                  ->get();
```

### Accessors

#### Get background image URL
```php
$room = ChatRoom::find(1);
$imageUrl = $room->background_image_url;  // Full URL or null
```

#### Check room type
```php
$room = ChatRoom::find(1);
if ($room->is_general) {
    // This is a general room
}

if ($room->is_course) {
    // This is a course-specific room
}
```

### Common Operations

#### Create a general room
```php
$room = ChatRoom::create([
    'name' => 'General Discussion',
    'description' => 'General chat for all users',
    'type' => 'general',
    'created_by' => auth()->id(),
    'color' => '#007bff',
]);
```

#### Create a course room
```php
$room = ChatRoom::create([
    'name' => 'Course Discussion',
    'description' => 'Discussion for Course XYZ',
    'type' => 'course',
    'course_id' => $courseId,
    'created_by' => auth()->id(),
]);
```

#### Add user to room
```php
$room = ChatRoom::find(1);
$room->users()->attach($userId, [
    'role' => 'member',
    'joined_at' => now(),
]);
```

#### Update user role
```php
$room = ChatRoom::find(1);
$room->users()->updateExistingPivot($userId, [
    'role' => 'moderator',
]);
```

#### Remove user from room
```php
$room = ChatRoom::find(1);
$room->users()->detach($userId);
```

#### Get room with all data
```php
$room = ChatRoom::with('creator', 'course', 'users', 'messages')
                 ->find(1);
```

---

## 💬 ChatMessage Model

### Fillable Fields
```php
protected $fillable = [
    'chat_room_id',
    'user_id',
    'content',
    'type',              // 'text', 'image', 'file', 'system'
    'reply_to_id',
    'edited_content',
    'edited_at',
    'reaction_count',
    'is_pinned',
    'is_deleted',
    'metadata',          // JSON
];
```

### Relationships

#### Get the chat room
```php
$message = ChatMessage::find(1);
$room = $message->chatRoom;
```

#### Get the sender
```php
$message = ChatMessage::find(1);
$user = $message->user;
```

#### Get the message being replied to
```php
$message = ChatMessage::find(1);
if ($message->replyTo) {
    $originalMessage = $message->replyTo;
}
```

#### Get all replies to this message
```php
$message = ChatMessage::find(1);
$replies = $message->replies;  // Messages replying to this one
```

#### Get reactions on this message
```php
$message = ChatMessage::find(1);
$reactions = $message->reactions;  // Collection of MessageReaction models
```

### Scopes

#### Get messages from a room
```php
$messages = ChatMessage::inRoom($roomId)->get();
```

#### Get messages from a user
```php
$messages = ChatMessage::fromUser($userId)->get();
```

#### Get text messages only
```php
$textMessages = ChatMessage::textMessages()->get();
```

#### Get image messages
```php
$images = ChatMessage::imageMessages()->get();
```

#### Get file messages
```php
$files = ChatMessage::fileMessages()->get();
```

#### Get system messages
```php
$systemMessages = ChatMessage::systemMessages()->get();
```

#### Get pinned messages
```php
$pinnedMessages = ChatMessage::pinned()->get();
```

#### Get recent messages
```php
$recent = ChatMessage::recent(24)->get();  // Last 24 hours
```

#### Get edited messages
```php
$edited = ChatMessage::edited()->get();
```

#### Get messages with replies
```php
$threaded = ChatMessage::withReplies()->get();
```

### Accessors

#### Check if edited
```php
$message = ChatMessage::find(1);
if ($message->is_edited) {
    echo "This message was edited";
}
```

#### Check message type
```php
$message = ChatMessage::find(1);
if ($message->is_text) { }
if ($message->is_image) { }
if ($message->is_file) { }
if ($message->is_system) { }
```

### Methods

#### Get display content
```php
$message = ChatMessage::find(1);
$content = $message->getDisplayContent();  // Edited or original
```

#### Get formatted time
```php
$message = ChatMessage::find(1);
echo $message->getFormattedTime();      // "14:30"
echo $message->getFormattedDateTime();  // "Dec 30, 2025 14:30"
```

### Common Operations

#### Send a message
```php
$message = ChatMessage::create([
    'chat_room_id' => $roomId,
    'user_id' => auth()->id(),
    'content' => 'Hello everyone!',
    'type' => 'text',
]);
```

#### Reply to a message
```php
$message = ChatMessage::create([
    'chat_room_id' => $roomId,
    'user_id' => auth()->id(),
    'content' => 'I agree!',
    'type' => 'text',
    'reply_to_id' => $originalMessageId,
]);
```

#### Edit a message
```php
$message = ChatMessage::find(1);
$message->update([
    'edited_content' => 'Updated content',
    'edited_at' => now(),
]);
```

#### Pin a message
```php
$message = ChatMessage::find(1);
$message->update(['is_pinned' => true]);
```

#### Delete a message (soft delete)
```php
$message = ChatMessage::find(1);
$message->delete();  // Soft delete

// Restore
$message->restore();

// Force delete
$message->forceDelete();
```

#### Get paginated messages
```php
$messages = ChatMessage::inRoom($roomId)
                        ->latest()
                        ->paginate(50);
```

---

## 😊 MessageReaction Model

### Fillable Fields
```php
protected $fillable = [
    'chat_message_id',
    'user_id',
    'reaction',  // Emoji string like '👍', '❤️', etc.
];
```

### Relationships

#### Get the message
```php
$reaction = MessageReaction::find(1);
$message = $reaction->message;
```

#### Get the user
```php
$reaction = MessageReaction::find(1);
$user = $reaction->user;
```

### Scopes

#### Get reactions for a message
```php
$reactions = MessageReaction::forMessage($messageId)->get();
```

#### Get reactions from a user
```php
$reactions = MessageReaction::fromUser($userId)->get();
```

#### Get specific reaction type
```php
$likes = MessageReaction::withReaction('👍')->get();
```

#### Get grouped by reaction
```php
$grouped = MessageReaction::forMessage($messageId)
                           ->groupedByReaction()
                           ->get();
// Result: [
//   ['reaction' => '👍', 'count' => 5],
//   ['reaction' => '❤️', 'count' => 3],
// ]
```

### Methods

#### Get emoji
```php
$reaction = MessageReaction::find(1);
echo $reaction->emoji;  // '👍'
```

#### Get reaction name
```php
$reaction = MessageReaction::find(1);
echo $reaction->reaction_name;  // 'Like'
```

### Common Operations

#### Add reaction
```php
$reaction = MessageReaction::create([
    'chat_message_id' => $messageId,
    'user_id' => auth()->id(),
    'reaction' => '👍',
]);
```

#### Remove reaction
```php
MessageReaction::where('chat_message_id', $messageId)
                ->where('user_id', auth()->id())
                ->where('reaction', '👍')
                ->delete();
```

#### Get reaction summary
```php
$reactions = MessageReaction::forMessage($messageId)
                             ->groupedByReaction()
                             ->get();

foreach ($reactions as $reaction) {
    echo "{$reaction->reaction} ({$reaction->count})";
}
```

---

## 🔗 Relationships Summary

```
User (1) ──────────────────────── (Many) ChatRoom
  │                                    │
  │ (Many)                             │ (Many)
  │                                    │
  └─ ChatRoomUser ◄──────────────────┘

Course (1) ──────────────────── (1) ChatRoom

ChatRoom (1) ──────────────────── (Many) ChatMessage
  │                                    │
  │                                    │ (Many)
  │                                    │
  │                                MessageReaction
  │                                    │
  │                                    └─ reaction (emoji)
  │
  └─ User (1) ◄──────────────────────┘
```

---

## 📝 Usage Examples

### Get all chat rooms for current user
```php
$rooms = auth()->user()->chatRooms()->get();
```

### Get unread messages in a room
```php
$room = ChatRoom::find(1);
$unreadCount = $room->users()
                    ->where('user_id', auth()->id())
                    ->first()
                    ->pivot
                    ->unread_count;
```

### Get recent messages with reactions
```php
$messages = ChatMessage::inRoom($roomId)
                        ->with('reactions', 'user')
                        ->latest()
                        ->paginate(50);
```

### Get all reactions on a message
```php
$message = ChatMessage::find(1);
$reactions = $message->reactions()
                      ->with('user')
                      ->get();
```

### Create a course chat room
```php
$course = Course::find(1);
$room = ChatRoom::create([
    'name' => $course->title . ' Discussion',
    'description' => 'Discussion for ' . $course->title,
    'type' => 'course',
    'course_id' => $course->id,
    'created_by' => auth()->id(),
]);

// Add all enrolled students
foreach ($course->students as $student) {
    $room->users()->attach($student->id, ['role' => 'member']);
}
```

---

## ✅ Models Updated

- ✅ **ChatRoom** - Created with all relationships and scopes
- ✅ **ChatMessage** - Updated with new schema relationships
- ✅ **MessageReaction** - Created with relationships
- ✅ **User** - Added chat relationships
- ✅ **Course** - Added chat room relationship

---

## 🚀 Next Steps

1. Create controllers for chat operations
2. Create routes for chat endpoints
3. Create views/components for chat UI
4. Add broadcasting for real-time updates
5. Add authorization policies

---

*Complete Eloquent models for Laravel group chat system*


