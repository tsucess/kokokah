<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Kokokah LMS',
                'type' => 'string',
                'description' => 'The name of the learning management system'
            ],
            [
                'key' => 'site_description',
                'value' => 'A comprehensive learning management system for students and educators',
                'type' => 'string',
                'description' => 'Brief description of the platform'
            ],
            [
                'key' => 'default_currency',
                'value' => 'NGN',
                'type' => 'string',
                'description' => 'Default currency for transactions'
            ],
            [
                'key' => 'enable_registration',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Allow new user registrations'
            ],
            [
                'key' => 'require_email_verification',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Require email verification for new accounts'
            ],
            
            // Course Settings
            [
                'key' => 'max_course_price',
                'value' => '100000',
                'type' => 'integer',
                'description' => 'Maximum price for a course in cents'
            ],
            [
                'key' => 'default_course_duration',
                'value' => '40',
                'type' => 'integer',
                'description' => 'Default course duration in hours'
            ],
            [
                'key' => 'auto_enroll_free_courses',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Automatically enroll users in free courses'
            ],
            
            // Quiz Settings
            [
                'key' => 'default_quiz_time_limit',
                'value' => '60',
                'type' => 'integer',
                'description' => 'Default quiz time limit in minutes'
            ],
            [
                'key' => 'default_quiz_attempts',
                'value' => '3',
                'type' => 'integer',
                'description' => 'Default number of quiz attempts allowed'
            ],
            [
                'key' => 'default_passing_score',
                'value' => '70',
                'type' => 'integer',
                'description' => 'Default passing score percentage'
            ],
            
            // Notification Settings
            [
                'key' => 'send_welcome_email',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Send welcome email to new users'
            ],
            [
                'key' => 'send_course_completion_email',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Send email when user completes a course'
            ],
            [
                'key' => 'send_assignment_reminder',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Send reminder emails for upcoming assignments'
            ],
            
            // AI Settings
            [
                'key' => 'enable_ai_recommendations',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable AI-powered course recommendations'
            ],
            [
                'key' => 'enable_ai_chat',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable AI chat assistant'
            ],
            
            // Forum Settings
            [
                'key' => 'enable_forums',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable course discussion forums'
            ],
            [
                'key' => 'allow_anonymous_posts',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Allow anonymous forum posts'
            ],
            
            // Gamification Settings
            [
                'key' => 'enable_badges',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable badge system'
            ],
            [
                'key' => 'enable_certificates',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable course completion certificates'
            ],

            // Wallet & Reward Settings
            [
                'key' => 'daily_login_reward_amount',
                'value' => '10',
                'type' => 'integer',
                'description' => 'Base amount for daily login reward'
            ],
            [
                'key' => 'study_time_reward_amount',
                'value' => '5',
                'type' => 'integer',
                'description' => 'Base amount for study time reward'
            ],
            [
                'key' => 'course_completion_reward_amount',
                'value' => '50',
                'type' => 'integer',
                'description' => 'Base amount for course completion reward'
            ],
            [
                'key' => 'minimum_study_time_for_reward',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Minimum study time in minutes to earn reward'
            ],
            [
                'key' => 'maximum_daily_study_reward',
                'value' => '25',
                'type' => 'integer',
                'description' => 'Maximum study reward per day'
            ],
            [
                'key' => 'enable_wallet_transfers',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Allow users to transfer money to each other'
            ],
            [
                'key' => 'minimum_transfer_amount',
                'value' => '1',
                'type' => 'integer',
                'description' => 'Minimum amount for transfers'
            ],
            [
                'key' => 'maximum_transfer_amount',
                'value' => '1000',
                'type' => 'integer',
                'description' => 'Maximum amount for transfers'
            ],
            [
                'key' => 'wallet_currency',
                'value' => 'NGN',
                'type' => 'string',
                'description' => 'Default wallet currency'
            ],
            [
                'key' => 'enable_daily_rewards',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable daily login rewards'
            ],
            [
                'key' => 'enable_study_rewards',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable study time rewards'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
