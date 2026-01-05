# Course ChatRoom Automation - Implementation Details

Complete implementation guide with code examples and explanations.

---

## 📋 What Was Created

### 1. CourseObserver (`app/Observers/CourseObserver.php`)

Listens to course lifecycle events:

**Events Handled:**
- `created()` - Create chat room when course is created
- `updated()` - Update chat room when course is updated
- `deleted()` - Soft delete chat room when course is deleted
- `restored()` - Restore chat room when course is restored
- `forceDeleted()` - Permanently delete chat room

**Key Features:**
- Creates chat room with course title
- Attaches instructor as admin
- Attaches enrolled students as members
- Updates chat room when course title changes
- Handles soft deletes and restores

### 2. EnrollmentObserver (`app/Observers/EnrollmentObserver.php`)

Listens to enrollment lifecycle events:

**Events Handled:**
- `created()` - Add student to chat room when enrolled
- `updated()` - Update student status in chat room
- `deleted()` - Remove student from chat room
- `restored()` - Re-add student to chat room
- `forceDeleted()` - Permanently remove student

**Key Features:**
- Adds students to chat room when they enroll
- Removes students when enrollment is deleted
- Handles enrollment status changes
- Prevents duplicate entries

### 3. CourseCreated Event (`app/Events/CourseCreated.php`)

Event dispatched when a course is created.

**Usage:**
```php
CourseCreated::dispatch($course);
```

### 4. CreateCourseChatRoom Listener (`app/Listeners/CreateCourseChatRoom.php`)

Listens to CourseCreated event and creates chat room.

**Alternative to Observer:**
- Use if you prefer event-driven architecture
- Allows multiple listeners for same event
- Better for complex scenarios

### 5. AppServiceProvider (`app/Providers/AppServiceProvider.php`)

Registers observers:

```php
public function boot(): void
{
    Course::observe(CourseObserver::class);
    Enrollment::observe(EnrollmentObserver::class);
}
```

### 6. EventServiceProvider (`app/Providers/EventServiceProvider.php`)

Registers events and listeners:

```php
protected $listen = [
    CourseCreated::class => [
        CreateCourseChatRoom::class,
    ],
];
```

---

## 🔄 How It Works

### Course Creation Flow

```
1. Course::create() is called
   ↓
2. CourseObserver::created() is triggered
   ↓
3. ChatRoom is created with:
   - name = course title + " Discussion"
   - type = "course"
   - course_id = course id
   - created_by = instructor id
   - background_image = default image
   - is_active = true
   ↓
4. Instructor is attached as "admin"
   ↓
5. All active enrolled students are attached as "members"
   ↓
6. Chat room is ready to use!
```

### Student Enrollment Flow

```
1. Enrollment::create() is called
   ↓
2. EnrollmentObserver::created() is triggered
   ↓
3. Check if enrollment status is "active"
   ↓
4. Get course's chat room
   ↓
5. Check if student is already in room
   ↓
6. If not, attach student as "member"
   ↓
7. Student can now access chat room!
```

### Course Update Flow

```
1. Course::update() is called
   ↓
2. CourseObserver::updated() is triggered
   ↓
3. Check if title or description changed
   ↓
4. If changed, update chat room name and description
   ↓
5. Chat room is updated!
```

---

## 💻 Code Examples

### Example 1: Create a Course

```php
// In your CourseController
public function store(Request $request)
{
    $course = Course::create([
        'title' => 'Laravel Basics',
        'slug' => 'laravel-basics',
        'description' => 'Learn Laravel from scratch',
        'instructor_id' => auth()->id(),
        'status' => 'published',
    ]);

    // Chat room is automatically created!
    // No need to do anything else!

    return redirect()->route('courses.show', $course);
}
```

### Example 2: Get Course Chat Room

```php
$course = Course::find(1);
$chatRoom = $course->chatRoom;

if ($chatRoom) {
    echo "Chat room: " . $chatRoom->name;
    echo "Members: " . $chatRoom->member_count;
    echo "Messages: " . $chatRoom->message_count;
}
```

### Example 3: Get Chat Room Users

```php
$chatRoom = $course->chatRoom;

// Get all users
$users = $chatRoom->users;

// Get instructor
$instructor = $chatRoom->users()
    ->where('role', 'admin')
    ->first();

// Get members
$members = $chatRoom->users()
    ->where('role', 'member')
    ->get();

// Get muted users
$muted = $chatRoom->users()
    ->where('is_muted', true)
    ->get();
```

