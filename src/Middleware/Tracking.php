<?php

declare(strict_types=1);

namespace Esplora\Analytics\Middleware;

use Closure;
use Esplora\Analytics\Contracts\Rule;
use Esplora\Analytics\Esplora;
use Esplora\Analytics\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Ramsey\Uuid\Rfc4122\UuidV4;

class Tracking
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $needWrite = collect(config('esplora.rules'))
            ->map(fn (string $class)   => app()->make($class))
            ->map(fn (Rule $rule)      => $rule->passes($request))
            ->filter(fn (bool $result) => $result === false)
            ->isEmpty();

        if ($needWrite) {
            $this->boot($request);
        }

        return $next($request);
    }

    /**
     * @param Store $session
     *
     * @return UuidV4
     */
    protected function loadVisitId(Store $session): UuidV4
    {
        $id = Str::orderedUuid();

        return $session->remember(Esplora::ID_SESSION, fn () => $id);
    }

    /**
     * @param Request $request
     */
    protected function boot(Request $request): void
    {
        $transfer = collect([
            'id'                 => $this->loadVisitId($request->session()),
            'ip'                 => $request->ip(),
            'referer'            => $request->headers->get('referer'),
            'user_agent'         => $request->userAgent(),
            'url'                => $request->fullUrl(),
            'preferred_language' => $request->getPreferredLanguage(),
            'created_at'         => now(),
        ]);

        if (config('esplora.filling', 'sync') === 'sync') {
            $this->fillingSync($transfer);

            return;
        }

        $this->fillingRedis($transfer);
    }

    /**
     * @param Collection $transfer
     *
     * @return mixed
     */
    protected function fillingSync(Collection $transfer): void
    {
        Visit::create($transfer->toArray());
    }

    /**
     * @param Collection $transfer
     */
    public function fillingRedis(Collection $transfer): void
    {
        dispatch(fn () => Esplora::redis()->publish(Esplora::VISITS_CHANNEL, $transfer->toJson()))
            ->afterResponse();
    }
}
