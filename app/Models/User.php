<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerificationCodeNotification;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

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
        'gender',
        'level_id',
        'date_of_birth',
        'address',
        'state',
        'zipcode',
        'profile_photo',
        'parent_first_name',
        'parent_last_name',
        'parent_email',
        'parent_phone',
        'email_verified_at',
        'points'
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
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'password' => 'hashed',
        'points' => 'integer',
    ];




    // Use custom notification for API reset links
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Send email verification notification with verification code
     */
    public function sendEmailVerificationNotification()
    {
        // Create verification code
        $verificationCode = \App\Models\VerificationCode::createForUser($this, 'email', 15);

        // Send notification with verification code
        $this->notify(new VerificationCodeNotification($verificationCode, 'email'));
    }


    // Relationships
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function curriculumCategories()
    {
        return $this->hasMany(CurriculumCategory::class);
    }

    public function courseCategories()
    {
        return $this->hasMany(CourseCategory::class);
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

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function chatSessions()
    {
        return $this->hasMany(ChatSession::class);
    }

    // Chat Room Relationships
    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class, 'chat_room_users')
                    ->withPivot('role', 'is_active', 'is_muted', 'is_pinned', 'joined_at', 'last_read_at', 'unread_count', 'notification_level')
                    ->withTimestamps();
    }

    public function createdChatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'created_by');
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function messageReactions()
    {
        return $this->hasMany(MessageReaction::class);
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

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscriptions()
    {
        return $this->hasMany(UserSubscription::class)
                    ->where('status', 'active')
                    ->where('expires_at', '>', now());
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

    public function pointsHistory()
    {
        return $this->hasMany(UserPointsHistory::class);
    }

    public function badgeCriteriaLogs()
    {
        return $this->hasMany(BadgeCriteriaLog::class);
    }

    public function levelHistory()
    {
        return $this->hasMany(UserLevelHistory::class);
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

    public function scopeSuperAdmins($query)
    {
        return $query->where('role', 'superadmin');
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

    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is a superadmin or admin
     */
    public function isAdminOrSuperAdmin()
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    /**
     * Check if user is an instructor or higher
     */
    public function isInstructorOrHigher()
    {
        return in_array($this->role, ['instructor', 'admin', 'superadmin']);
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
     * @param string $role The role to check (e.g., 'admin', 'instructor', 'student')
     * @return bool True if user has the specified role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     * @param array|string $roles The roles to check
     * @return bool True if user has any of the specified roles
     */
    public function hasAnyRole($roles): bool
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
        // Superadmin, admin and instructors can access any course
        if (in_array($this->role, ['superadmin', 'admin', 'instructor'])) {
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
    /**
     * Add points to user
     */
    public function addPoints($amount, $reason = null)
    {
        $this->increment('points', $amount);
        return $this;
    }

    /**
     * Deduct points from user
     */
    public function deductPoints($amount, $reason = null)
    {
        if ($this->points >= $amount) {
            $this->decrement('points', $amount);
            return true;
        }
        return false;
    }

    /**
     * Check if user has enough points
     */
    public function hasEnoughPoints($amount)
    {
        return $this->points >= $amount;
    }

    /**
     * Get user's current points
     */
    public function getPoints()
    {
        return $this->points ?? 0;
    }

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
