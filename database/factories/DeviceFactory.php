<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $models = ['Samsung Galaxy S', 'Samsung A', 'Samsung M', 'Galaxy Note ', 'Galaxy Tab S'];
        return [
            'name' => $this->faker->randomElement($models) . sprintf('%02d',$this->faker->numberBetween(1, 24)),
            'color' => $this->faker->colorName(),
            'price' => $this->faker->numberBetween(1000, 10000) * 1000,
            'stock' => $this->faker->numberBetween(1, 100),
        ];
    }
}
