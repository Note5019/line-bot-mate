<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class PushLineMessage
{
  public static function execute(array $msgPayload, string $to = null): void
  {
    $to = $to ?? config('line.user_id');
    $token = config('line.line_channel_access_token');
    $payload = [
      'to' => $to,
      // 'messages' => [
      //   [
      //     "type" => "flex",
      //     "altText" => $altText,
      //     "contents" => $msgPayload,
      //   ]
      // ]
      'messages' => [$msgPayload],
    ];

    $response = Http::withHeaders([
      'Content-Type' => 'application/json',
      'Authorization' => "Bearer {$token}"
    ])->post('https://api.line.me/v2/bot/message/push', $payload);

    if ($response->status() > 200) {
      \Log::error($response->json());
      // TODO: line noti error
    }
  }
}
