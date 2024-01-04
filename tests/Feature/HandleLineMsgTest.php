<?php

namespace Tests\Feature;

use App\Actions\HandleLineMsg;
use App\Enums\ResponseCode;
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
}
