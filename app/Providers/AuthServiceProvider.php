<?php

namespace App\Providers;

use App\Models\Review;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Video;
use App\Models\Test;
use App\Policies\ReviewPolicy;
use App\Policies\CoursePolicy;
use App\Policies\UserPolicy;
use App\Policies\EnrollmentPolicy;
use App\Policies\VideoPolicy;
use App\Policies\TestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Review::class => ReviewPolicy::class,
        Course::class => CoursePolicy::class,
        User::class => UserPolicy::class,
        Enrollment::class => EnrollmentPolicy::class,
        Video::class => VideoPolicy::class,
        Test::class => TestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
