<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Level;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StudentUserSeeder extends Seeder
{
    public function run()
    {
        // Get levels for assignment
        $levels = Level::all();
        $universityLevels = Level::where('type', 'university')->get();
        $secondaryLevels = Level::where('type', 'secondary')->get();
        $gradeLevels = Level::where('type', 'grade')->get();

        // Nigerian first names
        $firstNames = [
            'male' => ['Adebayo', 'Chinedu', 'Emeka', 'Olumide', 'Kelechi', 'Tunde', 'Segun', 'Ikechukwu', 'Obinna', 'Chijioke', 'Biodun', 'Femi', 'Kunle', 'Tobi', 'Damilola', 'Ayomide', 'Chidera', 'Nnamdi', 'Okechukwu', 'Temitope'],
            'female' => ['Adunni', 'Chioma', 'Ngozi', 'Folake', 'Kemi', 'Funmi', 'Blessing', 'Chinelo', 'Nneka', 'Adaora', 'Bukola', 'Yemi', 'Tola', 'Bisola', 'Omolara', 'Chidinma', 'Ifeoma', 'Oluwaseun', 'Temiloluwa', 'Adebimpe']
        ];

        // Nigerian last names
        $lastNames = ['Adebayo', 'Okafor', 'Ogundimu', 'Nwosu', 'Abdullahi', 'Adeyemi', 'Okwu', 'Eze', 'Bello', 'Okonkwo', 'Adesanya', 'Ogbonna', 'Yakubu', 'Olumide', 'Chukwu', 'Adamu', 'Oladele', 'Nnamani', 'Usman', 'Okoro', 'Ajayi', 'Emeka', 'Babatunde', 'Chidi', 'Aminu'];

        // Nigerian cities
        $cities = ['Lagos', 'Abuja', 'Kano', 'Ibadan', 'Port Harcourt', 'Benin City', 'Kaduna', 'Jos', 'Ilorin', 'Enugu', 'Aba', 'Onitsha', 'Warri', 'Sokoto', 'Calabar', 'Uyo', 'Akure', 'Bauchi', 'Minna', 'Gombe'];

        $students = [];

        // Create 50 university students
        for ($i = 1; $i <= 50; $i++) {
            $gender = $i % 2 == 0 ? 'female' : 'male';
            $firstName = $firstNames[$gender][array_rand($firstNames[$gender])];
            $lastName = $lastNames[array_rand($lastNames)];
            $level = $universityLevels->random();
            $city = $cities[array_rand($cities)];
            
            $students[] = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower($firstName . '.' . $lastName . $i . '@student.kokokah.com'),
                'password' => Hash::make('student123'),
                'role' => 'student',
                'is_active' => true,
                'contact' => '+234-' . rand(700, 909) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
                'gender' => $gender,
                'level_id' => $level->id,
                'date_of_birth' => Carbon::now()->subYears(rand(18, 25))->subDays(rand(1, 365)),
                'address' => $city . ', Nigeria',
                'email_verified_at' => rand(0, 1) ? now() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Create 30 secondary school students
        for ($i = 51; $i <= 80; $i++) {
            $gender = $i % 2 == 0 ? 'female' : 'male';
            $firstName = $firstNames[$gender][array_rand($firstNames[$gender])];
            $lastName = $lastNames[array_rand($lastNames)];
            $level = $secondaryLevels->random();
            $city = $cities[array_rand($cities)];
            
            $students[] = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower($firstName . '.' . $lastName . $i . '@student.kokokah.com'),
                'password' => Hash::make('student123'),
                'role' => 'student',
                'is_active' => true,
                'contact' => '+234-' . rand(700, 909) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
                'gender' => $gender,
                'level_id' => $level->id,
                'date_of_birth' => Carbon::now()->subYears(rand(14, 18))->subDays(rand(1, 365)),
                'address' => $city . ', Nigeria',
                'email_verified_at' => rand(0, 1) ? now() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Create 20 grade school students
        for ($i = 81; $i <= 100; $i++) {
            $gender = $i % 2 == 0 ? 'female' : 'male';
            $firstName = $firstNames[$gender][array_rand($firstNames[$gender])];
            $lastName = $lastNames[array_rand($lastNames)];
            $level = $gradeLevels->random();
            $city = $cities[array_rand($cities)];
            
            $students[] = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower($firstName . '.' . $lastName . $i . '@student.kokokah.com'),
                'password' => Hash::make('student123'),
                'role' => 'student',
                'is_active' => rand(0, 10) > 1, // 90% active, 10% inactive
                'contact' => '+234-' . rand(700, 909) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
                'gender' => $gender,
                'level_id' => $level->id,
                'date_of_birth' => Carbon::now()->subYears(rand(6, 14))->subDays(rand(1, 365)),
                'address' => $city . ', Nigeria',
                'email_verified_at' => rand(0, 1) ? now() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert students in chunks for better performance
        $chunks = array_chunk($students, 25);
        foreach ($chunks as $chunk) {
            User::insert($chunk);
        }

        echo "✅ 100 Student users created successfully!\n";
        echo "📊 Distribution:\n";
        echo "   🎓 University Students: 50 (18-25 years)\n";
        echo "   🏫 Secondary Students: 30 (14-18 years)\n";
        echo "   📚 Grade Students: 20 (6-14 years)\n";
        echo "📧 Email format: firstname.lastname[number]@student.kokokah.com\n";
        echo "🔑 Default password: student123\n";
        echo "📱 All students have Nigerian phone numbers and addresses\n";
        echo "✉️ Random email verification status (some verified, some not)\n";
        echo "👥 Gender distribution: 50% male, 50% female\n";
        echo "🏠 Cities: Lagos, Abuja, Kano, Ibadan, Port Harcourt, and 15 more Nigerian cities\n";
    }
}
