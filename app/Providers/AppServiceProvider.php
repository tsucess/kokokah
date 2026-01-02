<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Course;
use App\Models\Enrollment;
use App\Observers\CourseObserver;
use App\Observers\EnrollmentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        Course::observe(CourseObserver::class);
        Enrollment::observe(EnrollmentObserver::class);
    }
}
