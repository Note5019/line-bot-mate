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
        $data = ["destination" => "U67083a98564e67bd1e62c8cfc6b799fc", "events" => [["type" => "message", "message" => ["type" => "text", "id" => "489135213419954273", "quoteToken" => "rp6qJOF2y0kCl27qx1H7NpqrSjd_omhcFjgAIPsfdLuyoULT71kd9TPQBjbZFFhVr5KOD2QMxg6fcP7_cdWt3Vp2oKBqnXGs18KrDIOqA1fvkoo1iLXYv8KpfFtQtzRHL-2cxGxsu10e6Q0YGqXTIA", "text" => $text], "webhookEventId" => "01HKAE3CXPEV6JKF157TBDDGFV", "deliveryContext" => ["isRedelivery" => false], "timestamp" => 1704378610435, "source" => ["type" => "user", "userId" => "Ufa70ef0a0e13d6281c421bf9b9820de1"], "replyToken" => "1862728564f243398bbd95c957ee3f13", "mode" => "active"]]];
        
        $handler = new HandleLineMsg($data);
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
