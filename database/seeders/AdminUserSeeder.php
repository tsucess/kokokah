<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Level;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Get some levels for assignment
        $universityLevel = Level::where('name', '400 Level')->first();
        $secondaryLevel = Level::where('name', 'SS3')->first();

        // Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@kokokah.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'contact' => '+234-800-KOKOKAH',
                'gender' => 'male',
                'level_id' => $universityLevel?->id,
                'date_of_birth' => Carbon::parse('1985-01-15'),
                'address' => 'Lagos, Nigeria',
                'email_verified_at' => now(),
            ]
        );

        // Create System Admin
        $systemAdmin = User::firstOrCreate(
            ['email' => 'system@kokokah.com'],
            [
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'password' => Hash::make('system123'),
                'role' => 'admin',
                'is_active' => true,
                'contact' => '+234-801-SYSTEM',
                'gender' => 'female',
                'level_id' => $universityLevel?->id,
                'date_of_birth' => Carbon::parse('1988-03-22'),
                'address' => 'Abuja, Nigeria',
                'email_verified_at' => now(),
            ]
        );

        // Create Lead Instructor - Mathematics
        $mathInstructor = User::firstOrCreate(
            ['email' => 'math.instructor@kokokah.com'],
            [
                'first_name' => 'Dr. Adebayo',
                'last_name' => 'Ogundimu',
                'password' => Hash::make('instructor123'),
                'role' => 'instructor',
                'is_active' => true,
                'contact' => '+234-802-MATH001',
                'gender' => 'male',
                'level_id' => $universityLevel?->id,
                'date_of_birth' => Carbon::parse('1980-07-10'),
                'address' => 'University of Lagos, Lagos',
                'email_verified_at' => now(),
            ]
        );

        // Create Lead Instructor - English
        $englishInstructor = User::firstOrCreate(
            ['email' => 'english.instructor@kokokah.com'],
            [
                'first_name' => 'Prof. Chinelo',
                'last_name' => 'Okwu',
                'password' => Hash::make('instructor123'),
                'role' => 'instructor',
                'is_active' => true,
                'contact' => '+234-803-ENG001',
                'gender' => 'female',
                'level_id' => $universityLevel?->id,
                'date_of_birth' => Carbon::parse('1975-11-28'),
                'address' => 'University of Ibadan, Ibadan',
                'email_verified_at' => now(),
            ]
        );

        // Create Lead Instructor - Science
        $scienceInstructor = User::firstOrCreate(
            ['email' => 'science.instructor@kokokah.com'],
            [
                'first_name' => 'Dr. Emeka',
                'last_name' => 'Nwosu',
                'password' => Hash::make('instructor123'),
                'role' => 'instructor',
                'is_active' => true,
                'contact' => '+234-804-SCI001',
                'gender' => 'male',
                'level_id' => $universityLevel?->id,
                'date_of_birth' => Carbon::parse('1982-04-15'),
                'address' => 'University of Nigeria, Nsukka',
                'email_verified_at' => now(),
            ]
        );

        // Create Lead Instructor - Computer Science
        $csInstructor = User::firstOrCreate(
            ['email' => 'cs.instructor@kokokah.com'],
            [
                'first_name' => 'Eng. Fatima',
                'last_name' => 'Abdullahi',
                'password' => Hash::make('instructor123'),
                'role' => 'instructor',
                'is_active' => true,
                'contact' => '+234-805-CS001',
                'gender' => 'female',
                'level_id' => $universityLevel?->id,
                'date_of_birth' => Carbon::parse('1987-09-03'),
                'address' => 'Ahmadu Bello University, Zaria',
                'email_verified_at' => now(),
            ]
        );

        // Create Secondary School Instructor
        $secondaryInstructor = User::firstOrCreate(
            ['email' => 'secondary.instructor@kokokah.com'],
            [
                'first_name' => 'Mr. Olumide',
                'last_name' => 'Adeyemi',
                'password' => Hash::make('instructor123'),
                'role' => 'instructor',
                'is_active' => true,
                'contact' => '+234-806-SEC001',
                'gender' => 'male',
                'level_id' => $secondaryLevel?->id,
                'date_of_birth' => Carbon::parse('1990-12-08'),
                'address' => 'Lagos State, Nigeria',
                'email_verified_at' => now(),
            ]
        );

        // Create Content Manager (Admin role)
        $contentManager = User::firstOrCreate(
            ['email' => 'content@kokokah.com'],
            [
                'first_name' => 'Mrs. Blessing',
                'last_name' => 'Eze',
                'password' => Hash::make('content123'),
                'role' => 'admin',
                'is_active' => true,
                'contact' => '+234-807-CONTENT',
                'gender' => 'female',
                'level_id' => $universityLevel?->id,
                'date_of_birth' => Carbon::parse('1985-06-20'),
                'address' => 'Port Harcourt, Nigeria',
                'email_verified_at' => now(),
            ]
        );

        echo "✅ Admin and Instructor users created successfully!\n";
        echo "📧 Admin Emails: admin@kokokah.com, system@kokokah.com, content@kokokah.com\n";
        echo "👨‍🏫 Instructor Emails: math.instructor@kokokah.com, english.instructor@kokokah.com, science.instructor@kokokah.com, cs.instructor@kokokah.com, secondary.instructor@kokokah.com\n";
        echo "🔑 Default passwords: admin123, system123, content123, instructor123\n";
    }
}
