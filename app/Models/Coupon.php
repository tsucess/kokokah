<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_limit_per_user' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('starts_at', '<=', now())
                    ->where('expires_at', '>=', now());
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', strtoupper($code));
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function isValid()
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        
        return true;
    }

    public function canBeUsedBy(User $user, $amount = 0)
    {
        if (!$this->isValid()) return false;
        
        // Check minimum amount
        if ($this->minimum_amount && $amount < $this->minimum_amount) return false;
        
        // Check per-user usage limit
        if ($this->usage_limit_per_user) {
            $userUsageCount = $this->usages()->where('user_id', $user->id)->count();
            if ($userUsageCount >= $this->usage_limit_per_user) return false;
        }
        
        return true;
    }

    public function calculateDiscount($amount)
    {
        if ($this->type === 'percentage') {
            return min($amount, ($amount * $this->value) / 100);
        } else {
            return min($amount, $this->value);
        }
    }

    public function applyTo(User $user, $amount, Course $course = null)
    {
        if (!$this->canBeUsedBy($user, $amount)) {
            throw new \Exception('Coupon cannot be used');
        }

        $discountAmount = $this->calculateDiscount($amount);
        
        // Record usage
        $this->usages()->create([
            'user_id' => $user->id,
            'course_id' => $course?->id,
            'discount_amount' => $discountAmount
        ]);
        
        // Increment usage count
        $this->increment('used_count');
        
        return $discountAmount;
    }

    public function getFormattedValue()
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        } else {
            return '$' . number_format($this->value, 2);
        }
    }

    public function getRemainingUses()
    {
        if (!$this->usage_limit) return null;
        return max(0, $this->usage_limit - $this->used_count);
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isNotStarted()
    {
        return $this->starts_at && $this->starts_at->isFuture();
    }
}
