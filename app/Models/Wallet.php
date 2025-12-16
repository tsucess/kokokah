<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'currency'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCurrency($query, $currency)
    {
        return $query->where('currency', $currency);
    }

    // Methods
    // Deposit money into wallet
    public function deposit($amount, $reference = null, $description = null, $metadata = null, $paymentMethod = null)
    {
        $transaction = $this->transactions()->create([
            'amount' => $amount,
            'type' => 'credit',
            'reference' => $reference ?: 'DEP-' . uniqid(),
            'status' => 'success',
            'description' => $description ?: 'Wallet deposit',
            'metadata' => $metadata,
            'payment_method' => $paymentMethod
        ]);

        $this->increment('balance', $amount);

        return $transaction;
    }

    // Transfer money to another user
    public function transferTo(Wallet $recipientWallet, $amount, $description = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        $baseReference = 'TRF-' . uniqid();

        // Debit from sender
        $debitTransaction = $this->transactions()->create([
            'amount' => $amount,
            'type' => 'debit',
            'reference' => $baseReference . '-OUT',
            'status' => 'success',
            'description' => $description ?: 'Transfer sent',
            'related_user_id' => $recipientWallet->user_id
        ]);

        // Credit to recipient
        $creditTransaction = $recipientWallet->transactions()->create([
            'amount' => $amount,
            'type' => 'credit',
            'reference' => $baseReference . '-IN',
            'status' => 'success',
            'description' => $description ?: 'Transfer received',
            'related_user_id' => $this->user_id
        ]);

        $this->decrement('balance', $amount);
        $recipientWallet->increment('balance', $amount);

        return ['debit' => $debitTransaction, 'credit' => $creditTransaction];
    }

    // Purchase a course
    public function purchaseCourse(Course $course, $couponCode = null)
    {
        $amount = $course->price;
        $discount = 0;

        // Apply coupon if provided
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->active()->first();
            if ($coupon && $coupon->canBeUsedBy($this->user, $amount)) {
                $discount = $coupon->applyTo($this->user, $amount, $course);
                $amount -= $discount;
            }
        }

        if ($this->balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        $transaction = $this->transactions()->create([
            'amount' => $amount,
            'type' => 'debit',
            'reference' => 'PUR-' . uniqid(),
            'status' => 'success',
            'description' => 'Course purchase: ' . $course->title,
            'course_id' => $course->id,
            'metadata' => [
                'original_price' => $course->price,
                'discount_amount' => $discount,
                'coupon_code' => $couponCode
            ]
        ]);

        $this->decrement('balance', $amount);

        // Enroll user in course
        $this->user->enrollments()->create([
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => now(),
            'amount_paid' => $amount
        ]);

        return $transaction;
    }

    // Add reward to wallet
    public function addReward($amount, $rewardType, $description = null, $metadata = null)
    {
        $transaction = $this->transactions()->create([
            'amount' => $amount,
            'type' => 'credit',
            'reference' => 'REW-' . uniqid(),
            'status' => 'success',
            'description' => $description,
            'reward_type' => $rewardType,
            'metadata' => $metadata,
            'payment_method' => 'Reward'
        ]);

        $this->increment('balance', $amount);

        return $transaction;
    }

    // Withdraw money from wallet
    public function withdraw($amount, $reference = null, $description = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        $transaction = $this->transactions()->create([
            'amount' => $amount,
            'type' => 'debit',
            'reference' => $reference ?: 'WTH-' . uniqid(),
            'status' => 'success',
            'description' => $description ?: 'Wallet withdrawal',
            'payment_method' => 'Wallet'
        ]);

        $this->decrement('balance', $amount);

        return $transaction;
    }

    public function getFormattedBalance()
    {
        return $this->currency . ' ' . number_format($this->balance, 2);
    }

    public function getTotalCredits()
    {
        return $this->transactions()->where('type', 'credit')->where('status', 'success')->sum('amount');
    }

    public function getTotalDebits()
    {
        return $this->transactions()->where('type', 'debit')->where('status', 'success')->sum('amount');
    }
}
