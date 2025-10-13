<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'forum_id',
        'user_id',
        'title',
        'content',
        'is_pinned',
        'is_locked',
        'views'
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
        'views' => 'integer',
    ];

    // Relationships
    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(ForumReply::class, 'topic_id')->orderBy('created_at');
    }

    public function latestReply()
    {
        return $this->hasOne(ForumReply::class, 'topic_id')->latestOfMany();
    }

    // Scopes
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeUnpinned($query)
    {
        return $query->where('is_pinned', false);
    }

    public function scopeLocked($query)
    {
        return $query->where('is_locked', true);
    }

    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }

    public function scopeByForum($query, $forumId)
    {
        return $query->where('forum_id', $forumId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function getReplyCount()
    {
        return $this->replies()->count();
    }

    public function getLatestActivity()
    {
        $latestReply = $this->latestReply;
        return $latestReply ? $latestReply->created_at : $this->created_at;
    }

    public function canUserReply(User $user)
    {
        if ($this->is_locked) return false;
        
        // Check if user is enrolled in the course
        return $this->forum->course->enrollments()
                          ->where('user_id', $user->id)
                          ->where('status', 'active')
                          ->exists();
    }

    public function pin()
    {
        $this->update(['is_pinned' => true]);
    }

    public function unpin()
    {
        $this->update(['is_pinned' => false]);
    }

    public function lock()
    {
        $this->update(['is_locked' => true]);
    }

    public function unlock()
    {
        $this->update(['is_locked' => false]);
    }
}
