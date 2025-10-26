<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function topics()
    {
        return $this->hasMany(ForumTopic::class)->orderBy('is_pinned', 'desc')->orderBy('created_at', 'desc');
    }

    public function pinnedTopics()
    {
        return $this->hasMany(ForumTopic::class)->where('is_pinned', true)->orderBy('created_at', 'desc');
    }

    public function regularTopics()
    {
        return $this->hasMany(ForumTopic::class)->where('is_pinned', false)->orderBy('created_at', 'desc');
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

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Methods
    public function getTopicCount()
    {
        return $this->topics()->count();
    }

    public function getReplyCount()
    {
        return ForumReply::whereHas('topic', function ($query) {
            $query->where('forum_id', $this->id);
        })->count();
    }

    public function getLatestTopic()
    {
        return $this->topics()->latest()->first();
    }

    public function getLatestActivity()
    {
        $latestTopic = $this->topics()->latest()->first();
        $latestReply = ForumReply::whereHas('topic', function ($query) {
            $query->where('forum_id', $this->id);
        })->latest()->first();

        if (!$latestTopic && !$latestReply) return null;
        if (!$latestReply) return $latestTopic->created_at;
        if (!$latestTopic) return $latestReply->created_at;

        return $latestTopic->created_at->gt($latestReply->created_at) 
               ? $latestTopic->created_at 
               : $latestReply->created_at;
    }
}
