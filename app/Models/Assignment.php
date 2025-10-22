<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'due_date',
        'max_score',
        'allowed_file_types',
        'max_file_size_mb'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'max_score' => 'integer',
        'allowed_file_types' => 'array',
        'max_file_size_mb' => 'integer',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    // Scopes
    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('due_date', '>', now());
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now());
    }

    // Methods
    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast();
    }

    public function getDaysUntilDue()
    {
        if (!$this->due_date) return null;
        return now()->diffInDays($this->due_date, false);
    }

    public function getSubmissionByUser(User $user)
    {
        return $this->submissions()->where('student_id', $user->id)->first();
    }

    public function hasUserSubmitted(User $user)
    {
        return $this->submissions()->where('student_id', $user->id)->exists();
    }

    public function getSubmissionRate()
    {
        $totalStudents = $this->course->enrollments()->where('status', 'active')->count();
        if ($totalStudents === 0) return 0;
        
        $submissions = $this->submissions()->count();
        return round(($submissions / $totalStudents) * 100, 2);
    }

    public function getAverageGrade()
    {
        return $this->submissions()->whereNotNull('grade')->avg('grade');
    }
}
