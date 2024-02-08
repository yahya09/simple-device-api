<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Device;
use App\Models\Preorder;
use App\Services\DeviceRepositoryService;
use App\Services\PreorderRepositoryService;
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
        $this->app->bind(
            PreorderRepositoryService::class,
            function (Application $app) {
                return new PreorderRepositoryService(
                    $app->make(Device::class),
                    $app->make(Preorder::class),
                    $app->make(Customer::class)
                );
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
