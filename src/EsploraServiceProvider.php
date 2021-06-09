<?php

declare(strict_types=1);

namespace Esplora\Analytics;

use Esplora\Analytics\Commands\Clear;
use Esplora\Analytics\Commands\Subscribe;
use Illuminate\Support\ServiceProvider;

class EsploraServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/Esplora.php' => config_path('esplora.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../config/Esplora.php', 'esplora');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Subscribe::class,
                Clear::class,
            ]);
        }
    }
}
