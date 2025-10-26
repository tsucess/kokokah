<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'date',
        'views',
        'enrollments',
        'completions',
        'average_rating',
        'total_revenue_cents'
    ];

    protected $casts = [
        'date' => 'date',
        'views' => 'integer',
        'enrollments' => 'integer',
        'completions' => 'integer',
        'average_rating' => 'decimal:2',
        'total_revenue_cents' => 'integer',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('date', now()->year);
    }

    // Accessors
    public function getTotalRevenueAttribute()
    {
        return $this->total_revenue_cents / 100;
    }

    // Mutators
    public function setTotalRevenueAttribute($value)
    {
        $this->attributes['total_revenue_cents'] = $value * 100;
    }

    // Methods
    public function getConversionRate()
    {
        if ($this->views === 0) return 0;
        return round(($this->enrollments / $this->views) * 100, 2);
    }

    public function getCompletionRate()
    {
        if ($this->enrollments === 0) return 0;
        return round(($this->completions / $this->enrollments) * 100, 2);
    }

    public static function recordView($courseId, $date = null)
    {
        $date = $date ?: today();
        
        static::updateOrCreate(
            ['course_id' => $courseId, 'date' => $date],
            []
        )->increment('views');
    }

    public static function recordEnrollment($courseId, $date = null)
    {
        $date = $date ?: today();
        
        static::updateOrCreate(
            ['course_id' => $courseId, 'date' => $date],
            []
        )->increment('enrollments');
    }

    public static function recordCompletion($courseId, $date = null)
    {
        $date = $date ?: today();
        
        static::updateOrCreate(
            ['course_id' => $courseId, 'date' => $date],
            []
        )->increment('completions');
    }
}
