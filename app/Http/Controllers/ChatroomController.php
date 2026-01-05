<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatroomController extends Controller
{
    /**
     * Display a listing of chatrooms for the authenticated user.
     *
     * Returns:
     * - General chatroom (available to all users)
     * - Course-specific chatrooms (only for courses user is enrolled in)
     */
    public function index()
    {
        try {
            $user = Auth::user();

            // Get General chatroom (available to all users)
            $generalChatrooms = ChatRoom::where('type', 'general')
                ->with(['creator:id,first_name,last_name'])
                ->get();

            // Get course-specific chatrooms for courses user is enrolled in
            $enrolledCourseIds = $user->enrolledCourses()
                ->pluck('courses.id')
                ->toArray();

            $courseChatrooms = ChatRoom::where('type', 'course')
                ->whereIn('course_id', $enrolledCourseIds)
                ->with(['creator:id,first_name,last_name'])
                ->get();

            // Combine both types of chatrooms
            $allChatrooms = $generalChatrooms->concat($courseChatrooms)
                ->sortByDesc('updated_at')
                ->values();

            // Format the response
            $chatrooms = $allChatrooms->map(function ($room) use ($user) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'description' => $room->description,
                    'type' => $room->type,
                    'course_id' => $room->course_id,
                    'icon' => $room->icon,
                    'color' => $room->color,
                    'is_active' => $room->is_active,
                    'member_count' => $room->users()->count(),
                    'message_count' => $room->messages()->count(),
                    'last_message_at' => $room->last_message_at,
                    'unread_count' => $room->users()
                        ->where('user_id', $user->id)
                        ->first()?->pivot->unread_count ?? 0,
                    'created_by' => $room->creator,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $chatrooms
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch chatrooms: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified chatroom.
     */
    public function show(ChatRoom $chatroom)
    {
        try {
            $user = Auth::user();

            // Admin can access all chatrooms
            if ($user->role !== 'admin') {
                // General chatrooms are accessible to all authenticated users
                if ($chatroom->type === 'general') {
                    // Allow access
                }
                // For course-specific chatrooms, check enrollment
                elseif ($chatroom->type === 'course' && $chatroom->course_id) {
                    // Check if user is the instructor
                    if ($chatroom->course && $chatroom->course->instructor_id === $user->id) {
                        // Allow access
                    }
                    // Check if user is enrolled in the course with active status
                    elseif ($chatroom->course && $chatroom->course->enrollments()
                        ->where('user_id', $user->id)
                        ->where('status', 'active')
                        ->exists()) {
                        // Allow access
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'You do not have access to this chat room.'
                        ], 403);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have access to this chat room.'
                    ], 403);
                }
            }

            $chatroom->load(['creator:id,first_name,last_name', 'users:id,first_name,last_name,profile_photo']);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $chatroom->id,
                    'name' => $chatroom->name,
                    'description' => $chatroom->description,
                    'type' => $chatroom->type,
                    'icon' => $chatroom->icon,
                    'color' => $chatroom->color,
                    'is_active' => $chatroom->is_active,
                    'member_count' => $chatroom->users()->count(),
                    'message_count' => $chatroom->messages()->count(),
                    'last_message_at' => $chatroom->last_message_at,
                    'created_by' => $chatroom->creator,
                    'members' => $chatroom->users,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch chatroom: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created chatroom (admin only).
     */
    public function store(Request $request)
    {
        try {
            // Only admins can create chatrooms
            if (Auth::user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only admins can create chatrooms'
                ], 403);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'type' => 'nullable|in:general,course,private',
                'icon' => 'nullable|string',
                'color' => 'nullable|string',
            ]);

            $validated['created_by'] = Auth::id();
            $validated['type'] = $validated['type'] ?? 'general';
            $validated['is_active'] = true;

            $chatroom = ChatRoom::create($validated);

            // Add creator as admin member
            $chatroom->users()->attach(Auth::id(), ['role' => 'admin', 'joined_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Chatroom created successfully',
                'data' => $chatroom
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create chatroom: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified chatroom (admin only).
     */
    public function update(Request $request, ChatRoom $chatroom)
    {
        try {
            if (Auth::user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only admins can update chatrooms'
                ], 403);
            }

            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'icon' => 'nullable|string',
                'color' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ]);

            $chatroom->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Chatroom updated successfully',
                'data' => $chatroom
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update chatroom: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the specified chatroom (admin only).
     */
    public function destroy(ChatRoom $chatroom)
    {
        try {
            if (Auth::user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only admins can delete chatrooms'
                ], 403);
            }

            $chatroom->delete();

            return response()->json([
                'success' => true,
                'message' => 'Chatroom deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete chatroom: ' . $e->getMessage()
            ], 500);
        }
    }
}

