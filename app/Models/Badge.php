<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'criteria'
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')
                    ->withPivot('earned_at')
                    ->withTimestamps();
    }

    // Methods
    public function getUserCount()
    {
        return $this->users()->count();
    }

    public function hasBeenEarnedBy(User $user)
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    public function awardTo(User $user)
    {
        if (!$this->hasBeenEarnedBy($user)) {
            $this->users()->attach($user->id, ['earned_at' => now()]);
        }
    }
}
