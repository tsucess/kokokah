<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningPathEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'learning_path_id',
        'enrolled_at',
        'started_at',
        'completed_at',
        'progress_percentage',
        'current_course_id',
        'status',
        'completion_time_hours',
        'certificate_issued',
        'certificate_id'
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_percentage' => 'decimal:2',
        'completion_time_hours' => 'decimal:2',
        'certificate_issued' => 'boolean'
    ];

    protected $dates = [
        'enrolled_at',
        'started_at',
        'completed_at'
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

    public function learningPath()
    {
        return $this->belongsTo(LearningPath::class);
    }

    public function currentCourse()
    {
        return $this->belongsTo(Course::class, 'current_course_id');
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByLearningPath($query, $pathId)
    {
        return $query->where('learning_path_id', $pathId);
    }

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

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['active', 'in_progress']);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('enrolled_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'completed':
                return 'success';
            case 'active':
            case 'in_progress':
                return 'primary';
            case 'dropped':
                return 'danger';
            case 'paused':
                return 'warning';
            default:
                return 'secondary';
        }
    }

    public function getCompletionTimeFormattedAttribute()
    {
        if (!$this->completion_time_hours) return 'Not completed';
        
        $hours = floor($this->completion_time_hours);
        $minutes = round(($this->completion_time_hours - $hours) * 60);
        
        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }
        
        return $minutes . 'm';
    }

    public function getProgressBarWidthAttribute()
    {
        return min($this->progress_percentage, 100);
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isDropped()
    {
        return $this->status === 'dropped';
    }

    public function isPaused()
    {
        return $this->status === 'paused';
    }

    public function calculateProgress()
    {
        $learningPath = $this->learningPath;
        $requiredCourses = $learningPath->requiredCourses();
        $totalCourses = $requiredCourses->count();
        
        if ($totalCourses === 0) {
            $this->progress_percentage = 100;
            $this->save();
            return 100;
        }
        
        $completedCourses = $requiredCourses->whereHas('enrollments', function ($query) {
            $query->where('user_id', $this->user_id)
                  ->where('status', 'completed');
        })->count();
        
        $progress = round(($completedCourses / $totalCourses) * 100, 2);
        $this->progress_percentage = $progress;
        
        // Auto-complete if all courses are done
        if ($progress >= 100 && $this->status !== 'completed') {
            $this->markAsCompleted();
        }
        
        $this->save();
        return $progress;
    }

    public function updateCurrentCourse()
    {
        $nextCourse = $this->learningPath->getNextCourseForUser($this->user);
        
        if ($nextCourse) {
            $this->current_course_id = $nextCourse->id;
            if (!$this->started_at) {
                $this->started_at = now();
                $this->status = 'in_progress';
            }
        } else {
            $this->current_course_id = null;
        }
        
        $this->save();
        return $this;
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress_percentage' => 100
        ]);
        
        $this->calculateCompletionTime();
        $this->generateCertificate();
        
        return $this;
    }

    public function markAsDropped($reason = null)
    {
        $this->update([
            'status' => 'dropped',
            'dropped_at' => now(),
            'drop_reason' => $reason
        ]);
        
        return $this;
    }

    public function pause()
    {
        $this->update(['status' => 'paused']);
        return $this;
    }

    public function resume()
    {
        $this->update(['status' => 'active']);
        return $this;
    }

    public function calculateCompletionTime()
    {
        if ($this->started_at && $this->completed_at) {
            $hours = $this->completed_at->diffInHours($this->started_at);
            $this->completion_time_hours = $hours;
            $this->save();
        }
        
        return $this;
    }

    public function generateCertificate()
    {
        if ($this->isCompleted() && !$this->certificate_issued) {
            $certificate = Certificate::create([
                'user_id' => $this->user_id,
                'course_id' => null, // Learning path certificate
                'learning_path_id' => $this->learning_path_id,
                'certificate_number' => Certificate::generateCertificateNumber(),
                'issued_at' => now(),
                'type' => 'learning_path',
                'title' => 'Learning Path Completion Certificate',
                'description' => 'Successfully completed the learning path: ' . $this->learningPath->title
            ]);
            
            $this->update([
                'certificate_issued' => true,
                'certificate_id' => $certificate->id
            ]);
            
            return $certificate;
        }
        
        return null;
    }

    public function getCompletedCourses()
    {
        return $this->learningPath->courses()
                   ->whereHas('enrollments', function ($query) {
                       $query->where('user_id', $this->user_id)
                             ->where('status', 'completed');
                   })
                   ->get();
    }

    public function getRemainingCourses()
    {
        return $this->learningPath->courses()
                   ->whereDoesntHave('enrollments', function ($query) {
                       $query->where('user_id', $this->user_id)
                             ->where('status', 'completed');
                   })
                   ->get();
    }

    public function getEstimatedTimeRemaining()
    {
        $remainingCourses = $this->getRemainingCourses();
        return $remainingCourses->sum('duration_hours');
    }

    // Static methods
    public static function createEnrollment($userId, $learningPathId)
    {
        return static::create([
            'user_id' => $userId,
            'learning_path_id' => $learningPathId,
            'enrolled_at' => now(),
            'status' => 'active',
            'progress_percentage' => 0
        ]);
    }

    public static function getUserProgress($userId, $learningPathId)
    {
        $enrollment = static::where('user_id', $userId)
                           ->where('learning_path_id', $learningPathId)
                           ->first();
        
        return $enrollment ? $enrollment->calculateProgress() : 0;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::created(function ($enrollment) {
            $enrollment->updateCurrentCourse();
        });

        static::updating(function ($enrollment) {
            // Auto-update current course when progress changes
            if ($enrollment->isDirty('progress_percentage')) {
                $enrollment->updateCurrentCourse();
            }
        });
    }
}
