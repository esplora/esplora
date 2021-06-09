<?php

declare(strict_types=1);

namespace Esplora\Analytics\Contracts;

use Illuminate\Http\Request;

interface Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function passes(Request $request): bool;
}
