# 🎮 Controller Inventory - Kokokah.com LMS
**Date:** January 7, 2026 | **Total Controllers:** 40+

---

## 🔐 Authentication & Authorization

### AuthController
- `register()` - User registration
- `login()` - User login
- `logout()` - User logout
- `sendVerificationCode()` - Email verification
- `verifyEmailWithCode()` - Verify with code
- `resendVerificationCode()` - Resend code
- `passwordReset()` - Password reset
- `refreshToken()` - Token refresh

---

## 📚 Course Management

### CourseController
- `index()` - List courses (with filters)
- `show()` - Get course details
- `store()` - Create course
- `update()` - Update course
- `destroy()` - Delete course
- `publish()` - Publish course
- `enroll()` - Enroll in course
- `getInstructorCourses()` - Instructor's courses
- `getCourseStats()` - Course statistics

### LessonController
- `index()` - List lessons
- `show()` - Get lesson details
- `store()` - Create lesson
- `update()` - Update lesson
- `destroy()` - Delete lesson
- `complete()` - Mark lesson complete

### TopicController
- `index()` - List topics
- `show()` - Get topic details
- `store()` - Create topic
- `update()` - Update topic
- `destroy()` - Delete topic

### CourseCategoryController
- `index()` - List categories
- `store()` - Create category
- `update()` - Update category
- `destroy()` - Delete category

### CurriculumCategoryController
- `index()` - List curriculum categories
- `store()` - Create curriculum
- `update()` - Update curriculum
- `destroy()` - Delete curriculum

### LevelController
- `index()` - List levels
- `store()` - Create level
- `update()` - Update level
- `destroy()` - Delete level

### TermController
- `index()` - List terms
- `store()` - Create term
- `update()` - Update term
- `destroy()` - Delete term

---

## 📝 Learning & Assessment

### QuizController
- `index()` - List quizzes
- `show()` - Get quiz details
- `store()` - Create quiz
- `update()` - Update quiz
- `destroy()` - Delete quiz
- `startQuiz()` - Start quiz attempt
- `submitAnswers()` - Submit quiz answers
- `getResults()` - Get quiz results
- `getAnalytics()` - Quiz analytics

### AssignmentController
- `index()` - List assignments
- `show()` - Get assignment details
- `store()` - Create assignment
- `update()` - Update assignment
- `destroy()` - Delete assignment
- `submit()` - Submit assignment
- `grade()` - Grade submission
- `getSubmissions()` - Get submissions

### GradingController
- `gradeAssignment()` - Grade assignment
- `gradeQuiz()` - Grade quiz
- `getGrades()` - Get student grades
- `updateGrade()` - Update grade

---

## 👥 User Management

### UserController
- `index()` - List users
- `show()` - Get user details
- `store()` - Create user
- `update()` - Update user
- `destroy()` - Delete user
- `getProfile()` - Get user profile
- `updateProfile()` - Update profile
- `changePassword()` - Change password

### AdminController
- `getDashboard()` - Admin dashboard
- `getSystemStats()` - System statistics
- `getUserAnalytics()` - User analytics
- `getCourseAnalytics()` - Course analytics
- `getRevenueAnalytics()` - Revenue analytics
- `getSystemActivities()` - System activities
- `getPendingApprovals()` - Pending approvals
- `getSystemHealth()` - System health

---

## 💬 Communication

### ChatController
- `getRooms()` - List chat rooms
- `createRoom()` - Create room
- `updateRoom()` - Update room
- `deleteRoom()` - Delete room
- `joinRoom()` - Join room
- `leaveRoom()` - Leave room
- `muteUser()` - Mute user in room

### ChatMessageController
- `getMessages()` - Get room messages
- `sendMessage()` - Send message
- `editMessage()` - Edit message
- `deleteMessage()` - Delete message
- `getMessageHistory()` - Message history

