<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TransactionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transactions')->insert([
            // 1: Course purchase credit
            [
                'wallet_id' => 18,
                'amount' => 15000.00,
                'type' => 'credit',
                'reference' => 'TXN-CRS-0001',
                'status' => 'success',
                'description' => 'Course purchase: Web Development Basics',
                'metadata' => json_encode([
                    'payment_method' => 'card',
                    'platform' => 'flutterwave'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'related_user_id' => 5,
                'course_id' => 2,
                'reward_type' => null
            ],

            // 2: Course purchase debit
            [
                'wallet_id' => 18,
                'amount' => 15000.00,
                'type' => 'debit',
                'reference' => 'TXN-CRS-0002',
                'status' => 'success',
                'description' => 'Wallet debit for course: Web Development Basics',
                'metadata' => json_encode([
                    'payment_method' => 'wallet'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'related_user_id' => 5,
                'course_id' => 2,
                'reward_type' => null
            ],

            // 3: Daily login reward
            [
                'wallet_id' => 21,
                'amount' => 50.00,
                'type' => 'credit',
                'reference' => 'TXN-RWD-0003',
                'status' => 'success',
                'description' => 'Daily login reward',
                'metadata' => json_encode([
                    'day' => '2025-12-01'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'related_user_id' => 8,
                'course_id' => 3,
                'reward_type' => 'daily_login'
            ],

            // 4: Study time reward
            [
                'wallet_id' => 21,
                'amount' => 120.00,
                'type' => 'credit',
                'reference' => 'TXN-RWD-0004',
                'status' => 'success',
                'description' => 'Study time reward (45 minutes)',
                'metadata' => json_encode([
                    'minutes' => 45
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'related_user_id' => 8,
                'course_id' => 5,
                'reward_type' => 'study_time'
            ],

            // 5: Course completion reward
            [
                'wallet_id' => 23,
                'amount' => 500.00,
                'type' => 'credit',
                'reference' => 'TXN-RWD-0005',
                'status' => 'success',
                'description' => 'Course completion reward',
                'metadata' => json_encode([
                    'course_id' => 4,
                    'course_title' => 'Advanced UI/UX'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'related_user_id' => 10,
                'course_id' => 4,
                'reward_type' => 'course_completion'
            ],

            // 6: Quiz perfect reward
            [
                'wallet_id' => 23,
                'amount' => 200.00,
                'type' => 'credit',
                'reference' => 'TXN-RWD-0006',
                'status' => 'success',
                'description' => 'Perfect quiz score reward',
                'metadata' => json_encode([
                    'score' => '100%',
                    'quiz_id' => 12
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'related_user_id' => 10,
                'course_id' => 2,
                'reward_type' => 'quiz_perfect'
            ],

            // 7: Referral reward
            [
                'wallet_id' => 19,
                'amount' => 300.00,
                'type' => 'credit',
                'reference' => 'TXN-RWD-0007',
                'status' => 'success',
                'description' => 'Referral reward for inviting a new user',
                'metadata' => json_encode([
                    'referred_user_id' => 15
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'related_user_id' => 6,
                'course_id' => 6,
                'reward_type' => 'referral'
            ],

            // 8: Pending wallet funding
            [
                'wallet_id' => 15,
                'amount' => 20000.00,
                'type' => 'credit',
                'reference' => 'TXN-FUND-0008',
                'status' => 'pending',
                'description' => 'Wallet funding via Paystack',
                'metadata' => json_encode([
                    'payment_gateway' => 'paystack'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'related_user_id' => 2,
                'course_id' => 4,
                'reward_type' => null
            ],

            // 9: Failed wallet funding
            [
                'wallet_id' => 15,
                'amount' => 20000.00,
                'type' => 'credit',
                'reference' => 'TXN-FUND-0009',
                'status' => 'failed',
                'description' => 'Wallet funding failure',
                'metadata' => json_encode([
                    'reason' => 'insufficient_funds'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'related_user_id' => 2,
                'course_id' => 5,
                'reward_type' => null
            ],

            // 10: Instructor payout
            [
                'wallet_id' => 15,
                'amount' => 5000.00,
                'type' => 'debit',
                'reference' => 'TXN-PAYOUT-0010',
                'status' => 'success',
                'description' => 'Instructor payout withdrawal',
                'metadata' => json_encode([
                    'method' => 'bank_transfer'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'related_user_id' => 2,
                'course_id' => 5,
                'reward_type' => null
            ],
        ]);
    }
}
