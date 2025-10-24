# 🚀 Kokokah.com LMS - Complete API Endpoints Reference

## 📊 Overview

**Total Endpoints:** 200+  
**API Version:** v1  
**Authentication:** Laravel Sanctum (Token-based)  
**Base URL:** `http://localhost:8000/api`

---

## 📋 Endpoint Categories

### 1. **Authentication (6 endpoints)**
- `POST /register` - User registration
- `POST /login` - User login
- `POST /logout` - User logout
- `GET /user` - Get current user
- `POST /forgot-password` - Request password reset
- `POST /reset-password` - Reset password

### 2. **Categories (5 endpoints)**
- `GET /category` - List categories
- `POST /category` - Create category
- `GET /category/{id}` - Get category
- `PUT /category/{id}` - Update category
- `DELETE /category/{id}` - Delete category

### 3. **Courses (15+ endpoints)**
- `GET /courses` - List courses
- `GET /courses/search` - Search courses
- `GET /courses/featured` - Get featured courses
- `GET /courses/popular` - Get popular courses
- `GET /courses/{id}` - Get course details
- `GET /courses/my-courses` - Get user's courses
- `POST /courses` - Create course (instructor/admin)
- `PUT /courses/{id}` - Update course
- `DELETE /courses/{id}` - Delete course
- `GET /courses/{id}/students` - Get course students
- `GET /courses/{id}/analytics` - Get course analytics
- `POST /courses/{id}/publish` - Publish course
- `POST /courses/{id}/unpublish` - Unpublish course
- `POST /courses/{id}/enroll` - Enroll in course
- `DELETE /courses/{id}/unenroll` - Unenroll from course

### 4. **Lessons (8 endpoints)**
- `GET /courses/{courseId}/lessons` - List lessons
- `POST /courses/{courseId}/lessons` - Create lesson
- `GET /lessons/{id}` - Get lesson
- `PUT /lessons/{id}` - Update lesson
- `DELETE /lessons/{id}` - Delete lesson
- `POST /lessons/{id}/complete` - Mark lesson complete
- `GET /lessons/{id}/progress` - Get lesson progress
- `POST /lessons/{id}/watch-time` - Track watch time
- `GET /lessons/{id}/attachments` - Get attachments

### 5. **Enrollments (7 endpoints)**
- `GET /enrollments` - List enrollments
- `POST /enrollments` - Create enrollment
- `GET /enrollments/{id}` - Get enrollment
- `PUT /enrollments/{id}` - Update enrollment
- `DELETE /enrollments/{id}` - Delete enrollment
- `GET /enrollments/{id}/progress` - Get enrollment progress
- `POST /enrollments/{id}/complete` - Complete enrollment
- `GET /enrollments/certificates` - Get certificates

### 6. **Quizzes (8 endpoints)**
- `GET /lessons/{lessonId}/quizzes` - List quizzes
- `POST /lessons/{lessonId}/quizzes` - Create quiz
- `GET /quizzes/{id}` - Get quiz
- `PUT /quizzes/{id}` - Update quiz
- `DELETE /quizzes/{id}` - Delete quiz
- `POST /quizzes/{id}/start` - Start quiz attempt
- `POST /quizzes/{id}/submit` - Submit quiz
- `GET /quizzes/{id}/results` - Get quiz results
- `GET /quizzes/{id}/analytics` - Get quiz analytics

### 7. **Assignments (7 endpoints)**
- `GET /courses/{courseId}/assignments` - List assignments
- `POST /courses/{courseId}/assignments` - Create assignment
- `GET /assignments/{id}` - Get assignment
- `PUT /assignments/{id}` - Update assignment
- `DELETE /assignments/{id}` - Delete assignment
- `POST /assignments/{id}/submit` - Submit assignment
- `GET /assignments/{id}/submissions` - Get submissions
- `GET /assignments/{id}/grades` - Get grades
- `PUT /submissions/{id}/grade` - Grade submission

### 8. **Users (8 endpoints)**
- `GET /users/profile` - Get user profile
- `PUT /users/profile` - Update profile
- `GET /users/dashboard` - Get user dashboard
- `GET /users/achievements` - Get achievements
- `GET /users/learning-stats` - Get learning stats
- `PUT /users/preferences` - Update preferences
- `GET /users/notifications` - Get notifications
- `POST /users/notifications/read` - Mark notifications read
- `POST /users/change-password` - Change password

### 9. **Payments (7 endpoints)**
- `GET /payments/gateways` - Get payment gateways
- `POST /payments/deposit` - Initialize wallet deposit
- `POST /payments/purchase-course` - Initialize course payment
- `GET /payments/history` - Get payment history
- `GET /payments/{id}` - Get payment details
- `POST /payments/webhook/{gateway}` - Payment webhook
- `GET /payments/callback/{gateway}` - Payment callback
- `GET /payments/success/{gateway}` - Payment success
- `GET /payments/cancel/{gateway}` - Payment cancel

