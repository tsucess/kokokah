<?php

namespace Database\Seeders;

use App\Models\CurriculumCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get an admin user to assign as category creator
        $adminUser = \App\Models\User::where('role', 'admin')->first();

        if (!$adminUser) {
            echo "❌ No admin user found! Please run AdminUserSeeder first.\n";
            return;
        }

        $categories = [
            [
                'user_id' => $adminUser->id,
                'title' => 'Mathematics',
                'description' => 'Mathematical concepts and problem solving',
            ],
            [
                'user_id' => $adminUser->id,
                'title' => 'English Language',
                'description' => 'English language and literature studies',
            ],
            [
                'user_id' => $adminUser->id,
                'title' => 'Science',
                'description' => 'General science and scientific methods',
            ],
            [
                'user_id' => $adminUser->id,
                'title' => 'Physics',
                'description' => 'Physics concepts and applications',
            ],
            [
                'user_id' => $adminUser->id,
                'title' => 'Chemistry',
                'description' => 'Chemistry principles and experiments',
            ],
            [
                'user_id' => $adminUser->id,
                'title' => 'Biology',
                'description' => 'Biological sciences and life studies',
            ],
            [
                'user_id' => $adminUser->id,
                'title' => 'Computer Science',
                'description' => 'Programming and computer technology',
            ],
            [
                'user_id' => $adminUser->id,
                'title' => 'History',
                'description' => 'Historical events and civilizations',
            ],
            [
                'user_id' => $adminUser->id,
                'title' => 'Geography',
                'description' => 'Physical and human geography',
            ],
            [
                'user_id' => $adminUser->id,
                'title' => 'Economics',
                'description' => 'Economic principles and applications',
            ],
        ];

        foreach ($categories as $category) {
            CurriculumCategory::updateOrCreate(
                ['title' => $category['title']],
                $category
            );
        }

        echo "✅ Categories seeded successfully!\n";
    }
}
