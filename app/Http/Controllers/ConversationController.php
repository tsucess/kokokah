<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller
{
    /**
     * Get all conversations for a course
     */
    public function indexByCourse($courseId)
    {
        try {
            $user = Auth::user();
            $course = Course::findOrFail($courseId);

            // Check if user is enrolled or instructor
            $isEnrolled = $user->enrollments()->where('course_id', $courseId)->exists();
            $isInstructor = $course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled in this course to access conversations'
                ], 403);
            }

            $conversations = Conversation::where('course_id', $courseId)
                ->active()
                ->with(['creator', 'latestMessage.user', 'participants'])
                ->orderBy('last_message_at', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $conversations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch conversations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get messages for a conversation
     */
    public function getMessages($conversationId)
    {
        try {
            $user = Auth::user();
            $conversation = Conversation::findOrFail($conversationId);

            // Check access
            if (!$conversation->hasParticipant($user->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this conversation'
                ], 403);
            }

            // Mark as read
            $conversation->markAsRead($user->id);

            $messages = $conversation->messages()
                ->with('user')
                ->paginate(50);

            return response()->json([
                'success' => true,
                'data' => $messages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch messages: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a message to a conversation
     */
    public function sendMessage(Request $request, $conversationId)
    {
        try {
            $user = Auth::user();
            $conversation = Conversation::findOrFail($conversationId);

            // Check access
            if (!$conversation->hasParticipant($user->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this conversation'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'message' => 'required|string|max:5000',
                'attachments' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $message = $conversation->addMessage(
                $user->id,
                $request->message,
                $request->attachments
            );

            // Award chatroom badge if applicable
            $this->checkAndAwardChatroomBadges($user);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => $message->load('user')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new conversation (admin/instructor only)
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'course_id' => 'required|exists:courses,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $course = Course::findOrFail($request->course_id);

            // Check if user is instructor or admin
            if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only instructors can create conversations'
                ], 403);
            }

            $conversation = Conversation::create([
                'course_id' => $request->course_id,
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => $user->id
            ]);

            // Add creator as participant
            $conversation->addParticipant($user->id);

            return response()->json([
                'success' => true,
                'message' => 'Conversation created successfully',
                'data' => $conversation->load('creator', 'participants')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create conversation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Join a conversation
     */
    public function join($conversationId)
    {
        try {
            $user = Auth::user();
            $conversation = Conversation::findOrFail($conversationId);

            // Check if user is enrolled in the course
            $isEnrolled = $user->enrollments()->where('course_id', $conversation->course_id)->exists();
            $isInstructor = $conversation->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled in this course to join the conversation'
                ], 403);
            }

            $conversation->addParticipant($user->id);

            return response()->json([
                'success' => true,
                'message' => 'Joined conversation successfully',
                'data' => $conversation->load('participants')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to join conversation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a message as helpful (instructor/admin only)
     */
    public function markMessageAsHelpful($messageId)
    {
        try {
            $user = Auth::user();
            $message = ConversationMessage::findOrFail($messageId);
            $conversation = $message->conversation;

            // Check if user is instructor or admin
            $isInstructor = $conversation->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only instructors can mark messages as helpful'
                ], 403);
            }

            $message->markAsHelpful();

            return response()->json([
                'success' => true,
                'message' => 'Message marked as helpful',
                'data' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark message as helpful: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check and award chatroom badges
     */
    private function checkAndAwardChatroomBadges($user)
    {
        $messageCount = ConversationMessage::where('user_id', $user->id)->count();

        // Award badges based on message count
        $badgeCriteria = [
            'chatroom_posts' => 10,
            'chatroom_posts_25' => 25,
            'chatroom_posts_50' => 50,
            'chatroom_posts_100' => 100
        ];

        foreach ($badgeCriteria as $criteria => $required) {
            if ($messageCount >= $required) {
                $badge = \App\Models\Badge::where('criteria', $criteria)->first();
                if ($badge && !$user->badges()->where('badge_id', $badge->id)->exists()) {
                    $user->badges()->attach($badge->id, ['earned_at' => now()]);
                }
            }
        }
    }
}

