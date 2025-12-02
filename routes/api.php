<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\CurriculumCategoryController;
use App\Http\Controllers\CourseCategoryController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\GradingController;
use App\Http\Controllers\LearningPathController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TermController;




// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');




Route::middleware('auth:sanctum')->group(function () {

    // Resend verification link (traditional method)
    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent.']);
    });

    // Verify email (clicked link lands here)
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        // This will mark the user as verified
        $request->fulfill();

        return response()->json(['message' => 'Email verified successfully.']);
    })->middleware(['signed'])->name('verification.verify');

    // Send verification code (authenticated user)
    Route::post('/email/send-code', [AuthController::class, 'sendVerificationCode']);

    // Verify with code (authenticated user)
    Route::post('/email/verify-code', [AuthController::class, 'verifyEmailWithCode']);

    // Resend verification code (authenticated user)
    Route::post('/email/resend-code', [AuthController::class, 'resendVerificationCode']);
});
















Route::get('/', function() {
    return 'API';
});


// Curriculum Category (LMS curriculum) 
Route::apiResource('curriculum-category', CurriculumCategoryController::class);

// Course Category (general category for courses)
Route::apiResource('course-category', CourseCategoryController::class);



// Public course routes
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/search', [CourseController::class, 'search']);
Route::get('/courses/featured', [CourseController::class, 'featured']);
Route::get('/courses/popular', [CourseController::class, 'popular']);

// Authenticated course routes (must be before {id} route)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/courses/my-courses', [CourseController::class, 'myCourses']);
});

Route::get('/courses/{id}', [CourseController::class, 'show']);






Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/forgot-password', [PasswordResetController::class,'sendResetLinkEmail']);
Route::post('/reset-password', [PasswordResetController::class,'reset']);

// Email verification with code routes (public)
Route::post('/email/send-verification-code', [AuthController::class, 'sendVerificationCode']);
Route::post('/email/verify-with-code', [AuthController::class, 'verifyEmailWithCode']);
Route::post('/email/resend-verification-code', [AuthController::class, 'resendVerificationCode']);

// Public payment routes (no auth required)
Route::prefix('payments')->group(function () {
    Route::post('/webhook/{gateway}', [PaymentController::class, 'webhook'])->name('payment.webhook');
    Route::get('/callback/{gateway}', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/success/{gateway}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/cancel/{gateway}', [PaymentController::class, 'cancel'])->name('payment.cancel');
});



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class,'user']);
    Route::post('/logout', [AuthController::class,'logout']);

    // Wallet routes
    Route::prefix('wallet')->group(function () {
        Route::get('/', [WalletController::class, 'index']);
        Route::post('/transfer', [WalletController::class, 'transfer']);
        Route::post('/purchase-course', [WalletController::class, 'purchaseCourse']);
        Route::get('/transactions', [WalletController::class, 'transactions']);
        Route::get('/rewards', [WalletController::class, 'rewards']);
        Route::post('/claim-login-reward', [WalletController::class, 'claimLoginReward']);
        Route::post('/check-affordability', [WalletController::class, 'checkAffordability']);
    });

    // Payment routes (handles all gateway payments)
    Route::prefix('payments')->group(function () {
        Route::get('/gateways', [PaymentController::class, 'gateways']);
        Route::post('/deposit', [PaymentController::class, 'initializeWalletDeposit']);
        Route::post('/purchase-course', [PaymentController::class, 'initializeCoursePayment']);
        Route::get('/history', [PaymentController::class, 'history']);
        Route::get('/{id}', [PaymentController::class, 'show']);
    });

    // Course management routes (authenticated)
    Route::prefix('courses')->group(function () {
        // Instructor/Admin only routes
        Route::middleware('role:instructor,admin')->group(function () {
            Route::post('/', [CourseController::class, 'store']);
            Route::put('/{id}', [CourseController::class, 'update']);
            Route::delete('/{id}', [CourseController::class, 'destroy']);
            Route::get('/{id}/students', [CourseController::class, 'students']);
            Route::get('/{id}/analytics', [CourseController::class, 'analytics']);
            Route::post('/{id}/publish', [CourseController::class, 'publish']);
            Route::post('/{id}/unpublish', [CourseController::class, 'unpublish']);
        });

        // Student accessible routes
        Route::post('/{id}/enroll', [CourseController::class, 'enroll']);
        Route::delete('/{id}/unenroll', [CourseController::class, 'unenroll']);
    });

    // Lesson management routes (authenticated)
    Route::prefix('courses/{courseId}/lessons')->group(function () {
        Route::get('/', [LessonController::class, 'index']);
        Route::post('/', [LessonController::class, 'store']);
    });

