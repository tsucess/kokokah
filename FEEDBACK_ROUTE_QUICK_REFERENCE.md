# Feedback Route - Quick Reference

## What Was Added
Feedback route now appears in the sidebar for admin and superadmin users with proper role-based access control.

## Files Changed

### 1. routes/web.php (Line 133-135)
```php
Route::middleware(['auth:sanctum', 'role:admin,superadmin'])->get('/feedback', function () {
    return view('admin.feedback');
});
```

### 2. public/js/sidebarManager.js (Line 150)
```html
<a class="nav-item-link d-block nav-child" href="/feedback" title="View and manage user feedback">Feedback</a>
```

## Access Control

| Role | Can Access | Location |
|------|-----------|----------|
| Superadmin | ✅ Yes | Sidebar → Communication → Feedback |
| Admin | ✅ Yes | Sidebar → Communication → Feedback |
| Instructor | ❌ No | Not visible in sidebar |
| Student | ❌ No | Not visible in sidebar |

## Route Details

**URL**: `/feedback`
**Method**: GET
**Auth Required**: Yes (Sanctum token)
**Roles Required**: admin, superadmin
**View**: `resources/views/admin/feedback.blade.php`

## API Endpoints

### Get All Feedback (Superadmin only)
```
GET /api/feedback/
Authorization: Bearer {token}
```

### Get Single Feedback (Superadmin only)
```
GET /api/feedback/{id}
Authorization: Bearer {token}
```

### Submit Feedback (Public)
```
POST /api/feedback/submit
```

### Get User's Feedback (Authenticated)
```
GET /api/feedback/my-feedback
Authorization: Bearer {token}
```

## Testing

### Quick Test
1. Login as admin
2. Look for "Communication" menu
3. Click "Feedback"
4. Should see feedback page

### Verify Access Control
```javascript
// In browser console
// Check if user has access
const user = JSON.parse(localStorage.getItem('auth_user'));
console.log(user.role); // Should be 'admin' or 'superadmin'
```

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Feedback link not visible | Check user role in localStorage |
| 403 Forbidden error | Verify user has admin/superadmin role |
| 401 Unauthorized | Check auth token is valid |
| Page not loading | Check feedback.blade.php exists |

## Security Features

✅ Authentication required (Sanctum)
✅ Role-based authorization
✅ Middleware protection
✅ Unauthorized access blocked
✅ Proper error handling

## Related Files

- `resources/views/admin/feedback.blade.php` - Feedback view
- `app/Http/Controllers/FeedbackController.php` - Controller
- `app/Models/Feedback.php` - Model
- `routes/api.php` - API endpoints

## Status

✅ Implementation Complete
✅ Ready for Testing
✅ Production Ready

