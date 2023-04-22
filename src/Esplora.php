<?php

declare(strict_types=1);

namespace Esplora\Tracker;

use Esplora\Tracker\Contracts\Rule;
use Esplora\Tracker\Models\Goal;
use Esplora\Tracker\Models\Visit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use Ramsey\Uuid\Rfc4122\UuidV4;

class Esplora
{
    use Conditionable;

    public const REDIS_PREFIX = 'esplore-';
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
     * @param Request  $request
     * @param Response $response
     *
     * @return bool
     */
    public function isNeedVisitWrite($request, $response): bool
    {
        return collect(config('esplora.rules'))
            ->map(fn (string $class) => app()->make($class))
            ->map(fn (Rule $rule) => $rule->passes($request, $response))
            ->filter(fn (bool $result) => $result === false)
            ->isEmpty();
    }

    /**
     * @return UuidV4
     */
    public function loadVisitId(): UuidV4
    {
        return $this->request
            ->session()
            ->remember(Esplora::ID_SESSION, fn () => Str::orderedUuid());
    }

    /**
     * @return Connection
     */
    public function redis(): Connection
    {
        return Redis::connection(config('esplora.redis'));
    }

    /**
     * @param Request  $request
     * @param Response $response
     */
    public function visit($request, $response)
    {
        if (! $this->isNeedVisitWrite($request, $response)) {
            return;
        }

        $this->save(new Visit([
            'id'                 => $this->loadVisitId(),
            'ip'                 => $request->ip(),
            'route'              => $request->route()?->getName(),
            'referer'            => $request->headers->get('referer'),
            'user_agent'         => $request->userAgent(),
            'url'                => $request->fullUrl(),
            'preferred_language' => $request->getPreferredLanguage(),
            'response_code'      => $response->getStatusCode(),
            'response_time'      => defined('LARAVEL_START') ? round(microtime(true) - LARAVEL_START) * 1000 : null,  // ms
            'created_at'         => now(),
        ]));
    }

    /**
     * @param string $name
     * @param array  $parameters
     */
    public function goal(string $name, array $parameters = []): void
    {
        dispatch(Goal::create([
            'id'         => Str::orderedUuid(),
            'visitor_id' => $this->loadVisitId(),
            'name'       => $name,
            'parameters' => $parameters,
            'created_at' => now(),
        ]))->afterResponse();
    }

    /**
     * @param Model $model
     */
    public function save(Model $model): void
    {
        if (config('esplora.filling', 'sync') === 'sync') {
            $model->save();

            return;
        }

        $key = Str::of(get_class($model))->classBasename()
            ->start(Esplora::REDIS_PREFIX)
            ->finish('_')
            ->finish(Str::uuid())
            ->slug();

        $this->redis()->set($key, $model->toJson());
    }

    /**
     * @param Model $model
     */
    public function saveAfterResponse(Model $model): void
    {
        dispatch(fn () => $model->save())->afterResponse();
    }

    /**
     * @param string $model
     *
     * @return int
     */
    public function importModelsForRedis(string $model): int
    {
        $redis = $this->redis();

        $patternForSearch = Str::of($model)
            ->classBasename()
            ->start(Esplora::REDIS_PREFIX)
            ->slug()
            ->finish('*');

        // get all keys
        $keys = collect($redis->keys($patternForSearch))
            ->map(fn ($key) => Str::of($key)->after(Esplora::REDIS_PREFIX)->start(Esplora::REDIS_PREFIX))
            ->toArray();

        if (count($keys) === 0) {
            return 0;
        }

        // get all values
        $values = collect()
            ->merge($redis->mGet($keys))
            ->map(fn (string $value) => json_decode($value, true, 512, JSON_THROW_ON_ERROR))
            ->map(fn (array $value) => collect($value)->map(fn ($attr) => is_array($attr) ? json_encode($attr,
                JSON_THROW_ON_ERROR) : $attr))
            ->toArray();

        // save mass records
        (new $model)->insert($values);

        // remove all values
        $redis->del($keys);

        return count($values);
    }
}
