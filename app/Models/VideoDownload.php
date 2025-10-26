<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoDownload extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_stream_id',
        'user_id',
        'quality_label',
        'file_size_bytes',
        'download_url',
        'status',
        'progress_percentage',
        'expires_at',
        'downloaded_at'
    ];

    protected $casts = [
        'file_size_bytes' => 'integer',
        'progress_percentage' => 'float',
        'expires_at' => 'datetime',
        'downloaded_at' => 'datetime'
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

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>=', now());
    }

    // Methods
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at < now();
    }

    public function isActive()
    {
        return !$this->isExpired();
    }

    public function getFormattedFileSize()
    {
        $bytes = $this->file_size_bytes;
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public function getTimeRemaining()
    {
        if (!$this->expires_at) {
            return null;
        }

        return $this->expires_at->diffForHumans();
    }

    public function markAsInProgress()
    {
        $this->update([
            'status' => 'in_progress',
            'progress_percentage' => 0
        ]);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'progress_percentage' => 100,
            'downloaded_at' => now(),
            'expires_at' => now()->addDays(7) // Downloads expire after 7 days
        ]);
    }

    public function updateProgress($percentage)
    {
        $this->update(['progress_percentage' => $percentage]);
    }

    public static function createDownload($videoStreamId, $userId, $qualityLabel, $fileSize)
    {
        return self::create([
            'video_stream_id' => $videoStreamId,
            'user_id' => $userId,
            'quality_label' => $qualityLabel,
            'file_size_bytes' => $fileSize,
            'status' => 'pending',
            'progress_percentage' => 0,
            'expires_at' => now()->addDays(7)
        ]);
    }

    public function cleanupExpiredDownloads()
    {
        self::where('expires_at', '<', now())
           ->where('status', 'completed')
           ->delete();
    }
}

