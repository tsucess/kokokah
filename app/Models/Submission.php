<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_url',
        'grade',
        'feedback',
        'submitted_at'
    ];

    protected $casts = [
        'grade' => 'integer',
        'submitted_at' => 'datetime',
    ];

    // Relationships
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Scopes
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByAssignment($query, $assignmentId)
    {
        return $query->where('assignment_id', $assignmentId);
    }

    public function scopeGraded($query)
    {
        return $query->whereNotNull('grade');
    }

    public function scopeUngraded($query)
    {
        return $query->whereNull('grade');
    }

    // Methods
    public function isGraded()
    {
        return !is_null($this->grade);
    }

    public function isLate()
    {
        if (!$this->assignment->due_date || !$this->submitted_at) return false;
        return $this->submitted_at->isAfter($this->assignment->due_date);
    }

    public function getGradePercentage()
    {
        if (!$this->isGraded()) return null;
        return round(($this->grade / $this->assignment->max_score) * 100, 2);
    }

    public function getLetterGrade()
    {
        $percentage = $this->getGradePercentage();
        if ($percentage === null) return null;
        
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }
}
