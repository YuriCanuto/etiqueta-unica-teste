<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid'   => $this->faker->uuid(),
            'name'   => $this->faker->company(),
            'amount' => $this->faker->randomFloat(2, 0, 100),
            'active' => true
        ];
    }
}
