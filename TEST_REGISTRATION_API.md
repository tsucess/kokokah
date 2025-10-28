# Test Registration API

## 🧪 How to Debug the 422 Error

### Step 1: Open Browser DevTools
1. Press `F12` to open DevTools
2. Go to the **Network** tab
3. Go to the **Console** tab

### Step 2: Try to Register
1. Fill in the registration form with test data:
   - First Name: `John`
   - Last Name: `Doe`
   - Email: `john@example.com`
   - Password: `Password123!`
   - Role: `Student`
2. Click Register

### Step 3: Check Network Tab
1. Look for the `register` request
2. Click on it
3. Go to **Response** tab
4. You should see the validation errors in JSON format

### Step 4: Check Console Tab
1. Look for error messages
2. The enhanced error handler will show the specific field error

---

## 📋 Expected Validation Errors

The backend validates:
```php
'first_name' => 'required|string|max:255',
'last_name' => 'required|string|max:255',
'email' => 'required|email|unique:users',
'password' => 'required|string|min:8|confirmed',
'role' => 'nullable|in:student,instructor,admin'
```

### Possible Errors:
- **first_name**: Must be provided, string, max 255 characters
- **last_name**: Must be provided, string, max 255 characters
- **email**: Must be valid email format, must be unique (not already registered)
- **password**: Must be 8+ characters, must match password_confirmation
- **role**: Must be one of: student, instructor, admin (or null)

---

## 🔧 Test Data

### Valid Registration
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "password": "Password123!",
  "password_confirmation": "Password123!",
  "role": "student"
}
```

### Invalid Examples
```json
// Missing first_name
{
  "last_name": "Doe",
  "email": "john@example.com",
  "password": "Password123!",
  "password_confirmation": "Password123!",
  "role": "student"
}

// Password too short
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "password": "Pass123",
  "password_confirmation": "Pass123",
  "role": "student"
}

// Email already exists
{
  "first_name": "Jane",
  "last_name": "Smith",
  "email": "john@example.com",  // Already registered
  "password": "Password123!",
  "password_confirmation": "Password123!",
  "role": "student"
}
```

---

## 📊 What to Look For

### In Network Tab Response:
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "first_name": ["The first name field is required."],
    "email": ["The email has already been taken."]
  }
}
```

### In Console:
The error message will show the first validation error, e.g.:
```
"The first name field is required."
```

---

## ✅ Success Response

When registration succeeds, you should see:
```json
{
  "status": "success",
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "role": "student",
    ...
  },
  "token": "api_token_string"
}
```

---

## 🚀 Next Steps

1. Hard refresh browser (Ctrl+Shift+R)
2. Try registering with the test data above
3. Check DevTools Network tab for the response
4. Report the exact error message you see
5. We can then fix the specific validation issue

---

**Last Updated**: 2025-10-28

