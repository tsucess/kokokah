# Course ChatRoom Automation - Quick Start

Fast setup guide for automatic course chat room creation.

---

## ⚡ Quick Setup (5 minutes)

### Step 1: Files Already Created ✅

The following files have been created for you:

```
app/Observers/
├── CourseObserver.php          ✅ Handles course events
└── EnrollmentObserver.php      ✅ Handles enrollment events

app/Events/
└── CourseCreated.php           ✅ Course created event

app/Listeners/
└── CreateCourseChatRoom.php    ✅ Listens to course creation

app/Providers/
├── AppServiceProvider.php      ✅ Registers observers
└── EventServiceProvider.php    ✅ Registers events
```

### Step 2: Verify Setup

Check that `app/Providers/AppServiceProvider.php` has:

```php
public function boot(): void
{
    Course::observe(CourseObserver::class);
    Enrollment::observe(EnrollmentObserver::class);
}
```

### Step 3: Test It!

Create a course:

```php
$course = Course::create([
    'title' => 'Laravel Basics',
    'instructor_id' => 1,
    'description' => 'Learn Laravel',
]);

// Chat room is automatically created!
$chatRoom = $course->chatRoom;
echo $chatRoom->name;  // "Laravel Basics Discussion"
```

---

## 🎯 What Happens Automatically

### When a Course is Created

✅ Chat room is created with:
- Name: `{Course Title} Discussion`
- Type: `course`
- Creator: Course instructor
- Background: Default image
- Status: Active

✅ Instructor is added as `admin`

✅ All active enrolled students are added as `members`

### When a Student Enrolls

✅ Student is automatically added to the course chat room as `member`

### When Enrollment Status Changes

✅ If enrollment becomes active → Student added to chat room
✅ If enrollment becomes inactive → Student deactivated in chat room

### When a Course is Updated

✅ Chat room name and description are updated

### When a Course is Deleted

✅ Chat room is soft deleted (can be restored)

### When a Course is Restored

✅ Chat room is restored

---

## 📝 Usage Examples

### Get course chat room

```php
$course = Course::find(1);
$chatRoom = $course->chatRoom;
```

### Get all users in chat room

```php
$users = $chatRoom->users;

foreach ($users as $user) {
    echo $user->name;           // User name
    echo $user->pivot->role;    // 'admin' or 'member'
    echo $user->pivot->is_muted; // Boolean
}
```

### Get instructor

```php
$instructor = $chatRoom->users()
    ->where('role', 'admin')
    ->first();
```

### Get members

```php
$members = $chatRoom->users()
    ->where('role', 'member')
    ->get();
```

### Send a message

```php
$message = ChatMessage::create([
    'chat_room_id' => $chatRoom->id,
    'user_id' => auth()->id(),
    'content' => 'Hello everyone!',
    'type' => 'text',
]);
```

### Get recent messages

```php
$messages = $chatRoom->messages()
    ->with('user', 'reactions')
    ->latest()
    ->paginate(50);
```

---

## 🔧 Customization

### Change Default Background Image

Edit `app/Observers/CourseObserver.php`:

```php
'background_image' => 'images/your-custom-image.jpg',
```

### Change Default Color

Edit `app/Observers/CourseObserver.php`:

```php
'color' => '#FF5733',  // Your color
```

### Change Chat Room Name Format

Edit `app/Observers/CourseObserver.php`:

```php
'name' => $course->title . ' - Chat',  // Custom format
```

### Add More Users to Chat Room

```php
$chatRoom = $course->chatRoom;

// Add a user as moderator
$chatRoom->users()->attach($userId, [
    'role' => 'moderator',
    'is_active' => true,
    'joined_at' => now(),
]);
```

### Remove User from Chat Room

```php
$chatRoom->users()->detach($userId);
```

### Update User Role

```php
$chatRoom->users()->updateExistingPivot($userId, [
    'role' => 'moderator',
]);
```

---

## 🚀 Advanced Usage

### Manually Create Chat Room

If you need to create a chat room manually:

```php
$chatRoom = ChatRoom::create([
    'name' => 'Custom Room',
    'type' => 'course',
    'course_id' => $courseId,
    'created_by' => auth()->id(),
    'background_image' => 'images/bg.jpg',
    'color' => '#007bff',
    'is_active' => true,
]);

// Add users
$chatRoom->users()->attach($userId, [
    'role' => 'member',
    'joined_at' => now(),
]);
```

### Query Courses with Chat Rooms

```php
// Get courses with their chat rooms
$courses = Course::with('chatRoom')->get();

// Get courses with active chat rooms
$courses = Course::whereHas('chatRoom', function ($q) {
    $q->where('is_active', true);
})->get();
```

### Get Chat Room Statistics

```php
$chatRoom = $course->chatRoom;

echo $chatRoom->member_count;    // Number of members
echo $chatRoom->message_count;   // Number of messages
echo $chatRoom->last_message_at; // Last message time
```

---

## ✅ Checklist

- [ ] Verify CourseObserver.php exists
- [ ] Verify EnrollmentObserver.php exists
- [ ] Verify AppServiceProvider registers both observers
- [ ] Create a test course
- [ ] Verify chat room is created
- [ ] Verify instructor is added as admin
- [ ] Verify students are added as members
- [ ] Test sending a message
- [ ] Test adding a reaction
- [ ] Test updating course title

---

## 🐛 Troubleshooting

### Chat room not created?

```php
// Check if observer is registered
dd(Course::getObservers());

// Check if course has instructor_id
dd($course->instructor_id);

// Check if ChatRoom model exists
dd(ChatRoom::count());
```

### Students not added?

```php
// Check if enrollments exist
dd($course->enrollments()->count());

// Check if enrollments are active
dd($course->enrollments()->where('status', 'active')->count());

// Check if users are in chat room
dd($chatRoom->users()->count());
```

### Chat room not updating?

```php
// Check if isDirty() works
$course->title = 'New Title';
dd($course->isDirty('title'));

// Check if chatRoom relationship works
dd($course->chatRoom);
```

---

## 📚 Learn More

For detailed information, see:
- **COURSE_CHATROOM_AUTOMATION_GUIDE.md** - Complete guide
- **CHAT_MODELS_USAGE_GUIDE.md** - Chat models guide
- **CHAT_MODELS_QUICK_REFERENCE.md** - Quick reference

---

## 🎉 Done!

Your course chat room automation is ready to use!

Create a course and watch the magic happen ✨

---

*Quick start guide for course chat room automation*


