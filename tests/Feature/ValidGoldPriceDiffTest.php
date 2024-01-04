<?php

namespace Tests\Feature;

use App\Actions\ValidGoldPriceDiff;
use App\Models\Gold;
use App\Models\MyGold;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidGoldPriceDiffTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_gold_price_false(): void
    {
        Gold::factory()->create([
            'buy' => '13000',
        ]);
        Gold::factory()->create([
            'buy' => '12000',
        ]);
        $currentGold = Gold::factory()->create([
            'buy' => '12000',
        ]);
        $this->assertFalse(ValidGoldPriceDiff::execute($currentGold));
    }

    public function test_valid_gold_price_true(): void
    {
        Gold::factory()->create([
            'buy' => '12000',
        ]);
        Gold::factory()->create([
            'buy' => '10000',
        ]);
        $currentGold = Gold::factory()->create([
            'buy' => '12000',
        ]);
        $this->assertTrue(ValidGoldPriceDiff::execute($currentGold));
    }
}
