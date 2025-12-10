# 🔧 Profile API - Debugging Guide

**Issue:** 404 Not Found error when fetching profile data  
**Status:** Troubleshooting  

---

## 🔍 Debugging Steps

### Step 1: Check Authentication Token
Open browser console (F12) and run:
```javascript
// Check if token exists
const token = localStorage.getItem('auth_token');
console.log('Token:', token);
console.log('Token exists:', !!token);
```

**Expected:** Token should be a long string (e.g., `eyJ0eXAiOiJKV1QiLCJhbGc...`)

**If empty/null:** User is not logged in. Need to login first.

---

### Step 2: Check API Endpoint
The endpoint should be: `/api/users/profile`

Verify in browser console:
```javascript
// Check the full URL being called
const baseUrl = '/api';
const endpoint = '/users/profile';
const fullUrl = baseUrl + endpoint;
console.log('Full URL:', fullUrl);
```

**Expected:** `/api/users/profile`

---

### Step 3: Check Network Request
1. Open browser DevTools (F12)
2. Go to **Network** tab
3. Reload the profile page
4. Look for a request to `/api/users/profile`
5. Click on it and check:
   - **Status:** Should be 200 (not 404)
   - **Headers:** Should include `Authorization: Bearer {token}`
   - **Response:** Should contain user data

---

### Step 4: Check Console Logs
Open browser console (F12) and look for these messages:
```
✅ Profile page loaded, fetching user data...
✅ Auth token exists: true
✅ Fetching profile data from API...
✅ Token: Present
✅ API Response: {...}
✅ Profile data populated successfully
```

**If you see errors:**
- ❌ Auth token exists: false → User not logged in
- ❌ Failed to fetch profile → API error
- ❌ Network error → Server not responding

---

## 🐛 Common Issues & Solutions

### Issue 1: 404 Not Found
**Cause:** Endpoint doesn't exist or wrong URL  
**Solution:**
1. Verify endpoint is `/api/users/profile` (not `/api/profile`)
2. Check routes in `routes/api.php`
3. Verify UserController has `profile()` method

### Issue 2: 401 Unauthorized
**Cause:** Token is missing or invalid  
**Solution:**
1. Login first to get a token
2. Check token is stored in localStorage
3. Verify token is being sent in Authorization header

### Issue 3: Token Not Found
**Cause:** User not logged in  
**Solution:**
1. Go to login page
2. Login with valid credentials
3. Token will be stored in localStorage
4. Then access profile page

### Issue 4: CORS Error
**Cause:** Cross-origin request blocked  
**Solution:**
1. Check CORS configuration in Laravel
2. Verify API_BASE_URL is correct
3. Check browser console for CORS error details

---

## 🔐 Authentication Flow

```
1. User Logs In
   ↓
2. AuthApiClient.login() called
   ↓
3. API returns token
   ↓
4. Token stored in localStorage (auth_token)
   ↓
5. User redirected to dashboard
   ↓
6. User opens profile page
   ↓
7. Profile page checks for token
   ↓
8. If token exists, fetch profile data
   ↓
9. UserApiClient.getProfile() called
   ↓
10. Token sent in Authorization header
    ↓
11. API returns user data
    ↓
12. Form fields populated
```

---

## 📋 Checklist

- [ ] User is logged in
- [ ] Token exists in localStorage
- [ ] Token is valid (not expired)
- [ ] API endpoint is correct (`/api/users/profile`)
- [ ] UserController has `profile()` method
- [ ] Route is registered in `routes/api.php`
- [ ] Route has `auth:sanctum` middleware
- [ ] Authorization header is being sent
- [ ] Server is running
- [ ] No CORS errors

---

## 🧪 Manual Testing

### Test 1: Check Token
```javascript
// In browser console
const token = localStorage.getItem('auth_token');
console.log('Token:', token);
```

### Test 2: Make API Call
```javascript
// In browser console
import UserApiClient from '/js/api/userApiClient.js';
const response = await UserApiClient.getProfile();
console.log('Response:', response);
```

### Test 3: Check Headers
```javascript
// In browser console
import BaseApiClient from '/js/api/baseApiClient.js';
const headers = BaseApiClient.getAuthHeaders();
console.log('Headers:', headers);
```

---

## 📞 Support

**File:** `resources/views/admin/profile.blade.php`  
**API Client:** `public/js/api/userApiClient.js`  
**Base Client:** `public/js/api/baseApiClient.js`  
**Controller:** `app/Http/Controllers/UserController.php`  
**Route:** `routes/api.php` (line 233)  

---

## ✅ Next Steps

1. **Check Console Logs**
   - Open browser console (F12)
   - Look for error messages
   - Note the exact error

2. **Check Network Tab**
   - Open DevTools Network tab
   - Reload page
   - Look for `/api/users/profile` request
   - Check status code and response

3. **Verify Authentication**
   - Ensure user is logged in
   - Check token in localStorage
   - Verify token is valid

4. **Check Server**
   - Verify Laravel server is running
   - Check for server errors in logs
   - Verify database connection

5. **Report Issue**
   - Include console error messages
   - Include network response
   - Include token status
   - Include server logs


