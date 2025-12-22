<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing badges (disable foreign key checks)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('badges')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $badges = [
            // LESSON BADGES (1-3)
            ['name' => 'First Lesson', 'description' => 'Complete your first lesson', 'points' => 10, 'icon' => '📖', 'criteria' => 'lesson_completion:1', 'category' => 'learning', 'type' => 'lesson_completion'],
            ['name' => 'Lesson Enthusiast', 'description' => 'Complete 10 lessons', 'points' => 25, 'icon' => '📚', 'criteria' => 'lesson_completion:10', 'category' => 'learning', 'type' => 'lesson_completion'],
            ['name' => 'Lesson Master', 'description' => 'Complete 50 lessons', 'points' => 50, 'icon' => '📕', 'criteria' => 'lesson_completion:50', 'category' => 'learning', 'type' => 'lesson_completion'],

            // TOPIC BADGES (4-6)
            ['name' => 'Topic Starter', 'description' => 'Complete your first topic', 'points' => 15, 'icon' => '🎯', 'criteria' => 'topic_completion:1', 'category' => 'learning', 'type' => 'topic_completion'],
            ['name' => 'Topic Explorer', 'description' => 'Complete 5 topics', 'points' => 30, 'icon' => '🗺️', 'criteria' => 'topic_completion:5', 'category' => 'learning', 'type' => 'topic_completion'],
            ['name' => 'Topic Conqueror', 'description' => 'Complete 20 topics', 'points' => 60, 'icon' => '⛰️', 'criteria' => 'topic_completion:20', 'category' => 'learning', 'type' => 'topic_completion'],

            // COURSE BADGES (7-10)
            ['name' => 'Course Starter', 'description' => 'Enroll in your first course', 'points' => 5, 'icon' => '🚀', 'criteria' => 'enrollment:1', 'category' => 'learning', 'type' => 'course_enrollment'],
            ['name' => 'Course Completer', 'description' => 'Complete your first course', 'points' => 50, 'icon' => '🎓', 'criteria' => 'course_completion:1', 'category' => 'learning', 'type' => 'course_completion'],
            ['name' => 'Scholar', 'description' => 'Complete 10 courses', 'points' => 100, 'icon' => '🏆', 'criteria' => 'course_completion:10', 'category' => 'learning', 'type' => 'course_completion'],
            ['name' => 'Master Student', 'description' => 'Complete 25 courses', 'points' => 150, 'icon' => '👑', 'criteria' => 'course_completion:25', 'category' => 'learning', 'type' => 'course_completion'],

            // QUIZ BADGES (11-14)
            ['name' => 'Quiz Taker', 'description' => 'Complete your first quiz', 'points' => 10, 'icon' => '❓', 'criteria' => 'quiz_pass:1', 'category' => 'achievement', 'type' => 'quiz_mastery'],
            ['name' => 'Perfect Score', 'description' => 'Get 100% on a quiz', 'points' => 40, 'icon' => '💯', 'criteria' => 'quiz_perfect:1', 'category' => 'achievement', 'type' => 'quiz_mastery'],
            ['name' => 'Quiz Master', 'description' => 'Pass 25 quizzes', 'points' => 75, 'icon' => '🧠', 'criteria' => 'quiz_pass:25', 'category' => 'achievement', 'type' => 'quiz_mastery'],
            ['name' => 'Quiz Legend', 'description' => 'Pass 50 quizzes', 'points' => 120, 'icon' => '⚡', 'criteria' => 'quiz_pass:50', 'category' => 'achievement', 'type' => 'quiz_mastery'],

            // POINTS BADGES (15-17)
            ['name' => 'Point Collector', 'description' => 'Earn 100 points', 'points' => 20, 'icon' => '💰', 'criteria' => 'points:100', 'category' => 'achievement', 'type' => 'points'],
            ['name' => 'Point Hoarder', 'description' => 'Earn 500 points', 'points' => 50, 'icon' => '💎', 'criteria' => 'points:500', 'category' => 'achievement', 'type' => 'points'],
            ['name' => 'Point Master', 'description' => 'Earn 1000 points', 'points' => 100, 'icon' => '👑', 'criteria' => 'points:1000', 'category' => 'achievement', 'type' => 'points'],

            // SPEED & TIME BADGES (18-20)
            ['name' => 'Quick Learner', 'description' => 'Complete a course in less than 7 days', 'points' => 35, 'icon' => '⚡', 'criteria' => 'course_speed:7', 'category' => 'achievement', 'type' => 'speed'],
            ['name' => 'Early Bird', 'description' => 'Complete 5 lessons before 8 AM', 'points' => 25, 'icon' => '🌅', 'criteria' => 'early_bird:5', 'category' => 'achievement', 'type' => 'time'],
            ['name' => 'Night Owl', 'description' => 'Complete 5 lessons after 10 PM', 'points' => 25, 'icon' => '🦉', 'criteria' => 'night_owl:5', 'category' => 'achievement', 'type' => 'time'],

            // CONSISTENCY BADGES (21-23)
            ['name' => 'Consistent Learner', 'description' => 'Study for 7 consecutive days', 'points' => 40, 'icon' => '📅', 'criteria' => 'consecutive_days:7', 'category' => 'achievement', 'type' => 'streak'],
            ['name' => 'Dedicated Learner', 'description' => 'Study for 30 consecutive days', 'points' => 80, 'icon' => '🔥', 'criteria' => 'consecutive_days:30', 'category' => 'achievement', 'type' => 'streak'],
            ['name' => 'Unstoppable', 'description' => 'Study for 100 consecutive days', 'points' => 150, 'icon' => '💪', 'criteria' => 'consecutive_days:100', 'category' => 'achievement', 'type' => 'streak'],

            // CHATROOM & SOCIAL BADGES (24-26)
            ['name' => 'Social Butterfly', 'description' => 'Participate in 10 chatroom discussions', 'points' => 20, 'icon' => '💬', 'criteria' => 'chatroom_posts:10', 'category' => 'social', 'type' => 'participation'],
            ['name' => 'Community Helper', 'description' => 'Help 5 students in chatroom', 'points' => 35, 'icon' => '🤝', 'criteria' => 'helpful_posts:5', 'category' => 'social', 'type' => 'participation'],
            ['name' => 'Community Leader', 'description' => 'Help 20 students in chatroom', 'points' => 70, 'icon' => '👥', 'criteria' => 'helpful_posts:20', 'category' => 'social', 'type' => 'participation'],

            // ENROLLMENT BADGES (27-28)
            ['name' => 'Multi-Learner', 'description' => 'Enroll in 5 courses simultaneously', 'points' => 30, 'icon' => '🎪', 'criteria' => 'active_enrollments:5', 'category' => 'learning', 'type' => 'course_enrollment'],
            ['name' => 'Enrollment Master', 'description' => 'Enroll in 20 courses total', 'points' => 60, 'icon' => '📋', 'criteria' => 'total_enrollments:20', 'category' => 'learning', 'type' => 'course_enrollment'],

            // SPECIAL BADGES (29-30)
            ['name' => 'Instructor', 'description' => 'Create your first course', 'points' => 50, 'icon' => '🎬', 'criteria' => 'instructor:1', 'category' => 'special', 'type' => 'instructor'],
            ['name' => 'Legendary Learner', 'description' => 'Achieve Expert level (1000+ points)', 'points' => 200, 'icon' => '⭐', 'criteria' => 'level:expert', 'category' => 'special', 'type' => 'milestone'],
        ];

        foreach ($badges as $badge) {
            DB::table('badges')->insert(array_merge($badge, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}

