<?php

namespace Esplora\Tracker\Database\Factories;

use Esplora\Tracker\Models\EsploraAggregator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EsploraAggregatorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EsploraAggregator::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'data' => '',
            'key' => $this->faker->randomKey,
            'created_at' => now()
        ];
    }
}
