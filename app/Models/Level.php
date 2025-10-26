<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type'
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeSecondary($query)
    {
        return $query->where('type', 'secondary');
    }

    public function scopeUniversity($query)
    {
        return $query->where('type', 'university');
    }

    public function scopeGrade($query)
    {
        return $query->where('type', 'grade');
    }
}
