<?php

namespace App\Actions;

use App\Actions\PushLineMessage;

class NotifyMessage
{
  public static function execute(string $topic, string $message): void
  {
    $msgPayload = [
      "type" => "text",
      "text" => "$topic, $message",
    ];

    PushLineMessage::execute($msgPayload);
    \Log::info('push msg!');
  }
}
