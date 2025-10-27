# Kokokah LMS - API Quick Reference Guide

**Last Updated:** October 26, 2025

---

## 🚀 Quick Start

### Base URL
```
http://localhost:8000/api
```

### Authentication
```
Authorization: Bearer {token}
```

---

## 📋 All Endpoints by Category

### 🔐 Authentication (8 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/register` | ❌ | Register new user |
| POST | `/login` | ❌ | Login user |
| GET | `/user` | ✅ | Get current user |
| POST | `/logout` | ✅ | Logout user |
| POST | `/email/send-verification-code` | ❌ | Send verification code |
| POST | `/email/verify-with-code` | ❌ | Verify email with code |
| POST | `/forgot-password` | ❌ | Request password reset |
| POST | `/reset-password` | ❌ | Reset password |

### 📚 Courses (15 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/courses` | ❌ | Get all courses |
| GET | `/courses/search` | ❌ | Search courses |
| GET | `/courses/featured` | ❌ | Get featured courses |
| GET | `/courses/popular` | ❌ | Get popular courses |
| GET | `/courses/{id}` | ❌ | Get single course |
| GET | `/courses/my-courses` | ✅ | Get my courses |
| POST | `/courses` | ✅ | Create course |
| PUT | `/courses/{id}` | ✅ | Update course |
| DELETE | `/courses/{id}` | ✅ | Delete course |
| POST | `/courses/{id}/enroll` | ✅ | Enroll in course |
| DELETE | `/courses/{id}/unenroll` | ✅ | Unenroll from course |
| GET | `/courses/{id}/students` | ✅ | Get course students |
| GET | `/courses/{id}/analytics` | ✅ | Get course analytics |
| POST | `/courses/{id}/publish` | ✅ | Publish course |
| POST | `/courses/{id}/unpublish` | ✅ | Unpublish course |

### 📖 Lessons (9 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/courses/{courseId}/lessons` | ✅ | Get lessons |
| POST | `/courses/{courseId}/lessons` | ✅ | Create lesson |
| GET | `/lessons/{id}` | ✅ | Get single lesson |
| PUT | `/lessons/{id}` | ✅ | Update lesson |
| DELETE | `/lessons/{id}` | ✅ | Delete lesson |
| POST | `/lessons/{id}/complete` | ✅ | Mark complete |
| GET | `/lessons/{id}/progress` | ✅ | Get progress |
| POST | `/lessons/{id}/watch-time` | ✅ | Track watch time |
| GET | `/lessons/{id}/attachments` | ✅ | Get attachments |

### 🎯 Quizzes (9 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/lessons/{lessonId}/quizzes` | ✅ | Get quizzes |
| POST | `/lessons/{lessonId}/quizzes` | ✅ | Create quiz |
| GET | `/quizzes/{id}` | ✅ | Get single quiz |
| PUT | `/quizzes/{id}` | ✅ | Update quiz |
| DELETE | `/quizzes/{id}` | ✅ | Delete quiz |
| POST | `/quizzes/{id}/start` | ✅ | Start quiz |
| POST | `/quizzes/{id}/submit` | ✅ | Submit quiz |
| GET | `/quizzes/{id}/results` | ✅ | Get results |
| GET | `/quizzes/{id}/analytics` | ✅ | Get analytics |

### 📝 Assignments (9 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/courses/{courseId}/assignments` | ✅ | Get assignments |
| POST | `/courses/{courseId}/assignments` | ✅ | Create assignment |
| GET | `/assignments/{id}` | ✅ | Get single assignment |
| PUT | `/assignments/{id}` | ✅ | Update assignment |
| DELETE | `/assignments/{id}` | ✅ | Delete assignment |
| POST | `/assignments/{id}/submit` | ✅ | Submit assignment |
| GET | `/assignments/{id}/submissions` | ✅ | Get submissions |
| GET | `/assignments/{id}/grades` | ✅ | Get grades |
| PUT | `/submissions/{id}/grade` | ✅ | Grade submission |

