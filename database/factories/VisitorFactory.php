<?php

namespace Esplora\Tracker\Database\Factories;

use Esplora\Tracker\Models\Visitor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VisitorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Visitor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'ip' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent,
            'created_at' => now()
        ];
    }
}
