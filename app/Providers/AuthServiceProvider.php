<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // $this->registerPolicies();

        // // Passport::routes(); // ✅ Un-comment this line!

        // Passport::tokensCan([
        //     'nutrition-specialist' => 'Access assessments',
        //     'admin' => 'Access statistics and data',
        //     'accountant' => 'Access financial records',
        //     'default-user' => 'Standard authenticated user access',
        // ]);
        //
    }
}
