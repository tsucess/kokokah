<?php

namespace App\Http\Controllers;

use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserSubscriptionController extends Controller
{
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
                'amount_paid' => 'required|numeric|min:0',
                'payment_reference' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);

            // Check if user already has active subscription to this plan
            $existingSubscription = UserSubscription::where('user_id', $user->id)
                                                    ->where('subscription_plan_id', $plan->id)
                                                    ->where('status', 'active')
                                                    ->first();

            if ($existingSubscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'User already has an active subscription to this plan'
                ], 400);
            }

            // Calculate expiration date based on duration type
            $expiresAt = Carbon::now();
            switch ($plan->duration_type) {
                case 'daily':
                    $expiresAt->addDays($plan->duration);
                    break;
                case 'weekly':
                    $expiresAt->addWeeks($plan->duration);
                    break;
                case 'monthly':
                    $expiresAt->addMonths($plan->duration);
                    break;
                case 'yearly':
                    $expiresAt->addYears($plan->duration);
                    break;
            }

            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'started_at' => Carbon::now(),
                'expires_at' => $expiresAt,
                'status' => 'active',
                'amount_paid' => $request->amount_paid,
                'payment_reference' => $request->payment_reference
            ]);

            return response()->json([
                'success' => true,
                'data' => $subscription->load('subscriptionPlan'),
                'message' => 'Successfully subscribed to plan'
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
}