### 10. **Wallet (7 endpoints)**
- `GET /wallet` - Get wallet balance
- `POST /wallet/transfer` - Transfer funds
- `POST /wallet/purchase-course` - Purchase course
- `GET /wallet/transactions` - Get transactions
- `GET /wallet/rewards` - Get rewards
- `POST /wallet/claim-login-reward` - Claim login reward
- `POST /wallet/check-affordability` - Check affordability

### 11. **Dashboard (4 endpoints)**
- `GET /dashboard/student` - Student dashboard
- `GET /dashboard/instructor` - Instructor dashboard
- `GET /dashboard/admin` - Admin dashboard
- `GET /dashboard/analytics` - Dashboard analytics

### 12. **Reviews (9 endpoints)**
- `GET /courses/{courseId}/reviews` - List reviews
- `POST /courses/{courseId}/reviews` - Create review
- `GET /courses/{courseId}/reviews/analytics` - Review analytics
- `GET /reviews/moderate` - Moderate reviews
- `GET /reviews/my-reviews` - Get user reviews
- `GET /reviews/{id}` - Get review
- `PUT /reviews/{id}` - Update review
- `DELETE /reviews/{id}` - Delete review
- `POST /reviews/{id}/helpful` - Mark helpful
- `POST /reviews/{id}/approve` - Approve review
- `POST /reviews/{id}/reject` - Reject review

### 13. **Forum (10 endpoints)**
- `GET /courses/{courseId}/forum` - List forum topics
- `POST /courses/{courseId}/forum` - Create topic
- `GET /courses/{courseId}/forum/analytics` - Forum analytics
- `GET /forum/topics/{id}` - Get topic
- `PUT /forum/topics/{id}` - Update topic
- `DELETE /forum/topics/{id}` - Delete topic
- `POST /forum/topics/{id}/subscribe` - Subscribe to topic
- `DELETE /forum/topics/{id}/unsubscribe` - Unsubscribe
- `POST /forum/topics/{id}/posts` - Create post
- `PUT /forum/posts/{id}` - Update post
- `DELETE /forum/posts/{id}` - Delete post
- `POST /forum/posts/{id}/like` - Like post
- `POST /forum/posts/{id}/solution` - Mark solution

### 14. **Certificates (7 endpoints)**
- `GET /certificates` - List certificates
- `GET /certificates/analytics` - Certificate analytics
- `GET /certificates/templates` - Get templates
- `POST /certificates/generate` - Generate certificate
- `POST /certificates/bulk-generate` - Bulk generate
- `GET /certificates/{id}` - Get certificate
- `GET /certificates/{id}/download` - Download certificate
- `POST /certificates/{id}/revoke` - Revoke certificate
- `GET /certificates/verify/{certificateNumber}` - Verify certificate

### 15. **Badges (9 endpoints)**
- `GET /badges` - List badges
- `GET /badges/analytics` - Badge analytics
- `GET /badges/leaderboard` - Get leaderboard
- `POST /badges` - Create badge
- `POST /badges/award` - Award badge
- `POST /badges/check-automatic/{userId}` - Check automatic badges
- `GET /badges/{id}` - Get badge
- `PUT /badges/{id}` - Update badge
- `DELETE /badges/{id}` - Delete badge
- `POST /badges/user-badges/{userId}/{badgeId}/revoke` - Revoke badge
- `GET /users/{userId}/badges` - Get user badges
- `GET /my-badges` - Get my badges

### 16. **Progress Tracking (8 endpoints)**
- `GET /progress/courses` - Course progress
- `GET /progress/lessons` - Lesson progress
- `GET /progress/overall` - Overall progress
- `POST /progress/update` - Update progress
- `GET /progress/certificates` - Available certificates
- `POST /progress/generate-cert` - Generate certificate
- `GET /progress/achievements` - Achievement progress
- `GET /progress/streaks` - Streak progress

### 17. **Grading (10 endpoints)**
- `GET /grading/gradebook/{courseId}` - Get gradebook
- `GET /grading/courses/{courseId}` - Course grades
- `GET /grading/students/{studentId}` - Student grades
- `POST /grading/bulk-grade` - Bulk grade
- `GET /grading/analytics` - Grading analytics
- `POST /grading/export` - Export grades
- `GET /grading/grade-history/{studentId}/{courseId}` - Grade history
- `PUT /grading/weights/{courseId}` - Update weights
- `POST /grading/comments` - Add comments
- `GET /grading/reports/{courseId}` - Get reports

