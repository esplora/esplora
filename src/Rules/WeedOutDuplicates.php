<?php

declare(strict_types=1);

namespace Esplora\Tracker\Rules;

use Esplora\Tracker\Contracts\Rule;
use Illuminate\Http\Request;

class WeedOutDuplicates implements Rule
{
    /**
     * @param Request $request
     *
     * @return bool
     */
    public function passes(Request $request): bool
    {
        return $request->session()->previousUrl() !== $request->url();
    }
}
