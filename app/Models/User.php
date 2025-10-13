<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'is_active',
        'identifier',
        'contact',
        'gender'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password' => 'hashed',
    //     ];
    // }
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];




    // Use custom notification for API reset links
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }


    // Relationships
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function instructedCourses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
                    ->withPivot('progress', 'status', 'enrolled_at', 'completed_at')
                    ->withTimestamps();
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'student_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function chatSessions()
    {
        return $this->hasMany(ChatSession::class);
    }

    public function aiRecommendations()
    {
        return $this->hasMany(AiRecommendation::class);
    }

    public function courseReviews()
    {
        return $this->hasMany(CourseReview::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function notificationPreferences()
    {
        return $this->hasOne(NotificationPreference::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function learningPathEnrollments()
    {
        return $this->hasMany(LearningPathEnrollment::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
                    ->withPivot('earned_at')
                    ->withTimestamps();
    }

    public function favoriteCourses()
    {
        return $this->belongsToMany(Course::class, 'user_favorites')->withTimestamps();
    }

    public function lessonCompletions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_completions')
                    ->withPivot('completed_at', 'time_spent')
                    ->withTimestamps();
    }

    public function forumTopics()
    {
        return $this->hasMany(ForumTopic::class);
    }

    public function forumReplies()
    {
        return $this->hasMany(ForumReply::class);
    }

    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function createdLearningPaths()
    {
        return $this->hasMany(LearningPath::class, 'created_by');
    }

    public function rewards()
    {
        return $this->hasMany(UserReward::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    public function scopeInstructors($query)
    {
        return $query->where('role', 'instructor');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeByLevel($query, $levelId)
    {
        return $query->where('level_id', $levelId);
    }

    // Helper Methods
    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isInstructor()
    {
        return $this->role === 'instructor';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getOrCreateWallet()
    {
        return $this->wallet ?: $this->wallet()->create(['balance' => 0]);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles)
    {
        return in_array($this->role, (array) $roles);
    }

    public function hasCompletedCourse(Course $course)
    {
        return $this->enrollments()
                   ->where('course_id', $course->id)
                   ->where('status', 'completed')
                   ->exists();
    }

    public function isEnrolledIn(Course $course)
    {
        return $this->enrollments()
                   ->where('course_id', $course->id)
                   ->whereIn('status', ['active', 'completed'])
                   ->exists();
    }

    public function canAccessCourse(Course $course)
    {
        // Admin and instructors can access any course
        if (in_array($this->role, ['admin', 'instructor'])) {
            return true;
        }

        // Students need to be enrolled
        return $this->isEnrolledIn($course);
    }

    public function getTotalCoursesCompleted()
    {
        return $this->enrollments()->where('status', 'completed')->count();
    }

    public function getTotalLearningTime()
    {
        return $this->lessonCompletions()->sum('time_spent');
    }

    public function getAverageRatingAsInstructor()
    {
        return $this->instructedCourses()
                   ->join('course_reviews', 'courses.id', '=', 'course_reviews.course_id')
                   ->avg('course_reviews.rating');
    }

    // Boot method for creating wallet
    protected static function booted()
    {
        parent::booted();

        static::created(function ($user) {
            if (empty($user->identifier)) {
                $user->identifier = 'KOKOKAH-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
                $user->saveQuietly();
            }

            // Create wallet for new user
            $user->wallet()->create(['balance' => 0]);

            // Create notification preferences for new user
            $user->notificationPreferences()->create(NotificationPreference::getDefaultPreferences());
        });
    }
}
