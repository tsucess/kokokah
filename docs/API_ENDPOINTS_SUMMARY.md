# 📋 **KOKOKAH.COM LMS API ENDPOINTS SUMMARY**

## 🔐 **Authentication Endpoints**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| POST | `/register` | Register new user | No | - |
| POST | `/login` | Login user | No | - |
| GET | `/user` | Get current user | Yes | - |
| POST | `/logout` | Logout user | Yes | - |
| POST | `/forgot-password` | Send password reset | No | - |
| POST | `/reset-password` | Reset password | No | - |

---

## 📚 **Course Management**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/courses` | Get all courses (public) | No | - |
| GET | `/courses/{id}` | Get single course | No | - |
| POST | `/courses` | Create course | Yes | Instructor/Admin |
| PUT | `/courses/{id}` | Update course | Yes | Instructor/Admin |
| DELETE | `/courses/{id}` | Delete course | Yes | Instructor/Admin |
| POST | `/courses/{id}/enroll` | Enroll in course | Yes | - |
| DELETE | `/courses/{id}/unenroll` | Unenroll from course | Yes | - |
| GET | `/courses/my-courses` | Get user's courses | Yes | - |
| GET | `/courses/search` | Search courses | No | - |
| GET | `/courses/featured` | Get featured courses | No | - |
| GET | `/courses/popular` | Get popular courses | No | - |
| GET | `/courses/{id}/students` | Get course students | Yes | Instructor/Admin |
| GET | `/courses/{id}/analytics` | Get course analytics | Yes | Instructor/Admin |
| POST | `/courses/{id}/publish` | Publish course | Yes | Instructor/Admin |
| POST | `/courses/{id}/unpublish` | Unpublish course | Yes | Instructor/Admin |

---

## 📖 **Lesson Management**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/courses/{courseId}/lessons` | Get course lessons | Yes | - |
| POST | `/courses/{courseId}/lessons` | Create lesson | Yes | Instructor/Admin |
| GET | `/lessons/{id}` | Get single lesson | Yes | - |
| PUT | `/lessons/{id}` | Update lesson | Yes | Instructor/Admin |
| DELETE | `/lessons/{id}` | Delete lesson | Yes | Instructor/Admin |
| POST | `/lessons/{id}/complete` | Mark lesson complete | Yes | - |
| GET | `/lessons/{id}/progress` | Get lesson progress | Yes | - |
| POST | `/lessons/{id}/watch-time` | Track watch time | Yes | - |
| GET | `/lessons/{id}/attachments` | Get lesson attachments | Yes | - |

---

## 📝 **Quiz Management**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/lessons/{lessonId}/quizzes` | Get lesson quizzes | Yes | - |
| POST | `/lessons/{lessonId}/quizzes` | Create quiz | Yes | Instructor/Admin |
| GET | `/quizzes/{id}` | Get single quiz | Yes | - |
| PUT | `/quizzes/{id}` | Update quiz | Yes | Instructor/Admin |
| DELETE | `/quizzes/{id}` | Delete quiz | Yes | Instructor/Admin |
| POST | `/quizzes/{id}/start` | Start quiz attempt | Yes | - |
| POST | `/quizzes/{id}/submit` | Submit quiz | Yes | - |
| GET | `/quizzes/{id}/results` | Get quiz results | Yes | - |
| GET | `/quizzes/{id}/analytics` | Get quiz analytics | Yes | Instructor/Admin |

---

## 📋 **Assignment Management**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/courses/{courseId}/assignments` | Get course assignments | Yes | - |
| POST | `/courses/{courseId}/assignments` | Create assignment | Yes | Instructor/Admin |
| GET | `/assignments/{id}` | Get single assignment | Yes | - |
| PUT | `/assignments/{id}` | Update assignment | Yes | Instructor/Admin |
| DELETE | `/assignments/{id}` | Delete assignment | Yes | Instructor/Admin |
| POST | `/assignments/{id}/submit` | Submit assignment | Yes | - |
| GET | `/assignments/{id}/submissions` | Get assignment submissions | Yes | Instructor/Admin |
| GET | `/assignments/{id}/grades` | Get assignment grades | Yes | Instructor/Admin |
| PUT | `/submissions/{id}/grade` | Grade submission | Yes | Instructor/Admin |

---

## 👥 **User Management**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/admin/users` | Get all users | Yes | Admin |
| GET | `/search/users` | Search users | Yes | - |
| GET | `/users/profile` | Get user profile | Yes | - |
| PUT | `/users/profile` | Update user profile | Yes | - |
| GET | `/users/dashboard` | Get user dashboard | Yes | - |
| GET | `/users/achievements` | Get user achievements | Yes | - |
| GET | `/users/learning-stats` | Get learning statistics | Yes | - |
| PUT | `/users/preferences` | Update user preferences | Yes | - |
| GET | `/users/notifications` | Get user notifications | Yes | - |
| POST | `/users/notifications/read` | Mark notifications read | Yes | - |
| POST | `/users/change-password` | Change password | Yes | - |
| POST | `/admin/users/{userId}/ban` | Ban user | Yes | Admin |
| POST | `/admin/users/{userId}/unban` | Unban user | Yes | Admin |
| GET | `/audit/users/{userId}/activity` | Get user activity | Yes | Admin |
| GET | `/users/{userId}/badges` | Get user badges | Yes | - |

