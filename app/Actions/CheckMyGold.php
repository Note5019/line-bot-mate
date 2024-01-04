<?php

namespace App\Actions;

use App\Models\Gold;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CheckMyGold
{
  public static function execute(Gold $currentGold): array
  {
    return DB::table('my_gold')
      ->where(function (Builder $query) use ($currentGold) {
        $query->whereNotNull('target_sell_price')
          ->where('target_sell_price', '<=', [$currentGold->buy]);
      })
      ->orWhere(function (Builder $query) use ($currentGold) {
        $query->whereNotNull('target_baht_profit')
          ->whereRaw('? - buy_price >= target_baht_profit', [$currentGold->buy]);
      })
      ->get()
      ->toArray();
  }
}
