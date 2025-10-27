# Email Verification Code - Flow Diagrams

## 📊 Verification Code Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    USER VERIFICATION FLOW                        │
└─────────────────────────────────────────────────────────────────┘

1. USER REQUESTS CODE
   ┌──────────────────────────────────────────────────────────┐
   │ POST /api/email/send-verification-code                   │
   │ {                                                         │
   │   "email": "user@example.com"                            │
   │ }                                                         │
   └──────────────────────────────────────────────────────────┘
                            ↓
2. SYSTEM VALIDATES EMAIL
   ┌──────────────────────────────────────────────────────────┐
   │ ✓ Email exists in database                              │
   │ ✓ Email not already verified                            │
   │ ✓ No rate limiting issues                               │
   └──────────────────────────────────────────────────────────┘
                            ↓
3. GENERATE VERIFICATION CODE
   ┌──────────────────────────────────────────────────────────┐
   │ Code: ABC123 (6 characters)                             │
   │ Type: email                                              │
   │ Expires: 15 minutes from now                            │
   │ Max Attempts: 5                                          │
   └──────────────────────────────────────────────────────────┘
                            ↓
4. STORE IN DATABASE
   ┌──────────────────────────────────────────────────────────┐
   │ INSERT INTO verification_codes (                         │
   │   user_id, code, type, expires_at,                      │
   │   attempts, max_attempts                                │
   │ )                                                        │
   └──────────────────────────────────────────────────────────┘
                            ↓
5. SEND EMAIL NOTIFICATION
   ┌──────────────────────────────────────────────────────────┐
   │ To: user@example.com                                    │
   │ Subject: Email Verification Code - Kokokah LMS         │
   │ Body: Your code is ABC123 (expires in 15 minutes)      │
   └──────────────────────────────────────────────────────────┘
                            ↓
6. RETURN SUCCESS RESPONSE
   ┌──────────────────────────────────────────────────────────┐
   │ {                                                         │
   │   "success": true,                                       │
   │   "message": "Verification code sent to your email",    │
   │   "data": {                                              │
   │     "expires_in_minutes": 15,                           │
   │     "code_length": 6                                    │
   │   }                                                      │
   │ }                                                         │
   └──────────────────────────────────────────────────────────┘
```

---

## 🔐 Code Verification Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    CODE VERIFICATION FLOW                        │
└─────────────────────────────────────────────────────────────────┘

1. USER SUBMITS CODE
   ┌──────────────────────────────────────────────────────────┐
   │ POST /api/email/verify-with-code                         │
   │ {                                                         │
   │   "email": "user@example.com",                          │
   │   "code": "ABC123"                                      │
   │ }                                                         │
   └──────────────────────────────────────────────────────────┘
                            ↓
2. VALIDATE INPUT
   ┌──────────────────────────────────────────────────────────┐
   │ ✓ Email is valid format                                 │
   │ ✓ Code is 6 characters                                  │
   │ ✓ User exists                                           │
   │ ✓ Email not already verified                            │
   └──────────────────────────────────────────────────────────┘
                            ↓
3. QUERY VERIFICATION CODE
   ┌──────────────────────────────────────────────────────────┐
   │ SELECT * FROM verification_codes WHERE                  │
   │   user_id = ? AND                                       │
   │   code = UPPER(?) AND                                   │
   │   type = 'email' AND                                    │
   │   used_at IS NULL AND                                   │
   │   expires_at > NOW() AND                                │
   │   attempts < max_attempts                               │
   └──────────────────────────────────────────────────────────┘
                            ↓
                    ┌───────┴───────┐
                    ↓               ↓
            CODE FOUND         CODE NOT FOUND
                    │               │
                    ↓               ↓
            4a. MARK USED    4b. INCREMENT ATTEMPTS
            ┌──────────────┐  ┌──────────────────┐
            │ used_at =    │  │ attempts++       │
            │ NOW()        │  │                  │
            └──────────────┘  │ Check if max     │
                    │         │ attempts reached │
                    │         └──────────────────┘
                    │                 │
                    ↓                 ↓
            5a. MARK EMAIL      5b. RETURN ERROR
                VERIFIED        ┌──────────────────┐
            ┌──────────────┐    │ 400: Invalid or  │
            │ email_       │    │ expired code     │
            │ verified_at  │    │                  │
            │ = NOW()      │    │ OR               │
            └──────────────┘    │ 429: Too many    │
                    │           │ attempts         │
                    ↓           └──────────────────┘
            6a. RETURN SUCCESS
            ┌──────────────────────────────────────┐
            │ {                                    │
            │   "success": true,                   │
            │   "message": "Email verified",      │
            │   "data": {                          │
            │     "user": {...},                   │
            │     "verified_at": "2025-10-26..."  │
            │   }                                  │
            │ }                                    │
            └──────────────────────────────────────┘
```

---

## 🔄 Code Lifecycle

