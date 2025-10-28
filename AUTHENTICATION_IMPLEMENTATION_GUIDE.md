# 🚀 AUTHENTICATION IMPLEMENTATION GUIDE

**Project:** Kokokah.com LMS  
**Date:** October 28, 2025  
**Purpose:** Complete guide to implement frontend API integration for auth pages

---

## 📋 IMPLEMENTATION ROADMAP

### Phase 1: Create API Client Module (1-2 hours)
### Phase 2: Implement Login Page (1-2 hours)
### Phase 3: Implement Register Page (1-2 hours)
### Phase 4: Implement Email Verification (1-2 hours)
### Phase 5: Implement Password Reset (1-2 hours)
### Phase 6: Add Error Handling & UX (2-3 hours)

**Total Estimated Time:** 8-13 hours

---

## 🔧 PHASE 1: CREATE API CLIENT MODULE

### Step 1.1: Create `resources/js/auth-api.js`

```javascript
// API Configuration
const API_BASE_URL = '/api';
const API_TIMEOUT = 30000; // 30 seconds

class AuthApiClient {
  constructor(baseURL = API_BASE_URL) {
    this.baseURL = baseURL;
    this.token = localStorage.getItem('auth_token');
  }

  // Helper method for API calls
  async request(endpoint, options = {}) {
    const url = `${this.baseURL}${endpoint}`;
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...options.headers
    };

    if (this.token) {
      headers['Authorization'] = `Bearer ${this.token}`;
    }

    try {
      const response = await fetch(url, {
        ...options,
        headers,
        timeout: API_TIMEOUT
      });

      const data = await response.json();

      if (response.status === 401) {
        this.logout();
        window.location.href = '/login';
      }

      return {
        status: response.status,
        success: response.ok,
        data: data
      };
    } catch (error) {
      return {
        status: 0,
        success: false,
        error: error.message
      };
    }
  }

  // Authentication Methods
  async register(userData) {
    return this.request('/register', {
      method: 'POST',
      body: JSON.stringify({
        first_name: userData.firstName,
        last_name: userData.lastName,
        email: userData.email,
        password: userData.password,
        password_confirmation: userData.passwordConfirmation,
        role: userData.role || 'student'
      })
    });
  }

  async login(email, password) {
    const response = await this.request('/login', {
      method: 'POST',
      body: JSON.stringify({ email, password })
    });

    if (response.success && response.data.token) {
      this.setToken(response.data.token);
    }

    return response;
  }

  async logout() {
    localStorage.removeItem('auth_token');
    this.token = null;
  }

  async sendVerificationCode(email) {
    return this.request('/email/send-verification-code', {
      method: 'POST',
      body: JSON.stringify({ email })
    });
  }

  async verifyEmailWithCode(email, code) {
    return this.request('/email/verify-with-code', {
      method: 'POST',
      body: JSON.stringify({ email, code })
    });
  }

  async resendVerificationCode(email) {
    return this.request('/email/resend-verification-code', {
      method: 'POST',
      body: JSON.stringify({ email })
    });
  }

  async forgotPassword(email) {
    return this.request('/forgot-password', {
      method: 'POST',
      body: JSON.stringify({ email })
    });
  }

  async resetPassword(token, email, password, passwordConfirmation) {
    return this.request('/reset-password', {
      method: 'POST',
      body: JSON.stringify({
        token,
        email,
        password,
        password_confirmation: passwordConfirmation
      })
    });
  }

  async getCurrentUser() {
    return this.request('/user', { method: 'GET' });
  }

  // Token Management
  setToken(token) {
    this.token = token;
    localStorage.setItem('auth_token', token);
  }

  getToken() {
    return this.token;
  }

  isAuthenticated() {
    return !!this.token;
  }
}

// Create global instance
const authApi = new AuthApiClient();
```

---

## 🔧 PHASE 2: IMPLEMENT LOGIN PAGE

### Step 2.1: Update `resources/views/auth/login.blade.php`

Add this script before closing `</body>` tag:

```html
<script src="{{ asset('js/auth-api.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form') || createForm();
  const emailInput = document.getElementById('emailaddress');
  const passwordInput = document.getElementById('psw');
  const submitBtn = document.querySelector('.primaryButton');

  submitBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    
    const email = emailInput.value.trim();
    const password = passwordInput.value;

    if (!email || !password) {
      showError('Please fill in all fields');
      return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing in...';

    const response = await authApi.login(email, password);

    if (response.success) {
      showSuccess('Login successful! Redirecting...');
      setTimeout(() => {
        window.location.href = '/dashboard';
      }, 1500);
    } else {
      showError(response.data?.message || 'Login failed');
      submitBtn.disabled = false;
      submitBtn.innerHTML = 'Sign in';
    }
  });
});

function showError(message) {
  const alert = document.createElement('div');
  alert.className = 'alert alert-danger alert-dismissible fade show';
  alert.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
  document.querySelector('.signup-form').prepend(alert);
}

function showSuccess(message) {
  const alert = document.createElement('div');
  alert.className = 'alert alert-success alert-dismissible fade show';
  alert.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
  document.querySelector('.signup-form').prepend(alert);
}
</script>
```

