# Email Verification Code System - Usage Guide

**Quick Reference for Developers**

---

## 🚀 Quick Start (3 Steps)

### Step 1: Run Migration
```bash
php artisan migrate
```

### Step 2: Send Verification Code
```bash
curl -X POST http://localhost:8000/api/email/send-verification-code \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com"}'
```

### Step 3: Verify Email with Code
```bash
curl -X POST http://localhost:8000/api/email/verify-with-code \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","code":"ABC123"}'
```

---

## 📡 API Endpoints

### Public Endpoints (No Authentication Required)

#### 1. Send Verification Code
```
POST /api/email/send-verification-code
Content-Type: application/json

{
  "email": "user@example.com"
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Verification code sent to your email",
  "data": {
    "expires_in_minutes": 15,
    "code_length": 6
  }
}
```

**Response (Error - Already Verified):**
```json
{
  "success": false,
  "message": "Email already verified"
}
```

---

#### 2. Verify Email with Code
```
POST /api/email/verify-with-code
Content-Type: application/json

{
  "email": "user@example.com",
  "code": "ABC123"
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Email verified successfully",
  "data": {
    "user": { ... },
    "verified_at": "2025-10-26T10:30:00Z"
  }
}
```

**Response (Error - Invalid Code):**
```json
{
  "success": false,
  "message": "Invalid or expired verification code"
}
```

**Response (Error - Too Many Attempts):**
```json
{
  "success": false,
  "message": "Too many failed attempts. Please request a new code."
}
```

---

#### 3. Resend Verification Code
```
POST /api/email/resend-verification-code
Content-Type: application/json

{
  "email": "user@example.com"
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "New verification code sent to your email",
  "data": {
    "expires_in_minutes": 15
  }
}
```

---

### Authenticated Endpoints (Bearer Token Required)

#### 1. Send Code (Authenticated)
```
POST /api/email/send-code
Authorization: Bearer {token}
Content-Type: application/json

{
  "email": "user@example.com"
}
```

#### 2. Verify Code (Authenticated)
```
POST /api/email/verify-code
Authorization: Bearer {token}
Content-Type: application/json

{
  "email": "user@example.com",
  "code": "ABC123"
}
```

#### 3. Resend Code (Authenticated)
```
POST /api/email/resend-code
Authorization: Bearer {token}
Content-Type: application/json

{
  "email": "user@example.com"
}
```

---

## 💻 Frontend Integration Examples

### React Component Example

```jsx
import { useState } from 'react';

export function EmailVerification() {
  const [email, setEmail] = useState('');
  const [code, setCode] = useState('');
  const [step, setStep] = useState('email'); // 'email' or 'code'
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  const sendCode = async () => {
    setLoading(true);
    setError('');
    
    try {
      const response = await fetch('/api/email/send-verification-code', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
      });
      
      const data = await response.json();
      
      if (data.success) {
        setSuccess('Code sent! Check your email.');
        setStep('code');
      } else {
        setError(data.message);
      }
    } catch (err) {
      setError('Failed to send code');
    } finally {
      setLoading(false);
    }
  };

  const verifyCode = async () => {
    setLoading(true);
    setError('');
    
    try {
      const response = await fetch('/api/email/verify-with-code', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, code })
      });
      
      const data = await response.json();
      
      if (data.success) {
        setSuccess('Email verified successfully!');
        // Redirect or update state
      } else {
        setError(data.message);
      }
    } catch (err) {
      setError('Verification failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="verification-container">
      {step === 'email' ? (
        <div>
          <h2>Verify Your Email</h2>
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            placeholder="Enter your email"
          />
          <button onClick={sendCode} disabled={loading}>
            {loading ? 'Sending...' : 'Send Code'}
          </button>
        </div>
      ) : (
        <div>
          <h2>Enter Verification Code</h2>
          <p>Code sent to {email}</p>
          <input
            type="text"
            value={code}
            onChange={(e) => setCode(e.target.value.toUpperCase())}
            placeholder="Enter 6-character code"
            maxLength="6"
          />
          <button onClick={verifyCode} disabled={loading}>
            {loading ? 'Verifying...' : 'Verify'}
          </button>
          <button onClick={() => setStep('email')}>
            Use different email
          </button>
        </div>
      )}
      
      {error && <div className="error">{error}</div>}
      {success && <div className="success">{success}</div>}
    </div>
  );
}
```

---

### Vue Component Example

