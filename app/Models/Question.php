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
        // MCQ and Alternate types use exact string comparison
        return strtolower(trim($answer)) === strtolower(trim($this->correct_answer));
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
