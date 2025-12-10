# Profile Authentication Debug Guide

## 🔍 Issue

`auth('sanctum')->user()` is returning null even though user is logged in.

## 🔧 Root Cause Analysis

The issue is that:
1. **Web routes use session-based authentication** (the `web` guard)
2. **Sanctum is configured to check the `web` guard first** (see config/sanctum.php)
3. When accessing `/profiles` from the browser, you're using session auth, not token auth
4. `auth('sanctum')->user()` only works with Bearer tokens in the Authorization header

## 📊 Authentication Guards

### Web Guard (Session-Based)
- Used for traditional web applications
- Stores session in cookies
- Used when accessing routes from browser
- `auth()->user()` or `auth('web')->user()`

### Sanctum Guard (Token-Based)
- Used for API requests
- Requires Bearer token in Authorization header
- Used when making API calls from JavaScript
- `auth('sanctum')->user()`

## 🔄 How It Works

### Browser Access (Web Route)
```
User logs in via /login
    ↓
Session created and stored in cookie
    ↓
User visits /profiles
    ↓
Browser sends session cookie
    ↓
Laravel validates session
    ↓
auth('web')->user() returns user
    ↓
auth('sanctum')->user() returns null (no Bearer token)
```

### API Access (API Route)
```
User logs in via /api/login
    ↓
Token created and returned
    ↓
JavaScript stores token in localStorage
    ↓
JavaScript makes API call with Bearer token
    ↓
Laravel validates token
    ↓
auth('sanctum')->user() returns user
    ↓
auth('web')->user() returns null (no session)
```

## ✅ Solution

Use the correct guard based on the context:

### For Web Routes (Browser Access)
```php
Route::get('/profiles', function () {
    $user = auth('web')->user();  // ✅ Use web guard
    // or
    $user = auth()->user();  // ✅ Default guard
})->middleware('auth');  // ✅ Use auth middleware
```

### For API Routes (Token Access)
```php
Route::get('/api/profiles', function () {
    $user = auth('sanctum')->user();  // ✅ Use sanctum guard
})->middleware('auth:sanctum');  // ✅ Use auth:sanctum middleware
```

### For Both (Flexible)
```php
Route::get('/profiles', function () {
    // Try web guard first (session), then sanctum (token)
    $user = auth('web')->user() ?? auth('sanctum')->user();
})->middleware('auth');
```

## 🔧 Current Fix Applied

**File**: `routes/web.php`

```php
Route::get('/profiles', function () {
    // Try web guard first (session), then sanctum (token)
    $user = auth('web')->user() ?? auth('sanctum')->user() ?? auth()->user();
    
    // Display layout based on user role
    if ($user && $user->role === 'student') {
        return view('users.profile');
    }

    // Default to admin layout for admin, instructor, staff, etc.
    return view('admin.profile');
})->middleware('auth');  // ✅ Use auth middleware (web guard)
```

## 🧪 Testing

### Test 1: Check Logs
```bash
# Open terminal and run:
tail -f storage/logs/laravel.log

# Then visit /profiles in browser
# Look for debug output showing which guard authenticated the user
```

### Test 2: Browser Console
```javascript
// Open DevTools (F12)
// Check console for debug messages:
console.log('Auth token:', localStorage.getItem('auth_token'));
console.log('All localStorage:', localStorage);
```

### Test 3: Network Tab
```
1. Open DevTools (F12)
2. Go to Network tab
3. Visit /profiles
4. Look for the request to /profiles
5. Check if session cookie is sent
6. Check response headers
```

## 📋 Debug Checklist

- [ ] Check `storage/logs/laravel.log` for debug output
- [ ] Verify user is logged in (check session)
- [ ] Verify session cookie is being sent
- [ ] Check browser console for errors
- [ ] Verify profile page loads
- [ ] Verify correct layout displays

## 🔐 Security Notes

✅ Using `auth` middleware protects the route  
✅ Session-based auth is secure for web routes  
✅ Token-based auth is secure for API routes  
✅ Sanctum validates both session and token auth  

## 📚 Related Files

- `routes/web.php` - Web routes (updated)
- `config/auth.php` - Authentication configuration
- `config/sanctum.php` - Sanctum configuration
- `storage/logs/laravel.log` - Debug logs

## 🚀 Next Steps

1. **Check the logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Visit /profiles in browser**
   - Login first
   - Navigate to /profiles
   - Check logs for debug output

3. **Verify it works**
   - Profile page should load
   - Correct layout should display
   - No errors in console

4. **Remove debug code**
   - Once working, remove the \Log::info() calls
   - Remove console.log() statements

---

## 📞 Support

### If Still Not Working
1. Check `storage/logs/laravel.log` for errors
2. Verify user is logged in
3. Check session cookie in DevTools
4. Verify middleware is applied
5. Check browser console for errors

### Common Issues

| Issue | Solution |
|-------|----------|
| User is null | Check if logged in, verify session |
| Wrong layout | Check user role in database |
| Redirect to login | Check middleware, verify session |
| Console errors | Check browser console, check logs |

---

**Debug Date**: December 10, 2025  
**Status**: ✅ DEBUGGING IN PROGRESS  
**Next**: Check logs and verify authentication

