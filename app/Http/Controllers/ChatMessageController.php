<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

class ChatMessageController extends Controller
{
    /**
     * Fetch messages for a chatroom with pagination.
     *
     * @param Request $request
     * @param ChatRoom $chatRoom
     * @return JsonResponse
     */
    public function index(Request $request, ChatRoom $chatRoom): JsonResponse
    {
        try {
            $user = Auth::user();

            // Authorization is handled by middleware, but we can add additional checks here if needed
            // The middleware (AuthorizeChatRoomAccess) already verified access

            // Validate pagination parameters
            $validator = Validator::make($request->all(), [
                'per_page' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
                'sort' => 'nullable|in:asc,desc',
                'type' => 'nullable|in:text,image,file,system',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $perPage = $request->get('per_page', 50);
            $sort = $request->get('sort', 'desc');
            $type = $request->get('type');

            // Build query
            $query = $chatRoom->messages()
                ->with(['user:id,first_name,last_name,profile_photo', 'reactions', 'replyTo.user:id,first_name,last_name'])
                ->where(function($q) {
                    $q->where('is_deleted', false)
                      ->orWhereNull('is_deleted');
                });

            // Filter by message type if provided
            if ($type) {
                $query->where('type', $type);
            }

            // Paginate messages
            $messages = $query->orderBy('created_at', $sort)
                ->paginate($perPage);

            // Update last read timestamp
            $this->updateLastRead($user, $chatRoom);

            return response()->json([
                'success' => true,
                'data' => $messages->items(),
                'pagination' => [
                    'total' => $messages->total(),
                    'per_page' => $messages->perPage(),
                    'current_page' => $messages->currentPage(),
                    'last_page' => $messages->lastPage(),
                    'from' => $messages->firstItem(),
                    'to' => $messages->lastItem(),
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Chat room not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch messages: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a message to a chatroom.
     *
     * @param Request $request
     * @param ChatRoom $chatRoom
     * @return JsonResponse
     */
    public function store(Request $request, ChatRoom $chatRoom): JsonResponse
    {
        try {
            $user = Auth::user();

            // Authorize using policy
            $this->authorize('create', [ChatMessage::class, $chatRoom]);

            // Validate message
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|max:5000',
                'type' => 'nullable|in:text,image,file,system',
                'reply_to_id' => 'nullable|exists:chat_messages,id',
                'metadata' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create message
            $message = ChatMessage::create([
                'chat_room_id' => $chatRoom->id,
                'user_id' => $user->id,
                'content' => $request->content,
                'type' => $request->get('type', 'text'),
                'reply_to_id' => $request->get('reply_to_id'),
                'metadata' => $request->get('metadata'),
            ]);

            // Load relationships
            $message->load(['user:id,first_name,last_name,profile_photo', 'reactions', 'replyTo.user:id,first_name,last_name']);

            // Update chat room stats
            $chatRoom->increment('message_count');
            $chatRoom->update(['last_message_at' => now()]);

            // Broadcast message event
            broadcast(new MessageSent($message, $chatRoom))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => $message
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'You do not have permission to send messages in this chat room.'
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Chat room not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific message.
     *
     * @param ChatMessage $message
     * @return JsonResponse
     */
    public function show(ChatMessage $message): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check if user is a member of the chat room
            if (!$this->isRoomMember($user, $message->chatRoom)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not a member of this chat room'
                ], 403);
            }

            $message->load(['user:id,first_name,last_name,profile_photo', 'reactions', 'replyTo.user:id,first_name,last_name', 'replies']);

            return response()->json([
                'success' => true,
                'data' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a message (only by sender or admin).
     *
     * @param Request $request
     * @param ChatMessage $message
     * @return JsonResponse
     */
    public function update(Request $request, ChatMessage $message): JsonResponse
    {
        try {
            // Authorize using policy
            $this->authorize('update', $message);

            // Validate input
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|max:5000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update message
            $message->update([
                'edited_content' => $request->content,
                'edited_at' => now(),
            ]);

            $message->load(['user:id,first_name,last_name,profile_photo', 'reactions']);

            // Broadcast update event
            broadcast(new MessageSent($message, $message->chatRoom))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Message updated successfully',
                'data' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a message (soft delete).
     *
     * @param ChatMessage $message
     * @return JsonResponse
     */
    public function destroy(ChatMessage $message): JsonResponse
    {
        try {
            // Authorize using policy
            $this->authorize('delete', $message);

            // Soft delete message
            $message->update(['is_deleted' => true]);

            // Broadcast delete event
            broadcast(new MessageSent($message, $message->chatRoom))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user is a member of the chat room.
     * General chatrooms are accessible to all authenticated users.
     *
     * @param $user
     * @param ChatRoom $chatRoom
     * @return bool
     */
    private function isRoomMember($user, ChatRoom $chatRoom): bool
    {
        // Admin can access all rooms
        if ($user->role === 'admin') {
            return true;
        }

        // General chatrooms are accessible to all authenticated users
        if ($chatRoom->type === 'general') {
            return true;
        }

        // For course-specific chatrooms, check membership
        return $chatRoom->users()
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Check if user is muted in the chat room.
     *
     * @param $user
     * @param ChatRoom $chatRoom
     * @return bool
     */
    private function isUserMuted($user, ChatRoom $chatRoom): bool
    {
        return $chatRoom->users()
            ->where('user_id', $user->id)
            ->where('is_muted', true)
            ->exists();
    }

    /**
     * Update user's last read timestamp.
     * For general chatrooms, automatically add user if not already a member.
     *
     * @param $user
     * @param ChatRoom $chatRoom
     * @return void
     */
    private function updateLastRead($user, ChatRoom $chatRoom): void
    {
        // Check if user is already in the chat room
        $exists = $chatRoom->users()
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            // Update existing record
            $chatRoom->users()
                ->where('user_id', $user->id)
                ->update(['last_read_at' => now()]);
        } else if ($chatRoom->type === 'general') {
            // For general chatrooms, automatically add user as member
            $chatRoom->users()->attach($user->id, [
                'role' => 'member',
                'is_active' => true,
                'joined_at' => now(),
                'last_read_at' => now(),
            ]);
        }
    }
}

