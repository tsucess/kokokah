<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'First Course Completed',
                'icon' => '🎓',
                'criteria' => 'Complete your first course'
            ],
            [
                'name' => 'Quick Learner',
                'icon' => '⚡',
                'criteria' => 'Complete a course in less than 7 days'
            ],
            [
                'name' => 'Dedicated Student',
                'icon' => '📚',
                'criteria' => 'Complete 5 courses'
            ],
            [
                'name' => 'Scholar',
                'icon' => '🏆',
                'criteria' => 'Complete 10 courses'
            ],
            [
                'name' => 'Master Student',
                'icon' => '👑',
                'criteria' => 'Complete 25 courses'
            ],
            [
                'name' => 'Perfect Score',
                'icon' => '💯',
                'criteria' => 'Get 100% on a quiz'
            ],
            [
                'name' => 'Quiz Master',
                'icon' => '🧠',
                'criteria' => 'Complete 50 quizzes'
            ],
            [
                'name' => 'Early Bird',
                'icon' => '🌅',
                'criteria' => 'Complete lessons before 8 AM'
            ],
            [
                'name' => 'Night Owl',
                'icon' => '🦉',
                'criteria' => 'Complete lessons after 10 PM'
            ],
            [
                'name' => 'Consistent Learner',
                'icon' => '📅',
                'criteria' => 'Study for 7 consecutive days'
            ],
            [
                'name' => 'Social Learner',
                'icon' => '💬',
                'criteria' => 'Participate in 10 forum discussions'
            ],
            [
                'name' => 'Helpful Student',
                'icon' => '🤝',
                'criteria' => 'Help other students in forums'
            ],
            [
                'name' => 'Assignment Ace',
                'icon' => '📝',
                'criteria' => 'Submit 10 assignments on time'
            ],
            [
                'name' => 'Video Watcher',
                'icon' => '📺',
                'criteria' => 'Watch 100 lesson videos'
            ],
            [
                'name' => 'Course Creator',
                'icon' => '🎬',
                'criteria' => 'Create your first course (for instructors)'
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['name' => $badge['name']],
                $badge
            );
        }
    }
}
