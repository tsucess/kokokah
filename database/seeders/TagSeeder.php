<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Mathematics', 'color' => '#3B82F6'],
            ['name' => 'Science', 'color' => '#10B981'],
            ['name' => 'English', 'color' => '#F59E0B'],
            ['name' => 'History', 'color' => '#8B5CF6'],
            ['name' => 'Geography', 'color' => '#06B6D4'],
            ['name' => 'Physics', 'color' => '#EF4444'],
            ['name' => 'Chemistry', 'color' => '#84CC16'],
            ['name' => 'Biology', 'color' => '#F97316'],
            ['name' => 'Computer Science', 'color' => '#6366F1'],
            ['name' => 'Economics', 'color' => '#EC4899'],
            ['name' => 'Literature', 'color' => '#14B8A6'],
            ['name' => 'Art', 'color' => '#F43F5E'],
            ['name' => 'Music', 'color' => '#A855F7'],
            ['name' => 'Physical Education', 'color' => '#22C55E'],
            ['name' => 'Social Studies', 'color' => '#64748B'],
            ['name' => 'French', 'color' => '#0EA5E9'],
            ['name' => 'Spanish', 'color' => '#F97316'],
            ['name' => 'German', 'color' => '#6B7280'],
            ['name' => 'Philosophy', 'color' => '#7C3AED'],
            ['name' => 'Psychology', 'color' => '#DB2777'],
            ['name' => 'Beginner', 'color' => '#22C55E'],
            ['name' => 'Intermediate', 'color' => '#F59E0B'],
            ['name' => 'Advanced', 'color' => '#EF4444'],
            ['name' => 'Free', 'color' => '#10B981'],
            ['name' => 'Premium', 'color' => '#F59E0B'],
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                ['name' => $tag['name']],
                $tag
            );
        }
    }
}
