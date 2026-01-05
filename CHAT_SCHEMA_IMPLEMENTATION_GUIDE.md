# Chat Schema - Implementation Guide

## 🚀 Quick Start

### 1. Run Migrations
```bash
php artisan migrate
```

This creates 4 tables:
- `chat_rooms` - Chat room metadata
- `chat_room_users` - User membership (pivot)
- `chat_messages` - Message content
- `message_reactions` - Emoji reactions

### 2. Create Models
Models should be created in `app/Models/`:
- `ChatRoom.php`
- `ChatMessage.php`
- `MessageReaction.php`

### 3. Define Relationships
See relationships section below.

---

## 📝 Model Examples

### ChatRoom Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ChatRoom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'type', 'course_id',
        'created_by', 'background_image', 'icon', 'color',
        'is_active', 'is_archived'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_archived' => 'boolean',
        'last_message_at' => 'datetime',
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_room_users')
                    ->withPivot('role', 'is_active', 'is_muted', 
                               'joined_at', 'last_read_at', 'unread_count')
                    ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }
}
```

### ChatMessage Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'chat_room_id', 'user_id', 'content', 'type',
        'reply_to_id', 'edited_content', 'edited_at',
        'is_pinned', 'is_deleted', 'metadata'
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_deleted' => 'boolean',
        'edited_at' => 'datetime',
        'metadata' => 'json',
    ];

    // Relationships
    public function room(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(MessageReaction::class, 'chat_message_id');
    }

    public function parentMessage(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'reply_to_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'reply_to_id');
    }
}
```

