# UserTemplate Testing Guide

## 🧪 Testing the Implementation

**File Updated**: `resources/views/layouts/usertemplate.blade.php`  
**Features Implemented**: Dynamic profile loading, logout functionality  
**Status**: Ready for testing  

---

## 📋 Pre-Testing Checklist

Before testing, ensure:
- [ ] Laravel server is running (`php artisan serve`)
- [ ] Vite dev server is running (`npm run dev`)
- [ ] Database is seeded with test users
- [ ] API endpoints are working
- [ ] Browser console is open (F12)
- [ ] Network tab is visible

---

## 🧪 Test 1: Profile Loading

### Steps
1. Navigate to student dashboard: `http://localhost:8000/usersdashboard`
2. Wait for page to fully load
3. Check profile section in sidebar

### Expected Results
✅ Profile image loads (not broken image)  
✅ User name displays (not "Culacino_")  
✅ User role displays (not "UX Designer")  
✅ No console errors  
✅ Network tab shows GET `/api/users/profile` with 200 status  

### Verification
```javascript
// In browser console, check:
document.getElementById('profileImage').src
document.getElementById('userName').textContent
document.getElementById('userRole').textContent
```

### If Test Fails
- Check API endpoint: `GET /api/users/profile`
- Verify user is authenticated
- Check network tab for error response
- Look for console errors
- Verify AuthApiClient is working

---

## 🧪 Test 2: Logout Functionality

### Steps
1. Navigate to student dashboard
2. Wait for profile to load
3. Click logout button (arrow icon in profile section)
4. Verify confirmation modal appears
5. Click "Yes" to confirm
6. Wait for redirect

### Expected Results
✅ Confirmation modal appears  
✅ Loading overlay shows during logout  
✅ Success toast notification displays  
✅ Redirects to `/login` page  
✅ Session is cleared  
✅ No console errors  

### Verification
```javascript
// In browser console, check:
// Before logout:
axios.get('/api/users/profile').then(r => console.log(r.data))

// After logout (should fail with 401):
axios.get('/api/users/profile').then(r => console.log(r.data))
```

### If Test Fails
- Check API endpoint: `POST /api/logout`
- Verify confirmation modal exists
- Check loading overlay visibility
- Look for console errors
- Verify redirect URL is correct

---

## 🧪 Test 3: Profile Navigation

### Steps
1. Navigate to student dashboard
2. Click on profile image
3. Verify navigation to `/profiles`
4. Go back to dashboard
5. Click on profile info (name/role)
6. Verify navigation to `/profiles`

### Expected Results
✅ Clicking profile image navigates to `/profiles`  
✅ Clicking profile info navigates to `/profiles`  
✅ Clicking logout button doesn't navigate  
✅ No console errors  

### Verification
```javascript
// In browser console, check:
window.location.pathname // Should be /profiles after click
```

### If Test Fails
- Check if `/profiles` route exists
- Verify event listeners are attached
- Check for JavaScript errors
- Verify profile section IDs are correct

---

## 🧪 Test 4: Mobile Responsiveness

### Steps
1. Open DevTools (F12)
2. Toggle device toolbar (Ctrl+Shift+M)
3. Set viewport to mobile (375px width)
4. Navigate to student dashboard
5. Test sidebar toggle
6. Test profile loading on mobile
7. Test logout on mobile

### Expected Results
✅ Sidebar hidden on mobile  
✅ Hamburger button visible  
✅ Profile loads correctly  
✅ Logout works on mobile  
✅ Sidebar closes after logout  
✅ No layout issues  

### Verification
```javascript
// In browser console, check:
window.innerWidth // Should be < 992
document.getElementById('sidebar').classList.contains('show')
```

### If Test Fails
- Check CSS media queries
- Verify sidebar toggle logic
- Check overlay visibility
- Look for CSS conflicts

---

## 🧪 Test 5: Error Handling

### Steps
1. Open DevTools Network tab
2. Throttle network to "Slow 3G"
3. Navigate to student dashboard
4. Observe loading behavior
5. Disable network (offline mode)
6. Try to logout
7. Verify error message

### Expected Results
✅ Loading overlay shows during slow load  
✅ Profile eventually loads  
✅ Error message shows on network failure  
✅ Logout fails gracefully with error  
✅ User can retry  
✅ No console errors  

### Verification
```javascript
// In browser console, check:
// Simulate network error:
axios.get('/api/users/profile').catch(e => console.log(e.message))
```

### If Test Fails
- Check error handling in DashboardModule
- Verify error messages are user-friendly
- Check timeout values
- Look for unhandled promise rejections

