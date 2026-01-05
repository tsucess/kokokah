<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'points',
        'criteria',
        'category',
        'type',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
                    ->withPivot('earned_at', 'revoked_at', 'is_featured', 'progress')
                    ->withTimestamps();
    }

    public function criteriaLogs(): HasMany
    {
        return $this->hasMany(BadgeCriteriaLog::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function getUserCount()
    {
        return $this->users()->count();
    }

    public function hasBeenEarnedBy(User $user)
    {
        return $this->users()
            ->where('user_id', $user->id)
            ->whereNull('user_badges.revoked_at')
            ->exists();
    }

    public function awardTo(User $user)
    {
        if (!$this->hasBeenEarnedBy($user)) {
            $this->users()->attach($user->id, ['earned_at' => now()]);
        }
    }

    public function revokeFrom(User $user)
    {
        $this->users()
            ->where('user_id', $user->id)
            ->update(['revoked_at' => now()]);
    }

    public function getTotalPointsAwarded()
    {
        return $this->users()
            ->whereNull('user_badges.revoked_at')
            ->count() * $this->points;
    }
}