// Topic management route 
    Route::apiResource('topic', TopicController::class);


// Term management route 
    Route::apiResource('term', TermController::class);

    Route::prefix('lessons')->group(function () {
        Route::get('/{id}', [LessonController::class, 'show']);
        Route::put('/{id}', [LessonController::class, 'update']);
        Route::delete('/{id}', [LessonController::class, 'destroy']);
        Route::post('/{id}/complete', [LessonController::class, 'complete']);
        Route::get('/{id}/progress', [LessonController::class, 'progress']);
        Route::post('/{id}/watch-time', [LessonController::class, 'trackWatchTime']);
        Route::get('/{id}/attachments', [LessonController::class, 'attachments']);
    });



    // Enrollment management routes (authenticated)
    Route::prefix('enrollments')->group(function () {
        Route::get('/', [EnrollmentController::class, 'index']);
        Route::post('/', [EnrollmentController::class, 'store']);
        Route::get('/certificates', [EnrollmentController::class, 'certificates']);
        Route::get('/{id}', [EnrollmentController::class, 'show']);
        Route::put('/{id}', [EnrollmentController::class, 'update']);
        Route::delete('/{id}', [EnrollmentController::class, 'destroy']);
        Route::get('/{id}/progress', [EnrollmentController::class, 'progress']);
        Route::post('/{id}/complete', [EnrollmentController::class, 'complete']);
    });

    // User profile and dashboard routes (authenticated)
    Route::prefix('users')->group(function () {
        Route::get('/profile', [UserController::class, 'profile']);
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::get('/dashboard', [UserController::class, 'dashboard']);
        Route::get('/achievements', [UserController::class, 'achievements']);
        Route::get('/learning-stats', [UserController::class, 'learningStats']);
        Route::put('/preferences', [UserController::class, 'updatePreferences']);
        Route::get('/notifications', [UserController::class, 'notifications']);
        Route::post('/notifications/read', [UserController::class, 'markNotificationsRead']);
        Route::post('/change-password', [UserController::class, 'changePassword']);
    });

    // Quiz management routes (authenticated)
    Route::prefix('lessons/{lessonId}/quizzes')->group(function () {
        Route::get('/', [QuizController::class, 'index']);
        Route::post('/', [QuizController::class, 'store']);
    });

    Route::prefix('quizzes')->group(function () {
        Route::get('/{id}', [QuizController::class, 'show']);
        Route::put('/{id}', [QuizController::class, 'update']);
        Route::delete('/{id}', [QuizController::class, 'destroy']);
        Route::post('/{id}/start', [QuizController::class, 'startAttempt']);
        Route::post('/{id}/submit', [QuizController::class, 'submitQuiz']);
        Route::get('/{id}/results', [QuizController::class, 'results']);
        Route::get('/{id}/analytics', [QuizController::class, 'analytics']);
    });

    // Assignment management routes (authenticated)
    Route::prefix('courses/{courseId}/assignments')->group(function () {
        Route::get('/', [AssignmentController::class, 'index']);
        Route::post('/', [AssignmentController::class, 'store']);
    });

    Route::prefix('assignments')->group(function () {
        Route::get('/{id}', [AssignmentController::class, 'show']);
        Route::put('/{id}', [AssignmentController::class, 'update']);
        Route::delete('/{id}', [AssignmentController::class, 'destroy']);
        Route::post('/{id}/submit', [AssignmentController::class, 'submit']);
        Route::get('/{id}/submissions', [AssignmentController::class, 'submissions']);
        Route::get('/{id}/grades', [AssignmentController::class, 'grades']);
    });

    Route::prefix('submissions')->group(function () {
        Route::put('/{id}/grade', [AssignmentController::class, 'gradeSubmission']);
    });

    // Dashboard routes (authenticated)
    Route::prefix('dashboard')->group(function () {
        Route::get('/student', [DashboardController::class, 'studentDashboard']);
        Route::get('/instructor', [DashboardController::class, 'instructorDashboard']);
        Route::get('/admin', [DashboardController::class, 'adminDashboard']);
        Route::get('/analytics', [DashboardController::class, 'analytics']);
    });

    // Review management routes (authenticated)
    Route::prefix('courses/{courseId}/reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'index']);
        Route::post('/', [ReviewController::class, 'store']);
        Route::get('/analytics', [ReviewController::class, 'analytics']);
    });

    Route::prefix('reviews')->group(function () {
        Route::get('/moderate', [ReviewController::class, 'moderate']);
        Route::get('/my-reviews', [ReviewController::class, 'userReviews']);
        Route::get('/{id}', [ReviewController::class, 'show']);
        Route::put('/{id}', [ReviewController::class, 'update']);
        Route::delete('/{id}', [ReviewController::class, 'destroy']);
        Route::post('/{id}/helpful', [ReviewController::class, 'markHelpful']);
        Route::post('/{id}/approve', [ReviewController::class, 'approve']);
        Route::post('/{id}/reject', [ReviewController::class, 'reject']);
    });

    // Forum management routes (authenticated)
    Route::prefix('courses/{courseId}/forum')->group(function () {
        Route::get('/', [ForumController::class, 'index']);
        Route::post('/', [ForumController::class, 'store']);
        Route::get('/analytics', [ForumController::class, 'analytics']);
    });

    Route::prefix('forum/topics')->group(function () {
        Route::get('/{id}', [ForumController::class, 'show']);
        Route::put('/{id}', [ForumController::class, 'update']);
        Route::delete('/{id}', [ForumController::class, 'destroy']);
        Route::post('/{id}/subscribe', [ForumController::class, 'subscribe']);
        Route::delete('/{id}/unsubscribe', [ForumController::class, 'unsubscribe']);
        Route::post('/{id}/posts', [ForumController::class, 'storePost']);
    });

    Route::prefix('forum/posts')->group(function () {
        Route::put('/{id}', [ForumController::class, 'updatePost']);
        Route::delete('/{id}', [ForumController::class, 'destroyPost']);
        Route::post('/{id}/like', [ForumController::class, 'likePost']);
        Route::post('/{id}/solution', [ForumController::class, 'markSolution']);
    });

    // Certificate management routes (authenticated)
    Route::prefix('certificates')->group(function () {
        Route::get('/', [CertificateController::class, 'index']);
        Route::get('/analytics', [CertificateController::class, 'analytics']);
        Route::get('/templates', [CertificateController::class, 'templates']);
        Route::post('/generate', [CertificateController::class, 'generate']);
        Route::post('/bulk-generate', [CertificateController::class, 'bulkGenerate']);
        Route::get('/{id}', [CertificateController::class, 'show']);
        Route::get('/{id}/download', [CertificateController::class, 'download']);
        Route::post('/{id}/revoke', [CertificateController::class, 'revoke']);
    });

    // Badge management routes (authenticated)
    Route::prefix('badges')->group(function () {
        Route::get('/', [BadgeController::class, 'index']);
        Route::get('/analytics', [BadgeController::class, 'analytics']);
        Route::get('/leaderboard', [BadgeController::class, 'leaderboard']);
        Route::post('/', [BadgeController::class, 'store']);
        Route::post('/award', [BadgeController::class, 'awardBadge']);
        Route::post('/check-automatic/{userId}', [BadgeController::class, 'checkAutomaticBadges']);
        Route::get('/{id}', [BadgeController::class, 'show']);
        Route::put('/{id}', [BadgeController::class, 'update']);
        Route::delete('/{id}', [BadgeController::class, 'destroy']);
        Route::post('/user-badges/{userId}/{badgeId}/revoke', [BadgeController::class, 'revokeBadge']);
    });

    // User badges routes
    Route::get('/users/{userId}/badges', [BadgeController::class, 'userBadges']);
    Route::get('/my-badges', [BadgeController::class, 'userBadges']);

    // Progress tracking routes (authenticated)
    Route::prefix('progress')->group(function () {
        Route::get('/courses', [ProgressController::class, 'courseProgress']);
        Route::get('/lessons', [ProgressController::class, 'lessonProgress']);
        Route::get('/overall', [ProgressController::class, 'overallProgress']);
        Route::post('/update', [ProgressController::class, 'updateProgress']);
        Route::get('/certificates', [ProgressController::class, 'availableCertificates']);
        Route::post('/generate-cert', [ProgressController::class, 'generateCertificate']);
        Route::get('/achievements', [ProgressController::class, 'achievementProgress']);
        Route::get('/streaks', [ProgressController::class, 'streakProgress']);
    });

    // Grading management routes (authenticated)
    Route::prefix('grading')->group(function () {
        Route::get('/gradebook/{courseId}', [GradingController::class, 'gradebook']);
        Route::get('/courses/{courseId}', [GradingController::class, 'courseGrades']);
        Route::get('/students/{studentId}', [GradingController::class, 'studentGrades']);
        Route::post('/bulk-grade', [GradingController::class, 'bulkGrade']);
        Route::get('/analytics', [GradingController::class, 'analytics']);
        Route::post('/export', [GradingController::class, 'exportGrades']);
        Route::get('/grade-history/{studentId}/{courseId}', [GradingController::class, 'gradeHistory']);
        Route::put('/weights/{courseId}', [GradingController::class, 'updateGradeWeights']);
        Route::post('/comments', [GradingController::class, 'addGradingComments']);
        Route::get('/reports/{courseId}', [GradingController::class, 'reports']);
    });

    // Admin management routes (admin only)
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/users/recent', [AdminController::class, 'recentlyRegisteredUsers']);
        Route::get('/users/{userId}', [AdminController::class, 'getUser']);
        Route::post('/users', [AdminController::class, 'createUser']);
        Route::put('/users/{userId}', [AdminController::class, 'updateUser']);
        Route::post('/users/{userId}', [AdminController::class, 'updateUser']);  // Allow POST with _method=PUT for FormData
        Route::delete('/users/{userId}', [AdminController::class, 'deleteUser']);
        Route::get('/courses', [AdminController::class, 'courses']);
        Route::get('/payments', [AdminController::class, 'payments']);
        Route::get('/transactions', [AdminController::class, 'transactions']);
        Route::get('/reports', [AdminController::class, 'reports']);
        Route::get('/settings', [AdminController::class, 'settings']);
        Route::get('/stats', [AdminController::class, 'databaseStats']); // Add missing stats route
        Route::post('/users/{userId}/ban', [AdminController::class, 'banUser']);
        Route::post('/users/{userId}/unban', [AdminController::class, 'unbanUser']);
        Route::get('/analytics', [AdminController::class, 'analytics']);
        Route::post('/bulk-actions', [AdminController::class, 'bulkActions']);
        Route::get('/audit-logs', [AdminController::class, 'auditLogs']);
        Route::post('/maintenance', [AdminController::class, 'maintenanceMode']);
        Route::post('/clear-cache', [AdminController::class, 'clearCache']);
        Route::get('/database-stats', [AdminController::class, 'databaseStats']);
    });

    // Analytics routes (instructor/admin)
    Route::prefix('analytics')->middleware('role:instructor,admin')->group(function () {
        Route::get('/learning', [AnalyticsController::class, 'learningAnalytics']);
        Route::get('/course-performance', [AnalyticsController::class, 'coursePerformance']);
        Route::get('/student-progress', [AnalyticsController::class, 'studentProgress']);
        Route::get('/revenue', [AnalyticsController::class, 'revenueAnalytics'])->middleware('role:admin');
        Route::get('/engagement', [AnalyticsController::class, 'engagementAnalytics']);
        Route::post('/comparative', [AnalyticsController::class, 'comparativeAnalytics']);
        Route::post('/export', [AnalyticsController::class, 'exportAnalytics']);
        Route::get('/real-time', [AnalyticsController::class, 'realTimeAnalytics']);
        Route::get('/predictive', [AnalyticsController::class, 'predictiveAnalytics'])->middleware('role:admin');
    });

    // Learning paths routes (authenticated)
    Route::prefix('learning-paths')->group(function () {
        Route::get('/', [LearningPathController::class, 'index']);
        Route::post('/', [LearningPathController::class, 'store']);
        Route::get('/{id}', [LearningPathController::class, 'show']);
        Route::put('/{id}', [LearningPathController::class, 'update']);
        Route::delete('/{id}', [LearningPathController::class, 'destroy']);
        Route::post('/{id}/enroll', [LearningPathController::class, 'enroll']);
        Route::delete('/{id}/unenroll', [LearningPathController::class, 'unenroll']);
        Route::get('/my/paths', [LearningPathController::class, 'myPaths']);
        Route::get('/{id}/progress', [LearningPathController::class, 'pathProgress']);
        Route::get('/{id}/analytics', [LearningPathController::class, 'analytics']);
        Route::post('/{id}/publish', [LearningPathController::class, 'publish']);
        Route::post('/{id}/unpublish', [LearningPathController::class, 'unpublish']);
    });

    // AI Chat routes (authenticated)
    Route::prefix('chat')->group(function () {
        Route::post('/start', [ChatController::class, 'startSession']);
        Route::post('/sessions/{sessionId}/message', [ChatController::class, 'sendMessage']);
        Route::get('/sessions/{sessionId}', [ChatController::class, 'getSessionHistory']);
        Route::get('/sessions', [ChatController::class, 'getUserSessions']);
        Route::post('/sessions/{sessionId}/end', [ChatController::class, 'endSession']);
        Route::post('/sessions/{sessionId}/rate', [ChatController::class, 'rateSession']);
        Route::get('/analytics', [ChatController::class, 'analytics'])->middleware('role:admin');
        Route::post('/suggestions', [ChatController::class, 'getSuggestedResponses']);
    });
});