---

## 📊 **Dashboard Endpoints**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/dashboard/student` | Student dashboard | Yes | Student |
| GET | `/dashboard/instructor` | Instructor dashboard | Yes | Instructor |
| GET | `/dashboard/admin` | Admin dashboard | Yes | Admin |
| GET | `/dashboard/analytics` | Dashboard analytics | Yes | - |

---

## 💰 **Payment Management**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/payments/gateways` | Get payment gateways | Yes | - |
| POST | `/payments/deposit` | Initialize wallet deposit | Yes | - |
| POST | `/payments/purchase-course` | Initialize course payment | Yes | - |
| GET | `/payments/history` | Get payment history | Yes | - |
| GET | `/payments/{id}` | Get single payment | Yes | - |
| POST | `/payments/webhook/{gateway}` | Payment webhook | No | - |
| GET | `/payments/callback/{gateway}` | Payment callback | No | - |
| GET | `/payments/success/{gateway}` | Payment success | No | - |
| GET | `/payments/cancel/{gateway}` | Payment cancel | No | - |

---

## 💳 **Wallet Management**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/wallet` | Get wallet info | Yes | - |
| POST | `/wallet/transfer` | Transfer funds | Yes | - |
| POST | `/wallet/purchase-course` | Purchase course with wallet | Yes | - |
| GET | `/wallet/transactions` | Get wallet transactions | Yes | - |
| GET | `/wallet/rewards` | Get wallet rewards | Yes | - |
| POST | `/wallet/claim-login-reward` | Claim login reward | Yes | - |
| POST | `/wallet/check-affordability` | Check if user can afford | Yes | - |

---

## 🎖️ **Certificates**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/certificates` | Get user certificates | Yes | - |
| GET | `/certificates/analytics` | Get certificate analytics | Yes | Instructor/Admin |
| GET | `/certificates/templates` | Get certificate templates | Yes | - |
| POST | `/certificates/generate` | Generate certificate | Yes | - |
| POST | `/certificates/bulk-generate` | Bulk generate certificates | Yes | Instructor/Admin |
| GET | `/certificates/{id}` | Get single certificate | Yes | - |
| GET | `/certificates/{id}/download` | Download certificate | Yes | - |
| POST | `/certificates/{id}/revoke` | Revoke certificate | Yes | Admin |
| GET | `/certificates/verify/{number}` | Verify certificate (public) | No | - |

---

## 🏆 **Badges & Achievements**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/badges` | Get all badges | Yes | - |
| GET | `/badges/analytics` | Get badge analytics | Yes | Admin |
| GET | `/badges/leaderboard` | Get badge leaderboard | Yes | - |
| POST | `/badges` | Create badge | Yes | Admin |
| POST | `/badges/award` | Award badge to user | Yes | Admin |
| POST | `/badges/check-automatic/{userId}` | Check automatic badges | Yes | Admin |
| GET | `/badges/{id}` | Get single badge | Yes | - |
| PUT | `/badges/{id}` | Update badge | Yes | Admin |
| DELETE | `/badges/{id}` | Delete badge | Yes | Admin |
| POST | `/badges/user-badges/{id}/revoke` | Revoke user badge | Yes | Admin |
| GET | `/users/{userId}/badges` | Get user badges | Yes | - |
| GET | `/my-badges` | Get my badges | Yes | - |

---

## 📈 **Progress Tracking**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/progress/courses` | Get course progress | Yes | - |
| GET | `/progress/lessons` | Get lesson progress | Yes | - |
| GET | `/progress/overall` | Get overall progress | Yes | - |
| POST | `/progress/update` | Update progress | Yes | - |
| GET | `/progress/certificates` | Get available certificates | Yes | - |
| POST | `/progress/generate-cert` | Generate certificate | Yes | - |
| GET | `/progress/achievements` | Get achievement progress | Yes | - |
| GET | `/progress/streaks` | Get streak progress | Yes | - |

---

## 📊 **Analytics**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/analytics/learning` | Learning analytics | Yes | Instructor/Admin |
| GET | `/analytics/course-performance` | Course performance | Yes | Instructor/Admin |
| GET | `/analytics/student-progress` | Student progress | Yes | Instructor/Admin |
| GET | `/analytics/revenue` | Revenue analytics | Yes | Admin |
| GET | `/analytics/engagement` | Engagement analytics | Yes | Instructor/Admin |
| POST | `/analytics/comparative` | Comparative analytics | Yes | Instructor/Admin |
| POST | `/analytics/export` | Export analytics | Yes | Instructor/Admin |
| GET | `/analytics/real-time` | Real-time analytics | Yes | Instructor/Admin |
| GET | `/analytics/predictive` | Predictive analytics | Yes | Admin |

