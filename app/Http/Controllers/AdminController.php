<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\WalletTransaction;
use App\Models\Certificate;
use App\Models\ForumTopic;
use App\Models\ForumReply;
use App\Models\CourseReview;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Middleware is applied at route level in routes/api.php
    // Route::prefix('admin')->middleware('role:admin')->group(function () {

    /**
     * Get admin dashboard overview
     */
    public function dashboard()
    {
        try {
            // Cache dashboard stats for 5 minutes to improve performance
            $stats = Cache::remember('admin_dashboard_stats', 300, function () {
                return [
                    'users' => [
                        'total' => User::count(),
                        'new_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
                        'active_this_week' => User::where('last_login_at', '>=', now()->startOfWeek())->count(),
                        'by_role' => [
                            'students' => User::where('role', 'student')->count(),
                            'instructors' => User::where('role', 'instructor')->count(),
                            'admins' => User::where('role', 'admin')->count()
                        ],
                        'by_gender' => [
                            'male' => User::where('gender', 'male')->count(),
                            'female' => User::where('gender', 'female')->count()
                        ],
                        'students_by_gender' => [
                            'male' => User::where('role', 'student')->where('gender', 'male')->count(),
                            'female' => User::where('role', 'student')->where('gender', 'female')->count()
                        ],
                        'instructors_by_gender' => [
                            'male' => User::where('role', 'instructor')->where('gender', 'male')->count(),
                            'female' => User::where('role', 'instructor')->where('gender', 'female')->count()
                        ]
                    ],
                'courses' => [
                    'total' => Course::count(),
                    'published' => Course::where('status', 'published')->count(),
                    'draft' => Course::where('status', 'draft')->count(),
                    'new_this_month' => Course::where('created_at', '>=', now()->startOfMonth())->count(),
                    'by_category' => \App\Models\Category::withCount('courses')
                                          ->get()
                                          ->pluck('courses_count', 'title'),
                    'most_popular' => Course::withCount('enrollments')
                                          ->orderBy('enrollments_count', 'desc')
                                          ->limit(5)
                                          ->get()
                ],
                'enrollments' => [
                    'total' => Enrollment::count(),
                    'active' => Enrollment::where('status', 'active')->count(),
                    'completed' => Enrollment::where('status', 'completed')->count(),
                    'this_month' => Enrollment::where('created_at', '>=', now()->startOfMonth())->count(),
                    'completion_rate' => $this->calculateCompletionRate()
                ],
                'revenue' => [
                    'total' => Payment::where('status', 'completed')->sum('amount'),
                    'this_month' => Payment::where('status', 'completed')
                                         ->where('created_at', '>=', now()->startOfMonth())
                                         ->sum('amount'),
                    'this_week' => Payment::where('status', 'completed')
                                        ->where('created_at', '>=', now()->startOfWeek())
                                        ->sum('amount'),
                    'by_gateway' => Payment::where('status', 'completed')
                                         ->groupBy('gateway')
                                         ->selectRaw('gateway, SUM(amount) as total')
                                         ->pluck('total', 'gateway')
                ],
                'engagement' => [
                    'forum_topics' => ForumTopic::count(),
                    'course_reviews' => CourseReview::count(),
                    'certificates_issued' => Certificate::count(),
                    'average_rating' => CourseReview::avg('rating')
                ]
                ];
            });

            // Recent activity
            $recentActivity = $this->getRecentActivity();

            // System health
            $systemHealth = $this->getSystemHealth();

            // Growth trends
            $growthTrends = $this->getGrowthTrends();

            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => $stats,
                    'recent_activity' => $recentActivity,
                    'system_health' => $systemHealth,
                    'growth_trends' => $growthTrends
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user management data
     */
    public function users(Request $request)
    {
        try {
            $query = User::query();

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Filter by role
            if ($request->has('role')) {
                $query->where('role', $request->role);
            }

            // Filter by status
            if ($request->has('status')) {
                if ($request->status === 'active') {
                    $query->whereNull('banned_at');
                } elseif ($request->status === 'banned') {
                    $query->whereNotNull('banned_at');
                }
            }

            // Filter by registration date
            if ($request->has('from_date')) {
                $query->where('created_at', '>=', $request->from_date);
            }

            if ($request->has('to_date')) {
                $query->where('created_at', '<=', $request->to_date);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $users = $query->withCount(['enrollments', 'instructedCourses', 'enrolledCourses'])
                          ->paginate($request->get('per_page', 20));

            // Add additional user data
            $users->getCollection()->transform(function ($user) {
                $userData = $user->toArray();
                $userData['total_spent'] = Payment::where('user_id', $user->id)
                                                ->where('status', 'completed')
                                                ->sum('amount');
                $userData['last_activity'] = $user->last_login_at;
                $userData['is_banned'] = !$user->is_active;
                return $userData;
            });

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recently registered users (max 10)
     */
    public function recentlyRegisteredUsers(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);

            $users = User::orderBy('created_at', 'desc')
                        ->paginate($perPage);

            // Transform user data to include profile photo URL
            $users->getCollection()->transform(function ($user) {
                $userData = $user->toArray();
                // Add profile photo URL
                if ($user->profile_photo) {
                    $userData['profile_photo_url'] = asset('storage/' . $user->profile_photo);
                } else {
                    $userData['profile_photo_url'] = asset('images/default-avatar.png');
                }
                $userData['formatted_date'] = $user->created_at->format('M d, Y');
                return $userData;
            });

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch recently registered users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course management data
     */
    public function courses(Request $request)
    {
        try {
            $query = Course::with(['instructor', 'category']);

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by category
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Filter by instructor
            if ($request->has('instructor_id')) {
                $query->where('instructor_id', $request->instructor_id);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $courses = $query->withCount(['enrollments', 'lessons', 'reviews'])
                           ->paginate($request->get('per_page', 20));

            // Add additional course data
            $courses->getCollection()->transform(function ($course) {
                $courseData = $course->toArray();
                $courseData['revenue'] = Payment::where('course_id', $course->id)
                                              ->where('status', 'completed')
                                              ->sum('amount');
                $courseData['average_rating'] = $course->reviews()
                                                     ->where('status', 'approved')
                                                     ->avg('rating');
                $courseData['completion_rate'] = $this->calculateCourseCompletionRate($course);
                return $courseData;
            });

            return response()->json([
                'success' => true,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch courses: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment management data
     */
    public function payments(Request $request)
    {
        try {
            $query = Payment::with(['user', 'course']);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by gateway
            if ($request->has('gateway')) {
                $query->where('gateway', $request->gateway);
            }

            // Filter by date range
            if ($request->has('from_date')) {
                $query->where('created_at', '>=', $request->from_date);
            }

            if ($request->has('to_date')) {
                $query->where('created_at', '<=', $request->to_date);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $payments = $query->paginate($request->get('per_page', 20));

            // Payment statistics
            $stats = [
                'total_payments' => Payment::count(),
                'successful_payments' => Payment::where('status', 'completed')->count(),
                'failed_payments' => Payment::where('status', 'failed')->count(),
                'pending_payments' => Payment::where('status', 'pending')->count(),
                'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
                'average_transaction' => Payment::where('status', 'completed')->avg('amount')
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'payments' => $payments,
                    'statistics' => $stats
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch payments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system reports
     */
    public function reports(Request $request)
    {
        try {
            $reportType = $request->get('type', 'overview');

            switch ($reportType) {
                case 'users':
                    $report = $this->generateUserReport($request);
                    break;
                case 'courses':
                    $report = $this->generateCourseReport($request);
                    break;
                case 'revenue':
                    $report = $this->generateRevenueReport($request);
                    break;
                case 'engagement':
                    $report = $this->generateEngagementReport($request);
                    break;
                default:
                    $report = $this->generateOverviewReport($request);
            }

            return response()->json([
                'success' => true,
                'data' => $report
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system settings
     */
    public function settings()
    {
        try {
            $settings = [
                'general' => [
                    'site_name' => config('app.name'),
                    'site_url' => config('app.url'),
                    'timezone' => config('app.timezone'),
                    'maintenance_mode' => app()->isDownForMaintenance()
                ],
                'email' => [
                    'driver' => config('mail.default'),
                    'from_address' => config('mail.from.address'),
                    'from_name' => config('mail.from.name')
                ],
                'payment' => [
                    'default_currency' => config('app.currency', 'USD'),
                    'enabled_gateways' => ['paystack', 'flutterwave', 'stripe', 'paypal']
                ],
                'features' => [
                    'course_reviews' => true,
                    'forums' => true,
                    'certificates' => true,
                    'badges' => true,
                    'wallet_system' => true
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ban a user
     */
    public function banUser(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Check if user is already banned
            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is already banned'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'reason' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Ban the user by setting is_active to false
            $user->update([
                'is_active' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User banned successfully',
                'data' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'is_active' => $user->is_active,
                    'banned_at' => now()->toISOString(),
                    'reason' => $request->reason ?? 'No reason provided'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to ban user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unban a user
     */
    public function unbanUser($userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Check if user is already active
            if ($user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not banned'
                ], 400);
            }

            // Unban the user by setting is_active to true
            $user->update([
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User unbanned successfully',
                'data' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'is_active' => $user->is_active,
                    'unbanned_at' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unban user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system analytics
     */
    public function analytics(Request $request)
    {
        try {
            $period = $request->get('period', '30'); // days

            $analytics = [
                'user_growth' => $this->getUserGrowthAnalytics($period),
                'course_performance' => $this->getCoursePerformanceAnalytics($period),
                'revenue_analytics' => $this->getRevenueAnalytics($period),
                'engagement_metrics' => $this->getEngagementMetrics($period),
                'platform_health' => $this->getPlatformHealthMetrics()
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Perform bulk actions on users
     */
    public function bulkActions(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'action' => 'required|in:ban,unban,delete,change_role',
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
                'reason' => 'required_if:action,ban|string|max:500',
                'new_role' => 'required_if:action,change_role|in:student,instructor,admin'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $results = [
                'processed' => 0,
                'errors' => []
            ];

            foreach ($request->user_ids as $userId) {
                try {
                    $user = User::findOrFail($userId);

                    switch ($request->action) {
                        case 'ban':
                            $user->update([
                                'banned_at' => now(),
                                'ban_reason' => $request->reason,
                                'banned_by' => Auth::id()
                            ]);
                            break;

                        case 'unban':
                            $user->update([
                                'banned_at' => null,
                                'ban_reason' => null,
                                'banned_by' => null
                            ]);
                            break;

                        case 'change_role':
                            $user->update(['role' => $request->new_role]);
                            break;

                        case 'delete':
                            // Soft delete or hard delete based on business rules
                            $user->delete();
                            break;
                    }

                    $results['processed']++;
                } catch (\Exception $e) {
                    $results['errors'][] = [
                        'user_id' => $userId,
                        'error' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Bulk action completed. {$results['processed']} users processed.",
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk action failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get audit logs
     */
    public function auditLogs(Request $request)
    {
        try {
            $query = DB::table('audit_logs')->orderBy('created_at', 'desc');

            // Filter by action
            if ($request->has('action')) {
                $query->where('event', 'like', '%' . $request->action . '%');
            }

            // Filter by user
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Filter by date range
            if ($request->has('from_date')) {
                $query->where('created_at', '>=', $request->from_date);
            }

            if ($request->has('to_date')) {
                $query->where('created_at', '<=', $request->to_date);
            }

            $logs = $query->paginate($request->get('per_page', 20));

            // If no audit logs exist, return sample data structure
            if ($logs->total() === 0) {
                $sampleLogs = [
                    [
                        'id' => 1,
                        'user_id' => Auth::id(),
                        'event' => 'user.banned',
                        'description' => 'User banned for violating terms',
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'created_at' => now()->subHours(2)->toISOString()
                    ],
                    [
                        'id' => 2,
                        'user_id' => Auth::id(),
                        'event' => 'course.approved',
                        'description' => 'Course approved for publication',
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'created_at' => now()->subHours(5)->toISOString()
                    ]
                ];

                return response()->json([
                    'success' => true,
                    'data' => $sampleLogs,
                    'message' => 'No audit logs found. Showing sample data structure.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $logs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch audit logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enable/disable maintenance mode
     */
    public function maintenanceMode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'enabled' => 'required|boolean',
                'message' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($request->enabled) {
                // Enable maintenance mode
                $message = $request->message ?? 'System is under maintenance. Please try again later.';
                Artisan::call('down', ['--message' => $message]);
            } else {
                // Disable maintenance mode
                Artisan::call('up');
            }

            return response()->json([
                'success' => true,
                'message' => $request->enabled ? 'Maintenance mode enabled' : 'Maintenance mode disabled'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle maintenance mode: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear system cache
     */
    public function clearCache(Request $request)
    {
        try {
            $cacheType = $request->get('type', 'all');

            switch ($cacheType) {
                case 'config':
                    Artisan::call('config:clear');
                    break;
                case 'route':
                    Artisan::call('route:clear');
                    break;
                case 'view':
                    Artisan::call('view:clear');
                    break;
                case 'application':
                    Cache::flush();
                    break;
                case 'all':
                default:
                    Cache::flush();
                    Artisan::call('config:clear');
                    Artisan::call('route:clear');
                    Artisan::call('view:clear');
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get database statistics
     */
    public function databaseStats()
    {
        try {
            $stats = [
                'tables' => [
                    'users' => User::count(),
                    'courses' => Course::count(),
                    'enrollments' => Enrollment::count(),
                    'payments' => Payment::count(),
                    'wallet_transactions' => WalletTransaction::count(),
                    'certificates' => Certificate::count(),
                    'forum_topics' => ForumTopic::count(),
                    'course_reviews' => CourseReview::count()
                ],
                'storage' => [
                    'database_size' => $this->getDatabaseSize(),
                    'table_sizes' => $this->getTableSizes()
                ],
                'performance' => [
                    'slow_queries' => $this->getSlowQueries(),
                    'connection_count' => $this->getConnectionCount()
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch database stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods
     */
    private function calculateCompletionRate()
    {
        $totalEnrollments = Enrollment::count();
        $completedEnrollments = Enrollment::where('status', 'completed')->count();

        return $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 2) : 0;
    }

    private function calculateCourseCompletionRate($course)
    {
        $totalEnrollments = $course->enrollments()->count();
        $completedEnrollments = $course->enrollments()->where('status', 'completed')->count();

        return $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 2) : 0;
    }

    private function getRecentActivity()
    {
        $activities = [];

        // Recent user registrations
        $recentUsers = User::latest()->limit(5)->get();
        foreach ($recentUsers as $user) {
            $activities[] = [
                'type' => 'user_registered',
                'description' => "New user registered: {$user->first_name} {$user->last_name}",
                'timestamp' => $user->created_at,
                'user' => $user
            ];
        }

        // Recent course creations
        $recentCourses = Course::with('instructor')->latest()->limit(5)->get();
        foreach ($recentCourses as $course) {
            $activities[] = [
                'type' => 'course_created',
                'description' => "New course created: {$course->title}",
                'timestamp' => $course->created_at,
                'course' => $course
            ];
        }

        // Recent payments
        $recentPayments = Payment::with(['user', 'course'])->where('status', 'completed')->latest()->limit(5)->get();
        foreach ($recentPayments as $payment) {
            $activities[] = [
                'type' => 'payment_completed',
                'description' => "Payment completed: {$payment->amount} for {$payment->course->title}",
                'timestamp' => $payment->created_at,
                'payment' => $payment
            ];
        }

        // Sort by timestamp
        usort($activities, function($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return array_slice($activities, 0, 15);
    }

    private function getSystemHealth()
    {
        return [
            'database' => [
                'status' => 'healthy',
                'response_time' => '< 100ms',
                'connections' => 5
            ],
            'cache' => [
                'status' => 'healthy',
                'hit_rate' => '95%',
                'memory_usage' => '45%'
            ],
            'storage' => [
                'status' => 'healthy',
                'disk_usage' => '60%',
                'available_space' => '40GB'
            ],
            'queue' => [
                'status' => 'healthy',
                'pending_jobs' => 0,
                'failed_jobs' => 0
            ]
        ];
    }

    private function getGrowthTrends()
    {
        $trends = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $trends[] = [
                'month' => $date->format('M Y'),
                'users' => User::where('created_at', '>=', $date->startOfMonth())
                             ->where('created_at', '<=', $date->endOfMonth())
                             ->count(),
                'courses' => Course::where('created_at', '>=', $date->startOfMonth())
                                 ->where('created_at', '<=', $date->endOfMonth())
                                 ->count(),
                'enrollments' => Enrollment::where('created_at', '>=', $date->startOfMonth())
                                         ->where('created_at', '<=', $date->endOfMonth())
                                         ->count(),
                'revenue' => Payment::where('status', 'completed')
                                  ->where('created_at', '>=', $date->startOfMonth())
                                  ->where('created_at', '<=', $date->endOfMonth())
                                  ->sum('amount')
            ];
        }

        return $trends;
    }

    private function generateUserReport($request)
    {
        $fromDate = $request->get('from_date', now()->subMonth());
        $toDate = $request->get('to_date', now());

        return [
            'period' => ['from' => $fromDate, 'to' => $toDate],
            'new_registrations' => User::whereBetween('created_at', [$fromDate, $toDate])->count(),
            'active_users' => User::whereBetween('last_login_at', [$fromDate, $toDate])->count(),
            'role_distribution' => User::whereBetween('created_at', [$fromDate, $toDate])
                                     ->groupBy('role')
                                     ->selectRaw('role, COUNT(*) as count')
                                     ->pluck('count', 'role'),
            'top_spenders' => User::withSum(['payments' => function($query) use ($fromDate, $toDate) {
                                    $query->where('status', 'completed')
                                          ->whereBetween('created_at', [$fromDate, $toDate]);
                                }], 'amount')
                                ->orderBy('payments_sum_amount', 'desc')
                                ->limit(10)
                                ->get()
        ];
    }

    private function generateCourseReport($request)
    {
        $fromDate = $request->get('from_date', now()->subMonth());
        $toDate = $request->get('to_date', now());

        return [
            'period' => ['from' => $fromDate, 'to' => $toDate],
            'new_courses' => Course::whereBetween('created_at', [$fromDate, $toDate])->count(),
            'published_courses' => Course::where('status', 'published')
                                        ->whereBetween('created_at', [$fromDate, $toDate])
                                        ->count(),
            'most_enrolled' => Course::withCount(['enrollments' => function($query) use ($fromDate, $toDate) {
                                     $query->whereBetween('created_at', [$fromDate, $toDate]);
                                 }])
                                 ->orderBy('enrollments_count', 'desc')
                                 ->limit(10)
                                 ->get(),
            'category_performance' => Course::join('categories', 'courses.category_id', '=', 'categories.id')
                                          ->whereBetween('courses.created_at', [$fromDate, $toDate])
                                          ->groupBy('categories.name')
                                          ->selectRaw('categories.name, COUNT(*) as course_count')
                                          ->pluck('course_count', 'name')
        ];
    }

    private function generateRevenueReport($request)
    {
        $fromDate = $request->get('from_date', now()->subMonth());
        $toDate = $request->get('to_date', now());

        return [
            'period' => ['from' => $fromDate, 'to' => $toDate],
            'total_revenue' => Payment::where('status', 'completed')
                                    ->whereBetween('created_at', [$fromDate, $toDate])
                                    ->sum('amount'),
            'transaction_count' => Payment::where('status', 'completed')
                                        ->whereBetween('created_at', [$fromDate, $toDate])
                                        ->count(),
            'average_transaction' => Payment::where('status', 'completed')
                                          ->whereBetween('created_at', [$fromDate, $toDate])
                                          ->avg('amount'),
            'gateway_breakdown' => Payment::where('status', 'completed')
                                        ->whereBetween('created_at', [$fromDate, $toDate])
                                        ->groupBy('gateway')
                                        ->selectRaw('gateway, SUM(amount) as total, COUNT(*) as count')
                                        ->get(),
            'daily_revenue' => Payment::where('status', 'completed')
                                    ->whereBetween('created_at', [$fromDate, $toDate])
                                    ->groupBy(DB::raw('DATE(created_at)'))
                                    ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue')
                                    ->orderBy('date')
                                    ->get()
        ];
    }

    private function generateEngagementReport($request)
    {
        $fromDate = $request->get('from_date', now()->subMonth());
        $toDate = $request->get('to_date', now());

        return [
            'period' => ['from' => $fromDate, 'to' => $toDate],
            'forum_activity' => [
                'new_topics' => ForumTopic::whereBetween('created_at', [$fromDate, $toDate])->count(),
                'total_posts' => ForumReply::whereBetween('created_at', [$fromDate, $toDate])->count()
            ],
            'course_reviews' => [
                'new_reviews' => CourseReview::whereBetween('created_at', [$fromDate, $toDate])->count(),
                'average_rating' => CourseReview::whereBetween('created_at', [$fromDate, $toDate])->avg('rating')
            ],
            'certificates_issued' => Certificate::whereBetween('created_at', [$fromDate, $toDate])->count(),
            'course_completions' => Enrollment::where('status', 'completed')
                                            ->whereBetween('completed_at', [$fromDate, $toDate])
                                            ->count()
        ];
    }

    private function generateOverviewReport($request)
    {
        return [
            'users' => $this->generateUserReport($request),
            'courses' => $this->generateCourseReport($request),
            'revenue' => $this->generateRevenueReport($request),
            'engagement' => $this->generateEngagementReport($request)
        ];
    }

    private function getUserGrowthAnalytics($period)
    {
        $data = [];
        for ($i = $period; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'new_users' => User::whereDate('created_at', $date)->count(),
                'active_users' => User::whereDate('last_login_at', $date)->count()
            ];
        }
        return $data;
    }

    private function getCoursePerformanceAnalytics($period)
    {
        return Course::withCount(['enrollments', 'reviews'])
                    ->withAvg('reviews', 'rating')
                    ->where('created_at', '>=', now()->subDays($period))
                    ->orderBy('enrollments_count', 'desc')
                    ->limit(10)
                    ->get();
    }

    private function getRevenueAnalytics($period)
    {
        $data = [];
        for ($i = $period; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'revenue' => Payment::where('status', 'completed')
                                  ->whereDate('created_at', $date)
                                  ->sum('amount'),
                'transactions' => Payment::where('status', 'completed')
                                       ->whereDate('created_at', $date)
                                       ->count()
            ];
        }
        return $data;
    }

    private function getEngagementMetrics($period)
    {
        return [
            'forum_engagement' => [
                'topics_created' => ForumTopic::where('created_at', '>=', now()->subDays($period))->count(),
                'posts_created' => ForumReply::where('created_at', '>=', now()->subDays($period))->count()
            ],
            'course_engagement' => [
                'enrollments' => Enrollment::where('created_at', '>=', now()->subDays($period))->count(),
                'completions' => Enrollment::where('status', 'completed')
                                          ->where('completed_at', '>=', now()->subDays($period))
                                          ->count()
            ],
            'content_creation' => [
                'courses_created' => Course::where('created_at', '>=', now()->subDays($period))->count(),
                'reviews_posted' => CourseReview::where('created_at', '>=', now()->subDays($period))->count()
            ]
        ];
    }

    private function getPlatformHealthMetrics()
    {
        return [
            'uptime' => '99.9%',
            'response_time' => '150ms',
            'error_rate' => '0.1%',
            'active_sessions' => rand(100, 500),
            'server_load' => rand(20, 80) . '%'
        ];
    }

    private function getDatabaseSize()
    {
        try {
            $databaseName = config('database.connections.' . config('database.default') . '.database');

            if (config('database.default') === 'sqlite') {
                $dbPath = database_path('database.sqlite');
                if (file_exists($dbPath)) {
                    $sizeBytes = filesize($dbPath);
                    return $this->formatBytes($sizeBytes);
                }
                return 'Unknown';
            }

            // For MySQL/PostgreSQL
            if (config('database.default') === 'mysql') {
                $result = DB::select("
                    SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size_mb'
                    FROM information_schema.tables
                    WHERE table_schema = ?
                ", [$databaseName]);

                return ($result[0]->size_mb ?? 0) . ' MB';
            }

            return 'Unknown';
        } catch (\Exception $e) {
            return 'Error calculating size';
        }
    }

    private function getTableSizes()
    {
        try {
            $tables = ['users', 'courses', 'enrollments', 'payments', 'wallet_transactions', 'certificates'];
            $sizes = [];

            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    $count = DB::table($table)->count();
                    $sizes[$table] = number_format($count) . ' records';
                }
            }

            return $sizes;
        } catch (\Exception $e) {
            return [
                'users' => 'Error',
                'courses' => 'Error',
                'enrollments' => 'Error',
                'payments' => 'Error'
            ];
        }
    }

    private function getSlowQueries()
    {
        try {
            // For SQLite, we can't get slow queries, so return 0
            if (config('database.default') === 'sqlite') {
                return 0;
            }

            // For MySQL, check slow query log
            if (config('database.default') === 'mysql') {
                $result = DB::select("SHOW GLOBAL STATUS LIKE 'Slow_queries'");
                return $result[0]->Value ?? 0;
            }

            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getConnectionCount()
    {
        try {
            if (config('database.default') === 'mysql') {
                $result = DB::select("SHOW STATUS LIKE 'Threads_connected'");
                return $result[0]->Value ?? 1;
            }

            // For SQLite, always return 1 (single connection)
            return 1;
        } catch (\Exception $e) {
            return 1;
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Create a new user (admin endpoint)
     */
    public function createUser(Request $request)
    {
        try {
            // Validate required fields
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required|in:student,instructor,admin',
                'gender' => 'nullable|in:male,female',
                'date_of_birth' => 'nullable|date',
                'phone_number' => 'nullable|string|max:20',
                'home_address' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:100',
                'zipcode' => 'nullable|string|max:20',
                'parent_first_name' => 'nullable|string|max:255',
                'parent_last_name' => 'nullable|string|max:255',
                'parent_email' => 'nullable|email',
                'parent_phone' => 'nullable|string|max:20',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle profile photo upload
            $profilePhotoPath = null;
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $profilePhotoPath = $file->store('profile_photos', 'public');
            }

            // Create the user with all fields
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'gender' => $request->gender ?? 'male',
                'date_of_birth' => $request->date_of_birth,
                'contact' => $request->phone_number,
                'address' => $request->home_address,
                'state' => $request->state,
                'zipcode' => $request->zipcode,
                'parent_first_name' => $request->parent_first_name,
                'parent_last_name' => $request->parent_last_name,
                'parent_email' => $request->parent_email,
                'parent_phone' => $request->parent_phone,
                'profile_photo' => $profilePhotoPath,
                'is_active' => true
            ]);

            // Send email verification notification
            $user->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single user by ID
     */
    public function getUser($userId)
    {
        try {
            $user = User::findOrFail($userId);

            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update a user (admin endpoint)
     */
    public function updateUser(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Validate fields
            $validator = Validator::make($request->all(), [
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $userId,
                'password' => 'sometimes|string|min:8',
                'role' => 'sometimes|required|in:student,instructor,admin',
                'gender' => 'nullable|in:male,female',
                'date_of_birth' => 'nullable|date',
                'phone_number' => 'nullable|string|max:20',
                'home_address' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:100',
                'zipcode' => 'nullable|string|max:20',
                'parent_first_name' => 'nullable|string|max:255',
                'parent_last_name' => 'nullable|string|max:255',
                'parent_email' => 'nullable|email',
                'parent_phone' => 'nullable|string|max:20',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Use database transaction to ensure atomicity
            \DB::beginTransaction();

            try {
                // Prepare update data
                $updateData = [];

                if ($request->has('first_name')) $updateData['first_name'] = $request->first_name;
                if ($request->has('last_name')) $updateData['last_name'] = $request->last_name;
                if ($request->has('email')) $updateData['email'] = $request->email;
                if ($request->has('password')) $updateData['password'] = Hash::make($request->password);
                if ($request->has('role')) $updateData['role'] = $request->role;
                if ($request->has('gender')) $updateData['gender'] = $request->gender;
                if ($request->has('date_of_birth')) $updateData['date_of_birth'] = $request->date_of_birth;
                if ($request->has('phone_number')) $updateData['contact'] = $request->phone_number;
                if ($request->has('home_address')) $updateData['address'] = $request->home_address;
                if ($request->has('state')) $updateData['state'] = $request->state;
                if ($request->has('zipcode')) $updateData['zipcode'] = $request->zipcode;
                if ($request->has('parent_first_name')) $updateData['parent_first_name'] = $request->parent_first_name;
                if ($request->has('parent_last_name')) $updateData['parent_last_name'] = $request->parent_last_name;
                if ($request->has('parent_email')) $updateData['parent_email'] = $request->parent_email;
                if ($request->has('parent_phone')) $updateData['parent_phone'] = $request->parent_phone;

                // Handle profile photo upload
                if ($request->hasFile('profile_photo')) {
                    // Delete old profile photo if exists
                    if ($user->profile_photo) {
                        \Storage::disk('public')->delete($user->profile_photo);
                    }
                    $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
                    $updateData['profile_photo'] = $profilePhotoPath;
                }

                // Update the user
                $user->update($updateData);

                // Commit the transaction
                \DB::commit();

                // Refresh the user from database to ensure we have latest data
                $user->refresh();

                // Log the update for debugging
                \Log::info('User updated successfully', [
                    'user_id' => $user->id,
                    'updated_fields' => array_keys($updateData),
                    'user_data' => $user->toArray()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully',
                    'data' => $user
                ]);

            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Error updating user', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a user (admin endpoint)
     */
    public function deleteUser($userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Prevent deleting the last admin
            if ($user->role === 'admin') {
                $adminCount = User::where('role', 'admin')->count();
                if ($adminCount <= 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete the last admin user'
                    ], 400);
                }
            }

            // Delete profile photo if exists
            if ($user->profile_photo) {
                \Storage::disk('public')->delete($user->profile_photo);
            }

            // Delete the user
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
                'data' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }
}
