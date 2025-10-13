<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed basic data
        $this->call([
            LevelSeeder::class,
            TermSeeder::class,
            TagSeeder::class,
            BadgeSeeder::class,
            SettingSeeder::class,
        ]);

        // Create test users
        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@kokokah.com',
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Instructor',
            'email' => 'instructor@kokokah.com',
            'role' => 'instructor',
            'is_active' => true,
        ]);

        User::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Student',
            'email' => 'student@kokokah.com',
            'role' => 'student',
            'is_active' => true,
        ]);

        // Create additional test users
        User::factory(10)->create();
    }
}
