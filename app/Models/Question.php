<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'type',
        'options',
        'correct_answer',
        'points',
        'explanation'
    ];

    protected $casts = [
        'options' => 'array',
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByQuiz($query, $quizId)
    {
        return $query->where('quiz_id', $quizId);
    }

    // Methods
    public function isCorrectAnswer($answer)
    {
        if ($this->type === 'mcq') {
            return $answer === $this->correct_answer;
        }
        
        // For theory questions, you might want to implement more complex checking
        // For now, we'll return true and let instructors grade manually
        return true;
    }

    public function getAnswerByUser(User $user)
    {
        return $this->answers()->where('student_id', $user->id)->first();
    }

    public function hasUserAnswered(User $user)
    {
        return $this->answers()->where('student_id', $user->id)->exists();
    }

    public function getCorrectAnswersCount()
    {
        return $this->answers()->where('score', '>', 0)->count();
    }

    public function getTotalAnswersCount()
    {
        return $this->answers()->count();
    }

    public function getSuccessRate()
    {
        $total = $this->getTotalAnswersCount();
        if ($total === 0) return 0;
        
        $correct = $this->getCorrectAnswersCount();
        return round(($correct / $total) * 100, 2);
    }
}
