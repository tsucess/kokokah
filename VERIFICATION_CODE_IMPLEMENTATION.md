# Email Verification Code Implementation

## Overview

The Kokokah LMS now supports email verification using both traditional link-based verification and modern verification codes. Users can verify their email by entering a 6-character code sent to their email address.

## Features

✅ **Verification Code Generation** - Automatic 6-character alphanumeric codes
✅ **Email Notifications** - Codes sent via email with expiration time
✅ **Code Expiration** - Codes expire after 15 minutes by default
✅ **Attempt Limiting** - Maximum 5 failed attempts before requiring a new code
✅ **Code Reuse Prevention** - Previous codes are invalidated when new ones are generated
✅ **Dual Verification Methods** - Support for both link-based and code-based verification
✅ **Public & Authenticated Routes** - Works for both registered and unregistered users

## Database Schema

### verification_codes Table

```sql
CREATE TABLE verification_codes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    code VARCHAR(10) NOT NULL,
    type ENUM('email', 'phone', 'password_reset') DEFAULT 'email',
    expires_at TIMESTAMP NOT NULL,
    used_at TIMESTAMP NULL,
    attempts INT DEFAULT 0,
    max_attempts INT DEFAULT 5,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (user_id, type),
    INDEX (code, type),
    INDEX (expires_at)
);
```

## API Endpoints

### 1. Send Verification Code (Public)

**Endpoint:** `POST /api/email/send-verification-code`

**Description:** Send a verification code to a user's email address

**Request:**
```json
{
    "email": "user@example.com"
}
```

**Response (Success - 200):**
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

**Response (Error - 400):**
```json
{
    "success": false,
    "message": "Email already verified"
}
```

---

### 2. Verify Email with Code (Public)

**Endpoint:** `POST /api/email/verify-with-code`

**Description:** Verify email using the code sent to the user's email

**Request:**
```json
{
    "email": "user@example.com",
    "code": "ABC123"
}
```

**Response (Success - 200):**
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

**Response (Error - 400):**
```json
{
    "success": false,
    "message": "Invalid or expired verification code"
}
```

**Response (Error - 429):**
```json
{
    "success": false,
    "message": "Too many failed attempts. Please request a new code."
}
```

---

### 3. Resend Verification Code (Public)

**Endpoint:** `POST /api/email/resend-verification-code`

**Description:** Request a new verification code (invalidates previous codes)

**Request:**
```json
{
    "email": "user@example.com"
}
```

**Response (Success - 200):**
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

### 4. Send Verification Code (Authenticated)

**Endpoint:** `POST /api/email/send-code`

**Description:** Send verification code to authenticated user's email

**Headers:**
```
Authorization: Bearer {token}
```

**Response (Success - 200):**
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

---

### 5. Verify Email with Code (Authenticated)

**Endpoint:** `POST /api/email/verify-code`

**Description:** Verify email using code (authenticated user)

**Headers:**
```
Authorization: Bearer {token}
```

**Request:**
```json
{
    "email": "user@example.com",
    "code": "ABC123"
}
```

**Response (Success - 200):**
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

---

### 6. Resend Verification Code (Authenticated)

**Endpoint:** `POST /api/email/resend-code`

**Description:** Request new verification code (authenticated user)

**Headers:**
```
Authorization: Bearer {token}
```

**Response (Success - 200):**
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

## Models

### VerificationCode Model

**Location:** `app/Models/VerificationCode.php`

**Key Methods:**

- `createForUser($user, $type, $expiresInMinutes)` - Create a new verification code
- `verify($userId, $code, $type)` - Verify a code for a user
- `generateCode($length)` - Generate a random code
- `markAsUsed()` - Mark code as used
- `incrementAttempts()` - Increment failed attempts
- `isValid()` - Check if code is valid
- `isExpired()` - Check if code is expired
- `hasExceededAttempts()` - Check if max attempts exceeded

**Scopes:**

- `active()` - Get active (unused, not expired, attempts < max) codes
- `byType($type)` - Filter by type (email, phone, password_reset)
- `forUser($userId)` - Filter by user

---

## Notifications

### VerificationCodeNotification

**Location:** `app/Notifications/VerificationCodeNotification.php`

Sends an email with:
- Verification code
- Expiration time
- Link to verification page
- Instructions to enter code manually

---

## Usage Examples

### Frontend Integration

```javascript
// Step 1: Request verification code
const response = await fetch('/api/email/send-verification-code', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email: 'user@example.com' })
});

// Step 2: User enters code from email
// Step 3: Verify the code
const verifyResponse = await fetch('/api/email/verify-with-code', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        email: 'user@example.com',
        code: 'ABC123'
    })
});

// Step 4: Handle response
if (verifyResponse.ok) {
    console.log('Email verified successfully!');
} else {
    console.log('Verification failed');
}
```

---

## Configuration

### Expiration Time

Default: 15 minutes

To change, modify in `AuthController::sendVerificationCode()`:
```php
$verificationCode = VerificationCode::createForUser($user, 'email', 30); // 30 minutes
```

### Code Length

Default: 6 characters

To change, modify in `VerificationCode::generateCode()`:
```php
public static function generateCode($length = 8) // 8 characters
```

### Max Attempts

Default: 5 attempts

To change, modify in `VerificationCode::createForUser()`:
```php
'max_attempts' => 10 // 10 attempts
```

---

## Security Considerations

✅ Codes are case-insensitive (converted to uppercase)
✅ Codes expire after 15 minutes
✅ Failed attempts are tracked
✅ Previous codes are invalidated when new ones are generated
✅ Rate limiting recommended on frontend
✅ HTTPS required for production
✅ Codes are not logged in plain text

---

## Migration

Run the migration to create the verification_codes table:

```bash
php artisan migrate
```

---

## Testing

```bash
# Test sending verification code
curl -X POST http://localhost:8000/api/email/send-verification-code \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com"}'

# Test verifying with code
curl -X POST http://localhost:8000/api/email/verify-with-code \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","code":"ABC123"}'
```

---

## Future Enhancements

- SMS verification codes
- Phone number verification
- Password reset with codes
- Configurable code length and expiration
- Rate limiting per IP/email
- Verification code history/audit log
- Multi-factor authentication integration

