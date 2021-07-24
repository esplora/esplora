<?php

declare(strict_types=1);

namespace Esplora\Tracker;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class Presenter implements Jsonable
{
    private $collection;

    /**
     * Presenter constructor.
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param Model $model
     *
     * @return array
     */
    abstract public function present(Model $model): array;

    /**
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0): string
    {
        return $this->collection->map([$this, 'present'])->toJson();
    }
}
