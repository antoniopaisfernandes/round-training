<?php

namespace App\Providers;

use App\Enrollment;
use App\Policies\EnrollmentPolicy;
use App\Policies\ProgramEditionPolicy;
use App\Policies\ProgramPolicy;
use App\Program;
use App\ProgramEdition;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Program::class => ProgramPolicy::class,
        ProgramEdition::class => ProgramEditionPolicy::class,
        Enrollment::class => EnrollmentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(fn ($user, $ability) => $user->hasRole('admin') ? true : null);
    }
}
