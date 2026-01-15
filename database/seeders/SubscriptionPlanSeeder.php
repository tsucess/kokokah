<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'title' => 'Free Plan',
                'description' => 'Access to free courses',
                'price' => 0,
                'duration' => 1,
                'duration_type' => 'free',
                'features' => [
                    'Access to free courses',
                    'Basic support'
                ],
                'is_active' => true,
                'max_users' => null
            ],
            [
                'title' => 'Daily Plan',
                'description' => 'Access to class notes, anytime, anywhere',
                'price' => 300,
                'duration' => 1,
                'duration_type' => 'daily',
                'features' => [
                    'Valid for 24hrs',
                    'Access to subject notes',
                    'Basic support'
                ],
                'is_active' => true,
                'max_users' => null
            ],
            [
                'title' => 'Weekly Plan',
                'description' => 'Full access to all learning materials for a week',
                'price' => 1500,
                'duration' => 7,
                'duration_type' => 'weekly',
                'features' => [
                    'Valid for 7 days',
                    'Access to all subject notes',
                    'Video tutorials',
                    'Email support',
                    'Progress tracking'
                ],
                'is_active' => true,
                'max_users' => null
            ],
            [
                'title' => 'Monthly Plan',
                'description' => 'Comprehensive access to all learning resources',
                'price' => 5000,
                'duration' => 30,
                'duration_type' => 'monthly',
                'features' => [
                    'Valid for 30 days',
                    'Unlimited access to all materials',
                    'Video tutorials and webinars',
                    'Priority email support',
                    'Progress tracking and analytics',
                    'Certificate of completion',
                    'Access to forums'
                ],
                'is_active' => true,
                'max_users' => null
            ],
            [
                'title' => 'Yearly Plan',
                'description' => 'Premium annual subscription with all features',
                'price' => 50000,
                'duration' => 365,
                'duration_type' => 'yearly',
                'features' => [
                    'Valid for 365 days',
                    'Unlimited access to all materials',
                    'All video tutorials and webinars',
                    '24/7 priority support',
                    'Advanced analytics and insights',
                    'Multiple certificates',
                    'Forum access and community',
                    'Exclusive content',
                    'One-on-one mentoring sessions',
                    'Early access to new courses'
                ],
                'is_active' => true,
                'max_users' => null
            ]
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}

