<?php

declare(strict_types=1);

namespace Esplora\Analytics;

use Esplora\Analytics\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Ramsey\Uuid\Rfc4122\UuidV4;

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
     * @var Request
     */
    protected Request $request;

    /**
     * Visitor constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return UuidV4
     */
    public function loadVisitId(): UuidV4
    {
        $id = Str::orderedUuid();

        return $this->request->session()->remember(Esplora::ID_SESSION, fn () => $id);
    }

    /**
     * @return Connection
     */
    public function redis(): Connection
    {
        return Redis::connection(config('esplora.redis'));
    }

    /**
     * @param string $name
     */
    public function goal(string $name): void
    {
        dispatch(fn () => Goal::create([
            'id'         => Str::orderedUuid(),
            'visitor_id' => $this->loadVisitId(),
            'name'       => $name,
            'created_at' => now(),
        ]))
            ->afterResponse();
    }
}
