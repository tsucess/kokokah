<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoQuality extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_stream_id',
        'quality_label',
        'resolution',
        'bitrate',
        'file_size_bytes',
        'url',
        'status',
        'codec_video',
        'codec_audio',
        'frame_rate'
    ];

    protected $casts = [
        'bitrate' => 'integer',
        'file_size_bytes' => 'integer',
        'frame_rate' => 'float'
    ];

    // Relationships
    public function videoStream()
    {
        return $this->belongsTo(VideoStream::class);
    }

    // Scopes
    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeByResolution($query, $resolution)
    {
        return $query->where('resolution', $resolution);
    }

    // Methods
    public function isReady()
    {
        return $this->status === 'ready';
    }

    public function getFormattedBitrate()
    {
        return ($this->bitrate / 1000) . ' kbps';
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

    public static function getStandardQualities()
    {
        return [
            [
                'label' => '360p',
                'resolution' => '640x360',
                'bitrate' => 500000, // 500 kbps
                'codec_video' => 'h264',
                'codec_audio' => 'aac'
            ],
            [
                'label' => '480p',
                'resolution' => '854x480',
                'bitrate' => 1000000, // 1 Mbps
                'codec_video' => 'h264',
                'codec_audio' => 'aac'
            ],
            [
                'label' => '720p',
                'resolution' => '1280x720',
                'bitrate' => 2500000, // 2.5 Mbps
                'codec_video' => 'h264',
                'codec_audio' => 'aac'
            ],
            [
                'label' => '1080p',
                'resolution' => '1920x1080',
                'bitrate' => 5000000, // 5 Mbps
                'codec_video' => 'h264',
                'codec_audio' => 'aac'
            ]
        ];
    }

    public static function createForStream($videoStreamId, $qualityConfig)
    {
        return self::create([
            'video_stream_id' => $videoStreamId,
            'quality_label' => $qualityConfig['label'],
            'resolution' => $qualityConfig['resolution'],
            'bitrate' => $qualityConfig['bitrate'],
            'codec_video' => $qualityConfig['codec_video'] ?? 'h264',
            'codec_audio' => $qualityConfig['codec_audio'] ?? 'aac',
            'status' => 'pending'
        ]);
    }

    public function markAsReady($url, $fileSize)
    {
        $this->update([
            'url' => $url,
            'file_size_bytes' => $fileSize,
            'status' => 'ready'
        ]);
    }

    public function markAsProcessing()
    {
        $this->update(['status' => 'processing']);
    }

    public function markAsFailed()
    {
        $this->update(['status' => 'failed']);
    }
}

