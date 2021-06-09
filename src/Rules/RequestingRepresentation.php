<?php

declare(strict_types=1);

namespace Esplora\Analytics\Rules;

use Esplora\Analytics\Contracts\Rule;
use Illuminate\Http\Request;

class RequestingRepresentation implements Rule
{
    /**
     * @param Request $request
     *
     * @return bool
     */
    public function passes(Request $request): bool
    {
        return $request->isMethod(Request::METHOD_GET);
    }
}
