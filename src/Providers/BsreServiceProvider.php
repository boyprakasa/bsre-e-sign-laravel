<?php

namespace Boyprakasa\BsreESignLaravel\Providers;

use Boyprakasa\BsreESignLaravel\BsreService;
use Illuminate\Support\ServiceProvider;

class BsreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Menggabungkan config dari paket agar bisa diakses via config('bsre.key')
        $this->mergeConfigFrom(__DIR__ . '/../../config/bsre.php', 'bsre');

        $this->app->singleton(BsreService::class, function ($app) {
            return new BsreService($app['config']['bsre']);
        });
    }

    public function boot(): void
    {
        // Mengizinkan user mempublikasikan file config dan migration
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/bsre.php' => config_path('bsre.php'),
            ], 'bsre-config');
        }
    }
}
