<?php

declare(strict_types=1);

namespace Esplora\Tracker\Aggregators;

use Esplora\Tracker\Aggregator;
use Esplora\Tracker\Models\Visitor;
use Illuminate\Database\Eloquent\Builder;

class CountVisitorsAggregate extends Aggregator
{
    protected function query(): Builder
    {
        return Visitor::query();
    }
}
