<?php

namespace Tests\Feature;

use App\Actions\FetchGoldPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchGoldTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_gold_price()
    {
        $fakeRes = [
            [
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
            ],
            [
                "GoldType" => "REF",
                "GoldCode" => "96.50",
                "Buy" => "33,400",
                "Sell" => "33,500",
                "TimeUpdate" => "2024-01-03T10:31:30",
                "BuyChange" => -50.00,
                "SellChange" => -50.00,
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
                "StrTimeUpdate" => "อัพเดตล่าสุด วันที่ 3 ม.ค. 2567 เวลา 10:31:30 น."
            ],
            [
                "GoldType" => "JEWEL",
                "GoldCode" => "96.50",
                "Buy" => "32,806.24",
                "Sell" => "34,000",
                "TimeUpdate" => "2024-01-04T00:58:44",
                "BuyChange" => 0.00,
                "SellChange" => 0.00,
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
                "StrTimeUpdate" => "อัพเดตล่าสุด วันที่ 4 ม.ค. 2567 เวลา 00:58:44 น."
            ]
        ];
        Http::fake(function (Request $request) use ($fakeRes) {
            return Http::response($fakeRes, 200);
        });
        $data = FetchGoldPrice::execute();
        $this->assertEquals(json_encode($fakeRes), json_encode($data));
    }
}
