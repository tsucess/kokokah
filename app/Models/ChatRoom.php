<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ChatRoom extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'type',
        'course_id',
        'created_by',
        'background_image',
        'icon',
        'color',
        'is_active',
        'is_archived',
        'member_count',
        'message_count',
        'last_message_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_archived' => 'boolean',
        'last_message_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be appended to arrays.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'background_image_url',
        'is_general',
        'is_course',
    ];

    // ============ RELATIONSHIPS ============

    /**
     * Get the creator of the chat room.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the course associated with this chat room.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get all users in this chat room.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_room_users')
                    ->withPivot('role', 'is_active', 'is_muted', 'is_pinned', 'joined_at', 'last_read_at', 'unread_count', 'notification_level')
                    ->withTimestamps();
    }

    /**
     * Get all messages in this chat room.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    // ============ SCOPES ============

    /**
     * Scope to get only general chat rooms.
     */
    public function scopeGeneralRooms($query)
    {
        return $query->where('type', 'general');
    }

    /**
     * Scope to get only course-specific chat rooms.
     */
    public function scopeCourseRooms($query)
    {
        return $query->where('type', 'course');
    }

    /**
     * Scope to get only active chat rooms.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only non-archived chat rooms.
     */
    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope to get chat rooms with recent activity.
     */
    public function scopeWithRecentActivity($query, $hours = 24)
    {
        return $query->where('last_message_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope to get chat rooms for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId)->where('is_active', true);
        });
    }

    // ============ ACCESSORS ============

    /**
     * Get the background image URL.
     */
    public function getBackgroundImageUrlAttribute(): ?string
    {
        if (!$this->background_image) {
            return null;
        }

        // If it's already a full URL, return as is
        if (str_starts_with($this->background_image, 'http')) {
            return $this->background_image;
        }

        // Otherwise, construct the storage URL
        return asset('storage/' . $this->background_image);
    }

    /**
     * Check if this is a general room.
     */
    public function getIsGeneralAttribute(): bool
    {
        return $this->type === 'general';
    }

    /**
     * Check if this is a course room.
     */
    public function getIsCourseAttribute(): bool
    {
        return $this->type === 'course';
    }
}

