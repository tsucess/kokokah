<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'amount',
        'currency',
        'gateway',
        'gateway_reference',
        'type',
        'status',
        'metadata',
        'gateway_response',
        'completed_at',
        'failed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'gateway_response' => 'array',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByGateway($query, $gateway)
    {
        return $query->where('gateway', $gateway);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeWalletDeposits($query)
    {
        return $query->where('type', 'wallet_deposit');
    }

    public function scopeCoursePurchases($query)
    {
        return $query->where('type', 'course_purchase');
    }

    // Methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isWalletDeposit()
    {
        return $this->type === 'wallet_deposit';
    }

    public function isCoursePurchase()
    {
        return $this->type === 'course_purchase';
    }

    public function getFormattedAmount()
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }

    public function getGatewayName()
    {
        return match($this->gateway) {
            'paystack' => 'Paystack',
            'flutterwave' => 'Flutterwave',
            'stripe' => 'Stripe',
            'paypal' => 'PayPal',
            default => ucfirst($this->gateway)
        };
    }

    public function getStatusBadge()
    {
        return match($this->status) {
            'pending' => '🟡 Pending',
            'completed' => '✅ Completed',
            'failed' => '❌ Failed',
            'cancelled' => '⚫ Cancelled',
            default => '❓ Unknown'
        };
    }

    public function getTypeDescription()
    {
        return match($this->type) {
            'wallet_deposit' => 'Wallet Deposit',
            'course_purchase' => 'Course Purchase',
            default => ucfirst(str_replace('_', ' ', $this->type))
        };
    }

    public function getDescription()
    {
        if ($this->isWalletDeposit()) {
            return "Wallet deposit of {$this->getFormattedAmount()} via {$this->getGatewayName()}";
        }

        if ($this->isCoursePurchase() && $this->course) {
            return "Purchase of '{$this->course->title}' for {$this->getFormattedAmount()}";
        }

        return "Payment of {$this->getFormattedAmount()} via {$this->getGatewayName()}";
    }

    // Boot method for automatic reference generation
    protected static function booted()
    {
        static::creating(function ($payment) {
            if (!$payment->gateway_reference) {
                $payment->gateway_reference = 'PAY_' . strtoupper(uniqid());
            }
        });
    }
}
