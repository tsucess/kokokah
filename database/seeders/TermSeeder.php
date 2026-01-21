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
            ['name' => 'First Term', 'order' => 1],
            ['name' => 'Second Term', 'order' => 2],
            ['name' => 'Third Term', 'order' => 3],

            // Previous year
            ['name' => 'First Term', 'order' => 1],
            ['name' => 'Second Term', 'order' => 2],
            ['name' => 'Third Term', 'order' => 3],

            // Next year
            ['name' => 'First Term', 'order' => 1],
            ['name' => 'Second Term', 'order' => 2],
            ['name' => 'Third Term', 'order' => 3],
        ];

        foreach ($terms as $term) {
            Term::updateOrCreate(
                ['name' => $term['name']],
                $term
            );
        }
    }
}
