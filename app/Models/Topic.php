<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $table = 'topics';

    protected $fillable = [
        'title',
        'course_id',
        'order',
    ];

    public $timestamps = false; // Your original table has no timestamps

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'topic_id')->orderBy('order');
    }
}





// class Topic extends Model
// {
//     use HasFactory;

//     protected $table = 'topics';

//     protected $fillable = [
//         'title',
//         'course_id',
//         'order',
//     ];

//     // Relationship (optional)
//     public function course()
//     {
//         return $this->belongsTo(Course::class, 'course_id');
//     }
// }
