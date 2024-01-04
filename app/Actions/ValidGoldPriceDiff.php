<?php

namespace App\Actions;

use App\Models\Gold;

class ValidGoldPriceDiff
{
  public static function execute(Gold $currentGold): bool
  {
    $latest = Gold::whereNot('id', $currentGold->id)->orderBy('id', 'desc')->first();
    dump($currentGold->toArray());
    dump($latest->toArray());
    return ($currentGold->buy - $latest->buy) > 0;
  }
}
