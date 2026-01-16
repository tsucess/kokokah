<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Get all subscription plans
     */
    public function index(Request $request)
    {
        try {
            $query = SubscriptionPlan::with('courses');

            // Filter by active status
            if ($request->has('active')) {
                $query->where('is_active', $request->boolean('active'));
            }

            // Filter by duration type
            if ($request->has('duration_type')) {
                $query->where('duration_type', $request->duration_type);
            }

            $plans = $query->orderBy('price', 'asc')->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $plans,
                'message' => 'Subscription plans retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subscription plans: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new subscription plan (Admin only)
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'duration' => 'required|integer|min:1',
                'duration_type' => 'required|in:free,daily,weekly,quarterly,monthly,half_yearly,yearly',
                'features' => 'nullable|array',
                'is_active' => 'boolean',
                'max_users' => 'nullable|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $plan = SubscriptionPlan::create($validator->validated());

            return response()->json([
                'success' => true,
                'data' => $plan,
                'message' => 'Subscription plan created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create subscription plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific subscription plan
     */
    public function show($id)
    {
        try {
            $plan = SubscriptionPlan::with('courses')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $plan,
                'message' => 'Subscription plan retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription plan not found: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update a subscription plan (Admin only)
     */
    public function update(Request $request, $id)
    {
        try {
            $plan = SubscriptionPlan::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'string|max:255',
                'description' => 'nullable|string',
                'price' => 'numeric|min:0',
                'duration' => 'integer|min:1',
                'duration_type' => 'in:free,daily,weekly,quarterly,monthly,half_yearly,yearly',
                'features' => 'nullable|array',
                'is_active' => 'boolean',
                'max_users' => 'nullable|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $plan->update($validator->validated());

            return response()->json([
                'success' => true,
                'data' => $plan,
                'message' => 'Subscription plan updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update subscription plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a subscription plan (Admin only)
     */
    public function destroy($id)
    {
        try {
            $plan = SubscriptionPlan::findOrFail($id);
            $plan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Subscription plan deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subscription plan: ' . $e->getMessage()
            ], 500);
        }
    }
}

