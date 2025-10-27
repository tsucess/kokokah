<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'amount',
        'type',
        'reference',
        'status',
        'description',
        'metadata',
        'related_user_id', // For transfers
        'course_id', // For course purchases
        'reward_type' // For reward transactions
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
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

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    public function scopeDebits($query)
    {
        return $query->where('type', 'debit');
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeDeposits($query)
    {
        return $query->where('type', 'credit')->whereNull('related_user_id')->whereNull('course_id')->whereNull('reward_type');
    }

    public function scopeTransfers($query)
    {
        return $query->where('type', 'credit')->whereNotNull('related_user_id');
    }

    public function scopePurchases($query)
    {
        return $query->where('type', 'debit')->whereNotNull('course_id');
    }

    public function scopeRewards($query)
    {
        return $query->where('type', 'credit')->whereNotNull('reward_type');
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('type', 'debit')->whereNull('course_id')->whereNull('related_user_id');
    }

    // Methods
    public function isCredit()
    {
        return $this->type === 'credit';
    }

    public function isDebit()
    {
        return $this->type === 'debit';
    }

    public function isSuccessful()
    {
        return $this->status === 'success';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function markAsSuccessful()
    {
        $this->update(['status' => 'success']);
    }

    public function markAsFailed()
    {
        $this->update(['status' => 'failed']);
    }

    public function getFormattedAmount()
    {
        $symbol = $this->isCredit() ? '+' : '-';
        return $symbol . $this->wallet->currency . ' ' . number_format($this->amount, 2);
    }

    // Wallet-specific methods
    public function isDeposit()
    {
        return $this->type === 'credit' && !$this->related_user_id && !$this->course_id && !$this->reward_type;
    }

    public function isTransfer()
    {
        return $this->related_user_id !== null;
    }

    public function isPurchase()
    {
        return $this->type === 'debit' && $this->course_id !== null;
    }

    public function isReward()
    {
        return $this->type === 'credit' && $this->reward_type !== null;
    }

    public function isWithdrawal()
    {
        return $this->type === 'debit' && !$this->course_id && !$this->related_user_id;
    }

    public function getTransactionIcon()
    {
        if ($this->isDeposit()) return '💰';
        if ($this->isTransfer()) return '↔️';
        if ($this->isPurchase()) return '🛒';
        if ($this->isReward()) return '🎁';
        if ($this->isWithdrawal()) return '💸';
        return '💳';
    }

    public function getTransactionDescription()
    {
        if ($this->description) {
            return $this->description;
        }

        if ($this->isDeposit()) return 'Wallet deposit';
        if ($this->isTransfer()) {
            return $this->isCredit()
                ? 'Transfer received from ' . $this->relatedUser?->full_name
                : 'Transfer sent to ' . $this->relatedUser?->full_name;
        }
        if ($this->isPurchase()) return 'Course purchase: ' . $this->course?->title;
        if ($this->isReward()) return $this->getRewardDescription();
        if ($this->isWithdrawal()) return 'Wallet withdrawal';

        return 'Transaction';
    }

    private function getRewardDescription()
    {
        return match($this->reward_type) {
            'daily_login' => 'Daily login reward',
            'study_time' => 'Study time reward',
            'course_completion' => 'Course completion bonus',
            'quiz_perfect' => 'Perfect quiz score bonus',
            'streak_bonus' => 'Study streak bonus',
            'referral' => 'Referral bonus',
            default => 'Reward'
        };
    }
}
