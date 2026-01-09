<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => 'required|string|max:5000|min:1',
            'type' => 'nullable|in:text,image,audio,file,system',
            'reply_to_id' => 'nullable|integer|exists:chat_messages,id',
            'metadata' => 'nullable|array',
            'metadata.*' => 'nullable|string',
            'file' => 'nullable|file|max:51200',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'content.required' => 'Message content is required',
            'content.max' => 'Message cannot exceed 5000 characters',
            'content.min' => 'Message cannot be empty',
            'type.in' => 'Invalid message type',
            'reply_to_id.exists' => 'The message you are replying to does not exist',
        ];
    }
}