### Example 4: Send a Message

```php
$chatRoom = $course->chatRoom;

$message = ChatMessage::create([
    'chat_room_id' => $chatRoom->id,
    'user_id' => auth()->id(),
    'content' => 'Hello everyone!',
    'type' => 'text',
]);

// Update message count
$chatRoom->increment('message_count');
$chatRoom->update(['last_message_at' => now()]);
```

### Example 5: Add Reaction

```php
$message = ChatMessage::find(1);

MessageReaction::create([
    'chat_message_id' => $message->id,
    'user_id' => auth()->id(),
    'reaction' => '👍',
]);

// Update reaction count
$message->increment('reaction_count');
```

### Example 6: Enroll Student

```php
// In your EnrollmentController
public function store(Request $request)
{
    $enrollment = Enrollment::create([
        'user_id' => $request->user_id,
        'course_id' => $request->course_id,
        'status' => 'active',
    ]);

    // Student is automatically added to chat room!
    // No need to do anything else!

    return redirect()->route('enrollments.show', $enrollment);
}
```

### Example 7: Update Enrollment Status

```php
$enrollment = Enrollment::find(1);

// Change status to inactive
$enrollment->update(['status' => 'inactive']);

// Student is automatically deactivated in chat room!
// EnrollmentObserver::updated() handles this
```

---

## 🎯 Key Methods

### CourseObserver Methods

```php
// Create chat room
public function created(Course $course): void

// Update chat room
public function updated(Course $course): void

// Soft delete chat room
public function deleted(Course $course): void

// Restore chat room
public function restored(Course $course): void

// Permanently delete chat room
public function forceDeleted(Course $course): void
```

### EnrollmentObserver Methods

```php
// Add student to chat room
public function created(Enrollment $enrollment): void

// Update student status in chat room
public function updated(Enrollment $enrollment): void

// Remove student from chat room
public function deleted(Enrollment $enrollment): void

// Re-add student to chat room
public function restored(Enrollment $enrollment): void

// Permanently remove student
public function forceDeleted(Enrollment $enrollment): void
```

---

## 🔐 Important Notes

### Soft Deletes

- CourseObserver uses soft deletes
- Chat room can be restored if course is restored
- Use `forceDelete()` to permanently delete

### Enrollment Status

- Only active enrollments are added to chat room
- Inactive enrollments are deactivated in chat room
- Deleted enrollments are removed from chat room

### Duplicate Prevention

- EnrollmentObserver checks if user already exists
- Prevents duplicate entries in chat_room_users

### Performance

- Observers run synchronously
- For large enrollments, consider queuing
- Use `dispatch(new Job)->onQueue('default')`

---

## 🧪 Testing

### Test Course Creation

```php
public function test_course_creation_creates_chat_room()
{
    $course = Course::factory()->create();

    $this->assertNotNull($course->chatRoom);
    $this->assertEquals('course', $course->chatRoom->type);
    $this->assertEquals($course->title . ' Discussion', $course->chatRoom->name);
}
```

### Test Instructor Added

```php
public function test_instructor_added_as_admin()
{
    $course = Course::factory()->create();
    $chatRoom = $course->chatRoom;

    $instructor = $chatRoom->users()
        ->where('role', 'admin')
        ->first();

    $this->assertNotNull($instructor);
    $this->assertEquals($course->instructor_id, $instructor->id);
}
```

### Test Student Added

```php
public function test_student_added_to_chat_room()
{
    $course = Course::factory()->create();
    $student = User::factory()->create();

    Enrollment::create([
        'user_id' => $student->id,
        'course_id' => $course->id,
        'status' => 'active',
    ]);

    $chatRoom = $course->chatRoom;
    $this->assertTrue($chatRoom->users()->where('user_id', $student->id)->exists());
}
```

---

## 📊 Database Impact

### Tables Affected

- `chat_rooms` - New chat room created
- `chat_room_users` - Users attached to room
- `courses` - No changes
- `enrollments` - No changes

### Queries Executed

When course is created:
1. INSERT into chat_rooms
2. INSERT into chat_room_users (instructor)
3. INSERT into chat_room_users (each student)

When student enrolls:
1. INSERT into enrollments
2. INSERT into chat_room_users

---

## 🚀 Next Steps

1. Test course creation
2. Verify chat room is created
3. Verify users are added
4. Create chat UI components
5. Add real-time updates with broadcasting
6. Add authorization policies

---

*Complete implementation guide for course chat room automation*


