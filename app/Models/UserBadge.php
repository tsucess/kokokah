<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "badge_id", 
        "earned_at",
        "revoked_at"
    ];

    protected $casts = [
        "earned_at" => "datetime",
        "revoked_at" => "datetime"
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNull("revoked_at");
    }

    public function scopeRevoked($query)
    {
        return $query->whereNotNull("revoked_at");
    }

    // Methods
    public function isActive()
    {
        return $this->revoked_at === null;
    }

    public function revoke()
    {
        $this->update(["revoked_at" => now()]);
    }

    public function restore()
    {
        $this->update(["revoked_at" => null]);
    }
}