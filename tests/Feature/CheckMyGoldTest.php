<?php

namespace Tests\Feature;

use App\Actions\CheckMyGold;
use App\Models\Gold;
use App\Models\MyGold;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckMyGoldTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_my_gold_not_reach_target_sell_price(): void
    {
        $currentGold = Gold::factory()->create([
            'buy' => '10000',
        ]);
        MyGold::factory()->create([
            'buy_price' => 11000,
            'target_sell_price' => 13000,
            'target_baht_profit' => 99999,
        ]);
        $data = CheckMyGold::execute($currentGold);
        $this->assertCount(0, $data);
    }

    public function test_check_my_gold_not_reach_target_baht_profit(): void
    {
        $currentGold = Gold::factory()->create([
            'buy' => '10000',
        ]);
        MyGold::factory()->create([
            'buy_price' => 13000,
            'target_sell_price' => 99999,
            'target_baht_profit' => 1000,
        ]);
        $data = CheckMyGold::execute($currentGold);
        $this->assertCount(0, $data);
    }

    public function test_check_my_gold_reach_target_sell_price(): void
    {
        $currentGold = Gold::factory()->create([
            'buy' => '13000',
        ]);
        MyGold::factory()->create([
            'buy_price' => 11000,
            'target_sell_price' => 13000,
            'target_baht_profit' => 99999,
        ]);
        $data = CheckMyGold::execute($currentGold);
        $this->assertCount(1, $data);
    }

    public function test_check_my_gold_reach_target_baht_profit(): void
    {
        $currentGold = Gold::factory()->create([
            'buy' => '14000',
        ]);
        MyGold::factory()->create([
            'buy_price' => 13000,
            'target_sell_price' => 99999,
            'target_baht_profit' => 1000,
        ]);
        $data = CheckMyGold::execute($currentGold);
        $this->assertCount(1, $data);
    }

    public function test_check_my_gold_reach_both_target(): void
    {
        $currentGold = Gold::factory()->create([
            'buy' => '12000',
        ]);
        MyGold::factory()->create([
            'buy_price' => 5000,
            'target_sell_price' => 8000,
            'target_baht_profit' => 99999,
        ]);
        MyGold::factory()->create([
            'buy_price' => 11000,
            'target_sell_price' => 99999,
            'target_baht_profit' => 1000,
        ]);
        $data = CheckMyGold::execute($currentGold);
        $this->assertCount(2, $data);
    }
}
