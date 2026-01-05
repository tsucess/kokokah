# Course ChatRoom - Model Relationships

Understanding the relationships between Course, ChatRoom, and User models.

---

## 📊 Model Relationships

### Course Model

```php
class Course extends Model
{
    // Existing relationships
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // NEW: Relationship to ChatRoom
    public function chatRoom()
    {
        return $this->hasOne(ChatRoom::class);
    }
}
```

### ChatRoom Model (Assumed)

```php
class ChatRoom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'course_id',
        'created_by',
        'background_image',
        'color',
        'is_active',
        'member_count',
        'message_count',
        'last_message_at',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_room_users')
            ->withPivot('role', 'is_active', 'is_muted', 'joined_at')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
```

### User Model

```php
class User extends Model
{
    // Existing relationships
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // NEW: Relationship to ChatRooms
    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class, 'chat_room_users')
            ->withPivot('role', 'is_active', 'is_muted', 'joined_at')
            ->withTimestamps();
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
```

### Enrollment Model

```php
class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'progress',
        'status',
        'enrolled_at',
        'completed_at',
        'amount_paid',
    ];

    // Existing relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
```

---

## 🔗 Relationship Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                         COURSE                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ id, title, instructor_id, description, ...          │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
         │                                    │
         │ hasOne                             │ hasMany
         │                                    │
         ▼                                    ▼
┌─────────────────────────────────────────────────────────────┐
│                      CHATROOM                               │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ id, name, type, course_id, created_by, ...          │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
         │
         │ belongsToMany (through chat_room_users)
         │
         ▼
┌─────────────────────────────────────────────────────────────┐
│                         USER                                │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ id, name, email, ...                                │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
         │
         │ hasMany
         │
         ▼
┌─────────────────────────────────────────────────────────────┐
│                     ENROLLMENT                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ id, user_id, course_id, status, ...                 │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

---

## 📋 Pivot Table: chat_room_users

```sql
CREATE TABLE chat_room_users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    chat_room_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    role VARCHAR(50) DEFAULT 'member',  -- 'admin', 'moderator', 'member'
    is_active BOOLEAN DEFAULT TRUE,
    is_muted BOOLEAN DEFAULT FALSE,
    joined_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (chat_room_id) REFERENCES chat_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_room (chat_room_id, user_id)
);
```

---

## 🔄 Query Examples

### Get Course with Chat Room

```php
$course = Course::with('chatRoom')->find(1);

echo $course->chatRoom->name;
echo $course->chatRoom->member_count;
```

### Get Chat Room with Users

```php
$chatRoom = ChatRoom::with('users')->find(1);

foreach ($chatRoom->users as $user) {
    echo $user->name;
    echo $user->pivot->role;      // 'admin' or 'member'
    echo $user->pivot->is_muted;  // true or false
}
```

### Get User's Chat Rooms

```php
$user = User::with('chatRooms')->find(1);

foreach ($user->chatRooms as $room) {
    echo $room->name;
    echo $room->pivot->role;
}
```

### Get Course Instructor

```php
$course = Course::find(1);
$instructor = $course->instructor;

// Or through chat room
$chatRoom = $course->chatRoom;
$instructor = $chatRoom->users()
    ->where('role', 'admin')
    ->first();
```

### Get Course Members

```php
$course = Course::find(1);
$chatRoom = $course->chatRoom;

// Get all members
$members = $chatRoom->users()
    ->where('role', 'member')
    ->get();

// Get active members
$activeMembers = $chatRoom->users()
    ->where('role', 'member')
    ->where('is_active', true)
    ->get();

// Get muted members
$mutedMembers = $chatRoom->users()
    ->where('is_muted', true)
    ->get();
```

### Get Enrolled Students

```php
$course = Course::find(1);

// Get enrolled students
$students = $course->enrollments()
    ->where('status', 'active')
    ->with('user')
    ->get();

// Get students in chat room
$chatRoom = $course->chatRoom;
$studentsInChat = $chatRoom->users()
    ->where('role', 'member')
    ->get();
```

---

## 🎯 Common Queries

### Check if User is in Chat Room

```php
$chatRoom = ChatRoom::find(1);
$userId = 5;

$isInRoom = $chatRoom->users()
    ->where('user_id', $userId)
    ->exists();

if ($isInRoom) {
    echo "User is in chat room";
}
```

### Check User's Role in Chat Room

```php
$chatRoom = ChatRoom::find(1);
$userId = 5;

$role = $chatRoom->users()
    ->where('user_id', $userId)
    ->value('role');

echo $role;  // 'admin', 'moderator', or 'member'
```

### Get User's Role in Course Chat Room

```php
$course = Course::find(1);
$userId = 5;

$role = $course->chatRoom->users()
    ->where('user_id', $userId)
    ->value('role');

if ($role === 'admin') {
    echo "User is instructor";
} else {
    echo "User is student";
}
```

### Count Members in Chat Room

```php
$chatRoom = ChatRoom::find(1);

$memberCount = $chatRoom->users()
    ->where('role', 'member')
    ->count();

echo "Members: " . $memberCount;
```

### Get Chat Room Statistics

```php
$chatRoom = ChatRoom::find(1);

$stats = [
    'total_users' => $chatRoom->users()->count(),
    'admins' => $chatRoom->users()->where('role', 'admin')->count(),
    'members' => $chatRoom->users()->where('role', 'member')->count(),
    'active_users' => $chatRoom->users()->where('is_active', true)->count(),
    'muted_users' => $chatRoom->users()->where('is_muted', true)->count(),
    'messages' => $chatRoom->message_count,
    'last_message' => $chatRoom->last_message_at,
];

dd($stats);
```

---

## 🔐 Authorization Examples

### Check if User Can Access Chat Room

```php
public function canAccessChatRoom(User $user, ChatRoom $chatRoom)
{
    return $chatRoom->users()
        ->where('user_id', $user->id)
        ->where('is_active', true)
        ->exists();
}
```

### Check if User Can Send Messages

```php
public function canSendMessage(User $user, ChatRoom $chatRoom)
{
    $userInRoom = $chatRoom->users()
        ->where('user_id', $user->id)
        ->first();

    if (!$userInRoom) {
        return false;
    }

    return !$userInRoom->pivot->is_muted;
}
```

### Check if User is Admin

```php
public function isAdmin(User $user, ChatRoom $chatRoom)
{
    return $chatRoom->users()
        ->where('user_id', $user->id)
        ->where('role', 'admin')
        ->exists();
}
```

---

## 📚 Migration Example

If you need to create the ChatRoom table:

```php
Schema::create('chat_rooms', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->enum('type', ['course', 'group', 'direct'])->default('group');
    $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
    $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
    $table->string('background_image')->nullable();
    $table->string('color')->default('#007bff');
    $table->boolean('is_active')->default(true);
    $table->integer('member_count')->default(0);
    $table->integer('message_count')->default(0);
    $table->timestamp('last_message_at')->nullable();
    $table->softDeletes();
    $table->timestamps();
});
```

---

*Course ChatRoom - Model Relationships Guide*


