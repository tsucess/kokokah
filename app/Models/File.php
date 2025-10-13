<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'original_name',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'extension',
        'folder',
        'description',
        'is_public',
        'course_id',
        'lesson_id',
        'upload_ip',
        'download_count',
        'last_downloaded_at',
        'share_token',
        'share_type',
        'share_expires_at',
        'share_password',
        'allowed_downloads',
        'is_shared'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_public' => 'boolean',
        'is_shared' => 'boolean',
        'download_count' => 'integer',
        'last_downloaded_at' => 'datetime',
        'share_expires_at' => 'datetime'
    ];

    protected $dates = [
        'last_downloaded_at',
        'share_expires_at',
        'deleted_at'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopePrivate($query)
    {
        return $query->where('is_public', false);
    }

    public function scopeShared($query)
    {
        return $query->where('is_shared', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeByExtension($query, $extension)
    {
        return $query->where('extension', $extension);
    }

    public function scopeByFolder($query, $folder)
    {
        return $query->where('folder', $folder);
    }

    // Accessors
    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public function getFileTypeAttribute()
    {
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        $videoTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv'];
        $audioTypes = ['mp3', 'wav', 'ogg', 'aac', 'flac'];
        $documentTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'];
        $archiveTypes = ['zip', 'rar', '7z', 'tar', 'gz'];

        if (in_array($this->extension, $imageTypes)) return 'image';
        if (in_array($this->extension, $videoTypes)) return 'video';
        if (in_array($this->extension, $audioTypes)) return 'audio';
        if (in_array($this->extension, $documentTypes)) return 'document';
        if (in_array($this->extension, $archiveTypes)) return 'archive';
        
        return 'other';
    }

    public function getIsPreviewableAttribute()
    {
        $previewableTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'html', 'css', 'js', 'json', 'xml'];
        return in_array($this->extension, $previewableTypes);
    }

    // Methods
    public function canBeAccessedBy(User $user)
    {
        // Owner can always access
        if ($this->user_id === $user->id) {
            return true;
        }

        // Admin can access all files
        if ($user->hasRole('admin')) {
            return true;
        }

        // Public files can be accessed by anyone
        if ($this->is_public) {
            return true;
        }

        // Course files can be accessed by enrolled students
        if ($this->course_id && $user->enrollments()->where('course_id', $this->course_id)->exists()) {
            return true;
        }

        return false;
    }

    public function canBeDeletedBy(User $user)
    {
        return $this->user_id === $user->id || $user->hasRole('admin');
    }

    public function canBeSharedBy(User $user)
    {
        return $this->user_id === $user->id;
    }

    public function isShareExpired()
    {
        return $this->share_expires_at && $this->share_expires_at->isPast();
    }

    public function incrementDownloadCount()
    {
        $this->increment('download_count');
        $this->update(['last_downloaded_at' => now()]);
    }

    // Boot method for model events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            // Set default values
            if (!$file->download_count) {
                $file->download_count = 0;
            }
            if (!$file->is_public) {
                $file->is_public = false;
            }
            if (!$file->is_shared) {
                $file->is_shared = false;
            }
        });

        static::deleting(function ($file) {
            // Delete physical file when model is deleted
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
        });
    }
}
