<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Actions\PushLineMessage;
use App\Actions\BuildGoldLineMessage;
use App\Actions\FetchGoldPrice;
use App\Actions\SaveGoldPrice;
use App\Models\Gold;
use App\Models\MyGold;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TestPushLineMsg extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:test-push-line-msg';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Execute the console command.
   */
  public function handle_old()
  {
    $bubble = [
      "type" => "bubble",
      "header" => [
        "type" => "box",
        "layout" => "vertical",
        "contents" => [
          [
            "type" => "text",
            "text" => "ราคาทองคำ",
            "size" => "lg"
          ]
        ],
        "backgroundColor" => "#80BCBD"
      ],
      "body" => [
        "type" => "box",
        "layout" => "vertical",
        "contents" => [
          [
            "type" => "box",
            "layout" => "horizontal",
            "contents" => [
              [
                "type" => "text",
                "text" => "ซื้อ"
              ],
              [
                "type" => "text",
                "align" => "end",
                "text" => "🔺"
              ],
              [
                "type" => "text",
                "text" => "33000",
                "contents" => [],
                "align" => "end"
              ]
            ]
          ],
          [
            "type" => "box",
            "layout" => "horizontal",
            "contents" => [
              [
                "type" => "text",
                "text" => "ขาย"
              ],
              [
                "type" => "text",
                "text" => "🔻",
                "align" => "end"
              ],
              [
                "type" => "text",
                "text" => "33000",
                "contents" => [],
                "align" => "end"
              ]
            ]
          ]
        ],
        "backgroundColor" => "#AAD9BB"
      ],
      "footer" => [
        "type" => "box",
        "layout" => "horizontal",
        "contents" => [
          [
            "type" => "text",
            "text" => "วันที่",
            "size" => "sm"
          ],
          [
            "type" => "text",
            "text" => "2023-01-03 22:22",
            "size" => "sm"
          ]
        ],
        "backgroundColor" => "#F9F7C9"
      ],
      "styles" => [
        "hero" => [
          "separator" => true
        ]
      ]
    ];
    $token = config('line.line_channel_access_token');
    $payload = [
      'to' => config('line.user_id'),
      'messages' => [
        // [
        //     "type" => "text",
        //     "text" => "Hello, world",
        // ],
        [
          "type" => "flex",
          "altText" => "this is a flex message",
          "contents" => $bubble
        ]

      ]
    ];
    $response = Http::withHeaders([
      'Content-Type' => 'application/json',
      'Authorization' => "Bearer {$token}"
    ])->post('https://api.line.me/v2/bot/message/push', $payload);

    dump($response->json());
  }

  public function handle()
  {
    $data = FetchGoldPrice::execute();
    $rawGold = collect($data)->filter(function ($item) {
      return $item['GoldType'] === Gold::GOLD_TYPE && $item['GoldCode'] === Gold::GOLD_CODE;
    })->first();

    $gold = SaveGoldPrice::execute($rawGold);

    // diff from myGold
    // $this->findGoldCanSell();



    // dump($gold);

    // $topic = "{$gold['GoldType']} {$gold['GoldCode']}%";
    // $buyPrice = $gold['Buy'];
    // $sellPrice = $gold['Sell'];
    // $date = $gold['StrTimeUpdate'];
    // $msg = BuildGoldLineMessage::execute($topic, $buyPrice, $sellPrice, $date);
    // dump(json_encode($msg));
    // $pusher = new PushLineMessage();
    // $altText = 'ราคาทองมาใหม่แล้ว';
    // $pusher->execute($altText, $msg);
  }

  // public function findGoldCanSell()
  // {
  //   $today = Carbon::now()->startOfDay();
  //   $currentGold = Gold::where('created_at', '>', $today)->latest()->first();
  //   // above target_sell_price
  //   $records = MyGold::whereNotNull('target_sell_price')->where('target_sell_price', '<=', $currentGold->buy)->get();
  //   // should notify

  //   // above target_baht_profit
  //   $records = DB::table('my_gold')
  //     ->whereRaw('? - buy_price > target_baht_profit', [$currentGold->buy])
  //     ->get();
  //     dump($currentGold->buy);
  //     dump($records);
  //     // should notify
  // }
}
