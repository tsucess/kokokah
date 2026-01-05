# 📝 Laravel 12 Migration Notes

## Key Differences from Laravel 11

### 1. Middleware Registration

#### ❌ Laravel 11 (Old Way)
```php
// app/Http/Kernel.php
protected $routeMiddleware = [
    'ensure.user.authenticated.for.chat' => \App\Http\Middleware\EnsureUserIsAuthenticatedForChat::class,
];
```

#### ✅ Laravel 12 (New Way)
```php
// bootstrap/app.php
$middleware->alias([
    'ensure.user.authenticated.for.chat' => \App\Http\Middleware\EnsureUserIsAuthenticatedForChat::class,
]);
```

**Key Changes:**
- No more `app/Http/Kernel.php`
- All middleware registered in `bootstrap/app.php`
- Uses `$middleware->alias()` instead of `$routeMiddleware`

---

### 2. Application Bootstrap

#### ❌ Laravel 11
```php
// bootstrap/app.php (minimal)
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(...)
    ->withMiddleware(...)
    ->withExceptions(...)
    ->create();
```

#### ✅ Laravel 12
```php
// bootstrap/app.php (same structure)
// But middleware registration is more explicit
$middleware->alias([...]);
$middleware->group('api', [...]);
```

---

### 3. Middleware Groups

#### ✅ Laravel 12 Syntax
```php
$middleware->group('api', [
    'rate.limit:api,300',
]);
```

---

## 🔄 Migration Checklist

When migrating from Laravel 11 to 12:

- [ ] Remove `app/Http/Kernel.php`
- [ ] Move all middleware aliases to `bootstrap/app.php`
- [ ] Update middleware group definitions
- [ ] Test all middleware functionality
- [ ] Verify routes still work

---

## 📋 Chatroom Implementation for Laravel 12

### Middleware Registration
```php
// bootstrap/app.php
$middleware->alias([
    'ensure.user.authenticated.for.chat' => \App\Http\Middleware\EnsureUserIsAuthenticatedForChat::class,
    'authorize.chat.room.access' => \App\Http\Middleware\AuthorizeChatRoomAccess::class,
    'check.chat.room.mute.status' => \App\Http\Middleware\CheckChatRoomMuteStatus::class,
]);
```

### Route Definition
```php
// routes/api.php
Route::prefix('chatrooms/{chatRoom}/messages')
    ->middleware([
        'auth:sanctum',
        'ensure.user.authenticated.for.chat',
        'authorize.chat.room.access',
    ])
    ->group(function () {
        Route::get('/', [ChatMessageController::class, 'index']);
        Route::post('/', [ChatMessageController::class, 'store'])
            ->middleware('check.chat.room.mute.status');
    });
```

---

## ✅ Verification

To verify Laravel 12 middleware is working:

```bash
# Check middleware is registered
php artisan route:list | grep chatrooms

# Test middleware
curl -X GET http://localhost:8000/api/chatrooms/1/messages \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 🎯 Summary

Laravel 12 simplifies middleware registration by:
- Centralizing all configuration in `bootstrap/app.php`
- Removing the need for `app/Http/Kernel.php`
- Using a cleaner, more explicit API

This makes the codebase easier to understand and maintain!

