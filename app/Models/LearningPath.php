<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LearningPath extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'created_by',
        'is_published',
        'difficulty',
        'estimated_hours',
        'category',
        'difficulty_level',
        'estimated_duration',
        'prerequisites',
        'learning_objectives',
        'image_path',
        'creator_id',
        'status'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'estimated_hours' => 'integer',
        'estimated_duration' => 'integer',
        'learning_objectives' => 'array',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'learning_path_courses')
                    ->withPivot('sort_order', 'is_required')
                    ->orderBy('learning_path_courses.sort_order')
                    ->withTimestamps();
    }

    public function requiredCourses()
    {
        return $this->belongsToMany(Course::class, 'learning_path_courses')
                    ->wherePivot('is_required', true)
                    ->orderBy('learning_path_courses.sort_order')
                    ->withTimestamps();
    }

    public function optionalCourses()
    {
        return $this->belongsToMany(Course::class, 'learning_path_courses')
                    ->wherePivot('is_required', false)
                    ->orderBy('learning_path_courses.sort_order')
                    ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(CourseReview::class, 'learning_path_id');
    }

    public function enrollments()
    {
        return $this->hasMany(LearningPathEnrollment::class);
    }

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($learningPath) {
            if (empty($learningPath->slug)) {
                $learningPath->slug = Str::slug($learningPath->title);
            }
        });
        
        static::updating(function ($learningPath) {
            if ($learningPath->isDirty('title')) {
                $learningPath->slug = Str::slug($learningPath->title);
            }
        });
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    public function scopeByCreator($query, $creatorId)
    {
        return $query->where('created_by', $creatorId);
    }

    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    // Methods
    public function addCourse(Course $course, $sortOrder = null, $isRequired = true)
    {
        if ($sortOrder === null) {
            $sortOrder = $this->courses()->count() + 1;
        }

        $this->courses()->attach($course->id, [
            'sort_order' => $sortOrder,
            'is_required' => $isRequired
        ]);
    }

    public function removeCourse(Course $course)
    {
        $this->courses()->detach($course->id);
    }

    public function reorderCourses(array $courseIds)
    {
        foreach ($courseIds as $index => $courseId) {
            $this->courses()->updateExistingPivot($courseId, [
                'sort_order' => $index + 1
            ]);
        }
    }

    public function getUserProgress(User $user)
    {
        $totalCourses = $this->requiredCourses()->count();
        if ($totalCourses === 0) return 100;

        $completedCourses = $this->requiredCourses()
                                ->whereHas('enrollments', function ($query) use ($user) {
                                    $query->where('user_id', $user->id)
                                          ->where('status', 'completed');
                                })
                                ->count();

        return round(($completedCourses / $totalCourses) * 100, 2);
    }

    public function hasUserCompleted(User $user)
    {
        return $this->getUserProgress($user) >= 100;
    }

    public function getNextCourseForUser(User $user)
    {
        return $this->requiredCourses()
                   ->whereDoesntHave('enrollments', function ($query) use ($user) {
                       $query->where('user_id', $user->id)
                             ->whereIn('status', ['completed', 'active']);
                   })
                   ->first();
    }

    public function getTotalDuration()
    {
        return $this->courses()->sum('duration_hours') ?: $this->estimated_hours;
    }

    public function getCourseCount()
    {
        return $this->courses()->count();
    }

    public function getRequiredCourseCount()
    {
        return $this->requiredCourses()->count();
    }
}
