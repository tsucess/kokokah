<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Level extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         'name',
//         'curriculum_category_id'
//     ];


//     // Relationships
//     public function users()
//     {
//         return $this->hasMany(User::class);
//     }

//     public function courses()
//     {
//         return $this->hasMany(Course::class);
//     }

//     // Scopes
//     public function scopeByType($query, $type)
//     {
//         return $query->where('type', $type);
//     }

//     public function scopeSecondary($query)
//     {
//         return $query->where('type', 'secondary');
//     }

//     public function scopeUniversity($query)
//     {
//         return $query->where('type', 'university');
//     }

//     public function scopeGrade($query)
//     {
//         return $query->where('type', 'grade');
//     }
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'curriculum_category_id'
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

    public function curriculumCategory()
    {
        return $this->belongsTo(CurriculumCategory::class);
    }

    // Scopes
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('curriculum_category_id', $categoryId);
    }

    public function scopeSecondary($query)
    {
        $categoryId = CurriculumCategory::where('title', 'Secondary')->value('id');
        return $query->where('curriculum_category_id', $categoryId);
    }

    public function scopeUniversity($query)
    {
        $categoryId = CurriculumCategory::where('title', 'University')->value('id');
        return $query->where('curriculum_category_id', $categoryId);
    }

    public function scopeGrade($query)
    {
        $categoryId = CurriculumCategory::where('title', 'Grade')->value('id');
        return $query->where('curriculum_category_id', $categoryId);
    }
}
