<?php

declare(strict_types=1);

namespace Esplora\Tracker;

use Esplora\Tracker\Models\EsploraAggregator;
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
     * @return int
     */
    public function getCount(): int
    {
        if (is_null($lastAggregation = EsploraAggregator::where('key', static::key())->get()->last())) {
            return $this->query()->count();
        } else {
            return $this->query()->where('created_at', '>', $lastAggregation->created_at)->count();
        }
    }

    /**
     * Get key as unique key for define in database
     * @return string
     */
    public static function key(): string
    {
        return static::class;
    }
}
