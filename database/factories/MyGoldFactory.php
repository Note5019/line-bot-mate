<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\myGold>
 */
class MyGoldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $buy = fake()->numberBetween(20000, 40000);
        return [
            'code' => fake()->word(),
            'buy_price' => $buy,
            'value' => fake()->numberBetween(1000, 10000),
            'weight' => fake()->randomFloat(4, 1, 10),
            'target_sell_price' => $buy + 1000,
            'target_baht_profit' => fake()->numberBetween(10, 1000),
            'sold' => false,
        ];
    }
}
