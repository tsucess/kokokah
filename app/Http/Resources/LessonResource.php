<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'video_url' => $this->video_url,
            'duration' => $this->duration,
            'order' => $this->order,
            'is_published' => $this->is_published,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

            // Course relationship
            'course' => $this->whenLoaded('course', function () {
                return [
                    'id' => $this->course->id,
                    'title' => $this->course->title,
                    'slug' => $this->course->slug
                ];
            }),

            // User-specific data
            'user_data' => $this->when($request->user(), function () use ($request) {
                $user = $request->user();
                $completion = $this->completions()->where('user_id', $user->id)->first();

                return [
                    'is_completed' => (bool) $completion,
                    'completed_at' => $completion?->completed_at?->format('Y-m-d H:i:s'),
                    'time_spent' => $completion?->time_spent ?? 0,
                    'can_access' => $this->course->enrollments()->where('user_id', $user->id)->exists()
                ];
            })
        ];
    }
}