### MessageReaction Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageReaction extends Model
{
    protected $fillable = ['chat_message_id', 'user_id', 'reaction'];

    public $timestamps = true;

    // Relationships
    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'chat_message_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## 🔗 Update User Model

Add these relationships to `app/Models/User.php`:

```php
public function chatRooms()
{
    return $this->belongsToMany(ChatRoom::class, 'chat_room_users')
                ->withPivot('role', 'is_active', 'is_muted', 
                           'joined_at', 'last_read_at', 'unread_count')
                ->withTimestamps();
}

public function chatMessages()
{
    return $this->hasMany(ChatMessage::class);
}

public function messageReactions()
{
    return $this->hasMany(MessageReaction::class);
}

public function createdChatRooms()
{
    return $this->hasMany(ChatRoom::class, 'created_by');
}
```

---

## 🔗 Update Course Model

Add this relationship to `app/Models/Course.php`:

```php
public function chatRoom()
{
    return $this->hasOne(ChatRoom::class);
}
```

---

## 📊 Common Operations

### Create a General Chat Room
```php
$room = ChatRoom::create([
    'name' => 'General Chat',
    'description' => 'General discussion for all users',
    'type' => 'general',
    'created_by' => auth()->id(),
    'is_active' => true,
]);
```

### Create a Course Chat Room
```php
$room = ChatRoom::create([
    'name' => $course->title . ' Chat',
    'description' => 'Discussion for ' . $course->title,
    'type' => 'course',
    'course_id' => $course->id,
    'created_by' => $course->instructor_id,
    'is_active' => true,
]);
```

### Add User to Room
```php
$room->users()->attach($user->id, [
    'role' => 'member',
    'joined_at' => now(),
    'notification_level' => 'all',
]);
```

### Send Message
```php
$message = ChatMessage::create([
    'chat_room_id' => $room->id,
    'user_id' => auth()->id(),
    'content' => 'Hello everyone!',
    'type' => 'text',
]);

// Update room metadata
$room->update([
    'message_count' => $room->messages()->count(),
    'last_message_at' => now(),
]);
```

### Add Reaction
```php
MessageReaction::create([
    'chat_message_id' => $message->id,
    'user_id' => auth()->id(),
    'reaction' => '👍',
]);

// Update message reaction count
$message->update([
    'reaction_count' => $message->reactions()->count(),
]);
```

### Get User's Rooms
```php
$rooms = auth()->user()->chatRooms()
    ->where('is_active', true)
    ->orderBy('updated_at', 'desc')
    ->get();
```

### Get Room Messages
```php
$messages = $room->messages()
    ->where('is_deleted', false)
    ->orderBy('created_at', 'desc')
    ->paginate(50);
```

### Get Unread Messages
```php
$unread = auth()->user()->chatRoomUsers()
    ->where('unread_count', '>', 0)
    ->with('chatRoom')
    ->get();
```

### Mark Messages as Read
```php
$membership = auth()->user()->chatRoomUsers()
    ->where('chat_room_id', $room->id)
    ->first();

$membership->update([
    'last_read_at' => now(),
    'unread_count' => 0,
]);
```

---

## 🔐 Authorization Examples

### Check if user can view room
```php
$canView = auth()->user()->chatRoomUsers()
    ->where('chat_room_id', $room->id)
    ->where('is_active', true)
    ->exists();
```

### Check if user can send message
```php
$canSend = auth()->user()->chatRoomUsers()
    ->where('chat_room_id', $room->id)
    ->where('is_active', true)
    ->where('is_muted', false)
    ->exists();
```

### Check if user can edit message
```php
$canEdit = $message->user_id === auth()->id() ||
    auth()->user()->chatRoomUsers()
        ->where('chat_room_id', $message->chat_room_id)
        ->whereIn('role', ['moderator', 'admin'])
        ->exists();
```

### Check if user can delete message
```php
$canDelete = $message->user_id === auth()->id() ||
    auth()->user()->chatRoomUsers()
        ->where('chat_room_id', $message->chat_room_id)
        ->whereIn('role', ['moderator', 'admin'])
        ->exists();
```

---

## 🎯 Seeding Data

Create `database/seeders/ChatRoomSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatRoomSeeder extends Seeder
{
    public function run(): void
    {
        // Create general chat room
        $general = ChatRoom::create([
            'name' => 'General Chat',
            'description' => 'General discussion for all users',
            'type' => 'general',
            'created_by' => User::where('role', 'admin')->first()->id,
            'is_active' => true,
        ]);

        // Add all users to general room
        User::all()->each(function ($user) use ($general) {
            $general->users()->attach($user->id, [
                'role' => 'member',
                'joined_at' => now(),
            ]);
        });
    }
}
```

Run seeder:
```bash
php artisan db:seed --class=ChatRoomSeeder
```

---

## 📈 Performance Optimization

### 1. Eager Load Relationships
```php
$rooms = ChatRoom::with('users', 'messages')->get();
```

### 2. Use Pagination
```php
$messages = $room->messages()->paginate(50);
```

### 3. Cache Member Lists
```php
$members = Cache::remember("room.{$room->id}.members", 3600, fn() =>
    $room->users()->get()
);
```

### 4. Use Denormalized Counts
```php
// Instead of: $room->messages()->count()
// Use: $room->message_count
```

### 5. Index Frequently Queried Columns
Already done in migrations!

---

## ✅ Implementation Checklist

- [ ] Run migrations: `php artisan migrate`
- [ ] Create ChatRoom model
- [ ] Create ChatMessage model
- [ ] Create MessageReaction model
- [ ] Update User model with relationships
- [ ] Update Course model with relationship
- [ ] Create ChatRoomSeeder
- [ ] Seed general chat room
- [ ] Test model relationships
- [ ] Test common operations
- [ ] Test authorization checks
- [ ] Create controllers (next step)
- [ ] Create API routes (next step)
- [ ] Create views (next step)

---

## 🔄 Next Steps

1. **Create Models** - Use examples above
2. **Create Controllers** - Handle CRUD operations
3. **Create Routes** - API endpoints
4. **Create Views** - Blade templates
5. **Add Broadcasting** - Real-time updates
6. **Add Tests** - Unit and feature tests

---

## 📞 Troubleshooting

### Migration fails
- Check if tables already exist
- Run `php artisan migrate:rollback` first
- Check database connection

### Relationship errors
- Verify model names match table names
- Check foreign key column names
- Verify relationships are defined correctly

### Query errors
- Use `->toSql()` to debug queries
- Check indexes are created
- Use eager loading to prevent N+1

---

## 📚 Related Documentation

- **CHAT_DATABASE_SCHEMA.md** - Detailed schema documentation
- **CHAT_SCHEMA_QUICK_REFERENCE.md** - Quick reference guide
- **database/migrations/** - Migration files


