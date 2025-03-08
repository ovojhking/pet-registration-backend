<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DangerousBreedService;
use App\Services\DangerousBreedServiceForMix;

class DangerousBreedServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DangerousBreedService::class, function ($app) {
            return new DangerousBreedService();
        });

        $this->app->singleton(DangerousBreedServiceForMix::class, function ($app) {
            return new DangerousBreedServiceForMix();
        });
    }

    public function boot()
    {
    }
}
