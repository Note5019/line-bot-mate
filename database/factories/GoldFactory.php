<?php

namespace Database\Factories;

use App\Models\Gold;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gold>
 */
class GoldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $change = fake()->numberBetween(0, 100);
        $sell = fake()->numberBetween(20000, 40000); 
        $buy = $sell - 100;
        return [
            'gold_type' => Gold::GOLD_TYPE,
            'gold_code' => Gold::GOLD_CODE,
            'buy' => $buy,
            'sell' => $sell,
            'buy_change' => $change,
            'sell_change' => $change,
            'time_update' => now(),
        ];
    }
}