// Admin-only route
Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin/reports', function () {
    return "Admin Reports";
});

// Instructor-only route
Route::middleware(['auth:sanctum', 'role:instructor'])->get('/instructor/courses', function () {
    return "Instructor Courses";
});




Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return response()->json(['message' => 'Welcome, verified user!']);
});

// Public certificate verification route (no authentication required)
Route::get('/certificates/verify/{certificateNumber}', [CertificateController::class, 'verify']);

// Recommendation routes
Route::middleware('auth:sanctum')->prefix('recommendations')->group(function () {
    Route::get('/', [RecommendationController::class, 'getRecommendations']);
    Route::get('/courses/{courseId}', [RecommendationController::class, 'getCourseBasedRecommendations']);
    Route::get('/learning-paths', [RecommendationController::class, 'getLearningPathRecommendations']);
    Route::get('/instructors', [RecommendationController::class, 'getInstructorRecommendations']);
    Route::get('/content', [RecommendationController::class, 'getContentRecommendations']);
    Route::put('/preferences', [RecommendationController::class, 'updatePreferences']);
    Route::get('/analytics', [RecommendationController::class, 'getAnalytics'])->middleware('role:admin');
});

// Coupon management routes
Route::middleware('auth:sanctum')->prefix('coupons')->group(function () {
    Route::get('/', [CouponController::class, 'index']);
    Route::post('/', [CouponController::class, 'store']);
    Route::get('/{id}', [CouponController::class, 'show']);
    Route::put('/{id}', [CouponController::class, 'update']);
    Route::delete('/{id}', [CouponController::class, 'destroy']);
    Route::post('/validate', [CouponController::class, 'validateCoupon']);
    Route::post('/apply', [CouponController::class, 'applyCoupon']);
    Route::get('/user/available', [CouponController::class, 'getUserCoupons']);
    Route::get('/admin/analytics', [CouponController::class, 'analytics']);
    Route::post('/bulk-action', [CouponController::class, 'bulkAction']);
});

