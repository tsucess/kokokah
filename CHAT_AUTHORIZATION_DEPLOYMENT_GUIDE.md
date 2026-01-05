# 🚀 Chat Authorization - Deployment Guide

## ✅ Pre-Deployment Checklist

### Code Review
- [ ] Review `app/Policies/ChatRoomPolicy.php`
- [ ] Review `app/Policies/ChatMessagePolicy.php`
- [ ] Review `app/Http/Middleware/EnsureUserAuthenticatedForChat.php`
- [ ] Review `app/Http/Middleware/AuthorizeChatRoomAccess.php`
- [ ] Review `app/Http/Middleware/CheckChatRoomMuteStatus.php`
- [ ] Review `app/Services/ChatAuthorizationService.php`
- [ ] Review routes in `routes/api.php`

### Testing
- [ ] Run unit tests: `php artisan test tests/Unit/`
- [ ] Run feature tests: `php artisan test tests/Feature/ChatAuthorizationTest.php`
- [ ] Run all tests: `php artisan test`
- [ ] Check test coverage: `php artisan test --coverage`
- [ ] Manual testing in development environment

### Security Review
- [ ] Verify authentication is required for all endpoints
- [ ] Verify authorization checks are in place
- [ ] Verify middleware is applied to all chat routes
- [ ] Verify policies are enforced in controllers
- [ ] Verify error messages don't leak sensitive info
- [ ] Verify rate limiting is configured
- [ ] Verify CORS is properly configured

### Database
- [ ] Verify all migrations are run
- [ ] Verify database schema matches models
- [ ] Verify indexes are created for performance
- [ ] Backup production database

### Documentation
- [ ] Review CHAT_AUTHORIZATION_COMPLETE_GUIDE.md
- [ ] Review CHAT_AUTHORIZATION_TESTING_GUIDE.md
- [ ] Review CHAT_AUTHORIZATION_QUICK_REFERENCE.md
- [ ] Update team documentation
- [ ] Update API documentation

---

## 📋 Deployment Steps

### Step 1: Pre-Deployment
```bash
# Pull latest code
git pull origin main

# Install dependencies
composer install

# Run migrations
php artisan migrate

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Step 2: Testing
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test tests/Feature/ChatAuthorizationTest.php

# Check test coverage
php artisan test --coverage
```

### Step 3: Deployment
```bash
# Build assets (if needed)
npm run build

# Optimize for production
php artisan optimize

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

### Step 4: Post-Deployment
```bash
# Verify deployment
php artisan tinker

# Check authorization service
>>> $service = new \App\Services\ChatAuthorizationService();
>>> $service->canAccessRoom($user, $chatRoom);

# Monitor logs
tail -f storage/logs/laravel.log
```

---

## 🔍 Verification Steps

### 1. Verify Policies
```bash
php artisan tinker

# Test ChatRoomPolicy
>>> $policy = new \App\Policies\ChatRoomPolicy();
>>> $admin = \App\Models\User::where('role', 'admin')->first();
>>> $room = \App\Models\ChatRoom::first();
>>> $policy->view($admin, $room);
```

### 2. Verify Middleware
```bash
# Check middleware is registered
php artisan route:list | grep chatrooms

# Verify middleware is applied
php artisan route:list --name=chatrooms
```

### 3. Verify Service
```bash
php artisan tinker

# Test authorization service
>>> $service = new \App\Services\ChatAuthorizationService();
>>> $user = \App\Models\User::first();
>>> $room = \App\Models\ChatRoom::first();
>>> $service->canAccessRoom($user, $room);
```

### 4. Test API Endpoints
```bash
# Test as admin
curl -H "Authorization: Bearer $ADMIN_TOKEN" \
  http://localhost:8000/api/chatrooms/1/messages

# Test as member
curl -H "Authorization: Bearer $MEMBER_TOKEN" \
  http://localhost:8000/api/chatrooms/1/messages

# Test as non-member (should fail)
curl -H "Authorization: Bearer $NON_MEMBER_TOKEN" \
  http://localhost:8000/api/chatrooms/1/messages
```

---

## 🔒 Security Verification

### 1. Authentication Check
```bash
# Test without token (should fail)
curl http://localhost:8000/api/chatrooms/1/messages
# Expected: 401 Unauthorized

# Test with invalid token (should fail)
curl -H "Authorization: Bearer invalid_token" \
  http://localhost:8000/api/chatrooms/1/messages
# Expected: 401 Unauthorized
```

### 2. Authorization Check
```bash
# Test non-member access (should fail)
curl -H "Authorization: Bearer $NON_MEMBER_TOKEN" \
  http://localhost:8000/api/chatrooms/1/messages
# Expected: 403 Forbidden

# Test member access (should succeed)
curl -H "Authorization: Bearer $MEMBER_TOKEN" \
  http://localhost:8000/api/chatrooms/1/messages
# Expected: 200 OK
```

### 3. Message Authorization Check
```bash
# Test user editing others message (should fail)
curl -X PUT \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"content":"hacked"}' \
  http://localhost:8000/api/chatrooms/1/messages/2
# Expected: 403 Forbidden

# Test user editing own message (should succeed)
curl -X PUT \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"content":"updated"}' \
  http://localhost:8000/api/chatrooms/1/messages/1
# Expected: 200 OK
```

---

## 📊 Monitoring

### Log Monitoring
```bash
# Watch logs in real-time
tail -f storage/logs/laravel.log

# Search for authorization failures
grep "authorization" storage/logs/laravel.log

# Search for errors
grep "ERROR" storage/logs/laravel.log
```

### Performance Monitoring
```bash
# Check query performance
php artisan tinker
>>> \DB::enableQueryLog();
>>> $service = new \App\Services\ChatAuthorizationService();
>>> $service->canAccessRoom($user, $room);
>>> \DB::getQueryLog();
```

### Error Monitoring
```bash
# Check for 403 errors
grep "403" storage/logs/laravel.log

# Check for 401 errors
grep "401" storage/logs/laravel.log

# Check for 500 errors
grep "500" storage/logs/laravel.log
```

---

## 🚨 Rollback Plan

If issues occur after deployment:

### Step 1: Immediate Rollback
```bash
# Revert to previous commit
git revert HEAD

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Restart services
php artisan queue:restart
```

### Step 2: Database Rollback
```bash
# Rollback migrations
php artisan migrate:rollback

# Restore from backup
# (Use your backup strategy)
```

### Step 3: Notify Team
- Notify team of rollback
- Document what went wrong
- Plan fix for next deployment

---

## 📞 Support

### During Deployment
- Have team available for testing
- Monitor logs in real-time
- Have rollback plan ready
- Document any issues

### After Deployment
- Monitor for errors
- Check performance metrics
- Gather user feedback
- Plan improvements

---

## ✅ Post-Deployment Checklist

- [ ] All tests passing
- [ ] No errors in logs
- [ ] Authorization working correctly
- [ ] Performance acceptable
- [ ] Team notified of changes
- [ ] Documentation updated
- [ ] Monitoring configured
- [ ] Rollback plan documented

---

**Status:** ✅ **DEPLOYMENT GUIDE COMPLETE!** 🚀


