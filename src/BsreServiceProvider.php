<?php

namespace Boyprakasa\BsreESignLaravel;

use Illuminate\Support\ServiceProvider;

class BsreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Menggabungkan config dari paket agar bisa diakses via config('bsre.key')
        $this->mergeConfigFrom(__DIR__ . '/../config/bsre.php', 'bsre');

        $this->app->singleton(BsreService::class, function ($app) {
            return new BsreService($app['config']['bsre']);
        });
    }

    public function boot(): void
    {
        // Memuat route dari paket
        $this->loadRoutesFrom(__DIR__ . '/../routes/bsre.php');

        // Memuat migration dari paket
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Mengizinkan user mempublikasikan file config dan migration
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/bsre.php' => config_path('bsre.php'),
            ], 'bsre-config');

            // $this->publishes([
            //     __DIR__ . '/../database/migrations/' => database_path('migrations'),
            // ], 'bsre-migrations');

            $this->publishes([
                __DIR__ . '/../routes/bsre.php' => base_path('routes/bsre.php'),
            ], 'bsre-routes');
        }
    }
}
