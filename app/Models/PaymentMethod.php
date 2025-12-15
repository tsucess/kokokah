<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_holder_name',
        'card_number',
        'card_last_four',
        'expiry_date',
        'cvv',
        'card_type',
        'is_default',
        'is_saved',
        'last_used_at'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_saved' => 'boolean',
        'last_used_at' => 'datetime'
    ];

    protected $hidden = [
        'card_number',
        'cvv'
    ];

    /**
     * Get the user that owns this payment method
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the masked card number for display
     */
    public function getMaskedCardNumber()
    {
        return '**** **** **** ' . $this->card_last_four;
    }

    /**
     * Scope to get default payment method
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope to get saved payment methods
     */
    public function scopeSaved($query)
    {
        return $query->where('is_saved', true);
    }
}
