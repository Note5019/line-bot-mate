<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class PushLineMessage
{
  public function execute(string $altText, array $msgPayload, string $to = null): void
  {
    $to = $to ?? config('line.user_id');
    $token = config('line.line_channel_access_token');
    $payload = [
      'to' => $to,
      'messages' => [
        [
          "type" => "flex",
          "altText" => "this is a flex message",
          "contents" => $msgPayload,
        ]
      ]
    ];

    $response = Http::withHeaders([
      'Content-Type' => 'application/json',
      'Authorization' => "Bearer {$token}"
    ])->post('https://api.line.me/v2/bot/message/push', $payload);
    dump($response->status());
    if ($response->status() > 200) {
      \Log::error($response->json());
    }
  }
}
