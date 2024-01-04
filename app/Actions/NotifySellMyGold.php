<?php

namespace App\Actions;

use App\Models\Gold;

class NotifySellMyGold
{
  public static function execute(array $records, Gold $currentGold): void
  {
    $msgPayload = [];
    foreach ($records as $record) {
      $msgPayload += [
        [
          "type" => "flex",
          "altText" => 'มีทองน่าขาย',
          "contents" => self::message($record, $currentGold),
        ]
      ];
    }
    PushLineMessage::execute($msgPayload);
    \Log::info('push msg!');
  }

  private static function message($record, Gold $currentGold): array
  {
    $profit = (($currentGold->buy - $record->buy_price) / Gold::GOLD_WEIGHT) * $record->weight;
    $json = '{"type":"bubble","body":{"type":"box","layout":"vertical","contents":[{"type":"box","layout":"horizontal","contents":[{"type":"text","text":"มีทองน่าขาย"},{"type":"text","text":"##code","align":"end"}]},{"type":"box","layout":"horizontal","contents":[{"type":"text","text":"ราคาเริ่ม"},{"type":"text","text":"##buy_price","align":"end"}]},{"type":"box","layout":"horizontal","contents":[{"type":"text","text":"ราคาปัจจุบัน"},{"type":"text","text":"##current_buy_price","align":"end"}]},{"type":"box","layout":"horizontal","contents":[{"type":"text","text":"ต้นทุน"},{"type":"text","text":"##buy","align":"end"}]},{"type":"box","layout":"horizontal","contents":[{"type":"text","text":"กำไร"},{"type":"text","text":"##profit","align":"end"}]}]}}';
    $json = str_replace('##code', $record->code, $json);
    $json = str_replace('##buy_price', $record->buy_price, $json);
    $json = str_replace('##current_buy_price', $currentGold->buy, $json);
    $json = str_replace('##buy', $record->value, $json);
    $json = str_replace('##profit', $profit, $json);

    return json_decode($json, true);
  }
}
