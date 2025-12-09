<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'topic_id',
        'title',
        'content',

        // Video fields
        'video_url',
        'video_type',
        'lesson_type',

        // Attachment fields
        'attachment',
        'attachment_type',

        // Summary
        'summary',

        // Mobile application video fields
        'video_type_for_mobile_application',
        'video_url_for_mobile_application',
        'duration_for_mobile_application',

        'order',
        'duration_minutes'
    ];

    protected $casts = [
        'order' => 'integer',
        'duration_minutes' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }
     

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function completedBy()
    {
        return $this->belongsToMany(User::class, 'lesson_completions')
                    ->withPivot('completed_at', 'time_spent')
                    ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeByTopic($query, $topicId)
    {
        return $query->where('topic_id', $topicId);
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function isCompletedBy(User $user)
    {
        return $this->completions()->where('user_id', $user->id)->exists();
    }

    public function getCompletionRate()
    {
        $totalStudents = $this->course->enrollments()->where('status', 'active')->count();
        if ($totalStudents === 0) return 0;

        $completions = $this->completions()->count();
        return round(($completions / $totalStudents) * 100, 2);
    }

    public function getNextLesson()
    {
        return $this->course->lessons()
                            ->where('order', '>', $this->order)
                            ->orderBy('order')
                            ->first();
    }

    public function getPreviousLesson()
    {
        return $this->course->lessons()
                            ->where('order', '<', $this->order)
                            ->orderBy('order', 'desc')
                            ->first();
    }
}



// class Lesson extends Model
// {
//     use HasFactory, SoftDeletes;

//     protected $fillable = [
//         'course_id',
//         'title',
//         'content',
//         'video_url',
//         'attachment',
//         'order',
//         'duration_minutes',
//         'is_free'
//     ];

//     protected $casts = [
//         'order' => 'integer',
//         'duration_minutes' => 'integer',
//         'is_free' => 'boolean',
//     ];

//     // Relationships
//     public function course()
//     {
//         return $this->belongsTo(Course::class);
//     }

//     public function quizzes()
//     {
//         return $this->hasMany(Quiz::class);
//     }

//     public function completions()
//     {
//         return $this->hasMany(LessonCompletion::class);
//     }

//     public function completedBy()
//     {
//         return $this->belongsToMany(User::class, 'lesson_completions')
//                     ->withPivot('completed_at', 'time_spent')
//                     ->withTimestamps();
//     }

//     // Scopes
//     public function scopeOrdered($query)
//     {
//         return $query->orderBy('order');
//     }

//     public function scopeFree($query)
//     {
//         return $query->where('is_free', true);
//     }

//     public function scopeByCourse($query, $courseId)
//     {
//         return $query->where('course_id', $courseId);
//     }

//     // Methods
//     public function isCompletedBy(User $user)
//     {
//         return $this->completions()->where('user_id', $user->id)->exists();
//     }

//     public function getCompletionRate()
//     {
//         $totalStudents = $this->course->enrollments()->where('status', 'active')->count();
//         if ($totalStudents === 0) return 0;
        
//         $completions = $this->completions()->count();
//         return round(($completions / $totalStudents) * 100, 2);
//     }

//     public function getNextLesson()
//     {
//         return $this->course->lessons()
//                     ->where('order', '>', $this->order)
//                     ->orderBy('order')
//                     ->first();
//     }

//     public function getPreviousLesson()
//     {
//         return $this->course->lessons()
//                     ->where('order', '<', $this->order)
//                     ->orderBy('order', 'desc')
//                     ->first();
//     }
// }
