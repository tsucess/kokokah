# Email Verification Code - Deployment Checklist

## 🚀 Pre-Deployment Checklist

### Development Environment
- [ ] All files created successfully
- [ ] No syntax errors in PHP files
- [ ] No missing imports or dependencies
- [ ] IDE shows no red underlines (Intelephense)

### Database
- [ ] Migration file created: `2025_10_26_000000_create_verification_codes_table.php`
- [ ] Migration syntax is correct
- [ ] Foreign key references are correct
- [ ] Indexes are properly defined

### Code Quality
- [ ] VerificationCode model has all required methods
- [ ] AuthController methods are properly implemented
- [ ] Error handling is comprehensive
- [ ] Security best practices are followed

### API Routes
- [ ] 3 public routes added to `routes/api.php`
- [ ] 3 authenticated routes added to `routes/api.php`
- [ ] Routes are properly grouped
- [ ] Middleware is correctly applied

### Documentation
- [ ] VERIFICATION_CODE_IMPLEMENTATION.md created
- [ ] VERIFICATION_CODE_SETUP_GUIDE.md created
- [ ] VERIFICATION_CODE_QUICK_REFERENCE.md created
- [ ] VERIFICATION_CODE_FLOW_DIAGRAM.md created
- [ ] VERIFICATION_CODE_SUMMARY.md created
- [ ] IMPLEMENTATION_COMPLETE.md created

---

## 🔧 Deployment Steps

### Step 1: Backup Database
```bash
# Create a backup of your current database
mysqldump -u root -p kokokah_lms > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Step 2: Run Migration
```bash
# Run the migration to create the verification_codes table
php artisan migrate
```

**Expected Output:**
```
Migrating: 2025_10_26_000000_create_verification_codes_table
Migrated:  2025_10_26_000000_create_verification_codes_table (XXms)
```

### Step 3: Verify Migration
```bash
# Check migration status
php artisan migrate:status

# Or verify table exists
php artisan tinker
>>> Schema::hasTable('verification_codes')
=> true
```

### Step 4: Configure Email (if needed)
Update `.env` file:
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

### Step 5: Test Email Configuration
```bash
php artisan tinker
>>> Mail::raw('Test email', function($m) { $m->to('test@example.com'); });
```

### Step 6: Clear Cache
```bash
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

---

## ✅ Post-Deployment Testing

### Test 1: Send Verification Code
```bash
curl -X POST http://localhost:8000/api/email/send-verification-code \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com"}'
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

### Test 2: Verify with Code
```bash
curl -X POST http://localhost:8000/api/email/verify-with-code \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","code":"ABC123"}'
```

**Expected Response (Success):**
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

### Test 3: Resend Code
```bash
curl -X POST http://localhost:8000/api/email/resend-verification-code \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com"}'
```

**Expected Response:**
```json
{
    "success": true,
    "message": "New verification code sent to your email",
    "data": {
        "expires_in_minutes": 15
    }
}
```

### Test 4: Error Cases

**Test 4a: Invalid Code**
```bash
curl -X POST http://localhost:8000/api/email/verify-with-code \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","code":"INVALID"}'
```

**Expected Response:**
```json
{
    "success": false,
    "message": "Invalid or expired verification code"
}
```

**Test 4b: Already Verified Email**
```bash
curl -X POST http://localhost:8000/api/email/send-verification-code \
  -H "Content-Type: application/json" \
  -d '{"email":"verified@example.com"}'
```

**Expected Response:**
```json
{
    "success": false,
    "message": "Email already verified"
}
```

**Test 4c: Non-existent Email**
```bash
curl -X POST http://localhost:8000/api/email/send-verification-code \
  -H "Content-Type: application/json" \
  -d '{"email":"nonexistent@example.com"}'