---

## 🎯 **Recommendations**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/recommendations` | Get personalized recommendations | Yes | - |
| GET | `/recommendations/courses/{courseId}` | Course-based recommendations | Yes | - |
| GET | `/recommendations/learning-paths` | Learning path recommendations | Yes | - |
| GET | `/recommendations/instructors` | Instructor recommendations | Yes | - |
| GET | `/recommendations/content` | Content recommendations | Yes | - |
| PUT | `/recommendations/preferences` | Update recommendation preferences | Yes | - |
| GET | `/recommendations/analytics` | Recommendation analytics | Yes | Admin |

---

## 💬 **AI Chat**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| POST | `/chat/start` | Start chat session | Yes | - |
| POST | `/chat/sessions/{sessionId}/message` | Send message | Yes | - |
| GET | `/chat/sessions/{sessionId}` | Get session history | Yes | - |
| GET | `/chat/sessions` | Get user sessions | Yes | - |
| POST | `/chat/sessions/{sessionId}/end` | End session | Yes | - |
| POST | `/chat/sessions/{sessionId}/rate` | Rate session | Yes | - |
| GET | `/chat/analytics` | Chat analytics | Yes | Admin |
| POST | `/chat/suggestions` | Get suggested responses | Yes | - |

---

## 🔍 **Search**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/search/global` | Global search | Yes | - |
| GET | `/search/courses` | Course search | Yes | - |
| GET | `/search/users` | User search | Yes | - |
| GET | `/search/content` | Content search | Yes | - |
| GET | `/search/suggestions` | Search suggestions | Yes | - |
| GET | `/search/filters` | Get search filters | Yes | - |

---

## 🔔 **Notifications**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/notifications` | Get notifications | Yes | - |
| PUT | `/notifications/{id}/read` | Mark as read | Yes | - |
| PUT | `/notifications/read-all` | Mark all as read | Yes | - |
| DELETE | `/notifications/{id}` | Delete notification | Yes | - |
| GET | `/notifications/preferences` | Get notification preferences | Yes | - |
| PUT | `/notifications/preferences` | Update notification preferences | Yes | - |
| POST | `/notifications/send` | Send notification | Yes | Admin |
| POST | `/notifications/broadcast` | Broadcast notification | Yes | Admin |
| GET | `/notifications/analytics` | Notification analytics | Yes | Admin |

---

## 📁 **File Management**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| POST | `/files/upload` | Upload file | Yes | - |
| GET | `/files/download/{id}` | Download file | Yes | - |
| DELETE | `/files/{id}` | Delete file | Yes | - |
| GET | `/files/list` | List user files | Yes | - |
| GET | `/files/preview/{id}` | Preview file | Yes | - |
| POST | `/files/{id}/share` | Share file | Yes | - |
| POST | `/files/organize` | Organize files | Yes | - |
| GET | `/files/storage/stats` | Get storage statistics | Yes | - |

---

## ⚙️ **Admin Management**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/admin/dashboard` | Admin dashboard | Yes | Admin |
| GET | `/admin/users` | Get all users | Yes | Admin |
| GET | `/admin/courses` | Get all courses | Yes | Admin |
| GET | `/admin/payments` | Get all payments | Yes | Admin |
| GET | `/admin/reports` | Get admin reports | Yes | Admin |
| GET | `/admin/settings` | Get admin settings | Yes | Admin |
| POST | `/admin/users/{userId}/ban` | Ban user | Yes | Admin |
| POST | `/admin/users/{userId}/unban` | Unban user | Yes | Admin |
| GET | `/admin/analytics` | Admin analytics | Yes | Admin |
| POST | `/admin/bulk-actions` | Bulk actions | Yes | Admin |
| GET | `/admin/audit-logs` | Get audit logs | Yes | Admin |
| POST | `/admin/maintenance` | Toggle maintenance mode | Yes | Admin |
| POST | `/admin/clear-cache` | Clear system cache | Yes | Admin |
| GET | `/admin/database-stats` | Get database statistics | Yes | Admin |

---

## 📋 **Categories**

| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| GET | `/category` | Get all categories | No | - |
| POST | `/category` | Create category | Yes | Admin |
| GET | `/category/{id}` | Get single category | No | - |
| PUT | `/category/{id}` | Update category | Yes | Admin |
| DELETE | `/category/{id}` | Delete category | Yes | Admin |

---

**Total Endpoints: 200+**  
**Public Endpoints: 15**  
**Authenticated Endpoints: 185+**  
**Admin Only: 45+**  
**Instructor/Admin: 35+**
