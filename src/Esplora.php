<?php

declare(strict_types=1);

namespace Esplora\Analytics;

use Illuminate\Support\Facades\Redis;
use Illuminate\Redis\Connections\Connection;

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
     * @return Connection
     */
    public static function redis(): Connection
    {
        return Redis::connection(config('esplora.redis'));
    }
}
