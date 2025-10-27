# Kokokah LMS - Complete Endpoints Reference

**Last Updated:** October 26, 2025  
**Total Endpoints:** 200+

---

## 📊 Endpoint Statistics

| Category | Count | Auth Required |
|----------|-------|---------------|
| Authentication | 8 | Mixed |
| Courses | 15 | Mixed |
| Lessons | 9 | Yes |
| Quizzes | 9 | Yes |
| Assignments | 9 | Yes |
| Enrollments | 8 | Yes |
| Users | 9 | Yes |
| Wallet & Payments | 12 | Yes |
| Certificates & Badges | 15 | Mixed |
| Progress & Grading | 18 | Yes |
| Reviews & Forum | 24 | Yes |
| Learning Paths | 12 | Yes |
| Admin | 15 | Yes |
| Analytics | 9 | Yes |
| Notifications | 9 | Yes |
| Search | 6 | Yes |
| Files | 8 | Yes |
| Language | 9 | Mixed |
| Chat | 8 | Yes |
| Recommendations | 7 | Yes |
| Coupons | 10 | Yes |
| Reports | 8 | Yes |
| Settings | 9 | Mixed |
| Audit & Security | 6 | Yes |
| Video Streaming | 9 | Yes |
| Real-time Features | 9 | Yes |
| Localization | 8 | Yes |
| **TOTAL** | **220+** | - |

---

## 🔐 Authentication Endpoints

```
POST   /register                          - Register new user
POST   /login                             - Login user
GET    /user                              - Get current user
POST   /logout                            - Logout user
POST   /email/send-verification-code     - Send verification code
POST   /email/verify-with-code           - Verify email with code
POST   /forgot-password                  - Request password reset
POST   /reset-password                   - Reset password
```

---

## 📚 Courses Endpoints

```
GET    /courses                           - Get all courses (paginated)
GET    /courses/search                    - Search courses
GET    /courses/featured                  - Get featured courses
GET    /courses/popular                   - Get popular courses
GET    /courses/{id}                      - Get single course
GET    /courses/my-courses                - Get my courses
POST   /courses                           - Create course
PUT    /courses/{id}                      - Update course
DELETE /courses/{id}                      - Delete course
POST   /courses/{id}/enroll               - Enroll in course
DELETE /courses/{id}/unenroll             - Unenroll from course
GET    /courses/{id}/students             - Get course students
GET    /courses/{id}/analytics            - Get course analytics
POST   /courses/{id}/publish              - Publish course
POST   /courses/{id}/unpublish            - Unpublish course
```

---

## 📖 Lessons Endpoints

```
GET    /courses/{courseId}/lessons        - Get lessons for course
POST   /courses/{courseId}/lessons        - Create lesson
GET    /lessons/{id}                      - Get single lesson
PUT    /lessons/{id}                      - Update lesson
DELETE /lessons/{id}                      - Delete lesson
POST   /lessons/{id}/complete             - Mark lesson complete
GET    /lessons/{id}/progress             - Get lesson progress
POST   /lessons/{id}/watch-time           - Track watch time
GET    /lessons/{id}/attachments          - Get attachments
```

---

## 🎯 Quizzes Endpoints

```
GET    /lessons/{lessonId}/quizzes        - Get quizzes for lesson
POST   /lessons/{lessonId}/quizzes        - Create quiz
GET    /quizzes/{id}                      - Get single quiz
PUT    /quizzes/{id}                      - Update quiz
DELETE /quizzes/{id}                      - Delete quiz
POST   /quizzes/{id}/start                - Start quiz attempt
POST   /quizzes/{id}/submit               - Submit quiz answers
GET    /quizzes/{id}/results              - Get quiz results
GET    /quizzes/{id}/analytics            - Get quiz analytics
```

---

## 📝 Assignments Endpoints

```
GET    /courses/{courseId}/assignments    - Get assignments
POST   /courses/{courseId}/assignments    - Create assignment
GET    /assignments/{id}                  - Get single assignment
PUT    /assignments/{id}                  - Update assignment
DELETE /assignments/{id}                  - Delete assignment
POST   /assignments/{id}/submit           - Submit assignment
GET    /assignments/{id}/submissions      - Get submissions
GET    /assignments/{id}/grades           - Get grades
PUT    /submissions/{id}/grade            - Grade submission
```

---

## 👥 Enrollments Endpoints

