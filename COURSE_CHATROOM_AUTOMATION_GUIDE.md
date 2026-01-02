# Course ChatRoom Automation Guide

Automatically create chat rooms when courses are created using Laravel Model Observers or Events.

---

## 🎯 Overview

This guide shows two approaches to automatically create a chat room whenever a new course is created:

1. **Model Observer** (Recommended for simple cases)
2. **Events & Listeners** (Recommended for complex cases)

Both approaches will:
- ✅ Create a chat room with course title
- ✅ Set type to 'course'
- ✅ Attach instructor as admin
- ✅ Attach enrolled students as members
- ✅ Set default background image

---

## 🔍 Approach 1: Model Observer (Recommended)

### What is a Model Observer?

A Model Observer is a class that listens to model lifecycle events (created, updated, deleted, etc.) and automatically performs actions.

**Pros:**
- Simple and straightforward
- All logic in one place
- Easy to understand
- Good for single model concerns

**Cons:**
- Tightly coupled to the model
- Harder to test
- Less flexible for complex scenarios

### Implementation

#### Step 1: Create the Observer

**File:** `app/Observers/CourseObserver.php`

```php
<?php

namespace App\Observers;

use App\Models\Course;
use App\Models\ChatRoom;

class CourseObserver
{
    public function created(Course $course): void
    {
        // Create chat room
        $chatRoom = ChatRoom::create([
            'name' => $course->title . ' Discussion',
            'description' => 'Discussion room for ' . $course->title,
            'type' => 'course',
            'course_id' => $course->id,
            'created_by' => $course->instructor_id,
            'background_image' => 'images/default-course-chat-bg.jpg',
            'color' => '#007bff',
            'is_active' => true,
        ]);

        // Attach instructor as admin
        if ($course->instructor_id) {
            $chatRoom->users()->attach($course->instructor_id, [
                'role' => 'admin',
                'is_active' => true,
                'joined_at' => now(),
            ]);
        }

        // Attach enrolled students
        $enrolledStudents = $course->enrollments()
            ->where('status', 'active')
            ->pluck('user_id')
            ->toArray();

        if (!empty($enrolledStudents)) {
            $attachData = [];
            foreach ($enrolledStudents as $studentId) {
                $attachData[$studentId] = [
                    'role' => 'member',
                    'is_active' => true,
                    'joined_at' => now(),
                ];
            }
            $chatRoom->users()->attach($attachData);
        }
    }

    public function updated(Course $course): void
    {
        // Update chat room if title/description changed
        if ($course->isDirty('title') || $course->isDirty('description')) {
            $chatRoom = $course->chatRoom;
            if ($chatRoom) {
                $chatRoom->update([
                    'name' => $course->title . ' Discussion',
                    'description' => 'Discussion room for ' . $course->title,
                ]);
            }
        }
    }

    public function deleted(Course $course): void
    {
        // Soft delete chat room
        $chatRoom = $course->chatRoom;
        if ($chatRoom) {
            $chatRoom->delete();
        }
    }

    public function restored(Course $course): void
    {
        // Restore chat room
        $chatRoom = $course->chatRoom;
        if ($chatRoom && $chatRoom->trashed()) {
            $chatRoom->restore();
        }
    }

    public function forceDeleted(Course $course): void
    {
        // Permanently delete chat room
        $chatRoom = $course->chatRoom;
        if ($chatRoom) {
            $chatRoom->forceDelete();
        }
    }
}
```

#### Step 2: Register the Observer

**File:** `app/Providers/AppServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Course;
use App\Observers\CourseObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register the observer
        Course::observe(CourseObserver::class);
    }
}
```

#### Step 3: Done!

The observer is now active. Whenever a course is created, updated, or deleted, the observer will automatically handle the chat room.

---

## 📢 Approach 2: Events & Listeners

### What are Events & Listeners?

Events are actions that happen in your application. Listeners respond to those events.

**Pros:**
- Decoupled from models
- Easy to test
- Can have multiple listeners
- More flexible
- Better for complex scenarios

**Cons:**
- More boilerplate code
- Slightly more complex to understand
- Need to manually dispatch events

### Implementation

#### Step 1: Create the Event

**File:** `app/Events/CourseCreated.php`

```php
<?php

namespace App\Events;

use App\Models\Course;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CourseCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Course $course)
    {
    }
}
```

#### Step 2: Create the Listener

**File:** `app/Listeners/CreateCourseChatRoom.php`

