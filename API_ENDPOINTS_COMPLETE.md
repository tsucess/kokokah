# 🔌 Kokokah.com LMS - Complete API Endpoints Documentation

**Date:** October 23, 2025  
**Base URL:** `http://localhost:8000/api`  
**Authentication:** Bearer Token (Laravel Sanctum)  
**Total Endpoints:** 100+

---

## 📋 Authentication Endpoints (6)

### 1. Register User
```
POST /register
Content-Type: application/json

{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "student"
}

Response: 201 Created
{
  "status": "success",
  "message": "User registered successfully",
  "user": {...},
  "token": "1|token..."
}
```

### 2. Login
```
POST /login
{
  "email": "john@example.com",
  "password": "password123"
}

Response: 200 OK
{
  "status": "success",
  "message": "Login successful",
  "user": {...},
  "token": "1|token..."
}
```

### 3. Get Current User
```
GET /user
Authorization: Bearer {token}

Response: 200 OK
{
  "success": true,
  "data": {...}
}
```

### 4. Logout
```
POST /logout
Authorization: Bearer {token}

Response: 200 OK
{
  "status": "success",
  "message": "Logged out successfully"
}
```

### 5. Forgot Password
```
POST /forgot-password
{
  "email": "john@example.com"
}

Response: 200 OK
{
  "status": "success",
  "message": "Password reset link sent"
}
```

### 6. Reset Password
```
POST /reset-password
{
  "token": "reset-token",
  "email": "john@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}

Response: 200 OK
{
  "status": "success",
  "message": "Password reset successfully"
}
```

---

## 📚 Course Endpoints (15+)

### Public Endpoints
```
GET /courses                    # List all courses
GET /courses/search             # Search courses
GET /courses/featured           # Get featured courses
GET /courses/popular            # Get popular courses
GET /courses/{id}               # Get course details
```

### Authenticated Endpoints
```
GET /courses/my-courses         # Get user's courses
POST /courses                   # Create course (instructor/admin)
PUT /courses/{id}               # Update course
DELETE /courses/{id}            # Delete course
GET /courses/{id}/students      # Get course students
GET /courses/{id}/analytics     # Get course analytics
POST /courses/{id}/publish      # Publish course
POST /courses/{id}/unpublish    # Unpublish course
POST /courses/{id}/enroll       # Enroll in course
DELETE /courses/{id}/unenroll   # Unenroll from course
```

---

## 📖 Lesson Endpoints (8)

```
GET /courses/{courseId}/lessons         # List lessons
POST /courses/{courseId}/lessons        # Create lesson
GET /lessons/{id}                       # Get lesson
PUT /lessons/{id}                       # Update lesson
DELETE /lessons/{id}                    # Delete lesson
POST /lessons/{id}/complete             # Mark complete
GET /lessons/{id}/progress              # Get progress
POST /lessons/{id}/watch-time           # Track watch time
```

---

## ❓ Quiz Endpoints (9)

```
GET /lessons/{lessonId}/quizzes         # List quizzes
POST /lessons/{lessonId}/quizzes        # Create quiz
GET /quizzes/{id}                       # Get quiz
PUT /quizzes/{id}                       # Update quiz
DELETE /quizzes/{id}                    # Delete quiz
POST /quizzes/{id}/start                # Start attempt
POST /quizzes/{id}/submit               # Submit quiz
GET /quizzes/{id}/results               # Get results
GET /quizzes/{id}/analytics             # Get analytics
```

---

## 📝 Assignment Endpoints (8)

```
GET /courses/{courseId}/assignments     # List assignments
POST /courses/{courseId}/assignments    # Create assignment
GET /assignments/{id}                   # Get assignment
PUT /assignments/{id}                   # Update assignment
DELETE /assignments/{id}                # Delete assignment
POST /assignments/{id}/submit           # Submit assignment
GET /assignments/{id}/submissions       # Get submissions
PUT /submissions/{id}/grade             # Grade submission
```

---

## 💳 Payment Endpoints (15+)

```
GET /payments/gateways                  # Get available gateways
POST /payments/deposit                  # Initialize deposit
POST /payments/purchase-course          # Initialize course payment
GET /payments/history                   # Get payment history
GET /payments/{id}                      # Get payment details
POST /payments/webhook/{gateway}        # Webhook handler
GET /payments/callback/{gateway}        # Payment callback
GET /payments/success/{gateway}         # Success page
GET /payments/cancel/{gateway}          # Cancel page
```

