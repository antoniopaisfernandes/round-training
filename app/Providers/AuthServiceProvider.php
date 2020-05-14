<?php

namespace App\Providers;

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
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