### 👥 Enrollments (8 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/enrollments` | ✅ | Get my enrollments |
| POST | `/enrollments` | ✅ | Create enrollment |
| GET | `/enrollments/{id}` | ✅ | Get single enrollment |
| PUT | `/enrollments/{id}` | ✅ | Update enrollment |
| DELETE | `/enrollments/{id}` | ✅ | Delete enrollment |
| GET | `/enrollments/{id}/progress` | ✅ | Get progress |
| POST | `/enrollments/{id}/complete` | ✅ | Complete enrollment |
| GET | `/enrollments/certificates` | ✅ | Get certificates |

### 👤 Users (9 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/users/profile` | ✅ | Get profile |
| PUT | `/users/profile` | ✅ | Update profile |
| GET | `/users/dashboard` | ✅ | Get dashboard |
| GET | `/users/achievements` | ✅ | Get achievements |
| GET | `/users/learning-stats` | ✅ | Get learning stats |
| PUT | `/users/preferences` | ✅ | Update preferences |
| GET | `/users/notifications` | ✅ | Get notifications |
| POST | `/users/notifications/read` | ✅ | Mark as read |
| POST | `/users/change-password` | ✅ | Change password |

### 💰 Wallet & Payments (12 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/wallet` | ✅ | Get wallet balance |
| POST | `/wallet/transfer` | ✅ | Transfer money |
| POST | `/wallet/purchase-course` | ✅ | Purchase with wallet |
| GET | `/wallet/transactions` | ✅ | Get transactions |
| GET | `/wallet/rewards` | ✅ | Get rewards |
| POST | `/wallet/claim-login-reward` | ✅ | Claim reward |
| POST | `/wallet/check-affordability` | ✅ | Check affordability |
| GET | `/payments/gateways` | ✅ | Get gateways |
| POST | `/payments/deposit` | ✅ | Deposit funds |
| POST | `/payments/purchase-course` | ✅ | Purchase course |
| GET | `/payments/history` | ✅ | Get history |
| GET | `/payments/{id}` | ✅ | Get payment details |

### 🎓 Certificates & Badges (15 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/certificates` | ✅ | Get my certificates |
| GET | `/certificates/templates` | ✅ | Get templates |
| POST | `/certificates/generate` | ✅ | Generate certificate |
| POST | `/certificates/bulk-generate` | ✅ | Bulk generate |
| GET | `/certificates/{id}` | ✅ | Get certificate |
| GET | `/certificates/{id}/download` | ✅ | Download certificate |
| POST | `/certificates/{id}/revoke` | ✅ | Revoke certificate |
| GET | `/certificates/verify/{number}` | ❌ | Verify certificate |
| GET | `/certificates/analytics` | ✅ | Get analytics |
| GET | `/badges` | ✅ | Get badges |
| GET | `/badges/leaderboard` | ✅ | Get leaderboard |
| GET | `/my-badges` | ✅ | Get my badges |
| GET | `/users/{userId}/badges` | ✅ | Get user badges |
| POST | `/badges/award` | ✅ | Award badge |
| POST | `/badges/user-badges/{userId}/{badgeId}/revoke` | ✅ | Revoke badge |

### 📊 Progress & Grading (18 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/progress/courses` | ✅ | Get course progress |
| GET | `/progress/lessons` | ✅ | Get lesson progress |
| GET | `/progress/overall` | ✅ | Get overall progress |
| POST | `/progress/update` | ✅ | Update progress |
| GET | `/progress/certificates` | ✅ | Get available certs |
| POST | `/progress/generate-cert` | ✅ | Generate cert |
| GET | `/progress/achievements` | ✅ | Get achievements |
| GET | `/progress/streaks` | ✅ | Get streaks |
| GET | `/grading/gradebook/{courseId}` | ✅ | Get gradebook |
| GET | `/grading/courses/{courseId}` | ✅ | Get course grades |
| GET | `/grading/students/{studentId}` | ✅ | Get student grades |
| POST | `/grading/bulk-grade` | ✅ | Bulk grade |
| GET | `/grading/analytics` | ✅ | Get analytics |
| POST | `/grading/export` | ✅ | Export grades |
| GET | `/grading/grade-history/{studentId}/{courseId}` | ✅ | Get history |
| PUT | `/grading/weights/{courseId}` | ✅ | Update weights |
| POST | `/grading/comments` | ✅ | Add comments |
| GET | `/grading/reports/{courseId}` | ✅ | Get reports |