---

## 👛 Wallet Endpoints (7)

```
GET /wallet                             # Get wallet info
POST /wallet/transfer                   # Transfer funds
POST /wallet/purchase-course            # Purchase with wallet
GET /wallet/transactions                # Get transactions
GET /wallet/rewards                     # Get rewards
POST /wallet/claim-login-reward         # Claim daily reward
POST /wallet/check-affordability        # Check affordability
```

---

## 📊 Enrollment Endpoints (8)

```
GET /enrollments                        # List enrollments
POST /enrollments                       # Create enrollment
GET /enrollments/{id}                   # Get enrollment
PUT /enrollments/{id}                   # Update enrollment
DELETE /enrollments/{id}                # Delete enrollment
GET /enrollments/{id}/progress          # Get progress
POST /enrollments/{id}/complete         # Complete enrollment
GET /enrollments/certificates           # Get certificates
```

---

## 👤 User Endpoints (8)

```
GET /users/profile                      # Get profile
PUT /users/profile                      # Update profile
GET /users/dashboard                    # Get dashboard
GET /users/achievements                 # Get achievements
GET /users/learning-stats               # Get learning stats
PUT /users/preferences                  # Update preferences
GET /users/notifications                # Get notifications
POST /users/change-password             # Change password
```

---

## 🏆 Badge Endpoints (8)

```
GET /badges                             # List badges
GET /badges/analytics                   # Get analytics
GET /badges/leaderboard                 # Get leaderboard
POST /badges                            # Create badge
POST /badges/award                      # Award badge
POST /badges/check-automatic/{userId}   # Check automatic
GET /badges/{id}                        # Get badge
PUT /badges/{id}                        # Update badge
```

---

## 📜 Certificate Endpoints (8)

```
GET /certificates                       # List certificates
GET /certificates/analytics             # Get analytics
GET /certificates/templates             # Get templates
POST /certificates/generate             # Generate certificate
POST /certificates/bulk-generate        # Bulk generate
GET /certificates/{id}                  # Get certificate
GET /certificates/{id}/download         # Download certificate
POST /certificates/{id}/revoke          # Revoke certificate
```

---

## 💬 Forum Endpoints (15+)

```
GET /courses/{courseId}/forum           # List forum topics
POST /courses/{courseId}/forum          # Create topic
GET /forum/topics/{id}                  # Get topic
PUT /forum/topics/{id}                  # Update topic
DELETE /forum/topics/{id}               # Delete topic
POST /forum/topics/{id}/subscribe       # Subscribe
DELETE /forum/topics/{id}/unsubscribe   # Unsubscribe
POST /forum/topics/{id}/posts           # Create post
PUT /forum/posts/{id}                   # Update post
DELETE /forum/posts/{id}                # Delete post
POST /forum/posts/{id}/like             # Like post
POST /forum/posts/{id}/solution         # Mark solution
```

---

## 🤖 Chat Endpoints (6)

```
POST /chat/start                        # Start session
POST /chat/sessions/{sessionId}/message # Send message
GET /chat/sessions/{sessionId}          # Get history
GET /chat/sessions                      # Get user sessions
POST /chat/sessions/{sessionId}/end     # End session
POST /chat/sessions/{sessionId}/rate    # Rate session
```

---

## 📈 Analytics Endpoints (12+)

```
GET /analytics/learning                 # Learning analytics
GET /analytics/course-performance       # Course performance
GET /analytics/student-progress         # Student progress
GET /analytics/revenue                  # Revenue analytics
GET /analytics/engagement               # Engagement analytics
POST /analytics/comparative             # Comparative analytics
POST /analytics/export                  # Export analytics
GET /analytics/real-time                # Real-time analytics
GET /analytics/predictive               # Predictive analytics
```

---

## 🎓 Learning Path Endpoints (10)

```
GET /learning-paths                     # List paths
POST /learning-paths                    # Create path
GET /learning-paths/{id}                # Get path
PUT /learning-paths/{id}                # Update path
DELETE /learning-paths/{id}             # Delete path
POST /learning-paths/{id}/enroll        # Enroll
DELETE /learning-paths/{id}/unenroll    # Unenroll
GET /learning-paths/{id}/progress       # Get progress
GET /learning-paths/{id}/analytics      # Get analytics
POST /learning-paths/{id}/publish       # Publish
```

