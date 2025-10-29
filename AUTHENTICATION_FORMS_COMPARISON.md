# Authentication Forms - Detailed Comparison

## 1. LOGIN FORM

### Current Structure
```
✅ Email input (id: emailaddress, name: email)
✅ Password input (id: psw, name: password)
✅ Remember me checkbox
✅ Forgot password link
✅ Sign up link
✅ Alert container
✅ Form validation
✅ Loading state
❌ CSRF token
❌ Password visibility toggle
❌ Accessibility attributes
```

### Issues
- Input ID `emailaddress` doesn't match name `email`
- No CSRF protection
- No password visibility toggle
- Missing ARIA labels

### Recommended Changes
```html
<form id="loginForm">
  @csrf
  
  <div class="custom-form-group">
    <label for="email" class="custom-label">Email</label>
    <input 
      type="email" 
      class="form-control-custom" 
      id="email" 
      name="email" 
      placeholder="majorsignature@gmail.com"
      aria-label="Email Address"
      autocomplete="email"
      required
    >
  </div>

  <div class="custom-form-group mb-2">
    <label for="password" class="custom-label">Password</label>
    <div class="input-group">
      <input 
        type="password" 
        class="form-control-custom" 
        id="password" 
        name="password" 
        placeholder="*******"
        aria-label="Password"
        autocomplete="current-password"
        required
      >
      <button class="btn btn-outline-secondary" type="button" id="togglePassword">
        <i class="fa fa-eye"></i>
      </button>
    </div>
  </div>
  
  <!-- Rest of form -->
</form>
```

---

## 2. REGISTER FORM

### Current Structure
```
✅ First name input
✅ Last name input
✅ Email input
✅ Password input with strength indicator
✅ Role selection dropdown
✅ Form validation
✅ Loading state
✅ Social login buttons (disabled)
❌ CSRF token
❌ Password visibility toggle
❌ Email storage for verification
❌ Accessibility attributes
```

### Issues
- No CSRF protection
- Email not stored for verification page
- No password visibility toggle
- Inconsistent input naming
- Missing accessibility attributes

### Recommended Changes
```html
<form id="registerForm">
  @csrf
  
  <div class="custom-form-group">
    <label for="firstName" class="custom-label">First Name</label>
    <input 
      type="text" 
      class="form-control-custom" 
      id="firstName" 
      name="firstName" 
      placeholder="Winner"
      aria-label="First Name"
      autocomplete="given-name"
      required
    >
  </div>

  <div class="custom-form-group">
    <label for="lastName" class="custom-label">Last Name</label>
    <input 
      type="text" 
      class="form-control-custom" 
      id="lastName" 
      name="lastName" 
      placeholder="Effiong"
      aria-label="Last Name"
      autocomplete="family-name"
      required
    >
  </div>

  <div class="custom-form-group">
    <label for="email" class="custom-label">Email Address</label>
    <input 
      type="email" 
      class="form-control-custom" 
      id="email" 
      name="email" 
      placeholder="majorsignature@gmail.com"
      aria-label="Email Address"
      autocomplete="email"
      required
    >
  </div>

  <div class="custom-form-group">
    <label for="password" class="custom-label">Password</label>
    <div class="input-group">
      <input 
        type="password" 
        class="form-control-custom" 
        id="password" 
        name="password" 
        placeholder="*******"
        aria-label="Password"
        aria-describedby="passwordStrength"
        autocomplete="new-password"
        required
      >
      <button class="btn btn-outline-secondary" type="button" id="togglePassword">
        <i class="fa fa-eye"></i>
      </button>
    </div>
    <small class="text-muted d-block mt-1" id="passwordStrength"></small>
  </div>

  <div class="custom-form-group">
    <label for="role" class="custom-label">Select Role</label>
    <select 
      class="form-control-custom" 
      id="role" 
      name="role"
      aria-label="User Role"
      required
    >
      <option value="">-- Select Role --</option>
      <option value="student">Student</option>
      <option value="instructor">Instructor</option>
    </select>
  </div>

  <button type="submit" class="btn primaryButton" id="registerBtn">Sign Up</button>
</form>

<script>
// Store email for verification page
document.getElementById('registerForm').addEventListener('submit', async (e) => {
  // ... validation code ...
  
  const email = document.getElementById('email').value.trim();
  sessionStorage.setItem('registerEmail', email);
  
  // ... API call ...
});
</script>
```

---

## 3. EMAIL VERIFICATION FORM

### Current Structure
```
✅ 6-digit code input with maxlength
✅ Resend link
✅ Form validation
✅ Loading state
✅ Back to login link
❌ CSRF token
❌ Email display
❌ Accessibility attributes
```

### Issues
- No CSRF protection
- Email not displayed to user
- No confirmation of which email code was sent to
- Missing accessibility attributes

### Recommended Changes
```html
<form id="verifyForm">
  @csrf
  
  <div class="alert alert-info">
    <p>Verification code sent to: <strong id="emailDisplay"></strong></p>
  </div>

  <div class="custom-form-group">
    <label for="code" class="custom-label">Enter Code</label>
    <input 
      type="text" 
      class="form-control-custom" 
      id="code" 
      name="code" 
      placeholder="000000" 
      maxlength="6"
      inputmode="numeric"
      pattern="[0-9]{6}"
      aria-label="Verification Code"
      required
    >
  </div>

  <p>
    Did not receive a code?
    <a href="#" id="resendLink" style="color: #FDAF22; cursor: pointer;">Resend</a>
  </p>

  <button type="submit" class="btn primaryButton w-100" id="verifyBtn">Verify</button>
</form>

<script>
// Display email to user
const email = UIHelpers.getUrlParameter('email') || sessionStorage.getItem('registerEmail');
document.getElementById('emailDisplay').textContent = email;
</script>
```

---

## 4. FORGOT PASSWORD FORM

### Current Structure
```
✅ Email input
✅ Form validation
✅ Loading state
✅ Back to login link
❌ CSRF token
❌ Accessibility attributes
```

### Issues
- No CSRF protection
- Missing accessibility attributes
- No success message persistence

---

## 5. RESET PASSWORD FORM

### Current Structure
```
✅ Password input with strength indicator
✅ Confirm password input
✅ Form validation
✅ Loading state
✅ Back to login link
❌ CSRF token
❌ Password visibility toggle
❌ Accessibility attributes
```

### Issues
- No CSRF protection
- No password visibility toggle
- Missing accessibility attributes
- No token/email validation feedback

---

## Summary Table

| Form | CSRF | Accessibility | Password Toggle | Email Display | Validation |
|------|------|----------------|-----------------|----------------|------------|
| Login | ❌ | ❌ | ❌ | N/A | ✅ |
| Register | ❌ | ❌ | ❌ | N/A | ✅ |
| Verify Email | ❌ | ❌ | N/A | ❌ | ✅ |
| Forgot Password | ❌ | ❌ | N/A | N/A | ✅ |
| Reset Password | ❌ | ❌ | ❌ | ❌ | ✅ |

**Overall Completion: 40% (2/5 critical features)**

---

## Priority Fixes

### Must Do (This Week)
1. Add CSRF tokens to all forms
2. Add password visibility toggles
3. Add accessibility attributes

### Should Do (Next Week)
1. Standardize input naming
2. Add email display on verification
3. Improve error messages

### Nice to Have (Later)
1. Add form animations
2. Add success animations
3. Add keyboard shortcuts

