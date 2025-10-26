# Kokokah LMS - Testing Guide

**Last Updated:** October 26, 2025

---

## 🧪 Testing Overview

This guide covers testing all 220+ endpoints of the Kokokah LMS API using multiple methods:
- Postman Collection
- Frontend HTML/CSS/Bootstrap Application
- Manual cURL Commands
- Automated Test Scripts

---

## 📋 Prerequisites

1. **Backend Running**
   ```bash
   php artisan serve
   ```

2. **Database Migrated**
   ```bash
   php artisan migrate
   ```

3. **Postman Installed** (optional)
   - Download from https://www.postman.com/downloads/

4. **Frontend Files**
   - Located in `frontend/` directory

---

## 🚀 Quick Start

### Option 1: Using Postman (Recommended)

1. **Import Collection**
   - Open Postman
   - Click "Import"
   - Select `postman/Kokokah_LMS_API.postman_collection.json`

2. **Import Environment**
   - Click "Environments"
   - Click "Import"
   - Select `postman/Kokokah_LMS_Environment.postman_environment.json`

3. **Configure Variables**
   - Set `base_url` to your API URL (default: `http://localhost:8000/api`)
   - Set `token` after login

4. **Run Tests**
   - Select environment
   - Click on requests to test

### Option 2: Using Frontend Application

1. **Open Frontend**
   ```bash
   # Open in browser
   file:///path/to/frontend/index.html
   ```

2. **Configure API**
   - Click "Configure API" link on login page
   - Enter API URL: `http://localhost:8000/api`
   - Click "Save"

3. **Test Features**
   - Register new account
   - Login
   - Browse courses
   - Enroll in courses
   - View dashboard

### Option 3: Using cURL

```bash
# Register
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "student"
  }'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'

# Get Current User (replace TOKEN with actual token)
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer TOKEN"
```

---

## 📊 Test Categories

### 1. Authentication Tests (8 endpoints)
- [ ] Register user
- [ ] Login user
- [ ] Get current user
- [ ] Logout user
- [ ] Send verification code
- [ ] Verify email with code
- [ ] Forgot password
- [ ] Reset password

### 2. Course Tests (15 endpoints)
- [ ] Get all courses
- [ ] Search courses
- [ ] Get featured courses
- [ ] Get popular courses
- [ ] Get single course
- [ ] Create course
- [ ] Update course
- [ ] Delete course
- [ ] Enroll in course
- [ ] Unenroll from course
- [ ] Get course students
- [ ] Get course analytics
- [ ] Publish course
- [ ] Unpublish course
- [ ] Get my courses

### 3. Lesson Tests (9 endpoints)
- [ ] Get lessons
- [ ] Create lesson
- [ ] Get single lesson
- [ ] Update lesson
- [ ] Delete lesson
- [ ] Complete lesson
- [ ] Get lesson progress
- [ ] Track watch time
- [ ] Get attachments

### 4. Quiz Tests (9 endpoints)
- [ ] Get quizzes
- [ ] Create quiz
- [ ] Get single quiz
- [ ] Update quiz
- [ ] Delete quiz
- [ ] Start quiz
- [ ] Submit quiz
- [ ] Get results
- [ ] Get analytics

### 5. User Tests (9 endpoints)
- [ ] Get profile
- [ ] Update profile
- [ ] Get dashboard
- [ ] Get achievements
- [ ] Get learning stats
- [ ] Update preferences
- [ ] Get notifications
- [ ] Mark as read
- [ ] Change password

### 6. Wallet & Payment Tests (12 endpoints)
- [ ] Get wallet balance
- [ ] Transfer money
- [ ] Purchase with wallet
- [ ] Get transactions
- [ ] Get rewards
- [ ] Claim reward
- [ ] Check affordability
- [ ] Get gateways
- [ ] Deposit funds
- [ ] Purchase course
- [ ] Get history
- [ ] Get payment details

### 7. Certificate Tests (15 endpoints)
- [ ] Get certificates
- [ ] Get templates
- [ ] Generate certificate
- [ ] Bulk generate
- [ ] Get certificate
- [ ] Download certificate
- [ ] Revoke certificate
- [ ] Verify certificate
- [ ] Get analytics
- [ ] Get badges
- [ ] Get leaderboard
- [ ] Get my badges
- [ ] Get user badges
- [ ] Award badge
- [ ] Revoke badge

