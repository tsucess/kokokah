<?php

namespace App\Http\Controllers;

use App\Services\VideoStreamingService;
use App\Models\VideoStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoStreamingController extends Controller
{
    protected $videoService;

    public function __construct(VideoStreamingService $videoService)
    {
        $this->videoService = $videoService;
    }

    /**
     * Create video stream
     */
    public function createVideoStream(Request $request)
    {
        try {
            $validated = $request->validate([
                'lesson_id' => 'required|integer|exists:lessons,id',
                'video_url' => 'required|url'
            ]);

            $videoStream = $this->videoService->createVideoStream(
                $validated['lesson_id'],
                $validated['video_url']
            );

            if (!$videoStream) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create video stream'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Video stream created successfully',
                'data' => $videoStream
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create video stream: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process video stream
     */
    public function processVideoStream($videoStreamId)
    {
        try {
            $success = $this->videoService->processVideoStream($videoStreamId);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process video stream'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Video stream processing started'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process video stream: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get video stream details
     */
    public function getVideoStream($videoStreamId)
    {
        try {
            $details = $this->videoService->getVideoStreamDetails($videoStreamId);

            if (!$details) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video stream not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $details
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch video stream: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Record video view
     */
    public function recordVideoView(Request $request, $videoStreamId)
    {
        try {
            $validated = $request->validate([
                'quality_watched' => 'nullable|string',
                'device_type' => 'nullable|string',
                'browser' => 'nullable|string',
                'country' => 'nullable|string',
                'ip_address' => 'nullable|ip'
            ]);

            $user = Auth::user();

            $analytic = $this->videoService->recordVideoView(
                $videoStreamId,
                $user->id,
                $validated['quality_watched'] ?? 'unknown',
                $validated['device_type'] ?? 'unknown',
                $validated['browser'] ?? 'unknown',
                $validated['country'] ?? 'unknown',
                $validated['ip_address'] ?? request()->ip()
            );

            return response()->json([
                'success' => true,
                'message' => 'View recorded successfully',
                'data' => $analytic
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record view: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update watch time
     */
    public function updateWatchTime(Request $request, $videoStreamId)
    {
        try {
            $validated = $request->validate([
                'seconds' => 'required|integer|min:0'
            ]);

            $user = Auth::user();

            $analytic = $this->videoService->updateWatchTime(
                $videoStreamId,
                $user->id,
                $validated['seconds']
            );

            if (!$analytic) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update watch time'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Watch time updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update watch time: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create download request
     */
    public function createDownloadRequest(Request $request, $videoStreamId)
    {
        try {
            $validated = $request->validate([
                'quality_label' => 'required|string|in:360p,480p,720p,1080p'
            ]);

            $user = Auth::user();

            $download = $this->videoService->createDownloadRequest(
                $videoStreamId,
                $user->id,
                $validated['quality_label']
            );

            if (!$download) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create download request'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Download request created successfully',
                'data' => $download
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create download request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get video analytics
     */
    public function getVideoAnalytics($videoStreamId)
    {
        try {
            $analytics = $this->videoService->getVideoAnalytics($videoStreamId);

            if (!$analytics) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video stream not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get top videos
     */
    public function getTopVideos(Request $request)
    {
        try {
            $limit = $request->query('limit', 10);
            $topVideos = $this->videoService->getTopVideos($limit);

            return response()->json([
                'success' => true,
                'data' => $topVideos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch top videos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user downloads
     */
    public function getUserDownloads()
    {
        try {
            $user = Auth::user();
            $downloads = $this->videoService->getUserDownloads($user->id);

            return response()->json([
                'success' => true,
                'data' => $downloads
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch downloads: ' . $e->getMessage()
            ], 500);
        }
    }
}