```
GET    /enrollments                       - Get my enrollments
POST   /enrollments                       - Create enrollment
GET    /enrollments/{id}                  - Get single enrollment
PUT    /enrollments/{id}                  - Update enrollment
DELETE /enrollments/{id}                  - Delete enrollment
GET    /enrollments/{id}/progress         - Get enrollment progress
POST   /enrollments/{id}/complete         - Complete enrollment
GET    /enrollments/certificates          - Get certificates
```

---

## 👤 Users Endpoints

```
GET    /users/profile                     - Get user profile
PUT    /users/profile                     - Update profile
GET    /users/dashboard                   - Get dashboard
GET    /users/achievements                - Get achievements
GET    /users/learning-stats              - Get learning stats
PUT    /users/preferences                 - Update preferences
GET    /users/notifications               - Get notifications
POST   /users/notifications/read          - Mark as read
POST   /users/change-password             - Change password
```

---

## 💰 Wallet & Payments Endpoints

```
GET    /wallet                            - Get wallet balance
POST   /wallet/transfer                   - Transfer money
POST   /wallet/purchase-course            - Purchase with wallet
GET    /wallet/transactions               - Get transactions
GET    /wallet/rewards                    - Get rewards
POST   /wallet/claim-login-reward         - Claim reward
POST   /wallet/check-affordability        - Check affordability
GET    /payments/gateways                 - Get payment gateways
POST   /payments/deposit                  - Deposit funds
POST   /payments/purchase-course          - Purchase course
GET    /payments/history                  - Get payment history
GET    /payments/{id}                     - Get payment details
```

---

## 🎓 Certificates & Badges Endpoints

```
GET    /certificates                      - Get my certificates
GET    /certificates/templates            - Get templates
POST   /certificates/generate             - Generate certificate
POST   /certificates/bulk-generate        - Bulk generate
GET    /certificates/{id}                 - Get certificate
GET    /certificates/{id}/download        - Download certificate
POST   /certificates/{id}/revoke          - Revoke certificate
GET    /certificates/verify/{number}      - Verify certificate (public)
GET    /certificates/analytics            - Get analytics
GET    /badges                            - Get badges
GET    /badges/leaderboard                - Get leaderboard
GET    /my-badges                         - Get my badges
GET    /users/{userId}/badges             - Get user badges
POST   /badges/award                      - Award badge
POST   /badges/user-badges/{userId}/{badgeId}/revoke - Revoke badge
```

---

## 📊 Progress & Grading Endpoints

```
GET    /progress/courses                  - Get course progress
GET    /progress/lessons                  - Get lesson progress
GET    /progress/overall                  - Get overall progress
POST   /progress/update                   - Update progress
GET    /progress/certificates             - Get available certificates
POST   /progress/generate-cert            - Generate certificate
GET    /progress/achievements             - Get achievements
GET    /progress/streaks                  - Get streaks
GET    /grading/gradebook/{courseId}      - Get gradebook
GET    /grading/courses/{courseId}        - Get course grades
GET    /grading/students/{studentId}      - Get student grades
POST   /grading/bulk-grade                - Bulk grade
GET    /grading/analytics                 - Get analytics
POST   /grading/export                    - Export grades
GET    /grading/grade-history/{studentId}/{courseId} - Get history
PUT    /grading/weights/{courseId}        - Update weights
POST   /grading/comments                  - Add comments
GET    /grading/reports/{courseId}        - Get reports
```

---

## ⭐ Reviews & Forum Endpoints

```
GET    /courses/{courseId}/reviews        - Get reviews
POST   /courses/{courseId}/reviews        - Create review
GET    /courses/{courseId}/reviews/analytics - Get analytics
GET    /reviews/moderate                  - Get to moderate
GET    /reviews/my-reviews                - Get my reviews
GET    /reviews/{id}                      - Get review
PUT    /reviews/{id}                      - Update review
DELETE /reviews/{id}                      - Delete review
POST   /reviews/{id}/helpful              - Mark helpful
POST   /reviews/{id}/approve              - Approve review
POST   /reviews/{id}/reject               - Reject review
GET    /courses/{courseId}/forum          - Get forum
POST   /courses/{courseId}/forum          - Create topic
GET    /courses/{courseId}/forum/analytics - Get analytics
GET    /forum/topics/{id}                 - Get topic
PUT    /forum/topics/{id}                 - Update topic
DELETE /forum/topics/{id}                 - Delete topic
POST   /forum/topics/{id}/subscribe       - Subscribe
DELETE /forum/topics/{id}/unsubscribe     - Unsubscribe
POST   /forum/topics/{id}/posts           - Create post
PUT    /forum/posts/{id}                  - Update post
DELETE /forum/posts/{id}                  - Delete post
POST   /forum/posts/{id}/like             - Like post
POST   /forum/posts/{id}/solution         - Mark solution
```

