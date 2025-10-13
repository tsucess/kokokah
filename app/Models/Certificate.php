<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'issued_at'
    ];

    protected $casts = [
        'issued_at' => 'datetime',
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

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeIssuedThisYear($query)
    {
        return $query->whereYear('issued_at', now()->year);
    }

    // Methods
    public function getCertificateNumber()
    {
        return 'CERT-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getFormattedIssueDate()
    {
        return $this->issued_at->format('F j, Y');
    }

    // Boot method to auto-set issued_at
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($certificate) {
            if (!$certificate->issued_at) {
                $certificate->issued_at = now();
            }
        });
    }
}
