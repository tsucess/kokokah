<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'last_login_at' => $this->last_login_at,
            'last_login_ip' => $this->last_login_ip,

            // Ban information
            'is_banned' => $this->banned_at !== null,
            'banned_at' => $this->banned_at,
            'ban_reason' => $this->ban_reason,
            'banned_until' => $this->banned_until,
            'banned_by' => $this->whenLoaded('bannedBy', function () {
                return [
                    'id' => $this->bannedBy->id,
                    'name' => $this->bannedBy->first_name . ' ' . $this->bannedBy->last_name
                ];
            }),

            // Statistics
            'enrollments_count' => $this->whenCounted('enrollments'),
            'courses_count' => $this->whenCounted('courses'),
            'total_spent' => $this->when(isset($this->total_spent), $this->total_spent),

            // Profile information
            'profile_photo' => $this->profile_photo,
            'contact' => $this->contact,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'address' => $this->address,

            // Relationships
            'level' => $this->whenLoaded('level'),
            'wallet' => $this->whenLoaded('wallet', function () {
                return [
                    'balance' => $this->wallet->balance,
                    'currency' => $this->wallet->currency ?? 'USD'
                ];
            }),
        ];
    }
}