### ⭐ Reviews & Forum (24 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/courses/{courseId}/reviews` | ✅ | Get reviews |
| POST | `/courses/{courseId}/reviews` | ✅ | Create review |
| GET | `/courses/{courseId}/reviews/analytics` | ✅ | Get analytics |
| GET | `/reviews/moderate` | ✅ | Get to moderate |
| GET | `/reviews/my-reviews` | ✅ | Get my reviews |
| GET | `/reviews/{id}` | ✅ | Get review |
| PUT | `/reviews/{id}` | ✅ | Update review |
| DELETE | `/reviews/{id}` | ✅ | Delete review |
| POST | `/reviews/{id}/helpful` | ✅ | Mark helpful |
| POST | `/reviews/{id}/approve` | ✅ | Approve review |
| POST | `/reviews/{id}/reject` | ✅ | Reject review |
| GET | `/courses/{courseId}/forum` | ✅ | Get forum |
| POST | `/courses/{courseId}/forum` | ✅ | Create topic |
| GET | `/courses/{courseId}/forum/analytics` | ✅ | Get analytics |
| GET | `/forum/topics/{id}` | ✅ | Get topic |
| PUT | `/forum/topics/{id}` | ✅ | Update topic |
| DELETE | `/forum/topics/{id}` | ✅ | Delete topic |
| POST | `/forum/topics/{id}/subscribe` | ✅ | Subscribe |
| DELETE | `/forum/topics/{id}/unsubscribe` | ✅ | Unsubscribe |
| POST | `/forum/topics/{id}/posts` | ✅ | Create post |
| PUT | `/forum/posts/{id}` | ✅ | Update post |
| DELETE | `/forum/posts/{id}` | ✅ | Delete post |
| POST | `/forum/posts/{id}/like` | ✅ | Like post |
| POST | `/forum/posts/{id}/solution` | ✅ | Mark solution |

### 🎓 Learning Paths (12 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/learning-paths` | ✅ | Get paths |
| POST | `/learning-paths` | ✅ | Create path |
| GET | `/learning-paths/{id}` | ✅ | Get path |
| PUT | `/learning-paths/{id}` | ✅ | Update path |
| DELETE | `/learning-paths/{id}` | ✅ | Delete path |
| POST | `/learning-paths/{id}/enroll` | ✅ | Enroll |
| DELETE | `/learning-paths/{id}/unenroll` | ✅ | Unenroll |
| GET | `/learning-paths/my/paths` | ✅ | Get my paths |
| GET | `/learning-paths/{id}/progress` | ✅ | Get progress |
| GET | `/learning-paths/{id}/analytics` | ✅ | Get analytics |
| POST | `/learning-paths/{id}/publish` | ✅ | Publish |
| POST | `/learning-paths/{id}/unpublish` | ✅ | Unpublish |

### 🔧 Admin (15 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/admin/dashboard` | ✅ | Get dashboard |
| GET | `/admin/users` | ✅ | Get all users |
| GET | `/admin/courses` | ✅ | Get all courses |
| GET | `/admin/payments` | ✅ | Get all payments |
| GET | `/admin/reports` | ✅ | Get reports |
| GET | `/admin/settings` | ✅ | Get settings |
| GET | `/admin/stats` | ✅ | Get stats |
| POST | `/admin/users/{userId}/ban` | ✅ | Ban user |
| POST | `/admin/users/{userId}/unban` | ✅ | Unban user |
| GET | `/admin/analytics` | ✅ | Get analytics |
| POST | `/admin/bulk-actions` | ✅ | Bulk actions |
| GET | `/admin/audit-logs` | ✅ | Get audit logs |
| POST | `/admin/maintenance` | ✅ | Maintenance mode |
| POST | `/admin/clear-cache` | ✅ | Clear cache |
| GET | `/admin/database-stats` | ✅ | Get DB stats |

