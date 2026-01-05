<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewHelpful extends Model
{
    use HasFactory;

    protected $table = 'review_helpful';

    protected $fillable = [
        'review_id',
        'user_id',
    ];

    /**
     * Get the review that this helpful mark belongs to
     */
    public function review()
    {
        return $this->belongsTo(CourseReview::class, 'review_id');
    }

    /**
     * Get the user who marked this review as helpful
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

