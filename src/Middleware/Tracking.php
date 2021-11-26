<?php

declare(strict_types=1);

namespace Esplora\Tracker\Middleware;

use Closure;
use Esplora\Tracker\Esplora;
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
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Illuminate\Http\Response $response
     *
     * @return void
     */
    public function terminate($request, $response)
    {
        $this->esplora->visit($request, $response);
    }
}