### 📈 Analytics (9 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/analytics/learning` | ✅ | Learning analytics |
| GET | `/analytics/course-performance` | ✅ | Course performance |
| GET | `/analytics/student-progress` | ✅ | Student progress |
| GET | `/analytics/revenue` | ✅ | Revenue analytics |
| GET | `/analytics/engagement` | ✅ | Engagement analytics |
| POST | `/analytics/comparative` | ✅ | Comparative analytics |
| POST | `/analytics/export` | ✅ | Export analytics |
| GET | `/analytics/real-time` | ✅ | Real-time analytics |
| GET | `/analytics/predictive` | ✅ | Predictive analytics |

### 🔔 Notifications (9 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/notifications` | ✅ | Get notifications |
| PUT | `/notifications/{id}/read` | ✅ | Mark as read |
| PUT | `/notifications/read-all` | ✅ | Mark all as read |
| DELETE | `/notifications/{id}` | ✅ | Delete notification |
| GET | `/notifications/preferences` | ✅ | Get preferences |
| PUT | `/notifications/preferences` | ✅ | Update preferences |
| POST | `/notifications/send` | ✅ | Send notification |
| POST | `/notifications/broadcast` | ✅ | Broadcast notification |
| GET | `/notifications/analytics` | ✅ | Get analytics |

### 🔍 Search (6 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/search` | ✅ | Global search |
| GET | `/search/courses` | ✅ | Search courses |
| GET | `/search/users` | ✅ | Search users |
| GET | `/search/content` | ✅ | Search content |
| GET | `/search/suggestions` | ✅ | Get suggestions |
| GET | `/search/filters` | ✅ | Get filters |

### 📁 Files (8 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/files/upload` | ✅ | Upload file |
| GET | `/files/download/{id}` | ✅ | Download file |
| DELETE | `/files/{id}` | ✅ | Delete file |
| GET | `/files/list` | ✅ | List files |
| GET | `/files/preview/{id}` | ✅ | Preview file |
| POST | `/files/{id}/share` | ✅ | Share file |
| POST | `/files/organize` | ✅ | Organize files |
| GET | `/files/storage/stats` | ✅ | Get storage stats |

### 🌍 Language (9 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/language/current` | ❌ | Get current locale |
| GET | `/language/supported` | ❌ | Get supported languages |
| POST | `/language/set` | ❌ | Set locale (guest) |
| GET | `/language/translations` | ❌ | Get translations |
| GET | `/language/translations/{locale}` | ❌ | Get translations by locale |
| GET | `/language/info/{locale}` | ❌ | Get language info |
| GET | `/language/info` | ❌ | Get all language info |
| POST | `/language/user/set` | ✅ | Set user language |
| GET | `/language/user` | ✅ | Get user language |

### 💬 Chat (8 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/chat/start` | ✅ | Start chat |
| POST | `/chat/sessions/{sessionId}/message` | ✅ | Send message |
| GET | `/chat/sessions/{sessionId}` | ✅ | Get history |
| GET | `/chat/sessions` | ✅ | Get sessions |
| POST | `/chat/sessions/{sessionId}/end` | ✅ | End chat |
| POST | `/chat/sessions/{sessionId}/rate` | ✅ | Rate chat |
| GET | `/chat/analytics` | ✅ | Get analytics |
| POST | `/chat/suggestions` | ✅ | Get suggestions |

### 🎯 Recommendations (7 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/recommendations` | ✅ | Get recommendations |
| GET | `/recommendations/courses/{courseId}` | ✅ | Course-based |
| GET | `/recommendations/learning-paths` | ✅ | Learning paths |
| GET | `/recommendations/instructors` | ✅ | Instructors |
| GET | `/recommendations/content` | ✅ | Content |
| PUT | `/recommendations/preferences` | ✅ | Update preferences |
| GET | `/recommendations/analytics` | ✅ | Get analytics |

### 🎁 Coupons (10 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/coupons` | ✅ | Get coupons |
| POST | `/coupons` | ✅ | Create coupon |
| GET | `/coupons/{id}` | ✅ | Get coupon |
| PUT | `/coupons/{id}` | ✅ | Update coupon |
| DELETE | `/coupons/{id}` | ✅ | Delete coupon |
| POST | `/coupons/validate` | ✅ | Validate coupon |
| POST | `/coupons/apply` | ✅ | Apply coupon |
| GET | `/coupons/user/available` | ✅ | Get user coupons |
| GET | `/coupons/admin/analytics` | ✅ | Get analytics |
| POST | `/coupons/bulk-action` | ✅ | Bulk actions |

