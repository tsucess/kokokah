# 🔐 AUTHENTICATION QUICK REFERENCE

**Project:** Kokokah.com LMS  
**Date:** October 28, 2025

---

## 📍 FILE LOCATIONS

### Backend Files
```
app/Http/Controllers/
  ├── AuthController.php          (register, login, verify email)
  └── PasswordResetController.php  (forgot password, reset password)

routes/
  └── api.php                      (all auth endpoints)

config/
  ├── auth.php                     (authentication config)
  └── sanctum.php                  (token config)
```

### Frontend Files
```
resources/views/auth/
  ├── login.blade.php              (login page)
  ├── register.blade.php           (registration page)
  ├── verifypassword.blade.php     (email verification)
  ├── forgotpassword.blade.php     (forgot password)
  ├── resetpassword.blade.php      (reset password)
  ├── stemregister.blade.php       (STEM registration variant)
  └── stemregister2.blade.php      (STEM registration variant 2)

resources/js/
  └── bootstrap.js                 (Axios setup)
```

---

## 🔗 API ENDPOINTS SUMMARY

| Method | Endpoint | Auth | Purpose |
|--------|----------|------|---------|
| POST | `/api/register` | ❌ | Register new user |
| POST | `/api/login` | ❌ | Login user |
| GET | `/api/user` | ✅ | Get current user |
| POST | `/api/logout` | ✅ | Logout user |
| POST | `/api/forgot-password` | ❌ | Request password reset |
| POST | `/api/reset-password` | ❌ | Reset password |
| POST | `/api/email/send-verification-code` | ❌ | Send verification code |
| POST | `/api/email/verify-with-code` | ❌ | Verify email with code |
| POST | `/api/email/resend-verification-code` | ❌ | Resend verification code |

---

## 📝 REQUEST/RESPONSE EXAMPLES

### Register
```javascript
// Request
POST /api/register
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "password": "Password123",
  "password_confirmation": "Password123",
  "role": "student"
}

// Response (201)
{
  "status": "success",
  "message": "User registered successfully",
  "user": { /* user object */ },
  "token": "api_token_string"
}
```

### Login
```javascript
// Request
POST /api/login
{
  "email": "john@example.com",
  "password": "Password123"
}

// Response (200)
{
  "status": "success",
  "message": "Login successful",
  "user": { /* user object */ },
  "token": "api_token_string"
}
```

### Send Verification Code
```javascript
// Request
POST /api/email/send-verification-code
{
  "email": "john@example.com"
}

// Response (200)
{
  "success": true,
  "message": "Verification code sent to your email",
  "data": { "expires_in_minutes": 15 }
}
```

### Verify Email with Code
```javascript
// Request
POST /api/email/verify-with-code
{
  "email": "john@example.com",
  "code": "123456"
}

// Response (200)
{
  "success": true,
  "message": "Email verified successfully",
  "data": { "user": { /* user object */ } }
}
```

### Forgot Password
```javascript
// Request
POST /api/forgot-password
{
  "email": "john@example.com"
}

// Response (200)
{
  "success": true,
  "message": "Password reset link sent to your email"
}
```

### Reset Password
```javascript
// Request
POST /api/reset-password
{
  "token": "reset_token_from_email",
  "email": "john@example.com",
  "password": "NewPassword123",
  "password_confirmation": "NewPassword123"
}

// Response (200)
{
  "message": "Password reset successfully"
}
```

---

## 🛠️ QUICK IMPLEMENTATION

### 1. Create API Client
```javascript
// resources/js/auth-api.js
class AuthApiClient {
  async login(email, password) {
    const response = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });
    const data = await response.json();
    if (data.token) {
      localStorage.setItem('auth_token', data.token);
    }
    return data;
  }
}
const authApi = new AuthApiClient();
```

### 2. Add to Login Page
```html
<script src="{{ asset('js/auth-api.js') }}"></script>
<script>
document.querySelector('.primaryButton').addEventListener('click', async (e) => {
  e.preventDefault();
  const email = document.getElementById('emailaddress').value;
  const password = document.getElementById('psw').value;
  const response = await authApi.login(email, password);
  if (response.status === 'success') {
    window.location.href = '/dashboard';
  }
});
</script>
```

### 3. Add to Register Page
```html
<script src="{{ asset('js/auth-api.js') }}"></script>
<script>
document.querySelector('.primaryButton').addEventListener('click', async (e) => {
  e.preventDefault();
  const response = await authApi.register({
    firstName: document.getElementById('firstNameInput').value,
    lastName: document.getElementById('lastNameInput').value,
    email: document.getElementById('emailaddress').value,
    password: document.getElementById('psw').value,
    passwordConfirmation: document.getElementById('psw').value
  });
  if (response.status === 'success') {
    window.location.href = '/verify';
  }
});
</script>
```

---

## 🔑 KEY CONCEPTS

### Authentication Flow
1. User registers → receives token
2. User logs in → receives token
3. Token stored in localStorage
4. Token sent in Authorization header
5. Token used for authenticated requests

### Token Management
```javascript
// Store token
localStorage.setItem('auth_token', token);

// Retrieve token
const token = localStorage.getItem('auth_token');

// Use in requests
headers: {
  'Authorization': `Bearer ${token}`
}

// Clear token (logout)
localStorage.removeItem('auth_token');
```

### Error Handling
```javascript
if (response.status === 401) {
  // Unauthorized - redirect to login
  localStorage.removeItem('auth_token');
  window.location.href = '/login';
}

if (response.status === 422) {
  // Validation error
  console.log(response.data.errors);
}

if (response.status === 500) {
  // Server error
  console.log(response.data.message);
}
```

---

## ⚠️ COMMON ISSUES

### Issue: Token not persisting
**Solution:** Check localStorage is enabled in browser

### Issue: 401 Unauthorized
**Solution:** Ensure token is sent in Authorization header

### Issue: CORS errors
**Solution:** Check CORS configuration in Laravel

### Issue: Email not sending
**Solution:** Check mail configuration in .env

### Issue: Verification code not working
**Solution:** Check code expiration (15 minutes)

---

## 📚 RELATED DOCUMENTS

- `AUTHENTICATION_ENDPOINTS_ANALYSIS.md` - Detailed analysis
- `AUTHENTICATION_IMPLEMENTATION_GUIDE.md` - Implementation steps
- `AUTHENTICATION_TESTING_GUIDE.md` - Testing procedures
- `API_CONSUMPTION_IMPROVEMENTS.md` - General improvements

---

## 🚀 NEXT STEPS

1. ✅ Review this quick reference
2. ⏳ Create API client module
3. ⏳ Implement login page
4. ⏳ Implement register page
5. ⏳ Implement verification flow
6. ⏳ Implement password reset
7. ⏳ Add error handling
8. ⏳ Test all flows

---

**Document Version:** 1.0  
**Last Updated:** October 28, 2025

