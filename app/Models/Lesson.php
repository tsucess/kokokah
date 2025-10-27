<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'content',
        'video_url',
        'attachment',
        'order',
        'duration_minutes',
        'is_free'
    ];

    protected $casts = [
        'order' => 'integer',
        'duration_minutes' => 'integer',
        'is_free' => 'boolean',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function completedBy()
    {
        return $this->belongsToMany(User::class, 'lesson_completions')
                    ->withPivot('completed_at', 'time_spent')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    // Methods
    public function isCompletedBy(User $user)
    {
        return $this->completions()->where('user_id', $user->id)->exists();
    }

    public function getCompletionRate()
    {
        $totalStudents = $this->course->enrollments()->where('status', 'active')->count();
        if ($totalStudents === 0) return 0;
        
        $completions = $this->completions()->count();
        return round(($completions / $totalStudents) * 100, 2);
    }

    public function getNextLesson()
    {
        return $this->course->lessons()
                    ->where('order', '>', $this->order)
                    ->orderBy('order')
                    ->first();
    }

    public function getPreviousLesson()
    {
        return $this->course->lessons()
                    ->where('order', '<', $this->order)
                    ->orderBy('order', 'desc')
                    ->first();
    }
}
