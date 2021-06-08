<?php

declare(strict_types=1);

namespace Esplora\Analytics\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Str;
use Esplora\Analytics\Esplora;
use Esplora\Analytics\Models\Visit;

class Visition
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
        if ($request->isMethod(Request::METHOD_GET)) {
            $this->boot($request);
        }

        return $next($request);
    }

    /**
     * @param Store $session
     *
     * @return string
     */
    protected function loadVisitId(Store $session): string
    {
        if ($session->has('Esplora.id')) {
            return $session->get(Esplora::ID_SESSION);
        }

        return tap(Str::uuid(), fn(string $id) => $session->put(Esplora::ID_SESSION, $id));
    }

    /**
     *
     */
    protected function boot(Request $request): void
    {
        $visitor = new Visit([
            'id'         => $this->loadVisitId($request->session()),
            'user_id'    => $request->user(),
            'ip'         => $request->ip(),
            'referer'    => $request->headers->get('referer'),
            'user_agent' => $request->userAgent(),
            'url'        => $request->fullUrl(),
            'created_at' => now(),
        ]);

        dispatch(fn() => Esplora::redis()->publish(Esplora::VISITS_CHANNEL, $visitor->toJson()))
            ->afterResponse();
    }
}
