<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //gate de administrador
        Gate::define('is-administrator', function ($user) {
            return $user->isAdmin();
        });

        //gate de gerente
        Gate::define('is-manager', function ($user) {
            return $user->isManager();
        });

        //gate de usuário sem perfil
        Gate::define('no-profile', function ($user) {
            return $user->noProfile();
        });

    }
}
