# 🧪 AUTHENTICATION TESTING GUIDE

**Project:** Kokokah.com LMS  
**Date:** October 28, 2025  
**Purpose:** Comprehensive testing guide for authentication endpoints

---

## 📋 TEST SCENARIOS

### TEST SUITE 1: REGISTRATION ENDPOINT

#### Test 1.1: Successful Registration
**Endpoint:** `POST /api/register`  
**Expected Status:** 201

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "password": "Password123",
    "password_confirmation": "Password123",
    "role": "student"
  }'
```

**Expected Response:**
```json
{
  "status": "success",
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "role": "student"
  },
  "token": "api_token_string"
}
```

**Verification:**
- [ ] Status code is 201
- [ ] Response contains token
- [ ] User object has correct data
- [ ] Token can be used for authenticated requests

---

#### Test 1.2: Registration with Duplicate Email
**Expected Status:** 422

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Jane",
    "last_name": "Doe",
    "email": "john@example.com",
    "password": "Password123",
    "password_confirmation": "Password123"
  }'
```

**Expected Response:**
```json
{
  "message": "The email has already been taken.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

---

#### Test 1.3: Registration with Invalid Password
**Expected Status:** 422

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Jane",
    "last_name": "Doe",
    "email": "jane@example.com",
    "password": "short",
    "password_confirmation": "short"
  }'
```

**Expected Response:**
```json
{
  "message": "The password must be at least 8 characters.",
  "errors": {
    "password": ["The password must be at least 8 characters."]
  }
}
```

---

### TEST SUITE 2: LOGIN ENDPOINT

#### Test 2.1: Successful Login
**Endpoint:** `POST /api/login`  
**Expected Status:** 200

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "Password123"
  }'
```

**Expected Response:**
```json
{
  "status": "success",
  "message": "Login successful",
  "user": {
    "id": 1,
    "email": "john@example.com",
    "first_name": "John"
  },
  "token": "api_token_string"
}
```

---

#### Test 2.2: Login with Invalid Credentials
**Expected Status:** 401

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "WrongPassword"
  }'
```

**Expected Response:**
```json
{
  "message": "Invalid credentials"
}
```

---

#### Test 2.3: Login with Non-existent Email
**Expected Status:** 422

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "nonexistent@example.com",
    "password": "Password123"
  }'
```

---

### TEST SUITE 3: EMAIL VERIFICATION

#### Test 3.1: Send Verification Code
**Endpoint:** `POST /api/email/send-verification-code`  
**Expected Status:** 200

```bash
curl -X POST http://localhost:8000/api/email/send-verification-code \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Verification code sent to your email",
  "data": {
    "expires_in_minutes": 15
  }
}
```

---

#### Test 3.2: Verify Email with Code
**Endpoint:** `POST /api/email/verify-with-code`  
**Expected Status:** 200

```bash
curl -X POST http://localhost:8000/api/email/verify-with-code \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "code": "123456"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Email verified successfully",
  "data": {
    "user": { /* user object */ }
  }
}
```

---

#### Test 3.3: Verify with Invalid Code
**Expected Status:** 400

```bash
curl -X POST http://localhost:8000/api/email/verify-with-code \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "code": "000000"
  }'
```

**Expected Response:**
```json
{
  "success": false,
  "message": "Invalid verification code"
}
```

---

### TEST SUITE 4: PASSWORD RESET

#### Test 4.1: Request Password Reset
**Endpoint:** `POST /api/forgot-password`  
**Expected Status:** 200

```bash
curl -X POST http://localhost:8000/api/forgot-password \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Password reset link sent to your email"
}
```

---

#### Test 4.2: Reset Password
**Endpoint:** `POST /api/reset-password`  
**Expected Status:** 200

```bash
curl -X POST http://localhost:8000/api/reset-password \
  -H "Content-Type: application/json" \
  -d '{
    "token": "reset_token_from_email",
    "email": "john@example.com",
    "password": "NewPassword123",
    "password_confirmation": "NewPassword123"
  }'
```

**Expected Response:**
```json
{
  "message": "Password reset successfully"
}
```

---

### TEST SUITE 5: AUTHENTICATED ENDPOINTS

#### Test 5.1: Get Current User
**Endpoint:** `GET /api/user`  
**Expected Status:** 200  
**Auth Required:** ✅ Bearer Token

```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer api_token_string"
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "email": "john@example.com",
    "first_name": "John"
  }
}
```

---

#### Test 5.2: Get User Without Token
**Expected Status:** 401

```bash
curl -X GET http://localhost:8000/api/user
```

**Expected Response:**
```json
{
  "message": "Unauthenticated."
}
```

---

## 🧪 FRONTEND TESTING CHECKLIST

### Login Page Tests
- [ ] Form submits on button click
- [ ] Email validation works
- [ ] Password field is masked
- [ ] Loading state shows during request
- [ ] Success redirects to dashboard
- [ ] Error message displays on failure
- [ ] Token stored in localStorage
- [ ] "Forgot Password" link works

### Register Page Tests
- [ ] All form fields required
- [ ] Email validation works
- [ ] Password confirmation matches
- [ ] Role selection works
- [ ] Social login buttons visible
- [ ] Loading state shows during request
- [ ] Success redirects to verify page
- [ ] Error message displays on failure
- [ ] Duplicate email error shows

### Verification Page Tests
- [ ] Code input accepts 6 digits
- [ ] Verify button submits code
- [ ] Loading state shows
- [ ] Success redirects to dashboard
- [ ] Resend link works
- [ ] Error message displays on failure

### Forgot Password Tests
- [ ] Email input required
- [ ] Submit sends reset link
- [ ] Success message displays
- [ ] Error message on failure

### Reset Password Tests
- [ ] Password fields required
- [ ] Passwords must match
- [ ] Submit resets password
- [ ] Success message displays
- [ ] Can login with new password

---

## 🔍 DEBUGGING TIPS

### Check Network Requests
1. Open DevTools (F12)
2. Go to Network tab
3. Perform action
4. Check request/response

### Check localStorage
```javascript
// In DevTools Console
localStorage.getItem('auth_token')
localStorage.getItem('verification_email')
```

### Check API Response
```javascript
// In DevTools Console
fetch('/api/login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ email: 'test@example.com', password: 'password' })
}).then(r => r.json()).then(console.log)
```

---

## 📊 TEST RESULTS TEMPLATE

| Test Case | Status | Notes |
|-----------|--------|-------|
| Register - Success | ✅/❌ | |
| Register - Duplicate Email | ✅/❌ | |
| Login - Success | ✅/❌ | |
| Login - Invalid Credentials | ✅/❌ | |
| Verify Email - Send Code | ✅/❌ | |
| Verify Email - Verify Code | ✅/❌ | |
| Forgot Password | ✅/❌ | |
| Reset Password | ✅/❌ | |
| Get Current User | ✅/❌ | |
| Frontend - Login Form | ✅/❌ | |
| Frontend - Register Form | ✅/❌ | |
| Frontend - Verify Form | ✅/❌ | |

---

**Document Version:** 1.0  
**Last Updated:** October 28, 2025