### 8. Progress & Grading Tests (18 endpoints)
- [ ] Get course progress
- [ ] Get lesson progress
- [ ] Get overall progress
- [ ] Update progress
- [ ] Get available certificates
- [ ] Generate certificate
- [ ] Get achievements
- [ ] Get streaks
- [ ] Get gradebook
- [ ] Get course grades
- [ ] Get student grades
- [ ] Bulk grade
- [ ] Get analytics
- [ ] Export grades
- [ ] Get grade history
- [ ] Update weights
- [ ] Add comments
- [ ] Get reports

### 9. Reviews & Forum Tests (24 endpoints)
- [ ] Get reviews
- [ ] Create review
- [ ] Get analytics
- [ ] Get to moderate
- [ ] Get my reviews
- [ ] Get review
- [ ] Update review
- [ ] Delete review
- [ ] Mark helpful
- [ ] Approve review
- [ ] Reject review
- [ ] Get forum
- [ ] Create topic
- [ ] Get forum analytics
- [ ] Get topic
- [ ] Update topic
- [ ] Delete topic
- [ ] Subscribe
- [ ] Unsubscribe
- [ ] Create post
- [ ] Update post
- [ ] Delete post
- [ ] Like post
- [ ] Mark solution

### 10. Additional Tests
- [ ] Learning Paths (12 endpoints)
- [ ] Admin (15 endpoints)
- [ ] Analytics (9 endpoints)
- [ ] Notifications (9 endpoints)
- [ ] Search (6 endpoints)
- [ ] Files (8 endpoints)
- [ ] Language (9 endpoints)
- [ ] Chat (8 endpoints)
- [ ] Recommendations (7 endpoints)
- [ ] Coupons (10 endpoints)
- [ ] Reports (8 endpoints)
- [ ] Settings (9 endpoints)
- [ ] Audit & Security (6 endpoints)
- [ ] Video Streaming (9 endpoints)
- [ ] Real-time Features (9 endpoints)
- [ ] Localization (8 endpoints)

---

## 🔍 Testing Best Practices

### 1. Test Order
1. Authentication (register, login)
2. User profile
3. Courses (browse, enroll)
4. Lessons (view, complete)
5. Quizzes (start, submit)
6. Payments (purchase)
7. Certificates (generate)
8. Advanced features

### 2. Error Handling
- Test with invalid data
- Test with missing required fields
- Test with unauthorized access
- Test with non-existent resources
- Test with rate limiting

### 3. Response Validation
- Check status codes (200, 201, 400, 401, 404, 422, 500)
- Validate response structure
- Check data types
- Verify pagination
- Check error messages

### 4. Performance Testing
- Measure response times
- Test with large datasets
- Test pagination
- Monitor memory usage
- Check database queries

---

## 📝 Test Report Template

```
Test Date: [DATE]
Tester: [NAME]
Environment: [DEV/STAGING/PROD]

Total Endpoints: 220+
Endpoints Tested: [X]
Passed: [X]
Failed: [X]
Skipped: [X]

Issues Found:
1. [Issue description]
2. [Issue description]

Recommendations:
1. [Recommendation]
2. [Recommendation]
```

---

## 🐛 Common Issues & Solutions

### Issue: 401 Unauthorized
**Solution:** Check token validity, re-login if needed

### Issue: 422 Validation Error
**Solution:** Check required fields, validate data types

### Issue: 404 Not Found
**Solution:** Check endpoint path, verify resource exists

### Issue: 500 Server Error
**Solution:** Check server logs, verify database connection

### Issue: CORS Error
**Solution:** Check CORS headers, verify origin is allowed

---

## 📊 Test Metrics

- **Total Endpoints:** 220+
- **Authentication Endpoints:** 8
- **Core Feature Endpoints:** 60+
- **Advanced Feature Endpoints:** 150+
- **Expected Pass Rate:** 95%+

---

## 🚀 Continuous Testing

### Automated Tests
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=test_name

# Run with coverage
php artisan test --coverage
```

### Postman Collection Runner
1. Open Postman
2. Click "Runner"
3. Select collection
4. Select environment
5. Click "Run"

---

## 📞 Support

For testing issues:
1. Check API documentation
2. Review error messages
3. Check server logs
4. Contact development team

---

## ✅ Sign-Off

- [ ] All endpoints tested
- [ ] All tests passed
- [ ] Documentation reviewed
- [ ] Performance acceptable
- [ ] Security verified
- [ ] Ready for production

---

*Last Updated: October 26, 2025*  
*Status: ✅ Ready for Testing*

