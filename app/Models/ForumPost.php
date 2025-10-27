<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'user_id',
        'content',
        'parent_id',
        'status',
        'edited_at',
        'edited_by',
        'likes_count',
        'is_solution'
    ];

    protected $casts = [
        'edited_at' => 'datetime',
        'likes_count' => 'integer',
        'is_solution' => 'boolean'
    ];

    // Relationships
    public function topic()
    {
        return $this->belongsTo(ForumTopic::class, 'topic_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(ForumPost::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(ForumPost::class, 'parent_id')->orderBy('created_at');
    }

    public function likes()
    {
        return $this->hasMany(ForumPostLike::class, 'post_id');
    }

    // Scopes
    public function scopeByTopic($query, $topicId)
    {
        return $query->where('topic_id', $topicId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Methods
    public function canUserEdit(User $user)
    {
        // User can edit their own posts within 24 hours
        return $this->user_id === $user->id && 
               $this->created_at->gt(now()->subHours(24));
    }

    public function isReply()
    {
        return !is_null($this->parent_id);
    }

    public function isTopLevel()
    {
        return is_null($this->parent_id);
    }
}

