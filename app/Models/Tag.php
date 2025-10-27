<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color'
    ];

    // Relationships
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_tags')->withTimestamps();
    }

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
        
        static::updating(function ($tag) {
            if ($tag->isDirty('name')) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    // Scopes
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    // Methods
    public function getCourseCount()
    {
        return $this->courses()->count();
    }
}
