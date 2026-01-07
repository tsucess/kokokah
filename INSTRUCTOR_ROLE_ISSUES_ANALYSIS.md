# Instructor Role Issues - Analysis & Fix Plan

## 🐛 Issues Found

### Issue 1: Instructor Redirects to /dashboard Instead of /usersdashboard
**Location**: `resources/views/auth/login.blade.php` (Lines 157-165)

**Current Logic**:
```javascript
let redirectUrl = '/dashboard'; // Default for admin/instructor
const user = result.data?.user || result.user;
if (user && user.role === 'student') {
  redirectUrl = '/usersdashboard';
}
```

**Problem**: Only students redirect to `/usersdashboard`. Instructors redirect to `/dashboard` (admin dashboard).

**Expected**: Instructors should redirect to `/usersdashboard` (student dashboard) since they have student features.

---

### Issue 2: Student Sidebar Links Not Visible to Instructor
**Location**: `public/js/sidebarManager.js` (Lines 40-77)

**Current Logic**:
```javascript
static getMenuItemsForRole(role) {
    let html = '';
    
    // Users Management (Admin+)
    if (['admin', 'superadmin'].includes(role)) { ... }
    
    // Subject Management (Instructor+)
    if (['instructor', 'admin', 'superadmin'].includes(role)) { ... }
    
    // Transactions (Admin+)
    if (['admin', 'superadmin'].includes(role)) { ... }
    
    // Reports & Analytics (Instructor+)
    if (['instructor', 'admin', 'superadmin'].includes(role)) { ... }
    
    // Communication (Admin+)
    if (['admin', 'superadmin'].includes(role)) { ... }
}
```

**Problem**: Instructor sidebar only shows:
- Dashboard
- Course Management
- Reports & Analytics

**Missing**: Student-level sidebar items like:
- Profile
- Classes
- Subjects
- Results
- Enrollment
- Announcements
- Feedback
- Leaderboard
- Koodies

---

## ✅ Fix Plan

### Fix 1: Update Login Redirect Logic
Change redirect logic to:
- Student → `/usersdashboard`
- Instructor → `/usersdashboard` (same as student)
- Admin → `/dashboard`
- Superadmin → `/dashboard`

### Fix 2: Add Student Sidebar Items to Instructor
Add student-level sidebar items to instructor role in `sidebarManager.js`.

---

## 📁 Files to Modify

1. `resources/views/auth/login.blade.php` - Update redirect logic
2. `public/js/sidebarManager.js` - Add student items to instructor sidebar

---

**Status**: Ready for implementation

