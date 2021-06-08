<?php

declare(strict_types=1);

namespace Esplora\Analytics;

use Illuminate\Support\Facades\Redis;
use Illuminate\Redis\RedisManager;

class Esplora
{
    /**
     *
     */
    public const VISITS_CHANNEL = 'visits-channel';

    /**
     *
     */
    public const ID_SESSION = 'esplora.id';

    /**
     * @return RedisManager
     */
    public static function redis(): RedisManager
    {
        return Redis::connection(config('esplora.use'));
    }
}
