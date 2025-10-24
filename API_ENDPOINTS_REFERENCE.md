# Kokokah.com LMS - Complete API Endpoints Reference

**Base URL:** `http://localhost:8000/api`  
**Authentication:** Bearer Token (Sanctum)

---

## 🔐 Authentication Endpoints

### Public Endpoints (No Auth Required)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/register` | Register new user |
| POST | `/login` | User login |
| POST | `/forgot-password` | Request password reset |
| POST | `/reset-password` | Reset password with token |
| GET | `/courses` | List all courses |
| GET | `/courses/search` | Search courses |
| GET | `/courses/featured` | Get featured courses |
| GET | `/courses/popular` | Get popular courses |
| GET | `/courses/{id}` | Get course details |
| GET | `/certificates/verify/{certificateNumber}` | Verify certificate |
| GET | `/settings/public` | Get public settings |

### Authenticated Endpoints (Auth Required)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/user` | Get authenticated user |
| POST | `/logout` | User logout |

---

## 📚 Course Management (15+ endpoints)

| Method | Endpoint | Auth | Role |
|--------|----------|------|------|
| GET | `/courses/my-courses` | ✅ | Student |
| POST | `/courses` | ✅ | Instructor, Admin |
| PUT | `/courses/{id}` | ✅ | Instructor, Admin |
| DELETE | `/courses/{id}` | ✅ | Instructor, Admin |
| GET | `/courses/{id}/students` | ✅ | Instructor, Admin |
| GET | `/courses/{id}/analytics` | ✅ | Instructor, Admin |
| POST | `/courses/{id}/publish` | ✅ | Instructor, Admin |
| POST | `/courses/{id}/unpublish` | ✅ | Instructor, Admin |
| POST | `/courses/{id}/enroll` | ✅ | Student |
| DELETE | `/courses/{id}/unenroll` | ✅ | Student |

---

## 📖 Lesson Management (8 endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/courses/{courseId}/lessons` | List course lessons |
| POST | `/courses/{courseId}/lessons` | Create lesson |
| GET | `/lessons/{id}` | Get lesson details |
| PUT | `/lessons/{id}` | Update lesson |
| DELETE | `/lessons/{id}` | Delete lesson |
| POST | `/lessons/{id}/complete` | Mark lesson complete |
| GET | `/lessons/{id}/progress` | Get lesson progress |
| POST | `/lessons/{id}/watch-time` | Track watch time |

---

## 📝 Quiz Management (7 endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/lessons/{lessonId}/quizzes` | List lesson quizzes |
| POST | `/lessons/{lessonId}/quizzes` | Create quiz |
| GET | `/quizzes/{id}` | Get quiz details |
| PUT | `/quizzes/{id}` | Update quiz |
| DELETE | `/quizzes/{id}` | Delete quiz |
| POST | `/quizzes/{id}/start` | Start quiz attempt |
| POST | `/quizzes/{id}/submit` | Submit quiz answers |
| GET | `/quizzes/{id}/results` | Get quiz results |
| GET | `/quizzes/{id}/analytics` | Get quiz analytics |

---

## ✏️ Assignment Management (6 endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/courses/{courseId}/assignments` | List assignments |
| POST | `/courses/{courseId}/assignments` | Create assignment |
| GET | `/assignments/{id}` | Get assignment details |
| PUT | `/assignments/{id}` | Update assignment |
| DELETE | `/assignments/{id}` | Delete assignment |
| POST | `/assignments/{id}/submit` | Submit assignment |
| GET | `/assignments/{id}/submissions` | Get submissions |
| PUT | `/submissions/{id}/grade` | Grade submission |

---

## 💳 Payment & Wallet (15+ endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/wallet` | Get wallet balance |
| POST | `/wallet/transfer` | Transfer funds |
| POST | `/wallet/purchase-course` | Purchase course with wallet |
| GET | `/wallet/transactions` | Get transaction history |
| GET | `/wallet/rewards` | Get available rewards |
| POST | `/wallet/claim-login-reward` | Claim daily reward |
| POST | `/wallet/check-affordability` | Check if can afford course |
| GET | `/payments/gateways` | Get available gateways |
| POST | `/payments/deposit` | Initialize wallet deposit |
| POST | `/payments/purchase-course` | Initialize course payment |
| GET | `/payments/history` | Get payment history |
| GET | `/payments/{id}` | Get payment details |

---

## 🎓 Enrollment & Progress (8 endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/enrollments` | List user enrollments |
| POST | `/enrollments` | Create enrollment |
| GET | `/enrollments/{id}` | Get enrollment details |
| PUT | `/enrollments/{id}` | Update enrollment |
| DELETE | `/enrollments/{id}` | Delete enrollment |
| GET | `/enrollments/{id}/progress` | Get enrollment progress |
| POST | `/enrollments/{id}/complete` | Mark enrollment complete |
| GET | `/enrollments/certificates` | Get certificates |

---

## 👤 User Management (8 endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/users/profile` | Get user profile |
| PUT | `/users/profile` | Update profile |
| GET | `/users/dashboard` | Get user dashboard |
| GET | `/users/achievements` | Get achievements |
| GET | `/users/learning-stats` | Get learning statistics |
| PUT | `/users/preferences` | Update preferences |
| GET | `/users/notifications` | Get notifications |
| POST | `/users/change-password` | Change password |

---