### 📋 Reports (8 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/reports/types` | ✅ | Get report types |
| POST | `/reports/financial` | ✅ | Financial report |
| POST | `/reports/academic` | ✅ | Academic report |
| POST | `/reports/user` | ✅ | User report |
| POST | `/reports/content` | ✅ | Content report |
| GET | `/reports/scheduled` | ✅ | Get scheduled |
| POST | `/reports/schedule` | ✅ | Schedule report |
| GET | `/reports/history` | ✅ | Get history |

### ⚙️ Settings (9 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/settings` | ✅ | Get settings |
| GET | `/settings/{key}` | ✅ | Get setting |
| PUT | `/settings/{key}` | ✅ | Update setting |
| PUT | `/settings` | ✅ | Bulk update |
| POST | `/settings/reset` | ✅ | Reset settings |
| GET | `/settings/email/config` | ✅ | Email settings |
| GET | `/settings/payment/config` | ✅ | Payment settings |
| GET | `/settings/features/toggles` | ✅ | Feature toggles |
| GET | `/settings/public` | ❌ | Public settings |

### 🔐 Audit & Security (6 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/audit/logs` | ✅ | Get audit logs |
| GET | `/audit/logs/{id}` | ✅ | Get log details |
| GET | `/audit/users/{userId}/activity` | ✅ | User activity |
| GET | `/audit/system/events` | ✅ | System events |
| GET | `/audit/security/events` | ✅ | Security events |
| POST | `/audit/export` | ✅ | Export logs |

### 📺 Video Streaming (9 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/videos` | ✅ | Create video |
| POST | `/videos/{videoStreamId}/process` | ✅ | Process video |
| GET | `/videos/{videoStreamId}` | ✅ | Get video |
| POST | `/videos/{videoStreamId}/view` | ✅ | Record view |
| POST | `/videos/{videoStreamId}/watch-time` | ✅ | Update watch time |
| POST | `/videos/{videoStreamId}/download` | ✅ | Download request |
| GET | `/videos/{videoStreamId}/analytics` | ✅ | Get analytics |
| GET | `/videos/top/videos` | ✅ | Get top videos |
| GET | `/videos/user/downloads` | ✅ | Get downloads |

### 🔄 Real-time Features (9 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/realtime/online` | ✅ | Mark online |
| POST | `/realtime/offline` | ✅ | Mark offline |
| GET | `/realtime/users/online` | ✅ | Get online users |
| GET | `/realtime/users/online/count` | ✅ | Get online count |
| GET | `/realtime/course/{courseId}/users/online` | ✅ | Course online users |
| GET | `/realtime/course/{courseId}/users/online/count` | ✅ | Course online count |
| POST | `/realtime/typing` | ✅ | Typing indicator |
| GET | `/realtime/activity/{userId}` | ✅ | User activity |
| GET | `/realtime/activity` | ✅ | Current activity |

### 📚 Localization (8 endpoints)
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/localization/preferences` | ✅ | Get preferences |
| PUT | `/localization/preferences` | ✅ | Update preferences |
| GET | `/localization/languages` | ✅ | Get languages |
| GET | `/localization/currencies` | ✅ | Get currencies |
| GET | `/localization/timezones` | ✅ | Get timezones |
| POST | `/localization/convert-currency` | ✅ | Convert currency |
| POST | `/localization/translate` | ✅ | Translate content |
| GET | `/localization/translations` | ✅ | Get translations |

---

## 📊 Summary

- **Total Endpoints:** 200+
- **Authenticated Endpoints:** 180+
- **Public Endpoints:** 20+
- **Supported Languages:** 6 (English, French, Arabic, Hausa, Yoruba, Igbo)
- **Status Codes:** 200, 201, 204, 400, 401, 403, 404, 422, 429, 500

---

*Last Updated: October 26, 2025*

