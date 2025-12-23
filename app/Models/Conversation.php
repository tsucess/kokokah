<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'name',
        'description',
        'is_active',
        'created_by',
        'last_message_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_message_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class)->orderBy('created_at');
    }

    public function latestMessage()
    {
        return $this->hasOne(ConversationMessage::class)->latestOfMany();
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
                    ->withPivot('joined_at', 'last_read_at')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    // Methods
    public function addMessage($userId, $message, $attachments = null)
    {
        $msg = $this->messages()->create([
            'user_id' => $userId,
            'message' => $message,
            'attachments' => $attachments
        ]);

        // Update last message timestamp
        $this->update(['last_message_at' => now()]);

        return $msg;
    }

    public function getMessageCount()
    {
        return $this->messages()->count();
    }

    public function getParticipantCount()
    {
        return $this->participants()->count();
    }

    public function addParticipant($userId)
    {
        if (!$this->participants()->where('user_id', $userId)->exists()) {
            $this->participants()->attach($userId, ['joined_at' => now()]);
        }
    }

    public function removeParticipant($userId)
    {
        $this->participants()->detach($userId);
    }

    public function hasParticipant($userId)
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }

    public function getUnreadMessageCount($userId)
    {
        $participant = $this->participants()->where('user_id', $userId)->first();
        
        if (!$participant) {
            return 0;
        }

        $lastReadAt = $participant->pivot->last_read_at;
        
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->where('created_at', '>', $lastReadAt ?? $this->created_at)
            ->count();
    }

    public function markAsRead($userId)
    {
        $this->participants()->updateExistingPivot($userId, [
            'last_read_at' => now()
        ]);
    }
}

