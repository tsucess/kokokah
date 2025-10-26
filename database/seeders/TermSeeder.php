<?php

namespace Database\Seeders;

use App\Models\Term;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    public function run(): void
    {
        $currentYear = now()->year;
        $terms = [
            ['name' => 'First Term', 'year' => $currentYear],
            ['name' => 'Second Term', 'year' => $currentYear],
            ['name' => 'Third Term', 'year' => $currentYear],
            
            // Previous year
            ['name' => 'First Term', 'year' => $currentYear - 1],
            ['name' => 'Second Term', 'year' => $currentYear - 1],
            ['name' => 'Third Term', 'year' => $currentYear - 1],
            
            // Next year
            ['name' => 'First Term', 'year' => $currentYear + 1],
            ['name' => 'Second Term', 'year' => $currentYear + 1],
            ['name' => 'Third Term', 'year' => $currentYear + 1],
        ];

        foreach ($terms as $term) {
            Term::updateOrCreate(
                ['name' => $term['name'], 'year' => $term['year']],
                $term
            );
        }
    }
}
