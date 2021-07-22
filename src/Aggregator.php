<?php

declare(strict_types=1);

namespace Esplora\Tracker;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Aggregator
 * @package Esplora\Tracker
 */
abstract class Aggregator
{
    /**
     * Return query builder which has prepared query request
     * @return Builder
     */
    abstract protected function query(): Builder;

    public function getCount(): int
    {
        if (is_null($lastAggregation = $this->query()->get()->last())) {
            return $this->query()->count();
        } else {
            return $this->query()->where('created_at', '>', $lastAggregation->created_at)->count();
        }
    }
}
