# Chat System - Integration Guide with Existing Kokokah LMS

## 🔗 INTEGRATION POINTS

### 1. User Model Integration
Your existing User model already has:
- Authentication (Sanctum)
- Roles (admin, instructor, student)
- Profile data (first_name, last_name, profile_photo)

**Add these relationships:**
```php
public function chatrooms()
{
    return $this->belongsToMany(Chatroom::class, 'chatroom_members')
                ->withPivot('role', 'joined_at', 'last_read_at', 'is_muted')
                ->withTimestamps();
}

public function messages()
{
    return $this->hasMany(Message::class);
}

public function messageReactions()
{
    return $this->hasMany(MessageReaction::class);
}
```

### 2. Course Model Integration
Your existing Course model has:
- Instructor relationship
- Enrollments (students)
- Status tracking

**Add this relationship:**
```php
public function chatroom()
{
    return $this->hasOne(Chatroom::class);
}
```

**Auto-create chatroom when course is created:**
```php
// In CourseController or CourseService
protected static function booted()
{
    static::created(function ($course) {
        Chatroom::create([
            'name' => $course->title . ' Chat',
            'slug' => $course->slug . '-chat',
            'description' => 'Discussion room for ' . $course->title,
            'type' => 'course',
            'course_id' => $course->id,
            'created_by' => $course->instructor_id,
        ]);
    });
}
```

### 3. Enrollment Integration
When a student enrolls in a course:
```php
// In EnrollmentController or EnrollmentService
public function enroll(User $user, Course $course)
{
    // Create enrollment
    $enrollment = Enrollment::create([...]);
    
    // Auto-add to course chatroom
    if ($course->chatroom) {
        $course->chatroom->addMember($user, 'member');
    }
    
    return $enrollment;
}
```

### 4. Authentication Integration
Your existing Sanctum auth works perfectly:
```php
// API routes already protected with auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('chatrooms', ChatroomController::class);
    Route::apiResource('messages', MessageController::class);
});
```

### 5. Broadcasting Integration
Add to your existing `config/broadcasting.php`:
```php
'default' => env('BROADCAST_DRIVER', 'pusher'),

'connections' => [
    'pusher' => [
        'driver' => 'pusher',
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'app_id' => env('PUSHER_APP_ID'),
        'options' => [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ],
    ],
],
```

---

## 🎯 INTEGRATION WORKFLOW

### Step 1: Database Setup
```bash
# Copy migration files to database/migrations/
# Run migrations
php artisan migrate

# Create general chatroom
php artisan db:seed --class=ChatroomSeeder
```

### Step 2: Model Updates
```bash
# Update User model (add relationships)
# Update Course model (add relationship)
# Create new models: Chatroom, Message, MessageReaction
```

### Step 3: Service Layer
```bash
# Create ChatroomService
# Create MessageService
# These handle business logic
```

### Step 4: Controllers
```bash
# Create ChatroomController
# Create MessageController
# Create TypingIndicatorController
```

### Step 5: Authorization
```bash
# Create ChatroomPolicy
# Create MessagePolicy
# Register in AuthServiceProvider
```

### Step 6: Events & Broadcasting
```bash
# Create events (MessageSent, MessageEdited, etc.)
# Create listeners
# Configure broadcasting driver
```

### Step 7: Routes
```bash
# Add API routes (routes/api.php)
# Add web routes (routes/web.php)
```

### Step 8: Frontend
```bash
# Create Blade views
# Integrate with existing usertemplate
# Add JavaScript for real-time updates
```

---

## 📱 FRONTEND INTEGRATION

### Existing Layout
Your app uses `layouts/usertemplate.blade.php`

**Add chat link to sidebar:**
```blade
<li class="nav-item">
    <a class="nav-link" href="{{ route('chatrooms.index') }}">
        <i class="bi bi-chat-dots"></i> Chat
    </a>
</li>
```

### Existing Styles
Your app uses Bootstrap 5 + Tailwind CSS 4

**Chat styles already match:**
- Color scheme (--bs-dark-teal)
- Button styles (.btn-send)
- Card layouts
- Responsive grid system

### Existing JavaScript
Your app uses Vite + vanilla JS

**Add to resources/js/app.js:**
```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});
```

---

## 🔄 DATA FLOW DIAGRAM

```
User sends message
    ↓
MessageController@store
    ↓
MessageService::createMessage()
    ↓
Message model saved to DB
    ↓
MessageSent event dispatched
    ↓
Laravel Echo broadcasts to channel
    ↓
Other users receive via WebSocket
    ↓
JavaScript updates DOM in real-time
```

---

## 🧪 TESTING INTEGRATION

### Unit Tests
```php
// tests/Unit/Models/ChatroomTest.php
public function test_chatroom_has_members()
{
    $chatroom = Chatroom::factory()->create();
    $user = User::factory()->create();
    
    $chatroom->addMember($user);
    
    $this->assertTrue($chatroom->isMember($user));
}
```

### Feature Tests
```php
// tests/Feature/ChatroomTest.php
public function test_user_can_send_message()
{
    $user = User::factory()->create();
    $chatroom = Chatroom::factory()->create();
    $chatroom->addMember($user);
    
    $response = $this->actingAs($user)
        ->postJson("/api/chatrooms/{$chatroom->id}/messages", [
            'content' => 'Hello World'
        ]);
    
    $response->assertStatus(201);
    $this->assertDatabaseHas('messages', [
        'content' => 'Hello World'
    ]);
}
```

---

## 🚀 DEPLOYMENT CHECKLIST

- [ ] All migrations run successfully
- [ ] All models created and relationships working
- [ ] All controllers and services implemented
- [ ] All policies registered
- [ ] All events and listeners created
- [ ] All routes defined
- [ ] Broadcasting driver configured
- [ ] Environment variables set
- [ ] Frontend views created
- [ ] JavaScript Echo configured
- [ ] Tests passing
- [ ] Security audit completed
- [ ] Performance optimized
- [ ] Staging deployment successful
- [ ] User acceptance testing passed
- [ ] Production deployment

---

## 📞 COMMON ISSUES & SOLUTIONS

### Issue: Messages not appearing in real-time
**Solution:**
1. Check BROADCAST_DRIVER in .env
2. Verify Pusher/Soketi credentials
3. Check browser console for errors
4. Verify user is authenticated
5. Test with: `php artisan tinker` → `broadcast(new App\Events\MessageSent($message));`

### Issue: Course chatroom not created
**Solution:**
1. Add model observer to Course
2. Or call ChatroomService in CourseController
3. Verify course_id is set correctly
4. Check database for chatroom record

### Issue: User can't access course chatroom
**Solution:**
1. Verify user is enrolled in course
2. Check ChatroomPolicy authorization
3. Verify user is added to chatroom_members
4. Check user role and permissions

### Issue: WebSocket connection fails
**Solution:**
1. Check Pusher/Soketi is running
2. Verify credentials in .env
3. Check firewall/proxy settings
4. Try polling fallback
5. Check browser console for CORS errors

---

## 📚 RELATED DOCUMENTATION

- Laravel Broadcasting: https://laravel.com/docs/broadcasting
- Laravel Policies: https://laravel.com/docs/authorization
- Laravel Events: https://laravel.com/docs/events
- Pusher Documentation: https://pusher.com/docs
- Soketi Documentation: https://soketi.app/


