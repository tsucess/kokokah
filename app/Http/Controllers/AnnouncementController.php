<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements
     */
    public function index(Request $request)
    {
        try {
            $query = Announcement::with('user');

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by type
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            // Filter by priority
            if ($request->has('priority')) {
                $query->where('priority', $request->priority);
            }

            // Search by title or description
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Get published announcements for non-admin users
            if (!Auth::user() || !in_array(Auth::user()->role, ['admin', 'superadmin'])) {
                $query->published();
            }

            $announcements = $query->recent()->paginate(10);

            return response()->json([
                'status' => 200,
                'message' => 'Announcements retrieved successfully',
                'data' => $announcements
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error retrieving announcements',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'type' => 'required|in:Exams,Events,Alert,General Info',
                'priority' => 'required|in:Info,Urgent,Warning',
                'audience' => 'required|in:All students,Specific class,Specific level',
                'audience_value' => 'nullable|string',
                'scheduled_at' => 'nullable|date_format:Y-m-d H:i:s',
                'status' => 'required|in:draft,published'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $announcement = Announcement::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'priority' => $request->priority,
                'audience' => $request->audience,
                'audience_value' => $request->audience_value,
                'scheduled_at' => $request->scheduled_at,
                'status' => $request->status
            ]);

            return response()->json([
                'status' => 201,
                'message' => 'Announcement created successfully',
                'data' => $announcement->load('user')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error creating announcement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified announcement
     */
    public function show(string $id)
    {
        try {
            $announcement = Announcement::with('user')->findOrFail($id);

            // Increment view count
            $announcement->increment('view_count');

            return response()->json([
                'status' => 200,
                'message' => 'Announcement retrieved successfully',
                'data' => $announcement
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Announcement not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, string $id)
    {
        try {
            $announcement = Announcement::findOrFail($id);

            // Check authorization
            if (Auth::id() !== $announcement->user_id && Auth::user()->role !== 'admin') {
                return response()->json([
                    'status' => 403,
                    'message' => 'Unauthorized to update this announcement'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'type' => 'sometimes|required|in:Exams,Events,Alert,General Info',
                'priority' => 'sometimes|required|in:Info,Urgent,Warning',
                'audience' => 'sometimes|required|in:All students,Specific class,Specific level',
                'audience_value' => 'nullable|string',
                'scheduled_at' => 'nullable|date_format:Y-m-d H:i:s',
                'status' => 'sometimes|required|in:draft,published,archived'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $announcement->update($request->only([
                'title', 'description', 'type', 'priority', 'audience', 'audience_value', 'scheduled_at', 'status'
            ]));

            return response()->json([
                'status' => 200,
                'message' => 'Announcement updated successfully',
                'data' => $announcement->load('user')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error updating announcement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified announcement
     */
    public function destroy(string $id)
    {
        try {
            $announcement = Announcement::findOrFail($id);

            // Check authorization
            if (Auth::id() !== $announcement->user_id && !in_array(Auth::user()->role, ['admin', 'superadmin'])) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Unauthorized to delete this announcement'
                ], 403);
            }

            $announcement->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Announcement deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error deleting announcement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get announcements by type with count
     */
    public function getByType()
    {
        try {
            $types = ['Exams', 'Events', 'Alert', 'General Info'];
            $result = [];

            foreach ($types as $type) {
                $count = Announcement::where('type', $type)->published()->count();
                $result[] = [
                    'type' => $type,
                    'count' => $count
                ];
            }

            return response()->json([
                'status' => 200,
                'message' => 'Announcement types retrieved successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error retrieving announcement types',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
