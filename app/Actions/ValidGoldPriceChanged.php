<?php

namespace App\Actions;

use App\Models\Gold;

class ValidGoldPriceChanged
{
  public static function execute(Gold $currentGold): bool
  {
    $latest = Gold::whereNot('id', $currentGold->id)->orderBy('id', 'desc')->first();

    if (!$latest) {
      return false;
    }

    return ($currentGold->buy - $latest->buy) > 0;
  }
}
