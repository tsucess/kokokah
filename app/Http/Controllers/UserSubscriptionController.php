<?php

namespace App\Http\Controllers;

use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use App\Models\Enrollment;
use App\Models\Course;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSubscriptionController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Get current user's subscriptions
     */
    public function getUserSubscriptions(Request $request)
    {
        try {
            $user = Auth::user();
            $status = $request->get('status', 'active');

            $query = $user->subscriptions();

            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }

            $subscriptions = $query->with('subscriptionPlan')
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $subscriptions,
                'message' => 'User subscriptions retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subscriptions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subscribe user to a plan
     */
    public function subscribe(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'subscription_plan_id' => 'required|exists:subscription_plans,id',
                'amount_paid' => 'required|numeric|min:0.01',
                'payment_reference' => 'nullable|string',
                'course_ids' => 'nullable|array',
                'course_ids.*' => 'exists:courses,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);

            // Validate wallet balance if amount_paid is provided (wallet payment)
            if ($request->amount_paid > 0) {
                $affordability = $this->walletService->canAffordSubscription($user, $request->amount_paid);
                if (!$affordability['can_afford']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient wallet balance for this subscription',
                        'data' => $affordability
                    ], 400);
                }
            }

            // Check if user is trying to subscribe to courses they're already enrolled in
            if ($request->has('course_ids') && is_array($request->course_ids)) {
                $duplicateCourses = Enrollment::where('user_id', $user->id)
                                             ->whereIn('course_id', $request->course_ids)
                                             ->pluck('course_id')
                                             ->toArray();

                if (!empty($duplicateCourses)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User is already enrolled in some of the selected courses',
                        'data' => [
                            'duplicate_course_ids' => $duplicateCourses
                        ]
                    ], 400);
                }
            }

            // Calculate expiration date based on duration type
            $expiresAt = Carbon::now();
            switch ($plan->duration_type) {
                case 'free':
                    // Free subscriptions don't expire
                    $expiresAt = null;
                    break;
                case 'daily':
                    $expiresAt->addDays($plan->duration);
                    break;
                case 'weekly':
                    $expiresAt->addWeeks($plan->duration);
                    break;
                case 'quarterly':
                    // Quarterly = 3 months
                    $expiresAt->addMonths(3);
                    break;
                case 'monthly':
                    $expiresAt->addMonths($plan->duration);
                    break;
                case 'half_yearly':
                    // Half yearly = 6 months
                    $expiresAt->addMonths(6);
                    break;
                case 'yearly':
                    $expiresAt->addYears($plan->duration);
                    break;
            }

            // Use transaction to ensure both subscription and enrollments are created together
            $subscription = DB::transaction(function () use ($user, $plan, $request, $expiresAt) {
                $subscription = UserSubscription::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $plan->id,
                    'started_at' => Carbon::now(),
                    'expires_at' => $expiresAt,
                    'status' => 'active',
                    'amount_paid' => $request->amount_paid,
                    'payment_reference' => $request->payment_reference
                ]);

                // Deduct from wallet if amount_paid is provided (wallet payment)
                if ($request->amount_paid > 0) {
                    $wallet = $user->getOrCreateWallet();

                    // Create wallet transaction
                    $wallet->transactions()->create([
                        'amount' => $request->amount_paid,
                        'type' => 'debit',
                        'reference' => $request->payment_reference ?? 'SUB-' . uniqid(),
                        'status' => 'success',
                        'description' => 'Subscription: ' . $plan->title,
                        'payment_method' => 'wallet',
                        'metadata' => [
                            'subscription_plan_id' => $plan->id,
                            'subscription_id' => $subscription->id
                        ]
                    ]);

                    // Deduct from wallet balance
                    $wallet->decrement('balance', $request->amount_paid);
                }

                // Enroll user in selected courses if course_ids provided
                if ($request->has('course_ids') && is_array($request->course_ids)) {
                    foreach ($request->course_ids as $courseId) {
                        // Check if already enrolled
                        $existingEnrollment = Enrollment::where('user_id', $user->id)
                                                       ->where('course_id', $courseId)
                                                       ->first();

                        if (!$existingEnrollment) {
                            Enrollment::create([
                                'user_id' => $user->id,
                                'course_id' => $courseId,
                                'status' => 'active',
                                'enrolled_at' => Carbon::now(),
                                'amount_paid' => $request->amount_paid
                            ]);
                        }
                    }
                }

                return $subscription;
            });

            return response()->json([
                'success' => true,
                'data' => $subscription->load('subscriptionPlan'),
                'message' => 'Successfully subscribed to plan and enrolled in courses'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel user subscription
     */
    public function cancelSubscription($subscriptionId)
    {
        try {
            $user = Auth::user();
            $subscription = UserSubscription::where('id', $subscriptionId)
                                           ->where('user_id', $user->id)
                                           ->firstOrFail();

            $subscription->cancel();

            return response()->json([
                'success' => true,
                'data' => $subscription,
                'message' => 'Subscription cancelled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel subscription: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pause user subscription
     */
    public function pauseSubscription($subscriptionId)
    {
        try {
            $user = Auth::user();
            $subscription = UserSubscription::where('id', $subscriptionId)
                                           ->where('user_id', $user->id)
                                           ->firstOrFail();

            $subscription->pause();

            return response()->json([
                'success' => true,
                'data' => $subscription,
                'message' => 'Subscription paused successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to pause subscription: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resume user subscription
     */
    public function resumeSubscription($subscriptionId)
    {
        try {
            $user = Auth::user();
            $subscription = UserSubscription::where('id', $subscriptionId)
                                           ->where('user_id', $user->id)
                                           ->firstOrFail();

            $subscription->resume();

            return response()->json([
                'success' => true,
                'data' => $subscription,
                'message' => 'Subscription resumed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resume subscription: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user has access to a course based on subscription
     *
     * Access is granted if:
     * 1. User is enrolled in the course
     * 2. Course is in free subscription plan AND user has active free subscription
     * 3. Course is in free subscription plan AND user has no subscriptions (new/unsubscribed users)
     */
    public function checkCourseAccess(Request $request, $courseId)
    {
        try {
            $user = Auth::user();
            $course = Course::findOrFail($courseId);

            // Check if course is in free subscription plan
            $freeSubscriptionPlan = SubscriptionPlan::where('duration_type', 'free')
                                                    ->where('is_active', true)
                                                    ->first();

            $hasAccess = false;
            $accessReason = null;

            // If course is in free subscription plan, grant access to:
            // 1. Users with active free subscription
            // 2. Users with no subscriptions at all (new/unsubscribed users)
            if ($freeSubscriptionPlan && $course->subscriptionPlans()->where('subscription_plan_id', $freeSubscriptionPlan->id)->exists()) {
                // Check if user has any active subscription
                $hasAnyActiveSubscription = UserSubscription::where('user_id', $user->id)
                                                           ->where('status', 'active')
                                                           ->where(function ($q) {
                                                               $q->whereNull('expires_at')
                                                                 ->orWhere('expires_at', '>', Carbon::now());
                                                           })
                                                           ->exists();

                // If user has no active subscriptions, they are new/unsubscribed - grant free access
                if (!$hasAnyActiveSubscription) {
                    $hasAccess = true;
                    $accessReason = 'User has access to free courses (new/unsubscribed user)';
                } else {
                    // User has subscriptions, check if they have active free subscription
                    $activeFreeSubscription = UserSubscription::where('user_id', $user->id)
                                                             ->where('subscription_plan_id', $freeSubscriptionPlan->id)
                                                             ->where('status', 'active')
                                                             ->where(function ($q) {
                                                                 $q->whereNull('expires_at')
                                                                   ->orWhere('expires_at', '>', Carbon::now());
                                                             })
                                                             ->first();

                    if ($activeFreeSubscription) {
                        $hasAccess = true;
                        $accessReason = 'User has active free subscription';
                    } else {
                        $accessReason = 'Course requires free subscription which user does not have';
                    }
                }
            } else {
                // Check if user is enrolled in the course
                $enrollment = Enrollment::where('user_id', $user->id)
                                       ->where('course_id', $courseId)
                                       ->where('status', 'active')
                                       ->first();

                if ($enrollment) {
                    $hasAccess = true;
                    $accessReason = 'User is enrolled in this course';
                } else {
                    $accessReason = 'User is not enrolled in this course';
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'course_id' => $courseId,
                    'has_access' => $hasAccess,
                    'reason' => $accessReason
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check course access: ' . $e->getMessage()
            ], 500);
        }
    }
}

