# 🚀 Authentication Implementation - Developer Guide

## Quick Start

### Import the API Client
```javascript
import AuthApiClient from '/resources/js/api/authClient.js';
import UIHelpers from '/resources/js/utils/uiHelpers.js';
```

---

## API Client Methods

### **User Registration**
```javascript
const result = await AuthApiClient.register(
  firstName,    // string
  lastName,     // string
  email,        // string
  password,     // string
  role          // 'student' or 'instructor'
);

// Response
{
  success: true,
  data: {
    token: "...",
    user: { id, name, email, role }
  }
}
```

### **User Login**
```javascript
const result = await AuthApiClient.login(email, password);

// Response
{
  success: true,
  data: {
    token: "...",
    user: { id, name, email, role }
  }
}
```

### **Send Verification Code**
```javascript
const result = await AuthApiClient.sendVerificationCode(email);

// Response
{
  success: true,
  message: "Verification code sent"
}
```

### **Verify Email with Code**
```javascript
const result = await AuthApiClient.verifyEmailWithCode(email, code);

// Response
{
  success: true,
  message: "Email verified"
}
```

### **Resend Verification Code**
```javascript
const result = await AuthApiClient.resendVerificationCode(email);

// Response
{
  success: true,
  message: "Code resent"
}
```

### **Send Password Reset Link**
```javascript
const result = await AuthApiClient.sendPasswordResetLink(email);

// Response
{
  success: true,
  message: "Reset link sent"
}
```

### **Reset Password**
```javascript
const result = await AuthApiClient.resetPassword(
  email,
  token,
  password,
  passwordConfirmation
);

// Response
{
  success: true,
  message: "Password reset"
}
```

### **Get Current User**
```javascript
const result = await AuthApiClient.getCurrentUser();

// Response
{
  success: true,
  data: { id, name, email, role }
}
```

### **Logout**
```javascript
const result = await AuthApiClient.logout();

// Response
{
  success: true,
  message: "Logged out"
}
```

---

## Token Management

### **Set Token**
```javascript
AuthApiClient.setToken(token);
// Automatically sets Authorization header
```

### **Get Token**
```javascript
const token = AuthApiClient.getToken();
```

### **Clear Token**
```javascript
AuthApiClient.clearToken();
```

### **Check Authentication**
```javascript
if (AuthApiClient.isAuthenticated()) {
  // User is logged in
}
```

---

## User Management

### **Set User Data**
```javascript
AuthApiClient.setUser(userData);
```

### **Get User Data**
```javascript
const user = AuthApiClient.getUser();
```

### **Clear User Data**
```javascript
AuthApiClient.clearUser();
```

---

## UI Helper Methods

### **Show Alerts**
```javascript
UIHelpers.showSuccess('Operation successful');
UIHelpers.showError('An error occurred');
UIHelpers.showWarning('Warning message');
UIHelpers.showInfo('Information');
```

### **Button Loading State**
```javascript
// Store original text
UIHelpers.storeButtonText('submitBtn');

// Show loading
UIHelpers.setButtonLoading('submitBtn', true);

// Hide loading
UIHelpers.setButtonLoading('submitBtn', false);
```

### **Form Validation**
```javascript
// Email validation
if (UIHelpers.isValidEmail(email)) {
  // Valid email
}

// Password validation
if (UIHelpers.isValidPassword(password)) {
  // Strong password
}

// Get password strength message
const msg = UIHelpers.getPasswordStrengthMessage(password);
```

### **Form Operations**
```javascript
// Clear form
UIHelpers.clearForm('formId');

// Get form data
const data = UIHelpers.getFormData('formId');

// Disable form
UIHelpers.disableForm('formId', true);

// Enable form
UIHelpers.disableForm('formId', false);
```

### **Navigation**
```javascript
// Redirect with delay
UIHelpers.redirect('/dashboard', 1500);

// Get URL parameter
const token = UIHelpers.getUrlParameter('token');
```

