<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        "assignment_id",
        "user_id",
        "content",
        "file_path",
        "submitted_at",
        "graded_at",
        "grade",
        "feedback",
        "status"
    ];

    protected $casts = [
        "submitted_at" => "datetime",
        "graded_at" => "datetime",
        "grade" => "decimal:2"
    ];

    // Relationships
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    // Scopes
    public function scopeSubmitted($query)
    {
        return $query->where("status", "submitted");
    }

    public function scopeGraded($query)
    {
        return $query->where("status", "graded");
    }

    public function scopePending($query)
    {
        return $query->where("status", "pending");
    }

    // Methods
    public function isSubmitted()
    {
        return $this->status === "submitted" || $this->status === "graded";
    }

    public function isGraded()
    {
        return $this->status === "graded";
    }

    public function isPending()
    {
        return $this->status === "pending";
    }
}