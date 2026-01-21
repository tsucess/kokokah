<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $table = 'terms';

    protected $fillable = [
        'name',
        'order',
    ];

    // Relationships
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Methods
    public function getFullNameAttribute()
    {
        return $this->name;
    }
}
