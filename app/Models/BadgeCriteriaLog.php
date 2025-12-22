<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BadgeCriteriaLog extends Model
{
    protected $table = 'badge_criteria_log';

    protected $fillable = [
        'user_id',
        'badge_id',
        'qualified',
        'criteria_data',
        'reason'
    ];

    protected $casts = [
        'criteria_data' => 'array',
        'qualified' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the badge
     */
    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }

    /**
     * Scope: Get qualified logs
     */
    public function scopeQualified($query)
    {
        return $query->where('qualified', true);
    }

    /**
     * Scope: Get not qualified logs
     */
    public function scopeNotQualified($query)
    {
        return $query->where('qualified', false);
    }

    /**
     * Scope: Get logs for a specific badge
     */
    public function scopeForBadge($query, $badgeId)
    {
        return $query->where('badge_id', $badgeId);
    }

    /**
     * Scope: Get logs for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}

