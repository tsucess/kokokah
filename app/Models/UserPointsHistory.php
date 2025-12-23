<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPointsHistory extends Model
{
    protected $table = 'user_points_history';

    protected $fillable = [
        'user_id',
        'points_change',
        'points_before',
        'points_after',
        'reason',
        'action_type',
        'action_id',
        'action_model',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns this history record
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Get history for a specific action type
     */
    public function scopeByActionType($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    /**
     * Scope: Get recent history
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get the action model instance
     */
    public function getActionModel()
    {
        if (!$this->action_model || !$this->action_id) {
            return null;
        }

        $modelClass = 'App\\Models\\' . $this->action_model;
        if (class_exists($modelClass)) {
            return $modelClass::find($this->action_id);
        }

        return null;
    }
}

