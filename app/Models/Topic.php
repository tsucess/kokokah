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
        'term_id',
        'order'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'topic_id')->orderBy('order');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'topic_id');
    }
}