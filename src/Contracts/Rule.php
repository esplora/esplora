<?php

declare(strict_types=1);

namespace Esplora\Tracker\Contracts;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  Request   $request
     * @param  Response  $response
     *
     * @return bool
     */
    public function passes($request, $response): bool;
}
