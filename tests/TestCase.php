<?php

declare(strict_types=1);

namespace Esplora\Tracker\Tests;

use Esplora\Tracker\EsploraServiceProvider;
use Esplora\Tracker\Facades\Tracker;
use Esplora\Tracker\Middleware\Tracking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        /* Install application */
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(realpath('./database/migrations'));

        Route::get('visit/{string?}', fn () => '')
            ->middleware(['web', Tracking::class])
            ->name('visit');

        Route::get('goal', fn () => Tracker::goal('changeTheme', [
            'color' => 'dark',
        ]))
            ->middleware(['web', Tracking::class])
            ->name('goal');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $config = config();

        // set up database configuration
        $config->set('database.connections.orchid', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $config->set('scout.driver', 'array');
        $config->set('database.default', 'orchid');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            EsploraServiceProvider::class,
        ];
    }

    /**
     * Override application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Redis' => \Illuminate\Support\Facades\Redis::class,
        ];
    }
}
