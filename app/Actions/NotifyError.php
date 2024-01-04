<?php

namespace App\Actions;

use App\Actions\PushLineMessage;

class NotifyError
{
  public static function execute(string $topic, string $message): void
  {
    $msgPayload = [
      "type" => "flex",
      "altText" => $topic,
      "contents" => self::message($topic, $message),
    ];

    PushLineMessage::execute($msgPayload);
    \Log::info('push msg!');
  }

  private static function message(string $topic, string $message): array
  {
    $json = '{"type":"bubble","body":{"type":"box","layout":"vertical","contents":[{"type":"text","text":"##topic","color":"#FCF5ED","size":"lg","margin":"none"},{"type":"separator","margin":"sm"},{"type":"text","text":"##error","color":"#FCF5ED","margin":"sm","size":"md"}]},"styles":{"header":{"separatorColor":"#BF3131","separator":true,"backgroundColor":"#7D0A0A"},"body":{"backgroundColor":"#CE5A67"}}}';
    $json = str_replace('##topic', $topic, $json);
    $json = str_replace('##error', $message, $json);

    return json_decode($json, true);
  }
}
