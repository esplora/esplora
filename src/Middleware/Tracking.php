<?php

declare(strict_types=1);

namespace Esplora\Analytics\Middleware;

use Closure;
use Esplora\Analytics\Esplora;
use Esplora\Analytics\Models\Visit;
use Illuminate\Http\Request;

class Tracking
{
    /**
     * @var Esplora
     */
    protected Esplora $esplora;

    /**
     * Tracking constructor.
     *
     * @param Esplora $esplora
     */
    public function __construct(Esplora $esplora)
    {
        $this->esplora = $esplora;
    }

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
        $this->boot($request);

        return $next($request);
    }

    /**
     * @param Request $request
     */
    protected function boot(Request $request): void
    {
        if (! $this->esplora->isNeedVisitWrite($request)) {
            return;
        }

        $this->esplora
            ->fillingVisit(new Visit([
                'id'                 => $this->esplora->loadVisitId(),
                'ip'                 => $request->ip(),
                'referer'            => $request->headers->get('referer'),
                'user_agent'         => $request->userAgent(),
                'url'                => $request->fullUrl(),
                'preferred_language' => $request->getPreferredLanguage(),
                'created_at'         => now(),
            ]));
    }
}
