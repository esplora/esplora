<?php

declare(strict_types=1);

namespace Esplora\Tracker\Rules;

use Esplora\Tracker\Contracts\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WeedOutDuplicates implements Rule
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return bool
     */
    public function passes(Request $request, Response $response): bool
    {
        return $request->session()->previousUrl() !== $request->url();
    }
}