```vue
<template>
  <div class="verification-container">
    <div v-if="step === 'email'">
      <h2>Verify Your Email</h2>
      <input
        v-model="email"
        type="email"
        placeholder="Enter your email"
      />
      <button @click="sendCode" :disabled="loading">
        {{ loading ? 'Sending...' : 'Send Code' }}
      </button>
    </div>
    
    <div v-else>
      <h2>Enter Verification Code</h2>
      <p>Code sent to {{ email }}</p>
      <input
        v-model="code"
        type="text"
        placeholder="Enter 6-character code"
        maxlength="6"
        @input="code = code.toUpperCase()"
      />
      <button @click="verifyCode" :disabled="loading">
        {{ loading ? 'Verifying...' : 'Verify' }}
      </button>
      <button @click="step = 'email'">Use different email</button>
    </div>
    
    <div v-if="error" class="error">{{ error }}</div>
    <div v-if="success" class="success">{{ success }}</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: '',
      code: '',
      step: 'email',
      loading: false,
      error: '',
      success: ''
    };
  },
  methods: {
    async sendCode() {
      this.loading = true;
      this.error = '';
      
      try {
        const response = await fetch('/api/email/send-verification-code', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email: this.email })
        });
        
        const data = await response.json();
        
        if (data.success) {
          this.success = 'Code sent! Check your email.';
          this.step = 'code';
        } else {
          this.error = data.message;
        }
      } catch (err) {
        this.error = 'Failed to send code';
      } finally {
        this.loading = false;
      }
    },
    
    async verifyCode() {
      this.loading = true;
      this.error = '';
      
      try {
        const response = await fetch('/api/email/verify-with-code', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            email: this.email,
            code: this.code
          })
        });
        
        const data = await response.json();
        
        if (data.success) {
          this.success = 'Email verified successfully!';
        } else {
          this.error = data.message;
        }
      } catch (err) {
        this.error = 'Verification failed';
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>
```

---

## 🔧 Backend Usage (PHP/Laravel)

### Using the Model Directly

```php
use App\Models\VerificationCode;
use App\Models\User;

// Create verification code
$user = User::find(1);
$code = VerificationCode::createForUser($user, 'email', 15);

// Verify code
$verification = VerificationCode::verify($user->id, 'ABC123', 'email');

if ($verification) {
    $user->markEmailAsVerified();
    $verification->markAsUsed();
}

// Check if code is valid
if ($code->isValid()) {
    // Code is valid
}

// Check if code is expired
if ($code->isExpired()) {
    // Code has expired
}

// Check if max attempts exceeded
if ($code->hasExceededAttempts()) {
    // Too many failed attempts
}
```

---

## 📊 Database Queries

### Get Active Codes for User
```php
$codes = VerificationCode::forUser($userId)
    ->byType('email')
    ->active()
    ->get();
```

### Get All Codes for User
```php
$codes = VerificationCode::forUser($userId)->get();
```

### Get Expired Codes
```php
$expired = VerificationCode::where('expires_at', '<', now())->get();
```

### Get Used Codes
```php
$used = VerificationCode::whereNotNull('used_at')->get();
```

---

## ⚙️ Configuration

### Change Code Expiration Time
In `AuthController::sendVerificationCode()`:
```php
// Change from 15 to 30 minutes
$verificationCode = VerificationCode::createForUser($user, 'email', 30);
```

### Change Code Length
In `VerificationCode::generateCode()`:
```php
// Change from 6 to 8 characters
public static function generateCode($length = 8)
```

### Change Max Attempts
In `VerificationCode::createForUser()`:
```php
'max_attempts' => 10 // Change from 5 to 10
```

---

## 🧪 Testing

### Test with Postman

1. **Send Code:**
   - Method: POST
   - URL: `http://localhost:8000/api/email/send-verification-code`
   - Body: `{"email":"test@example.com"}`

2. **Verify Code:**
   - Method: POST
   - URL: `http://localhost:8000/api/email/verify-with-code`
   - Body: `{"email":"test@example.com","code":"ABC123"}`

3. **Resend Code:**
   - Method: POST
   - URL: `http://localhost:8000/api/email/resend-verification-code`
   - Body: `{"email":"test@example.com"}`

---

## 📞 Troubleshooting

| Issue | Solution |
|-------|----------|
| "Table doesn't exist" | Run `php artisan migrate` |
| "Codes not sending" | Check `.env` mail configuration |
| "Too many attempts" | User needs to request new code |
| "Code not working" | Check code expiration and format |
| "Email not received" | Check spam folder, verify email config |

---

## ✨ Features Summary

- ✅ 6-character alphanumeric codes
- ✅ 15-minute expiration
- ✅ 5 attempt limit
- ✅ Email notifications
- ✅ Code invalidation
- ✅ Public and authenticated routes
- ✅ Attempt tracking
- ✅ Case-insensitive codes
- ✅ Database indexed
- ✅ Production ready

---

*Last Updated: October 26, 2025*

