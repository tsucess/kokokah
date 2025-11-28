<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => 'required|integer|exists:courses,id',
            'topic_id' => 'nullable|integer|exists:topic,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|string|max:255',
            'video_type' => 'nullable|string|max:255',
            'lesson_type' => 'nullable|string|max:255',
            'attachment' => 'nullable',
            'attachment_type' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'video_type_for_mobile_application' => 'nullable|string|max:255',
            'video_url_for_mobile_application' => 'nullable|string|max:255',
            'duration_for_mobile_application' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'duration_minutes' => 'nullable|integer',
            'is_free' => 'nullable|boolean',
        ];
    }
}
