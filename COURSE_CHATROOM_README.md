# 🎓 Course ChatRoom Automation

Automatically create chat rooms for courses with instructors and students.

---

## ⚡ Quick Start (5 Minutes)

### What You Get

✅ **Automatic Chat Room Creation**
- Chat room created when course is created
- Name: Course title + " Discussion"
- Type: "course"
- Default background image

✅ **Automatic User Assignment**
- Instructor added as **admin**
- Enrolled students added as **members**
- Automatic updates when students enroll/unenroll

✅ **Lifecycle Management**
- Chat room updated when course is updated
- Chat room deleted when course is deleted
- Chat room restored when course is restored

### How to Use

```php
// Create a course
$course = Course::create([
    'title' => 'Laravel Basics',
    'instructor_id' => 1,
]);

// Chat room is automatically created! ✨
$chatRoom = $course->chatRoom;

// Get all users in the room
$users = $chatRoom->users;

// Get instructor
$instructor = $chatRoom->users()->where('role', 'admin')->first();

// Get members
$members = $chatRoom->users()->where('role', 'member')->get();
```

---

## 📁 What Was Created

### Code Files (6 files)

```
app/Observers/
├── CourseObserver.php              ✅ Handles course events
└── EnrollmentObserver.php          ✅ Handles enrollment events

app/Events/
└── CourseCreated.php               ✅ Course created event

app/Listeners/
└── CreateCourseChatRoom.php        ✅ Listens to course creation

app/Providers/
├── AppServiceProvider.php          ✅ Registers observers
└── EventServiceProvider.php        ✅ Registers events
```

### Documentation (6 files)

```
COURSE_CHATROOM_QUICK_START.md      ⭐ Start here (5 min)
COURSE_CHATROOM_SUMMARY.md          📊 Overview (5 min)
COURSE_CHATROOM_AUTOMATION_GUIDE.md 📖 Complete guide (10 min)
COURSE_CHATROOM_IMPLEMENTATION.md   🔧 Implementation (10 min)
COURSE_CHATROOM_RELATIONSHIPS.md    🔗 Relationships (10 min)
COURSE_CHATROOM_INDEX.md            📚 Navigation guide
```

---

## 🎯 Two Approaches

### 1. Model Observer (Recommended) ⭐

**Simple and straightforward**

```php
// In AppServiceProvider
public function boot(): void
{
    Course::observe(CourseObserver::class);
    Enrollment::observe(EnrollmentObserver::class);
}
```

**Pros:**
- Simple code
- All logic in one place
- Easy to understand

**Cons:**
- Tightly coupled to model
- Harder to test

### 2. Events & Listeners

**Decoupled and flexible**

```php
// In EventServiceProvider
protected $listen = [
    CourseCreated::class => [
        CreateCourseChatRoom::class,
    ],
];

// In CourseController
CourseCreated::dispatch($course);
```

**Pros:**
- Decoupled from models
- Easy to test
- Multiple listeners possible

**Cons:**
- More boilerplate code
- Need to dispatch events manually

---

## 🔄 How It Works

### When Course is Created

```
1. Course::create() is called
   ↓
2. CourseObserver::created() is triggered
   ↓
3. ChatRoom is created with course title
   ↓
4. Instructor is attached as admin
   ↓
5. Enrolled students are attached as members
   ↓
6. Chat room is ready! ✅
```

### When Student Enrolls

```
1. Enrollment::create() is called
   ↓
2. EnrollmentObserver::created() is triggered
   ↓
3. Student is added to chat room as member
   ↓
4. Student can now chat! ✅
```

---

## 💻 Code Examples

### Create a Course

```php
$course = Course::create([
    'title' => 'Laravel Basics',
    'instructor_id' => 1,
    'description' => 'Learn Laravel',
]);

// Chat room is automatically created!
```

### Get Course Chat Room

```php
$course = Course::find(1);
$chatRoom = $course->chatRoom;

echo $chatRoom->name;        // "Laravel Basics Discussion"
echo $chatRoom->type;        // "course"
echo $chatRoom->member_count; // Number of members
```

### Get Chat Room Users

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
```

### Enroll a Student

```php
$enrollment = Enrollment::create([
    'user_id' => $studentId,
    'course_id' => $courseId,
    'status' => 'active',
]);

