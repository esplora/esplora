<?php

declare(strict_types=1);

namespace Esplora\Tracker;

use Esplora\Tracker\Models\EsploraAggregator;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Aggregator
 *
 * @package Esplora\Tracker
 */
abstract class Aggregator
{
    /**
     * Return query builder which has prepared query request
     *
     * @return Builder
     */
    abstract protected function query(): Builder;

    /**
     * @return Jsonable
     */
    public function presenter(): Jsonable
    {
        return $this->getResult();
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->filterDate()->count();
    }

    public function getResult()
    {
        return $this->filterDate()->get();
    }

    /**
     * Get key as unique key for define in database
     *
     * @return string
     */
    public static function key(): string
    {
        return static::class;
    }

    /**
     * @return Builder
     */
    private function filterDate(): Builder
    {
        $lastAggregation = EsploraAggregator::where('key', static::key())->get()->last();
        if (is_null($lastAggregation) === false) {
            return $this->query()->where('created_at', '>', $lastAggregation->created_at);
        }

        return $this->query();
    }
}
