<?php

namespace Esplora\Tracker\Rules;

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
