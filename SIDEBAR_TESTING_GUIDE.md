# Sidebar Testing Guide

## 🧪 How to Test the Sidebar Fix

### Prerequisites
- Clear browser cache (Ctrl+Shift+Delete)
- Open Developer Console (F12)
- Have test accounts for each role

### Test Case 1: Superadmin User

**Steps**:
1. Log in with superadmin account
2. Navigate to `/dashboard`
3. Check sidebar shows:
   - ✅ Dashboard
   - ✅ Users Management (with dropdown)
   - ✅ Course Management (with dropdown)
   - ✅ Transactions
   - ✅ Reports & Analytics
   - ✅ Communication (with dropdown)
   - ✅ Settings (in footer)

**Expected Result**: All menu items visible

**Console Check**:
```javascript
// Open console and run:
const user = JSON.parse(localStorage.getItem('auth_user'));
console.log('User role:', user.role); // Should be 'superadmin'
```

### Test Case 2: Admin User

**Steps**:
1. Log in with admin account
2. Navigate to `/dashboard`
3. Check sidebar shows:
   - ✅ Dashboard
   - ✅ Course Management (with dropdown)
   - ✅ Reports & Analytics
   - ❌ Users Management (hidden)
   - ❌ Transactions (hidden)
   - ❌ Communication (hidden)
   - ❌ Settings (hidden)

**Expected Result**: Only admin-level items visible

### Test Case 3: Instructor User

**Steps**:
1. Log in with instructor account
2. Navigate to `/dashboard`
3. Check sidebar shows:
   - ✅ Dashboard
   - ✅ Course Management (with dropdown)
   - ✅ Reports & Analytics
   - ❌ Users Management (hidden)
   - ❌ Transactions (hidden)
   - ❌ Communication (hidden)
   - ❌ Settings (hidden)

**Expected Result**: Same as admin (instructor-level items)

### Test Case 4: Student User

**Steps**:
1. Log in with student account
2. Navigate to `/dashboard`
3. Check sidebar shows:
   - ✅ Dashboard
   - ✅ Profile
   - ❌ All other items (hidden)

**Expected Result**: Only basic items visible

### Test Case 5: Menu Functionality

**Steps**:
1. Click on "Course Management" dropdown
2. Verify submenu items appear
3. Click again to collapse
4. Verify submenu items hide

**Expected Result**: Dropdowns work smoothly

### Test Case 6: Mobile Sidebar

**Steps**:
1. Resize browser to mobile width (< 768px)
2. Click hamburger menu icon
3. Verify sidebar slides in
4. Click overlay to close
5. Verify sidebar slides out

**Expected Result**: Mobile menu works correctly

### Test Case 7: Page Refresh

**Steps**:
1. Log in as superadmin
2. Navigate to `/dashboard`
3. Press F5 to refresh
4. Verify sidebar still shows all items

**Expected Result**: Menu persists after refresh

### Test Case 8: Navigation

**Steps**:
1. Click on "All Courses" link
2. Verify page loads correctly
3. Go back to dashboard
4. Verify sidebar menu items still visible

**Expected Result**: Navigation works without issues

## 🐛 Troubleshooting

### Sidebar shows only Dashboard
**Solution**: 
- Check localStorage: `localStorage.getItem('auth_user')`
- Verify user object has `role` property
- Clear cache and refresh

### Menu items not appearing
**Solution**:
- Check browser console for errors
- Verify `sidebarManager.js` is loaded
- Check user role in localStorage

### Settings link not showing for superadmin
**Solution**:
- Verify user role is exactly 'superadmin'
- Check `settingsLink` element exists in DOM
- Refresh page

## ✅ Checklist

- [ ] Superadmin sees all menu items
- [ ] Admin sees admin-level items
- [ ] Instructor sees instructor-level items
- [ ] Student sees only Dashboard & Profile
- [ ] Dropdowns expand/collapse correctly
- [ ] Mobile sidebar works
- [ ] Menu persists after refresh
- [ ] Navigation works correctly
- [ ] No console errors
- [ ] Settings link shows for superadmin only

---

**Status**: Ready for testing!

