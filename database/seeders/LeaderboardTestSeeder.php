<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Badge;
use Illuminate\Database\Seeder;

class LeaderboardTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get test users
        $users = User::whereIn('id', [2, 3, 4, 5, 6])->get();
        
        // Get badges
        $badges = Badge::limit(5)->get();
        
        if ($badges->isEmpty()) {
            echo "No badges found. Creating test badges...\n";
            // Create test badges if none exist
            $badgeNames = ['Quick Learner', 'Quiz Master', 'Course Completer', 'Active Participant', 'Top Performer'];
            foreach ($badgeNames as $name) {
                Badge::create([
                    'name' => $name,
                    'icon' => 'badge-icon.png',
                    'criteria' => json_encode(['type' => 'test'])
                ]);
            }
            $badges = Badge::limit(5)->get();
        }
        
        // Assign badges to users with different counts
        $badgeCounts = [5, 4, 3, 2, 1];
        
        foreach ($users as $index => $user) {
            $count = $badgeCounts[$index] ?? 1;
            $selectedBadges = $badges->take($count);
            
            foreach ($selectedBadges as $badge) {
                // Check if already attached
                if (!$user->badges()->where('badge_id', $badge->id)->exists()) {
                    $user->badges()->attach($badge->id, [
                        'earned_at' => now()->subDays(rand(1, 30))
                    ]);
                }
            }
        }
        
        echo "Leaderboard test data created successfully!\n";
    }
}

