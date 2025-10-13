<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'gateway' => $this->gateway,
            'gateway_transaction_id' => $this->gateway_transaction_id,
            'gateway_response' => $this->gateway_response,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'processed_at' => $this->processed_at,

            // User information
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->first_name . ' ' . $this->user->last_name,
                    'email' => $this->user->email,
                    'role' => $this->user->role
                ];
            }),

            // Course information
            'course' => $this->whenLoaded('course', function () {
                return [
                    'id' => $this->course->id,
                    'title' => $this->course->title,
                    'price' => $this->course->price,
                    'instructor' => $this->course->instructor->first_name . ' ' . $this->course->instructor->last_name
                ];
            }),

            // Payment metadata
            'metadata' => $this->metadata,
            'fees' => $this->fees,
            'net_amount' => $this->net_amount,
            'refunded_amount' => $this->refunded_amount,
            'is_refunded' => $this->refunded_amount > 0,

            // Timestamps
            'failed_at' => $this->failed_at,
            'refunded_at' => $this->refunded_at,
        ];
    }
}
