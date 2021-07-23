<?php

declare(strict_types=1);

namespace Esplora\Tracker\Tests;

use Esplora\Tracker\Facades\Tracker;
use Esplora\Tracker\Middleware\Tracking;
use Illuminate\Database\Eloquent\Factories\Factory;
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


        Route::get('visit/{string?}', function () {
            return '';
        })
            ->middleware(['web', Tracking::class])
            ->name('visit');

        Route::get('goal', function () {
            Tracker::goal('changeTheme', [
                'color' => 'dark',
            ]);

            return '';
        })
            ->middleware(['web', Tracking::class])
            ->name('goal');

        Factory::guessFactoryNamesUsing(function ($factory) {
            $factoryBasename = class_basename($factory);

            return "Esplora\Tracker\Database\Factories\\$factoryBasename".'Factory';
        });
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
}
