<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'feedback_type',
        'rating',
        'subject',
        'message',
        'status',
        'admin_response',
        'responded_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that submitted the feedback
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get unread feedback
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope to get feedback by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('feedback_type', $type);
    }

    /**
     * Scope to get feedback by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Mark feedback as read
     */
    public function markAsRead()
    {
        if ($this->status === 'new') {
            $this->update(['status' => 'read']);
        }
    }

    /**
     * Add admin response
     */
    public function addResponse($response)
    {
        $this->update([
            'admin_response' => $response,
            'status' => 'resolved',
            'responded_at' => now(),
        ]);
    }
}

