# 🔧 Chat Authorization - Troubleshooting Guide

## 🚨 Common Issues & Solutions

### Issue 1: "401 Unauthorized" Error

**Symptoms:**
- User cannot access chat endpoints
- Error: "401 Unauthorized"
- User is not authenticated

**Solutions:**

1. **Check Authentication Token**
```bash
# Verify token is valid
curl -H "Authorization: Bearer $TOKEN" \
  http://localhost:8000/api/user
# Should return user data
```

2. **Check Token Expiration**
```php
// In tinker
>>> $user = \App\Models\User::first();
>>> $token = $user->createToken('chat')->plainTextToken;
>>> echo $token;
```

3. **Check Sanctum Configuration**
```bash
# Verify sanctum is installed
composer show laravel/sanctum

# Check config/sanctum.php
cat config/sanctum.php
```

4. **Clear Cache**
```bash
php artisan cache:clear
php artisan config:clear
```

---

### Issue 2: "403 Forbidden" Error

**Symptoms:**
- User is authenticated but cannot access room
- Error: "403 Forbidden"
- User is not authorized

**Solutions:**

1. **Check Room Membership**
```php
// In tinker
>>> $user = \App\Models\User::first();
>>> $room = \App\Models\ChatRoom::first();
>>> $room->users()->where('user_id', $user->id)->exists();
// Should return true
```

2. **Check User Role**
```php
>>> $user = \App\Models\User::first();
>>> $user->role;
// Should be 'admin', 'instructor', or 'student'
```

3. **Check Course Enrollment**
```php
>>> $user = \App\Models\User::first();
>>> $course = \App\Models\Course::first();
>>> $course->enrollments()->where('user_id', $user->id)->exists();
// Should return true for course rooms
```

4. **Check Policy**
```php
>>> $policy = new \App\Policies\ChatRoomPolicy();
>>> $user = \App\Models\User::first();
>>> $room = \App\Models\ChatRoom::first();
>>> $policy->view($user, $room);
// Should return Response::allow()
```

---

### Issue 3: User Cannot Send Messages

**Symptoms:**
- User can view room but cannot send messages
- Error: "403 Forbidden" on POST
- User is muted or not authorized

**Solutions:**

1. **Check Mute Status**
```php
>>> $user = \App\Models\User::first();
>>> $room = \App\Models\ChatRoom::first();
>>> $room->users()
    ->where('user_id', $user->id)
    ->first()
    ->pivot
    ->is_muted;
// Should be false
```

2. **Check Message Policy**
```php
>>> $policy = new \App\Policies\ChatMessagePolicy();
>>> $user = \App\Models\User::first();
>>> $room = \App\Models\ChatRoom::first();
>>> $policy->create($user, \App\Models\ChatMessage::class, $room);
// Should return Response::allow()
```

3. **Unmute User**
```php
>>> $user = \App\Models\User::first();
>>> $room = \App\Models\ChatRoom::first();
>>> $room->users()
    ->updateExistingPivot($user->id, ['is_muted' => false]);
```

---

### Issue 4: User Cannot Edit/Delete Messages

**Symptoms:**
- User can send messages but cannot edit/delete
- Error: "403 Forbidden" on PUT/DELETE
- User is not message owner

**Solutions:**

1. **Check Message Ownership**
```php
>>> $message = \App\Models\ChatMessage::first();
>>> $message->user_id;
// Should match authenticated user ID
```

2. **Check Message Policy**
```php
>>> $policy = new \App\Policies\ChatMessagePolicy();
>>> $user = \App\Models\User::first();
>>> $message = \App\Models\ChatMessage::first();
>>> $policy->update($user, $message);
// Should return Response::allow() if owner
```

3. **Check Room Creator**
```php
>>> $room = \App\Models\ChatRoom::first();
>>> $room->created_by;
// Should match authenticated user ID for room creators
```

---

### Issue 5: Middleware Not Applied

**Symptoms:**
- Authorization checks not working
- Users can access rooms they shouldn't
- Middleware not being called

