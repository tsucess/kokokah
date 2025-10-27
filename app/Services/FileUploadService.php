<?php

namespace App\Services;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    protected $allowedTypes = [
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'],
        'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv'],
        'audio' => ['mp3', 'wav', 'ogg', 'aac', 'flac'],
        'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'],
        'archive' => ['zip', 'rar', '7z', 'tar', 'gz']
    ];

    protected $maxFileSizes = [
        'image' => 5 * 1024 * 1024, // 5MB
        'video' => 100 * 1024 * 1024, // 100MB
        'audio' => 20 * 1024 * 1024, // 20MB
        'document' => 10 * 1024 * 1024, // 10MB
        'archive' => 50 * 1024 * 1024, // 50MB
        'default' => 5 * 1024 * 1024 // 5MB
    ];

    protected $storageQuotas = [
        'student' => 1 * 1024 * 1024 * 1024, // 1GB
        'instructor' => 5 * 1024 * 1024 * 1024, // 5GB
        'admin' => 10 * 1024 * 1024 * 1024 // 10GB
    ];

    /**
     * Upload a file
     */
    public function upload(UploadedFile $file, User $user, array $options = []): File
    {
        // Validate file
        $this->validateFile($file, $user);

        // Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $filename = $this->generateUniqueFilename($extension);
        
        // Determine folder structure
        $folder = $options['folder'] ?? $this->generateFolderPath($user, $options);
        
        // Store file
        $path = $file->storeAs($folder, $filename, 'public');
        
        // Create file record
        $fileRecord = File::create([
            'user_id' => $user->id,
            'original_name' => $file->getClientOriginalName(),
            'file_name' => $filename,
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $extension,
            'folder' => $folder,
            'description' => $options['description'] ?? null,
            'is_public' => $options['is_public'] ?? false,
            'course_id' => $options['course_id'] ?? null,
            'lesson_id' => $options['lesson_id'] ?? null,
            'upload_ip' => request()->ip()
        ]);

        return $fileRecord;
    }

    /**
     * Validate uploaded file
     */
    protected function validateFile(UploadedFile $file, User $user): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $fileSize = $file->getSize();
        
        // Check if file type is allowed
        $fileType = $this->getFileType($extension);
        if (!$this->isFileTypeAllowed($extension)) {
            throw new \InvalidArgumentException("File type '{$extension}' is not allowed");
        }

        // Check file size
        $maxSize = $this->maxFileSizes[$fileType] ?? $this->maxFileSizes['default'];
        if ($fileSize > $maxSize) {
            throw new \InvalidArgumentException("File size exceeds maximum allowed size of " . $this->formatFileSize($maxSize));
        }

        // Check storage quota
        if (!$this->checkStorageQuota($user, $fileSize)) {
            throw new \InvalidArgumentException("Upload would exceed your storage quota");
        }
    }

    /**
     * Check if file type is allowed
     */
    protected function isFileTypeAllowed(string $extension): bool
    {
        foreach ($this->allowedTypes as $type => $extensions) {
            if (in_array($extension, $extensions)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get file type category
     */
    protected function getFileType(string $extension): string
    {
        foreach ($this->allowedTypes as $type => $extensions) {
            if (in_array($extension, $extensions)) {
                return $type;
            }
        }
        return 'other';
    }

    /**
     * Check storage quota
     */
    public function checkStorageQuota(User $user, int $additionalSize = 0): bool
    {
        $currentUsage = $this->getUserStorageUsage($user);
        $quota = $this->getUserStorageQuota($user);
        
        return ($currentUsage + $additionalSize) <= $quota;
    }

    /**
     * Get user storage usage
     */
    public function getUserStorageUsage(User $user): int
    {
        return $user->files()->sum('file_size');
    }

    /**
     * Get user storage quota
     */
    public function getUserStorageQuota(User $user): int
    {
        return $this->storageQuotas[$user->role] ?? $this->storageQuotas['student'];
    }

    /**
     * Generate unique filename
     */
    protected function generateUniqueFilename(string $extension): string
    {
        return Str::uuid() . '.' . $extension;
    }

    /**
     * Generate folder path
     */
    protected function generateFolderPath(User $user, array $options = []): string
    {
        $basePath = 'uploads';
        
        if (isset($options['course_id'])) {
            return $basePath . '/courses/' . $options['course_id'];
        }
        
        if (isset($options['lesson_id'])) {
            return $basePath . '/lessons/' . $options['lesson_id'];
        }
        
        return $basePath . '/users/' . $user->id;
    }

    /**
     * Delete file
     */
    public function delete(File $file): bool
    {
        // Delete physical file
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }
        
        // Delete database record
        return $file->delete();
    }

    /**
     * Generate file preview
     */
    public function generatePreview(File $file): ?string
    {
        if (!$file->is_previewable) {
            return null;
        }

        $fileType = $file->file_type;
        
        switch ($fileType) {
            case 'image':
                return $this->generateImagePreview($file);
            case 'document':
                return $this->generateDocumentPreview($file);
            default:
                return null;
        }
    }

    /**
     * Generate image preview
     */
    protected function generateImagePreview(File $file): string
    {
        return Storage::disk('public')->url($file->file_path);
    }

    /**
     * Generate document preview
     */
    protected function generateDocumentPreview(File $file): ?string
    {
        // For PDF files, you could generate thumbnail using libraries like Imagick
        // For now, return the file URL
        if ($file->extension === 'pdf') {
            return Storage::disk('public')->url($file->file_path);
        }
        
        return null;
    }

    /**
     * Share file
     */
    public function shareFile(File $file, array $options = []): string
    {
        $shareToken = Str::random(32);
        
        $file->update([
            'share_token' => $shareToken,
            'share_type' => $options['type'] ?? 'private',
            'share_expires_at' => $options['expires_at'] ?? null,
            'share_password' => $options['password'] ?? null,
            'allowed_downloads' => $options['allowed_downloads'] ?? null,
            'is_shared' => true
        ]);
        
        return route('file.shared', ['token' => $shareToken]);
    }

    /**
     * Get file by share token
     */
    public function getSharedFile(string $token, string $password = null): ?File
    {
        $file = File::where('share_token', $token)
                   ->where('is_shared', true)
                   ->first();
        
        if (!$file) {
            return null;
        }
        
        // Check if expired
        if ($file->isShareExpired()) {
            return null;
        }
        
        // Check password if required
        if ($file->share_password && $file->share_password !== $password) {
            return null;
        }
        
        return $file;
    }

    /**
     * Format file size
     */
    public function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Get storage statistics
     */
    public function getStorageStats(User $user): array
    {
        $usage = $this->getUserStorageUsage($user);
        $quota = $this->getUserStorageQuota($user);
        $fileCount = $user->files()->count();
        
        return [
            'usage' => $usage,
            'quota' => $quota,
            'usage_formatted' => $this->formatFileSize($usage),
            'quota_formatted' => $this->formatFileSize($quota),
            'usage_percentage' => $quota > 0 ? round(($usage / $quota) * 100, 2) : 0,
            'file_count' => $fileCount,
            'available' => $quota - $usage,
            'available_formatted' => $this->formatFileSize($quota - $usage)
        ];
    }

    /**
     * Get allowed file types
     */
    public function getAllowedFileTypes(): array
    {
        return $this->allowedTypes;
    }

    /**
     * Get max file sizes
     */
    public function getMaxFileSizes(): array
    {
        return array_map([$this, 'formatFileSize'], $this->maxFileSizes);
    }
}
