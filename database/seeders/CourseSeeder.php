<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use App\Models\Level;
use App\Models\Term;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get instructors
        $instructors = User::where('role', 'instructor')->get();
        
        if ($instructors->isEmpty()) {
            echo "❌ No instructors found! Please run AdminUserSeeder first.\n";
            return;
        }

        // Get categories
        $categories = Category::all();
        if ($categories->isEmpty()) {
            echo "❌ No categories found! Please run CategorySeeder first.\n";
            return;
        }

        // Get levels and terms
        $levels = Level::all();
        $terms = Term::all();

        if ($levels->isEmpty() || $terms->isEmpty()) {
            echo "❌ No levels or terms found! Please run LevelSeeder and TermSeeder first.\n";
            return;
        }

        $courses = [
            // Mathematics Courses
            [
                'title' => 'Introduction to Algebra',
                'description' => 'Learn the fundamentals of algebra including equations, inequalities, and functions.',
                'category_id' => $categories->where('title', 'Mathematics')->first()->id ?? $categories->first()->id,
                'instructor_id' => $instructors->first()->id,
                'term_id' => $terms->first()->id,
                'level_id' => $levels->first()->id,
                'price' => 5000,
                'status' => 'published',
                'duration_hours' => 40,
                'difficulty' => 'beginner',
                'max_students' => 50,
            ],
            [
                'title' => 'Advanced Calculus',
                'description' => 'Master calculus concepts including derivatives, integrals, and applications.',
                'category_id' => $categories->where('title', 'Mathematics')->first()->id ?? $categories->first()->id,
                'instructor_id' => $instructors->skip(1)->first()->id ?? $instructors->first()->id,
                'term_id' => $terms->first()->id,
                'level_id' => $levels->skip(1)->first()->id ?? $levels->first()->id,
                'price' => 8000,
                'status' => 'published',
                'duration_hours' => 60,
                'difficulty' => 'advanced',
                'max_students' => 30,
            ],
            // English Courses
            [
                'title' => 'English Literature Basics',
                'description' => 'Explore classic and contemporary literature with critical analysis.',
                'category_id' => $categories->where('title', 'English')->first()->id ?? $categories->first()->id,
                'instructor_id' => $instructors->skip(2)->first()->id ?? $instructors->first()->id,
                'term_id' => $terms->first()->id,
                'level_id' => $levels->first()->id,
                'price' => 4000,
                'status' => 'published',
                'duration_hours' => 35,
                'difficulty' => 'beginner',
                'max_students' => 40,
            ],
            // Science Courses
            [
                'title' => 'Physics Fundamentals',
                'description' => 'Understand the basic principles of physics and their real-world applications.',
                'category_id' => $categories->where('title', 'Science')->first()->id ?? $categories->first()->id,
                'instructor_id' => $instructors->skip(3)->first()->id ?? $instructors->first()->id,
                'term_id' => $terms->first()->id,
                'level_id' => $levels->first()->id,
                'price' => 6000,
                'status' => 'published',
                'duration_hours' => 45,
                'difficulty' => 'intermediate',
                'max_students' => 35,
            ],
            [
                'title' => 'Chemistry for Beginners',
                'description' => 'Introduction to chemical reactions, elements, and compounds.',
                'category_id' => $categories->where('title', 'Science')->first()->id ?? $categories->first()->id,
                'instructor_id' => $instructors->skip(4)->first()->id ?? $instructors->first()->id,
                'term_id' => $terms->first()->id,
                'level_id' => $levels->first()->id,
                'price' => 5500,
                'status' => 'published',
                'duration_hours' => 40,
                'difficulty' => 'beginner',
                'max_students' => 45,
            ],
            // Computer Science Courses
            [
                'title' => 'Web Development with Laravel',
                'description' => 'Build modern web applications using the Laravel framework.',
                'category_id' => $categories->where('title', 'Computer Science')->first()->id ?? $categories->first()->id,
                'instructor_id' => $instructors->skip(5)->first()->id ?? $instructors->first()->id,
                'term_id' => $terms->first()->id,
                'level_id' => $levels->skip(1)->first()->id ?? $levels->first()->id,
                'price' => 10000,
                'status' => 'published',
                'duration_hours' => 80,
                'difficulty' => 'intermediate',
                'max_students' => 25,
            ],
            [
                'title' => 'Python Programming',
                'description' => 'Learn Python programming from basics to advanced concepts.',
                'category_id' => $categories->where('title', 'Computer Science')->first()->id ?? $categories->first()->id,
                'instructor_id' => $instructors->first()->id,
                'term_id' => $terms->first()->id,
                'level_id' => $levels->first()->id,
                'price' => 7000,
                'status' => 'draft',
                'duration_hours' => 50,
                'difficulty' => 'beginner',
                'max_students' => 40,
            ],
            // History Course
            [
                'title' => 'World History Overview',
                'description' => 'A comprehensive overview of major historical events and civilizations.',
                'category_id' => $categories->where('title', 'History')->first()->id ?? $categories->first()->id,
                'instructor_id' => $instructors->skip(1)->first()->id ?? $instructors->first()->id,
                'term_id' => $terms->first()->id,
                'level_id' => $levels->first()->id,
                'price' => 4500,
                'status' => 'published',
                'duration_hours' => 38,
                'difficulty' => 'beginner',
                'max_students' => 50,
            ],
            // Geography Course
            [
                'title' => 'Physical Geography',
                'description' => 'Study landforms, climate systems, and natural processes.',
                'category_id' => $categories->where('title', 'Geography')->first()->id ?? $categories->first()->id,
                'instructor_id' => $instructors->skip(2)->first()->id ?? $instructors->first()->id,
                'term_id' => $terms->first()->id,
                'level_id' => $levels->first()->id,
                'price' => 4800,
                'status' => 'published',
                'duration_hours' => 42,
                'difficulty' => 'intermediate',
                'max_students' => 35,
            ],
            // Economics Course
            [
                'title' => 'Microeconomics Essentials',
                'description' => 'Understand individual economic behavior and market dynamics.',
                'category_id' => $categories->where('title', 'Economics')->first()->id ?? $categories->first()->id,
                'instructor_id' => $instructors->skip(3)->first()->id ?? $instructors->first()->id,
                'term_id' => $terms->first()->id,
                'level_id' => $levels->first()->id,
                'price' => 6500,
                'status' => 'published',
                'duration_hours' => 48,
                'difficulty' => 'intermediate',
                'max_students' => 30,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }

        echo "✅ " . count($courses) . " courses created successfully!\n";
        echo "📊 Course Distribution:\n";
        echo "   📚 Published: " . Course::where('status', 'published')->count() . "\n";
        echo "   📝 Draft: " . Course::where('status', 'draft')->count() . "\n";
        echo "   📈 Total: " . Course::count() . "\n";
    }
}

