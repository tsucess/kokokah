<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'user_id',
        'content',
        'parent_id'
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
        return $this->belongsTo(ForumReply::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ForumReply::class, 'parent_id')->orderBy('created_at');
    }

    public function forum()
    {
        return $this->topic->forum();
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

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    // Methods
    public function isReply()
    {
        return !is_null($this->parent_id);
    }

    public function isTopLevel()
    {
        return is_null($this->parent_id);
    }

    public function getDepth()
    {
        $depth = 0;
        $parent = $this->parent;
        
        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }
        
        return $depth;
    }

    public function canUserEdit(User $user)
    {
        // User can edit their own replies within 24 hours
        return $this->user_id === $user->id && 
               $this->created_at->gt(now()->subHours(24));
    }

    public function canUserDelete(User $user)
    {
        // User can delete their own replies, or instructor/admin can delete any
        return $this->user_id === $user->id || 
               in_array($user->role, ['instructor', 'admin']);
    }
}
