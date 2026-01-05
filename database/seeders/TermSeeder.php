<?php

namespace Database\Seeders;

use App\Models\Term;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    public function run(): void
    {
        // $currentYear = now()->year;
        $terms = [
            ['name' => 'First Term'],
            ['name' => 'Second Term'],
            ['name' => 'Third Term'],

            // Previous year
            ['name' => 'First Term'],
            ['name' => 'Second Term'],
            ['name' => 'Third Term'],

            // Next year
            ['name' => 'First Term'],
            ['name' => 'Second Term'],
            ['name' => 'Third Term'],
        ];

        foreach ($terms as $term) {
            Term::updateOrCreate(
                ['name' => $term['name']],
                $term
            );
        }
    }
}
