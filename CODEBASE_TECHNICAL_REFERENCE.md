# 🔧 Kokokah.com LMS - Technical Reference Guide

**Last Updated:** December 9, 2025

---

## 📋 QUICK NAVIGATION

- [Controllers](#controllers)
- [Models](#models)
- [Services](#services)
- [API Routes](#api-routes)
- [Database Schema](#database-schema)
- [Frontend Components](#frontend-components)
- [Common Patterns](#common-patterns)

---

## 🎮 CONTROLLERS (40+)

### Authentication & User Management
- **AuthController** - Register, login, logout, password reset, email verification
- **UserController** - Profile, dashboard, achievements, learning stats, preferences
- **PasswordResetController** - Password reset flow

### Course Management
- **CourseController** - CRUD, publish/unpublish, enrollment, analytics
- **LessonController** - Lesson management, completion tracking, watch time
- **TopicController** - Topic management within courses
- **TermController** - Academic term management
- **LevelController** - Student level/grade management

### Learning & Assessment
- **QuizController** - Quiz management, attempts, results, analytics
- **AssignmentController** - Assignment CRUD, submissions, grading
- **ProgressController** - Progress tracking and updates
- **GradingController** - Grading operations

### Payments & Wallet
- **PaymentController** - Payment processing, webhooks, callbacks (4 gateways)
- **WalletController** - Wallet balance, transfers, purchases, rewards
- **CouponController** - Coupon management and validation

### Communication & Engagement
- **ChatController** - Chat sessions, messages, AI tutor
- **ForumController** - Forum topics, posts, replies
- **NotificationController** - Notification management and preferences
- **ReviewController** - Course reviews and ratings

### Admin & Analytics
- **AdminController** - Admin dashboard, user management, system stats
- **DashboardController** - Role-specific dashboards (student, instructor, admin)
- **AnalyticsController** - Learning analytics, course performance
- **AdvancedAnalyticsController** - ML-based predictions, cohort analysis
- **ReportController** - Report generation and export
- **AuditController** - Audit logging

### Advanced Features
- **CertificateController** - Certificate generation and management
- **BadgeController** - Badge creation and awarding
- **LearningPathController** - Learning path management
- **RecommendationController** - AI recommendations
- **SearchController** - Global search functionality
- **FileController** - File upload and management
- **LanguageController** - Multi-language support
- **SettingController** - System settings
- **VideoStreamingController** - Video streaming
- **LocalizationController** - Content localization
- **RealtimeController** - Real-time features
- **EnrollmentController** - Enrollment management

---

## 📦 MODELS (50+)

### User & Authentication
- **User** - User accounts with roles (student, instructor, admin)
- **VerificationCode** - Email/phone verification codes

### Learning Content
- **Course** - Course definitions with metadata
- **Lesson** - Lesson content within courses
- **Topic** - Topics within lessons
- **Term** - Academic terms
- **Level** - Student levels/grades
- **CurriculumCategory** - Curriculum organization
- **CourseCategory** - Course categorization

### Learning Progress
- **Enrollment** - Student-Course relationship
- **LessonCompletion** - Lesson completion tracking
- **Progress** - Overall progress tracking
- **QuizAttempt** - Quiz attempt records
- **LearningPathEnrollment** - Learning path progress

### Assessment
- **Quiz** - Quiz definitions
- **Question** - Quiz questions
- **Answer** - Student answers
- **Assignment** - Assignment definitions
- **Submission** - Assignment submissions
- **AssignmentSubmission** - Detailed submission records

### Achievements
- **Certificate** - Course completion certificates
- **Badge** - Achievement badges
- **UserBadge** - User-Badge relationship
- **UserReward** - User rewards

### Payment & Wallet
- **Payment** - Payment records
- **Wallet** - User wallet accounts
- **Transaction** - Wallet transactions
- **WalletTransaction** - Detailed transaction records
- **Coupon** - Discount coupons
- **CouponUsage** - Coupon usage tracking

### Communication
- **ChatSession** - Chat session records
- **ChatMessage** - Chat messages
- **ForumTopic** - Forum topics
- **ForumPost** - Forum posts
- **ForumReply** - Forum replies
- **Notification** - User notifications
- **NotificationPreference** - Notification settings

### Analytics & Insights
- **CourseAnalytic** - Course analytics
- **CourseReview** - Course reviews
- **EngagementScore** - Student engagement metrics
- **StudentSuccessPrediction** - ML predictions
- **CohortAnalysis** - Cohort-level analysis
- **AiRecommendation** - AI recommendations
- **ActivityLog** - Activity logging
- **AuditLog** - Audit trail

### Advanced Features
- **File** - File uploads
- **ContentTranslation** - Multi-language content
- **VideoStream** - Video streaming records
- **VideoQuality** - Video quality options
- **VideoAnalytic** - Video analytics
- **VideoDownload** - Video download tracking
- **Setting** - System settings
- **Tag** - Content tags

---

## 🔧 SERVICES

### Core Services
- **PaymentGatewayService** - Payment processing orchestration
- **WalletService** - Wallet operations
- **FileUploadService** - File handling
- **NotificationService** - Notification delivery
- **CacheService** - Caching layer

### Advanced Services
- **AdvancedAnalyticsService** - ML analytics
- **LocalizationService** - Multi-language support
- **VideoStreamingService** - Video delivery
- **RealtimeService** - Real-time features

### Payment Gateways
- **PaystackGateway** - Paystack integration
- **FlutterwaveGateway** - Flutterwave integration
- **StripeGateway** - Stripe integration
- **PaypalGateway** - PayPal integration

---

## 🛣️ API ROUTES

### Route Structure
```
/api/
├── /auth/                    (Authentication)
├── /courses/                 (Course management)
├── /lessons/                 (Lesson management)
├── /quizzes/                 (Quiz management)
├── /assignments/             (Assignment management)
├── /enrollments/             (Enrollment management)
├── /wallet/                  (Wallet operations)
├── /payments/                (Payment processing)
├── /users/                   (User management)
├── /certificates/            (Certificates)
├── /badges/                  (Badges)
├── /dashboard/               (Dashboards)
├── /analytics/               (Analytics)
├── /admin/                   (Admin operations)
├── /notifications/           (Notifications)
├── /chat/                    (Chat)
├── /forum/                   (Forum)
├── /search/                  (Search)
├── /files/                   (File management)
├── /learning-paths/          (Learning paths)
├── /recommendations/         (Recommendations)
├── /coupons/                 (Coupons)
└── /reports/                 (Reports)
```

### Middleware
- `auth:sanctum` - Token authentication
- `role:admin,instructor` - Role-based access
- `verified` - Email verification
- `throttle` - Rate limiting

---

## 🗄️ DATABASE SCHEMA

### Key Tables
- **users** (70+ columns) - User accounts
- **courses** (20+ columns) - Course definitions
- **lessons** (15+ columns) - Lesson content
- **enrollments** (8 columns) - Student-Course relationship
- **quizzes** (12 columns) - Quiz definitions
- **questions** (10 columns) - Quiz questions
- **assignments** (12 columns) - Assignment definitions
- **payments** (12 columns) - Payment records
- **wallets** (5 columns) - User wallets
- **transactions** (10 columns) - Wallet transactions
- **certificates** (10 columns) - Certificates
- **badges** (8 columns) - Badges
- **notifications** (8 columns) - Notifications
- **chat_sessions** (10 columns) - Chat sessions
- **chat_messages** (8 columns) - Chat messages

### Indexes
- User role and status indexes
- Course status and category indexes
- Enrollment user-course composite index
- Transaction wallet and status indexes
- Lesson course-order composite index

### Relationships
- One-to-Many: User → Enrollments, Courses, Wallets
- Many-to-Many: Users ↔ Courses (via Enrollments)
- Hierarchical: Course → Lessons → Topics
- Polymorphic: Notifications, Files

---

## 🎨 FRONTEND COMPONENTS

### Blade Templates (50+)
- **Admin:** Dashboard, users, courses, categories, levels, terms
- **Auth:** Login, register, password reset, email verification
- **Users:** Dashboard, profile, courses, wallet, notifications
- **Layouts:** Main template, dashboard layout, user template

### JavaScript API Clients
- **BaseApiClient** - Base functionality
- **AuthClient** - Authentication
- **CourseApiClient** - Course operations
- **AdminApiClient** - Admin operations
- **WalletApiClient** - Wallet operations
- **LessonApiClient** - Lesson operations
- **TopicApiClient** - Topic operations
- **TransactionApiClient** - Transaction operations

### Utilities
- **ToastNotification** - Toast notifications
- **UIHelpers** - UI utility functions

---

## 🔄 COMMON PATTERNS

### API Response Format
```json
{
  "status": "success|error",
  "message": "Human-readable message",
  "data": { /* response data */ },
  "errors": { /* validation errors */ }
}
```

### Authentication Flow
1. Register/Login → Get token
2. Include token in Authorization header
3. Token validated by Sanctum middleware
4. Request processed with authenticated user

### Payment Flow
1. Initiate payment → Create Payment record
2. Redirect to gateway
3. Gateway processes payment
4. Webhook callback updates Payment status
5. Create Wallet transaction if successful

### Enrollment Flow
1. User enrolls in course
2. Create Enrollment record
3. Initialize progress tracking
4. Process payment if course is paid
5. Grant access to course content

### Progress Tracking
1. User completes lesson
2. Create LessonCompletion record
3. Update Enrollment progress
4. Check if course is complete
5. Award certificate if applicable

---

## 🧪 TESTING PATTERNS

### Test Structure
```php
class FeatureTest extends TestCase {
    use RefreshDatabase;
    
    public function setUp(): void {
        parent::setUp();
        // Setup test data
    }
    
    public function test_endpoint() {
        $response = $this->postJson('/api/endpoint', $data);
        $response->assertStatus(200);
    }
}
```

### Common Assertions
- `assertStatus(200)` - HTTP status
- `assertJsonStructure()` - Response structure
- `assertDatabaseHas()` - Database records
- `assertAuthenticated()` - User authenticated

---

## 📊 PERFORMANCE CONSIDERATIONS

### Database Optimization
- Indexes on frequently queried columns
- Composite indexes for common filters
- Eager loading with `with()`
- Query optimization in services

### Caching
- CacheService for frequently accessed data
- Redis support configured
- Cache invalidation on updates

### API Optimization
- Pagination support (default 15 per page)
- Selective field loading
- Response compression
- Rate limiting

---

## 🔒 SECURITY FEATURES

- **Authentication:** Laravel Sanctum tokens
- **Authorization:** Role-based access control
- **Validation:** Request validation classes
- **Encryption:** Password hashing (bcrypt)
- **CORS:** Configured for API access
- **SQL Injection:** Eloquent ORM protection
- **CSRF:** Token-based protection
- **Rate Limiting:** Throttle middleware

---

## 📈 SCALABILITY

- **Database:** Indexed queries, soft deletes
- **Caching:** Redis support
- **Queue:** Background job processing
- **API:** Stateless design
- **Load Balancing:** Horizontal scaling ready
- **CDN:** Static asset delivery

---

*For implementation details, refer to specific controller/model files.*

