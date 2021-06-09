<?php

declare(strict_types=1);

namespace Esplora\Analytics;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;

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
