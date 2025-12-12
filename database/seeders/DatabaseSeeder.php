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
        // Seed basic data first
        $this->call([
            LevelSeeder::class,
            TermSeeder::class,
            TagSeeder::class,
            BadgeSeeder::class,
            SettingSeeder::class,
        ]);

        // Seed users with proper roles and data
        $this->call([
            AdminUserSeeder::class,
            StudentUserSeeder::class,
        ]);


        $this->call(TopicSeeder::class);





        echo "\n🎉 Database seeding completed successfully!\n";
        echo "📊 Total users created:\n";
        echo "   👑 Admins: " . User::where('role', 'admin')->count() . "\n";
        echo "   👨‍🏫 Instructors: " . User::where('role', 'instructor')->count() . "\n";
        echo "   👨‍🎓 Students: " . User::where('role', 'student')->count() . "\n";
        echo "   📈 Total: " . User::count() . "\n\n";
    }
}

$this->call(TransactionsSeeder::class);

