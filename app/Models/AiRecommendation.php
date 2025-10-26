<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'reason'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Methods
    public function hasUserEnrolled()
    {
        return $this->user->enrollments()
                         ->where('course_id', $this->course_id)
                         ->exists();
    }

    public function markAsViewed()
    {
        // You could add a viewed_at timestamp if needed
        $this->touch();
    }
}