## 📊 Dashboard & Analytics (12+ endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/student` | Student dashboard |
| GET | `/dashboard/instructor` | Instructor dashboard |
| GET | `/dashboard/admin` | Admin dashboard |
| GET | `/dashboard/analytics` | Dashboard analytics |
| GET | `/analytics/learning` | Learning analytics |
| GET | `/analytics/course-performance` | Course performance |
| GET | `/analytics/student-progress` | Student progress |
| GET | `/analytics/revenue` | Revenue analytics |
| GET | `/analytics/engagement` | Engagement analytics |
| POST | `/analytics/comparative` | Comparative analytics |
| POST | `/analytics/export` | Export analytics |
| GET | `/analytics/real-time` | Real-time analytics |

---

## 🏆 Badges & Certificates (8 endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/badges` | List badges |
| POST | `/badges` | Create badge |
| GET | `/badges/{id}` | Get badge details |
| PUT | `/badges/{id}` | Update badge |
| DELETE | `/badges/{id}` | Delete badge |
| POST | `/badges/award` | Award badge to user |
| GET | `/users/{userId}/badges` | Get user badges |
| GET | `/certificates` | List certificates |
| POST | `/certificates/generate` | Generate certificate |
| GET | `/certificates/{id}/download` | Download certificate |

---

## 💬 Forum & Chat (15+ endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/courses/{courseId}/forum` | List forum topics |
| POST | `/courses/{courseId}/forum` | Create topic |
| GET | `/forum/topics/{id}` | Get topic details |
| PUT | `/forum/topics/{id}` | Update topic |
| DELETE | `/forum/topics/{id}` | Delete topic |
| POST | `/forum/topics/{id}/posts` | Create post |
| PUT | `/forum/posts/{id}` | Update post |
| DELETE | `/forum/posts/{id}` | Delete post |
| POST | `/forum/posts/{id}/like` | Like post |
| POST | `/chat/start` | Start chat session |
| POST | `/chat/sessions/{sessionId}/message` | Send message |
| GET | `/chat/sessions/{sessionId}` | Get chat history |
| GET | `/chat/sessions` | Get user sessions |
| POST | `/chat/sessions/{sessionId}/end` | End session |

---

## 🔍 Search & Recommendations (10+ endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/search/global` | Global search |
| GET | `/search/courses` | Search courses |
| GET | `/search/users` | Search users |
| GET | `/search/content` | Search content |
| GET | `/search/suggestions` | Get suggestions |
| GET | `/recommendations` | Get recommendations |
| GET | `/recommendations/courses/{courseId}` | Course recommendations |
| GET | `/recommendations/learning-paths` | Path recommendations |
| GET | `/recommendations/instructors` | Instructor recommendations |

---

## 🎬 Video Streaming (9 endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/videos` | Create video stream |
| POST | `/videos/{videoStreamId}/process` | Process video |
| GET | `/videos/{videoStreamId}` | Get video details |
| POST | `/videos/{videoStreamId}/view` | Record view |
| POST | `/videos/{videoStreamId}/watch-time` | Update watch time |
| POST | `/videos/{videoStreamId}/download` | Request download |
| GET | `/videos/{videoStreamId}/analytics` | Get video analytics |
| GET | `/videos/top/videos` | Get top videos |
| GET | `/videos/user/downloads` | Get user downloads |

---

## ⚡ Real-time Features (9 endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/realtime/online` | Mark user online |
| POST | `/realtime/offline` | Mark user offline |
| GET | `/realtime/users/online` | Get online users |
| GET | `/realtime/users/online/count` | Get online count |
| GET | `/realtime/course/{courseId}/users/online` | Course online users |
| GET | `/realtime/course/{courseId}/users/online/count` | Course online count |
| POST | `/realtime/typing` | Send typing indicator |
| GET | `/realtime/activity/{userId}` | Get user activity |
| GET | `/realtime/activity` | Get current user activity |

---

## 🌍 Localization (7 endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/localization/preferences` | Get preferences |
| PUT | `/localization/preferences` | Update preferences |
| GET | `/localization/languages` | Get languages |
| GET | `/localization/currencies` | Get currencies |
| GET | `/localization/timezones` | Get timezones |
| POST | `/localization/convert-currency` | Convert currency |
| POST | `/localization/translate` | Translate content |

---

## 👨‍💼 Admin Management (15+ endpoints)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/admin/dashboard` | Admin dashboard |
| GET | `/admin/users` | List users |
| GET | `/admin/courses` | List courses |
| GET | `/admin/payments` | List payments |
| GET | `/admin/reports` | Get reports |
| GET | `/admin/settings` | Get settings |
| GET | `/admin/stats` | Get database stats |
| POST | `/admin/users/{userId}/ban` | Ban user |
| POST | `/admin/users/{userId}/unban` | Unban user |
| GET | `/admin/analytics` | Admin analytics |
| POST | `/admin/bulk-actions` | Bulk actions |
| GET | `/admin/audit-logs` | Get audit logs |

---

## 📋 Summary

**Total Endpoints:** 100+

**By Category:**
- Authentication: 4
- Courses: 15+
- Lessons: 8
- Quizzes: 9
- Assignments: 8
- Payments: 15+
- Enrollments: 8
- Users: 8
- Analytics: 12+
- Badges/Certificates: 8
- Forum/Chat: 15+
- Search/Recommendations: 10+
- Video: 9
- Real-time: 9
- Localization: 7
- Admin: 15+

**Authentication:** All endpoints except public ones require Bearer token in Authorization header

**Response Format:** JSON with `success`, `message`, `data`, and `meta` fields

---

**Last Updated:** October 23, 2025

