# Email Verification Testing Guide

## ✅ Configuration Status

Your email configuration is **properly set up** with:
- **SMTP Server**: smtp.gmail.com
- **Port**: 587 (TLS)
- **Encryption**: TLS (updated)
- **From Address**: taofeeq.muhammad22@gmail.com
- **Queue**: Database (asynchronous email sending)

## 🧪 Testing the Email Verification Flow

### Option 1: Test via API (Recommended)

#### Step 1: Register a New User
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "student"
  }'
```

**Expected Response:**
```json
{
  "status": "success",
  "message": "User registered successfully",
  "user": { ... },
  "token": "..."
}
```

#### Step 2: Send Verification Code
```bash
curl -X POST http://localhost:8000/api/email/send-verification-code \
  -H "Content-Type: application/json" \
  -d '{"email": "john@example.com"}'
```

**Expected Response:**
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

#### Step 3: Verify Email with Code
```bash
curl -X POST http://localhost:8000/api/email/verify-with-code \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "code": "ABC123"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Email verified successfully",
  "data": {
    "user": { ... },
    "verified_at": "2025-01-05T10:30:00Z"
  }
}
```

### Option 2: Test via Frontend

1. Navigate to `/register`
2. Fill in registration form
3. Click "Register"
4. You'll be redirected to `/verify`
5. Enter the code from your email
6. Click "Verify"

### Option 3: Test via Artisan Tinker

```bash
php artisan tinker

# Create a test user
$user = App\Models\User::create([
  'first_name' => 'Test',
  'last_name' => 'User',
  'email' => 'test@example.com',
  'password' => bcrypt('password123'),
  'role' => 'student'
]);

# Create verification code
$code = App\Models\VerificationCode::createForUser($user, 'email', 15);
echo $code->code; // Shows the code

# Verify the code
$verified = App\Models\VerificationCode::verify($user->id, $code->code, 'email');
$user->markEmailAsVerified();
```

## 📧 Email Queue Processing

Emails are queued asynchronously. To process them:

```bash
# Process queued jobs
php artisan queue:work

# Or process a single job
php artisan queue:work --once
```

## 🔍 Troubleshooting

### Emails Not Sending?

1. **Check queue jobs**:
   ```bash
   php artisan queue:failed
   ```

2. **Check mail logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Verify Gmail credentials**:
   - Ensure you're using an App Password (not regular password)
   - Enable "Less secure app access" if needed

4. **Test mail configuration**:
   ```bash
   php artisan tinker
   Mail::raw('Test email', function($message) {
     $message->to('your@email.com');
   });
   ```

## ✨ Features Implemented

- ✅ 6-character alphanumeric codes
- ✅ 15-minute expiration
- ✅ 5 attempt limit
- ✅ Code reuse prevention
- ✅ Automatic code invalidation on new request
- ✅ Email queue support
- ✅ Professional email template
- ✅ Resend functionality
- ✅ Role-based redirect after verification

