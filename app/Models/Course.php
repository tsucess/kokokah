<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'instructor_id',
        'term_id',
        'level_id',
        'price',
        'status',
        'duration_hours',
        'published_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'published_at' => 'datetime',
        'duration_hours' => 'integer',
        'max_students' => 'integer',
    ];

    // Relationships
    public function curriculumCategory()
    {
        return $this->belongsTo(CurriculumCategory::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
                    ->withPivot('progress', 'status', 'enrolled_at', 'completed_at')
                    ->withTimestamps();
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function reviews()
    {
        return $this->hasMany(CourseReview::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function aiRecommendations()
    {
        return $this->hasMany(AiRecommendation::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'course_tags')->withTimestamps();
    }

    public function prerequisites()
    {
        return $this->belongsToMany(Course::class, 'course_prerequisites', 'course_id', 'prerequisite_course_id');
    }

    public function dependentCourses()
    {
        return $this->belongsToMany(Course::class, 'course_prerequisites', 'prerequisite_course_id', 'course_id');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'user_favorites')->withTimestamps();
    }

    public function forums()
    {
        return $this->hasMany(Forum::class);
    }

    public function analytics()
    {
        return $this->hasMany(CourseAnalytic::class);
    }

    public function quizzes()
    {
        return $this->hasManyThrough(Quiz::class, Lesson::class, 'course_id', 'lesson_id', 'id', 'id');
    }

    public function learningPaths()
    {
        return $this->belongsToMany(LearningPath::class, 'learning_path_courses')
                    ->withPivot('sort_order', 'is_required')
                    ->withTimestamps();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByLevel($query, $levelId)
    {
        return $query->where('level_id', $levelId);
    }

    public function scopeByInstructor($query, $instructorId)
    {
        return $query->where('instructor_id', $instructorId);
    }

    // Accessors & Mutators
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating');
    }

    public function getTotalStudentsAttribute()
    {
        return $this->enrollments()->count();
    }

    public function getCompletionRateAttribute()
    {
        $total = $this->enrollments()->count();
        if ($total === 0) return 0;
        
        $completed = $this->enrollments()->where('status', 'completed')->count();
        return round(($completed / $total) * 100, 2);
    }

    public function getIsFullAttribute()
    {
        if (!$this->max_students) return false;
        return $this->enrollments()->where('status', 'active')->count() >= $this->max_students;
    }
}