// Student is automatically added to chat room!
```

---

## 📚 Documentation Guide

### For Quick Setup
→ Read: **COURSE_CHATROOM_QUICK_START.md** (5 min)

### For Overview
→ Read: **COURSE_CHATROOM_SUMMARY.md** (5 min)

### For Both Approaches
→ Read: **COURSE_CHATROOM_AUTOMATION_GUIDE.md** (10 min)

### For Implementation Details
→ Read: **COURSE_CHATROOM_IMPLEMENTATION.md** (10 min)

### For Model Relationships
→ Read: **COURSE_CHATROOM_RELATIONSHIPS.md** (10 min)

### For Navigation
→ Read: **COURSE_CHATROOM_INDEX.md**

---

## ✅ Checklist

- [x] CourseObserver created
- [x] EnrollmentObserver created
- [x] CourseCreated event created
- [x] CreateCourseChatRoom listener created
- [x] AppServiceProvider updated
- [x] EventServiceProvider created
- [x] Documentation created
- [ ] Test course creation
- [ ] Test student enrollment
- [ ] Build chat UI
- [ ] Deploy to production

---

## 🧪 Testing

### Test Course Creation

```php
public function test_course_creation_creates_chat_room()
{
    $course = Course::factory()->create();
    
    $this->assertNotNull($course->chatRoom);
    $this->assertEquals('course', $course->chatRoom->type);
}
```

### Test Instructor Added

```php
public function test_instructor_added_as_admin()
{
    $course = Course::factory()->create();
    
    $instructor = $course->chatRoom->users()
        ->where('role', 'admin')
        ->first();
    
    $this->assertNotNull($instructor);
}
```

### Test Student Added

```php
public function test_student_added_on_enrollment()
{
    $course = Course::factory()->create();
    $student = User::factory()->create();
    
    Enrollment::create([
        'user_id' => $student->id,
        'course_id' => $course->id,
        'status' => 'active',
    ]);
    
    $this->assertTrue(
        $course->chatRoom->users()
            ->where('user_id', $student->id)
            ->exists()
    );
}
```

---

## 🔧 Customization

### Change Chat Room Name

Edit `app/Observers/CourseObserver.php`:

```php
'name' => $course->title . ' - Chat',  // Custom format
```

### Change Default Background Image

Edit `app/Observers/CourseObserver.php`:

```php
'background_image' => 'images/your-image.jpg',
```

### Change Default Color

Edit `app/Observers/CourseObserver.php`:

```php
'color' => '#FF5733',  // Your color
```

---

## 🐛 Troubleshooting

### Chat room not created?

1. Check if observer is registered in AppServiceProvider
2. Check if Course model has instructor_id
3. Check if ChatRoom model exists
4. Check database for errors

### Students not added?

1. Check if enrollments exist with status='active'
2. Check if ChatRoom relationship is working
3. Check if users() relationship is working

### Chat room not updating?

1. Check if isDirty() is working correctly
2. Check if chatRoom relationship is loaded
3. Check if update() is being called

---

## 📞 Support

### Documentation
- Laravel Observers: https://laravel.com/docs/eloquent#observers
- Laravel Events: https://laravel.com/docs/events
- Laravel Relationships: https://laravel.com/docs/eloquent-relationships

### In This Project
- COURSE_CHATROOM_QUICK_START.md - Troubleshooting
- COURSE_CHATROOM_IMPLEMENTATION.md - Testing
- COURSE_CHATROOM_RELATIONSHIPS.md - Queries

---

## 🎉 You're Ready!

Your course chat room automation is fully set up!

### Next Steps:
1. ✅ Read COURSE_CHATROOM_QUICK_START.md
2. ✅ Test course creation
3. ✅ Test student enrollment
4. ✅ Build your chat UI
5. ✅ Deploy to production

---

## 📊 Architecture

```
Course Model
    ↓
CourseObserver
    ├─ created() → Create ChatRoom
    ├─ updated() → Update ChatRoom
    ├─ deleted() → Delete ChatRoom
    └─ restored() → Restore ChatRoom

Enrollment Model
    ↓
EnrollmentObserver
    ├─ created() → Add Student to ChatRoom
    ├─ updated() → Update Student Status
    ├─ deleted() → Remove Student from ChatRoom
    └─ restored() → Re-add Student
```

---

## 📈 What's Included

- ✅ 2 Model Observers (Course, Enrollment)
- ✅ 1 Event (CourseCreated)
- ✅ 1 Listener (CreateCourseChatRoom)
- ✅ 2 Service Providers (AppServiceProvider, EventServiceProvider)
- ✅ 6 Documentation Files
- ✅ Complete code examples
- ✅ Testing guide
- ✅ Troubleshooting guide

---

*Course ChatRoom Automation - Complete Setup & Documentation*


