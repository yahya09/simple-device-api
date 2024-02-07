<?php

namespace App\Providers;

use App\Models\Device;
use App\Services\DeviceRepositoryService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            DeviceRepositoryService::class,
            function (Application $app) {
                return new DeviceRepositoryService($app->make(Device::class));
            });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
