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
        'slug',
        'description',
        'curriculum_category_id',
        'course_category_id',
        'instructor_id',
        'term_id',
        'level_id',
        'free',
        'free_subscription',
        'status',
        'thumbnail',
        'url',
        'duration_hours',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'duration_hours' => 'integer',
        'free' => 'boolean',
    ];

    protected $appends = [
        'thumbnail_url',
    ];

    // Relationships
    public function curriculumCategory()
    {
        return $this->belongsTo(CurriculumCategory::class, 'curriculum_category_id');
    }

    public function courseCategory()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
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

    public function topics()
    {
        return $this->hasMany(Topic::class)->orderBy('order');
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

    public function subscriptionPlans()
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'course_subscription_plan')
                    ->withTimestamps();
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

    public function chatRoom()
    {
        return $this->hasOne(ChatRoom::class);
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

    // Accessors
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

    /**
     * Get the full URL for the thumbnail image
     */
    public function getThumbnailUrlAttribute()
    {
        if (!$this->thumbnail) {
            return null;
        }
        // Return relative path for better compatibility with different hosts
        return '/storage/' . $this->thumbnail;
    }

    /**
     * Generate a unique slug from the course title
     */
    public static function generateSlug($title)
    {
        $slug = \Illuminate\Support\Str::slug($title);

        // Check if slug already exists
        $count = self::where('slug', $slug)->count();

        // If slug exists, append a number to make it unique
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        return $slug;
    }
}



