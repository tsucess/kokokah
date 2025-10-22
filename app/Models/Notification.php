<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'action_url',
        'action_text',
        'priority',
        'category',
        'expires_at',
        'sender_id',
        'related_model_type',
        'related_model_id'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    protected $dates = [
        'read_at',
        'expires_at'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function relatedModel()
    {
        return $this->morphTo('related_model', 'related_model_type', 'related_model_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getIsReadAttribute()
    {
        return !is_null($this->read_at);
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Methods
    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
        return $this;
    }

    public function markAsUnread()
    {
        $this->update(['read_at' => null]);
        return $this;
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function isUnread()
    {
        return is_null($this->read_at);
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function hasAction()
    {
        return !empty($this->action_url);
    }

    // Static methods for creating notifications
    public static function createForUser($userId, $type, $title, $message, $data = [])
    {
        return static::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'category' => $data['category'] ?? 'general',
            'priority' => $data['priority'] ?? 'normal'
        ]);
    }

    public static function createCourseNotification($userId, $courseId, $title, $message, $type = 'course_update')
    {
        return static::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'category' => 'course',
            'priority' => 'normal',
            'related_model_type' => Course::class,
            'related_model_id' => $courseId,
            'data' => ['course_id' => $courseId]
        ]);
    }

    public static function createSystemNotification($userId, $title, $message, $priority = 'normal')
    {
        return static::create([
            'user_id' => $userId,
            'type' => 'system',
            'title' => $title,
            'message' => $message,
            'category' => 'system',
            'priority' => $priority
        ]);
    }

    public static function broadcastToRole($role, $title, $message, $type = 'announcement')
    {
        $users = User::where('role', $role)->get();
        $notifications = [];

        foreach ($users as $user) {
            $notifications[] = [
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'category' => 'announcement',
                'priority' => 'normal',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        static::insert($notifications);
        return count($notifications);
    }

    public static function broadcastToAll($title, $message, $type = 'announcement')
    {
        $users = User::all();
        $notifications = [];

        foreach ($users as $user) {
            $notifications[] = [
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'category' => 'announcement',
                'priority' => 'normal',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        static::insert($notifications);
        return count($notifications);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($notification) {
            // Set default values
            if (!$notification->priority) {
                $notification->priority = 'normal';
            }
            if (!$notification->category) {
                $notification->category = 'general';
            }
        });
    }
}
