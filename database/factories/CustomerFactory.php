<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_number' => $this->faker->unique()->numerify('################'),
            'name' => $this->faker->name(),
            'phone_number' => $this->faker->unique()->numerify('08##########'),
        ];
    }
}
