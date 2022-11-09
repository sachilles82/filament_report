<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->realText(10),
            'description' => $this->faker->realText(50),
            'vk_price' => rand(100, 500),
            'ek_price' => rand(1, 100),
            'unit_id' => rand(1, 5),
        ];
    }
}
