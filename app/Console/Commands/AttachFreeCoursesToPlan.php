<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\SubscriptionPlan;
use Illuminate\Console\Command;

class AttachFreeCoursesToPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courses:attach-free-to-plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach all free courses to the free subscription plan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find the free subscription plan
        $freePlan = SubscriptionPlan::where('duration_type', 'free')
                                    ->where('is_active', true)
                                    ->first();

        if (!$freePlan) {
            $this->error('Free subscription plan not found!');
            return 1;
        }

        $this->info('Found free plan: ' . $freePlan->title . ' (ID: ' . $freePlan->id . ')');

        // Find all courses marked as free
        $freeCourses = Course::where('free_subscription', true)->get();

        if ($freeCourses->isEmpty()) {
            $this->warn('No free courses found!');
            return 0;
        }

        $this->info('Found ' . $freeCourses->count() . ' free courses');

        // Attach each course to the free plan
        foreach ($freeCourses as $course) {
            // Check if already attached
            if (!$freePlan->courses()->where('course_id', $course->id)->exists()) {
                $freePlan->courses()->attach($course->id);
                $this->line('✓ Attached: ' . $course->title . ' (ID: ' . $course->id . ')');
            } else {
                $this->line('→ Already attached: ' . $course->title . ' (ID: ' . $course->id . ')');
            }
        }

        $this->info('Total courses attached to free plan: ' . $freePlan->courses()->count());
        $this->info('Done!');

        return 0;
    }
}

