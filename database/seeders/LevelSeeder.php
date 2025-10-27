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
            ['name' => 'SS1', 'type' => 'secondary'],
            ['name' => 'SS2', 'type' => 'secondary'],
            ['name' => 'SS3', 'type' => 'secondary'],
            
            // University Levels
            ['name' => '100 Level', 'type' => 'university'],
            ['name' => '200 Level', 'type' => 'university'],
            ['name' => '300 Level', 'type' => 'university'],
            ['name' => '400 Level', 'type' => 'university'],
            ['name' => '500 Level', 'type' => 'university'],
            
            // Grade School Levels
            ['name' => 'Grade 1', 'type' => 'grade'],
            ['name' => 'Grade 2', 'type' => 'grade'],
            ['name' => 'Grade 3', 'type' => 'grade'],
            ['name' => 'Grade 4', 'type' => 'grade'],
            ['name' => 'Grade 5', 'type' => 'grade'],
            ['name' => 'Grade 6', 'type' => 'grade'],
            ['name' => 'Grade 7', 'type' => 'grade'],
            ['name' => 'Grade 8', 'type' => 'grade'],
            ['name' => 'Grade 9', 'type' => 'grade'],
            ['name' => 'Grade 10', 'type' => 'grade'],
            ['name' => 'Grade 11', 'type' => 'grade'],
            ['name' => 'Grade 12', 'type' => 'grade'],
        ];

        foreach ($levels as $level) {
            Level::updateOrCreate(
                ['name' => $level['name'], 'type' => $level['type']],
                $level
            );
        }
    }
}
