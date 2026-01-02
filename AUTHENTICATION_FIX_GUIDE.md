# Authentication Fix Guide - 401 Unauthorized Error

## 🔍 Problem Identified

You were getting a **401 Unauthorized** error when trying to save announcements because:

1. **Token Not Found** - The authentication token wasn't being retrieved from localStorage
2. **Wrong Key** - The code was looking for `'token'` but the system stores it as `'auth_token'`
3. **Missing Authorization Header** - Without the token, the API request had no authorization

## ✅ Solution Applied

### Changes Made to `public/js/announcements.js`

#### 1. **Improved `getToken()` Method**
```javascript
getToken() {
    // Check both possible localStorage keys
    let token = localStorage.getItem('auth_token');  // Primary key
    if (token) return token;
    
    token = localStorage.getItem('token');           // Fallback key
    if (token) return token;
    
    // Fallback to CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (csrfToken) return csrfToken;
    
    console.warn('No authentication token found!');
    return null;
}
```

#### 2. **Enhanced `submitAnnouncement()` Method**
- ✅ Validates required fields before submission
- ✅ Checks for authentication token
- ✅ Redirects to login if token is missing
- ✅ Better error handling for 401/403 responses
- ✅ Logs token and data for debugging

#### 3. **Better Error Messages**
- 401 Unauthorized → "Authentication failed. Please log in again."
- 403 Forbidden → "You do not have permission to create announcements."
- Other errors → Shows specific error message from API

## 🔑 Token Storage Keys

Your application uses **two possible keys** for storing the authentication token:

| Key | Used By | Location |
|-----|---------|----------|
| `auth_token` | BaseApiClient | `public/js/api/baseApiClient.js` |
| `token` | AuthApiClient | `public/js/api/authClient.js` |

The updated code checks **both keys** to ensure compatibility.

## 🧪 How to Test the Fix

### Step 1: Login
1. Go to `/login`
2. Enter your credentials
3. Click "Sign In"
4. You should be redirected to dashboard

### Step 2: Check Token Storage
Open browser DevTools (F12) and run:
```javascript
// Check if token is stored
console.log('auth_token:', localStorage.getItem('auth_token'));
console.log('token:', localStorage.getItem('token'));
console.log('All keys:', Object.keys(localStorage));
```

### Step 3: Create Announcement
1. Go to `/createannouncement`
2. Fill in the form:
   - Title: "Test Announcement"
   - Type: "Exams"
   - Description: "This is a test"
   - Priority: Select one (Info/Urgent/Warning)
3. Click "Save As Draft" or "Publish"
4. Check browser console for logs

### Step 4: Verify Success
- ✅ No 401 error
- ✅ Announcement created successfully
- ✅ Redirected to `/announcement` page

## 🐛 Debugging Steps

If you still get 401 error:

### 1. Check Token in Console
```javascript
// In browser console
const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
console.log('Token:', token);
console.log('Token length:', token?.length);
```

### 2. Check API Response
```javascript
// In browser console, Network tab
// Look for POST request to /api/announcements
// Check Response headers for error details
```

### 3. Check User Role
```javascript
// In browser console
const user = JSON.parse(localStorage.getItem('auth_user'));
console.log('User role:', user?.role);
// Must be 'admin' to create announcements
```

### 4. Check Middleware
The API endpoint requires:
- ✅ `auth:sanctum` - Valid authentication token
- ✅ `role:admin` - User must be admin

## 📋 Checklist Before Creating Announcement

- [ ] Logged in successfully
- [ ] Token exists in localStorage (`auth_token` or `token`)
- [ ] User role is `admin`
- [ ] Browser console shows no errors
- [ ] Network tab shows Authorization header in request

## 🔐 How Authentication Works

```
1. User logs in at /login
   ↓
2. AuthApiClient.login() called
   ↓
3. API returns token
   ↓
4. Token stored in localStorage (auth_token)
   ↓
5. User redirected to dashboard
   ↓
6. When creating announcement:
   - getToken() retrieves token from localStorage
   - Token sent in Authorization header
   - API validates token with auth:sanctum middleware
   - API checks user role with role:admin middleware
   - Announcement created if all checks pass
```

## 📝 API Requirements

**Endpoint:** `POST /api/announcements`

**Required Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Required Middleware:**
- `auth:sanctum` - Validates Bearer token
- `role:admin` - Checks user is admin

**Request Body:**
```json
{
    "title": "string",
    "description": "string",
    "type": "Exams|Events|Alert|General Info",
    "priority": "Info|Urgent|Warning",
    "audience": "All students|Specific class|Specific level",
    "scheduled_at": "ISO 8601 datetime or null",
    "status": "published|draft"
}
```

## ✨ What's Fixed

✅ Token retrieval from localStorage
✅ Support for both `auth_token` and `token` keys
✅ Better error handling and messages
✅ Form validation before submission
✅ Debugging logs in console
✅ Proper 401/403 error handling
✅ Redirect to login if not authenticated

## 🚀 Next Steps

1. **Test the fix** - Follow the testing steps above
2. **Check console logs** - Look for token retrieval messages
3. **Verify announcement creation** - Create a test announcement
4. **Check database** - Verify announcement was saved

## 📞 If Still Having Issues

1. **Check browser console** - Look for error messages
2. **Check Network tab** - Verify Authorization header is sent
3. **Check user role** - Must be `admin`
4. **Check token expiration** - Token might be expired
5. **Clear localStorage** - Try logging in again

---

**Fix Applied:** January 2, 2026
**Status:** ✅ Ready for Testing

