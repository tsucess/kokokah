<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_stream_id',
        'user_id',
        'view_count',
        'watch_time_seconds',
        'quality_watched',
        'device_type',
        'browser',
        'country',
        'ip_address',
        'last_watched_at'
    ];

    protected $casts = [
        'view_count' => 'integer',
        'watch_time_seconds' => 'integer',
        'last_watched_at' => 'datetime'
    ];

    // Relationships
    public function videoStream()
    {
        return $this->belongsTo(VideoStream::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForVideo($query, $videoStreamId)
    {
        return $query->where('video_stream_id', $videoStreamId);
    }

    public function scopeByDeviceType($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeRecentViews($query, $days = 7)
    {
        return $query->where('last_watched_at', '>=', now()->subDays($days));
    }

    // Methods
    public function getFormattedWatchTime()
    {
        $hours = floor($this->watch_time_seconds / 3600);
        $minutes = floor(($this->watch_time_seconds % 3600) / 60);
        $seconds = $this->watch_time_seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    public function getCompletionPercentage()
    {
        if (!$this->videoStream) {
            return 0;
        }

        return ($this->watch_time_seconds / $this->videoStream->duration_seconds) * 100;
    }

    public function isCompleted()
    {
        if (!$this->videoStream) {
            return false;
        }

        return $this->watch_time_seconds >= ($this->videoStream->duration_seconds * 0.9);
    }

    public static function recordView($videoStreamId, $userId, $qualityWatched, $deviceType, $browser, $country, $ipAddress)
    {
        $analytic = self::where('video_stream_id', $videoStreamId)
                       ->where('user_id', $userId)
                       ->first();

        if ($analytic) {
            $analytic->increment('view_count');
            $analytic->update([
                'quality_watched' => $qualityWatched,
                'device_type' => $deviceType,
                'browser' => $browser,
                'country' => $country,
                'ip_address' => $ipAddress,
                'last_watched_at' => now()
            ]);
        } else {
            $analytic = self::create([
                'video_stream_id' => $videoStreamId,
                'user_id' => $userId,
                'view_count' => 1,
                'watch_time_seconds' => 0,
                'quality_watched' => $qualityWatched,
                'device_type' => $deviceType,
                'browser' => $browser,
                'country' => $country,
                'ip_address' => $ipAddress,
                'last_watched_at' => now()
            ]);
        }

        return $analytic;
    }

    public function updateWatchTime($seconds)
    {
        $this->increment('watch_time_seconds', $seconds);
        $this->update(['last_watched_at' => now()]);
    }

    public static function getTopVideos($limit = 10)
    {
        return self::selectRaw('video_stream_id, SUM(view_count) as total_views')
                  ->groupBy('video_stream_id')
                  ->orderByDesc('total_views')
                  ->limit($limit)
                  ->with('videoStream')
                  ->get();
    }

    public static function getDeviceStats($videoStreamId)
    {
        return self::where('video_stream_id', $videoStreamId)
                  ->selectRaw('device_type, COUNT(*) as count, AVG(watch_time_seconds) as avg_watch_time')
                  ->groupBy('device_type')
                  ->get();
    }

    public static function getCountryStats($videoStreamId)
    {
        return self::where('video_stream_id', $videoStreamId)
                  ->selectRaw('country, COUNT(*) as count, AVG(watch_time_seconds) as avg_watch_time')
                  ->groupBy('country')
                  ->orderByDesc('count')
                  ->get();
    }
}