### AnnouncementController
- `index()` - List announcements
- `show()` - Get announcement
- `store()` - Create announcement
- `update()` - Update announcement
- `destroy()` - Delete announcement
- `markAsRead()` - Mark as read

### NotificationController
- `getNotifications()` - Get notifications
- `markAsRead()` - Mark as read
- `deleteNotification()` - Delete notification
- `getPreferences()` - Get preferences
- `updatePreferences()` - Update preferences

### ForumController
- `getTopics()` - List topics
- `createTopic()` - Create topic
- `getReplies()` - Get replies
- `createReply()` - Create reply
- `updateReply()` - Update reply
- `deleteReply()` - Delete reply

---

## 💰 Financial

### WalletController
- `getBalance()` - Get wallet balance
- `getTransactions()` - Get transactions
- `transfer()` - Transfer funds
- `addFunds()` - Add funds
- `withdrawFunds()` - Withdraw funds

### PaymentController
- `initiatePayment()` - Start payment
- `verifyPayment()` - Verify payment
- `getPaymentHistory()` - Payment history
- `refund()` - Process refund
- `getPaymentMethods()` - Payment methods

### CouponController
- `index()` - List coupons
- `store()` - Create coupon
- `update()` - Update coupon
- `destroy()` - Delete coupon
- `validate()` - Validate coupon

### PointsAndBadgesController
- `getPoints()` - Get user points
- `getBadges()` - Get user badges
- `awardPoints()` - Award points
- `awardBadge()` - Award badge
- `getLeaderboard()` - Get leaderboard

---

## 📊 Analytics & Reporting

### DashboardController
- `studentDashboard()` - Student dashboard
- `instructorDashboard()` - Instructor dashboard
- `adminDashboard()` - Admin dashboard
- `analytics()` - Analytics data

### AnalyticsController
- `getEngagementMetrics()` - Engagement data
- `getCoursePerformance()` - Course performance
- `getStudentProgress()` - Student progress
- `getSystemMetrics()` - System metrics

### ReportController
- `generateReport()` - Generate report
- `getReports()` - List reports
- `downloadReport()` - Download report
- `deleteReport()` - Delete report

### AuditController
- `getAuditLogs()` - Get audit logs
- `getUserActivity()` - User activity
- `getSystemActivity()` - System activity

---

## 🎖️ Certificates & Achievements

### CertificateController
- `generate()` - Generate certificate
- `getCertificates()` - Get certificates
- `verifyCertificate()` - Verify certificate
- `downloadCertificate()` - Download certificate

### BadgeController
- `getBadges()` - List badges
- `awardBadge()` - Award badge
- `getUserBadges()` - User badges

---

## 📈 Progress & Enrollment

### EnrollmentController
- `index()` - List enrollments
- `show()` - Get enrollment
- `store()` - Create enrollment
- `update()` - Update enrollment
- `destroy()` - Delete enrollment
- `getProgress()` - Get progress

### ProgressController
- `getProgress()` - Get progress
- `updateProgress()` - Update progress
- `getCompletionStats()` - Completion stats

### ReviewController
- `index()` - List reviews
- `store()` - Create review
- `update()` - Update review
- `destroy()` - Delete review
- `getAnalytics()` - Review analytics

---

## 🔧 Utilities

### SearchController
- `search()` - Global search
- `searchCourses()` - Search courses
- `searchUsers()` - Search users

### FileController
- `upload()` - Upload file
- `download()` - Download file
- `delete()` - Delete file

### SettingController
- `getSettings()` - Get settings
- `updateSettings()` - Update settings

### LanguageController
- `getLanguages()` - List languages
- `setLanguage()` - Set language

### RecommendationController
- `getRecommendations()` - Get recommendations
- `getPersonalized()` - Personalized recommendations

### RatingController
- `getRatings()` - Get ratings
- `createRating()` - Create rating
- `updateRating()` - Update rating

---

**Inventory Completed:** January 7, 2026