```

**Expected Response:**
```json
{
    "success": false,
    "message": "User not found"
}
```

---

## 🔍 Verification Queries

### Check Verification Codes Table
```sql
SELECT * FROM verification_codes;
```

### Check Active Codes
```sql
SELECT * FROM verification_codes 
WHERE used_at IS NULL 
AND expires_at > NOW() 
AND attempts < max_attempts;
```

### Check User Verification Status
```sql
SELECT id, email, email_verified_at FROM users WHERE id = 1;
```

### Check Code Attempts
```sql
SELECT user_id, code, attempts, max_attempts, expires_at 
FROM verification_codes 
WHERE user_id = 1;
```

---

## 🚨 Rollback Plan

If something goes wrong, you can rollback:

### Option 1: Rollback Last Migration
```bash
php artisan migrate:rollback
```

### Option 2: Rollback Specific Migration
```bash
php artisan migrate:rollback --step=1
```

### Option 3: Restore from Backup
```bash
mysql -u root -p kokokah_lms < backup_YYYYMMDD_HHMMSS.sql
```

---

## 📊 Monitoring

### Monitor Email Sending
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Look for VerificationCodeNotification entries
grep -i "verification" storage/logs/laravel.log
```

### Monitor Database
```bash
# Check for failed verification attempts
SELECT user_id, COUNT(*) as failed_attempts 
FROM verification_codes 
WHERE attempts >= max_attempts 
GROUP BY user_id;
```

### Monitor Performance
```bash
# Check query performance
EXPLAIN SELECT * FROM verification_codes 
WHERE user_id = 1 AND type = 'email';
```

---

## 🔐 Security Checklist

- [ ] HTTPS is enabled in production
- [ ] Email credentials are secure in `.env`
- [ ] Database backups are in place
- [ ] Rate limiting is configured (optional)
- [ ] Codes are not logged in plain text
- [ ] Database indexes are created
- [ ] Foreign key constraints are enforced
- [ ] User input is validated

---

## 📱 Frontend Integration Checklist

- [ ] Verification page created/updated
- [ ] Code input field added (6 characters)
- [ ] Send code button implemented
- [ ] Verify code button implemented
- [ ] Resend code link added
- [ ] Error messages displayed
- [ ] Success messages displayed
- [ ] Loading states handled
- [ ] Timer for code expiration added
- [ ] Attempt counter displayed

---

## 📞 Support & Troubleshooting

### Issue: Migration fails
**Solution:** Check database connection and permissions
```bash
php artisan migrate --verbose
```

### Issue: Codes not sending
**Solution:** Check mail configuration
```bash
php artisan config:cache
php artisan cache:clear
```

### Issue: "Table already exists"
**Solution:** Check if migration already ran
```bash
php artisan migrate:status
```

### Issue: Foreign key constraint error
**Solution:** Ensure users table exists and has correct structure
```bash
php artisan migrate:refresh
```

---

## ✨ Final Checklist

- [ ] All files created and modified
- [ ] Migration run successfully
- [ ] Email configuration verified
- [ ] All 6 endpoints tested
- [ ] Error cases tested
- [ ] Database verified
- [ ] Documentation reviewed
- [ ] Frontend integration planned
- [ ] Security checklist completed
- [ ] Monitoring setup complete

---

## 🎉 Deployment Complete!

Once all items are checked, your email verification code system is ready for production use.

**Status: ✅ READY FOR DEPLOYMENT**

---

## 📚 Documentation Reference

- **Full API Docs**: `VERIFICATION_CODE_IMPLEMENTATION.md`
- **Setup Guide**: `VERIFICATION_CODE_SETUP_GUIDE.md`
- **Quick Reference**: `VERIFICATION_CODE_QUICK_REFERENCE.md`
- **Flow Diagrams**: `VERIFICATION_CODE_FLOW_DIAGRAM.md`
- **Summary**: `VERIFICATION_CODE_SUMMARY.md`
- **Completion**: `IMPLEMENTATION_COMPLETE.md`

---

*Deployment Date: October 26, 2025*
*Version: 1.0*
*Status: Ready for Production*

