<?php

declare(strict_types=1);

namespace Esplora\Tracker\Rules;

use Esplora\Tracker\Contracts\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExceptPath implements Rule
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return bool
     */
    public function passes($request, $response): bool
    {
        $except = config('esplora.except', []);

        if (empty($except)) {
            return true;
        }

        return ! $request->is($except);
    }
}
