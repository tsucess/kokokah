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
            // WELCOME & PROFILE BADGES (1-2)
            ['name' => 'Welcome to Kokokah', 'description' => 'Sign up for the platform', 'points' => 5, 'icon' => '🎉', 'criteria' => 'signup:1', 'category' => 'special', 'type' => 'milestone'],
            ['name' => 'Profile Complete', 'description' => 'Complete your profile', 'points' => 10, 'icon' => '✅', 'criteria' => 'profile_complete:1', 'category' => 'special', 'type' => 'milestone'],

            // LESSON BADGES (3-5)
            ['name' => 'First Lesson', 'description' => 'Complete your first lesson', 'points' => 10, 'icon' => '📖', 'criteria' => 'lesson_completion:1', 'category' => 'learning', 'type' => 'lesson_completion'],
            ['name' => 'Lesson Enthusiast', 'description' => 'Complete 10 lessons', 'points' => 25, 'icon' => '📚', 'criteria' => 'lesson_completion:10', 'category' => 'learning', 'type' => 'lesson_completion'],
            ['name' => 'Lesson Master', 'description' => 'Complete 50 lessons', 'points' => 50, 'icon' => '📕', 'criteria' => 'lesson_completion:50', 'category' => 'learning', 'type' => 'lesson_completion'],

            // TOPIC BADGES (6-8)
            ['name' => 'Topic Starter', 'description' => 'Complete your first topic', 'points' => 15, 'icon' => '🎯', 'criteria' => 'topic_completion:1', 'category' => 'learning', 'type' => 'topic_completion'],
            ['name' => 'Topic Explorer', 'description' => 'Complete 5 topics', 'points' => 30, 'icon' => '🗺️', 'criteria' => 'topic_completion:5', 'category' => 'learning', 'type' => 'topic_completion'],
            ['name' => 'Topic Conqueror', 'description' => 'Complete 20 topics', 'points' => 60, 'icon' => '⛰️', 'criteria' => 'topic_completion:20', 'category' => 'learning', 'type' => 'topic_completion'],

            // COURSE BADGES (9-13)
            ['name' => 'Course Starter', 'description' => 'Enroll in your first course', 'points' => 5, 'icon' => '🚀', 'criteria' => 'enrollment:1', 'category' => 'learning', 'type' => 'course_enrollment'],
            ['name' => 'Course Completer', 'description' => 'Complete your first course', 'points' => 50, 'icon' => '🎓', 'criteria' => 'course_completion:1', 'category' => 'learning', 'type' => 'course_completion'],
            ['name' => 'Scholar', 'description' => 'Complete 10 courses', 'points' => 100, 'icon' => '🏆', 'criteria' => 'course_completion:10', 'category' => 'learning', 'type' => 'course_completion'],
            ['name' => 'Master Student', 'description' => 'Complete 25 courses', 'points' => 150, 'icon' => '👑', 'criteria' => 'course_completion:25', 'category' => 'learning', 'type' => 'course_completion'],
            ['name' => 'Enrollment Enthusiast', 'description' => 'Enroll in 50 courses', 'points' => 120, 'icon' => '📚', 'criteria' => 'enrollment:50', 'category' => 'learning', 'type' => 'course_enrollment'],

            // QUIZ BADGES (14-17)
            ['name' => 'Quiz Taker', 'description' => 'Complete your first quiz', 'points' => 10, 'icon' => '❓', 'criteria' => 'quiz_pass:1', 'category' => 'achievement', 'type' => 'quiz_mastery'],
            ['name' => 'Perfect Score', 'description' => 'Get 100% on a quiz', 'points' => 40, 'icon' => '💯', 'criteria' => 'quiz_perfect:1', 'category' => 'achievement', 'type' => 'quiz_mastery'],
            ['name' => 'Quiz Master', 'description' => 'Pass 25 quizzes', 'points' => 75, 'icon' => '🧠', 'criteria' => 'quiz_pass:25', 'category' => 'achievement', 'type' => 'quiz_mastery'],
            ['name' => 'Quiz Legend', 'description' => 'Pass 50 quizzes', 'points' => 120, 'icon' => '⚡', 'criteria' => 'quiz_pass:50', 'category' => 'achievement', 'type' => 'quiz_mastery'],

            // POINTS BADGES (18-20)
            ['name' => 'Point Collector', 'description' => 'Earn 100 points', 'points' => 20, 'icon' => '💰', 'criteria' => 'points:100', 'category' => 'achievement', 'type' => 'points'],
            ['name' => 'Point Hoarder', 'description' => 'Earn 500 points', 'points' => 50, 'icon' => '💎', 'criteria' => 'points:500', 'category' => 'achievement', 'type' => 'points'],
            ['name' => 'Point Master', 'description' => 'Earn 1000 points', 'points' => 100, 'icon' => '👑', 'criteria' => 'points:1000', 'category' => 'achievement', 'type' => 'points'],

            // SPEED & TIME BADGES (21-23)
            ['name' => 'Quick Learner', 'description' => 'Complete a course in less than 7 days', 'points' => 35, 'icon' => '⚡', 'criteria' => 'course_speed:7', 'category' => 'achievement', 'type' => 'speed'],
            ['name' => 'Early Bird', 'description' => 'Complete 5 lessons before 8 AM', 'points' => 25, 'icon' => '🌅', 'criteria' => 'early_bird:5', 'category' => 'achievement', 'type' => 'time'],
            ['name' => 'Night Owl', 'description' => 'Complete 5 lessons after 10 PM', 'points' => 25, 'icon' => '🦉', 'criteria' => 'night_owl:5', 'category' => 'achievement', 'type' => 'time'],

            // CONSISTENCY BADGES (24-26)
            ['name' => 'Consistent Learner', 'description' => 'Study for 7 consecutive days', 'points' => 40, 'icon' => '📅', 'criteria' => 'consecutive_days:7', 'category' => 'achievement', 'type' => 'streak'],
            ['name' => 'Dedicated Learner', 'description' => 'Study for 30 consecutive days', 'points' => 80, 'icon' => '🔥', 'criteria' => 'consecutive_days:30', 'category' => 'achievement', 'type' => 'streak'],
            ['name' => 'Unstoppable', 'description' => 'Study for 100 consecutive days', 'points' => 150, 'icon' => '💪', 'criteria' => 'consecutive_days:100', 'category' => 'achievement', 'type' => 'streak'],

            // CHATROOM & SOCIAL BADGES (27-30)
            ['name' => 'Social Butterfly', 'description' => 'Participate in 10 chatroom discussions', 'points' => 20, 'icon' => '💬', 'criteria' => 'chatroom_posts:10', 'category' => 'social', 'type' => 'participation'],
            ['name' => 'Community Helper', 'description' => 'Help 5 students in chatroom', 'points' => 35, 'icon' => '🤝', 'criteria' => 'helpful_posts:5', 'category' => 'social', 'type' => 'participation'],
            ['name' => 'Community Leader', 'description' => 'Help 20 students in chatroom', 'points' => 70, 'icon' => '👥', 'criteria' => 'helpful_posts:20', 'category' => 'social', 'type' => 'participation'],
            ['name' => 'Chat Champion', 'description' => 'Be the most active in chatroom', 'points' => 100, 'icon' => '🏅', 'criteria' => 'chatroom_champion:1', 'category' => 'social', 'type' => 'participation'],

            // MONEY TRANSFER BADGES (31-33)
            ['name' => 'Generous Soul', 'description' => 'Transfer money to another user', 'points' => 15, 'icon' => '💸', 'criteria' => 'money_transfer:1', 'category' => 'social', 'type' => 'participation'],
            ['name' => 'Transfer Master', 'description' => 'Complete 10 money transfers', 'points' => 40, 'icon' => '💳', 'criteria' => 'money_transfer:10', 'category' => 'social', 'type' => 'participation'],
            ['name' => 'Transfer Champion', 'description' => 'Be the user with most transfers', 'points' => 150, 'icon' => '💰', 'criteria' => 'transfer_champion:1', 'category' => 'special', 'type' => 'milestone'],

            // SUBSCRIPTION BADGES (34-35)
            ['name' => 'Subscriber', 'description' => 'Subscribe for more than 3 months', 'points' => 50, 'icon' => '⭐', 'criteria' => 'subscription:3', 'category' => 'special', 'type' => 'milestone'],
            ['name' => 'Loyal Subscriber', 'description' => 'Resubscribe to the platform', 'points' => 60, 'icon' => '💎', 'criteria' => 'resubscription:1', 'category' => 'special', 'type' => 'milestone'],

            // ENROLLMENT MILESTONES (36-38)
            ['name' => 'Enrollment Starter', 'description' => 'Enroll in 10 courses', 'points' => 40, 'icon' => '📖', 'criteria' => 'enrollment:10', 'category' => 'learning', 'type' => 'course_enrollment'],
            ['name' => 'Enrollment Collector', 'description' => 'Enroll in 50 courses', 'points' => 100, 'icon' => '📚', 'criteria' => 'enrollment:50', 'category' => 'learning', 'type' => 'course_enrollment'],
            ['name' => 'Enrollment Master', 'description' => 'Enroll in 100 courses', 'points' => 200, 'icon' => '🎓', 'criteria' => 'enrollment:100', 'category' => 'learning', 'type' => 'course_enrollment'],

            // SPECIAL BADGES (39-40)
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