---

## 🔧 PHASE 3: IMPLEMENT REGISTER PAGE

### Step 3.1: Update `resources/views/auth/register.blade.php`

Add this script before closing `</body>` tag:

```html
<script src="{{ asset('js/auth-api.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const firstNameInput = document.getElementById('firstNameInput');
  const lastNameInput = document.getElementById('lastNameInput');
  const emailInput = document.getElementById('emailaddress');
  const passwordInput = document.getElementById('psw');
  const roleSelect = document.querySelector('select');
  const submitBtn = document.querySelector('.primaryButton');

  submitBtn.addEventListener('click', async (e) => {
    e.preventDefault();

    const userData = {
      firstName: firstNameInput.value.trim(),
      lastName: lastNameInput.value.trim(),
      email: emailInput.value.trim(),
      password: passwordInput.value,
      passwordConfirmation: passwordInput.value,
      role: roleSelect.value || 'student'
    };

    if (!userData.firstName || !userData.lastName || !userData.email || !userData.password) {
      showError('Please fill in all fields');
      return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating account...';

    const response = await authApi.register(userData);

    if (response.success) {
      showSuccess('Registration successful! Redirecting...');
      setTimeout(() => {
        window.location.href = '/verify';
      }, 1500);
    } else {
      showError(response.data?.message || 'Registration failed');
      submitBtn.disabled = false;
      submitBtn.innerHTML = 'Sign Up';
    }
  });
});
</script>
```

---

## 🔧 PHASE 4: IMPLEMENT EMAIL VERIFICATION

### Step 4.1: Update `resources/views/auth/verifypassword.blade.php`

```html
<script src="{{ asset('js/auth-api.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const codeInput = document.getElementById('verifycode');
  const verifyBtn = document.querySelector('.primaryButton');
  const resendLink = document.querySelector('a[style*="color:red"]');
  const email = localStorage.getItem('verification_email');

  verifyBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    const code = codeInput.value.trim();

    if (!code || code.length !== 6) {
      showError('Please enter a valid 6-digit code');
      return;
    }

    verifyBtn.disabled = true;
    verifyBtn.innerHTML = 'Verifying...';

    const response = await authApi.verifyEmailWithCode(email, code);

    if (response.success) {
      showSuccess('Email verified! Redirecting...');
      setTimeout(() => {
        window.location.href = '/dashboard';
      }, 1500);
    } else {
      showError(response.data?.message || 'Verification failed');
      verifyBtn.disabled = false;
      verifyBtn.innerHTML = 'Verify';
    }
  });

  resendLink.addEventListener('click', async (e) => {
    e.preventDefault();
    const response = await authApi.resendVerificationCode(email);
    if (response.success) {
      showSuccess('Code resent to your email');
    } else {
      showError('Failed to resend code');
    }
  });
});
</script>
```

---

## 🔧 PHASE 5: IMPLEMENT PASSWORD RESET

### Step 5.1: Update `resources/views/auth/forgotpassword.blade.php`

```html
<script src="{{ asset('js/auth-api.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const emailInput = document.getElementById('emailaddress');
  const submitBtn = document.querySelector('.primaryButton');

  submitBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    const email = emailInput.value.trim();

    if (!email) {
      showError('Please enter your email');
      return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Sending...';

    const response = await authApi.forgotPassword(email);

    if (response.success) {
      showSuccess('Reset link sent to your email');
      localStorage.setItem('reset_email', email);
    } else {
      showError(response.data?.message || 'Failed to send reset link');
      submitBtn.disabled = false;
      submitBtn.innerHTML = 'Submit';
    }
  });
});
</script>
```

---

## 📊 TESTING CHECKLIST

- [ ] Login with valid credentials
- [ ] Login with invalid credentials
- [ ] Register new user
- [ ] Verify email with code
- [ ] Resend verification code
- [ ] Forgot password flow
- [ ] Reset password flow
- [ ] Token storage in localStorage
- [ ] Error messages display
- [ ] Loading states work
- [ ] Redirect after success
- [ ] 401 logout handling

---

**Next Document:** AUTHENTICATION_TESTING_GUIDE.md

