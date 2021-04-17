<?php

declare(strict_types=1);

namespace Mircurius\Analytics;

use Illuminate\Support\ServiceProvider;
use Mircurius\Analytics\Commands\Clear;
use Mircurius\Analytics\Commands\Subscribe;

class MircuriusServiceProvider extends ServiceProvider
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
            __DIR__ . '/../config/mircurius.php' => config_path('mircurius.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Subscribe::class,
                Clear::class,
            ]);
        }
    }
}
