<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'description' => $this->description,
            'slug' => $this->slug,
            'status' => $this->status,
            'difficulty' => $this->difficulty,
            'duration_hours' => $this->duration_hours,
            'max_students' => $this->max_students,
            'published_at' => $this->published_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

            // Relationships
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug
                ];
            }),

            'instructor' => $this->whenLoaded('instructor', function () {
                return [
                    'id' => $this->instructor->id,
                    'name' => $this->instructor->full_name,
                    'email' => $this->instructor->email,
                    'avatar' => $this->instructor->avatar ?? null
                ];
            }),

            'term' => $this->whenLoaded('term', function () {
                return [
                    'id' => $this->term->id,
                    'name' => $this->term->name
                ];
            }),

            'level' => $this->whenLoaded('level', function () {
                return [
                    'id' => $this->level->id,
                    'name' => $this->level->name
                ];
            }),

            // Statistics
            'stats' => [
                'total_students' => $this->total_students,
                'average_rating' => round($this->average_rating, 2),
                'total_lessons' => $this->whenLoaded('lessons', function () {
                    return $this->lessons->count();
                }),
                'total_reviews' => $this->whenLoaded('reviews', function () {
                    return $this->reviews->count();
                })
            ],

            // Collections
            'lessons' => LessonResource::collection($this->whenLoaded('lessons')),
            'tags' => $this->whenLoaded('tags', function () {
                return $this->tags->pluck('name');
            }),
            'prerequisites' => $this->whenLoaded('prerequisites', function () {
                return $this->prerequisites->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'slug' => $course->slug
                    ];
                });
            }),

            // User-specific data (when authenticated)
            'user_data' => $this->when($request->user(), function () use ($request) {
                $user = $request->user();
                $enrollment = $this->enrollments()->where('user_id', $user->id)->first();

                return [
                    'is_enrolled' => (bool) $enrollment,
                    'enrollment_status' => $enrollment?->status,
                    'progress' => $enrollment?->progress ?? 0,
                    'is_favorite' => $this->favoritedBy()->where('user_id', $user->id)->exists(),
                    'can_enroll' => $this->status === 'published' && !$enrollment,
                    'has_reviewed' => $this->reviews()->where('user_id', $user->id)->exists()
                ];
            })
        ];
    }
}
