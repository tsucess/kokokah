# Email Verification Code - Quick Reference

## 🚀 Quick Start

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Send Code to User
```bash
curl -X POST http://localhost:8000/api/email/send-verification-code \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com"}'
```

### 3. Verify with Code
```bash
curl -X POST http://localhost:8000/api/email/verify-with-code \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","code":"ABC123"}'
```

---

## 📋 API Endpoints Summary

| Method | Endpoint | Auth | Purpose |
|--------|----------|------|---------|
| POST | `/api/email/send-verification-code` | ❌ | Send code to email |
| POST | `/api/email/verify-with-code` | ❌ | Verify email with code |
| POST | `/api/email/resend-verification-code` | ❌ | Resend code |
| POST | `/api/email/send-code` | ✅ | Send code (authenticated) |
| POST | `/api/email/verify-code` | ✅ | Verify code (authenticated) |
| POST | `/api/email/resend-code` | ✅ | Resend code (authenticated) |

---

## 📁 Files Created

```
app/
  Models/
    └── VerificationCode.php
  Notifications/
    └── VerificationCodeNotification.php

database/
  migrations/
    └── 2025_10_26_000000_create_verification_codes_table.php

Documentation/
  ├── VERIFICATION_CODE_IMPLEMENTATION.md
  ├── VERIFICATION_CODE_SETUP_GUIDE.md
  └── VERIFICATION_CODE_QUICK_REFERENCE.md
```

---

## 📝 Files Modified

```
app/Http/Controllers/AuthController.php
  + sendVerificationCode()
  + verifyEmailWithCode()
  + resendVerificationCode()

routes/api.php
  + 6 new routes for verification codes
```

---

## 🔧 Configuration

### Code Expiration
**Default:** 15 minutes
**Location:** `AuthController::sendVerificationCode()`
```php
VerificationCode::createForUser($user, 'email', 15); // Change 15 to desired minutes
```

### Code Length
**Default:** 6 characters
**Location:** `VerificationCode::generateCode()`
```php
public static function generateCode($length = 6) // Change 6 to desired length
```

### Max Attempts
**Default:** 5 attempts
**Location:** `VerificationCode::createForUser()`
```php
'max_attempts' => 5 // Change 5 to desired attempts
```

---

## 💡 Usage Examples

### JavaScript/Fetch

```javascript
// Step 1: Send code
const sendResponse = await fetch('/api/email/send-verification-code', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ email: 'user@example.com' })
});

// Step 2: Verify code
const verifyResponse = await fetch('/api/email/verify-with-code', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    email: 'user@example.com',
    code: 'ABC123'
  })
});

if (verifyResponse.ok) {
  console.log('Email verified!');
}
```

### PHP/Laravel

```php
use App\Models\VerificationCode;
use App\Models\User;

// Create code
$user = User::find(1);
$code = VerificationCode::createForUser($user, 'email', 15);

// Verify code
$verification = VerificationCode::verify($user->id, 'ABC123', 'email');
if ($verification) {
    $user->markEmailAsVerified();
    $verification->markAsUsed();
}
```

---

## ✅ Response Codes

| Code | Meaning | Example |
|------|---------|---------|
| 200 | Success | Code sent, verified, or resent |
| 400 | Bad Request | Invalid code, already verified, user not found |
| 404 | Not Found | User doesn't exist |
| 429 | Too Many Attempts | Max attempts exceeded |
| 500 | Server Error | Internal error |

---

## 🔒 Security Features

✅ Codes expire after 15 minutes
✅ Maximum 5 failed attempts
✅ Previous codes invalidated on new request
✅ Case-insensitive code matching
✅ Attempt tracking
✅ No plain text logging
✅ Database indexed for performance

---

## 🧪 Testing Checklist

- [ ] Run migration: `php artisan migrate`
- [ ] Send code to valid email
- [ ] Verify with correct code
- [ ] Verify with incorrect code (should fail)
- [ ] Exceed max attempts (should return 429)
- [ ] Request new code (should invalidate old one)
- [ ] Verify already verified email (should fail)
- [ ] Test with non-existent email (should fail)

---

## 📊 Database Schema

```sql
CREATE TABLE verification_codes (
    id BIGINT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    code VARCHAR(10) NOT NULL,
    type ENUM('email', 'phone', 'password_reset'),
    expires_at TIMESTAMP,
    used_at TIMESTAMP NULL,
    attempts INT DEFAULT 0,
    max_attempts INT DEFAULT 5,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX (user_id, type),
    INDEX (code, type),
    INDEX (expires_at)
);
```

---

## 🎯 Key Methods

### VerificationCode Model

```php
// Create code
VerificationCode::createForUser($user, 'email', 15);

// Verify code
VerificationCode::verify($userId, $code, 'email');

// Generate code
VerificationCode::generateCode(6);

// Mark as used
$code->markAsUsed();

// Increment attempts
$code->incrementAttempts();

// Check validity
$code->isValid();
$code->isExpired();
$code->hasExceededAttempts();
```

---

## 🚨 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Table doesn't exist | Run `php artisan migrate` |
| Codes not sending | Check `.env` mail config |
| "Too many attempts" | User needs to request new code |
| Code not working | Check expiration time and code format |
| Email not verified | Ensure `markEmailAsVerified()` is called |

---

## 📚 Documentation Files

1. **VERIFICATION_CODE_IMPLEMENTATION.md** - Complete API documentation
2. **VERIFICATION_CODE_SETUP_GUIDE.md** - Installation and setup guide
3. **VERIFICATION_CODE_QUICK_REFERENCE.md** - This file

---

## 🔗 Related Files

- `app/Models/VerificationCode.php` - Model logic
- `app/Notifications/VerificationCodeNotification.php` - Email template
- `app/Http/Controllers/AuthController.php` - Controller methods
- `routes/api.php` - API routes
- `database/migrations/2025_10_26_000000_create_verification_codes_table.php` - Database schema

---

## 💬 Support

For detailed information, see:
- Full API docs: `VERIFICATION_CODE_IMPLEMENTATION.md`
- Setup guide: `VERIFICATION_CODE_SETUP_GUIDE.md`
- Model code: `app/Models/VerificationCode.php`
- Controller code: `app/Http/Controllers/AuthController.php`

