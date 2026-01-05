# Emails Not Sending - Complete Debug Guide

## 🔍 Root Cause Analysis

Your emails are not being sent because:

1. **Queue Worker Not Running**
   - `QUEUE_CONNECTION=database` means emails are queued in database
   - A queue worker process must be running to process these emails
   - Without the worker, emails stay in the `jobs` table forever

2. **Email Configuration**
   - Gmail SMTP is configured correctly
   - TLS encryption is enabled
   - Credentials are set

3. **Notification Setup**
   - `VerificationCodeNotification` implements `ShouldQueue`
   - Emails are queued asynchronously

## ✅ Solution: Start Queue Worker

### Step 1: Open a New Terminal
```bash
# Open a new terminal/command prompt
# Navigate to project directory
cd c:\Users\Rise Networks\Desktop\Kokokah.com\kokokah.com
```

### Step 2: Start Queue Worker
```bash
php artisan queue:work
```

**Expected Output:**
```
Processing jobs from the [default] queue.

[2025-01-05 10:30:00] Processing: App\Notifications\VerificationCodeNotification
[2025-01-05 10:30:02] Processed:  App\Notifications\VerificationCodeNotification
```

### Step 3: Keep Terminal Open
- **IMPORTANT**: Keep this terminal window open
- The queue worker must run continuously
- If you close it, emails will stop being sent

## 🧪 Testing Email Sending

### Test 1: Register a New User
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Test 2: Watch Queue Worker
- Look at the queue worker terminal
- You should see:
  ```
  Processing: App\Notifications\VerificationCodeNotification
  Processed:  App\Notifications\VerificationCodeNotification
  ```

### Test 3: Check Email
- Check your email inbox (john@example.com)
- You should receive verification code email
- Code should be 6 characters

## 🔍 Debugging Steps

### Check 1: Verify Queue Configuration
```bash
# Check .env file
grep QUEUE_CONNECTION .env
# Should show: QUEUE_CONNECTION=database
```

### Check 2: Check Jobs Table
```bash
php artisan tinker
> DB::table('jobs')->count()
# Shows number of queued jobs
```

### Check 3: Check Failed Jobs
```bash
php artisan queue:failed
# Shows any failed email jobs
```

### Check 4: Check Email Logs
```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# Look for:
# - "Processing: App\Notifications\VerificationCodeNotification"
# - "Processed: App\Notifications\VerificationCodeNotification"
# - Any SMTP errors
```

### Check 5: Test Mail Configuration
```bash
php artisan tinker
> Mail::raw('Test email', function($message) {
    $message->to('your@email.com');
  });
# Should queue the email
```

## 🚨 Common Issues & Solutions

### Issue: "No jobs found" in queue worker
**Cause**: Queue worker is running but no jobs to process  
**Solution**: Register a new user to create a job

### Issue: "SMTP connection failed"
**Cause**: Gmail credentials are wrong or Gmail security settings  
**Solution**:
1. Verify Gmail credentials in .env
2. Check if Gmail account has "Less secure app access" enabled
3. Use Gmail App Password instead of regular password

### Issue: "Connection timeout"
**Cause**: Network issue or Gmail server down  
**Solution**:
1. Check internet connection
2. Try again later
3. Check Gmail status page

### Issue: Jobs keep failing
**Cause**: Email configuration issue  
**Solution**:
1. Check failed jobs: `php artisan queue:failed`
2. Check logs: `tail -f storage/logs/laravel.log`
3. Verify Gmail credentials
4. Test with `php artisan tinker`

## 📋 Configuration Checklist

- [x] MAIL_MAILER=smtp
- [x] MAIL_SCHEME=tls
- [x] MAIL_HOST=smtp.gmail.com
- [x] MAIL_PORT=587
- [x] MAIL_USERNAME=taofeeq.muhammad22@gmail.com
- [x] MAIL_PASSWORD=hxycxhyyvhaqtjxx
- [x] MAIL_FROM_ADDRESS=taofeeq.muhammad22@gmail.com
- [x] QUEUE_CONNECTION=database
- [ ] Queue worker running

## 🚀 Production Setup

For production, use supervisor to keep queue worker running:

```ini
[program:kokokah-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --queue=default --tries=3
autostart=true
autorestart=true
numprocs=4
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/queue.log
```

## ✨ Features

- ✅ Gmail SMTP configured
- ✅ TLS encryption enabled
- ✅ Database queue configured
- ✅ Notification system ready
- ⚠️ Queue worker needs to be running

## 🎯 Next Steps

1. **Start Queue Worker**
   ```bash
   php artisan queue:work
   ```

2. **Test Email Sending**
   - Register a new user
   - Check email inbox
   - Verify code received

3. **Monitor Queue**
   - Watch queue worker terminal
   - Check logs for errors
   - Monitor failed jobs

## ✅ Status

**Email Configuration**: ✅ CORRECT  
**Queue Configuration**: ✅ CORRECT  
**Queue Worker**: ⚠️ NOT RUNNING (NEEDS TO BE STARTED)

**Action Required**: Start queue worker with `php artisan queue:work`

