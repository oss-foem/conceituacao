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

        /**
         * Define a permissão 'is-administrator'.
         * Retorna verdadeiro se o usuário for administrador.
         */
        Gate::define('is-administrator', function ($user) {
            return $user->isAdmin();
        });

        /**
         * Define a permissão 'is-manager'.
         * Retorna verdadeiro se o usuário for gerente.
         */
        Gate::define('is-manager', function ($user) {
            return $user->isManager();
        });

        /**
         * Define a permissão 'no-profile'.
         * Retorna verdadeiro se o usuário não tiver um perfil associado.
         */
        Gate::define('no-profile', function ($user) {
            return $user->noProfile();
        });

    }
}