// Report generation routes (instructor/admin)
Route::middleware(['auth:sanctum', 'role:instructor,admin'])->prefix('reports')->group(function () {
    Route::get('/types', [ReportController::class, 'getReportTypes']);
    Route::post('/financial', [ReportController::class, 'generateFinancialReport']);
    Route::post('/academic', [ReportController::class, 'generateAcademicReport']);
    Route::post('/user', [ReportController::class, 'generateUserReport'])->middleware('role:admin');
    Route::post('/content', [ReportController::class, 'generateContentReport']);
    Route::get('/scheduled', [ReportController::class, 'getScheduledReports']);
    Route::post('/schedule', [ReportController::class, 'scheduleReport'])->middleware('role:admin');
    Route::get('/history', [ReportController::class, 'getReportHistory']);
});

// System settings routes (admin only)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('settings')->group(function () {
    Route::get('/', [SettingController::class, 'index']);
    Route::get('/{key}', [SettingController::class, 'show']);
    Route::put('/{key}', [SettingController::class, 'update']);
    Route::put('/', [SettingController::class, 'updateBulk']);
    Route::post('/reset', [SettingController::class, 'reset']);
    Route::get('/email/config', [SettingController::class, 'getEmailSettings']);
    Route::get('/payment/config', [SettingController::class, 'getPaymentSettings']);
    Route::get('/features/toggles', [SettingController::class, 'getFeatureToggles']);
});

