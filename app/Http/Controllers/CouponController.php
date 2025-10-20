<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for middleware configuration

    /**
     * Get all coupons (admin/instructor)
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Coupon::with(['creator', 'courses']);

            // If instructor, only show their coupons
            if ($user->hasRole('instructor')) {
                $query->where('created_by', $user->id);
            }

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by type
            if ($request->has('type')) {
                $query->where('discount_type', $request->type);
            }

            // Filter by expiry
            if ($request->has('expired')) {
                if ($request->expired === 'true') {
                    $query->where('expires_at', '<', now());
                } else {
                    $query->where('expires_at', '>', now());
                }
            }

            $coupons = $query->orderBy('created_at', 'desc')
                           ->paginate($request->get('per_page', 20));

            // Add usage statistics
            $coupons->getCollection()->transform(function ($coupon) {
                $couponData = $coupon->toArray();
                $couponData['usage_count'] = $coupon->usages()->count();
                $couponData['remaining_uses'] = $coupon->usage_limit ? 
                    max(0, $coupon->usage_limit - $couponData['usage_count']) : null;
                $couponData['total_savings'] = $this->calculateTotalSavings($coupon);
                return $couponData;
            });

            return response()->json([
                'success' => true,
                'data' => $coupons
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch coupons: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new coupon
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => 'nullable|string|max:50|unique:coupons,code',
                'discount_type' => 'required|in:percentage,fixed',
                'discount_value' => 'required|numeric|min:0',
                'minimum_amount' => 'nullable|numeric|min:0',
                'maximum_discount' => 'nullable|numeric|min:0',
                'usage_limit' => 'nullable|integer|min:1',
                'user_limit' => 'nullable|integer|min:1',
                'starts_at' => 'required|date|after_or_equal:today',
                'expires_at' => 'required|date|after:starts_at',
                'applicable_to' => 'required|in:all,specific_courses,categories',
                'course_ids' => 'required_if:applicable_to,specific_courses|array',
                'course_ids.*' => 'exists:courses,id',
                'categories' => 'required_if:applicable_to,categories|array',
                'description' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate code if not provided
            $code = $request->code ?: $this->generateCouponCode();

            // Validate discount value
            if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Percentage discount cannot exceed 100%'
                ], 400);
            }

            // Check course ownership for instructors
            if ($user->hasRole('instructor') && $request->applicable_to === 'specific_courses') {
                $courses = Course::whereIn('id', $request->course_ids)->get();
                foreach ($courses as $course) {
                    if ($course->instructor_id !== $user->id) {
                        return response()->json([
                            'success' => false,
                            'message' => 'You can only create coupons for your own courses'
                        ], 403);
                    }
                }
            }

            // Create coupon
            $coupon = Coupon::create([
                'name' => $request->name,
                'code' => $code,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'minimum_amount' => $request->minimum_amount,
                'maximum_discount' => $request->maximum_discount,
                'usage_limit' => $request->usage_limit,
                'user_limit' => $request->user_limit,
                'starts_at' => $request->starts_at,
                'expires_at' => $request->expires_at,
                'applicable_to' => $request->applicable_to,
                'course_ids' => $request->applicable_to === 'specific_courses' ? $request->course_ids : null,
                'categories' => $request->applicable_to === 'categories' ? $request->categories : null,
                'description' => $request->description,
                'created_by' => $user->id,
                'status' => 'active'
            ]);

            // Attach courses if specific courses
            if ($request->applicable_to === 'specific_courses') {
                $coupon->courses()->attach($request->course_ids);
            }

            return response()->json([
                'success' => true,
                'message' => 'Coupon created successfully',
                'data' => $coupon->load(['creator', 'courses'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create coupon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific coupon
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $coupon = Coupon::with(['creator', 'courses', 'usages.user'])->findOrFail($id);

            // Check permissions
            if ($user->hasRole('instructor') && $coupon->created_by !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view this coupon'
                ], 403);
            }

            $couponData = $coupon->toArray();
            $couponData['usage_count'] = $coupon->usages()->count();
            $couponData['remaining_uses'] = $coupon->usage_limit ? 
                max(0, $coupon->usage_limit - $couponData['usage_count']) : null;
            $couponData['total_savings'] = $this->calculateTotalSavings($coupon);
            $couponData['usage_analytics'] = $this->getCouponUsageAnalytics($coupon);

            return response()->json([
                'success' => true,
                'data' => $couponData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch coupon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update coupon
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $coupon = Coupon::findOrFail($id);

            // Check permissions
            if ($user->hasRole('instructor') && $coupon->created_by !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this coupon'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'discount_type' => 'sometimes|in:percentage,fixed',
                'discount_value' => 'sometimes|numeric|min:0',
                'minimum_amount' => 'nullable|numeric|min:0',
                'maximum_discount' => 'nullable|numeric|min:0',
                'usage_limit' => 'nullable|integer|min:1',
                'user_limit' => 'nullable|integer|min:1',
                'starts_at' => 'sometimes|date',
                'expires_at' => 'sometimes|date|after:starts_at',
                'status' => 'sometimes|in:active,inactive,expired',
                'description' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate discount value
            if ($request->has('discount_type') && $request->discount_type === 'percentage' && 
                $request->has('discount_value') && $request->discount_value > 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Percentage discount cannot exceed 100%'
                ], 400);
            }

            $coupon->update($request->only([
                'name', 'discount_type', 'discount_value', 'minimum_amount',
                'maximum_discount', 'usage_limit', 'user_limit', 'starts_at',
                'expires_at', 'status', 'description'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Coupon updated successfully',
                'data' => $coupon->load(['creator', 'courses'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update coupon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete coupon
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $coupon = Coupon::findOrFail($id);

            // Check permissions
            if ($user->hasRole('instructor') && $coupon->created_by !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this coupon'
                ], 403);
            }

            // Check if coupon has been used
            if ($coupon->usages()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete coupon that has been used. Consider deactivating it instead.'
                ], 400);
            }

            $coupon->delete();

            return response()->json([
                'success' => true,
                'message' => 'Coupon deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete coupon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate coupon code
     */
    public function validateCoupon(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'code' => 'required|string',
                'course_id' => 'nullable|exists:courses,id',
                'amount' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validation = $this->validateCouponCode(
                $request->code,
                $user,
                $request->course_id,
                $request->amount
            );

            return response()->json([
                'success' => $validation['valid'],
                'message' => $validation['message'],
                'data' => $validation['valid'] ? [
                    'coupon' => $validation['coupon'],
                    'discount_amount' => $validation['discount_amount'],
                    'final_amount' => $validation['final_amount']
                ] : null
            ], $validation['valid'] ? 200 : 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to validate coupon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Apply coupon (record usage)
     */
    public function applyCoupon(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'code' => 'required|string',
                'course_id' => 'nullable|exists:courses,id',
                'amount' => 'required|numeric|min:0',
                'payment_id' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validation = $this->validateCouponCode(
                $request->code,
                $user,
                $request->course_id,
                $request->amount
            );

            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message']
                ], 400);
            }

            // Record coupon usage
            CouponUsage::create([
                'coupon_id' => $validation['coupon']->id,
                'user_id' => $user->id,
                'course_id' => $request->course_id,
                'payment_id' => $request->payment_id,
                'original_amount' => $request->amount,
                'discount_amount' => $validation['discount_amount'],
                'final_amount' => $validation['final_amount'],
                'used_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully',
                'data' => [
                    'discount_amount' => $validation['discount_amount'],
                    'final_amount' => $validation['final_amount']
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply coupon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's available coupons
     */
    public function getUserCoupons()
    {
        try {
            $user = Auth::user();

            $coupons = Coupon::where('status', 'active')
                           ->where('starts_at', '<=', now())
                           ->where('expires_at', '>', now())
                           ->where(function($query) use ($user) {
                               // Check usage limits
                               $query->whereNull('usage_limit')
                                     ->orWhereRaw('(SELECT COUNT(*) FROM coupon_usages WHERE coupon_id = coupons.id) < usage_limit');
                           })
                           ->where(function($query) use ($user) {
                               // Check user limits
                               $query->whereNull('user_limit')
                                     ->orWhereRaw('(SELECT COUNT(*) FROM coupon_usages WHERE coupon_id = coupons.id AND user_id = ?) < user_limit', [$user->id]);
                           })
                           ->with(['courses'])
                           ->get();

            return response()->json([
                'success' => true,
                'data' => $coupons
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user coupons: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get coupon analytics
     */
    public function analytics(Request $request)
    {
        try {
            $user = Auth::user();
            $period = $request->get('period', 30); // days

            $query = Coupon::query();

            // If instructor, only their coupons
            if ($user->hasRole('instructor')) {
                $query->where('created_by', $user->id);
            }

            $coupons = $query->get();

            $analytics = [
                'overview' => [
                    'total_coupons' => $coupons->count(),
                    'active_coupons' => $coupons->where('status', 'active')->count(),
                    'expired_coupons' => $coupons->where('expires_at', '<', now())->count(),
                    'total_usage' => CouponUsage::whereIn('coupon_id', $coupons->pluck('id'))->count(),
                    'total_savings' => CouponUsage::whereIn('coupon_id', $coupons->pluck('id'))->sum('discount_amount')
                ],
                'performance' => [
                    'most_used' => $this->getMostUsedCoupons($coupons, 5),
                    'highest_savings' => $this->getHighestSavingsCoupons($coupons, 5),
                    'conversion_rates' => $this->getCouponConversionRates($coupons),
                    'usage_trends' => $this->getCouponUsageTrends($coupons, $period)
                ],
                'insights' => [
                    'popular_discount_types' => $this->getPopularDiscountTypes($coupons),
                    'average_discount' => $this->getAverageDiscount($coupons),
                    'seasonal_patterns' => $this->getSeasonalPatterns($coupons)
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch coupon analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk operations on coupons
     */
    public function bulkAction(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'action' => 'required|in:activate,deactivate,delete,extend',
                'coupon_ids' => 'required|array',
                'coupon_ids.*' => 'exists:coupons,id',
                'extend_days' => 'required_if:action,extend|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $coupons = Coupon::whereIn('id', $request->coupon_ids)->get();

            // Check permissions for instructors
            if ($user->hasRole('instructor')) {
                foreach ($coupons as $coupon) {
                    if ($coupon->created_by !== $user->id) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Unauthorized to perform bulk action on some coupons'
                        ], 403);
                    }
                }
            }

            $results = [
                'processed' => 0,
                'errors' => []
            ];

            foreach ($coupons as $coupon) {
                try {
                    switch ($request->action) {
                        case 'activate':
                            $coupon->update(['status' => 'active']);
                            break;
                        case 'deactivate':
                            $coupon->update(['status' => 'inactive']);
                            break;
                        case 'delete':
                            if ($coupon->usages()->count() === 0) {
                                $coupon->delete();
                            } else {
                                throw new \Exception('Coupon has usage history');
                            }
                            break;
                        case 'extend':
                            $coupon->update([
                                'expires_at' => $coupon->expires_at->addDays($request->extend_days)
                            ]);
                            break;
                    }
                    $results['processed']++;
                } catch (\Exception $e) {
                    $results['errors'][] = [
                        'coupon_id' => $coupon->id,
                        'error' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Bulk action completed. {$results['processed']} coupons processed.",
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
     * Helper methods
     */
    private function generateCouponCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Coupon::where('code', $code)->exists());

        return $code;
    }

    private function calculateTotalSavings($coupon)
    {
        return $coupon->usages()->sum('discount_amount');
    }

    private function getCouponUsageAnalytics($coupon)
    {
        $usages = $coupon->usages()->with('user')->get();

        return [
            'total_uses' => $usages->count(),
            'unique_users' => $usages->pluck('user_id')->unique()->count(),
            'total_savings' => $usages->sum('discount_amount'),
            'average_discount' => $usages->avg('discount_amount'),
            'usage_by_month' => $usages->groupBy(function($usage) {
                return $usage->used_at->format('Y-m');
            })->map->count(),
            'top_users' => $usages->groupBy('user_id')
                                 ->map(function($userUsages) {
                                     return [
                                         'user' => $userUsages->first()->user,
                                         'usage_count' => $userUsages->count(),
                                         'total_savings' => $userUsages->sum('discount_amount')
                                     ];
                                 })
                                 ->sortByDesc('usage_count')
                                 ->take(5)
                                 ->values()
        ];
    }

    private function validateCouponCode($code, $user, $courseId = null, $amount = 0)
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Invalid coupon code'];
        }

        // Check if coupon is active
        if ($coupon->status !== 'active') {
            return ['valid' => false, 'message' => 'Coupon is not active'];
        }

        // Check if coupon has started
        if ($coupon->starts_at > now()) {
            return ['valid' => false, 'message' => 'Coupon is not yet valid'];
        }

        // Check if coupon has expired
        if ($coupon->expires_at < now()) {
            return ['valid' => false, 'message' => 'Coupon has expired'];
        }

        // Check usage limit
        if ($coupon->usage_limit && $coupon->usages()->count() >= $coupon->usage_limit) {
            return ['valid' => false, 'message' => 'Coupon usage limit reached'];
        }

        // Check user limit
        if ($coupon->user_limit && $coupon->usages()->where('user_id', $user->id)->count() >= $coupon->user_limit) {
            return ['valid' => false, 'message' => 'You have reached the usage limit for this coupon'];
        }

        // Check minimum amount
        if ($coupon->minimum_amount && $amount < $coupon->minimum_amount) {
            return ['valid' => false, 'message' => "Minimum purchase amount of {$coupon->minimum_amount} required"];
        }

        // Check course applicability
        if ($courseId && $coupon->applicable_to === 'specific_courses') {
            if (!$coupon->courses()->where('course_id', $courseId)->exists()) {
                return ['valid' => false, 'message' => 'Coupon is not applicable to this course'];
            }
        }

        // Check category applicability
        if ($courseId && $coupon->applicable_to === 'categories') {
            $course = Course::find($courseId);
            if ($course && !in_array($course->category_id, $coupon->categories ?? [])) {
                return ['valid' => false, 'message' => 'Coupon is not applicable to this course category'];
            }
        }

        // Calculate discount
        $discountAmount = $this->calculateDiscount($coupon, $amount);
        $finalAmount = max(0, $amount - $discountAmount);

        return [
            'valid' => true,
            'message' => 'Coupon is valid',
            'coupon' => $coupon,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount
        ];
    }

    private function calculateDiscount($coupon, $amount)
    {
        if ($coupon->discount_type === 'percentage') {
            $discount = ($amount * $coupon->discount_value) / 100;
        } else {
            $discount = $coupon->discount_value;
        }

        // Apply maximum discount limit
        if ($coupon->maximum_discount) {
            $discount = min($discount, $coupon->maximum_discount);
        }

        return round($discount, 2);
    }

    private function getMostUsedCoupons($coupons, $limit)
    {
        return $coupons->map(function($coupon) {
            return [
                'coupon' => $coupon,
                'usage_count' => $coupon->usages()->count()
            ];
        })->sortByDesc('usage_count')->take($limit)->values();
    }

    private function getHighestSavingsCoupons($coupons, $limit)
    {
        return $coupons->map(function($coupon) {
            return [
                'coupon' => $coupon,
                'total_savings' => $coupon->usages()->sum('discount_amount')
            ];
        })->sortByDesc('total_savings')->take($limit)->values();
    }

    private function getCouponConversionRates($coupons)
    {
        // Mock implementation - would track actual conversion rates
        return [
            'view_to_use' => rand(15, 35) . '%',
            'cart_to_purchase' => rand(60, 85) . '%',
            'overall_effectiveness' => rand(25, 45) . '%'
        ];
    }

    private function getCouponUsageTrends($coupons, $period)
    {
        $data = [];
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'usage_count' => CouponUsage::whereIn('coupon_id', $coupons->pluck('id'))
                                          ->whereDate('used_at', $date)
                                          ->count(),
                'savings_amount' => CouponUsage::whereIn('coupon_id', $coupons->pluck('id'))
                                              ->whereDate('used_at', $date)
                                              ->sum('discount_amount')
            ];
        }
        return $data;
    }

    private function getPopularDiscountTypes($coupons)
    {
        return $coupons->groupBy('discount_type')
                      ->map->count()
                      ->sortDesc();
    }

    private function getAverageDiscount($coupons)
    {
        return [
            'percentage_coupons' => $coupons->where('discount_type', 'percentage')->avg('discount_value'),
            'fixed_coupons' => $coupons->where('discount_type', 'fixed')->avg('discount_value')
        ];
    }

    private function getSeasonalPatterns($coupons)
    {
        // Mock implementation - would analyze actual seasonal usage patterns
        return [
            'peak_months' => ['November', 'December', 'January'],
            'low_months' => ['June', 'July', 'August'],
            'holiday_effectiveness' => 'High during Black Friday and New Year'
        ];
    }
}
