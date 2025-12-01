<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            // Secondary School Levels
            ['name' => 'SS1', 'description' => 'secondary'],
            ['name' => 'SS2', 'description' => 'secondary'],
            ['name' => 'SS3', 'description' => 'secondary'],

            // University Levels
            ['name' => '100 Level', 'description' => 'university'],
            ['name' => '200 Level', 'description' => 'university'],
            ['name' => '300 Level', 'description' => 'university'],
            ['name' => '400 Level', 'description' => 'university'],
            ['name' => '500 Level', 'description' => 'university'],

            // Grade School Levels
            ['name' => 'Grade 1', 'description' => 'grade'],
            ['name' => 'Grade 2', 'description' => 'grade'],
            ['name' => 'Grade 3', 'description' => 'grade'],
            ['name' => 'Grade 4', 'description' => 'grade'],
            ['name' => 'Grade 5', 'description' => 'grade'],
            ['name' => 'Grade 6', 'description' => 'grade'],
            ['name' => 'Grade 7', 'description' => 'grade'],
            ['name' => 'Grade 8', 'description' => 'grade'],
            ['name' => 'Grade 9', 'description' => 'grade'],
            ['name' => 'Grade 10', 'description' => 'grade'],
            ['name' => 'Grade 11', 'description' => 'grade'],
            ['name' => 'Grade 12', 'description' => 'grade'],
        ];

        foreach ($levels as $level) {
            Level::updateOrCreate(
                ['name' => $level['name'], 'description' => $level['description']],
                $level
            );
        }
    }
}
