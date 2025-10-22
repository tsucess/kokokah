<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'completed_at',
        'time_spent'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'time_spent' => 'integer', // seconds
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByLesson($query, $lessonId)
    {
        return $query->where('lesson_id', $lessonId);
    }

    public function scopeCompletedToday($query)
    {
        return $query->whereDate('completed_at', today());
    }

    public function scopeCompletedThisWeek($query)
    {
        return $query->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    // Methods
    public function getTimeSpentFormatted()
    {
        $minutes = floor($this->time_spent / 60);
        $seconds = $this->time_spent % 60;
        return sprintf('%d:%02d', $minutes, $seconds);
    }

    // Boot method to update enrollment progress
    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($completion) {
            // Update enrollment progress when lesson is completed
            $enrollment = Enrollment::where('user_id', $completion->user_id)
                                  ->where('course_id', $completion->lesson->course_id)
                                  ->first();
            
            if ($enrollment) {
                $enrollment->updateProgress();
            }
        });
    }
}