---

## 🧪 Test 6: Tooltip Functionality

### Steps
1. Navigate to student dashboard
2. Hover over profile image
3. Verify tooltip appears
4. Hover over profile info
5. Verify tooltip appears

### Expected Results
✅ Tooltip shows "Profile" on hover  
✅ Tooltip disappears on mouse leave  
✅ No console errors  

### Verification
```javascript
// In browser console, check:
document.getElementById('profileImage').getAttribute('data-bs-toggle')
document.getElementById('profileInfo').getAttribute('data-bs-toggle')
```

### If Test Fails
- Check Bootstrap tooltip initialization
- Verify data attributes are present
- Check CSS for tooltip styling
- Look for JavaScript errors

---

## 🧪 Test 7: Sidebar Behavior

### Steps
1. Navigate to student dashboard
2. On mobile, click hamburger
3. Verify sidebar opens
4. Click on navigation link
5. Verify sidebar closes
6. On desktop, verify sidebar stays open

### Expected Results
✅ Sidebar opens on mobile hamburger click  
✅ Overlay appears when sidebar open  
✅ Sidebar closes on nav link click  
✅ Sidebar stays open on desktop  
✅ No console errors  

### Verification
```javascript
// In browser console, check:
document.getElementById('sidebar').classList.contains('show')
document.getElementById('sidebarOverlay').classList.contains('show')
```

### If Test Fails
- Check sidebar toggle logic
- Verify overlay visibility
- Check window resize handler
- Look for CSS conflicts

---

## 📊 Test Results Template

```
Test Date: _______________
Tester: ___________________
Browser: __________________
OS: _______________________

Test 1: Profile Loading
- [ ] PASS  [ ] FAIL
Notes: _____________________

Test 2: Logout Functionality
- [ ] PASS  [ ] FAIL
Notes: _____________________

Test 3: Profile Navigation
- [ ] PASS  [ ] FAIL
Notes: _____________________

Test 4: Mobile Responsiveness
- [ ] PASS  [ ] FAIL
Notes: _____________________

Test 5: Error Handling
- [ ] PASS  [ ] FAIL
Notes: _____________________

Test 6: Tooltip Functionality
- [ ] PASS  [ ] FAIL
Notes: _____________________

Test 7: Sidebar Behavior
- [ ] PASS  [ ] FAIL
Notes: _____________________

Overall Status: [ ] PASS  [ ] FAIL
Issues Found: _______________
```

---

## 🐛 Debugging Tips

### Check Profile Loading
```javascript
// In console:
axios.get('/api/users/profile')
  .then(r => console.log('Profile:', r.data))
  .catch(e => console.error('Error:', e.response?.data))
```

### Check Logout
```javascript
// In console:
axios.post('/api/logout')
  .then(r => console.log('Logout success:', r.data))
  .catch(e => console.error('Logout error:', e.response?.data))
```

### Check Module Initialization
```javascript
// In console:
console.log('DashboardModule loaded:', typeof DashboardModule)
```

### Check Element IDs
```javascript
// In console:
console.log('profileImage:', document.getElementById('profileImage'))
console.log('userName:', document.getElementById('userName'))
console.log('userRole:', document.getElementById('userRole'))
console.log('logoutBtn:', document.getElementById('logoutBtn'))
```

---

## 📝 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Profile not loading | Check API endpoint, verify auth token |
| Logout not working | Check API endpoint, verify confirmation modal |
| Sidebar not closing | Check window.innerWidth, verify CSS |
| Tooltips not showing | Check Bootstrap initialization, verify data attributes |
| Console errors | Check browser console, look for missing files |
| Network errors | Check API endpoints, verify CORS headers |

---

## ✅ Sign-Off

Once all tests pass:
- [ ] All 7 tests passed
- [ ] No console errors
- [ ] No network errors
- [ ] Mobile responsive
- [ ] Error handling works
- [ ] Ready for production

**Tested By**: _______________  
**Date**: _______________  
**Status**: ✅ READY FOR PRODUCTION

---

## 📞 Support

If tests fail:
1. Check browser console for errors
2. Check network tab for failed requests
3. Verify API endpoints are working
4. Check AuthApiClient configuration
5. Review DashboardModule code
6. Check HTML element IDs match

For more information, see:
- USERTEMPLATE_IMPLEMENTATION_COMPLETE.md
- TEMPLATE_IMPLEMENTATION_EXAMPLES.md
- TEMPLATE_DYNAMIC_REFERENCE.md

