# 🚀 Quick Reference Guide - Kokokah.com LMS
**Date:** January 7, 2026

---

## 📍 File Locations - Quick Map

### Frontend Files
```
resources/views/
├── auth/
│   └── login.blade.php          ← Login redirect logic (FIXED)
├── admin/
│   ├── dashboard.blade.php
│   └── profile.blade.php
├── users/
│   ├── usersdashboard.blade.php
│   ├── profile.blade.php
│   └── [other user pages]
└── layouts/
    ├── dashboardtemp.blade.php  ← Main dashboard layout
    └── usertemplate.blade.php

public/js/
├── sidebarManager.js            ← Menu rendering (FIXED)
├── api/
│   ├── baseApiClient.js
│   ├── authClient.js
│   ├── courseApiClient.js
│   └── [15+ more clients]
└── utils/
    ├── toastNotification.js
    ├── confirmationModal.js
    └── notificationHelper.js
```

### Backend Files
```
app/Http/
├── Controllers/
│   ├── AuthController.php
│   ├── CourseController.php
│   ├── DashboardController.php
│   ├── AdminController.php
│   └── [40+ more controllers]
├── Middleware/
│   ├── RoleMiddleware.php
│   ├── AuthorizeChatRoomAccess.php
│   └── [more middleware]
└── Requests/
    └── [validation requests]

app/Models/
├── User.php
├── Course.php
├── Enrollment.php
└── [30+ more models]

routes/
├── api.php                      ← API routes (100+)
└── web.php                      ← Web routes (50+)

database/
├── migrations/
│   └── [30+ migration files]
└── seeders/
    └── [seeder files]
```

---

## 🔑 Key Code Snippets

### Instructor Redirect (FIXED)
**File:** `resources/views/auth/login.blade.php` (Line 164)
```javascript
if (user && ['student', 'instructor'].includes(user.role)) {
  redirectUrl = '/usersdashboard';
}
```

### Sidebar Menu Rendering (FIXED)
**File:** `public/js/sidebarManager.js` (Line 77)
```javascript
if (['student', 'instructor'].includes(role)) {
  html += this.getStudentMenu();
}
```

### Role Middleware
**File:** `app/Http/Middleware/RoleMiddleware.php`
```php
if (!in_array($user->role, $roles)) {
    return response()->json(['message' => 'Forbidden'], 403);
}
```

### Dashboard Controller
**File:** `app/Http/Controllers/DashboardController.php`
```php
public function studentDashboard() {
    if (!$user->hasRole('student')) {
        return response()->json(['message' => 'Access denied'], 403);
    }
}
```

---

## 🎯 Common Tasks

### Add New Menu Item to Sidebar
1. Open `public/js/sidebarManager.js`
2. Find the role condition (e.g., `if (['student', 'instructor'].includes(role))`)
3. Add new link in the appropriate menu method
4. Test in browser

### Create New API Endpoint
1. Create controller method in `app/Http/Controllers/`
2. Add route in `routes/api.php`
3. Add middleware if needed
4. Create API client in `public/js/api/`
5. Test with Postman

### Add New User Role
1. Update `User` model role validation
2. Add role check in `RoleMiddleware.php`
3. Update `sidebarManager.js` conditions
4. Add role-specific menu items
5. Update dashboard controller

### Fix Authentication Issue
1. Check `routes/api.php` for middleware
2. Verify token in localStorage
3. Check `AuthController.php` login method
4. Verify `Sanctum` configuration
5. Check browser console for errors

---

## 🧪 Testing Checklist

### Login Flow
- [ ] Student login → redirects to `/usersdashboard`
- [ ] Instructor login → redirects to `/usersdashboard`
- [ ] Admin login → redirects to `/dashboard`
- [ ] Superadmin login → redirects to `/dashboard`

### Sidebar Visibility
- [ ] Student sees: Profile, Classes, Subjects, Results, etc.
- [ ] Instructor sees: All student items + Course Management + Reports
- [ ] Admin sees: Users Management + Course Management + Transactions
- [ ] Superadmin sees: All items + Settings

### API Access
- [ ] Student can access `/api/dashboard/student`
- [ ] Instructor can access `/api/dashboard/instructor`
- [ ] Admin can access `/api/dashboard/admin`
- [ ] Unauthorized users get 403 error

---

## 🐛 Debugging Tips

### Check User Role
```javascript
// In browser console
const user = JSON.parse(localStorage.getItem('auth_user'));
console.log(user.role);
```

### Check API Token
```javascript
// In browser console
const token = localStorage.getItem('auth_token');
console.log(token);
```

### Check Network Requests
1. Open DevTools (F12)
2. Go to Network tab
3. Look for API requests
4. Check response status & data

### Check Server Logs
```bash
tail -f storage/logs/laravel.log
```

### Check Database
```bash
php artisan tinker
>>> User::find(1)->role
```

---

## 📊 Role Hierarchy

```
Superadmin (Full Access)
    ↓
Admin (Course & User Management)
    ↓
Instructor (Course Creation & Student Management)
    ↓
Student (Learning & Enrollment)
```

---

## 🔐 Authentication Flow

```
1. User enters credentials
   ↓
2. POST /api/auth/login
   ↓
3. AuthController validates
   ↓
4. Sanctum generates token
   ↓
5. Token stored in localStorage
   ↓
6. Redirect based on role
   ↓
7. Dashboard loads with user data
```

---

## 📱 Responsive Breakpoints

- **Mobile:** < 768px
- **Tablet:** 768px - 1024px
- **Desktop:** > 1024px

---

## 🎨 Color Scheme

- **Primary:** #FDAF22 (Orange)
- **Secondary:** #007bff (Blue)
- **Success:** #28a745 (Green)
- **Danger:** #dc3545 (Red)
- **Warning:** #ffc107 (Yellow)

---

## 📚 Important URLs

| URL | Purpose |
|-----|---------|
| `/` | Home page |
| `/login` | Login page |
| `/register` | Registration page |
| `/dashboard` | Admin dashboard |
| `/usersdashboard` | Student/Instructor dashboard |
| `/api/courses` | Courses API |
| `/api/enrollments` | Enrollments API |
| `/api/dashboard/student` | Student dashboard API |

---

## 🚀 Deployment Checklist

- [ ] Run migrations: `php artisan migrate`
- [ ] Build assets: `npm run build`
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Set environment: `.env` configured
- [ ] Test login flow
- [ ] Test sidebar rendering
- [ ] Test API endpoints
- [ ] Monitor logs

---

## 📞 Quick Help

**Issue:** Instructor sees admin dashboard  
**Solution:** Check `login.blade.php` line 164

**Issue:** Sidebar items not showing  
**Solution:** Check `sidebarManager.js` role conditions

**Issue:** API returns 403  
**Solution:** Check `RoleMiddleware.php` and user role

**Issue:** Token not working  
**Solution:** Check localStorage and Sanctum config

---

**Last Updated:** January 7, 2026

