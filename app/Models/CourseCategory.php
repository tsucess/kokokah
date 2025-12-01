<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithCourses($query)
    {
        return $query->has('courses');
    }

    public function scopeWithoutCourses($query)
    {
        return $query->doesntHave('courses');
    }

    // Methods
    public function getCourseCount()
    {
        return $this->courses()->count();
    }

    public function getPublishedCourseCount()
    {
        return $this->courses()->where('status', 'published')->count();
    }

    public function hasPublishedCourses()
    {
        return $this->courses()->where('status', 'published')->exists();
    }
}
