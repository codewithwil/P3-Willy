<?php

namespace App\Providers;

use App\{
    Repositories\Contracts\Auth\AuthRepositoryContract,
    Repositories\Eloquent\Auth\AuthRepository,

};

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryContract::class, AuthRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
