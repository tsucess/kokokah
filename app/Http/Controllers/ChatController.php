<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Start a new chat session
     */
    public function startSession(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'course_id' => 'nullable|exists:courses,id',
                'context' => 'nullable|string|max:1000',
                'session_type' => 'required|in:general,course_help,assignment_help,quiz_help'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check course access if course_id provided
            if ($request->course_id) {
                $course = Course::findOrFail($request->course_id);
                $enrollment = $user->enrollments()->where('course_id', $course->id)->first();
                
                if (!$enrollment && !$user->hasRole('instructor') && !$user->hasRole('admin')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You must be enrolled in this course to get course-specific help'
                    ], 403);
                }
            }

            // Create chat session
            $session = ChatSession::create([
                'user_id' => $user->id,
                'course_id' => $request->course_id,
                'session_type' => $request->session_type,
                'context' => $request->context,
                'status' => 'active',
                'started_at' => now()
            ]);

            // Create welcome message
            $welcomeMessage = $this->generateWelcomeMessage($session);
            
            $message = ChatMessage::create([
                'chat_session_id' => $session->id,
                'sender_type' => 'ai',
                'message' => $welcomeMessage,
                'sent_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Chat session started successfully',
                'data' => [
                    'session' => $session,
                    'welcome_message' => $message
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start chat session: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a message in chat session
     */
    public function sendMessage(Request $request, $sessionId)
    {
        try {
            $user = Auth::user();
            $session = ChatSession::findOrFail($sessionId);

            // Check if user owns the session
            if ($session->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this chat session'
                ], 403);
            }

            // Check if session is active
            if ($session->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Chat session is not active'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'message' => 'required|string|max:2000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create user message
            $userMessage = ChatMessage::create([
                'chat_session_id' => $session->id,
                'sender_type' => 'user',
                'message' => $request->message,
                'sent_at' => now()
            ]);

            // Generate AI response
            $aiResponse = $this->generateAIResponse($session, $request->message);

            // Create AI message
            $aiMessage = ChatMessage::create([
                'chat_session_id' => $session->id,
                'sender_type' => 'ai',
                'message' => $aiResponse,
                'sent_at' => now()
            ]);

            // Update session last activity
            $session->update(['last_activity_at' => now()]);

            return response()->json([
                'success' => true,
                'data' => [
                    'user_message' => $userMessage,
                    'ai_response' => $aiMessage
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get chat session history
     */
    public function getSessionHistory($sessionId)
    {
        try {
            $user = Auth::user();
            $session = ChatSession::with(['messages' => function($query) {
                $query->orderBy('sent_at', 'asc');
            }, 'course'])->findOrFail($sessionId);

            // Check if user owns the session
            if ($session->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this chat session'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $session
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch session history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's chat sessions
     */
    public function getUserSessions(Request $request)
    {
        try {
            $user = Auth::user();

            $query = ChatSession::with(['course', 'messages' => function($q) {
                $q->latest()->limit(1);
            }])->where('user_id', $user->id);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by session type
            if ($request->has('session_type')) {
                $query->where('session_type', $request->session_type);
            }

            // Filter by course
            if ($request->has('course_id')) {
                $query->where('course_id', $request->course_id);
            }

            $sessions = $query->orderBy('last_activity_at', 'desc')
                            ->paginate($request->get('per_page', 20));

            return response()->json([
                'success' => true,
                'data' => $sessions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch chat sessions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * End chat session
     */
    public function endSession($sessionId)
    {
        try {
            $user = Auth::user();
            $session = ChatSession::findOrFail($sessionId);

            // Check if user owns the session
            if ($session->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this chat session'
                ], 403);
            }

            $session->update([
                'status' => 'ended',
                'ended_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Chat session ended successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to end chat session: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rate chat session
     */
    public function rateSession(Request $request, $sessionId)
    {
        try {
            $user = Auth::user();
            $session = ChatSession::findOrFail($sessionId);

            // Check if user owns the session
            if ($session->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this chat session'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'rating' => 'required|integer|min:1|max:5',
                'feedback' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $session->update([
                'rating' => $request->rating,
                'feedback' => $request->feedback,
                'rated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Session rated successfully',
                'data' => $session
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to rate session: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get chat analytics (for admins)
     */
    public function analytics(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $period = $request->get('period', 30); // days
            $fromDate = now()->subDays($period);

            $analytics = [
                'overview' => [
                    'total_sessions' => ChatSession::count(),
                    'active_sessions' => ChatSession::where('status', 'active')->count(),
                    'period_sessions' => ChatSession::where('started_at', '>=', $fromDate)->count(),
                    'total_messages' => ChatMessage::count(),
                    'average_session_length' => $this->calculateAverageSessionLength(),
                    'user_satisfaction' => $this->calculateUserSatisfaction()
                ],
                'usage_patterns' => [
                    'sessions_by_type' => $this->getSessionsByType($fromDate),
                    'daily_usage' => $this->getDailyUsage($period),
                    'peak_hours' => $this->getPeakUsageHours(),
                    'course_help_requests' => $this->getCourseHelpRequests($fromDate)
                ],
                'performance' => [
                    'response_times' => $this->getResponseTimes(),
                    'resolution_rates' => $this->getResolutionRates(),
                    'user_ratings' => $this->getUserRatings($fromDate),
                    'common_topics' => $this->getCommonTopics($fromDate)
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch chat analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get suggested responses (for AI training)
     */
    public function getSuggestedResponses(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'message' => 'required|string|max:1000',
                'context' => 'nullable|string|max:500',
                'session_type' => 'required|in:general,course_help,assignment_help,quiz_help'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $suggestions = $this->generateSuggestedResponses(
                $request->message,
                $request->context,
                $request->session_type
            );

            return response()->json([
                'success' => true,
                'data' => $suggestions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate suggestions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods
     */
    private function generateWelcomeMessage($session)
    {
        $messages = [
            'general' => "Hello! I'm your AI learning assistant. How can I help you today?",
            'course_help' => "Hi! I'm here to help you with your course. What would you like to know?",
            'assignment_help' => "Hello! I can help you understand your assignment better. What specific questions do you have?",
            'quiz_help' => "Hi there! I'm here to help you prepare for your quiz. What topics would you like to review?"
        ];

        $baseMessage = $messages[$session->session_type] ?? $messages['general'];

        if ($session->course_id) {
            $course = Course::find($session->course_id);
            $baseMessage .= " I see you're working on \"{$course->title}\". ";
        }

        return $baseMessage;
    }

    private function generateAIResponse($session, $userMessage)
    {
        // This is a mock implementation. In a real application, this would:
        // 1. Use natural language processing to understand the user's intent
        // 2. Query relevant course content, FAQs, or knowledge base
        // 3. Generate contextually appropriate responses
        // 4. Potentially integrate with external AI services like OpenAI GPT

        $responses = [
            // General responses
            'hello' => "Hello! How can I assist you with your learning today?",
            'help' => "I'm here to help! You can ask me about course content, assignments, quizzes, or general learning questions.",
            'thanks' => "You're welcome! Is there anything else I can help you with?",

            // Course-related responses
            'course' => "I can help you with course content, explain concepts, or guide you through lessons. What specific topic interests you?",
            'lesson' => "Which lesson are you working on? I can provide explanations, summaries, or additional resources.",
            'assignment' => "I can help clarify assignment requirements, suggest approaches, or explain related concepts. What's your question?",
            'quiz' => "I can help you review key concepts, explain difficult topics, or suggest study strategies. What would you like to focus on?",

            // Default responses
            'default' => "I understand you're asking about that topic. Let me help you find the information you need. Could you be more specific about what you'd like to know?"
        ];

        // Simple keyword matching (in real implementation, use NLP)
        $message = strtolower($userMessage);

        foreach ($responses as $keyword => $response) {
            if (strpos($message, $keyword) !== false) {
                return $response;
            }
        }

        // If no keyword match, provide contextual response based on session type
        switch ($session->session_type) {
            case 'course_help':
                return "I can help you with course-related questions. Could you tell me more about what you're trying to understand?";
            case 'assignment_help':
                return "I'm here to help with your assignment. What specific part would you like assistance with?";
            case 'quiz_help':
                return "I can help you prepare for your quiz. What topics would you like to review or practice?";
            default:
                return $responses['default'];
        }
    }

    private function calculateAverageSessionLength()
    {
        $completedSessions = ChatSession::whereNotNull('ended_at')->get();

        if ($completedSessions->count() === 0) return 0;

        $totalMinutes = $completedSessions->sum(function($session) {
            return $session->started_at->diffInMinutes($session->ended_at);
        });

        return round($totalMinutes / $completedSessions->count(), 1);
    }

    private function calculateUserSatisfaction()
    {
        $ratedSessions = ChatSession::whereNotNull('rating')->get();

        if ($ratedSessions->count() === 0) return null;

        return round($ratedSessions->avg('rating'), 2);
    }

    private function getSessionsByType($fromDate)
    {
        return ChatSession::where('started_at', '>=', $fromDate)
                         ->groupBy('session_type')
                         ->selectRaw('session_type, COUNT(*) as count')
                         ->pluck('count', 'session_type');
    }

    private function getDailyUsage($period)
    {
        $data = [];
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'sessions' => ChatSession::whereDate('started_at', $date)->count(),
                'messages' => ChatMessage::whereDate('sent_at', $date)->count()
            ];
        }
        return $data;
    }

    private function getPeakUsageHours()
    {
        $hours = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $hours[] = [
                'hour' => sprintf('%02d:00', $hour),
                'sessions' => ChatSession::whereRaw('HOUR(started_at) = ?', [$hour])->count()
            ];
        }
        return collect($hours)->sortByDesc('sessions')->take(5)->values();
    }

    private function getCourseHelpRequests($fromDate)
    {
        return ChatSession::with('course')
                         ->where('session_type', 'course_help')
                         ->where('started_at', '>=', $fromDate)
                         ->whereNotNull('course_id')
                         ->get()
                         ->groupBy('course.title')
                         ->map(function($sessions, $courseTitle) {
                             return [
                                 'course' => $courseTitle,
                                 'requests' => $sessions->count()
                             ];
                         })
                         ->sortByDesc('requests')
                         ->take(10)
                         ->values();
    }

    private function getResponseTimes()
    {
        // Mock implementation - would calculate actual AI response times
        return [
            'average' => '2.3 seconds',
            'median' => '1.8 seconds',
            'p95' => '5.2 seconds'
        ];
    }

    private function getResolutionRates()
    {
        $totalSessions = ChatSession::whereNotNull('ended_at')->count();
        $satisfiedSessions = ChatSession::where('rating', '>=', 4)->count();

        return [
            'overall_satisfaction' => $totalSessions > 0 ? round(($satisfiedSessions / $totalSessions) * 100, 2) : 0,
            'resolution_rate' => rand(75, 95) // Mock data
        ];
    }

    private function getUserRatings($fromDate)
    {
        $ratings = ChatSession::where('started_at', '>=', $fromDate)
                            ->whereNotNull('rating')
                            ->groupBy('rating')
                            ->selectRaw('rating, COUNT(*) as count')
                            ->pluck('count', 'rating');

        return [
            '5_stars' => $ratings[5] ?? 0,
            '4_stars' => $ratings[4] ?? 0,
            '3_stars' => $ratings[3] ?? 0,
            '2_stars' => $ratings[2] ?? 0,
            '1_star' => $ratings[1] ?? 0
        ];
    }

    private function getCommonTopics($fromDate)
    {
        // Mock implementation - would use NLP to extract topics from messages
        return [
            ['topic' => 'Assignment Help', 'frequency' => 45],
            ['topic' => 'Quiz Preparation', 'frequency' => 38],
            ['topic' => 'Course Content', 'frequency' => 32],
            ['topic' => 'Technical Issues', 'frequency' => 28],
            ['topic' => 'Grading Questions', 'frequency' => 22]
        ];
    }

    private function generateSuggestedResponses($message, $context, $sessionType)
    {
        // Mock implementation - would use AI to generate contextual suggestions
        $suggestions = [
            "I understand you're asking about that. Let me help you find the right information.",
            "That's a great question! Here's what I can tell you about that topic.",
            "I can help you with that. Let me break it down for you step by step.",
            "Based on your question, here are some key points to consider.",
            "I see what you're looking for. Here's some helpful information on that topic."
        ];

        return array_slice($suggestions, 0, 3);
    }
}
