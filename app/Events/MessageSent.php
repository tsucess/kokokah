<?php

namespace App\Events;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The chat message instance.
     *
     * @var ChatMessage
     */
    public $message;

    /**
     * The chat room instance.
     *
     * @var ChatRoom
     */
    public $chatRoom;

    /**
     * Create a new event instance.
     *
     * @param ChatMessage $message
     * @param ChatRoom $chatRoom
     */
    public function __construct(ChatMessage $message, ChatRoom $chatRoom)
    {
        $this->message = $message;
        $this->chatRoom = $chatRoom;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chatroom.' . $this->chatRoom->id),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'chat_room_id' => $this->message->chat_room_id,
            'user_id' => $this->message->user_id,
            'user' => [
                'id' => $this->message->user->id,
                'first_name' => $this->message->user->first_name,
                'last_name' => $this->message->user->last_name,
                'profile_photo' => $this->message->user->profile_photo,
            ],
            'content' => $this->message->content,
            'type' => $this->message->type,
            'reply_to_id' => $this->message->reply_to_id,
            'edited_content' => $this->message->edited_content,
            'edited_at' => $this->message->edited_at,
            'is_deleted' => $this->message->is_deleted,
            'is_pinned' => $this->message->is_pinned,
            'reaction_count' => $this->message->reaction_count,
            'created_at' => $this->message->created_at,
            'updated_at' => $this->message->updated_at,
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}