### 18. **Admin (11 endpoints)**
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/users` - List users
- `GET /admin/courses` - List courses
- `GET /admin/payments` - List payments
- `GET /admin/reports` - Get reports
- `GET /admin/settings` - Get settings
- `GET /admin/stats` - Get stats
- `POST /admin/users/{userId}/ban` - Ban user
- `POST /admin/users/{userId}/unban` - Unban user
- `GET /admin/analytics` - Admin analytics
- `POST /admin/bulk-actions` - Bulk actions
- `GET /admin/audit-logs` - Audit logs
- `POST /admin/maintenance` - Maintenance mode
- `POST /admin/clear-cache` - Clear cache
- `GET /admin/database-stats` - Database stats

### 19. **Analytics (9 endpoints)**
- `GET /analytics/learning` - Learning analytics
- `GET /analytics/course-performance` - Course performance
- `GET /analytics/student-progress` - Student progress
- `GET /analytics/revenue` - Revenue analytics (admin)
- `GET /analytics/engagement` - Engagement analytics
- `POST /analytics/comparative` - Comparative analytics
- `POST /analytics/export` - Export analytics
- `GET /analytics/real-time` - Real-time analytics
- `GET /analytics/predictive` - Predictive analytics (admin)

### 20. **Learning Paths (11 endpoints)**
- `GET /learning-paths` - List paths
- `POST /learning-paths` - Create path
- `GET /learning-paths/{id}` - Get path
- `PUT /learning-paths/{id}` - Update path
- `DELETE /learning-paths/{id}` - Delete path
- `POST /learning-paths/{id}/enroll` - Enroll in path
- `DELETE /learning-paths/{id}/unenroll` - Unenroll from path
- `GET /learning-paths/my/paths` - Get my paths
- `GET /learning-paths/{id}/progress` - Path progress
- `GET /learning-paths/{id}/analytics` - Path analytics
- `POST /learning-paths/{id}/publish` - Publish path
- `POST /learning-paths/{id}/unpublish` - Unpublish path

### 21. **AI Chat (7 endpoints)**
- `POST /chat/start` - Start chat session
- `POST /chat/sessions/{sessionId}/message` - Send message
- `GET /chat/sessions/{sessionId}` - Get session history
- `GET /chat/sessions` - Get user sessions
- `POST /chat/sessions/{sessionId}/end` - End session
- `POST /chat/sessions/{sessionId}/rate` - Rate session
- `GET /chat/analytics` - Chat analytics (admin)
- `POST /chat/suggestions` - Get suggestions

### 22. **Recommendations (7 endpoints)**
- `GET /recommendations` - Get recommendations
- `GET /recommendations/courses/{courseId}` - Course recommendations
- `GET /recommendations/learning-paths` - Path recommendations
- `GET /recommendations/instructors` - Instructor recommendations
- `GET /recommendations/content` - Content recommendations
- `PUT /recommendations/preferences` - Update preferences
- `GET /recommendations/analytics` - Analytics (admin)

### 23. **Coupons (11 endpoints)**
- `GET /coupons` - List coupons
- `POST /coupons` - Create coupon
- `GET /coupons/{id}` - Get coupon
- `PUT /coupons/{id}` - Update coupon
- `DELETE /coupons/{id}` - Delete coupon
- `POST /coupons/validate` - Validate coupon
- `POST /coupons/apply` - Apply coupon
- `GET /coupons/user/available` - Get user coupons
- `GET /coupons/admin/analytics` - Coupon analytics
- `POST /coupons/bulk-action` - Bulk action

### 24. **Reports (7 endpoints)**
- `GET /reports/types` - Get report types
- `POST /reports/financial` - Financial report
- `POST /reports/academic` - Academic report
- `POST /reports/user` - User report (admin)
- `POST /reports/content` - Content report
- `GET /reports/scheduled` - Scheduled reports
- `POST /reports/schedule` - Schedule report (admin)
- `GET /reports/history` - Report history

### 25. **Settings (8 endpoints)**
- `GET /settings` - Get all settings
- `GET /settings/{key}` - Get setting
- `PUT /settings/{key}` - Update setting
- `PUT /settings` - Update bulk
- `POST /settings/reset` - Reset settings
- `GET /settings/email/config` - Email settings
- `GET /settings/payment/config` - Payment settings
- `GET /settings/features/toggles` - Feature toggles
- `GET /settings/public` - Public settings

### 26. **Audit (5 endpoints)**
- `GET /audit/logs` - Get audit logs
- `GET /audit/logs/{id}` - Get log
- `GET /audit/users/{userId}/activity` - User activity
- `GET /audit/system/events` - System events
- `GET /audit/security/events` - Security events
- `POST /audit/export` - Export logs

### 27. **Notifications (7 endpoints)**
- `GET /notifications` - List notifications
- `PUT /notifications/{id}/read` - Mark as read
- `PUT /notifications/read-all` - Mark all as read
- `DELETE /notifications/{id}` - Delete notification
- `GET /notifications/preferences` - Get preferences
- `PUT /notifications/preferences` - Update preferences
- `POST /notifications/send` - Send notification (admin)
- `POST /notifications/broadcast` - Broadcast (admin)
- `GET /notifications/analytics` - Analytics (admin)

### 28. **Search (6 endpoints)**
- `GET /search/global` - Global search
- `GET /search/courses` - Course search
- `GET /search/users` - User search
- `GET /search/content` - Content search
- `GET /search/suggestions` - Get suggestions
- `GET /search/filters` - Get filters

### 29. **Files (8 endpoints)**
- `POST /files/upload` - Upload file
- `GET /files/download/{id}` - Download file
- `DELETE /files/{id}` - Delete file
- `GET /files/list` - List files
- `GET /files/preview/{id}` - Preview file
- `POST /files/{id}/share` - Share file
- `POST /files/organize` - Organize files
- `GET /files/storage/stats` - Storage stats

### 30. **Advanced Analytics (11 endpoints)**
- `GET /analytics/advanced/predictions/student/{studentId}` - Student predictions
- `POST /analytics/advanced/predictions/student/{studentId}/calculate` - Calculate predictions
- `GET /analytics/advanced/cohorts` - List cohorts
- `POST /analytics/advanced/cohorts` - Create cohort
- `GET /analytics/advanced/cohorts/{cohortId}` - Get cohort
- `POST /analytics/advanced/cohorts/{cohortId1}/compare/{cohortId2}` - Compare cohorts
- `GET /analytics/advanced/engagement/course/{courseId}` - Course engagement
- `GET /analytics/advanced/engagement/student/{studentId}/course/{courseId}` - Student engagement
- `POST /analytics/advanced/engagement/course/{courseId}/calculate` - Calculate engagement
- `GET /analytics/advanced/at-risk/course/{courseId}` - At-risk students
- `GET /analytics/advanced/high-performing/course/{courseId}` - High-performing students
- `GET /analytics/advanced/dashboard` - Dashboard

### 31. **Localization (8 endpoints)**
- `GET /localization/preferences` - Get preferences
- `PUT /localization/preferences` - Update preferences
- `GET /localization/languages` - Get languages
- `GET /localization/currencies` - Get currencies
- `GET /localization/timezones` - Get timezones
- `POST /localization/convert-currency` - Convert currency
- `POST /localization/translate` - Translate content
- `GET /localization/translations` - Get translations

### 32. **Video Streaming (9 endpoints)**
- `POST /videos` - Create video stream
- `POST /videos/{videoStreamId}/process` - Process video (admin)
- `GET /videos/{videoStreamId}` - Get video
- `POST /videos/{videoStreamId}/view` - Record view
- `POST /videos/{videoStreamId}/watch-time` - Update watch time
- `POST /videos/{videoStreamId}/download` - Create download request
- `GET /videos/{videoStreamId}/analytics` - Video analytics
- `GET /videos/top/videos` - Get top videos
- `GET /videos/user/downloads` - User downloads

### 33. **Real-time Features (9 endpoints)**
- `POST /realtime/online` - Mark online
- `POST /realtime/offline` - Mark offline
- `GET /realtime/users/online` - Get online users
- `GET /realtime/users/online/count` - Get online count
- `GET /realtime/course/{courseId}/users/online` - Course online users
- `GET /realtime/course/{courseId}/users/online/count` - Course online count
- `POST /realtime/typing` - Send typing indicator
- `GET /realtime/activity/{userId}` - User activity status
- `GET /realtime/activity` - Current user activity

### 34. **Email Verification (2 endpoints)**
- `POST /email/verification-notification` - Send verification
- `GET /email/verify/{id}/{hash}` - Verify email

---

## 🔐 Authentication

All endpoints (except public ones) require:
```
Authorization: Bearer {token}
```

Token obtained from login endpoint.

---

## 📦 API Service Usage

```javascript
import { 
    authAPI, courseAPI, paymentAPI, userAPI, 
    quizAPI, analyticsAPI, fileAPI, videoAPI,
    // ... and 25+ more API modules
} from './services/api.js';

// Example: Login
const response = await authAPI.login(email, password);
localStorage.setItem('auth_token', response.data.token);

// Example: Get courses
const courses = await courseAPI.list();

// Example: Upload file
const formData = new FormData();
formData.append('file', file);
await fileAPI.upload(formData);
```

---

## ✅ Status

- **Total Endpoints:** 200+
- **Documented:** ✅ Complete
- **API Service:** ✅ Complete
- **Frontend Integration:** 🔄 In Progress

---

**Last Updated:** October 23, 2025  
**Version:** 1.0

