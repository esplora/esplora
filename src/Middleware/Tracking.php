<?php

declare(strict_types=1);

namespace Esplora\Tracker\Middleware;

use Closure;
use Esplora\Tracker\Esplora;
use Esplora\Tracker\Models\Visitor;
use Esplora\Tracker\Models\VisitorUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $visitor = Visitor::firstOrNew([
            'id' => $this->esplora->loadVisitId()
        ])->fill([
            'ip'                 => $request->ip(),
            'user_agent'         => $request->userAgent(),
            'preferred_language' => $request->getPreferredLanguage(),
            'created_at'         => now(),
        ]);
        $url = new VisitorUrl([
            'id'                 => Str::orderedUuid(),
            'visitor_id'         => $visitor->id,
            'url'                => $request->fullUrl(),
            'referer'            => $request->headers->get('referer')
        ]);

        $this->esplora->saveAfterResponse($visitor);
        $this->esplora->saveAfterResponse($url);
    }
}
