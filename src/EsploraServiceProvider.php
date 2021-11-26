<?php

declare(strict_types=1);

namespace Esplora\Tracker;

use Esplora\Tracker\Commands\EventsImport;
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
            __DIR__ . '/../config/esplora.php' => config_path('esplora.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../config/esplora.php', 'esplora');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole() && config('app.env') !== 'testing') {
            $this->commands([
                EventsImport::class,
            ]);
        }
    }
}
