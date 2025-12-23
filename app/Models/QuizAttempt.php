<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'attempt_number',
        'started_at',
        'submitted_at',
        'completed_at',
        'score',
        'max_score',
        'percentage',
        'passed',
        'time_taken',
        'ip_address',
        'user_agent',
        'answers_data',
        'status'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'passed' => 'boolean',
        'time_taken' => 'integer',
        'answers_data' => 'array'
    ];

    protected $dates = [
        'started_at',
        'submitted_at',
        'completed_at'
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'quiz_attempt_id');
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByQuiz($query, $quizId)
    {
        return $query->where('quiz_id', $quizId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopePassed($query)
    {
        return $query->where('passed', true);
    }

    public function scopeFailed($query)
    {
        return $query->where('passed', false);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('started_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getFormattedScoreAttribute()
    {
        return $this->score . '/' . $this->max_score;
    }

    public function getGradeAttribute()
    {
        if ($this->percentage >= 90) return 'A';
        if ($this->percentage >= 80) return 'B';
        if ($this->percentage >= 70) return 'C';
        if ($this->percentage >= 60) return 'D';
        return 'F';
    }

    public function getTimeTakenFormattedAttribute()
    {
        if (!$this->time_taken) return '0 minutes';
        
        $hours = floor($this->time_taken / 3600);
        $minutes = floor(($this->time_taken % 3600) / 60);
        $seconds = $this->time_taken % 60;
        
        $formatted = '';
        if ($hours > 0) $formatted .= $hours . 'h ';
        if ($minutes > 0) $formatted .= $minutes . 'm ';
        if ($seconds > 0 || empty($formatted)) $formatted .= $seconds . 's';
        
        return trim($formatted);
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'completed':
                return $this->passed ? 'success' : 'danger';
            case 'in_progress':
                return 'warning';
            case 'abandoned':
                return 'secondary';
            default:
                return 'primary';
        }
    }

    // Methods
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isAbandoned()
    {
        return $this->status === 'abandoned';
    }

    public function isPassed()
    {
        return $this->passed === true;
    }

    public function isFailed()
    {
        return $this->passed === false;
    }

    public function calculateScore()
    {
        $totalScore = 0;
        $maxScore = 0;

        foreach ($this->answers as $answer) {
            $totalScore += $answer->points_earned ?? 0;
            $maxScore += $answer->points_possible ?? 0;
        }

        $this->score = $totalScore;
        $this->max_score = $maxScore;
        $this->percentage = $maxScore > 0 ? round(($totalScore / $maxScore) * 100, 2) : 0;
        
        // Determine if passed based on quiz passing score
        $passingScore = $this->quiz->passing_score ?? 60;
        $this->passed = $this->percentage >= $passingScore;
        
        $this->save();
        
        return $this;
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'submitted_at' => now()
        ]);
        
        $this->calculateScore();
        return $this;
    }

    public function markAsAbandoned()
    {
        $this->update([
            'status' => 'abandoned',
            'completed_at' => now()
        ]);
        
        return $this;
    }

    public function calculateTimeTaken()
    {
        if ($this->started_at && $this->completed_at) {
            $this->time_taken = $this->completed_at->diffInSeconds($this->started_at);
            $this->save();
        }
        
        return $this;
    }

    public function getCorrectAnswersCount()
    {
        return $this->answers()->where('is_correct', true)->count();
    }

    public function getIncorrectAnswersCount()
    {
        return $this->answers()->where('is_correct', false)->count();
    }

    public function getTotalQuestionsCount()
    {
        return $this->answers()->count();
    }

    public function getAnswerBreakdown()
    {
        return [
            'total' => $this->getTotalQuestionsCount(),
            'correct' => $this->getCorrectAnswersCount(),
            'incorrect' => $this->getIncorrectAnswersCount(),
            'accuracy' => $this->getTotalQuestionsCount() > 0 
                ? round(($this->getCorrectAnswersCount() / $this->getTotalQuestionsCount()) * 100, 2) 
                : 0
        ];
    }

    // Static methods
    public static function createAttempt($quizId, $userId, $attemptNumber)
    {
        return static::create([
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'attempt_number' => $attemptNumber,
            'started_at' => now(),
            'status' => 'in_progress',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public static function getNextAttemptNumber($quizId, $userId)
    {
        return static::where('quiz_id', $quizId)
                    ->where('user_id', $userId)
                    ->max('attempt_number') + 1;
    }

    public static function getUserBestAttempt($quizId, $userId)
    {
        return static::where('quiz_id', $quizId)
                    ->where('user_id', $userId)
                    ->where('status', 'completed')
                    ->orderBy('score', 'desc')
                    ->first();
    }

    public static function getUserLatestAttempt($quizId, $userId)
    {
        return static::where('quiz_id', $quizId)
                    ->where('user_id', $userId)
                    ->latest('started_at')
                    ->first();
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attempt) {
            if (!$attempt->attempt_number) {
                $attempt->attempt_number = static::getNextAttemptNumber($attempt->quiz_id, $attempt->user_id);
            }
        });

        static::updating(function ($attempt) {
            // Auto-calculate time taken when completed
            if ($attempt->isDirty('completed_at') && $attempt->completed_at) {
                $attempt->calculateTimeTaken();
            }

            // Award points and badges when quiz is completed
            if ($attempt->isDirty('status') && $attempt->status === 'completed') {
                $pointsService = new \App\Services\PointsAndBadgesService();
                $pointsService->awardPointsForQuizPass($attempt->user, $attempt);
            }
        });
    }
}
