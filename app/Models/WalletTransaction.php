<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'transactions'; // Use existing transactions table

    protected $fillable = [
        'wallet_id',
        'amount',
        'type',
        'reference',
        'status',
        'description',
        'metadata',
        'related_user_id',
        'course_id',
        'reward_type',
        'transaction_fee',
        'net_amount',
        'currency',
        'exchange_rate',
        'payment_method',
        'gateway_reference',
        'gateway_response',
        'processed_at',
        'failed_at',
        'failure_reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'metadata' => 'array',
        'gateway_response' => 'array',
        'processed_at' => 'datetime',
        'failed_at' => 'datetime'
    ];

    protected $dates = [
        'processed_at',
        'failed_at'
    ];

    // Relationships
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->wallet->user();
    }

    public function relatedUser()
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopeByWallet($query, $walletId)
    {
        return $query->where('wallet_id', $walletId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->whereHas('wallet', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCredit($query)
    {
        return $query->whereIn('type', ['deposit', 'refund', 'reward', 'transfer_in', 'commission']);
    }

    public function scopeDebit($query)
    {
        return $query->whereIn('type', ['withdrawal', 'purchase', 'transfer_out', 'fee']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return ($this->currency ?? 'NGN') . ' ' . number_format($this->amount, 2);
    }

    public function getTransactionTypeIconAttribute()
    {
        $icons = [
            'deposit' => 'fas fa-plus-circle text-success',
            'withdrawal' => 'fas fa-minus-circle text-danger',
            'purchase' => 'fas fa-shopping-cart text-primary',
            'refund' => 'fas fa-undo text-info',
            'reward' => 'fas fa-gift text-warning',
            'transfer_in' => 'fas fa-arrow-down text-success',
            'transfer_out' => 'fas fa-arrow-up text-danger',
            'commission' => 'fas fa-percentage text-success',
            'fee' => 'fas fa-credit-card text-secondary'
        ];

        return $icons[$this->type] ?? 'fas fa-exchange-alt text-secondary';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'completed' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            'cancelled' => 'secondary',
            'processing' => 'info'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getIsDebitAttribute()
    {
        return in_array($this->type, ['withdrawal', 'purchase', 'transfer_out', 'fee']);
    }

    public function getIsCreditAttribute()
    {
        return in_array($this->type, ['deposit', 'refund', 'reward', 'transfer_in', 'commission']);
    }

    // Methods
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isDebit()
    {
        return $this->is_debit;
    }

    public function isCredit()
    {
        return $this->is_credit;
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now()
        ]);

        // Update wallet balance
        $this->updateWalletBalance();

        return $this;
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'failed_at' => now(),
            'failure_reason' => $reason
        ]);

        return $this;
    }

    public function markAsPending()
    {
        $this->update(['status' => 'pending']);
        return $this;
    }

    public function updateWalletBalance()
    {
        if (!$this->isCompleted()) {
            return false;
        }

        $wallet = $this->wallet;
        
        if ($this->isCredit()) {
            $wallet->increment('balance', $this->net_amount ?? $this->amount);
        } elseif ($this->isDebit()) {
            $wallet->decrement('balance', $this->net_amount ?? $this->amount);
        }

        return true;
    }

    public function calculateNetAmount()
    {
        $fee = $this->transaction_fee ?? 0;
        
        if ($this->isDebit()) {
            $this->net_amount = $this->amount + $fee;
        } else {
            $this->net_amount = $this->amount - $fee;
        }
        
        $this->save();
        return $this->net_amount;
    }

    public function reverseTransaction($reason = null)
    {
        if (!$this->isCompleted()) {
            return false;
        }

        // Create reverse transaction
        $reverseType = $this->isDebit() ? 'refund' : 'withdrawal';
        
        $reverseTransaction = static::create([
            'wallet_id' => $this->wallet_id,
            'amount' => $this->amount,
            'type' => $reverseType,
            'reference' => 'REV-' . $this->reference,
            'status' => 'completed',
            'description' => 'Reversal: ' . ($reason ?? $this->description),
            'metadata' => [
                'original_transaction_id' => $this->id,
                'reversal_reason' => $reason
            ],
            'processed_at' => now()
        ]);

        // Update wallet balance
        $reverseTransaction->updateWalletBalance();

        // Mark original as reversed
        $this->update(['status' => 'reversed']);

        return $reverseTransaction;
    }

    // Static methods
    public static function createDeposit($walletId, $amount, $reference, $description = null)
    {
        return static::create([
            'wallet_id' => $walletId,
            'amount' => $amount,
            'type' => 'deposit',
            'reference' => $reference,
            'status' => 'pending',
            'description' => $description ?? 'Wallet deposit',
            'currency' => 'NGN'
        ]);
    }

    public static function createPurchase($walletId, $amount, $courseId, $reference, $description = null)
    {
        return static::create([
            'wallet_id' => $walletId,
            'amount' => $amount,
            'type' => 'purchase',
            'reference' => $reference,
            'status' => 'completed',
            'description' => $description ?? 'Course purchase',
            'course_id' => $courseId,
            'currency' => 'NGN',
            'processed_at' => now()
        ]);
    }

    public static function createTransfer($fromWalletId, $toWalletId, $amount, $reference, $description = null)
    {
        // Create debit transaction for sender
        $debitTransaction = static::create([
            'wallet_id' => $fromWalletId,
            'amount' => $amount,
            'type' => 'transfer_out',
            'reference' => $reference,
            'status' => 'completed',
            'description' => $description ?? 'Wallet transfer',
            'related_user_id' => Wallet::find($toWalletId)->user_id,
            'currency' => 'NGN',
            'processed_at' => now()
        ]);

        // Create credit transaction for receiver
        $creditTransaction = static::create([
            'wallet_id' => $toWalletId,
            'amount' => $amount,
            'type' => 'transfer_in',
            'reference' => $reference,
            'status' => 'completed',
            'description' => $description ?? 'Wallet transfer',
            'related_user_id' => Wallet::find($fromWalletId)->user_id,
            'currency' => 'NGN',
            'processed_at' => now()
        ]);

        // Update balances
        $debitTransaction->updateWalletBalance();
        $creditTransaction->updateWalletBalance();

        return [$debitTransaction, $creditTransaction];
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            // Generate reference if not provided
            if (!$transaction->reference) {
                $transaction->reference = 'TXN-' . strtoupper(uniqid());
            }

            // Set default currency
            if (!$transaction->currency) {
                $transaction->currency = 'NGN';
            }

            // Calculate net amount
            $transaction->calculateNetAmount();
        });

        static::updated(function ($transaction) {
            // Update wallet balance when status changes to completed
            if ($transaction->isDirty('status') && $transaction->status === 'completed') {
                $transaction->updateWalletBalance();
            }
        });
    }
}
