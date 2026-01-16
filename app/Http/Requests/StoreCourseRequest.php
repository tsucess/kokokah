<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAnyRole(['instructor', 'admin', 'superadmin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'category_id' => 'required|exists:categories,id',
            'term_id' => 'nullable|exists:terms,id',
            'level_id' => 'nullable|exists:levels,id',
            'duration_hours' => 'nullable|integer|min:1|max:1000',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'max_students' => 'nullable|integer|min:1|max:10000',
            'status' => 'nullable|in:draft,published,archived',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:courses,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Course title is required',
            'title.max' => 'Course title cannot exceed 255 characters',
            'description.required' => 'Course description is required',
            'description.min' => 'Course description must be at least 50 characters',
            'category_id.required' => 'Please select a category',
            'category_id.exists' => 'Selected category does not exist',
            'difficulty.required' => 'Please select difficulty level',
            'difficulty.in' => 'Difficulty must be beginner, intermediate, or advanced',
            'max_students.min' => 'Maximum students must be at least 1',
            'max_students.max' => 'Maximum students cannot exceed 10,000',
            'duration_hours.min' => 'Duration must be at least 1 hour',
            'duration_hours.max' => 'Duration cannot exceed 1,000 hours'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'term_id' => 'term',
            'level_id' => 'level',
            'duration_hours' => 'duration',
            'max_students' => 'maximum students'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean and prepare data
        if ($this->has('tags') && is_string($this->tags)) {
            $this->merge([
                'tags' => array_filter(array_map('trim', explode(',', $this->tags)))
            ]);
        }
    }
}