// Public settings (no auth required)
Route::get('/settings/public', [SettingController::class, 'getPublicSettings']);

// Audit and security routes (admin only)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('audit')->group(function () {
    Route::get('/logs', [AuditController::class, 'index']);
    Route::get('/logs/{id}', [AuditController::class, 'show']);
    Route::get('/users/{userId}/activity', [AuditController::class, 'getUserActivity']);
    Route::get('/system/events', [AuditController::class, 'getSystemEvents']);
    Route::get('/security/events', [AuditController::class, 'getSecurityEvents']);
    Route::post('/export', [AuditController::class, 'exportLogs']);
});

// Notification routes
Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/{id}', [NotificationController::class, 'delete']);
    Route::get('/preferences', [NotificationController::class, 'getPreferences']);
    Route::put('/preferences', [NotificationController::class, 'updatePreferences']);
    Route::post('/send', [NotificationController::class, 'sendNotification'])->middleware('role:admin');
    Route::post('/broadcast', [NotificationController::class, 'broadcastNotification'])->middleware('role:admin');
    Route::get('/analytics', [NotificationController::class, 'getAnalytics'])->middleware('role:admin');
});

// Search routes
Route::middleware('auth:sanctum')->prefix('search')->group(function () {
    Route::get('/', [SearchController::class, 'globalSearch']); // General search route
    Route::get('/global', [SearchController::class, 'globalSearch']);
    Route::get('/courses', [SearchController::class, 'courseSearch']);
    Route::get('/users', [SearchController::class, 'userSearch']);
    Route::get('/content', [SearchController::class, 'contentSearch']);
    Route::get('/suggestions', [SearchController::class, 'getSuggestions']);
    Route::get('/filters', [SearchController::class, 'getFilters']);
});

