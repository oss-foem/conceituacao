<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domains\User\Contracts\UserRepositoryInterface;
use App\Infrastructure\Persistence\User\EloquentUserRepository;
use App\Domains\Profile\Contracts\ProfileRepositoryInterface;
use App\Infrastructure\Persistence\Profile\EloquentProfileRepository;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(ProfileRepositoryInterface::class, EloquentProfileRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