```
┌─────────────────────────────────────────────────────────────────┐
│                    CODE LIFECYCLE                                │
└─────────────────────────────────────────────────────────────────┘

CREATED
  │
  ├─ Code: ABC123
  ├─ Expires: 15 minutes
  ├─ Attempts: 0/5
  ├─ Used: No
  │
  ↓
ACTIVE (Can be used)
  │
  ├─ User enters wrong code → Attempts: 1/5
  ├─ User enters wrong code → Attempts: 2/5
  ├─ User enters wrong code → Attempts: 3/5
  ├─ User enters wrong code → Attempts: 4/5
  ├─ User enters wrong code → Attempts: 5/5 → EXPIRED (Too many attempts)
  │
  ├─ OR
  │
  ├─ User enters correct code → VERIFIED
  │   ├─ Used: Yes
  │   ├─ Used At: NOW()
  │   ├─ Email Verified: Yes
  │   └─ Code cannot be reused
  │
  ├─ OR
  │
  ├─ 15 minutes pass → EXPIRED (Time limit)
  │   └─ Code cannot be used
  │
  ↓
INACTIVE (Cannot be used)
  │
  └─ Reason: Used, Expired, or Max Attempts Exceeded
```

---

## 📱 Frontend Integration Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                  FRONTEND INTEGRATION FLOW                       │
└─────────────────────────────────────────────────────────────────┘

USER INTERFACE
┌─────────────────────────────────────────────────────────────────┐
│                                                                   │
│  STEP 1: ENTER EMAIL                                            │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ Email: [user@example.com]                              │   │
│  │ [Send Code Button]                                     │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
│  ↓ (User clicks "Send Code")                                    │
│                                                                   │
│  STEP 2: WAIT FOR EMAIL                                         │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ ⏳ Sending verification code...                         │   │
│  │ Check your email for the code                          │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
│  ↓ (User receives email)                                        │
│                                                                   │
│  STEP 3: ENTER CODE                                             │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ Verification Code: [ABC123]                            │   │
│  │ Code expires in: 14:32                                 │   │
│  │ [Verify Button]                                        │   │
│  │ [Resend Code]                                          │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
│  ↓ (User enters code and clicks "Verify")                       │
│                                                                   │
│  STEP 4: VERIFICATION RESULT                                    │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ ✓ Email verified successfully!                         │   │
│  │ Redirecting to dashboard...                            │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔀 Decision Tree

```
┌─────────────────────────────────────────────────────────────────┐
│                    VERIFICATION DECISION TREE                    │
└─────────────────────────────────────────────────────────────────┘

START: User submits code
  │
  ├─ Email exists?
  │  ├─ NO → 404: User not found
  │  └─ YES ↓
  │
  ├─ Email already verified?
  │  ├─ YES → 400: Email already verified
  │  └─ NO ↓
  │
  ├─ Code format valid (6 chars)?
  │  ├─ NO → 400: Invalid code format
  │  └─ YES ↓
  │
  ├─ Code exists in database?
  │  ├─ NO → 400: Invalid or expired code
  │  └─ YES ↓
  │
  ├─ Code expired?
  │  ├─ YES → 400: Invalid or expired code
  │  └─ NO ↓
  │
  ├─ Max attempts exceeded?
  │  ├─ YES → 429: Too many failed attempts
  │  └─ NO ↓
  │
  ├─ Code already used?
  │  ├─ YES → 400: Invalid or expired code
  │  └─ NO ↓
  │
  └─ ✓ ALL CHECKS PASSED
     │
     ├─ Mark code as used
     ├─ Mark email as verified
     ├─ Update user.email_verified_at
     └─ 200: Email verified successfully
```

---

## 📊 Database Relationships

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATABASE RELATIONSHIPS                        │
└─────────────────────────────────────────────────────────────────┘

users
├─ id (PK)
├─ email
├─ email_verified_at
└─ ...
  │
  │ (1:N relationship)
  │
  ↓
verification_codes
├─ id (PK)
├─ user_id (FK) ──→ users.id
├─ code
├─ type (email, phone, password_reset)
├─ expires_at
├─ used_at
├─ attempts
├─ max_attempts
├─ created_at
└─ updated_at

Indexes:
├─ PRIMARY KEY (id)
├─ FOREIGN KEY (user_id)
├─ INDEX (user_id, type)
├─ INDEX (code, type)
└─ INDEX (expires_at)
```

---

## 🔐 Security Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    SECURITY FLOW                                 │
└─────────────────────────────────────────────────────────────────┘

INPUT VALIDATION
  ├─ Email format validation
  ├─ Code length validation
  └─ User existence check

CODE GENERATION
  ├─ Random 6-character code
  ├─ Alphanumeric characters only
  └─ Case-insensitive matching

ATTEMPT TRACKING
  ├─ Count failed attempts
  ├─ Max 5 attempts allowed
  └─ Return 429 on exceeded

EXPIRATION HANDLING
  ├─ 15-minute expiration
  ├─ Automatic cleanup possible
  └─ Check on every verification

CODE INVALIDATION
  ├─ Previous codes invalidated
  ├─ One code per user per type
  └─ Cannot reuse used codes

RATE LIMITING (Recommended)
  ├─ Limit requests per IP
  ├─ Limit requests per email
  └─ Implement on frontend/backend
```

