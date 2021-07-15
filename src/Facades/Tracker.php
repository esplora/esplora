<?php

declare(strict_types=1);

namespace Esplora\Tracker\Facades;

use Esplora\Tracker\Esplora;
use Illuminate\Support\Facades\Facade;

/**
 * Class Tracker
 *
 * @method static void goal(string $name, array $parameters = [])
 */
class Tracker extends Facade
{
    /**
     * Initiate a mock expectation on the facade.
     *
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return Esplora::class;
    }
}