// File management routes
Route::middleware('auth:sanctum')->prefix('files')->group(function () {
    Route::post('/upload', [FileController::class, 'upload']);
    Route::get('/download/{id}', [FileController::class, 'download'])->name('files.download');
    Route::delete('/{id}', [FileController::class, 'delete']);
    Route::get('/list', [FileController::class, 'listFiles']);
    Route::get('/preview/{id}', [FileController::class, 'preview'])->name('files.preview');
    Route::post('/{id}/share', [FileController::class, 'share']);
    Route::post('/organize', [FileController::class, 'organize']);
    Route::get('/storage/stats', [FileController::class, 'getStorageStats']);
});

// Advanced Analytics routes
Route::middleware('auth:sanctum')->prefix('analytics/advanced')->group(function () {
    // Student predictions
    Route::get('/predictions/student/{studentId}', 'App\Http\Controllers\AdvancedAnalyticsController@getStudentPredictions');
    Route::post('/predictions/student/{studentId}/calculate', 'App\Http\Controllers\AdvancedAnalyticsController@calculateStudentPredictions')->middleware('role:admin');

    // Cohort analysis
    Route::get('/cohorts', 'App\Http\Controllers\AdvancedAnalyticsController@listCohorts');
    Route::post('/cohorts', 'App\Http\Controllers\AdvancedAnalyticsController@createCohort')->middleware('role:admin');
    Route::get('/cohorts/{cohortId}', 'App\Http\Controllers\AdvancedAnalyticsController@getCohortAnalysis');
    Route::post('/cohorts/{cohortId1}/compare/{cohortId2}', 'App\Http\Controllers\AdvancedAnalyticsController@compareCohorts');

    // Engagement scores
    Route::get('/engagement/course/{courseId}', 'App\Http\Controllers\AdvancedAnalyticsController@getCourseEngagement');
    Route::get('/engagement/student/{studentId}/course/{courseId}', 'App\Http\Controllers\AdvancedAnalyticsController@getStudentEngagement');
    Route::post('/engagement/course/{courseId}/calculate', 'App\Http\Controllers\AdvancedAnalyticsController@calculateCourseEngagement')->middleware('role:admin');

    // At-risk and high-performing students
    Route::get('/at-risk/course/{courseId}', 'App\Http\Controllers\AdvancedAnalyticsController@getAtRiskStudents');
    Route::get('/high-performing/course/{courseId}', 'App\Http\Controllers\AdvancedAnalyticsController@getHighPerformingStudents');

    // Dashboard
    Route::get('/dashboard', 'App\Http\Controllers\AdvancedAnalyticsController@getDashboard');
});

