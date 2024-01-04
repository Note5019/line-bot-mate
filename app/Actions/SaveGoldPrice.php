<?php

namespace App\Actions;

use App\Models\Gold;

class SaveGoldPrice
{
  public static function execute($data): Gold
  {
    return Gold::create([
      'gold_type' => $data['GoldType'],
      'gold_code' => $data['GoldCode'],
      'buy' => self::convertStringToInt($data['Buy']),
      'sell' => self::convertStringToInt($data['Sell']),
      'buy_change' => self::convertStringToInt($data['BuyChange']),
      'sell_change' => self::convertStringToInt($data['SellChange']),
      'time_update' => $data['TimeUpdate'],
    ]);
  }

  private static function convertStringToInt($str):int
  {
    return str_replace(',', '', $str);
  }
}
