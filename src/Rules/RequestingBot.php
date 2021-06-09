<?php

declare(strict_types=1);

namespace Esplora\Analytics\Rules;

use Esplora\Analytics\Contracts\Rule;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class RequestingBot implements Rule
{
    /**
     * @param Request $request
     *
     * @return bool
     */
    public function passes(Request $request): bool
    {
        $agent = new Agent();

        return ! $agent->isRobot();
    }
}
