<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CurriculumCategory;
use App\Models\CourseCategory;
use App\Models\Level;
use App\Models\Term;
use App\Models\Badge;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds for production environment
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@kokokah.com'],
            [
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'contact' => '+234-XXX-XXX-XXXX',
                'gender' => 'male',
                'date_of_birth' => '1990-01-01',
                'address' => 'Lagos, Nigeria',
            ]
        );

        // Create default categories
        $categories = [
            ['title' => 'Mathematics', 'description' => 'Mathematical subjects and topics'],
            ['title' => 'English Language', 'description' => 'English language and literature'],
            ['title' => 'Sciences', 'description' => 'Physics, Chemistry, Biology'],
            ['title' => 'Social Studies', 'description' => 'History, Geography, Government'],
            ['title' => 'Computer Science', 'description' => 'Programming and computer literacy'],
            ['title' => 'Arts', 'description' => 'Creative arts and design'],
            ['title' => 'Languages', 'description' => 'Foreign languages and linguistics'],
            ['title' => 'Business Studies', 'description' => 'Commerce, accounting, economics'],
        ];

        foreach ($categories as $categoryData) {
            CurriculumCategory::firstOrCreate(
                ['title' => $categoryData['title']],
                array_merge($categoryData, ['user_id' => $admin->id])
            );
        }

        // Create academic levels
        $levels = [
            ['name' => 'JSS 1', 'type' => 'secondary'],
            ['name' => 'JSS 2', 'type' => 'secondary'],
            ['name' => 'JSS 3', 'type' => 'secondary'],
            ['name' => 'SS 1', 'type' => 'secondary'],
            ['name' => 'SS 2', 'type' => 'secondary'],
            ['name' => 'SS 3', 'type' => 'secondary'],
            ['name' => '100 Level', 'type' => 'university'],
            ['name' => '200 Level', 'type' => 'university'],
            ['name' => '300 Level', 'type' => 'university'],
            ['name' => '400 Level', 'type' => 'university'],
            ['name' => 'Grade 6', 'type' => 'grade'],
            ['name' => 'Grade 7', 'type' => 'grade'],
            ['name' => 'Grade 8', 'type' => 'grade'],
            ['name' => 'Grade 9', 'type' => 'grade'],
        ];

        foreach ($levels as $levelData) {
            Level::firstOrCreate($levelData);
        }

        // Create academic terms
        $currentYear = date('Y');
        $terms = [
            ['name' => 'First Term', 'year' => $currentYear],
            ['name' => 'Second Term', 'year' => $currentYear],
            ['name' => 'Third Term', 'year' => $currentYear],
            ['name' => 'First Term', 'year' => $currentYear + 1],
            ['name' => 'Second Term', 'year' => $currentYear + 1],
            ['name' => 'Third Term', 'year' => $currentYear + 1],
        ];

        foreach ($terms as $termData) {
            Term::firstOrCreate($termData);
        }

        // Create default badges (simplified to match table structure)
        $badges = [
            [
                'name' => 'First Course Completed',
                'icon' => 'trophy',
                'criteria' => json_encode(['courses_completed' => 1]),
            ],
            [
                'name' => 'Quick Learner',
                'icon' => 'lightning',
                'criteria' => json_encode(['courses_completed' => 5, 'timeframe' => '1 month']),
            ],
            [
                'name' => 'Perfect Score',
                'icon' => 'star',
                'criteria' => json_encode(['quiz_score' => 100]),
            ],
            [
                'name' => 'Dedicated Student',
                'icon' => 'calendar',
                'criteria' => json_encode(['login_streak' => 30]),
            ],
        ];

        foreach ($badges as $badgeData) {
            Badge::firstOrCreate(['name' => $badgeData['name']], $badgeData);
        }

        // Create system settings
        $settings = [
            ['key' => 'site_name', 'value' => 'Kokokah Learning Management System'],
            ['key' => 'site_description', 'value' => 'Premier online learning platform for Nigerian students'],
            ['key' => 'contact_email', 'value' => 'support@kokokah.com'],
            ['key' => 'contact_phone', 'value' => '+234-XXX-XXX-XXXX'],
            ['key' => 'default_currency', 'value' => 'NGN'],
            ['key' => 'timezone', 'value' => 'Africa/Lagos'],
            ['key' => 'max_file_size', 'value' => '10240'], // 10MB in KB
            ['key' => 'allowed_file_types', 'value' => 'jpg,jpeg,png,gif,pdf,doc,docx,mp4,mp3'],
            ['key' => 'enable_registration', 'value' => 'true'],
            ['key' => 'enable_email_verification', 'value' => 'true'],
            ['key' => 'enable_notifications', 'value' => 'true'],
            ['key' => 'enable_forums', 'value' => 'true'],
            ['key' => 'enable_certificates', 'value' => 'true'],
            ['key' => 'enable_badges', 'value' => 'true'],
            ['key' => 'enable_wallet', 'value' => 'true'],
            ['key' => 'maintenance_mode', 'value' => 'false'],
        ];

        foreach ($settings as $settingData) {
            Setting::firstOrCreate(['key' => $settingData['key']], $settingData);
        }

        $this->command->info('Production data seeded successfully!');
    }
}
