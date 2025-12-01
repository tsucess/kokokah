GET|HEAD        / .............................................................................................................................  
  GET|HEAD        about .........................................................................................................................  
  GET|HEAD        adduser .......................................................................................................................  
  GET|HEAD        announcement ..................................................................................................................  
  GET|HEAD        api ...........................................................................................................................  
  GET|HEAD        api/admin/analytics ................................................................................. AdminController@analytics  
  GET|HEAD        api/admin/audit-logs ................................................................................ AdminController@auditLogs  
  POST            api/admin/bulk-actions ............................................................................ AdminController@bulkActions  
  POST            api/admin/clear-cache .............................................................................. AdminController@clearCache  
  GET|HEAD        api/admin/courses ..................................................................................... AdminController@courses  
  GET|HEAD        api/admin/dashboard ................................................................................. AdminController@dashboard  
  GET|HEAD        api/admin/database-stats ........................................................................ AdminController@databaseStats  
  POST            api/admin/maintenance ......................................................................... AdminController@maintenanceMode  
  GET|HEAD        api/admin/payments ................................................................................... AdminController@payments  
  GET|HEAD        api/admin/reports .............................................................................................................  
  GET|HEAD        api/admin/settings ................................................................................... AdminController@settings  
  GET|HEAD        api/admin/stats ................................................................................. AdminController@databaseStats  
  GET|HEAD        api/admin/transactions ........................................................................... AdminController@transactions  
  GET|HEAD        api/admin/users ......................................................................................... AdminController@users  
  POST            api/admin/users .................................................................................... AdminController@createUser  
  GET|HEAD        api/admin/users/recent ................................................................ AdminController@recentlyRegisteredUsers  
  GET|HEAD        api/admin/users/{userId} .............................................................................. AdminController@getUser  
  PUT             api/admin/users/{userId} ........................................................................... AdminController@updateUser  
  POST            api/admin/users/{userId} ........................................................................... AdminController@updateUser  
  DELETE          api/admin/users/{userId} ........................................................................... AdminController@deleteUser  
  POST            api/admin/users/{userId}/ban .......................................................................... AdminController@banUser  
  POST            api/admin/users/{userId}/unban ...................................................................... AdminController@unbanUser  
  GET|HEAD        api/analytics/advanced/at-risk/course/{courseId} ................................ AdvancedAnalyticsController@getAtRiskStudents  
  GET|HEAD        api/analytics/advanced/cohorts ........................................................ AdvancedAnalyticsController@listCohorts  
  POST            api/analytics/advanced/cohorts ....................................................... AdvancedAnalyticsController@createCohort  
  POST            api/analytics/advanced/cohorts/{cohortId1}/compare/{cohortId2} ..................... AdvancedAnalyticsController@compareCohorts  
  GET|HEAD        api/analytics/advanced/cohorts/{cohortId} ....................................... AdvancedAnalyticsController@getCohortAnalysis  
  GET|HEAD        api/analytics/advanced/dashboard ..................................................... AdvancedAnalyticsController@getDashboard  
  GET|HEAD        api/analytics/advanced/engagement/course/{courseId} ........................... AdvancedAnalyticsController@getCourseEngagement  
  POST            api/analytics/advanced/engagement/course/{courseId}/calculate ........... AdvancedAnalyticsController@calculateCourseEngagement  
  GET|HEAD        api/analytics/advanced/engagement/student/{studentId}/course/{courseId} ...... AdvancedAnalyticsController@getStudentEngagement  
  GET|HEAD        api/analytics/advanced/high-performing/course/{courseId} ................ AdvancedAnalyticsController@getHighPerformingStudents  
  GET|HEAD        api/analytics/advanced/predictions/student/{studentId} ...................... AdvancedAnalyticsController@getStudentPredictions  
  POST            api/analytics/advanced/predictions/student/{studentId}/calculate ...... AdvancedAnalyticsController@calculateStudentPredictions  
  POST            api/analytics/comparative ............................................................ AnalyticsController@comparativeAnalytics  
  GET|HEAD        api/analytics/course-performance ........................................................ AnalyticsController@coursePerformance  
  GET|HEAD        api/analytics/engagement .............................................................. AnalyticsController@engagementAnalytics  
  POST            api/analytics/export ...................................................................... AnalyticsController@exportAnalytics  
  GET|HEAD        api/analytics/learning .................................................................. AnalyticsController@learningAnalytics  
  GET|HEAD        api/analytics/predictive .............................................................. AnalyticsController@predictiveAnalytics  
  GET|HEAD        api/analytics/real-time ................................................................. AnalyticsController@realTimeAnalytics  
  GET|HEAD        api/analytics/revenue .................................................................... AnalyticsController@revenueAnalytics  
  GET|HEAD        api/analytics/student-progress ............................................................ AnalyticsController@studentProgress  
  GET|HEAD        api/assignments/{id} ................................................................................ AssignmentController@show  
  PUT             api/assignments/{id} .............................................................................. AssignmentController@update  
  DELETE          api/assignments/{id} ............................................................................. AssignmentController@destroy  
  GET|HEAD        api/assignments/{id}/grades ....................................................................... AssignmentController@grades  
  GET|HEAD        api/assignments/{id}/submissions ............................................................. AssignmentController@submissions  
  POST            api/assignments/{id}/submit ....................................................................... AssignmentController@submit  
  POST            api/audit/export ................................................................................... AuditController@exportLogs  
  GET|HEAD        api/audit/logs .......................................................................................... AuditController@index  
  GET|HEAD        api/audit/logs/{id} ...................................................................................... AuditController@show  
  GET|HEAD        api/audit/security/events ................................................................... AuditController@getSecurityEvents  
  GET|HEAD        api/audit/system/events ....................................................................... AuditController@getSystemEvents  
  GET|HEAD        api/audit/users/{userId}/activity ............................................................. AuditController@getUserActivity  
  GET|HEAD        api/badges .............................................................................................. BadgeController@index  
  POST            api/badges .............................................................................................. BadgeController@store  
  GET|HEAD        api/badges/analytics ................................................................................ BadgeController@analytics  
  POST            api/badges/award ................................................................................... BadgeController@awardBadge  
  POST            api/badges/check-automatic/{userId} ...................................................... BadgeController@checkAutomaticBadges  
  GET|HEAD        api/badges/leaderboard ............................................................................ BadgeController@leaderboard  
  POST            api/badges/user-badges/{userId}/{badgeId}/revoke .................................................. BadgeController@revokeBadge  
  GET|HEAD        api/badges/{id} .......................................................................................... BadgeController@show  
  PUT             api/badges/{id} ........................................................................................ BadgeController@update  
  DELETE          api/badges/{id} ....................................................................................... BadgeController@destroy  
  GET|HEAD        api/certificates .................................................................................. CertificateController@index  
  GET|HEAD        api/certificates/analytics .................................................................... CertificateController@analytics  
  POST            api/certificates/bulk-generate ............................................................. CertificateController@bulkGenerate  
  POST            api/certificates/generate ...................................................................... CertificateController@generate  
  GET|HEAD        api/certificates/templates .................................................................... CertificateController@templates  
  GET|HEAD        api/certificates/verify/{certificateNumber} ...................................................... CertificateController@verify  
  GET|HEAD        api/certificates/{id} .............................................................................. CertificateController@show  
  GET|HEAD        api/certificates/{id}/download ................................................................. CertificateController@download  
  POST            api/certificates/{id}/revoke ..................................................................... CertificateController@revoke  
  GET|HEAD        api/chat/analytics ................................................................................... ChatController@analytics  
  GET|HEAD        api/chat/sessions .............................................................................. ChatController@getUserSessions  
  GET|HEAD        api/chat/sessions/{sessionId} ................................................................ ChatController@getSessionHistory  
  POST            api/chat/sessions/{sessionId}/end ................................................................... ChatController@endSession  
  POST            api/chat/sessions/{sessionId}/message .............................................................. ChatController@sendMessage  
  POST            api/chat/sessions/{sessionId}/rate ................................................................. ChatController@rateSession  
  POST            api/chat/start .................................................................................... ChatController@startSession  
  POST            api/chat/suggestions ..................................................................... ChatController@getSuggestedResponses  
  GET|HEAD        api/coupons ............................................................................................ CouponController@index  
  POST            api/coupons ............................................................................................ CouponController@store  
  GET|HEAD        api/coupons/admin/analytics ........................................................................ CouponController@analytics  
  POST            api/coupons/apply ................................................................................ CouponController@applyCoupon  
  POST            api/coupons/bulk-action ........................................................................... CouponController@bulkAction  
  GET|HEAD        api/coupons/user/available .................................................................... CouponController@getUserCoupons  
  POST            api/coupons/validate .......................................................................... CouponController@validateCoupon  
  GET|HEAD        api/coupons/{id} ........................................................................................ CouponController@show  
  PUT             api/coupons/{id} ...................................................................................... CouponController@update  
  DELETE          api/coupons/{id} ..................................................................................... CouponController@destroy  
  GET|HEAD        api/course-category ................................................ course-category.index › CurriculumCategoryController@index  
  POST            api/course-category ................................................ course-category.store › CurriculumCategoryController@store  
  GET|HEAD        api/course-category/{course_category} ................................ course-category.show › CurriculumCategoryController@show  
  PUT|PATCH       api/course-category/{course_category} ............................ course-category.update › CurriculumCategoryController@update  
  DELETE          api/course-category/{course_category} .......................... course-category.destroy › CurriculumCategoryController@destroy  
  GET|HEAD        api/courses ............................................................................................ CourseController@index  
  POST            api/courses ............................................................................................ CourseController@store  
  GET|HEAD        api/courses/featured ................................................................................ CourseController@featured  
  GET|HEAD        api/courses/my-courses ............................................................................. CourseController@myCourses  
  GET|HEAD        api/courses/popular .................................................................................. CourseController@popular  
  GET|HEAD        api/courses/search .................................................................................... CourseController@search  
  GET|HEAD        api/courses/{courseId}/assignments ................................................................. AssignmentController@index  
  POST            api/courses/{courseId}/assignments ................................................................. AssignmentController@store  
  GET|HEAD        api/courses/{courseId}/forum ............................................................................ ForumController@index  
  POST            api/courses/{courseId}/forum ............................................................................ ForumController@store  
  GET|HEAD        api/courses/{courseId}/forum/analytics .............................................................. ForumController@analytics  
  GET|HEAD        api/courses/{courseId}/lessons ......................................................................... LessonController@index  
  POST            api/courses/{courseId}/lessons ......................................................................... LessonController@store  
  GET|HEAD        api/courses/{courseId}/reviews ......................................................................... ReviewController@index  
  POST            api/courses/{courseId}/reviews ......................................................................... ReviewController@store  
  GET|HEAD        api/courses/{courseId}/reviews/analytics ........................................................... ReviewController@analytics  
  GET|HEAD        api/courses/{id} ........................................................................................ CourseController@show  
  PUT             api/courses/{id} ...................................................................................... CourseController@update  
  DELETE          api/courses/{id} ..................................................................................... CourseController@destroy  
  GET|HEAD        api/courses/{id}/analytics ......................................................................... CourseController@analytics  
  POST            api/courses/{id}/enroll ............................................................................... CourseController@enroll  
  POST            api/courses/{id}/publish ............................................................................. CourseController@publish  
  GET|HEAD        api/courses/{id}/students ........................................................................... CourseController@students  
  DELETE          api/courses/{id}/unenroll ........................................................................... CourseController@unenroll  
  POST            api/courses/{id}/unpublish ......................................................................... CourseController@unpublish  
  GET|HEAD        api/curriculum-category ............................................ curriculum-category.index › CourseCategoryController@index  
  POST            api/curriculum-category ............................................ curriculum-category.store › CourseCategoryController@store  
  GET|HEAD        api/curriculum-category/{curriculum_category} ........................ curriculum-category.show › CourseCategoryController@show  
  PUT|PATCH       api/curriculum-category/{curriculum_category} .................... curriculum-category.update › CourseCategoryController@update  
  DELETE          api/curriculum-category/{curriculum_category} .................. curriculum-category.destroy › CourseCategoryController@destroy  
  GET|HEAD        api/dashboard .................................................................................................................  
  GET|HEAD        api/dashboard/admin ........................................................................ DashboardController@adminDashboard  
  GET|HEAD        api/dashboard/analytics ......................................................................... DashboardController@analytics  
  GET|HEAD        api/dashboard/instructor .............................................................. DashboardController@instructorDashboard  
  GET|HEAD        api/dashboard/student .................................................................... DashboardController@studentDashboard  
  POST            api/email/resend-code ................................................................... AuthController@resendVerificationCode  
  POST            api/email/resend-verification-code ...................................................... AuthController@resendVerificationCode  
  POST            api/email/send-code ....................................................................... AuthController@sendVerificationCode  
  POST            api/email/send-verification-code .......................................................... AuthController@sendVerificationCode  
  POST            api/email/verification-notification ...........................................................................................  
  POST            api/email/verify-code ...................................................................... AuthController@verifyEmailWithCode  
  POST            api/email/verify-with-code ................................................................. AuthController@verifyEmailWithCode  
  GET|HEAD        api/email/verify/{id}/{hash} .............................................................................. verification.verify  
  GET|HEAD        api/enrollments .................................................................................... EnrollmentController@index  
  POST            api/enrollments .................................................................................... EnrollmentController@store  
  GET|HEAD        api/enrollments/certificates ................................................................ EnrollmentController@certificates  
  GET|HEAD        api/enrollments/{id} ................................................................................ EnrollmentController@show  
  PUT             api/enrollments/{id} .............................................................................. EnrollmentController@update  
  DELETE          api/enrollments/{id} ............................................................................. EnrollmentController@destroy  
  POST            api/enrollments/{id}/complete ................................................................... EnrollmentController@complete  
  GET|HEAD        api/enrollments/{id}/progress ................................................................... EnrollmentController@progress  
  GET|HEAD        api/files/download/{id} .............................................................. files.download › FileController@download  
  GET|HEAD        api/files/list ....................................................................................... FileController@listFiles  
  POST            api/files/organize .................................................................................... FileController@organize  
  GET|HEAD        api/files/preview/{id} ................................................................. files.preview › FileController@preview  
  GET|HEAD        api/files/storage/stats ........................................................................ FileController@getStorageStats  
  POST            api/files/upload ........................................................................................ FileController@upload  
  DELETE          api/files/{id} .......................................................................................... FileController@delete  
  POST            api/files/{id}/share ..................................................................................... FileController@share  
  POST            api/forgot-password ................................................................ PasswordResetController@sendResetLinkEmail  
  PUT             api/forum/posts/{id} ............................................................................... ForumController@updatePost  
  DELETE          api/forum/posts/{id} .............................................................................. ForumController@destroyPost  
  POST            api/forum/posts/{id}/like ............................................................................ ForumController@likePost  
  POST            api/forum/posts/{id}/solution .................................................................... ForumController@markSolution  
  GET|HEAD        api/forum/topics/{id} .................................................................................... ForumController@show  
  PUT             api/forum/topics/{id} .................................................................................. ForumController@update  
  DELETE          api/forum/topics/{id} ................................................................................. ForumController@destroy  
  POST            api/forum/topics/{id}/posts ......................................................................... ForumController@storePost  
  POST            api/forum/topics/{id}/subscribe ..................................................................... ForumController@subscribe  
  DELETE          api/forum/topics/{id}/unsubscribe ................................................................. ForumController@unsubscribe  
  GET|HEAD        api/grading/analytics ............................................................................. GradingController@analytics  
  POST            api/grading/bulk-grade ............................................................................ GradingController@bulkGrade  
  POST            api/grading/comments ..................................................................... GradingController@addGradingComments  
  GET|HEAD        api/grading/courses/{courseId} ................................................................. GradingController@courseGrades  
  POST            api/grading/export ............................................................................. GradingController@exportGrades  
  GET|HEAD        api/grading/grade-history/{studentId}/{courseId} ............................................... GradingController@gradeHistory  
  GET|HEAD        api/grading/gradebook/{courseId} .................................................................. GradingController@gradebook  
  GET|HEAD        api/grading/reports/{courseId} ...................................................................... GradingController@reports  
  GET|HEAD        api/grading/students/{studentId} .............................................................. GradingController@studentGrades  
  PUT             api/grading/weights/{courseId} ........................................................... GradingController@updateGradeWeights  
  GET|HEAD        api/instructor/courses ........................................................................................................  
  GET|HEAD        api/language/current ...................................................................... LanguageController@getCurrentLocale  
  GET|HEAD        api/language/info ....................................................................... LanguageController@getAllLanguageInfo  
  GET|HEAD        api/language/info/{locale} ................................................................. LanguageController@getLanguageInfo  
  POST            api/language/set ................................................................................. LanguageController@setLocale  
  GET|HEAD        api/language/supported ............................................................... LanguageController@getSupportedLanguages  
  GET|HEAD        api/language/translations .................................................................. LanguageController@getTranslations  
  GET|HEAD        api/language/translations/{locale} ................................................. LanguageController@getTranslationsByLocale  
  GET|HEAD        api/language/user .......................................................................... LanguageController@getUserLanguage  
  POST            api/language/user/set ...................................................................... LanguageController@setUserLanguage  
  GET|HEAD        api/learning-paths ............................................................................... LearningPathController@index  
  POST            api/learning-paths ............................................................................... LearningPathController@store  
  GET|HEAD        api/learning-paths/my/paths .................................................................... LearningPathController@myPaths  
  GET|HEAD        api/learning-paths/{id} ........................................................................... LearningPathController@show  
  PUT             api/learning-paths/{id} ......................................................................... LearningPathController@update  
  DELETE          api/learning-paths/{id} ........................................................................ LearningPathController@destroy  
  GET|HEAD        api/learning-paths/{id}/analytics ............................................................ LearningPathController@analytics  
  POST            api/learning-paths/{id}/enroll .................................................................. LearningPathController@enroll  
  GET|HEAD        api/learning-paths/{id}/progress .......................................................... LearningPathController@pathProgress  
  POST            api/learning-paths/{id}/publish ................................................................ LearningPathController@publish  
  DELETE          api/learning-paths/{id}/unenroll .............................................................. LearningPathController@unenroll  
  POST            api/learning-paths/{id}/unpublish ............................................................ LearningPathController@unpublish  
  GET|HEAD        api/lessons/{id} ........................................................................................ LessonController@show  
  PUT             api/lessons/{id} ...................................................................................... LessonController@update  
  DELETE          api/lessons/{id} ..................................................................................... LessonController@destroy  
  GET|HEAD        api/lessons/{id}/attachments ..................................................................... LessonController@attachments  
  POST            api/lessons/{id}/complete ........................................................................... LessonController@complete  
  GET|HEAD        api/lessons/{id}/progress ........................................................................... LessonController@progress  
  POST            api/lessons/{id}/watch-time ................................................................... LessonController@trackWatchTime  
  GET|HEAD        api/lessons/{lessonId}/quizzes ........................................................................... QuizController@index  
  POST            api/lessons/{lessonId}/quizzes ........................................................................... QuizController@store  
  POST            api/localization/convert-currency ...................................................... LocalizationController@convertCurrency  
  GET|HEAD        api/localization/currencies ..................................................... LocalizationController@getSupportedCurrencies  
  GET|HEAD        api/localization/languages ....................................................... LocalizationController@getSupportedLanguages  
  GET|HEAD        api/localization/preferences ............................................................ LocalizationController@getPreferences  
  PUT             api/localization/preferences ......................................................... LocalizationController@updatePreferences  
  GET|HEAD        api/localization/timezones ....................................................... LocalizationController@getSupportedTimezones  
  POST            api/localization/translate ............................................................ LocalizationController@translateContent  
  GET|HEAD        api/localization/translations .......................................................... LocalizationController@getTranslations  
  POST            api/login ................................................................................................ AuthController@login  
  POST            api/logout .............................................................................................. AuthController@logout  
  GET|HEAD        api/my-badges ...................................................................................... BadgeController@userBadges  
  GET|HEAD        api/notifications ................................................................................ NotificationController@index  
  GET|HEAD        api/notifications/analytics ............................................................... NotificationController@getAnalytics  
  POST            api/notifications/broadcast ...................................................... NotificationController@broadcastNotification  
  GET|HEAD        api/notifications/preferences ........................................................... NotificationController@getPreferences  
  PUT             api/notifications/preferences ........................................................ NotificationController@updatePreferences  
  PUT             api/notifications/read-all ............................................................... NotificationController@markAllAsRead  
  POST            api/notifications/send ................................................................ NotificationController@sendNotification  
  DELETE          api/notifications/{id} .......................................................................... NotificationController@delete  
  PUT             api/notifications/{id}/read ................................................................. NotificationController@markAsRead  
  GET|HEAD        api/payments/callback/{gateway} ................................................. payment.callback › PaymentController@callback  
  GET|HEAD        api/payments/cancel/{gateway} ....................................................... payment.cancel › PaymentController@cancel  
  POST            api/payments/deposit ................................................................ PaymentController@initializeWalletDeposit  
  GET|HEAD        api/payments/gateways .............................................................................. PaymentController@gateways  
  GET|HEAD        api/payments/history ................................................................................ PaymentController@history  
  POST            api/payments/purchase-course ........................................................ PaymentController@initializeCoursePayment  
  GET|HEAD        api/payments/success/{gateway} .................................................... payment.success › PaymentController@success  
  POST            api/payments/webhook/{gateway} .................................................... payment.webhook › PaymentController@webhook  
  GET|HEAD        api/payments/{id} ...................................................................................... PaymentController@show  
  GET|HEAD        api/progress/achievements .............................................................. ProgressController@achievementProgress  
  GET|HEAD        api/progress/certificates ............................................................ ProgressController@availableCertificates  
  GET|HEAD        api/progress/courses ........................................................................ ProgressController@courseProgress  
  POST            api/progress/generate-cert ............................................................. ProgressController@generateCertificate  
  GET|HEAD        api/progress/lessons ........................................................................ ProgressController@lessonProgress  
  GET|HEAD        api/progress/overall ....................................................................... ProgressController@overallProgress  
  GET|HEAD        api/progress/streaks ........................................................................ ProgressController@streakProgress  
  POST            api/progress/update ......................................................................... ProgressController@updateProgress  
  GET|HEAD        api/quizzes/{id} .......................................................................................... QuizController@show  
  PUT             api/quizzes/{id} ........................................................................................ QuizController@update  
  DELETE          api/quizzes/{id} ....................................................................................... QuizController@destroy  
  GET|HEAD        api/quizzes/{id}/analytics ........................................................................... QuizController@analytics  
  GET|HEAD        api/quizzes/{id}/results ............................................................................... QuizController@results  
  POST            api/quizzes/{id}/start ............................................................................ QuizController@startAttempt  
  POST            api/quizzes/{id}/submit ............................................................................. QuizController@submitQuiz  
  GET|HEAD        api/realtime/activity ......................................................... RealtimeController@getCurrentUserActivityStatus  
  GET|HEAD        api/realtime/activity/{userId} ....................................................... RealtimeController@getUserActivityStatus  
  GET|HEAD        api/realtime/course/{courseId}/users/online ......................................... RealtimeController@getOnlineUsersInCourse  
  GET|HEAD        api/realtime/course/{courseId}/users/online/count ................................... RealtimeController@getOnlineCountInCourse  
  POST            api/realtime/offline ....................................................................... RealtimeController@markUserOffline  
  POST            api/realtime/online ......................................................................... RealtimeController@markUserOnline  
  POST            api/realtime/typing .................................................................... RealtimeController@sendTypingIndicator  
  GET|HEAD        api/realtime/users/online ................................................................... RealtimeController@getOnlineUsers  
  GET|HEAD        api/realtime/users/online/count ............................................................. RealtimeController@getOnlineCount  
  GET|HEAD        api/recommendations ............................................................... RecommendationController@getRecommendations  
  GET|HEAD        api/recommendations/analytics ........................................................... RecommendationController@getAnalytics  
  GET|HEAD        api/recommendations/content ................................................ RecommendationController@getContentRecommendations  
  GET|HEAD        api/recommendations/courses/{courseId} ................................. RecommendationController@getCourseBasedRecommendations  
  GET|HEAD        api/recommendations/instructors ......................................... RecommendationController@getInstructorRecommendations  
  GET|HEAD        api/recommendations/learning-paths .................................... RecommendationController@getLearningPathRecommendations  
  PUT             api/recommendations/preferences .................................................... RecommendationController@updatePreferences  
  POST            api/register .......................................................................................... AuthController@register  
  POST            api/reports/academic .................................................................. ReportController@generateAcademicReport  
  POST            api/reports/content .................................................................... ReportController@generateContentReport  
  POST            api/reports/financial ................................................................ ReportController@generateFinancialReport  
  GET|HEAD        api/reports/history ......................................................................... ReportController@getReportHistory  
  POST            api/reports/schedule .......................................................................... ReportController@scheduleReport  
  GET|HEAD        api/reports/scheduled .................................................................... ReportController@getScheduledReports  
  GET|HEAD        api/reports/types ............................................................................. ReportController@getReportTypes  
  POST            api/reports/user .......................................................................... ReportController@generateUserReport  
  POST            api/reset-password .............................................................................. PasswordResetController@reset  
  GET|HEAD        api/reviews/moderate ................................................................................ ReviewController@moderate  
  GET|HEAD        api/reviews/my-reviews ........................................................................... ReviewController@userReviews  
  GET|HEAD        api/reviews/{id} ........................................................................................ ReviewController@show  
  PUT             api/reviews/{id} ...................................................................................... ReviewController@update  
  DELETE          api/reviews/{id} ..................................................................................... ReviewController@destroy  
  POST            api/reviews/{id}/approve ............................................................................. ReviewController@approve  
  POST            api/reviews/{id}/helpful ......................................................................... ReviewController@markHelpful  
  POST            api/reviews/{id}/reject ............................................................................... ReviewController@reject  
  GET|HEAD        api/search ...................................................................................... SearchController@globalSearch  
  GET|HEAD        api/search/content ............................................................................. SearchController@contentSearch  
  GET|HEAD        api/search/courses .............................................................................. SearchController@courseSearch  
  GET|HEAD        api/search/filters ................................................................................ SearchController@getFilters  
  GET|HEAD        api/search/global ............................................................................... SearchController@globalSearch  
  GET|HEAD        api/search/suggestions ........................................................................ SearchController@getSuggestions  
  GET|HEAD        api/search/users .................................................................................. SearchController@userSearch  
  GET|HEAD        api/settings .......................................................................................... SettingController@index  
  PUT             api/settings ..................................................................................... SettingController@updateBulk  
  GET|HEAD        api/settings/email/config .................................................................. SettingController@getEmailSettings  
  GET|HEAD        api/settings/features/toggles ............................................................. SettingController@getFeatureToggles  
  GET|HEAD        api/settings/payment/config .............................................................. SettingController@getPaymentSettings  
  GET|HEAD        api/settings/public ....................................................................... SettingController@getPublicSettings  
  POST            api/settings/reset .................................................................................... SettingController@reset  
  GET|HEAD        api/settings/{key} ..................................................................................... SettingController@show  
  PUT             api/settings/{key} ................................................................................... SettingController@update  
  PUT             api/submissions/{id}/grade ............................................................... AssignmentController@gradeSubmission  
  GET|HEAD        api/term .................................................................................... term.index › TermController@index  
  POST            api/term .................................................................................... term.store › TermController@store  
  GET|HEAD        api/term/{term} ............................................................................... term.show › TermController@show  
  PUT|PATCH       api/term/{term} ........................................................................... term.update › TermController@update  
  DELETE          api/term/{term} ......................................................................... term.destroy › TermController@destroy  
  GET|HEAD        api/topic ................................................................................. topic.index › TopicController@index  
  POST            api/topic ................................................................................. topic.store › TopicController@store  
  GET|HEAD        api/topic/{topic} ........................................................................... topic.show › TopicController@show  
  PUT|PATCH       api/topic/{topic} ....................................................................... topic.update › TopicController@update  
  DELETE          api/topic/{topic} ..................................................................... topic.destroy › TopicController@destroy  
  GET|HEAD        api/user .................................................................................................. AuthController@user  
  GET|HEAD        api/users/achievements ............................................................................ UserController@achievements  
  POST            api/users/change-password ....................................................................... UserController@changePassword  
  GET|HEAD        api/users/dashboard .................................................................................. UserController@dashboard  
  GET|HEAD        api/users/learning-stats ......................................................................... UserController@learningStats  
  GET|HEAD        api/users/notifications .......................................................................... UserController@notifications  
  POST            api/users/notifications/read ............................................................. UserController@markNotificationsRead  
  PUT             api/users/preferences ........................................................................ UserController@updatePreferences  
  GET|HEAD        api/users/profile ...................................................................................... UserController@profile  
  PUT             api/users/profile ................................................................................ UserController@updateProfile  
  GET|HEAD        api/users/{userId}/badges .......................................................................... BadgeController@userBadges  
  POST            api/videos ......................................................................... VideoStreamingController@createVideoStream  
  GET|HEAD        api/videos/top/videos ................................................................... VideoStreamingController@getTopVideos  
  GET|HEAD        api/videos/user/downloads ........................................................... VideoStreamingController@getUserDownloads  
  GET|HEAD        api/videos/{videoStreamId} ............................................................ VideoStreamingController@getVideoStream  
  GET|HEAD        api/videos/{videoStreamId}/analytics ............................................... VideoStreamingController@getVideoAnalytics  
  POST            api/videos/{videoStreamId}/download ............................................ VideoStreamingController@createDownloadRequest  
  POST            api/videos/{videoStreamId}/process ................................................ VideoStreamingController@processVideoStream  
  POST            api/videos/{videoStreamId}/view ...................................................... VideoStreamingController@recordVideoView  
  POST            api/videos/{videoStreamId}/watch-time ................................................ VideoStreamingController@updateWatchTime  
  GET|HEAD        api/wallet ............................................................................................. WalletController@index  
  POST            api/wallet/check-affordability ............................................................ WalletController@checkAffordability  
  POST            api/wallet/claim-login-reward ............................................................... WalletController@claimLoginReward  
  POST            api/wallet/purchase-course .................................................................... WalletController@purchaseCourse  
  GET|HEAD        api/wallet/rewards ................................................................................... WalletController@rewards  
  GET|HEAD        api/wallet/transactions ......................................................................... WalletController@transactions  
  POST            api/wallet/transfer ................................................................................. WalletController@transfer  
  GET|HEAD        application ...................................................................................................................  
  GET|HEAD        becometutor ...................................................................................................................  
  GET|HEAD        categories ....................................................................................................................  
  GET|HEAD        chatroom ......................................................................................................................  
  GET|HEAD        contact .......................................................................................................................  
  GET|HEAD        createannouncement ............................................................................................................  
  GET|HEAD        createsubject .................................................................................................................  
  GET|HEAD        curriculum ....................................................................................................................  
  GET|HEAD        curriculum-categories .........................................................................................................  
  GET|HEAD        dashboard .....................................................................................................................  
  GET|HEAD        editsubject ...................................................................................................................  
  GET|HEAD        edituser ......................................................................................................................  
  GET|HEAD        enroll ........................................................................................................................  
  GET|HEAD        feedback ......................................................................................................................  
  GET|HEAD        forgotpassword ................................................................................................................  
  GET|HEAD        instructor ....................................................................................................................  
  GET|HEAD        instructors ...................................................................................................................  
  GET|HEAD        kokoplay ......................................................................................................................  
  GET|HEAD        levels ........................................................................................................................  
  GET|HEAD        lms ...........................................................................................................................  
  GET|HEAD        login .........................................................................................................................  
  GET|HEAD        market ........................................................................................................................  
  GET|HEAD        pricing .......................................................................................................................  
  GET|HEAD        profile .......................................................................................................................  
  GET|HEAD        profiles ......................................................................................................................  
  GET|HEAD        publish .......................................................................................................................  
  GET|HEAD        rating ........................................................................................................................  
  GET|HEAD        register ......................................................................................................................  
  GET|HEAD        resetpassword .................................................................................................................  
  GET|HEAD        sanctum/csrf-cookie ......................................... sanctum.csrf-cookie › Laravel\Sanctum › CsrfCookieController@show  
  GET|HEAD        sms ...........................................................................................................................  
  GET|HEAD        stem ..........................................................................................................................  
  GET|HEAD        stemregister ..................................................................................................................  
  GET|HEAD        stemregister2 .................................................................................................................  
  GET|HEAD        storage/{path} .................................................................................................. storage.local  
  GET|HEAD        student .......................................................................................................................  
  GET|HEAD        students ......................................................................................................................  
  GET|HEAD        subject-categories ............................................................................................................  
  GET|HEAD        subjectchart ..................................................................................................................  
  GET|HEAD        subjectmedia ..................................................................................................................  
  GET|HEAD        subjects ......................................................................................................................  
  GET|HEAD        subjectselect .................................................................................................................  
  GET|HEAD        subscription ..................................................................................................................  
  GET|HEAD        template ......................................................................................................................  
  GET|HEAD        terms .........................................................................................................................  
  GET|HEAD        termsubject ...................................................................................................................  
  GET|HEAD        transactions ..................................................................................................................  
  GET|HEAD        up ............................................................................................................................  
  GET|HEAD        useractivity ..................................................................................................................  
  GET|HEAD        userclass .....................................................................................................................  
  GET|HEAD        userkoodies ...................................................................................................................  
  GET|HEAD        userkoodiesaudio ..............................................................................................................  
  GET|HEAD        users .........................................................................................................................  
  GET|HEAD        usersdashboard ................................................................................................................  
  GET|HEAD        usersubject ...................................................................................................................  
  GET|HEAD        verify ........................................................................................................................  
  GET|HEAD        wallet ........................................................................................................................  
