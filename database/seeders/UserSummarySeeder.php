<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Level;

class UserSummarySeeder extends Seeder
{
    public function run()
    {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "🎓 KOKOKAH.COM LMS - USER DATABASE SUMMARY\n";
        echo str_repeat("=", 80) . "\n\n";

        // Overall Statistics
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $instructorCount = User::where('role', 'instructor')->count();
        $studentCount = User::where('role', 'student')->count();
        $activeUsers = User::where('is_active', true)->count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();

        echo "📊 OVERALL STATISTICS:\n";
        echo "   Total Users: {$totalUsers}\n";
        echo "   Active Users: {$activeUsers} (" . round(($activeUsers/$totalUsers)*100, 1) . "%)\n";
        echo "   Verified Users: {$verifiedUsers} (" . round(($verifiedUsers/$totalUsers)*100, 1) . "%)\n\n";

        // Role Distribution
        echo "👥 ROLE DISTRIBUTION:\n";
        echo "   👑 Admins: {$adminCount}\n";
        echo "   👨‍🏫 Instructors: {$instructorCount}\n";
        echo "   👨‍🎓 Students: {$studentCount}\n\n";

        // Admin Users
        echo "👑 ADMIN USERS:\n";
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            echo "   📧 {$admin->email} - {$admin->full_name}\n";
        }
        echo "\n";

        // Instructor Users
        echo "👨‍🏫 INSTRUCTOR USERS:\n";
        $instructors = User::where('role', 'instructor')->get();
        foreach ($instructors as $instructor) {
            echo "   📧 {$instructor->email} - {$instructor->full_name}\n";
        }
        echo "\n";

        // Student Statistics by Level
        echo "👨‍🎓 STUDENT DISTRIBUTION BY LEVEL:\n";
        $levels = Level::withCount(['users' => function($query) {
            $query->where('role', 'student');
        }])->get();

        $universityStudents = 0;
        $secondaryStudents = 0;
        $gradeStudents = 0;

        foreach ($levels as $level) {
            if ($level->users_count > 0) {
                echo "   📚 {$level->name} ({$level->type}): {$level->users_count} students\n";
                
                if ($level->type === 'university') $universityStudents += $level->users_count;
                elseif ($level->type === 'secondary') $secondaryStudents += $level->users_count;
                elseif ($level->type === 'grade') $gradeStudents += $level->users_count;
            }
        }

        echo "\n📈 STUDENT SUMMARY BY EDUCATION LEVEL:\n";
        echo "   🎓 University Students: {$universityStudents}\n";
        echo "   🏫 Secondary Students: {$secondaryStudents}\n";
        echo "   📚 Grade Students: {$gradeStudents}\n\n";

        // Gender Distribution
        $maleCount = User::where('gender', 'male')->count();
        $femaleCount = User::where('gender', 'female')->count();
        $unknownGender = User::whereNull('gender')->count();

        echo "⚧ GENDER DISTRIBUTION:\n";
        echo "   👨 Male: {$maleCount} (" . round(($maleCount/$totalUsers)*100, 1) . "%)\n";
        echo "   👩 Female: {$femaleCount} (" . round(($femaleCount/$totalUsers)*100, 1) . "%)\n";
        if ($unknownGender > 0) {
            echo "   ❓ Unknown: {$unknownGender} (" . round(($unknownGender/$totalUsers)*100, 1) . "%)\n";
        }
        echo "\n";

        // Sample Student Emails
        echo "📧 SAMPLE STUDENT EMAILS (First 10):\n";
        $sampleStudents = User::where('role', 'student')->limit(10)->get();
        foreach ($sampleStudents as $student) {
            $level = $student->level ? $student->level->name : 'No Level';
            echo "   📧 {$student->email} - {$student->full_name} ({$level})\n";
        }
        echo "\n";

        // Login Credentials Summary
        echo "🔑 LOGIN CREDENTIALS:\n";
        echo "   👑 Admin Password: admin123, system123, content123\n";
        echo "   👨‍🏫 Instructor Password: instructor123\n";
        echo "   👨‍🎓 Student Password: student123\n\n";

        // Database Health Check
        echo "🏥 DATABASE HEALTH CHECK:\n";
        $usersWithWallets = User::whereHas('wallet')->count();
        $usersWithoutWallets = $totalUsers - $usersWithWallets;
        
        echo "   💰 Users with Wallets: {$usersWithWallets}\n";
        if ($usersWithoutWallets > 0) {
            echo "   ⚠️ Users without Wallets: {$usersWithoutWallets}\n";
        }
        
        $usersWithIdentifiers = User::whereNotNull('identifier')->count();
        echo "   🆔 Users with Identifiers: {$usersWithIdentifiers}\n";
        
        echo "\n";
        echo str_repeat("=", 80) . "\n";
        echo "✅ USER DATABASE SUMMARY COMPLETE\n";
        echo str_repeat("=", 80) . "\n\n";
    }
}
