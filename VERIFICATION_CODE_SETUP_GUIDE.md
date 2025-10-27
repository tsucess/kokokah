# Email Verification Code - Setup & Installation Guide

## What Was Implemented

A complete email verification code system has been added to the Kokokah LMS that allows users to verify their email addresses using 6-character codes instead of (or in addition to) clicking verification links.

## Files Created

### 1. Model
- **`app/Models/VerificationCode.php`** - Handles verification code logic and database operations

### 2. Notification
- **`app/Notifications/VerificationCodeNotification.php`** - Email notification for sending verification codes

### 3. Migration
- **`database/migrations/2025_10_26_000000_create_verification_codes_table.php`** - Creates the verification_codes table

### 4. Documentation
- **`VERIFICATION_CODE_IMPLEMENTATION.md`** - Complete API documentation
- **`VERIFICATION_CODE_SETUP_GUIDE.md`** - This file

## Files Modified

### 1. Controller
- **`app/Http/Controllers/AuthController.php`** - Added 3 new methods:
  - `sendVerificationCode()` - Send code to email
  - `verifyEmailWithCode()` - Verify email with code
  - `resendVerificationCode()` - Resend code

### 2. Routes
- **`routes/api.php`** - Added 6 new routes:
  - `POST /api/email/send-verification-code` (public)
  - `POST /api/email/verify-with-code` (public)
  - `POST /api/email/resend-verification-code` (public)
  - `POST /api/email/send-code` (authenticated)
  - `POST /api/email/verify-code` (authenticated)
  - `POST /api/email/resend-code` (authenticated)

## Installation Steps

### Step 1: Run Migration

```bash
php artisan migrate
```

This creates the `verification_codes` table with the following structure:
- `id` - Primary key
- `user_id` - Foreign key to users table
- `code` - 6-character verification code
- `type` - Type of verification (email, phone, password_reset)
- `expires_at` - When the code expires
- `used_at` - When the code was used
- `attempts` - Number of failed attempts
- `max_attempts` - Maximum allowed attempts
- `created_at`, `updated_at` - Timestamps

### Step 2: Configure Email Settings (Optional)

Ensure your `.env` file has proper mail configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@kokokah.com
MAIL_FROM_NAME="Kokokah LMS"
```

### Step 3: Test the Implementation

#### Test 1: Send Verification Code

```bash
curl -X POST http://localhost:8000/api/email/send-verification-code \
  -H "Content-Type: application/json" \
  -d '{
    "email": "student@example.com"
  }'
```

Expected Response:
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

#### Test 2: Verify Email with Code

```bash
curl -X POST http://localhost:8000/api/email/verify-with-code \
  -H "Content-Type: application/json" \
  -d '{
    "email": "student@example.com",
    "code": "ABC123"
  }'
```

Expected Response:
```json
{
    "success": true,
    "message": "Email verified successfully",
    "data": {
        "user": { /* user object */ },
        "verified_at": "2025-10-26T10:30:00Z"
    }
}
```

#### Test 3: Resend Code

```bash
curl -X POST http://localhost:8000/api/email/resend-verification-code \
  -H "Content-Type: application/json" \
  -d '{
    "email": "student@example.com"
  }'
```

## Key Features

✅ **6-Character Alphanumeric Codes** - Easy to type and remember
✅ **15-Minute Expiration** - Codes expire automatically
✅ **5 Attempt Limit** - Prevents brute force attacks
✅ **Email Notifications** - Users receive codes via email
✅ **Code Invalidation** - Previous codes are invalidated when new ones are generated
✅ **Dual Methods** - Works alongside traditional link-based verification
✅ **Public & Authenticated Routes** - Flexible for different use cases
✅ **Attempt Tracking** - Failed attempts are counted
✅ **Rate Limiting Ready** - Can be integrated with Laravel rate limiting

## Security Features

🔒 **Case-Insensitive Codes** - Codes are converted to uppercase for consistency
🔒 **Automatic Expiration** - Codes expire after 15 minutes
🔒 **Attempt Limiting** - Maximum 5 failed attempts
🔒 **Code Invalidation** - Previous codes are invalidated
🔒 **No Plain Text Logging** - Codes are not logged in plain text
🔒 **HTTPS Required** - Use HTTPS in production
🔒 **Database Indexed** - Optimized queries for performance

## Configuration Options

### Change Code Expiration Time

In `app/Http/Controllers/AuthController.php`, modify the `sendVerificationCode()` method:

```php
// Change from 15 to 30 minutes
$verificationCode = VerificationCode::createForUser($user, 'email', 30);
```

### Change Code Length

In `app/Models/VerificationCode.php`, modify the `generateCode()` method:

```php
// Change from 6 to 8 characters
public static function generateCode($length = 8)
```

### Change Max Attempts

In `app/Models/VerificationCode.php`, modify the `createForUser()` method:

```php
// Change from 5 to 10 attempts
'max_attempts' => 10
```

## Frontend Integration Example

### React Component

```jsx
import { useState } from 'react';

export function EmailVerification() {
  const [email, setEmail] = useState('');
  const [code, setCode] = useState('');
  const [step, setStep] = useState('email'); // 'email' or 'code'
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const sendCode = async () => {
    setLoading(true);
    try {
      const res = await fetch('/api/email/send-verification-code', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
      });
      const data = await res.json();
      if (data.success) {
        setStep('code');
      } else {
        setError(data.message);
      }
    } catch (err) {
      setError('Failed to send code');
    }
    setLoading(false);
  };

  const verifyCode = async () => {
    setLoading(true);
    try {
      const res = await fetch('/api/email/verify-with-code', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, code })
      });
      const data = await res.json();
      if (data.success) {
        alert('Email verified successfully!');
        // Redirect or update state
      } else {
        setError(data.message);
      }
    } catch (err) {
      setError('Verification failed');
    }
    setLoading(false);
  };

  return (
    <div>
      {step === 'email' ? (
        <>
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            placeholder="Enter your email"
          />
          <button onClick={sendCode} disabled={loading}>
            {loading ? 'Sending...' : 'Send Code'}
          </button>
        </>
      ) : (
        <>
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
            Resend Code
          </button>
        </>
      )}
      {error && <p style={{ color: 'red' }}>{error}</p>}
    </div>
  );
}
```

## Troubleshooting

### Issue: "SQLSTATE[HY000]: General error: 1 table verification_codes has no column..."

**Solution:** Run the migration:
```bash
php artisan migrate
```

### Issue: Codes not being sent

**Solution:** Check mail configuration in `.env` and test with:
```bash
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('test@example.com'); });
```

### Issue: "Too many failed attempts"

**Solution:** User needs to request a new code using the resend endpoint

## Next Steps

1. ✅ Run the migration: `php artisan migrate`
2. ✅ Configure email settings in `.env`
3. ✅ Test the endpoints using the examples above
4. ✅ Integrate with your frontend
5. ✅ Update your verification page to accept codes
6. ✅ Consider adding rate limiting for production

## Support

For issues or questions, refer to:
- `VERIFICATION_CODE_IMPLEMENTATION.md` - Complete API documentation
- `app/Models/VerificationCode.php` - Model implementation
- `app/Http/Controllers/AuthController.php` - Controller implementation