// Localization routes
Route::middleware('auth:sanctum')->prefix('localization')->group(function () {
    Route::get('/preferences', 'App\Http\Controllers\LocalizationController@getPreferences');
    Route::put('/preferences', 'App\Http\Controllers\LocalizationController@updatePreferences');
    Route::get('/languages', 'App\Http\Controllers\LocalizationController@getSupportedLanguages');
    Route::get('/currencies', 'App\Http\Controllers\LocalizationController@getSupportedCurrencies');
    Route::get('/timezones', 'App\Http\Controllers\LocalizationController@getSupportedTimezones');
    Route::post('/convert-currency', 'App\Http\Controllers\LocalizationController@convertCurrency');
    Route::post('/translate', 'App\Http\Controllers\LocalizationController@translateContent');
    Route::get('/translations', 'App\Http\Controllers\LocalizationController@getTranslations');
});

// Video Streaming routes
Route::middleware('auth:sanctum')->prefix('videos')->group(function () {
    Route::post('/', 'App\Http\Controllers\VideoStreamingController@createVideoStream')->middleware('role:instructor,admin');
    Route::post('/{videoStreamId}/process', 'App\Http\Controllers\VideoStreamingController@processVideoStream')->middleware('role:admin');
    Route::get('/{videoStreamId}', 'App\Http\Controllers\VideoStreamingController@getVideoStream');
    Route::post('/{videoStreamId}/view', 'App\Http\Controllers\VideoStreamingController@recordVideoView');
    Route::post('/{videoStreamId}/watch-time', 'App\Http\Controllers\VideoStreamingController@updateWatchTime');
    Route::post('/{videoStreamId}/download', 'App\Http\Controllers\VideoStreamingController@createDownloadRequest');
    Route::get('/{videoStreamId}/analytics', 'App\Http\Controllers\VideoStreamingController@getVideoAnalytics')->middleware('role:instructor,admin');
    Route::get('/top/videos', 'App\Http\Controllers\VideoStreamingController@getTopVideos');
    Route::get('/user/downloads', 'App\Http\Controllers\VideoStreamingController@getUserDownloads');
});

// Real-time Features routes
Route::middleware('auth:sanctum')->prefix('realtime')->group(function () {
    Route::post('/online', 'App\Http\Controllers\RealtimeController@markUserOnline');
    Route::post('/offline', 'App\Http\Controllers\RealtimeController@markUserOffline');
    Route::get('/users/online', 'App\Http\Controllers\RealtimeController@getOnlineUsers');
    Route::get('/users/online/count', 'App\Http\Controllers\RealtimeController@getOnlineCount');
    Route::get('/course/{courseId}/users/online', 'App\Http\Controllers\RealtimeController@getOnlineUsersInCourse');
    Route::get('/course/{courseId}/users/online/count', 'App\Http\Controllers\RealtimeController@getOnlineCountInCourse');
    Route::post('/typing', 'App\Http\Controllers\RealtimeController@sendTypingIndicator');
    Route::get('/activity/{userId}', 'App\Http\Controllers\RealtimeController@getUserActivityStatus');
    Route::get('/activity', 'App\Http\Controllers\RealtimeController@getCurrentUserActivityStatus');
});

// Language/Localization Routes (Public)
Route::prefix('language')->group(function () {
    // Get current locale
    Route::get('/current', [LanguageController::class, 'getCurrentLocale']);

    // Get all supported languages
    Route::get('/supported', [LanguageController::class, 'getSupportedLanguages']);

    // Set locale (for guests)
    Route::post('/set', [LanguageController::class, 'setLocale']);

    // Get translations for current locale
    Route::get('/translations', [LanguageController::class, 'getTranslations']);

    // Get translations for specific locale
    Route::get('/translations/{locale}', [LanguageController::class, 'getTranslationsByLocale']);

    // Get language info
    Route::get('/info/{locale}', [LanguageController::class, 'getLanguageInfo']);

    // Get all language info
    Route::get('/info', [LanguageController::class, 'getAllLanguageInfo']);
});

// Language/Localization Routes (Authenticated)
Route::middleware('auth:sanctum')->prefix('language')->group(function () {
    // Set user's preferred language
    Route::post('/user/set', [LanguageController::class, 'setUserLanguage']);

    // Get user's language preference
    Route::get('/user', [LanguageController::class, 'getUserLanguage']);
});
