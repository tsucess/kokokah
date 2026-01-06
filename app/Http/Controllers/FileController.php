<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for middleware configuration

    /**
     * Upload file
     */
    public function upload(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:102400', // 100MB max
                'folder' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:500',
                'is_public' => 'nullable|boolean',
                'course_id' => 'nullable|exists:courses,id',
                'lesson_id' => 'nullable|exists:lessons,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $uploadedFile = $request->file('file');

            // Validate file type
            $allowedTypes = $this->getAllowedFileTypes();
            $fileExtension = strtolower($uploadedFile->getClientOriginalExtension());
            
            if (!in_array($fileExtension, $allowedTypes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File type not allowed. Allowed types: ' . implode(', ', $allowedTypes)
                ], 400);
            }

            // Check user storage quota
            $quotaCheck = $this->checkStorageQuota($user, $uploadedFile->getSize());
            if (!$quotaCheck['allowed']) {
                return response()->json([
                    'success' => false,
                    'message' => $quotaCheck['message']
                ], 400);
            }

            // Generate unique filename
            $originalName = $uploadedFile->getClientOriginalName();
            $fileName = time() . '_' . Str::random(10) . '.' . $fileExtension;
            $folder = $request->folder ? "user_files/{$user->id}/{$request->folder}" : "user_files/{$user->id}";

            // Store file
            $filePath = $uploadedFile->storeAs($folder, $fileName, 'public');

            // Create file record
            $file = File::create([
                'user_id' => $user->id,
                'original_name' => $originalName,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $uploadedFile->getSize(),
                'mime_type' => $uploadedFile->getMimeType(),
                'extension' => $fileExtension,
                'folder' => $request->folder,
                'description' => $request->description,
                'is_public' => $request->get('is_public', false),
                'course_id' => $request->course_id,
                'lesson_id' => $request->lesson_id,
                'upload_ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'file' => $file,
                    'url' => Storage::disk('public')->url($filePath),
                    'download_url' => route('files.download', $file->id)
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download file
     */
    public function download($id)
    {
        try {
            $file = File::findOrFail($id);
            $user = Auth::user();

            // Check permissions
            if (!$this->canAccessFile($user, $file)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this file'
                ], 403);
            }

            // Check if file exists
            if (!Storage::disk('public')->exists($file->file_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found on storage'
                ], 404);
            }

            // Update download count
            $file->increment('download_count');
            $file->update(['last_downloaded_at' => now()]);

            // Log download
            $this->logFileAccess($file, $user, 'download');

            return Storage::disk('public')->download($file->file_path, $file->original_name);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File download failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete file
     */
    public function delete($id)
    {
        try {
            $file = File::findOrFail($id);
            $user = Auth::user();

            // Check permissions
            if (!$this->canDeleteFile($user, $file)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to delete this file'
                ], 403);
            }

            // Delete from storage
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete record
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * List user files
     */
    public function listFiles(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'folder' => 'nullable|string',
                'type' => 'nullable|in:image,video,audio,document,archive,other',
                'course_id' => 'nullable|exists:courses,id',
                'search' => 'nullable|string|max:255',
                'sort_by' => 'nullable|in:name,size,date,downloads',
                'sort_order' => 'nullable|in:asc,desc',
                'per_page' => 'nullable|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = File::where('user_id', $user->id);

            // Apply filters
            if ($request->folder) {
                $query->where('folder', $request->folder);
            }

            if ($request->type) {
                $extensions = $this->getExtensionsByType($request->type);
                $query->whereIn('extension', $extensions);
            }

            if ($request->course_id) {
                $query->where('course_id', $request->course_id);
            }

            if ($request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('original_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'date');
            $sortOrder = $request->get('sort_order', 'desc');

            switch ($sortBy) {
                case 'name':
                    $query->orderBy('original_name', $sortOrder);
                    break;
                case 'size':
                    $query->orderBy('file_size', $sortOrder);
                    break;
                case 'downloads':
                    $query->orderBy('download_count', $sortOrder);
                    break;
                case 'date':
                default:
                    $query->orderBy('created_at', $sortOrder);
                    break;
            }

            $files = $query->paginate($request->get('per_page', 20));

            // Add URLs to files
            $files->getCollection()->transform(function ($file) {
                $file->url = Storage::disk('public')->url($file->file_path);
                $file->download_url = route('files.download', $file->id);
                $file->preview_url = $this->canPreview($file) ? route('files.preview', $file->id) : null;
                return $file;
            });

            // Get storage usage
            $storageUsage = $this->getStorageUsage($user);

            return response()->json([
                'success' => true,
                'data' => [
                    'files' => $files,
                    'storage_usage' => $storageUsage
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to list files: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate file preview
     */
    public function preview($id)
    {
        try {
            $file = File::findOrFail($id);
            $user = Auth::user();

            // Check permissions
            if (!$this->canAccessFile($user, $file)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this file'
                ], 403);
            }

            // Check if file can be previewed
            if (!$this->canPreview($file)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File type cannot be previewed'
                ], 400);
            }

            // Log preview access
            $this->logFileAccess($file, $user, 'preview');

            $previewData = $this->generatePreview($file);

            return response()->json([
                'success' => true,
                'data' => $previewData
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Preview generation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Share file
     */
    public function share(Request $request, $id)
    {
        try {
            $file = File::findOrFail($id);
            $user = Auth::user();

            // Check permissions
            if (!$this->canShareFile($user, $file)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to share this file'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'share_type' => 'required|in:public,private,course',
                'expires_at' => 'nullable|date|after:now',
                'password' => 'nullable|string|min:6',
                'allowed_downloads' => 'nullable|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate share token
            $shareToken = Str::random(32);

            // Update file with sharing settings
            $file->update([
                'share_token' => $shareToken,
                'share_type' => $request->share_type,
                'share_expires_at' => $request->expires_at,
                'share_password' => $request->password ? bcrypt($request->password) : null,
                'allowed_downloads' => $request->allowed_downloads,
                'is_shared' => true
            ]);

            $shareUrl = url("files/shared/{$shareToken}");

            return response()->json([
                'success' => true,
                'message' => 'File shared successfully',
                'data' => [
                    'share_url' => $shareUrl,
                    'share_token' => $shareToken,
                    'expires_at' => $request->expires_at
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File sharing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Organize files into folders
     */
    public function organize(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'file_ids' => 'required|array',
                'file_ids.*' => 'exists:files,id',
                'folder' => 'nullable|string|max:255',
                'action' => 'required|in:move,copy'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $files = File::whereIn('id', $request->file_ids)
                        ->where('user_id', $user->id)
                        ->get();

            if ($files->count() !== count($request->file_ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some files not found or you do not have permission'
                ], 403);
            }

            $processedCount = 0;

            foreach ($files as $file) {
                if ($request->action === 'move') {
                    $file->update(['folder' => $request->folder]);
                    $processedCount++;
                } elseif ($request->action === 'copy') {
                    // Create a copy of the file
                    $newFile = $file->replicate();
                    $newFile->folder = $request->folder;
                    $newFile->original_name = 'Copy of ' . $newFile->original_name;
                    $newFile->save();
                    $processedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$processedCount} files {$request->action}d successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File organization failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get storage usage statistics
     */
    public function getStorageStats()
    {
        try {
            $user = Auth::user();
            $stats = $this->getStorageUsage($user);

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get storage statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods
     */
    private function getAllowedFileTypes()
    {
        return [
            // Images
            'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp',
            // Documents
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf',
            // Videos
            'mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv',
            // Audio
            'mp3', 'wav', 'ogg', 'aac', 'flac',
            // Archives
            'zip', 'rar', '7z', 'tar', 'gz',
            // Code
            'html', 'css', 'js', 'php', 'py', 'java', 'cpp', 'c', 'json', 'xml'
        ];
    }

    private function checkStorageQuota($user, $fileSize)
    {
        $quota = $this->getUserStorageQuota($user);
        $currentUsage = File::where('user_id', $user->id)->sum('file_size');

        if (($currentUsage + $fileSize) > $quota) {
            return [
                'allowed' => false,
                'message' => 'Storage quota exceeded. Current usage: ' . $this->formatFileSize($currentUsage) . 
                           ', Quota: ' . $this->formatFileSize($quota)
            ];
        }

        return ['allowed' => true];
    }

    private function getUserStorageQuota($user)
    {
        // Default quotas by role (in bytes)
        $quotas = [
            'student' => 1024 * 1024 * 1024, // 1GB
            'instructor' => 5 * 1024 * 1024 * 1024, // 5GB
            'admin' => 10 * 1024 * 1024 * 1024, // 10GB
            'superadmin' => 50 * 1024 * 1024 * 1024 // 50GB
        ];

        return $quotas[$user->role] ?? $quotas['student'];
    }

    private function canAccessFile($user, $file)
    {
        return $file->user_id === $user->id ||
               $file->is_public ||
               $user->hasAnyRole(['admin', 'superadmin']) ||
               ($file->course_id && $user->enrollments()->where('course_id', $file->course_id)->exists());
    }

    private function canDeleteFile($user, $file)
    {
        return $file->user_id === $user->id || $user->hasAnyRole(['admin', 'superadmin']);
    }

    private function canShareFile($user, $file)
    {
        return $file->user_id === $user->id;
    }

    private function canPreview($file)
    {
        $previewableTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'html', 'css', 'js', 'json', 'xml'];
        return in_array($file->extension, $previewableTypes);
    }

    private function generatePreview($file)
    {
        // Mock implementation - would generate actual previews
        return [
            'type' => $file->extension,
            'url' => Storage::disk('public')->url($file->file_path),
            'thumbnail' => null, // Would generate thumbnail for images/videos
            'metadata' => [
                'size' => $this->formatFileSize($file->file_size),
                'created' => $file->created_at->format('Y-m-d H:i:s'),
                'downloads' => $file->download_count
            ]
        ];
    }

    private function getStorageUsage($user)
    {
        $files = File::where('user_id', $user->id)->get();
        $totalSize = $files->sum('file_size');
        $quota = $this->getUserStorageQuota($user);

        return [
            'total_files' => $files->count(),
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatFileSize($totalSize),
            'quota' => $quota,
            'quota_formatted' => $this->formatFileSize($quota),
            'usage_percentage' => $quota > 0 ? round(($totalSize / $quota) * 100, 2) : 0,
            'by_type' => $this->getUsageByType($files),
            'recent_uploads' => $files->sortByDesc('created_at')->take(5)->values()
        ];
    }

    private function getUsageByType($files)
    {
        return $files->groupBy('extension')->map(function($group) {
            return [
                'count' => $group->count(),
                'size' => $group->sum('file_size'),
                'size_formatted' => $this->formatFileSize($group->sum('file_size'))
            ];
        });
    }

    private function getExtensionsByType($type)
    {
        $types = [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'],
            'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv'],
            'audio' => ['mp3', 'wav', 'ogg', 'aac', 'flac'],
            'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'],
            'archive' => ['zip', 'rar', '7z', 'tar', 'gz'],
            'other' => []
        ];

        return $types[$type] ?? [];
    }

    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    private function logFileAccess($file, $user, $action)
    {
        // Mock implementation - would log to file_access_logs table
        \Log::info('File access logged', [
            'file_id' => $file->id,
            'user_id' => $user->id,
            'action' => $action,
            'timestamp' => now()
        ]);
    }
}