```php
<?php

namespace App\Listeners;

use App\Events\CourseCreated;
use App\Models\ChatRoom;

class CreateCourseChatRoom
{
    public function handle(CourseCreated $event): void
    {
        $course = $event->course;

        // Create chat room
        $chatRoom = ChatRoom::create([
            'name' => $course->title . ' Discussion',
            'description' => 'Discussion room for ' . $course->title,
            'type' => 'course',
            'course_id' => $course->id,
            'created_by' => $course->instructor_id,
            'background_image' => 'images/default-course-chat-bg.jpg',
            'color' => '#007bff',
            'is_active' => true,
        ]);

        // Attach instructor and students...
        // (same code as observer)
    }
}
```

#### Step 3: Register Event & Listener

**File:** `app/Providers/EventServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\CourseCreated;
use App\Listeners\CreateCourseChatRoom;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CourseCreated::class => [
            CreateCourseChatRoom::class,
        ],
    ];
}
```

#### Step 4: Dispatch the Event

In your Course controller or wherever you create courses:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Events\CourseCreated;

class CourseController extends Controller
{
    public function store(Request $request)
    {
        $course = Course::create($request->validated());

        // Dispatch the event
        CourseCreated::dispatch($course);

        return redirect()->route('courses.show', $course);
    }
}
```

---

## 🔄 Handling Student Enrollment

When students enroll in a course after it's created, you should also add them to the chat room.

### Using an Observer on Enrollment

**File:** `app/Observers/EnrollmentObserver.php`

```php
<?php

namespace App\Observers;

use App\Models\Enrollment;

class EnrollmentObserver
{
    public function created(Enrollment $enrollment): void
    {
        // Only add if enrollment is active
        if ($enrollment->status === 'active') {
            $chatRoom = $enrollment->course->chatRoom;

            if ($chatRoom) {
                $chatRoom->users()->attach($enrollment->user_id, [
                    'role' => 'member',
                    'is_active' => true,
                    'joined_at' => now(),
                ]);
            }
        }
    }

    public function updated(Enrollment $enrollment): void
    {
        $chatRoom = $enrollment->course->chatRoom;

        if (!$chatRoom) {
            return;
        }

        // If enrollment became active, add to chat room
        if ($enrollment->isDirty('status') && $enrollment->status === 'active') {
            $chatRoom->users()->attach($enrollment->user_id, [
                'role' => 'member',
                'is_active' => true,
                'joined_at' => now(),
            ]);
        }

        // If enrollment became inactive, remove from chat room
        if ($enrollment->isDirty('status') && $enrollment->status !== 'active') {
            $chatRoom->users()->detach($enrollment->user_id);
        }
    }
}
```

Register it in AppServiceProvider:

```php
public function boot(): void
{
    Course::observe(CourseObserver::class);
    Enrollment::observe(EnrollmentObserver::class);
}
```

---

## 📊 Comparison

| Feature | Observer | Events |
|---------|----------|--------|
| Simplicity | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ |
| Decoupling | ⭐⭐ | ⭐⭐⭐⭐⭐ |
| Testability | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Flexibility | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Multiple Listeners | ❌ | ✅ |
| Boilerplate | Low | Medium |

---

## 🚀 Usage Examples

### Create a course (Observer approach)

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

### Create a course (Events approach)

```php
$course = Course::create([
    'title' => 'Laravel Basics',
    'instructor_id' => 1,
    'description' => 'Learn Laravel',
]);

// Dispatch the event
CourseCreated::dispatch($course);

// Chat room is automatically created!
```

### Get course chat room

```php
$course = Course::find(1);
$chatRoom = $course->chatRoom;

// Get all users in the room
$users = $chatRoom->users;

// Get instructor
$instructor = $chatRoom->users()->where('role', 'admin')->first();

// Get members
$members = $chatRoom->users()->where('role', 'member')->get();
```

---

## ✅ Checklist

- [ ] Create CourseObserver.php
- [ ] Register observer in AppServiceProvider
- [ ] Test course creation
- [ ] Verify chat room is created
- [ ] Verify instructor is added as admin
- [ ] Verify students are added as members
- [ ] (Optional) Create EnrollmentObserver for new enrollments
- [ ] (Optional) Create CourseCreated event
- [ ] (Optional) Create CreateCourseChatRoom listener
- [ ] (Optional) Register event in EventServiceProvider

---

## 🔧 Troubleshooting

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

## 📚 Files Created

- ✅ `app/Observers/CourseObserver.php`
- ✅ `app/Providers/AppServiceProvider.php` (updated)
- ✅ `app/Events/CourseCreated.php`
- ✅ `app/Listeners/CreateCourseChatRoom.php`
- ✅ `app/Providers/EventServiceProvider.php`

---

## 🎯 Recommendation

**Use Model Observer** if:
- You want simple, straightforward code
- You only need one action per event
- You're new to Laravel

**Use Events & Listeners** if:
- You need multiple listeners for one event
- You want better testability
- You want decoupled code
- You're building a complex application

---

*Complete guide for automating course chat room creation*


