# Start Queue Worker - Quick Guide

## ⚡ Quick Start (2 Steps)

### Step 1: Open New Terminal
Open a new command prompt/terminal window

### Step 2: Run Queue Worker
```bash
cd c:\Users\Rise Networks\Desktop\Kokokah.com\kokokah.com
php artisan queue:work
```

**That's it!** ✅

## 📋 What This Does

The queue worker:
- Processes queued emails from the database
- Sends verification codes via Gmail SMTP
- Runs continuously until you stop it
- Logs all activity to console

## 🎯 Expected Output

```
Processing jobs from the [default] queue.

[2025-01-05 10:30:00] Processing: App\Notifications\VerificationCodeNotification
[2025-01-05 10:30:02] Processed:  App\Notifications\VerificationCodeNotification
```

## ⚠️ Important Notes

1. **Keep Terminal Open**
   - Don't close the terminal window
   - Queue worker must run continuously
   - If closed, emails won't be sent

2. **Multiple Terminals**
   - Terminal 1: `php artisan serve` (Laravel server)
   - Terminal 2: `php artisan queue:work` (Queue worker)
   - Terminal 3: `npm run dev` (Frontend dev server)

3. **Stop Queue Worker**
   - Press `Ctrl + C` to stop
   - Emails will stop being sent

## 🧪 Test It

### After Starting Queue Worker:

1. **Register a user**
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

2. **Watch Queue Worker Terminal**
   - Should show: "Processing: App\Notifications\VerificationCodeNotification"
   - Should show: "Processed: App\Notifications\VerificationCodeNotification"

3. **Check Email**
   - Check john@example.com inbox
   - Should receive verification code email

## 🔧 Troubleshooting

### No output in queue worker?
- Check if jobs are in database: `php artisan queue:failed`
- Check logs: `tail -f storage/logs/laravel.log`

### "SMTP connection failed"?
- Verify Gmail credentials in .env
- Check Gmail security settings
- Try using Gmail App Password

### Jobs keep failing?
- Check failed jobs: `php artisan queue:failed`
- View error: `php artisan queue:failed --id=1`
- Retry: `php artisan queue:retry all`

## 📊 Queue Commands

```bash
# Start queue worker
php artisan queue:work

# Start with specific queue
php artisan queue:work --queue=default

# Start with retry limit
php artisan queue:work --tries=3

# View failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Clear all jobs
php artisan queue:flush

# Monitor queue
php artisan queue:monitor
```

## ✅ Checklist

- [ ] Opened new terminal
- [ ] Navigated to project directory
- [ ] Ran `php artisan queue:work`
- [ ] Terminal shows "Processing jobs from the [default] queue"
- [ ] Registered a test user
- [ ] Queue worker shows "Processing" and "Processed"
- [ ] Received email with verification code

## 🎉 Done!

Your emails should now be sending! Keep the queue worker terminal open.

## 📚 More Info

See `EMAIL_NOT_SENDING_DEBUG_GUIDE.md` for detailed troubleshooting.