### **Element Visibility**
```javascript
// Show element
UIHelpers.toggleElement('elementId', true);

// Hide element
UIHelpers.toggleElement('elementId', false);
```

---

## Common Patterns

### **Login Form Handler**
```javascript
document.getElementById('loginForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;

  // Validate
  if (!UIHelpers.isValidEmail(email)) {
    UIHelpers.showError('Invalid email');
    return;
  }

  // Show loading
  UIHelpers.setButtonLoading('loginBtn', true);

  // Call API
  const result = await AuthApiClient.login(email, password);

  if (result.success) {
    UIHelpers.showSuccess('Login successful!');
    UIHelpers.redirect('/dashboard', 1500);
  } else {
    UIHelpers.showError(result.message);
    UIHelpers.setButtonLoading('loginBtn', false);
  }
});
```

### **Register Form Handler**
```javascript
document.getElementById('registerForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const firstName = document.getElementById('firstName').value;
  const lastName = document.getElementById('lastName').value;
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  const role = document.getElementById('role').value;

  // Validate
  if (!UIHelpers.isValidEmail(email)) {
    UIHelpers.showError('Invalid email');
    return;
  }

  if (!UIHelpers.isValidPassword(password)) {
    UIHelpers.showError(UIHelpers.getPasswordStrengthMessage(password));
    return;
  }

  // Show loading
  UIHelpers.setButtonLoading('registerBtn', true);

  // Call API
  const result = await AuthApiClient.register(
    firstName, lastName, email, password, role
  );

  if (result.success) {
    UIHelpers.showSuccess('Registration successful!');
    UIHelpers.redirect('/verify-email', 1500);
  } else {
    UIHelpers.showError(result.message);
    UIHelpers.setButtonLoading('registerBtn', false);
  }
});
```

---

## Error Handling

All API methods return a consistent response format:

```javascript
{
  success: boolean,
  message: string,
  data?: any,
  error?: Error
}
```

### **Handle Errors**
```javascript
const result = await AuthApiClient.login(email, password);

if (!result.success) {
  console.error('Error:', result.message);
  UIHelpers.showError(result.message);
}
```

---

## Password Requirements

- Minimum 8 characters
- At least 1 uppercase letter
- At least 1 lowercase letter
- At least 1 number

---

## File Locations

| File | Purpose |
|------|---------|
| `resources/js/api/authClient.js` | API client |
| `resources/js/utils/uiHelpers.js` | UI utilities |
| `resources/views/auth/login.blade.php` | Login page |
| `resources/views/auth/register.blade.php` | Register page |
| `resources/views/auth/verify-email.blade.php` | Email verification |
| `resources/views/auth/forgotpassword.blade.php` | Forgot password |
| `resources/views/auth/resetpassword.blade.php` | Reset password |

---

## Testing

### **Test Login**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'
```

### **Test Register**
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name":"John",
    "last_name":"Doe",
    "email":"john@example.com",
    "password":"SecurePass123",
    "role":"student"
  }'
```

---

## Troubleshooting

### **Token not persisting**
- Check if localStorage is enabled
- Check browser console for errors
- Verify token is being set correctly

### **API calls failing**
- Check network tab for response
- Verify API endpoints are correct
- Check backend logs for errors

### **Forms not submitting**
- Check browser console for JavaScript errors
- Verify form IDs match in HTML and JavaScript
- Check if event listeners are attached

### **Redirects not working**
- Check if URL is correct
- Verify redirect delay is sufficient
- Check browser console for errors

---

## Best Practices

1. Always validate inputs before API calls
2. Show loading states during API calls
3. Display user-friendly error messages
4. Store tokens securely in localStorage
5. Clear tokens on logout
6. Use Bearer token in Authorization header
7. Handle all error cases
8. Test all authentication flows
9. Use HTTPS in production
10. Implement rate limiting on backend

---

## Support

For issues or questions, refer to:
- `AUTHENTICATION_IMPLEMENTATION_COMPLETE.md` - Full implementation details
- `AUTHENTICATION_TESTING_GUIDE.md` - Testing procedures
- Backend API documentation

