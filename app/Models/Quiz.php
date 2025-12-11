<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'topic_id',
        'title',
        'slug',
        'description',
        'type',
        'time_limit_minutes',
        'max_attempts',
        'passing_score',
        'shuffle_questions'
    ];

    protected $casts = [
        'time_limit_minutes' => 'integer',
        'max_attempts' => 'integer',
        'passing_score' => 'integer',
        'shuffle_questions' => 'boolean',
    ];

    // Relationships
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function answers()
    {
        return $this->hasManyThrough(Answer::class, Question::class);
    }

    public function course()
    {
        return $this->hasOneThrough(Course::class, Lesson::class, 'id', 'id', 'lesson_id', 'course_id');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByLesson($query, $lessonId)
    {
        return $query->where('lesson_id', $lessonId);
    }

    // Methods
    public function getQuestionsForUser(User $user)
    {
        $questions = $this->questions();
        
        if ($this->shuffle_questions) {
            $questions = $questions->inRandomOrder();
        }
        
        return $questions->get();
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function getUserAttempts(User $user)
    {
        return $this->answers()
                    ->where('student_id', $user->id)
                    ->distinct('created_at')
                    ->count();
    }

    public function canUserAttempt(User $user)
    {
        $attempts = $this->getUserAttempts($user);
        return $attempts < $this->max_attempts;
    }

    public function getUserScore(User $user)
    {
        $totalQuestions = $this->questions()->count();
        if ($totalQuestions === 0) return 0;

        $correctAnswers = $this->answers()
                              ->where('student_id', $user->id)
                              ->where('score', '>', 0)
                              ->count();

        return round(($correctAnswers / $totalQuestions) * 100, 2);
    }

    public function hasUserPassed(User $user)
    {
        return $this->getUserScore($user) >= $this->passing_score;
    }
}
