<?php

namespace App\Providers;

use App\Interfaces\AuthInterface;
use App\Interfaces\ParcelInterface;
use App\Repository\AuthRepository;
use App\Repository\ParcelRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthInterface::class, AuthRepository::class);
        $this->app->bind(ParcelInterface::class, ParcelRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