---

## 🎓 Learning Paths Endpoints

```
GET    /learning-paths                    - Get learning paths
POST   /learning-paths                    - Create path
GET    /learning-paths/{id}               - Get path
PUT    /learning-paths/{id}               - Update path
DELETE /learning-paths/{id}               - Delete path
POST   /learning-paths/{id}/enroll        - Enroll
DELETE /learning-paths/{id}/unenroll      - Unenroll
GET    /learning-paths/my/paths           - Get my paths
GET    /learning-paths/{id}/progress      - Get progress
GET    /learning-paths/{id}/analytics     - Get analytics
POST   /learning-paths/{id}/publish       - Publish
POST   /learning-paths/{id}/unpublish     - Unpublish
```

---

## 🔧 Admin Endpoints

```
GET    /admin/dashboard                   - Get dashboard
GET    /admin/users                       - Get all users
GET    /admin/courses                     - Get all courses
GET    /admin/payments                    - Get all payments
GET    /admin/reports                     - Get reports
GET    /admin/settings                    - Get settings
GET    /admin/stats                       - Get stats
POST   /admin/users/{userId}/ban          - Ban user
POST   /admin/users/{userId}/unban        - Unban user
GET    /admin/analytics                   - Get analytics
POST   /admin/bulk-actions                - Bulk actions
GET    /admin/audit-logs                  - Get audit logs
POST   /admin/maintenance                 - Maintenance mode
POST   /admin/clear-cache                 - Clear cache
GET    /admin/database-stats              - Get DB stats
```

---

## 📈 Analytics Endpoints

```
GET    /analytics/learning                - Learning analytics
GET    /analytics/course-performance      - Course performance
GET    /analytics/student-progress        - Student progress
GET    /analytics/revenue                 - Revenue analytics
GET    /analytics/engagement              - Engagement analytics
POST   /analytics/comparative             - Comparative analytics
POST   /analytics/export                  - Export analytics
GET    /analytics/real-time               - Real-time analytics
GET    /analytics/predictive              - Predictive analytics
```

---

## 🔔 Notifications Endpoints

```
GET    /notifications                     - Get notifications
PUT    /notifications/{id}/read           - Mark as read
PUT    /notifications/read-all            - Mark all as read
DELETE /notifications/{id}                - Delete notification
GET    /notifications/preferences         - Get preferences
PUT    /notifications/preferences         - Update preferences
POST   /notifications/send                - Send notification
POST   /notifications/broadcast           - Broadcast notification
GET    /notifications/analytics           - Get analytics
```

---

## 🔍 Search Endpoints

```
GET    /search                            - Global search
GET    /search/courses                    - Search courses
GET    /search/users                      - Search users
GET    /search/content                    - Search content
GET    /search/suggestions                - Get suggestions
GET    /search/filters                    - Get filters
```

---

## 📁 Files Endpoints

```
POST   /files/upload                      - Upload file
GET    /files/download/{id}               - Download file
DELETE /files/{id}                        - Delete file
GET    /files/list                        - List files
GET    /files/preview/{id}                - Preview file
POST   /files/{id}/share                  - Share file
POST   /files/organize                    - Organize files
GET    /files/storage/stats               - Get storage stats
```

---

## 🌍 Language Endpoints

```
GET    /language/current                  - Get current locale
GET    /language/supported                - Get supported languages
POST   /language/set                      - Set locale (guest)
GET    /language/translations             - Get translations
GET    /language/translations/{locale}    - Get translations by locale
GET    /language/info/{locale}            - Get language info
GET    /language/info                     - Get all language info
POST   /language/user/set                 - Set user language
GET    /language/user                     - Get user language
```

---

## 💬 Chat Endpoints

