<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'chat_room_id' => $this->chat_room_id,
            'user_id' => $this->user_id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->first_name . ' ' . $this->user->last_name,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'profile_photo' => $this->user->profile_photo,
            ],
            'content' => $this->content,
            'type' => $this->type,
            'reply_to_id' => $this->reply_to_id,
            'reply_to' => $this->when($this->replyTo, function () {
                return [
                    'id' => $this->replyTo->id,
                    'content' => $this->replyTo->content,
                    'user' => [
                        'id' => $this->replyTo->user->id,
                        'name' => $this->replyTo->user->first_name . ' ' . $this->replyTo->user->last_name,
                    ],
                ];
            }),
            'edited_content' => $this->edited_content,
            'edited_at' => $this->edited_at,
            'is_edited' => $this->is_edited,
            'is_deleted' => $this->is_deleted,
            'is_pinned' => $this->is_pinned,
            'reaction_count' => $this->reaction_count,
            'reactions' => $this->when($this->relationLoaded('reactions'), function () {
                return $this->reactions->groupBy('emoji')->map(function ($reactions) {
                    return [
                        'emoji' => $reactions->first()->emoji,
                        'count' => $reactions->count(),
                        'users' => $reactions->pluck('user.id')->toArray(),
                    ];
                })->values();
            }),
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