---

## 🎁 Coupon Endpoints (5)

```
GET /coupons                            # List coupons
POST /coupons                           # Create coupon
GET /coupons/{id}                       # Get coupon
PUT /coupons/{id}                       # Update coupon
DELETE /coupons/{id}                    # Delete coupon
```

---

## 🔍 Search Endpoints (3)

```
GET /search                             # Global search
GET /search/courses                     # Search courses
GET /search/instructors                 # Search instructors
```

---

## 💡 Recommendation Endpoints (5)

```
GET /recommendations                    # Get recommendations
GET /recommendations/courses/{courseId} # Course-based
GET /recommendations/learning-paths     # Path recommendations
GET /recommendations/instructors        # Instructor recommendations
PUT /recommendations/preferences        # Update preferences
```

---

## 🎬 Video Streaming Endpoints (9)

```
GET /videos                             # List videos
POST /videos                            # Upload video
GET /videos/{id}                        # Get video
PUT /videos/{id}                        # Update video
DELETE /videos/{id}                     # Delete video
GET /videos/{id}/stream                 # Stream video
GET /videos/{id}/qualities              # Get qualities
GET /videos/{id}/analytics              # Get analytics
POST /videos/{id}/download              # Track download
```

---

## ⚙️ Admin Endpoints (15+)

```
GET /admin/dashboard                    # Admin dashboard
GET /admin/users                        # List users
GET /admin/courses                      # List courses
GET /admin/payments                     # List payments
GET /admin/reports                      # Get reports
GET /admin/settings                     # Get settings
GET /admin/stats                        # Get stats
POST /admin/users/{userId}/ban          # Ban user
POST /admin/users/{userId}/unban        # Unban user
GET /admin/analytics                    # Get analytics
POST /admin/bulk-actions                # Bulk actions
GET /admin/audit-logs                   # Get audit logs
POST /admin/maintenance                 # Maintenance mode
POST /admin/clear-cache                 # Clear cache
GET /admin/database-stats               # Database stats
```

---

## 📊 Grading Endpoints (10)

```
GET /grading/gradebook/{courseId}       # Get gradebook
GET /grading/courses/{courseId}         # Course grades
GET /grading/students/{studentId}       # Student grades
POST /grading/bulk-grade                # Bulk grade
GET /grading/analytics                  # Get analytics
POST /grading/export                    # Export grades
GET /grading/grade-history/{studentId}/{courseId}  # History
PUT /grading/weights/{courseId}         # Update weights
POST /grading/comments                  # Add comments
GET /grading/reports/{courseId}         # Get reports
```

---

## 📋 Summary

| Category | Count | Status |
|----------|-------|--------|
| Authentication | 6 | ✅ |
| Courses | 15+ | ✅ |
| Lessons | 8 | ✅ |
| Quizzes | 9 | ✅ |
| Assignments | 8 | ✅ |
| Payments | 15+ | ✅ |
| Wallet | 7 | ✅ |
| Enrollments | 8 | ✅ |
| Users | 8 | ✅ |
| Badges | 8 | ✅ |
| Certificates | 8 | ✅ |
| Forum | 15+ | ✅ |
| Chat | 6 | ✅ |
| Analytics | 12+ | ✅ |
| Learning Paths | 10 | ✅ |
| Coupons | 5 | ✅ |
| Search | 3 | ✅ |
| Recommendations | 5 | ✅ |
| Video | 9 | ✅ |
| Admin | 15+ | ✅ |
| Grading | 10 | ✅ |
| **TOTAL** | **100+** | **✅** |

---

## 🔐 Authentication

All endpoints (except public ones) require:
```
Authorization: Bearer {token}
```

### Public Endpoints
- POST /register
- POST /login
- POST /forgot-password
- POST /reset-password
- GET /courses
- GET /courses/search
- GET /courses/featured
- GET /courses/popular
- GET /courses/{id}
- GET /certificates/verify/{certificateNumber}
- POST /payments/webhook/{gateway}
- GET /payments/callback/{gateway}

---

## 📝 Response Format

### Success Response
```json
{
  "success": true,
  "data": {...},
  "message": "Operation successful"
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {...}
}
```

---

**Documentation Completed:** October 23, 2025