**Solutions:**

1. **Check Route Configuration**
```bash
# List all routes
php artisan route:list | grep chatrooms

# Check middleware is applied
php artisan route:list --name=chatrooms
```

2. **Verify Middleware Registration**
```php
// In app/Http/Kernel.php
protected $routeMiddleware = [
    'ensure.user.authenticated.for.chat' => \App\Http\Middleware\EnsureUserAuthenticatedForChat::class,
    'authorize.chat.room.access' => \App\Http\Middleware\AuthorizeChatRoomAccess::class,
];
```

3. **Check Route Group**
```php
// In routes/api.php
Route::prefix('chatrooms/{chatRoom}/messages')
    ->middleware([
        'auth:sanctum',
        'ensure.user.authenticated.for.chat',
        'authorize.chat.room.access',
    ])
    ->group(function () {
        // Routes here
    });
```

---

### Issue 6: Policy Not Enforced

**Symptoms:**
- Authorization checks not working
- Users can perform unauthorized actions
- Policy methods not being called

**Solutions:**

1. **Check Policy Registration**
```php
// In app/Providers/AuthServiceProvider.php
protected $policies = [
    ChatRoom::class => ChatRoomPolicy::class,
    ChatMessage::class => ChatMessagePolicy::class,
];
```

2. **Check Controller Authorization**
```php
// In controller
public function store(Request $request, ChatRoom $chatRoom)
{
    // This line is required
    $this->authorize('create', [ChatMessage::class, $chatRoom]);
    
    // Create message...
}
```

3. **Test Policy Directly**
```php
>>> $policy = new \App\Policies\ChatMessagePolicy();
>>> $user = \App\Models\User::first();
>>> $room = \App\Models\ChatRoom::first();
>>> $policy->create($user, \App\Models\ChatMessage::class, $room);
```

---

### Issue 7: Service Not Working

**Symptoms:**
- Authorization service methods returning wrong results
- Complex authorization logic not working
- Service methods throwing errors

**Solutions:**

1. **Check Service Exists**
```bash
# Verify service file exists
ls -la app/Services/ChatAuthorizationService.php
```

2. **Test Service Methods**
```php
>>> $service = new \App\Services\ChatAuthorizationService();
>>> $user = \App\Models\User::first();
>>> $room = \App\Models\ChatRoom::first();
>>> $service->canAccessRoom($user, $room);
```

3. **Check Service Logic**
```php
// Review app/Services/ChatAuthorizationService.php
// Verify all methods are implemented correctly
```

---

## 🔍 Debugging Tips

### Enable Query Logging
```php
// In tinker
>>> \DB::enableQueryLog();
>>> $service = new \App\Services\ChatAuthorizationService();
>>> $service->canAccessRoom($user, $room);
>>> \DB::getQueryLog();
```

### Check Authorization Response
```php
>>> $policy = new \App\Policies\ChatRoomPolicy();
>>> $response = $policy->view($user, $room);
>>> $response->allowed();
>>> $response->message();
```

### Log Authorization Checks
```php
// Add to policy
\Log::info('Authorization check', [
    'user_id' => $user->id,
    'room_id' => $room->id,
    'allowed' => true,
]);
```

### Monitor Logs
```bash
# Watch logs in real-time
tail -f storage/logs/laravel.log

# Search for specific errors
grep "authorization" storage/logs/laravel.log
```

---

## 📞 Getting Help

1. **Check Documentation**
   - CHAT_AUTHORIZATION_COMPLETE_GUIDE.md
   - CHAT_AUTHORIZATION_QUICK_REFERENCE.md

2. **Review Code**
   - app/Policies/ChatRoomPolicy.php
   - app/Policies/ChatMessagePolicy.php
   - app/Services/ChatAuthorizationService.php

3. **Run Tests**
   - php artisan test tests/Feature/ChatAuthorizationTest.php

4. **Check Logs**
   - tail -f storage/logs/laravel.log

---

**Status:** ✅ **TROUBLESHOOTING GUIDE COMPLETE!** 🔧


