<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Show feedback page (data is loaded via API endpoint)
     */
    public function showPage()
    {
        return view('admin.feedback');
    }

    /**
     * Store user feedback
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'feedback_type' => 'required|in:bug,feature_request,general,other',
                'rating' => 'nullable|integer|min:1|max:5',
                'subject' => 'nullable|string|max:255',
                'message' => 'required|string|min:10|max:5000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $feedback = Feedback::create([
                'user_id' => Auth::id(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'feedback_type' => $request->feedback_type,
                'rating' => $request->rating,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 'new',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your feedback! We appreciate your input.',
                'data' => $feedback
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit feedback: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's feedback history (authenticated users only)
     */
    public function getUserFeedback()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $feedback = Feedback::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $feedback
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve feedback: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all feedback (admin and superadmin)
     */
    public function index()
    {
        try {
            $user = Auth::user();

            if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $feedback = Feedback::orderBy('created_at', 'desc')->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $feedback
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve feedback: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single feedback (admin and superadmin)
     */
    public function show($id)
    {
        try {
            $user = Auth::user();

            if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $feedback = Feedback::findOrFail($id);
            $feedback->markAsRead();

            return response()->json([
                'success' => true,
                'data' => $feedback
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve feedback: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get feedback by type (admin and superadmin)
     */
    public function getByType($type)
    {
        try {
            $user = Auth::user();

            if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $validTypes = ['bug', 'feature_request', 'general', 'other'];
            if (!in_array($type, $validTypes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid feedback type'
                ], 400);
            }

            $feedback = Feedback::where('feedback_type', $type)
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $feedback
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve feedback: ' . $e->getMessage()
            ], 500);
        }
    }
}

