<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class GetGoldPrice
{
  public static function execute(): array
  {
    $response = Http::withHeaders([
      'Accept' => 'application/json',
    ])->get('https://apicheckprice.huasengheng.com/api/values/getprice', []);

    if ($response->status() > 200) {
      \Log::error($response->json());
    }

    return $response->json();
  }
}
