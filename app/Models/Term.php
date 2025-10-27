<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year'
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    // Relationships
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Scopes
    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeCurrent($query)
    {
        return $query->where('year', now()->year);
    }

    // Methods
    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->year;
    }
}
