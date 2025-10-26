<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'type',
        'expires_at',
        'used_at',
        'attempts',
        'max_attempts'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'attempts' => 'integer',
        'max_attempts' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNull('used_at')
                    ->where('expires_at', '>', now())
                    ->where('attempts', '<', $this->max_attempts ?? 5);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function isValid()
    {
        return $this->active()->exists();
    }

    public function isExpired()
    {
        return $this->expires_at < now();
    }

    public function isUsed()
    {
        return $this->used_at !== null;
    }

    public function hasExceededAttempts()
    {
        return $this->attempts >= ($this->max_attempts ?? 5);
    }

    public function markAsUsed()
    {
        $this->update(['used_at' => now()]);
    }

    public function incrementAttempts()
    {
        $this->increment('attempts');
    }

    /**
     * Generate a random verification code
     */
    public static function generateCode($length = 6)
    {
        return strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length));
    }

    /**
     * Create a new verification code for a user
     */
    public static function createForUser($user, $type = 'email', $expiresInMinutes = 15)
    {
        // Invalidate previous codes of this type
        self::forUser($user->id)
            ->byType($type)
            ->whereNull('used_at')
            ->update(['expires_at' => now()]);

        return self::create([
            'user_id' => $user->id,
            'code' => self::generateCode(),
            'type' => $type,
            'expires_at' => now()->addMinutes($expiresInMinutes),
            'attempts' => 0,
            'max_attempts' => 5
        ]);
    }

    /**
     * Verify a code for a user
     */
    public static function verify($userId, $code, $type = 'email')
    {
        $verification = self::forUser($userId)
            ->byType($type)
            ->active()
            ->where('code', strtoupper($code))
            ->first();

        if (!$verification) {
            return null;
        }

        return $verification;
    }
}

