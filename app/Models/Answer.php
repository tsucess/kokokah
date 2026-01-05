<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'student_id',
        'answer_text',
        'score',
        'attempt_number',
        'points_earned',
        'points_possible',
        'is_correct'
    ];

    protected $casts = [
        'score' => 'integer',
        'attempt_number' => 'integer',
        'points_earned' => 'integer',
        'points_possible' => 'integer',
        'is_correct' => 'boolean'
    ];

    // Relationships
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function quiz()
    {
        return $this->question->quiz();
    }

    // Scopes
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByQuestion($query, $questionId)
    {
        return $query->where('question_id', $questionId);
    }

    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    // Methods
    public function isCorrect()
    {
        return $this->score > 0;
    }

    public function autoGrade()
    {
        if ($this->question->type === 'mcq') {
            $this->score = $this->question->isCorrectAnswer($this->answer) ? 1 : 0;
            $this->save();
        }
        // Theory questions need manual grading
    }
}
