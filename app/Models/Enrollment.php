<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'progress',
        'status',
        'enrolled_at',
        'completed_at',
        'amount_paid'
    ];

    protected $casts = [
        'progress' => 'integer',
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'amount_paid' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDropped($query)
    {
        return $query->where('status', 'dropped');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    // Methods
    public function updateProgress()
    {
        $totalLessons = $this->course->lessons()->count();
        if ($totalLessons === 0) {
            $this->progress = 100;
            $this->save();
            return;
        }

        $completedLessons = $this->course->lessons()
                                ->whereHas('completions', function ($query) {
                                    $query->where('user_id', $this->user_id);
                                })
                                ->count();

        $this->progress = round(($completedLessons / $totalLessons) * 100);
        
        // Auto-complete course if all lessons are done
        if ($this->progress >= 100 && $this->status === 'active') {
            $this->status = 'completed';
            $this->completed_at = now();
        }
        
        $this->save();
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isDropped()
    {
        return $this->status === 'dropped';
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress' => 100
        ]);
    }

    public function markAsDropped()
    {
        $this->update([
            'status' => 'dropped'
        ]);
    }
}