```
POST   /chat/start                        - Start chat session
POST   /chat/sessions/{sessionId}/message - Send message
GET    /chat/sessions/{sessionId}         - Get history
GET    /chat/sessions                     - Get sessions
POST   /chat/sessions/{sessionId}/end     - End chat
POST   /chat/sessions/{sessionId}/rate    - Rate chat
GET    /chat/analytics                    - Get analytics
POST   /chat/suggestions                  - Get suggestions
```

---

## 🎯 Recommendations Endpoints

```
GET    /recommendations                   - Get recommendations
GET    /recommendations/courses/{courseId} - Course-based
GET    /recommendations/learning-paths    - Learning paths
GET    /recommendations/instructors       - Instructors
GET    /recommendations/content           - Content
PUT    /recommendations/preferences       - Update preferences
GET    /recommendations/analytics         - Get analytics
```

---

## 🎁 Coupons Endpoints

```
GET    /coupons                           - Get coupons
POST   /coupons                           - Create coupon
GET    /coupons/{id}                      - Get coupon
PUT    /coupons/{id}                      - Update coupon
DELETE /coupons/{id}                      - Delete coupon
POST   /coupons/validate                  - Validate coupon
POST   /coupons/apply                     - Apply coupon
GET    /coupons/user/available            - Get user coupons
GET    /coupons/admin/analytics           - Get analytics
POST   /coupons/bulk-action               - Bulk actions
```

---

## 📋 Reports Endpoints

```
GET    /reports/types                     - Get report types
POST   /reports/financial                 - Financial report
POST   /reports/academic                  - Academic report
POST   /reports/user                      - User report
POST   /reports/content                   - Content report
GET    /reports/scheduled                 - Get scheduled
POST   /reports/schedule                  - Schedule report
GET    /reports/history                   - Get history
```

---

## ⚙️ Settings Endpoints

```
GET    /settings                          - Get settings
GET    /settings/{key}                    - Get setting
PUT    /settings/{key}                    - Update setting
PUT    /settings                          - Bulk update
POST   /settings/reset                    - Reset settings
GET    /settings/email/config             - Email settings
GET    /settings/payment/config           - Payment settings
GET    /settings/features/toggles         - Feature toggles
GET    /settings/public                   - Public settings
```

---

## 🔐 Audit & Security Endpoints

```
GET    /audit/logs                        - Get audit logs
GET    /audit/logs/{id}                   - Get log details
GET    /audit/users/{userId}/activity     - User activity
GET    /audit/system/events               - System events
GET    /audit/security/events             - Security events
POST   /audit/export                      - Export logs
```

---

## 📺 Video Streaming Endpoints

```
POST   /videos                            - Create video
POST   /videos/{videoStreamId}/process    - Process video
GET    /videos/{videoStreamId}            - Get video
POST   /videos/{videoStreamId}/view       - Record view
POST   /videos/{videoStreamId}/watch-time - Update watch time
POST   /videos/{videoStreamId}/download   - Download request
GET    /videos/{videoStreamId}/analytics  - Get analytics
GET    /videos/top/videos                 - Get top videos
GET    /videos/user/downloads             - Get downloads
```

---

## 🔄 Real-time Features Endpoints

```
POST   /realtime/online                   - Mark online
POST   /realtime/offline                  - Mark offline
GET    /realtime/users/online             - Get online users
GET    /realtime/users/online/count       - Get online count
GET    /realtime/course/{courseId}/users/online - Course online users
GET    /realtime/course/{courseId}/users/online/count - Course count
POST   /realtime/typing                   - Typing indicator
GET    /realtime/activity/{userId}        - User activity
GET    /realtime/activity                 - Current activity
```

---

## 📚 Localization Endpoints

```
GET    /localization/preferences          - Get preferences
PUT    /localization/preferences          - Update preferences
GET    /localization/languages            - Get languages
GET    /localization/currencies           - Get currencies
GET    /localization/timezones            - Get timezones
POST   /localization/convert-currency     - Convert currency
POST   /localization/translate            - Translate content
GET    /localization/translations         - Get translations
```

---

## 📊 Summary

- **Total Endpoints:** 220+
- **Authenticated:** 180+
- **Public:** 40+
- **HTTP Methods:** GET, POST, PUT, DELETE
- **Response Format:** JSON
- **Authentication:** Bearer Token (Sanctum)
- **Languages:** 6 (EN, FR, AR, HA, YO, IG)

---

*Last Updated: October 26, 2025*  
*Status: ✅ Production Ready*

