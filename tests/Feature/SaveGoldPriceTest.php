<?php

namespace Tests\Feature;

use App\Actions\SaveGoldPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaveGoldPriceTest extends TestCase
{
    use RefreshDatabase;

    public function test_save_gold_price(): void
    {
        $data = [
            "GoldType" => "HSH",
            "GoldCode" => "96.50",
            "Buy" => "33,220",
            "Sell" => "33,270",
            "TimeUpdate" => "2024-01-04T00:57:07",
            "BuyChange" => 10.00,
            "SellChange" => 10.00,
            "PresentDate" => "",
            "FxAsk" => null,
            "FxBid" => null,
            "Bid" => null,
            "Ask" => null,
            "QtyBid" => null,
            "QtyAsk" => null,
            "Discount" => null,
            "Premium" => null,
            "Increment" => null,
            "SourcePrice" => null,
            "StrTimeUpdate" => "อัพเดตล่าสุด วันที่ 4 ม.ค. 2567 เวลา 00:57:07 น."
        ];

        SaveGoldPrice::execute($data);

        $this->assertDatabaseHas('gold', [
            'gold_type' => $data['GoldType'],
            'gold_code' => $data['GoldCode'],
            'buy' => str_replace(',', '', $data['Buy']),
            'sell' => str_replace(',', '', $data['Sell']),
            'buy_change' => $data['BuyChange'],
            'sell_change' => $data['SellChange'],
            'time_update' => $data['TimeUpdate'],
        ]);
    }
}
