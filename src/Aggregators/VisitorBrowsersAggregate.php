<?php

declare(strict_types=1);

namespace Esplora\Tracker\Aggregators;

use Esplora\Tracker\Aggregator;
use Esplora\Tracker\Models\Visitor;
use Esplora\Tracker\Presenters\VisitorBrowsersPresenter;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class VisitorBrowsersAggregate extends Aggregator
{
    protected function query(): Builder
    {
        return Visitor::groupBy('browser')->select('browser', DB::raw('count(*) as count'));
    }

    public function presenter(): Jsonable
    {
        return new VisitorBrowsersPresenter($this->getResult());
    }
}
