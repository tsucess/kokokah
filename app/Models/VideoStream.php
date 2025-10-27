<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoStream extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'original_url',
        'hls_manifest_url',
        'dash_manifest_url',
        'cdn_url',
        'duration_seconds',
        'file_size_bytes',
        'status',
        'processing_progress',
        'error_message',
        'metadata'
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'file_size_bytes' => 'integer',
        'processing_progress' => 'float',
        'metadata' => 'array'
    ];

    // Relationships
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function qualities()
    {
        return $this->hasMany(VideoQuality::class);
    }

    public function analytics()
    {
        return $this->hasMany(VideoAnalytic::class);
    }

    public function downloads()
    {
        return $this->hasMany(VideoDownload::class);
    }

    // Scopes
    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeForLesson($query, $lessonId)
    {
        return $query->where('lesson_id', $lessonId);
    }

    // Methods
    public function isProcessed()
    {
        return $this->status === 'processed';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function hasFailed()
    {
        return $this->status === 'failed';
    }

    public function getFormattedDuration()
    {
        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
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

    public function getAvailableQualities()
    {
        return $this->qualities()
                   ->where('status', 'ready')
                   ->orderBy('bitrate', 'desc')
                   ->get();
    }

    public function getHLSPlaylist()
    {
        return $this->hls_manifest_url;
    }

    public function getDASHPlaylist()
    {
        return $this->dash_manifest_url;
    }

    public function getCDNUrl()
    {
        return $this->cdn_url;
    }

    public function getTotalViews()
    {
        return $this->analytics()->sum('view_count');
    }

    public function getAverageWatchTime()
    {
        $analytics = $this->analytics()->get();
        if ($analytics->isEmpty()) {
            return 0;
        }

        return $analytics->avg('watch_time_seconds');
    }

    public function getCompletionRate()
    {
        $analytics = $this->analytics()->get();
        if ($analytics->isEmpty()) {
            return 0;
        }

        $completed = $analytics->where('watch_time_seconds', '>=', $this->duration_seconds * 0.9)->count();
        return ($completed / $analytics->count()) * 100;
    }

    public function markAsProcessing()
    {
        $this->update(['status' => 'processing', 'processing_progress' => 0]);
    }

    public function markAsProcessed()
    {
        $this->update(['status' => 'processed', 'processing_progress' => 100]);
    }

    public function markAsFailed($errorMessage)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'processing_progress' => 0
        ]);
    }

    public function updateProgress($progress)
    {
        $this->update(['processing_progress' => $progress]);
    }
}

