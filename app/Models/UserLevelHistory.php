<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLevelHistory extends Model
{
    protected $table = 'user_level_history';

    protected $fillable = [
        'user_id',
        'previous_level',
        'new_level',
        'points_at_change'
    ];

    protected $casts = [
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
     * Scope: Get level changes for a user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scope: Get changes to a specific level
     */
    public function scopeToLevel($query, $level)
    {
        return $query->where('new_level', $level);
    }

    /**
     * Scope: Get recent level changes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get the level progression
     */
    public function getLevelProgression()
    {
        $levels = ['Amateur', 'Intermediate', 'Advanced', 'Expert'];
        $previousIndex = array_search($this->previous_level, $levels);
        $newIndex = array_search($this->new_level, $levels);

        return [
            'from' => $this->previous_level,
            'to' => $this->new_level,
            'progression' => $newIndex > $previousIndex ? 'up' : 'down',
            'levels_gained' => abs($newIndex - $previousIndex)
        ];
    }
}

