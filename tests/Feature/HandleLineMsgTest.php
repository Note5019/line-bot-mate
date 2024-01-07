<?php

namespace Tests\Feature;

use App\Actions\HandleLineMsg;
use App\Enums\ResponseCode;
use App\Models\Gold;
use App\Models\MyGold;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HandleLineMsgTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_line_msg_buy_gold(): void
    {
        $text = "Buy\nbuy_price:33000\nvalue:5000\nweight:2.3097\ncode:4630";

        $handler = new HandleLineMsg($text);
        $res = $handler->execute();

        $this->assertEquals(ResponseCode::OK, $res->code);
        $this->assertDatabaseCount('my_gold', 1);
        $this->assertDatabaseHas('my_gold', [
            'code' => '4630',
            'buy_price' => 33000,
            'value' => 5000,
            'weight' => 2.3097,
            'target_sell_price' => null,
            'target_baht_profit' => null,
            'sold' => false,
        ]);
    }

    // public function test_handle_line_msg_my_gold(): void
    // {
    //     Gold::factory()->create([
    //         'buy' => 33350,
    //     ]);
    //     MyGold::factory()->create([
    //         'buy_price' => 33370,
    //         'value' => 10000,
    //         'weight' => 4.5682
    //     ]);
    //     MyGold::factory()->create([
    //         'buy_price' => 33520,
    //         'value' => 5000,
    //         'weight' => 2.2739
    //     ]);
    //     $text = "my_gold";

    //     $handler = new HandleLineMsg($text);
    //     $res = $handler->execute();
    // }
}
