<?php

namespace App\Services;

use App\Models\VideoStream;
use App\Models\VideoQuality;
use App\Models\VideoAnalytic;
use App\Models\VideoDownload;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VideoStreamingService
{
    /**
     * Create video stream from URL
     */
    public function createVideoStream($lessonId, $videoUrl)
    {
        $lesson = Lesson::find($lessonId);
        if (!$lesson) {
            return null;
        }

        $videoStream = VideoStream::create([
            'lesson_id' => $lessonId,
            'original_url' => $videoUrl,
            'status' => 'pending'
        ]);

        // Create quality variants
        foreach (VideoQuality::getStandardQualities() as $quality) {
            VideoQuality::createForStream($videoStream->id, $quality);
        }

        return $videoStream;
    }

    /**
     * Process video stream (transcode to HLS/DASH)
     */
    public function processVideoStream($videoStreamId)
    {
        $videoStream = VideoStream::find($videoStreamId);
        if (!$videoStream) {
            return false;
        }

        try {
            $videoStream->markAsProcessing();

            // In production, this would call FFmpeg or a video processing service
            // For now, we'll simulate the process
            $this->simulateVideoProcessing($videoStream);

            $videoStream->markAsProcessed();
            return true;
        } catch (\Exception $e) {
            Log::error('Video processing failed: ' . $e->getMessage());
            $videoStream->markAsFailed($e->getMessage());
            return false;
        }
    }

    /**
     * Simulate video processing (in production, use FFmpeg or AWS MediaConvert)
     */
    private function simulateVideoProcessing($videoStream)
    {
        // Simulate getting video metadata
        $videoStream->update([
            'duration_seconds' => 3600, // 1 hour
            'file_size_bytes' => 1024 * 1024 * 500, // 500 MB
            'processing_progress' => 25
        ]);

        // Simulate transcoding to different qualities
        $qualities = $videoStream->qualities;
        foreach ($qualities as $quality) {
            $quality->markAsProcessing();

            // Simulate transcoding
            $quality->markAsReady(
                'https://cdn.example.com/videos/' . $videoStream->id . '/' . $quality->quality_label . '.mp4',
                1024 * 1024 * (int)($quality->bitrate / 1000) // Estimate file size
            );
        }

        // Generate HLS and DASH manifests
        $videoStream->update([
            'hls_manifest_url' => 'https://cdn.example.com/videos/' . $videoStream->id . '/playlist.m3u8',
            'dash_manifest_url' => 'https://cdn.example.com/videos/' . $videoStream->id . '/manifest.mpd',
            'cdn_url' => 'https://cdn.example.com/videos/' . $videoStream->id,
            'processing_progress' => 100
        ]);
    }

    /**
     * Get video stream details
     */
    public function getVideoStreamDetails($videoStreamId)
    {
        $videoStream = VideoStream::with('qualities', 'analytics')->find($videoStreamId);
        if (!$videoStream) {
            return null;
        }

        return [
            'id' => $videoStream->id,
            'lesson_id' => $videoStream->lesson_id,
            'status' => $videoStream->status,
            'duration' => $videoStream->getFormattedDuration(),
            'file_size' => $videoStream->getFormattedFileSize(),
            'hls_url' => $videoStream->getHLSPlaylist(),
            'dash_url' => $videoStream->getDASHPlaylist(),
            'cdn_url' => $videoStream->getCDNUrl(),
            'qualities' => $videoStream->getAvailableQualities()->map(function ($quality) {
                return [
                    'id' => $quality->id,
                    'label' => $quality->quality_label,
                    'resolution' => $quality->resolution,
                    'bitrate' => $quality->getFormattedBitrate(),
                    'url' => $quality->url
                ];
            }),
            'analytics' => [
                'total_views' => $videoStream->getTotalViews(),
                'average_watch_time' => $videoStream->getAverageWatchTime(),
                'completion_rate' => round($videoStream->getCompletionRate(), 2)
            ]
        ];
    }

    /**
     * Record video view
     */
    public function recordVideoView($videoStreamId, $userId, $qualityWatched, $deviceType, $browser, $country, $ipAddress)
    {
        return VideoAnalytic::recordView(
            $videoStreamId,
            $userId,
            $qualityWatched,
            $deviceType,
            $browser,
            $country,
            $ipAddress
        );
    }

    /**
     * Update watch time
     */
    public function updateWatchTime($videoStreamId, $userId, $seconds)
    {
        $analytic = VideoAnalytic::where('video_stream_id', $videoStreamId)
                                ->where('user_id', $userId)
                                ->first();

        if ($analytic) {
            $analytic->updateWatchTime($seconds);
            return $analytic;
        }

        return null;
    }

    /**
     * Create download request
     */
    public function createDownloadRequest($videoStreamId, $userId, $qualityLabel)
    {
        $videoStream = VideoStream::find($videoStreamId);
        if (!$videoStream) {
            return null;
        }

        $quality = $videoStream->qualities()
                              ->where('quality_label', $qualityLabel)
                              ->first();

        if (!$quality || !$quality->isReady()) {
            return null;
        }

        $download = VideoDownload::createDownload(
            $videoStreamId,
            $userId,
            $qualityLabel,
            $quality->file_size_bytes
        );

        return $download;
    }

    /**
     * Get video analytics
     */
    public function getVideoAnalytics($videoStreamId)
    {
        $videoStream = VideoStream::find($videoStreamId);
        if (!$videoStream) {
            return null;
        }

        return [
            'video_id' => $videoStreamId,
            'total_views' => $videoStream->getTotalViews(),
            'unique_viewers' => $videoStream->analytics()->distinct('user_id')->count(),
            'average_watch_time' => round($videoStream->getAverageWatchTime(), 2),
            'completion_rate' => round($videoStream->getCompletionRate(), 2),
            'device_stats' => VideoAnalytic::getDeviceStats($videoStreamId),
            'country_stats' => VideoAnalytic::getCountryStats($videoStreamId),
            'quality_distribution' => $videoStream->analytics()
                                                 ->selectRaw('quality_watched, COUNT(*) as count')
                                                 ->groupBy('quality_watched')
                                                 ->get()
        ];
    }

    /**
     * Get top videos
     */
    public function getTopVideos($limit = 10)
    {
        return VideoAnalytic::getTopVideos($limit);
    }

    /**
     * Get user's downloads
     */
    public function getUserDownloads($userId)
    {
        return VideoDownload::where('user_id', $userId)
                           ->where('status', 'completed')
                           ->where('expires_at', '>=', now())
                           ->with('videoStream')
                           ->get();
    }

    /**
     * Delete expired downloads
     */
    public function cleanupExpiredDownloads()
    {
        VideoDownload::where('expires_at', '<', now())
                    ->where('status', 'completed')
                    ->delete();
    }
}

