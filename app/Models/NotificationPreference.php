<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_enabled',
        'push_enabled',
        'sms_enabled',
        'course_updates',
        'assignment_reminders',
        'quiz_reminders',
        'forum_replies',
        'new_courses',
        'promotions',
        'system_announcements',
        'achievement_notifications',
        'payment_notifications',
        'security_alerts',
        'weekly_digest',
        'marketing_emails',
        'frequency',
        'quiet_hours_start',
        'quiet_hours_end',
        'timezone'
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'course_updates' => 'boolean',
        'assignment_reminders' => 'boolean',
        'quiz_reminders' => 'boolean',
        'forum_replies' => 'boolean',
        'new_courses' => 'boolean',
        'promotions' => 'boolean',
        'system_announcements' => 'boolean',
        'achievement_notifications' => 'boolean',
        'payment_notifications' => 'boolean',
        'security_alerts' => 'boolean',
        'weekly_digest' => 'boolean',
        'marketing_emails' => 'boolean',
        'quiet_hours_start' => 'datetime',
        'quiet_hours_end' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeEmailEnabled($query)
    {
        return $query->where('email_enabled', true);
    }

    public function scopePushEnabled($query)
    {
        return $query->where('push_enabled', true);
    }

    public function scopeSmsEnabled($query)
    {
        return $query->where('sms_enabled', true);
    }

    // Methods
    public function isNotificationEnabled($type, $channel = 'email')
    {
        // Check if the channel is enabled
        $channelEnabled = $this->{$channel . '_enabled'} ?? false;
        if (!$channelEnabled) {
            return false;
        }

        // Check if the specific notification type is enabled
        $typeEnabled = $this->{$type} ?? false;
        if (!$typeEnabled) {
            return false;
        }

        // Check quiet hours
        if ($this->isInQuietHours()) {
            return false;
        }

        return true;
    }

    public function isInQuietHours()
    {
        if (!$this->quiet_hours_start || !$this->quiet_hours_end) {
            return false;
        }

        $now = now($this->timezone ?? 'UTC');
        $start = $this->quiet_hours_start->setTimezone($this->timezone ?? 'UTC');
        $end = $this->quiet_hours_end->setTimezone($this->timezone ?? 'UTC');

        // Handle overnight quiet hours (e.g., 22:00 to 06:00)
        if ($start->greaterThan($end)) {
            return $now->greaterThanOrEqualTo($start) || $now->lessThanOrEqualTo($end);
        }

        return $now->between($start, $end);
    }

    public function enableAll()
    {
        $this->update([
            'email_enabled' => true,
            'push_enabled' => true,
            'course_updates' => true,
            'assignment_reminders' => true,
            'quiz_reminders' => true,
            'forum_replies' => true,
            'new_courses' => true,
            'system_announcements' => true,
            'achievement_notifications' => true,
            'payment_notifications' => true,
            'security_alerts' => true
        ]);
    }

    public function disableAll()
    {
        $this->update([
            'email_enabled' => false,
            'push_enabled' => false,
            'sms_enabled' => false,
            'course_updates' => false,
            'assignment_reminders' => false,
            'quiz_reminders' => false,
            'forum_replies' => false,
            'new_courses' => false,
            'promotions' => false,
            'system_announcements' => false,
            'achievement_notifications' => false,
            'payment_notifications' => false,
            'weekly_digest' => false,
            'marketing_emails' => false
        ]);
    }

    public function enableEssentialOnly()
    {
        $this->update([
            'email_enabled' => true,
            'push_enabled' => false,
            'sms_enabled' => false,
            'course_updates' => true,
            'assignment_reminders' => true,
            'quiz_reminders' => true,
            'forum_replies' => false,
            'new_courses' => false,
            'promotions' => false,
            'system_announcements' => true,
            'achievement_notifications' => false,
            'payment_notifications' => true,
            'security_alerts' => true,
            'weekly_digest' => false,
            'marketing_emails' => false
        ]);
    }

    public function getEnabledChannels()
    {
        $channels = [];
        
        if ($this->email_enabled) $channels[] = 'email';
        if ($this->push_enabled) $channels[] = 'push';
        if ($this->sms_enabled) $channels[] = 'sms';
        
        return $channels;
    }

    public function getEnabledNotificationTypes()
    {
        $types = [];
        
        if ($this->course_updates) $types[] = 'course_updates';
        if ($this->assignment_reminders) $types[] = 'assignment_reminders';
        if ($this->quiz_reminders) $types[] = 'quiz_reminders';
        if ($this->forum_replies) $types[] = 'forum_replies';
        if ($this->new_courses) $types[] = 'new_courses';
        if ($this->promotions) $types[] = 'promotions';
        if ($this->system_announcements) $types[] = 'system_announcements';
        if ($this->achievement_notifications) $types[] = 'achievement_notifications';
        if ($this->payment_notifications) $types[] = 'payment_notifications';
        if ($this->security_alerts) $types[] = 'security_alerts';
        if ($this->weekly_digest) $types[] = 'weekly_digest';
        if ($this->marketing_emails) $types[] = 'marketing_emails';
        
        return $types;
    }

    // Static methods
    public static function getDefaultPreferences()
    {
        return [
            'email_enabled' => true,
            'push_enabled' => true,
            'sms_enabled' => false,
            'course_updates' => true,
            'assignment_reminders' => true,
            'quiz_reminders' => true,
            'forum_replies' => true,
            'new_courses' => true,
            'promotions' => false,
            'system_announcements' => true,
            'achievement_notifications' => true,
            'payment_notifications' => true,
            'security_alerts' => true,
            'weekly_digest' => true,
            'marketing_emails' => false,
            'frequency' => 'immediate',
            'timezone' => 'UTC'
        ];
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($preference) {
            // Set default values if not provided
            $defaults = static::getDefaultPreferences();
            foreach ($defaults as $key => $value) {
                if (!isset($preference->{$key})) {
                    $preference->{$key} = $value;
                }
            }
        });
    }
}
