<?php

declare(strict_types=1);

namespace Mircurius\Analytics\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Mircurius\Analytics\Models\Visit;

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
        $this->boot($request);

        return $next($request);
    }

    /**
     * @param Store $session
     *
     * @return string
     */
    protected function loadVisitId(Store $session): string
    {
        if ($session->has('mircurius.id')) {
            return $session->get('mircurius.id');
        }

        return tap(Str::uuid(), fn($id) => $session->put('mircurius.id', $id));
    }

    /**
     *
     */
    protected function boot(Request $request): void
    {
        if (!$request->isMethod(Request::METHOD_GET)) {
            return;
        }

        $visitor = new Visit([
            'id'         => $this->loadVisitId($request->session()),
            'user_id'    => $request->user(),
            'ip'         => $request->ip(),
            'referer'    => $request->headers->get('referer'),
            'user_agent' => $request->userAgent(),
            'url'        => $request->fullUrl(),
            'created_at' => now(),
        ]);

        dispatch(fn() => Redis::publish('visits-channel', $visitor->toJson()))
            ->afterResponse();
    }
}
